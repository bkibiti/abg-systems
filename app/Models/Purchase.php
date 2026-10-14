<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Stock::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
