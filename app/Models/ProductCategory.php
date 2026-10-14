<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Stock::class, 'category_id');
    }
}
