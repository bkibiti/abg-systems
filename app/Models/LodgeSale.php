<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LodgeSale extends Model
{
    protected $table = 'lodge_sales';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
