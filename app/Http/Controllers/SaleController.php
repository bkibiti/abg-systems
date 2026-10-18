<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Sale;
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

    public function update(Request $request)
    {

        $request->validate([
            'created_at' => 'required',
            'selling_price' => 'required',
            'quantity' => 'required',
            'buying_price' => 'required',
            'remarks' => 'required',
        ]);

        $Sale = Sale::findOrFail($request->id);

        if ($Sale->quantity != $request->quantity) {
            $remarks = $request->remarks.' (Qty from: '.$Sale->quantity.' to: '.$request->quantity;
        } else {
            $remarks = $request->remarks;
        }
        $Sale->quantity = $request->quantity;
        $Sale->selling_price = $request->selling_price;
        $Sale->buying_price = $request->buying_price;
        $Sale->created_at = date('Y-m-d', strtotime($request->created_at));
        $Sale->updated_at = Carbon::now();
        $Sale->updated_by = Auth::user()->id;
        $Sale->remarks = $remarks;
        $Sale->save();

        session()->flash('alert-success', 'Sale updated successfully!');

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

        $total = $sales->sum(fn (Sale $sale): float => (float) $sale->quantity * (float) $sale->selling_price);

        return view('sales.history', compact('sales', 'total', 'products', 'fromDate', 'toDate'));
    }

    public function historySearch(Request $request)
    {
        return $this->history($request);
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
