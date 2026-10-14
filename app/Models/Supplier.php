<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    public $timestamps = false;

    public function order()
    {
        return $this->hasMany(Order::class, 'supplier_id');
    }


}
