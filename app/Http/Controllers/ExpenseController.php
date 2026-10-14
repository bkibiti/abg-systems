<?php

namespace App\Http\Controllers;

use App\Models\AccExpenseCategory;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{

    public function index()
    {
        $expense_category = AccExpenseCategory::all();
        $Expenses = Expense::whereRaw('month(created_at) = month(now()) and year(created_at)=year(now())')->get();
        
        $total = 0;
        foreach ($Expenses as $x) {
            $total = $total + $x->amount;
        }
        return view('expense.index', compact('expense_category','Expenses','total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description'=> 'max:200',
        ]);

        $expense = new Expense;
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

        $expense = Expense::findOrFail($request->id);
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
            $Expenses = Expense::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")->get();
        }
        else{
            $Expenses = Expense::whereRaw("date(created_at) between '". $from . "' and '". $to ."'")
                    ->where('expense_category_id',$request->expense_category)
                    ->get();
        }

        $expense_category = AccExpenseCategory::all();
      
        $total = 0;
        foreach ($Expenses as $x) {
            $total = $total + $x->amount;
        }
        $request->flash();
        return view('expense.index', compact('expense_category','Expenses','total'));
    }



}
