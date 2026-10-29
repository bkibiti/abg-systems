<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\SaleDeletion;
use App\Models\Stock;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    private const LOCAL_TIMEZONE = 'Africa/Dar_es_Salaam';

    /**
     * New sale screen: one product line per sale, plus the user's sales today.
     */
    public function index()
    {
        // Items with stock, plus all services (services have no stock)
        $products = Stock::select('id', 'name', 'type', 'sale_price_1', 'quantity')
            ->where(function ($q) {
                $q->where('quantity', '>', 0)->orWhere('type', Stock::TYPE_SERVICE);
            })
            ->servicesFirst()
            ->get();

        $customers = Customer::select('id', 'name')->where('status', 'Active')->orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('id')->get();

        $todayStart = CarbonImmutable::now(self::LOCAL_TIMEZONE)->startOfDay()->setTimezone('UTC');

        $todaySales = Sale::with(['product:id,name', 'customer:id,name'])
            ->where('created_by', Auth::id())
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<', $todayStart->addDay())
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $todayCount = $todaySales->count();
        $todayTotal = $todaySales->sum(fn (Sale $sale): float => (float) $sale->quantity * (float) $sale->selling_price);

        $lastSale = session('last_sale');
        $canSelectSaleDate = getSettings('enable_sale_date_select') === 'YES';
        $todayDate = CarbonImmutable::now(self::LOCAL_TIMEZONE)->toDateString();

        return view('sales.pos', compact(
            'products',
            'customers',
            'paymentMethods',
            'todaySales',
            'todayCount',
            'todayTotal',
            'lastSale',
            'canSelectSaleDate',
            'todayDate',
        ));
    }

    /**
     * Save one sale line. Prices for items always come from the stock table.
     */
    public function store(Request $request)
    {
        $request->merge(['total' => str_replace(',', '', (string) $request->total) ?: null]);

        $todayDate = CarbonImmutable::now(self::LOCAL_TIMEZONE)->toDateString();

        $request->validate([
            'product_id' => 'required|integer|exists:stock,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'nullable|numeric|gt:0',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'payment_method_id' => 'required|integer|exists:payment_method,id',
            'sale_date' => 'nullable|date_format:Y-m-d|before_or_equal:'.$todayDate,
        ], [
            'product_id.required' => 'Select a product or service.',
            'quantity.min' => 'Quantity must be at least 1.',
            'payment_method_id.required' => 'Select a payment method.',
            'sale_date.before_or_equal' => 'The sale date cannot be in the future.',
        ]);

        // A past date is only used when the admin has enabled date selection in Settings
        $saleDate = Carbon::now();
        if (getSettings('enable_sale_date_select') === 'YES' && filled($request->sale_date) && $request->sale_date !== $todayDate) {
            $saleDate = Carbon::parse($request->sale_date.' 12:00', self::LOCAL_TIMEZONE)->setTimezone('UTC');
        }

        $quantity = (int) $request->quantity;

        $sale = DB::transaction(function () use ($request, $quantity, $saleDate) {
            $stock = Stock::whereKey($request->product_id)->lockForUpdate()->first();

            if ($stock->isService()) {
                if (blank($request->total)) {
                    throw ValidationException::withMessages(['total' => 'Enter the total amount for this service.']);
                }
                $sellingPrice = round((float) $request->total / $quantity, 4);
                $buyingPrice = 0;
            } else {
                if (! $stock->sale_price_1 || $stock->sale_price_1 <= 0) {
                    throw ValidationException::withMessages(['product_id' => "'{$stock->name}' has no selling price. Set it in Masters > Products."]);
                }
                if ($quantity > $stock->quantity) {
                    throw ValidationException::withMessages(['quantity' => 'Only '.formatAmount($stock->quantity)." of '{$stock->name}' in stock."]);
                }
                $sellingPrice = $stock->sale_price_1;
                $buyingPrice = $stock->unit_cost ?? 0;
            }

            $sale = new Sale;
            $sale->receipt_number = $this->generateReceiptNo();
            $sale->customer_id = $request->customer_id;
            $sale->stock_id = $stock->id;
            $sale->quantity = $quantity;
            $sale->buying_price = $buyingPrice;
            $sale->selling_price = $sellingPrice;
            $sale->payment_method_id = $request->payment_method_id;
            $sale->created_by = Auth::id();
            $sale->created_at = $saleDate;
            $sale->updated_at = Carbon::now();
            $sale->status = 1;
            $sale->save();

            // Services are not stocked
            if (! $stock->isService()) {
                Stock::where('id', $stock->id)->decrement('quantity', $quantity);
            }

            return $sale;
        });

        $sale->load(['product:id,name', 'customer:id,name', 'paymentMethod']);

        session()->flash('last_sale', [
            'receipt_number' => $sale->receipt_number,
            'product' => $sale->product->name,
            'quantity' => $sale->quantity,
            'customer' => $sale->customer->name ?? 'Walk-in',
            'payment_method' => $sale->paymentMethod->name,
            'amount' => (float) $sale->quantity * (float) $sale->selling_price,
            'time' => CarbonImmutable::parse($sale->created_at)->setTimezone(self::LOCAL_TIMEZONE)->format('j M Y, H:i'),
        ]);

        return back();
    }

    /**
     * Update an existing sale line. Mirrors store(): never accepts buying_price or
     * selling_price from the request, items keep the price they were sold at.
     */
    public function update(Request $request)
    {
        $request->merge(['total' => str_replace(',', '', (string) $request->total) ?: null]);

        $todayDate = CarbonImmutable::now(self::LOCAL_TIMEZONE)->toDateString();

        $request->validate([
            'quantity' => 'required|numeric|gt:0',
            'total' => 'nullable|numeric|gt:0',
            'date' => 'required|date|before_or_equal:'.$todayDate,
            'customer_id' => 'nullable|integer|exists:customers,id',
            'payment_method_id' => 'required|integer|exists:payment_method,id',
        ], [
            'date.before_or_equal' => 'The sale date cannot be in the future.',
            'payment_method_id.required' => 'Select a payment method.',
        ]);

        $quantity = (int) $request->quantity;
        $saleDate = Carbon::parse($request->date.' 12:00', self::LOCAL_TIMEZONE)->setTimezone('UTC');

        DB::transaction(function () use ($request, $quantity, $saleDate) {
            $sale = Sale::whereKey($request->id)->lockForUpdate()->firstOrFail();
            $stock = $sale->stock_id ? Stock::whereKey($sale->stock_id)->lockForUpdate()->first() : null;

            if ($stock && $stock->isService()) {
                if (blank($request->total)) {
                    throw ValidationException::withMessages(['total' => 'Enter the total amount for this service.']);
                }
                $sale->selling_price = round((float) $request->total / $quantity, 4);
            } elseif ($stock) {
                $difference = $sale->quantity - $quantity;
                if ($difference < 0 && abs($difference) > $stock->quantity) {
                    throw ValidationException::withMessages(['quantity' => 'Only '.formatAmount($stock->quantity)." of '{$stock->name}' in stock."]);
                }
                Stock::whereKey($stock->id)->increment('quantity', $difference);
            }

            $sale->quantity = $quantity;
            $sale->customer_id = $request->customer_id;
            $sale->payment_method_id = $request->payment_method_id;
            $sale->created_at = $saleDate;
            $sale->updated_at = Carbon::now();
            $sale->updated_by = Auth::id();
            $sale->save();
        });

        session()->flash('alert-success', 'Sale updated.');

        return back();
    }

    /**
     * Sales history, filtered by date range (this month by default) and optionally by product.
     */
    public function history(Request $request)
    {
        [$fromDate, $toDate] = $this->historyDateRange($request);

        $sales = Sale::with(['product', 'user', 'updater', 'customer', 'paymentMethod'])
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->when($request->filled('product'), fn ($q) => $q->where('stock_id', $request->product))
            ->get();
        $products = Stock::select('id', 'name', 'type')->servicesFirst()->get();
        $customers = Customer::select('id', 'name')->where('status', 'Active')->orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('id')->get();
        $canDeleteAny = Auth::user()->can('Delete Sales');

        $total = $sales->sum(fn (Sale $sale): float => (float) $sale->quantity * (float) $sale->selling_price);

        return view('sales.history', compact('sales', 'total', 'products', 'customers', 'paymentMethods', 'canDeleteAny', 'fromDate', 'toDate'));
    }

    public function historySearch(Request $request)
    {
        return $this->history($request);
    }

    /**
     * Delete one sale line. Anyone with "Delete Sales" can delete any sale;
     * everyone else can only delete their own sale, on the day it was recorded.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:sales,id',
            'reason' => 'required|string|min:3|max:255',
        ]);

        $sale = Sale::findOrFail($request->id);
        $canDeleteAny = Auth::user()->can('Delete Sales');

        if (! $sale->isDeletableBy(Auth::id(), $canDeleteAny)) {
            abort(403);
        }

        $stockRestored = false;

        DB::transaction(function () use ($request, &$stockRestored) {
            $sale = Sale::whereKey($request->id)->lockForUpdate()->firstOrFail();
            $stock = $sale->stock_id ? Stock::whereKey($sale->stock_id)->lockForUpdate()->first() : null;

            if ($stock && ! $stock->isService()) {
                Stock::whereKey($stock->id)->increment('quantity', $sale->quantity);
                $stockRestored = true;
            }

            $deletion = new SaleDeletion;
            $deletion->sale_id = $sale->id;
            $deletion->receipt_number = $sale->receipt_number;
            $deletion->stock_id = $sale->stock_id;
            $deletion->product_name = $stock?->name;
            $deletion->quantity = $sale->quantity;
            $deletion->selling_price = $sale->selling_price;
            $deletion->amount = (float) $sale->quantity * (float) $sale->selling_price;
            $deletion->sale_date = $sale->created_at;
            $deletion->customer_id = $sale->customer_id;
            $deletion->payment_method_id = $sale->payment_method_id;
            $deletion->sale_created_by = $sale->created_by;
            $deletion->reason = $request->reason;
            $deletion->deleted_by = Auth::id();
            $deletion->deleted_at = Carbon::now();
            $deletion->save();

            $sale->delete();
        });

        session()->flash('alert-success', $stockRestored ? 'Sale deleted. Stock restored.' : 'Sale deleted.');

        return back();
    }

    /**
     * Read-only log of deleted sales. Gated by the "Delete Sales" permission at the route level.
     */
    public function deletions()
    {
        $deletions = SaleDeletion::with(['deleter', 'saleCreator'])
            ->orderByDesc('deleted_at')
            ->get();

        return view('sales.deletions', compact('deletions'));
    }

    public function generateReceiptNo()
    {
        $maxID = Sale::latest()->value('id');
        if ($maxID == '') {
            $maxID = 1000000;
        } else {
            $maxID = $maxID + 1;
        }
        $no = 'ABG'.$maxID;

        return $no;
    }
}
