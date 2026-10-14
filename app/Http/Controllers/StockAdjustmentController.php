<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::whereRaw('month(created_at) = month(now())')->get();
        return view('inventory.stock_adjustment_history', compact("adjustments"));

    }
    public function store(Request $request)
    {

        $original_qty = $request->quantity;
        $adjusted_qty = $request->qnty;

        if ($request->type == 'Positive'){
            $remain_qty = $original_qty + $adjusted_qty;
        }
        if ($request->type == 'Negative'){
            $remain_qty = $original_qty - $adjusted_qty;
        }

        if ($remain_qty < 0 ){
            session()->flash("alert-danger", "You can not deduct more that available quantity!");
            return back();
        }

        $product = Stock::find($request->id);
        $product->quantity = $remain_qty;
        $product->save();

        //insert into adjustment table
        $adjust = new StockAdjustment;
        $adjust->stock_id = $request->id;
        $adjust->original_qty = $original_qty;
        $adjust->adjusted_qty = $adjusted_qty;
        $adjust->remain_qty = $remain_qty;
        $adjust->adjustment_type = $request->type;
        $adjust->reason = $request->reason;
        $adjust->created_by = Auth::user()->id;
        $adjust->save();
     
        session()->flash("alert-success", "Stock Adjusted Successfully!");
        return back();
    }

   

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $adjustments = StockAdjustment::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();

        $request->flash();
        return view('inventory.stock_adjustment_history', compact("adjustments"));
    }


   
}
