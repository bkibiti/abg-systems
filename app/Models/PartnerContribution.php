<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerContribution extends Model
{
    protected $table = 'partner_contributions';

    protected $casts = [
        'contribution_date' => 'date',
    ];

    const TYPE_INVESTMENT = 'Investment';

    const TYPE_OPERATIONS = 'Operations';

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function scopeInvestment($query)
    {
        return $query->where('type', self::TYPE_INVESTMENT);
    }

    public function scopeOperations($query)
    {
        return $query->where('type', self::TYPE_OPERATIONS);
    }
}
