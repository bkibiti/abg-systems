<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    public const TYPE_ITEM = 'Item';
    public const TYPE_SERVICE = 'Service';

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /** Only items kept in stock (excludes services). */
    public function scopeGoods($query)
    {
        return $query->where('type', self::TYPE_ITEM);
    }

    public function scopeServices($query)
    {
        return $query->where('type', self::TYPE_SERVICE);
    }

    public function isService(): bool
    {
        return $this->type === self::TYPE_SERVICE;
    }
}
