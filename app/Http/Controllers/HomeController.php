<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Sale;
use App\Services\CashService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Dashboard periods selectable from the header, keyed by their ?period= value.
     *
     * @var array<string, string>
     */
    private const PERIODS = [
        'today' => 'Today',
        'month' => 'This month',
        '6m' => '6 months',
        '12m' => '12 months',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private CashService $cashService)
    {
        $this->middleware('auth');
    }

    // login form
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show the owner dashboard for the period chosen in the query string.
     */
    public function index(Request $request): Renderable
    {
        if (! auth()->user()->can('View Advanced Dashboard')) {
            return $this->staffDashboard();
        }

        $period = array_key_exists((string) $request->query('period'), self::PERIODS) ? (string) $request->query('period') : 'month';
        $periods = self::PERIODS;
        $today = CarbonImmutable::today();
        $range = $this->periodRange($period, $today);

        $sales = $this->salesTotal($range['start'], $range['end']);
        $previousSales = $this->salesTotal($range['previousStart'], $range['previousEnd']);
        $expenses = $this->expensesTotal($range['start'], $range['end']);
        $previousExpenses = $this->expensesTotal($range['previousStart'], $range['previousEnd']);

        $keyFigures = [
            [
                'title' => 'Sales',
                'amount' => $sales,
                'previous' => $previousSales,
                'change' => $this->percentChange($sales, $previousSales),
                'upIsGood' => true,
            ],
            [
                'title' => 'Expenses',
                'amount' => $expenses,
                'previous' => $previousExpenses,
                'change' => $this->percentChange($expenses, $previousExpenses),
                'upIsGood' => false,
            ],
        ];
        $comparisonLabel = $range['comparisonLabel'];

        $monthlyTrend = $this->monthlyTrend($today, $period === '12m' ? 12 : 6);
        $salesMix = $this->salesMix($range['start'], $range['end']);
        $expensesByCategory = $this->expensesByCategory($range['start'], $range['end']);
        $cashAvailable = [
            'sales' => $this->cashService->salesTotal(),
            'operational' => $this->cashService->operationalTotal(),
            'expenses' => $this->cashService->expensesTotal(),
            'payouts' => $this->cashService->payoutsTotal(),
            'available' => $this->cashService->available(),
        ];

        return view('home', compact(
            'period',
            'periods',
            'today',
            'keyFigures',
            'comparisonLabel',
            'monthlyTrend',
            'salesMix',
            'expensesByCategory',
            'cashAvailable',
        ));
    }

    /**
     * Show the simple dashboard for a user without "View Advanced Dashboard": today's totals
     * and every sale/expense they personally recorded in the last 7 days (today + the previous 6).
     */
    private function staffDashboard(): Renderable
    {
        $userId = auth()->id();
        $todayStart = CarbonImmutable::now('Africa/Dar_es_Salaam')->startOfDay()->setTimezone('UTC');
        $todayEnd = $todayStart->addDay();
        $weekStart = $todayStart->subDays(6);

        $mySalesToday = (float) Sale::where('created_by', $userId)
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<', $todayEnd)
            ->sum(DB::raw('quantity * selling_price'));

        $myExpensesToday = (float) Expense::where('updated_by', $userId)
            ->where('created_at', '>=', $todayStart)
            ->where('created_at', '<', $todayEnd)
            ->sum('amount');

        $recentSales = Sale::with('product')
            ->where('created_by', $userId)
            ->where('created_at', '>=', $weekStart)
            ->where('created_at', '<', $todayEnd)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Sale $sale): array => [
                'date' => CarbonImmutable::parse($sale->created_at)->setTimezone('Africa/Dar_es_Salaam'),
                'product' => $sale->product->name ?? '—',
                'amount' => (float) $sale->quantity * (float) $sale->selling_price,
            ]);

        $recentExpenses = Expense::with('category')
            ->where('updated_by', $userId)
            ->where('created_at', '>=', $weekStart)
            ->where('created_at', '<', $todayEnd)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Expense $expense): array => [
                'date' => CarbonImmutable::parse($expense->created_at)->setTimezone('Africa/Dar_es_Salaam'),
                'category' => $expense->category->name ?? '—',
                'description' => $expense->expense_description ?: '—',
                'amount' => (float) $expense->amount,
            ]);

        // Totals come from the same rows that are listed, so they always match
        $recentSalesTotal = $recentSales->sum('amount');
        $recentExpensesTotal = $recentExpenses->sum('amount');

        return view('dashboards.staff', compact(
            'mySalesToday',
            'myExpensesToday',
            'recentSales',
            'recentExpenses',
            'recentSalesTotal',
            'recentExpensesTotal',
        ));
    }

    /**
     * Current and previous date ranges for a period. Ends are exclusive, so queries use created_at >= start and < end.
     *
     * @return array{start: CarbonImmutable, end: CarbonImmutable, previousStart: CarbonImmutable, previousEnd: CarbonImmutable, comparisonLabel: string}
     */
    private function periodRange(string $period, CarbonImmutable $today): array
    {
        $tomorrow = $today->addDay();
        $monthStart = $today->startOfMonth();

        if ($period === 'today') {
            return [
                'start' => $today,
                'end' => $tomorrow,
                'previousStart' => $today->subDay(),
                'previousEnd' => $today,
                'comparisonLabel' => 'yesterday',
            ];
        }

        if ($period === 'month') {
            $previousStart = $monthStart->subMonth();
            $previousEnd = $this->sameDayInMonth($previousStart, $today->day);

            return [
                'start' => $monthStart,
                'end' => $tomorrow,
                'previousStart' => $previousStart,
                'previousEnd' => $previousEnd,
                'comparisonLabel' => '1–'.$previousEnd->subDay()->format('j M'),
            ];
        }

        $months = $period === '12m' ? 12 : 6;
        $start = $monthStart->subMonths($months - 1);
        $previousStart = $start->subMonths($months);
        $previousEnd = $this->sameDayInMonth($monthStart->subMonths($months), $today->day);

        return [
            'start' => $start,
            'end' => $tomorrow,
            'previousStart' => $previousStart,
            'previousEnd' => $previousEnd,
            'comparisonLabel' => $previousStart->format('M Y').' – '.$previousEnd->subDay()->format('j M Y'),
        ];
    }

    /**
     * Exclusive end of "the 1st up to day N" within a month, capped at the month's end for shorter months.
     */
    private function sameDayInMonth(CarbonImmutable $monthStart, int $day): CarbonImmutable
    {
        $end = $monthStart->addDays($day);
        $nextMonth = $monthStart->addMonth();

        return $end->greaterThan($nextMonth) ? $nextMonth : $end;
    }

    /**
     * Percentage change from the previous amount, or null when there is nothing to compare against.
     */
    private function percentChange(float $current, float $previous): ?float
    {
        if ($previous <= 0) {
            return null;
        }

        return ($current - $previous) / $previous * 100;
    }

    private function salesTotal(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) DB::table('sales')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->sum(DB::raw('quantity * selling_price'));
    }

    private function expensesTotal(CarbonImmutable $start, CarbonImmutable $end): float
    {
        return (float) DB::table('expenses')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->sum('amount');
    }

    /**
     * Sales joined to their product. A LEFT JOIN keeps sales whose product was deleted; they count as Items.
     */
    private function salesWithProduct(CarbonImmutable $start, CarbonImmutable $end): Builder
    {
        return DB::table('sales')
            ->leftJoin('stock', 'stock.id', '=', 'sales.stock_id')
            ->where('sales.created_at', '>=', $start)
            ->where('sales.created_at', '<', $end)
            ->selectRaw("SUM(CASE WHEN stock.type = 'Service' THEN 0 ELSE sales.quantity * sales.selling_price END) as items")
            ->selectRaw("SUM(CASE WHEN stock.type = 'Service' THEN sales.quantity * sales.selling_price ELSE 0 END) as services");
    }

    /**
     * Items, services and expenses per calendar month, oldest first, ending with the current month.
     *
     * @return list<array{label: string, items: float, services: float, total: float, expenses: float}>
     */
    private function monthlyTrend(CarbonImmutable $today, int $months): array
    {
        $firstMonth = $today->startOfMonth()->subMonths($months - 1);
        $end = $today->addDay();

        $salesByMonth = $this->salesWithProduct($firstMonth, $end)
            ->selectRaw("DATE_FORMAT(sales.created_at, '%Y-%m') as month_key")
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        $expensesByMonth = DB::table('expenses')
            ->where('created_at', '>=', $firstMonth)
            ->where('created_at', '<', $end)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(amount) as amount")
            ->groupBy('month_key')
            ->pluck('amount', 'month_key');

        $trend = [];
        for ($index = 0; $index < $months; $index++) {
            $month = $firstMonth->addMonths($index);
            $key = $month->format('Y-m');
            $items = (float) ($salesByMonth[$key]->items ?? 0);
            $services = (float) ($salesByMonth[$key]->services ?? 0);

            $trend[] = [
                'label' => $month->isSameMonth($today) ? $month->format('M').' (to '.$today->format('jS').')' : $month->format('M'),
                'items' => $items,
                'services' => $services,
                'total' => $items + $services,
                'expenses' => (float) ($expensesByMonth[$key] ?? 0),
            ];
        }

        return $trend;
    }

    /**
     * Items vs services split and each service's revenue for the period.
     *
     * @return array{items: float, services: float, total: float, itemsPercent: float, servicesPercent: float, serviceRows: list<array{name: string, revenue: float, jobs: int, share: float}>}
     */
    private function salesMix(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $split = $this->salesWithProduct($start, $end)->first();
        $items = (float) ($split->items ?? 0);
        $services = (float) ($split->services ?? 0);
        $total = $items + $services;

        $serviceRows = DB::table('sales')
            ->join('stock', 'stock.id', '=', 'sales.stock_id')
            ->where('stock.type', 'Service')
            ->where('sales.created_at', '>=', $start)
            ->where('sales.created_at', '<', $end)
            ->groupBy('stock.id', 'stock.name')
            ->selectRaw('stock.name, SUM(sales.quantity * sales.selling_price) as revenue, COUNT(sales.id) as jobs')
            ->orderByDesc('revenue')
            ->get();

        $topRevenue = (float) ($serviceRows->first()->revenue ?? 0);

        return [
            'items' => $items,
            'services' => $services,
            'total' => $total,
            'itemsPercent' => $total > 0 ? $items / $total * 100 : 0,
            'servicesPercent' => $total > 0 ? $services / $total * 100 : 0,
            'serviceRows' => $serviceRows->map(fn (object $row): array => [
                'name' => $row->name,
                'revenue' => (float) $row->revenue,
                'jobs' => (int) $row->jobs,
                'share' => $topRevenue > 0 ? max(0, (float) $row->revenue / $topRevenue * 100) : 0,
            ])->all(),
        ];
    }

    /**
     * Expenses per category for the period, largest first.
     *
     * @return array{total: float, rows: list<array{name: string, amount: float, share: float}>}
     */
    private function expensesByCategory(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $categories = DB::table('expenses')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->where('expenses.created_at', '>=', $start)
            ->where('expenses.created_at', '<', $end)
            ->groupBy('expenses.expense_category_id', 'expense_categories.name')
            ->selectRaw("COALESCE(expense_categories.name, 'Uncategorised') as name, SUM(expenses.amount) as amount")
            ->orderByDesc('amount')
            ->get();

        $largest = (float) ($categories->first()->amount ?? 0);

        return [
            'total' => (float) $categories->sum('amount'),
            'rows' => $categories->map(fn (object $row): array => [
                'name' => $row->name,
                'amount' => (float) $row->amount,
                'share' => $largest > 0 ? max(0, (float) $row->amount / $largest * 100) : 0,
            ])->all(),
        ];
    }
}
