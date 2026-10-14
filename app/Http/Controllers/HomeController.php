<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    // login form
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        if (! auth()->user()->can('View Advanced Dashboard')) {
            return view('home');
        }

      

        $monthlyTrends = DB::select("select Q1.month_no,Q1.month,Q1.amount as sales,Q2.amount as expenses from 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(quantity*selling_price) as amount 
                    FROM sales GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q1
                    left join 
                    (SELECT DATE_FORMAT(created_at,'%Y-%m') month_no, DATE_FORMAT(created_at,'%b-%y') month, sum(amount) as amount 
                    FROM expenses GROUP BY month_no,month ORDER BY month_no desc limit 12) as Q2
                    on Q1.month_no = Q2.month_no 
                    order by Q1.month_no
                    ");

       

        return view('home', compact( 'monthlyTrends'));

    }
}
