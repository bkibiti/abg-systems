<?php

namespace App\Services;

use App\Models\Stock;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * "Sales by service" and "Expenses by category" cards on the advanced dashboard.
 * Both follow the dashboard period switch; figures are built from the same
 * sales/expenses sources as the Sales/Expenses key-figure cards, so totals match.
 */
class DashboardService
{
    /**
     * Fixed colour per service product name (never by rank). Any other stock item
     * (a regular item, or a service not in this list) falls back to OTHER_COLOR.
     * Keep these in sync with the --abg-svc-* variables in public/css/abg-theme.css.
     *
     * @var array<string, string>
     */
    private const SERVICE_COLORS = [
        'Metal Bending' => '#2A9D8F',
        'CNC Plasma Cutting' => '#5B6FB5',
        'Pipe Bending' => '#C9A13F',
    ];

    private const OTHER_COLOR = '#6E6E6E';

    /**
     * @return array{total: float, salesCount: int, average: float, rows: list<array>, maxAmount: float, monthly: list<array>}
     */
    public function salesByService(
        CarbonImmutable $start,
        CarbonImmutable $end,
        CarbonImmutable $previousStart,
        CarbonImmutable $previousEnd
    ): array {
        $currentByStock = DB::table('sales')
            ->leftJoin('stock', 'stock.id', '=', 'sales.stock_id')
            ->where('sales.created_at', '>=', $start)
            ->where('sales.created_at', '<', $end)
            ->groupBy('sales.stock_id', 'stock.name', 'stock.type')
            ->selectRaw("sales.stock_id as stock_id, COALESCE(stock.name, '(deleted product)') as name, COALESCE(stock.type, 'Item') as type,
                SUM(sales.quantity * sales.selling_price) as amount, COUNT(*) as sale_rows, SUM(sales.quantity) as qty")
            ->get()
            ->keyBy('stock_id');

        $previousByStock = DB::table('sales')
            ->where('created_at', '>=', $previousStart)
            ->where('created_at', '<', $previousEnd)
            ->groupBy('stock_id')
            ->selectRaw('stock_id, SUM(quantity * selling_price) as amount')
            ->pluck('amount', 'stock_id');

        $totalAmount = (float) $currentByStock->sum('amount');
        $totalRows = (int) $currentByStock->sum('sale_rows');

        $services = Stock::services()->orderBy('name')->get(['id', 'name']);
        $serviceIds = $services->pluck('id')->all();

        $serviceRows = $services->map(fn ($service) => $this->buildServiceRow(
            $service->id,
            $service->name,
            Stock::TYPE_SERVICE,
            $currentByStock->get($service->id),
            (float) ($previousByStock[$service->id] ?? 0),
            $totalAmount
        ))->sortByDesc('amount')->values();

        $itemRows = $currentByStock
            ->filter(fn ($row) => ! in_array($row->stock_id, $serviceIds, true))
            ->map(fn ($row) => $this->buildServiceRow(
                $row->stock_id,
                $row->name,
                $row->type,
                $row,
                (float) ($previousByStock[$row->stock_id] ?? 0),
                $totalAmount
            ))
            ->sortByDesc('amount')
            ->values();

        $rows = $serviceRows->concat($itemRows)->values();

        return [
            'total' => $totalAmount,
            'salesCount' => $totalRows,
            'average' => $totalRows > 0 ? $totalAmount / $totalRows : 0.0,
            'rows' => $rows->all(),
            'maxAmount' => (float) $rows->max('amount'),
            'monthly' => $this->salesMixByMonth($start, $end),
        ];
    }

    /**
     * @return array{total: float, averagePerMonth: float, rows: list<array>, maxAmount: float, insight: ?string}
     */
    public function expensesByCategory(
        CarbonImmutable $start,
        CarbonImmutable $end,
        CarbonImmutable $previousStart,
        CarbonImmutable $previousEnd,
        int $periodMonths,
        int $topCount = 7
    ): array {
        $current = DB::table('expenses')
            ->leftJoin('expense_categories', 'expense_categories.id', '=', 'expenses.expense_category_id')
            ->where('expenses.created_at', '>=', $start)
            ->where('expenses.created_at', '<', $end)
            ->groupBy('expenses.expense_category_id', 'expense_categories.name')
            ->selectRaw("expenses.expense_category_id as id, COALESCE(expense_categories.name, 'Uncategorised') as name, SUM(expenses.amount) as amount")
            ->orderByDesc('amount')
            ->get();

        $previous = DB::table('expenses')
            ->where('created_at', '>=', $previousStart)
            ->where('created_at', '<', $previousEnd)
            ->groupBy('expense_category_id')
            ->selectRaw('expense_category_id as id, SUM(amount) as amount')
            ->pluck('amount', 'id');

        $total = (float) $current->sum('amount');

        $rows = $current->take($topCount)->map(
            fn ($row) => $this->buildCategoryRow($row->id, $row->name, (float) $row->amount, (float) ($previous[$row->id] ?? 0), $total, false)
        )->values();

        $rest = $current->slice($topCount);

        if ($rest->isNotEmpty()) {
            $restAmount = (float) $rest->sum('amount');
            $restPreviousAmount = (float) $rest->sum(fn ($row) => $previous[$row->id] ?? 0);
            $row = $this->buildCategoryRow(null, $rest->count().' smaller '.Str::plural('category', $rest->count()), $restAmount, $restPreviousAmount, $total, true);
            $row['names'] = $rest->pluck('name')->implode(', ');
            $rows->push($row);
        }

        return [
            'total' => $total,
            'averagePerMonth' => $periodMonths > 0 ? $total / $periodMonths : 0.0,
            'rows' => $rows->all(),
            'maxAmount' => (float) $rows->max('amount'),
            'insight' => $this->buildInsightSentence($current->take(3)->pluck('name'), $current->take(3)->sum('amount'), $total),
        ];
    }

    /**
     * One row per stock item: this period's amount/count/qty, and last period's amount for the % change.
     */
    private function buildServiceRow(int $id, string $name, string $type, ?object $currentRow, float $previousAmount, float $totalAmount): array
    {
        $amount = (float) ($currentRow->amount ?? 0);
        $salesCount = (int) ($currentRow->sale_rows ?? 0);
        $qty = (float) ($currentRow->qty ?? 0);

        return [
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'color' => $this->serviceColor($name, $type),
            'amount' => $amount,
            'share' => $totalAmount > 0 ? $amount / $totalAmount * 100 : 0.0,
            'salesCount' => $salesCount,
            'qty' => $qty,
            'average' => $salesCount > 0 ? $amount / $salesCount : 0.0,
            'previousAmount' => $previousAmount,
            'change' => $previousAmount > 0 ? ($amount - $previousAmount) / $previousAmount * 100 : null,
            'hasSales' => $amount > 0,
        ];
    }

    private function buildCategoryRow(?int $id, string $name, float $amount, float $previousAmount, float $total, bool $grouped): array
    {
        return [
            'id' => $id,
            'name' => $name,
            'amount' => $amount,
            'share' => $total > 0 ? $amount / $total * 100 : 0.0,
            'previousAmount' => $previousAmount,
            'change' => $previousAmount > 0 ? ($amount - $previousAmount) / $previousAmount * 100 : null,
            'isNew' => $previousAmount <= 0 && $amount > 0,
            'grouped' => $grouped,
        ];
    }

    private function serviceColor(string $name, string $type): string
    {
        if ($type !== Stock::TYPE_SERVICE) {
            return self::OTHER_COLOR;
        }

        return self::SERVICE_COLORS[$name] ?? self::OTHER_COLOR;
    }

    /**
     * Each calendar month in [$start, $end): the month's total sales, and a 100%-stacked
     * breakdown into the fixed services plus one lumped "Items" segment for everything else.
     *
     * @return list<array{label: string, total: float, segments: list<array{name: string, color: string, amount: float, share: float}>}>
     */
    private function salesMixByMonth(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $monthTotals = DB::table('sales')
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $end)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, SUM(quantity * selling_price) as total")
            ->groupBy('month_key')
            ->get()
            ->keyBy('month_key');

        $serviceTotals = DB::table('sales')
            ->join('stock', 'stock.id', '=', 'sales.stock_id')
            ->where('stock.type', Stock::TYPE_SERVICE)
            ->where('sales.created_at', '>=', $start)
            ->where('sales.created_at', '<', $end)
            ->selectRaw("DATE_FORMAT(sales.created_at, '%Y-%m') as month_key, stock.name as service_name, SUM(sales.quantity * sales.selling_price) as amount")
            ->groupBy('month_key', 'stock.name')
            ->get()
            ->groupBy('month_key');

        $cursor = $start->startOfMonth();
        $lastMonth = $end->subDay()->startOfMonth();
        $months = [];

        while ($cursor->lessThanOrEqualTo($lastMonth)) {
            $key = $cursor->format('Y-m');
            $monthTotal = (float) ($monthTotals[$key]->total ?? 0);
            $servicesThisMonth = ($serviceTotals[$key] ?? collect())->keyBy('service_name');

            $segments = [];
            $accountedFor = 0.0;

            foreach (self::SERVICE_COLORS as $serviceName => $color) {
                $amount = (float) ($servicesThisMonth[$serviceName]->amount ?? 0);
                $accountedFor += $amount;
                $segments[] = [
                    'name' => $serviceName,
                    'color' => $color,
                    'amount' => $amount,
                    'share' => $monthTotal > 0 ? $amount / $monthTotal * 100 : 0.0,
                ];
            }

            $itemsAmount = max(0.0, $monthTotal - $accountedFor);
            $segments[] = [
                'name' => 'Items',
                'color' => self::OTHER_COLOR,
                'amount' => $itemsAmount,
                'share' => $monthTotal > 0 ? $itemsAmount / $monthTotal * 100 : 0.0,
            ];

            $months[] = [
                'label' => $cursor->format('M y'),
                'total' => $monthTotal,
                'segments' => $segments,
            ];

            $cursor = $cursor->addMonth();
        }

        return $months;
    }

    /**
     * "Salaries, staff transport and rent make up 69% of all spending."
     */
    private function buildInsightSentence(Collection $topNames, float $topAmount, float $total): ?string
    {
        if ($topNames->isEmpty() || $total <= 0) {
            return null;
        }

        $names = $topNames->values();
        $list = $names->count() > 1
            ? $names->slice(0, -1)->implode(', ').' and '.$names->last()
            : $names->first();

        $share = round($topAmount / $total * 100);

        return $list.' make up '.$share.'% of all spending.';
    }
}
