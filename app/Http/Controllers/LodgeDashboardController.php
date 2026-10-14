<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LodgeSale;
use Illuminate\Support\Facades\DB;

class LodgeDashboardController extends Controller
{
    public function index()
    {     

        $totalDailySales = DB::table('lodge_sales')
            ->select(DB::raw('date(created_at) date, sum(amount) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->orderBy('date','Desc')
            ->limit('40')
            ->get();

        $monthlyTrends = DB::select("select Q1.month_no,Q1.month,Q1.amount as sales,Q2.amount as expenses from 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(amount) as amount 
                    FROM lodge_sales GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q1
                    left join 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(amount) as amount 
                    FROM lodge_expenses GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q2
                    on Q1.month_no = Q2.month_no 
                    order by Q1.month_no
                    ");
       
        $saleByDay = DB::table('lodge_sales')
            ->select(DB::raw('WEEKDAY(created_at) DayNumber,DAYNAME(created_at) DayName,sum(amount) Amount'))
            ->groupBy(DB::raw('WEEKDAY(created_at),DAYNAME(created_at)'))
            ->orderBy(DB::raw('DayNumber'))
            ->get();
        $saleByMonth = DB::table('lodge_sales')
            ->select(DB::raw('MONTHNAME(created_at) Month,sum(amount) Amount'))
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->get();
        
        $salesThisMonth = DB::table('lodge_sales')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW()) and YEAR(created_at) = YEAR(NOW())')
            ->get();
        $salesLastMonth = DB::table('lodge_sales')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();
        $salesBeforeLastMonth = DB::table('lodge_sales')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 2 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
            ->get();
   
        $expensesThisMonth = DB::table('lodge_expenses')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('MONTH(created_at) = MONTH(NOW()) and YEAR(created_at) = YEAR(NOW())')
            ->get();
        $expensesLastMonth = DB::table('lodge_expenses')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
            ->get();
        $expensesBeforeLastMonth = DB::table('lodge_expenses')
            ->select(DB::raw('sum(amount) Amount'))
            ->whereRaw('YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 2 MONTH) AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
            ->get();

        $thisMonth = [];
        $thisMonth['sales'] = $salesThisMonth[0]->Amount;
        $thisMonth['expense'] = $expensesThisMonth[0]->Amount;
        $thisMonth['profit'] = $salesThisMonth[0]->Amount - $expensesThisMonth[0]->Amount;

        $LastMonth = [];
        $lastMonth['sales'] = $salesLastMonth[0]->Amount;
        $lastMonth['expense'] = $expensesLastMonth[0]->Amount;
        $lastMonth['profit'] = $salesLastMonth[0]->Amount - $expensesLastMonth[0]->Amount;

        $BeforeLastMonth = [];
        $BeforeLastMonth['sales'] = $salesBeforeLastMonth[0]->Amount;
        $BeforeLastMonth['expense'] = $expensesBeforeLastMonth[0]->Amount;
        $BeforeLastMonth['profit'] = $salesBeforeLastMonth[0]->Amount - $expensesBeforeLastMonth[0]->Amount;

        return view('lodge.dashboard', compact('thisMonth','lastMonth','BeforeLastMonth','totalDailySales','saleByDay','saleByMonth', 'monthlyTrends'));

    }
}
