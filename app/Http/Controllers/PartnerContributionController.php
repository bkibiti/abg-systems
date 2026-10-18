<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerContributionController extends Controller
{
    /**
     * All contributions across partners, filterable by partner, type, and date range.
     */
    public function index(Request $request)
    {
        $contributions = PartnerContribution::with('partner')
            ->when($request->filled('partner_id'), fn ($q) => $q->where('partner_id', $request->partner_id))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('from_date'), fn ($q) => $q->whereDate('contribution_date', '>=', $request->from_date))
            ->when($request->filled('to_date'), fn ($q) => $q->whereDate('contribution_date', '<=', $request->to_date))
            ->orderByDesc('contribution_date')
            ->orderByDesc('id')
            ->get();

        $investmentTotal = (float) $contributions->where('type', PartnerContribution::TYPE_INVESTMENT)->sum('amount');
        $operationsTotal = (float) $contributions->where('type', PartnerContribution::TYPE_OPERATIONS)->sum('amount');

        $partners = Partner::orderBy('name')->get();

        return view('partnership.contributions.index', compact(
            'contributions', 'investmentTotal', 'operationsTotal', 'partners'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'type' => 'required|in:Investment,Operations',
            'contribution_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $contribution = new PartnerContribution;
        $contribution->partner_id = $request->partner_id;
        $contribution->type = $request->type;
        $contribution->contribution_date = $request->contribution_date;
        $contribution->amount = $request->amount;
        $contribution->description = $request->description;
        $contribution->created_by = Auth::user()->id;
        $contribution->save();

        session()->flash('alert-success', 'Contribution added successfully!');

        return back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'type' => 'required|in:Investment,Operations',
            'contribution_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $contribution = PartnerContribution::find($request->id);
        $contribution->partner_id = $request->partner_id;
        $contribution->type = $request->type;
        $contribution->contribution_date = $request->contribution_date;
        $contribution->amount = $request->amount;
        $contribution->description = $request->description;
        $contribution->save();

        session()->flash('alert-success', 'Contribution updated successfully!');

        return back();
    }

    public function destroy(Request $request)
    {
        PartnerContribution::destroy($request->id);

        session()->flash('alert-success', 'Contribution deleted successfully!');

        return back();
    }
}
