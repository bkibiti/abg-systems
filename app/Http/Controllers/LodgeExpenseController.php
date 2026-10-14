<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LodgeExpense;
use App\Models\AccExpenseCategory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LodgeExpenseController extends Controller
{
    public function index()
    {
        $expense_category = AccExpenseCategory::all();
        $Expenses = LodgeExpense::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();
        
        $total = 0;
        foreach ($Expenses as $x) {
            $total = $total + $x->amount;
        }
        return view('lodge.expense.index', compact('expense_category','Expenses','total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description'=> 'max:200',
        ]);

        $expense = new LodgeExpense;
        $expense->amount = $request->expense_amount;
        $expense->expense_category_id = $request->expense_category;
        $expense->expense_description = $request->expense_description;
        $expense->created_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_by = Auth::user()->id;
        $expense->save();

        session()->flash("alert-success", "Expense added successfully!");
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description'=> 'max:200',
        ]);

        $expense = LodgeExpense::findOrFail($request->id);
        $expense->amount = $request->expense_amount;
        $expense->expense_category_id = $request->expense_category;
        $expense->expense_description = $request->expense_description;
        $expense->updated_at = Carbon::now();
        $expense->created_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_by = Auth::user()->id;
        $expense->save();

        session()->flash("alert-success", "Expense updated successfully!");
        return back();
    }

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        if($request->expense_category == 0){
            $Expenses = LodgeExpense::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();
        }
        else{
            $Expenses = LodgeExpense::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
                    ->where('expense_category_id',$request->expense_category)
                    ->get();
        }

        $expense_category = AccExpenseCategory::all();
      
        $total = 0;
        foreach ($Expenses as $x) {
            $total = $total + $x->amount;
        }
        $request->flash();
        return view('lodge.expense.index', compact('expense_category','Expenses','total'));
    }
}
