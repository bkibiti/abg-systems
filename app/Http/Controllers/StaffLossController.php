<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\StaffAdvance;
use App\Models\User;

class StaffLossController extends Controller
{
    public function index()
    {
        $StaffLOss = StaffAdvance::whereRaw('month(date) = month(now()) and year(date) = year(now())')->get();
        $users = User::where('status',1)->orderBy('name')->get();
        $staffLossTotal = DB::select("SELECT users.name as user,sum(amount) as amount FROM 
                    staff_advances join users on staff_advances.user_id=users.id
                    where month(staff_advances.date) = month(now()) and year(staff_advances.date) = year(now())and type='loss'
                    group by users.name");
        $total = 0;
        foreach ($staffLossTotal as $s) {
            $total = $total + $s->amount;
        }
        return view('sales.staff_loss.index', compact('StaffLOss','users','staffLossTotal','total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'remarks'=> 'max:200',
        ]);

        $adv_date = date('Y-m-d', strtotime($request->date));

        $advance = new StaffAdvance;
        $advance->amount = $request->amount;
        $advance->date = $adv_date;
        $advance->type = "Loss";
        $advance->user_id =$request->user;
        $advance->remarks =$request->remarks;
        $advance->save();

        session()->flash("alert-success", "Expense added successfully!");
        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'amount' => 'required',
            'remarks'=> 'max:200',
        ]);

        $adv_date = date('Y-m-d', strtotime($request->date));

        $advance = StaffAdvance::findOrFail($request->id);
        $advance->amount = $request->amount;
        $advance->date = $adv_date;
        $advance->type = "Loss";
        $advance->user_id =$request->user;
        $advance->remarks =$request->remarks;
        $advance->save();

        session()->flash("alert-success", "Expense updated successfully!");
        return back();
    }


    public function search(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from_date));
        $to = date('Y-m-d', strtotime($request->to_date));

        $StaffLOss = StaffAdvance::whereRaw("date(date) between '". $from . "' and '". $to ."'")->get();
        $users = User::where('status',1)->orderBy('name')->get();

        $staffLossTotal = DB::select("SELECT users.name as user,sum(amount) as amount FROM 
                    staff_advances join users on staff_advances.user_id=users.id
                    where staff_advances.date between '". $from . "' and '" . $to . "' and type='loss' group by users.name");
        $total = 0;
        foreach ($staffLossTotal as $s) {
            $total = $total + $s->amount;
        }
        $request->flash();
        return view('sales.staff_loss.index', compact('StaffLOss','users','staffLossTotal','total'));
    }



}
