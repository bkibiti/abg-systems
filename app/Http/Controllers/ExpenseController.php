<?php

namespace App\Http\Controllers;

use App\Models\AccExpenseCategory;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Expenses, filtered by date range (this month by default) and optionally by category.
     */
    public function index(Request $request)
    {
        [$fromDate, $toDate] = $this->historyDateRange($request);

        $expense_category = AccExpenseCategory::all();
        $Expenses = Expense::with(['category', 'user'])
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->when($request->filled('expense_category') && $request->expense_category != 0, fn ($q) => $q->where('expense_category_id', $request->expense_category))
            ->get();

        $total = $Expenses->sum('amount');

        return view('expense.index', compact('expense_category', 'Expenses', 'total', 'fromDate', 'toDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description' => 'max:200',
        ]);

        $expense = new Expense;
        $expense->amount = $request->expense_amount;
        $expense->expense_category_id = $request->expense_category;
        $expense->expense_description = $request->expense_description;
        $expense->created_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_by = Auth::user()->id;
        $expense->save();

        session()->flash('alert-success', 'Expense added successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description' => 'max:200',
        ]);

        $expense = Expense::findOrFail($request->id);
        $expense->amount = $request->expense_amount;
        $expense->expense_category_id = $request->expense_category;
        $expense->expense_description = $request->expense_description;
        $expense->updated_at = Carbon::now();
        $expense->created_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->updated_by = Auth::user()->id;
        $expense->save();

        session()->flash('alert-success', 'Expense updated successfully!');

        return back();
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}
