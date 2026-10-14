<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
