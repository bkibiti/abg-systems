<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerContribution;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PartnerController extends Controller
{
    /**
     * Partners & shares overview. Shares are based on investment contributions only.
     */
    public function index()
    {
        $partners = Partner::withSum(['contributions as investment_total' => fn ($q) => $q->investment()], 'amount')
            ->withSum(['contributions as operations_total' => fn ($q) => $q->operations()], 'amount')
            ->withCount('contributions')
            ->get();

        $totalInvestment = (float) $partners->sum('investment_total');
        $totalOperations = (float) $partners->sum('operations_total');
        $totalContributed = $totalInvestment + $totalOperations;
        $totalContributions = (int) $partners->sum('contributions_count');

        $partners = $partners->map(function (Partner $partner) use ($totalInvestment) {
            $partner->share = $totalInvestment > 0
                ? round(((float) $partner->investment_total / $totalInvestment) * 100, 1)
                : null;

            return $partner;
        })->sortByDesc('share')->values();

        return view('partnership.overview', compact(
            'partners', 'totalInvestment', 'totalOperations', 'totalContributed', 'totalContributions'
        ));
    }

    /**
     * A single partner's contributions and share.
     */
    public function show(Partner $partner)
    {
        $partner->loadSum(['contributions as investment_total' => fn ($q) => $q->investment()], 'amount');
        $partner->loadSum(['contributions as operations_total' => fn ($q) => $q->operations()], 'amount');

        $totalInvestment = (float) PartnerContribution::investment()->sum('amount');
        $share = $totalInvestment > 0
            ? round(((float) $partner->investment_total / $totalInvestment) * 100, 1)
            : null;

        $contributions = $partner->contributions()->orderByDesc('contribution_date')->orderByDesc('id')->get();
        $payouts = $partner->payouts()->with(['paymentMethod'])->orderByDesc('payout_date')->orderByDesc('id')->get();
        $paidOutTotal = $partner->paidOutTotal();
        $partners = Partner::orderBy('name')->get();

        return view('partnership.partners.show', compact(
            'partner', 'share', 'totalInvestment', 'contributions', 'payouts', 'paidOutTotal', 'partners'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:partners,name',
            'phone' => 'nullable|string|max:45',
            'email' => 'nullable|email|max:100',
            'joined_on' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $partner = new Partner;
        $partner->name = $request->name;
        $partner->phone = $request->phone;
        $partner->email = $request->email;
        $partner->joined_on = $request->joined_on;
        $partner->notes = $request->notes;
        $partner->created_by = Auth::user()->id;
        $partner->save();

        session()->flash('alert-success', 'Partner added successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $partner = Partner::find($request->id);

        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('partners', 'name')->ignore($partner->id)],
            'phone' => 'nullable|string|max:45',
            'email' => 'nullable|email|max:100',
            'joined_on' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $partner->name = $request->name;
        $partner->phone = $request->phone;
        $partner->email = $request->email;
        $partner->joined_on = $request->joined_on;
        $partner->notes = $request->notes;
        $partner->save();

        session()->flash('alert-success', 'Partner updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $partner = Partner::find($request->id);

            if ($partner->contributions()->exists()) {
                session()->flash('alert-danger', 'This partner has contributions recorded and cannot be deleted.');

                return back();
            }

            $partner->delete();
            session()->flash('alert-success', 'Partner deleted successfully!');
        } catch (Exception $exception) {
            session()->flash('alert-danger', 'Partner in use!');
        }

        return back();
    }
}
