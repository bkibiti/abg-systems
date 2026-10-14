<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class StockAdjustment extends Model
{
    protected $table = 'stock_adjustments';


    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function product()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }



}
