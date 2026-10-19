<?php

namespace App\Services;

use App\Models\PartnerContribution;
use App\Models\PartnerPayout;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Single source of truth for "cash available": all-time sales and Operational
 * contributions, minus all-time expenses and non-reversed payouts, up to and
 * including today (Africa/Dar_es_Salaam). Not affected by any dashboard period
 * filter. Purchases/goods receiving, Investment (equipment) contributions, an
 * opening balance, and credit-sale adjustments are deliberately excluded.
 */
class CashService
{
    public function available(): float
    {
        return $this->salesTotal() + $this->operationalTotal() - $this->expensesTotal() - $this->payoutsTotal();
    }

    public function salesTotal(): float
    {
        return (float) DB::table('sales')
            ->where('created_at', '<', $this->cutoff())
            ->sum(DB::raw('quantity * selling_price'));
    }

    public function expensesTotal(): float
    {
        return (float) DB::table('expenses')
            ->where('created_at', '<', $this->cutoff())
            ->sum('amount');
    }

    public function operationalTotal(): float
    {
        if (! Schema::hasTable('partner_contributions')) {
            // Partnership module not installed in this environment yet.
            return 0.0;
        }

        return (float) PartnerContribution::operations()
            ->whereDate('contribution_date', '<=', $this->today())
            ->sum('amount');
    }

    public function payoutsTotal(): float
    {
        if (! Schema::hasTable('partner_payouts')) {
            // Payouts module not installed in this environment yet.
            return 0.0;
        }

        return (float) PartnerPayout::paid()
            ->whereDate('payout_date', '<=', $this->today())
            ->sum('amount');
    }

    /**
     * Exclusive upper bound for created_at comparisons: start of tomorrow in
     * Africa/Dar_es_Salaam, converted to UTC (the timezone timestamps are stored in).
     */
    private function cutoff(): CarbonImmutable
    {
        return CarbonImmutable::now('Africa/Dar_es_Salaam')->startOfDay()->addDay()->setTimezone('UTC');
    }

    /**
     * Today's date in Africa/Dar_es_Salaam, for comparing against date-only columns.
     */
    private function today(): string
    {
        return CarbonImmutable::now('Africa/Dar_es_Salaam')->toDateString();
    }
}
