<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    private const LOCAL_TIMEZONE = 'Africa/Dar_es_Salaam';

    /**
     * Anyone with "Delete Sales" can delete any sale. Everyone else can only
     * delete their own sale, and only on the day it was recorded.
     */
    public function isDeletableBy(?int $userId, bool $canDeleteAny): bool
    {
        if ($canDeleteAny) {
            return true;
        }

        return $userId !== null
            && (int) $this->created_by === $userId
            && CarbonImmutable::parse($this->created_at)->setTimezone(self::LOCAL_TIMEZONE)->isToday();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function product()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
