<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $products = Stock::select('id', 'name', 'type')->servicesFirst()->get();

        return view('reports.index', compact('products'));
    }

    protected function getReport(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));
        $products = Stock::select('id', 'name', 'type')->servicesFirst()->get();

        switch ($request->report) {
            case 1:
                $data = $this->TotalDailySales($from, $to);
                $request->flash();

                if ($request->action == 'view') {
                    return view('reports.total_daily_sales', compact('data', 'products'));
                }
                if ($request->action == 'print') {
                    $filterMsg = 'From '.date('d M Y', strtotime($request->from_date)).'    to '.date('d M Y', strtotime($request->to_date));
                    $pdf = PDF::loadView('reports.total_daily_sales_pdf', compact('data', 'filterMsg'));

                    return $pdf->stream();
                }
                break;
            case 2:
                $data = $this->TotalMonthlySale($from, $to);
                $request->flash();
                if ($request->action == 'view') {
                    return view('reports.total_monthly', compact('data', 'products'));
                }
                if ($request->action == 'print') {
                    $pdf = PDF::loadView('reports.total_monthly_pdf', compact('data'));

                    return $pdf->stream();
                }
                break;
            case 3:
                $data = $this->fastMovingItems();
                $days = DB::table('sales')->select(DB::raw('date(created_at)'))->distinct()->get();

                $request->flash();
                if ($request->action == 'view') {
                    return view('reports.fast_moving_items', compact('data', 'days', 'products'));
                }
                if ($request->action == 'print') {
                    $pdf = PDF::loadView('reports.fast_moving_items_pdf', compact('data', 'days'));

                    return $pdf->stream();
                }
                break;

            case 4:
                $data = $this->grossProfit($from, $to);
                $total = 0;
                foreach ($data as $d) {
                    $total = $total + $d->profit;
                }
                $request->flash();

                if ($request->action == 'view') {
                    return view('reports.gross_profit', compact('data', 'total', 'products'));
                }
                if ($request->action == 'print') {
                    $filterMsg = 'From '.date('d M Y', strtotime($request->from_date)).'    to '.date('d M Y', strtotime($request->to_date));
                    $pdf = PDF::loadView('reports.gross_profit_pdf', compact('data', 'total', 'filterMsg'));

                    return $pdf->stream();
                }
                break;

            case 5:
                $data = $this->stockValue();
                $total = DB::table('stock')->selectRaw('sum(quantity*unit_cost) total_purchase_value,sum(quantity*sale_price_1) total_sale_value')->get();
                $request->flash();
                if ($request->action == 'view') {
                    return view('reports.stock_value', compact('data', 'total', 'products'));
                }
                if ($request->action == 'print') {
                    $pdf = PDF::loadView('reports.stock_value_pdf', compact('data', 'total'));

                    return $pdf->stream();
                }
                break;

            case 7:
                $movements = $this->stockMovementsSql();
                $data = DB::select("SELECT * FROM 
                            (SELECT s.Date,s.Action,SUM(s.qty) as Qty,
                                    (   SELECT SUM(sm.qty) 
                                        FROM ($movements) sm
                                        WHERE sm.Date <= s.Date and sm.id = ?
                                    ) AS QOH
                                        FROM ($movements) s
                            WHERE s.id = ?
                            GROUP BY s.date,s.action
                            ORDER BY s.Date
                            ) AS Q
                            WHERE Q.Date between ? and ?", [$request->product, $request->product, $from, $to]);

                $request->flash();

                return view('reports.item_movement_history', compact('data', 'products'));
                break;
            default:

        }
    }

    private function TotalDailySales($from, $to)
    {
        $totalDailySales = DB::table('sales')
            ->select(DB::raw('date(created_at) date, sum(quantity*selling_price) amount'))
            ->whereRaw("date(created_at) between '".$from."' and '".$to."'")
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date', 'Desc')
            ->get();

        return $totalDailySales;
    }

    private function TotalMonthlySale($from, $to)
    {
        $totalMonthlySales = DB::table('sales')
            ->select(DB::raw("DATE_FORMAT(created_at, '%b %Y') month,sum(quantity*selling_price) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->limit('12')
            ->get();

        return $totalMonthlySales;
    }

    private function fastMovingItems()
    {
        $soldqty = DB::table('sales')
            ->selectRaw('stock.name,sum(sales.quantity) qty')
            ->join('stock', 'stock.id', '=', 'sales.stock_id')
            ->groupBy('stock.name')
            ->orderBy('qty', 'desc')
            ->get();

        return $soldqty;
    }

    private function stockValue()
    {
        $values = DB::table('stock')
            ->selectRaw('name,quantity,(quantity*unit_cost) purchase_value,(quantity*sale_price_1) sale_value')
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return $values;
    }

    /**
     * Stock movements (sales, purchases, adjustments) per product, replacing the
     * stock_movements DB view which inner-joins the retired product_categories table.
     */
    private function stockMovementsSql(): string
    {
        return "SELECT s.id, sales.quantity * -1 AS Qty, 'Sale' AS Action, CAST(sales.created_at AS DATE) AS Date
                    FROM sales JOIN stock s ON s.id = sales.stock_id
                UNION ALL
                SELECT s.id, i.quantity AS Qty, 'Purchase' AS Action, CAST(i.created_at AS DATE) AS Date
                    FROM purchases i JOIN stock s ON s.id = i.product_id
                UNION ALL
                SELECT s.id, CASE WHEN a.adjustment_type = 'Negative' THEN a.adjusted_qty * -1 ELSE a.adjusted_qty END AS Qty,
                        'Stock Adjusment' AS Action, CAST(a.created_at AS DATE) AS Date
                    FROM stock_adjustments a JOIN stock s ON s.id = a.stock_id";
    }

    private function grossProfit($from, $to)
    {
        $profit = DB::table('sales')
            ->selectRaw('date(created_at) date,sum((selling_price-buying_price)*quantity) profit')
            ->whereRaw("date(created_at) between '".$from."' and '".$to."'")
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','asc')
            ->get();

        return $profit;
    }
}
