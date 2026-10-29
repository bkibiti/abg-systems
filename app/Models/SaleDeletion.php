<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDeletion extends Model
{
    protected $table = 'sale_deletions';

    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function saleCreator()
    {
        return $this->belongsTo(User::class, 'sale_created_by');
    }
}
