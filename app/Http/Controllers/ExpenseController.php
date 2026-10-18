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
     * Users without "View Advanced Dashboard" only see the expenses they recorded.
     */
    public function index(Request $request)
    {
        [$fromDate, $toDate] = $this->historyDateRange($request);

        $expense_category = AccExpenseCategory::all();
        $Expenses = Expense::visibleTo(Auth::user())
            ->with(['category', 'user'])
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
        $expense = Expense::findOrFail($request->id);
        $this->authorizeExpense($expense);

        $request->validate([
            'expense_date' => 'required|date',
            'expense_amount' => 'required',
            'expense_description' => 'max:200',
        ]);

        // updated_by records who entered the expense (it decides who can see it), so editing keeps it unchanged
        $expense->amount = $request->expense_amount;
        $expense->expense_category_id = $request->expense_category;
        $expense->expense_description = $request->expense_description;
        $expense->updated_at = Carbon::now();
        $expense->created_at = date('Y-m-d', strtotime($request->expense_date));
        $expense->save();

        session()->flash('alert-success', 'Expense updated successfully!');

        return back();
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Stop a user without "View Advanced Dashboard" from working on an expense someone else recorded.
     */
    private function authorizeExpense(Expense $expense): void
    {
        abort_unless($expense->isVisibleTo(Auth::user()), 403, 'You can only change expenses you recorded.');
    }
}
