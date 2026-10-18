<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Stock;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        $products = Stock::goods()->orderBy('name')->get();

        return View('purchases.goods_receiving', (compact('suppliers', 'products')));
    }

    public function store(Request $request)
    {
        if ($request->purchase_date == 'NA') {
            $purchase_date = Carbon::now();
        } else {
            $purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        }

        // Products are bought and stocked in the same unit (no purchase unit conversion).
        $totalQty = $request->quantity;
        $UnitBuyingPrice = $request->unit_cost;

        $incoming = new Purchase;
        $incoming->product_id = $request->id;
        $incoming->quantity = $request->quantity;
        $incoming->supplier_id = $request->supplier;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_by = Auth::User()->id;
        $incoming->created_at = $purchase_date;
        $incoming->updated_at = Carbon::now();
        $incoming->save();

        $stock = Stock::find($request->id);
        $unit_cost = ($stock->quantity * $stock->unit_cost + $totalQty * $UnitBuyingPrice) / ($totalQty + $stock->quantity);
        $stock->quantity = $stock->quantity + $totalQty;
        $stock->unit_cost = $unit_cost;
        $stock->save();
        $request->flash();

        session()->flash('alert-success', 'Purchase saved successfully!');

        return back();
    }

    public function update(Request $request)
    {

        $purchase_date = date('Y-m-d', strtotime($request->created_at));

        $incoming = Purchase::find($request->id);
        $incoming->quantity = $request->quantity;
        $incoming->supplier_id = $request->supplier_id;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_at = $purchase_date;
        $incoming->updated_at = Carbon::now();
        $incoming->save();

        session()->flash('alert-success', 'Purchase updated successfully!');

        return back();
    }

    /**
     * Purchase history, filtered by date range (this month by default) and optionally by supplier and product.
     */
    public function history(Request $request)
    {
        [$fromDate, $toDate] = $this->historyDateRange($request);

        $purchases = Purchase::with(['product', 'supplier', 'user'])
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->when($request->filled('supplier') && $request->supplier != 0, fn ($q) => $q->where('supplier_id', $request->supplier))
            ->when($request->filled('product'), fn ($q) => $q->where('product_id', $request->product))
            ->get();

        $total = $purchases->sum(fn (Purchase $purchase): float => (float) $purchase->quantity * (float) $purchase->unit_cost);

        $suppliers = Supplier::all();
        $products = Stock::select('id', 'name', 'type')->servicesFirst()->get();

        return view('purchases.history', compact('purchases', 'total', 'suppliers', 'products', 'fromDate', 'toDate'));
    }

    public function search(Request $request)
    {
        return $this->history($request);
    }

    public function itemHistory(Request $request)
    {
        $history = Purchase::where('product_id', $request->prod_id)
            ->orderBy('id', 'desc')
            ->limit('7')
            ->get();

        $data = [];
        $prevPrice = [];
        foreach ($history as $h) {
            $data['date'] = date_format($h->created_at, 'd M Y');
            $data['cost'] = $h->unit_cost;
            $data['supplier'] = $h->supplier->name;
            $prevPrice[] = $data;
        }

        return $history;
    }
}
