<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Support\Collection;

class PartnershipService
{
    /**
     * Equal-share balance per partner: how far each partner's current Investment
     * total is from an equal split of all Investment contributions. Balances are
     * rounded to whole TZS, with any rounding remainder absorbed by the largest
     * balance so they always sum to exactly zero.
     *
     * @param  Collection<int, Partner>  $partners  partners with investment_total loaded (withSum)
     * @return array{total: float, equalShare: float|null, balances: array<int, int>}
     */
    public function equalShareBalances(Collection $partners, float $totalInvestment): array
    {
        $count = $partners->count();

        if ($totalInvestment <= 0 || $count === 0) {
            return [
                'total' => $totalInvestment,
                'equalShare' => null,
                'balances' => [],
            ];
        }

        $equalShare = $totalInvestment / $count;

        $balances = $partners->mapWithKeys(fn ($partner) => [
            $partner->id => (int) round(((float) ($partner->investment_total ?? 0)) - $equalShare),
        ]);

        $remainder = -$balances->sum();

        if ($remainder !== 0) {
            $largestId = $balances->sortDesc()->keys()->first();
            $balances[$largestId] += $remainder;
        }

        return [
            'total' => $totalInvestment,
            'equalShare' => $equalShare,
            'balances' => $balances->all(),
        ];
    }
}
