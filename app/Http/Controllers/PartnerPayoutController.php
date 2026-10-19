<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerPayout;
use App\Models\PaymentMethod;
use App\Services\CashService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartnerPayoutController extends Controller
{
    public function __construct(private CashService $cashService) {}

    /**
     * Payouts page: cash available, all-time/this-year totals, per-partner totals, and a filtered history.
     */
    public function index(Request $request)
    {
        $available = $this->cashService->available();

        $paidOutAllTime = (float) PartnerPayout::paid()->sum('amount');
        $payoutsCount = PartnerPayout::count();
        $reversedCount = PartnerPayout::reversed()->count();

        $yearStart = CarbonImmutable::now('Africa/Dar_es_Salaam')->startOfYear()->toDateString();
        $paidOutThisYear = (float) PartnerPayout::paid()->whereDate('payout_date', '>=', $yearStart)->sum('amount');

        $partners = Partner::withSum(['contributions as investment_total' => fn ($q) => $q->investment()], 'amount')
            ->withSum(['payouts as paid_out_total' => fn ($q) => $q->paid()], 'amount')
            ->orderBy('name')
            ->get();

        $totalInvestment = (float) $partners->sum('investment_total');
        $partners = $partners->map(function (Partner $partner) use ($totalInvestment) {
            $partner->share = $totalInvestment > 0
                ? round(((float) $partner->investment_total / $totalInvestment) * 100, 1)
                : 0.0;

            return $partner;
        })->sortByDesc('share')->values();

        $paymentMethods = PaymentMethod::orderBy('name')->get();

        $history = PartnerPayout::with(['partner', 'paymentMethod', 'user', 'reverser'])
            ->when($request->filled('partner_id'), fn ($q) => $q->where('partner_id', $request->partner_id))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('payout_date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('payout_date', '<=', $request->to_date))
            ->orderByDesc('payout_date')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('partnership.payouts', compact(
            'available',
            'paidOutAllTime',
            'payoutsCount',
            'reversedCount',
            'paidOutThisYear',
            'partners',
            'paymentMethods',
            'history',
        ));
    }

    /**
     * Record a payout, single or split across partners by share. Blocked if the total exceeds
     * cash available at the moment it is recorded (locked, so concurrent payouts can't both pass).
     */
    public function store(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:split,single',
            'payout_date' => 'required|date|before_or_equal:today',
            'payment_method_id' => 'nullable|integer|exists:payment_method,id',
            'reference' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:255',
        ]);

        if ($request->input('mode') === 'single') {
            $request->validate([
                'partner_id' => 'required|exists:partners,id',
                'amount' => 'required|numeric|min:1',
            ]);

            $amounts = [(int) $request->input('partner_id') => (float) $request->input('amount')];
        } else {
            $request->validate([
                'total_amount' => 'required|numeric|min:1',
                'amounts' => 'required|array',
                'amounts.*' => 'nullable|numeric|min:0',
            ]);

            $amounts = collect($request->input('amounts', []))
                ->map(fn ($amount) => (float) $amount)
                ->filter(fn ($amount) => $amount > 0)
                ->all();

            if (empty($amounts)) {
                session()->flash('alert-danger', 'Enter at least one partner amount.');

                return back()->withInput();
            }

            if (abs(array_sum($amounts) - (float) $request->input('total_amount')) > 1) {
                session()->flash('alert-danger', 'The per-partner amounts must add up to the total.');

                return back()->withInput();
            }
        }

        $total = array_sum($amounts);
        $available = 0.0;
        $blocked = false;

        $lock = Cache::lock('partner-payouts', 10);

        if (! $lock->block(5)) {
            session()->flash('alert-danger', 'The payouts list is busy, please try again.');

            return back()->withInput();
        }

        try {
            DB::transaction(function () use ($amounts, $total, $request, &$available, &$blocked) {
                $available = $this->cashService->available();

                if ($total > $available) {
                    $blocked = true;

                    return;
                }

                $batchId = count($amounts) > 1 ? (string) Str::uuid() : null;
                $payoutNo = $this->nextPayoutNumber();

                foreach ($amounts as $partnerId => $amount) {
                    $payout = new PartnerPayout;
                    $payout->payout_no = $payoutNo;
                    $payout->partner_id = $partnerId;
                    $payout->payout_date = $request->input('payout_date');
                    $payout->amount = $amount;
                    $payout->payment_method_id = $request->input('payment_method_id');
                    $payout->reference = $request->input('reference');
                    $payout->note = $request->input('note');
                    $payout->batch_id = $batchId;
                    $payout->status = PartnerPayout::STATUS_PAID;
                    $payout->created_by = Auth::id();
                    $payout->save();

                    $payoutNo = $this->incrementPayoutNumber($payoutNo);
                }
            });
        } finally {
            $lock->release();
        }

        if ($blocked) {
            session()->flash('alert-danger', 'Only TZS '.formatAmount($available).' is available. Reduce the payout.');

            return back()->withInput();
        }

        session()->flash('alert-success', 'Payout recorded successfully!');

        return back();
    }

    /**
     * Mark a payout as reversed. Never deletes or edits the amount; keeps who/when/why.
     */
    public function reverse(Request $request)
    {
        $request->validate([
            'reversal_reason' => 'required|string|max:255',
        ]);

        $payout = PartnerPayout::find($request->id);

        if (! $payout || $payout->status !== PartnerPayout::STATUS_PAID) {
            session()->flash('alert-danger', 'This payout was already reversed.');

            return back();
        }

        $payout->status = PartnerPayout::STATUS_REVERSED;
        $payout->reversed_at = now();
        $payout->reversed_by = Auth::id();
        $payout->reversal_reason = $request->input('reversal_reason');
        $payout->save();

        session()->flash('alert-success', 'Payout reversed.');

        return back();
    }

    /**
     * Next sequential payout number (PO-0001, PO-0002, ...). Must be called inside the
     * partner-payouts lock so concurrent requests can't allocate the same number.
     */
    private function nextPayoutNumber(): string
    {
        $last = DB::table('partner_payouts')->orderByDesc('id')->value('payout_no');

        return $this->formatPayoutNumber($last ? ((int) substr($last, 3)) + 1 : 1);
    }

    private function incrementPayoutNumber(string $payoutNo): string
    {
        return $this->formatPayoutNumber(((int) substr($payoutNo, 3)) + 1);
    }

    private function formatPayoutNumber(int $sequence): string
    {
        return 'PO-'.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
