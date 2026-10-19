<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $table = 'partners';

    protected $casts = [
        'joined_on' => 'date',
    ];

    public function contributions()
    {
        return $this->hasMany(PartnerContribution::class, 'partner_id');
    }

    public function payouts()
    {
        return $this->hasMany(PartnerPayout::class, 'partner_id');
    }

    public function investmentTotal(): float
    {
        return (float) $this->contributions()->investment()->sum('amount');
    }

    public function operationsTotal(): float
    {
        return (float) $this->contributions()->operations()->sum('amount');
    }

    public function totalContributed(): float
    {
        return (float) $this->contributions()->sum('amount');
    }

    public function paidOutTotal(): float
    {
        return (float) $this->payouts()->paid()->sum('amount');
    }
}
