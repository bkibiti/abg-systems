<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthClosingStock extends Model
{
    protected $table = 'monthly_closing_stock';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

}
