<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Supplier;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::all();
        $products = Stock::goods()->orderBy('name')->get();

        return View('purchases.goods_receiving', (compact( 'suppliers','products')));
    }


    public function store(Request $request){
        if($request->purchase_date =='NA'){
            $purchase_date = Carbon::now();
        }else{
            $purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        }

        // Products are bought and stocked in the same unit (no purchase unit conversion).
        $totalQty = $request->quantity;
        $UnitBuyingPrice = $request->unit_cost;

        $incoming = new purchase;
        $incoming->product_id = $request->id;
        $incoming->quantity = $request->quantity;
        $incoming->quantity_per_unit = 1;
        $incoming->supplier_id = $request->supplier;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_by = Auth::User()->id;
        $incoming->created_at = $purchase_date;
        $incoming->updated_at = Carbon::now();
        $incoming->save();

        $stock = Stock::find($request->id);
        $unit_cost = ($stock->quantity * $stock->unit_cost + $totalQty * $UnitBuyingPrice)/($totalQty + $stock->quantity);
        $stock->quantity = $stock->quantity + $totalQty;
        $stock->unit_cost = $unit_cost;
        $stock->save();
        $request->flash();

        session()->flash("alert-success", "Purchase saved successfully!");
        return back();
    }


    public function update(Request $request){

        $purchase_date = date('Y-m-d', strtotime($request->created_at));

        $incoming = Purchase::find($request->id);
        $incoming->quantity = $request->quantity;
        $incoming->supplier_id = $request->supplier_id;
        $incoming->unit_cost = $request->unit_cost;
        $incoming->created_at = $purchase_date;
        $incoming->updated_at = Carbon::now();
        $incoming->save();

        session()->flash("alert-success", "Purchase updated successfully!");
        return back();
    }


    public function history(){
        $now = Carbon::now();
        $purchases = Purchase::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();

        $total = 0;
        foreach ($purchases as $p) {
            $total = $total + ($p->quantity * $p->unit_cost);
        }

        $suppliers = Supplier::all();
        $products = Stock::select('id','name')->orderBy('name')->get();

        return view('purchases.history', compact("purchases","total",'suppliers','products'));
    }

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        if($request->supplier == 0){
            $purchases = Purchase::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
                        ->where('product_id','like','%'. $request->product. '%')
                        ->get();
        }else{
            $purchases = Purchase::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
                        ->where('product_id','like','%'. $request->product. '%')
                        ->where('supplier_id',$request->supplier)
                        ->get();
        }

        $total = 0;
        foreach ($purchases as $p) {
            $total = $total + ($p->quantity * $p->unit_cost);
        }

        $suppliers = Supplier::all();
        $products = Stock::select('id','name')->orderBy('name')->get();
        $request->flash();

        return view('purchases.history', compact('purchases','total','suppliers','products'));
    }

    public function itemHistory(Request $request)
    {
         $history = Purchase::where('product_id', $request->prod_id)
                ->orderBy('id','desc')
                ->limit('7')
                ->get();

        $data = [];
        $prevPrice = [];
        foreach ($history as $h) {
            $data['date'] = date_format($h->created_at,'d M Y');
            $data['cost'] = $h->unit_cost;
            $data['supplier'] = $h->supplier->name;
            $prevPrice[]= $data;
        }
        return $history;
    }



}


