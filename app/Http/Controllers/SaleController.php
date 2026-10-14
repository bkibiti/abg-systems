<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        // Products with stock, plus all services (services have no stock)
        $current_stock = Stock::where(function ($q) {
            $q->where('quantity', '>', 0)->orWhere('type', Stock::TYPE_SERVICE);
        })->orderBy('name')->get();

        $users = User::permission('POS Users')->get();
        $customers = Customer::orderBy('name', 'ASC')->get();

        return view('sales.pos', compact('current_stock', 'customers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_order' => 'required',
        ]);

        // Each order line: [id, qoh, name, qty, price, subtotal, unit_cost, type, total]
        $lines = json_decode($request->sale_order, true);

        if (! is_array($lines) || count($lines) === 0) {
            session()->flash('alert-danger', 'Add at least one item or service to the order.');

            return back();
        }

        if ($request->sale_date == 'NA' || empty($request->sale_date)) {
            $sale_date = Carbon::now();
        } else {
            $sale_date = date('Y-m-d', strtotime($request->sale_date));
        }

        // Validate every line against the database before saving anything
        $order_details = [];
        $stockChanges = [];

        foreach ($lines as $line) {
            $stock = Stock::find($line[0] ?? null);
            $qty = (int) ($line[3] ?? 0);

            if (! $stock || $qty < 1) {
                session()->flash('alert-danger', 'Invalid line in the order. Please check and try again.');

                return back();
            }

            if ($stock->isService()) {
                // Service: quantity and TOTAL cost are entered at the POS
                $total = (float) str_replace(',', '', $line[8] ?? $line[5] ?? 0);
                if ($total <= 0) {
                    session()->flash('alert-danger', "Enter the total cost for '{$stock->name}'.");

                    return back();
                }
                $sellingPrice = round($total / $qty, 4);
                $buyingPrice = 0;
            } else {
                // Item: price always comes from the stock table
                if (! $stock->sale_price_1 || $stock->sale_price_1 <= 0) {
                    session()->flash('alert-danger', "'{$stock->name}' has no selling price. Set it in Masters > Products.");

                    return back();
                }
                $alreadyInOrder = $stockChanges[$stock->id] ?? 0;
                if ($qty + $alreadyInOrder > $stock->quantity) {
                    session()->flash('alert-danger', "Only {$stock->quantity} of '{$stock->name}' in stock.");

                    return back();
                }
                $sellingPrice = $stock->sale_price_1;
                $buyingPrice = $stock->unit_cost ?? 0;
                $stockChanges[$stock->id] = $alreadyInOrder + $qty;
            }

            $order_details[] = [
                'customer_id' => $request->customer_id,
                'stock_id' => $stock->id,
                'quantity' => $qty,
                'buying_price' => $buyingPrice,
                'selling_price' => $sellingPrice,
                'created_by' => Auth::User()->id,
                'created_at' => $sale_date,
                'updated_at' => Carbon::now(),
                'status' => '1',
            ];
        }

        DB::transaction(function () use (&$order_details, $stockChanges) {
            $receiptNo = $this->generateReceiptNo();
            foreach ($order_details as &$row) {
                $row['receipt_number'] = $receiptNo;
            }
            unset($row);

            DB::table('sales')->insert($order_details);

            // Deduct sold items from stock (services are not stocked)
            foreach ($stockChanges as $stockId => $soldQty) {
                Stock::where('id', $stockId)->decrement('quantity', $soldQty);
            }
        });

        session()->flash('alert-success', 'Sale recorded successfully!');

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

    public function history()
    {
        $sales = Sale::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();
        $products = Stock::select('id', 'name')->orderBy('name')->get();

        $total = 0;
        foreach ($sales as $s) {
            $total = $total + ($s->quantity * $s->selling_price);
        }

        return view('sales.history', compact('sales', 'total', 'products'));
    }

    public function historySearch(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $sales = Sale::whereRaw("date(created_at) between '".$from."' and '".$to."'")
            ->where('stock_id', 'like', '%'.$request->product.'%')
            ->get();

        $total = 0;
        foreach ($sales as $s) {
            $total = $total + ($s->quantity * $s->selling_price);
        }

        $products = Stock::select('id', 'name')->orderBy('name')->get();
        $request->flash();

        return view('sales.history', compact('sales', 'total', 'products'));
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
