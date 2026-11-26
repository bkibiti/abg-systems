<?php

namespace App\Http\Controllers;

use App\Models\AccExpenseCategory;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\PaymentMethod;
use App\Models\Sale;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    /**
     * Phone "History" tab: sales and expenses mixed into one newest-first feed, grouped by day.
     * Reuses the same date-range filtering as sales.history/expense.index (this month by default)
     * and the same visibility rules (Sale::history has no owner restriction; Expense::visibleTo
     * limits a user without "View Advanced Dashboard" to the expenses they recorded).
     */
    public function index(Request $request)
    {
        [$fromDate, $toDate] = $this->historyDateRange($request);
        $user = Auth::user();
        $type = in_array($request->query('type'), ['sales', 'expenses'], true) ? $request->query('type') : 'all';

        $sales = collect();
        $canViewSalesHistory = $user->can('View Sales History');
        $canDeleteAnySale = $user->can('Delete Sales');
        if ($type !== 'expenses' && $canViewSalesHistory) {
            $sales = Sale::with(['product', 'customer', 'paymentMethod'])
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get()
                ->map(fn (Sale $sale): array => [
                    'kind' => 'sale',
                    'id' => $sale->id,
                    'date' => CarbonImmutable::parse($sale->created_at)->setTimezone('Africa/Dar_es_Salaam'),
                    'title' => $sale->product->name ?? '(deleted product)',
                    'sub' => formatAmount($sale->quantity).' pcs · '.($sale->customer->name ?? 'Walk-in').' · '.($sale->paymentMethod->name ?? '—'),
                    'amount' => (float) $sale->quantity * (float) $sale->selling_price,
                    'canDeleteThis' => $sale->isDeletableBy($user->id, $canDeleteAnySale),
                    'sale' => $sale,
                ]);
        }

        $expenses = collect();
        $canSeeExpenses = $user->canAny(['View Expense Management', 'View Expenses', 'Add Expenses', 'Edit Expenses']);
        if ($type !== 'sales' && $canSeeExpenses) {
            $expenses = Expense::visibleTo($user)
                ->with('category')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get()
                ->map(fn (Expense $expense): array => [
                    'kind' => 'expense',
                    'id' => $expense->id,
                    'date' => CarbonImmutable::parse($expense->created_at)->setTimezone('Africa/Dar_es_Salaam'),
                    'title' => $expense->expense_description ?: ($expense->category->name ?? 'Expense'),
                    'sub' => $expense->category->name ?? '—',
                    'amount' => (float) $expense->amount,
                    'expense' => $expense,
                ]);
        }

        $entries = $sales->concat($expenses)->sortByDesc(fn (array $row) => $row['date'])->values();

        $groups = $entries->groupBy(fn (array $row): string => $row['date']->toDateString())
            ->map(function ($rows, string $dateKey) {
                $date = CarbonImmutable::parse($dateKey);
                $label = $date->isToday() ? 'Today' : ($date->isYesterday() ? 'Yesterday' : $date->format('D j M'));

                return ['label' => $label, 'rows' => $rows];
            });

        $total = $entries->sum('amount');

        $customers = Customer::select('id', 'name')->where('status', 'Active')->orderBy('name')->get();
        $paymentMethods = PaymentMethod::orderBy('id')->get();
        $expense_category = AccExpenseCategory::all();

        return view('history.index', [
            'groups' => $groups,
            'total' => $total,
            'type' => $type,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'canViewSalesHistory' => $canViewSalesHistory,
            'canSeeExpenses' => $canSeeExpenses,
            'customers' => $customers,
            'paymentMethods' => $paymentMethods,
            'expense_category' => $expense_category,
        ]);
    }
}
