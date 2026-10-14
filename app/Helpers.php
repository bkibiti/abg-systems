<?php

use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

function getRoles()
{
    return DB::table('roles')
        ->select('id', 'name')
        ->orderBy('name')
        ->get();
}

function getSettings($settingName)
{
    $settings = Setting::get();

    $value = 'NA';
    foreach ($settings as $s) {
        if ($s->name == $settingName) {
            return $s->value;
        }
    }

    return $value;
}

function outofstock()
{
    $outOfStock = Stock::goods()->where('quantity', 0)->count();

    return $outOfStock;
}

function belowMin()
{
    $belowMin = Stock::goods()->whereRaw('quantity < min_quantinty and quantity > 0')->count();

    return $belowMin;
}

function stockItems()
{
    $num = Stock::goods()->count();

    return $num;
}

function notifications()
{
    $num = 0;

    if (belowMin() > 0) {
        $num = $num + 1;
    }
    if (outofstock() > 0) {
        $num = $num + 1;
    }
    if (count(getReminders()) > 0) {
        $num = $num + count(getReminders());
    }

    return $num;
}

/**
 * Format an amount compactly for the dashboard: 36.50M from one million up, 850k below that.
 */
function formatCompactAmount(float $amount): string
{
    $absolute = abs($amount);

    if ($absolute >= 999500) {
        return number_format($amount / 1000000, 2).'M';
    }

    if ($absolute >= 1000) {
        return number_format($amount / 1000).'k';
    }

    return number_format($amount);
}

function getReminders()
{
    $reminders = DB::table('reminders')
        ->select(DB::raw('name,end_date,datediff(end_date,now()) days'))
        ->whereRaw("datediff(end_date,now()) <= days_to_remind and status='On'")
        ->get();

    $msg = [];
    foreach ($reminders as $r) {
        if ($r->days > 0) {
            $msg[] = $r->name.' is due in '.$r->days.' days';
        } elseif ($r->days < 0) {
            $msg[] = $r->name.' is overdue by '.(0 - (int) $r->days).' days';
        } else {
            $msg[] = $r->name.' is due today';
        }
    }

    return $msg;
}
