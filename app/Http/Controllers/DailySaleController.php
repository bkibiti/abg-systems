<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Sale;
use App\Models\DailySale;


class DailySaleController extends Controller
{
    
    public function index()
    {
        $DailySale = DailySale::whereRaw('month(report_date) = month(now())')->get();
    
        $totals = DB::table('daily_report')
        ->select(DB::raw('sum(sales) as sales,sum(other_income) as income,sum(expenses) as expenses,sum(purchases) as purchases,sum(cash_on_hand) as coh'))
        ->whereRaw('month(report_date) = month(now())')
        ->groupBy(DB::raw('month(now())'))
        ->get();

        return view('sales.daily_report.index', compact('DailySale','totals'));
    }


    public function generate(Request $request){

        $request->validate([
            'other_income' => 'required|numeric',
            'report_date' => 'required',
            'submission_remarks' => 'required|min:3',
        ]);
    
        $report_date = date('Y-m-d', strtotime($request->report_date));

        $totalExpenses = DB::table('expenses')
                ->where('created_at', $report_date)
                ->sum('amount');

        $Purchases = DB::table('incoming_stock')
                ->where('created_at', $report_date)
                ->sum(DB::raw('quantity*unit_cost'));

        $totalSales = DB::table('sales')
                ->where('created_at', $report_date)
                ->sum(DB::raw('quantity*selling_price'));

        $cash = $totalSales + $request->other_income - $totalExpenses - $Purchases;

        $exist = DailySale::where('report_date',$report_date)->get();
        
        if ($exist->count() == 0){
            DailySale::Create(
                ['report_date' => $report_date,'status' => 'Pending',
                'sales' => $totalSales, 'expenses' => $totalExpenses, 'other_income'=> $request->other_income,
                'purchases' =>$Purchases,'submission_remarks' => $request->submission_remarks,
                'cash_on_hand' => $cash ,'submitted_by' =>Auth::user()->id,'submitted_at' => Carbon::now(), 
                'status' => 'Pending'] );
        }else{
            session()->flash("alert-danger", "The report for this date is already generated!");
            return back();
        }
       

        session()->flash("alert-success", "Report created successfully!");
        return back();

    }
    public function update(Request $request){

        $request->validate([
            'other_income' => 'required|numeric',
            'submission_remarks' => 'required',
        ]);

        $report_date = date('Y-m-d', strtotime($request->report_date));

        $totalSales = DB::table('sales')
                ->where('created_at', $report_date)
                ->sum(DB::raw('quantity*selling_price'));
        $Purchases = DB::table('incoming_stock')
                ->whereRaw("date(created_at) = '".$report_date. "'")
                ->sum(DB::raw('quantity*unit_cost'));
        $totalExpenses = DB::table('expenses')
                ->where('created_at', $report_date)
                ->sum('amount');
                
        $cash = $totalSales + $request->other_income - $totalExpenses - $Purchases;

        
        $report = DailySale::findOrFail($request->id);
        $report->other_income = $request->other_income;
        $report->purchases = $Purchases;
        $report->sales = $totalSales;
        $report->expenses = $totalExpenses;
        $report->cash_on_hand = $cash;
        $report->submission_remarks = $request->submission_remarks;
        $report->status = 'Pending';
        $report->save();

        session()->flash("alert-success", "Report updated successfully!");
        return back();

    }

    public function review(Request $request){
        
        if($request->status == 'reject'){
            $status = 'Rejected';
            $message = 'Report Rejected Successfully!';
        }
        if($request->status == 'approve'){
            $status = 'Approved';
            $message = 'Report Approved Successfully!';
        }
        
        $report = DailySale::findOrFail($request->id);
        $report->status = $status;
        $report->approved_at = Carbon::now();
        $report->approved_by = Auth::user()->id;
        $report->approver_remarks = $request->approval_remarks;
        $report->save(); 

        session()->flash("alert-success", $message);
        return back();

    }

    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $DailySale = DailySale::whereRaw("date(report_date) between '". $from . "' and '". $to ."'")->get();
        
        $totals = DB::table('daily_report')
            ->select(DB::raw('sum(sales) as sales,sum(other_income) as income,sum(expenses) as expenses,sum(purchases) as purchases,sum(cash_on_hand) as coh'))
            ->whereRaw("report_date between '". $from  ."' and '". $to ."'")
            ->groupBy(DB::raw('month(now())'))
            ->get();

        $request->flash();

        return view('sales.daily_report.index', compact('DailySale','totals'));
    }


}
