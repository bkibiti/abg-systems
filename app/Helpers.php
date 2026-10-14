<?php

use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Stock;


function getRoles(){
    return DB::table('roles')
        ->select('id','name')
        ->orderBy('name')
        ->get();
}

function getSettings($settingName){
    $settings = Setting::get();

    $value = 'NA';
    foreach ($settings as $s) {
        if($s->name == $settingName){
            return $s->value;
        }
    }
    return $value;
}

function outofstock(){
    $outOfStock = Stock::where('quantity', 0)->count();
    return  $outOfStock;
}

function belowMin(){
    $belowMin = Stock::whereRaw('quantity < min_quantinty and quantity > 0')->count();
    return $belowMin;
}

function stockItems(){
    $num = Stock::count();
    return $num;
}

function notifications(){
    $num = 0;

    if (belowMin() > 0){
        $num = $num + 1;
    }
    if (outofstock() > 0){
        $num = $num + 1;
    }
    if (count(getReminders()) > 0) {
        $num = $num + count(getReminders());
    }

    return $num;
}

function getReminders(){
    $reminders = DB::table('reminders')
        ->select(DB::raw('name,end_date,datediff(end_date,now()) days'))
        ->whereRaw("datediff(end_date,now()) <= days_to_remind and status='On'")
        ->get();
     
    $msg =[];
    foreach ($reminders as $r) {
        if ($r->days > 0) {
            $msg[] = $r->name . ' is due in '. $r->days . ' days';
        } else if ($r->days < 0) {
            $msg[] = $r->name . ' is overdue by '. (0 - (int)$r->days) . ' days';
        }else{
            $msg[] = $r->name . ' is due today';
        }       
    }
 
    return $msg;
}