<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LodgeSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LodgeSalesController extends Controller
{
    public function index()
    {
        $sales = LodgeSale::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();
        
        $total = 0;
        foreach ($sales as $x) {
            $total = $total + $x->amount;
        }
        return view('lodge.sales.index', compact('sales','total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description'=> 'max:200',
        ]);

        $sales = new LodgeSale;
        $sales->amount = $request->expense_amount;
        $sales->description = $request->expense_description;
        $sales->created_at = date('Y-m-d', strtotime($request->expense_date));
        $sales->updated_at = date('Y-m-d', strtotime($request->expense_date));
        $sales->updated_by = Auth::user()->id;
        $sales->save();

        session()->flash("alert-success", "Sales added successfully!");
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description'=> 'max:200',
        ]);

        $sales = LodgeSale::findOrFail($request->id);
        $sales->amount = $request->expense_amount;
        $sales->description = $request->expense_description;
        $sales->updated_at = Carbon::now();
        $sales->created_at = date('Y-m-d', strtotime($request->expense_date));
        $sales->updated_by = Auth::user()->id;
        $sales->save();

        session()->flash("alert-success", "Sales updated successfully!");
        return back();
    }

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $sales = LodgeSale::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();
      
        $total = 0;
        foreach ($sales as $x) {
            $total = $total + $x->amount;
        }
        $request->flash();
        return view('lodge.sales.index', compact('sales','total'));
    }
}
