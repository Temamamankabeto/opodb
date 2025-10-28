<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\RuralGovernmentAssetPlan;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RurarGovermentAssetController extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');
        return view('rural_goverment_asset.new', compact('cropTypes', 'planYears'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_year' => 'required|integer|min:2000|max:2100',
//            'kebele_id' => 'required|exists:kebeles,id', // kebele passed from session or form
            // Add more field validations as needed
        ]);

        $exists = RuralGovernmentAssetPlan::where('user_id', Auth::id())
            ->where('kebele_id', $request->kebele_id)
            ->where('plan_year', $request->plan_year)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'A record already exists for this plan year and kebele.');
        }

        $asset = new RuralGovernmentAssetPlan();
        $asset->user_id = Auth::user()->id;
        $asset->kebele_id = Auth::user()->kebele_id;
        $asset->zone_id = Auth::user()->zone_id;
        $asset->woreda_id = Auth::user()->woreda_id;
        $asset->plan_year = $request->plan_year;

        $asset->bg_members_plan = $request->bg_members_plan;
        $asset->bg_contributions_plan = $request->bg_contributions_plan;
        $asset->bg_crop_quintal_plan = $request->bg_crop_quintal_plan;
        $asset->bg_monetary_value_plan = $request->bg_monetary_value_plan;
        $asset->bg_cash_plan = $request->bg_cash_plan;
        $asset->bg_idaama_plan = $request->bg_idaama_plan;
        $asset->gs_trained_plan = $request->gs_trained_plan;
        $asset->gs_assigned_daily_plan = $request->gs_assigned_daily_plan;
        $asset->citizenship_service_plan = $request->citizenship_service_plan;
        $asset->family_prosperity_plan = $request->family_prosperity_plan;
        $asset->loan_not_paid_plan = $request->loan_not_paid_plan;
        $asset->loan_taken_this_year_plan = $request->loan_taken_this_year_plan;
        $asset->loan_idaama_plan = $request->loan_idaama_plan;
        $asset->loan_paid_saving_plan = $request->loan_paid_saving_plan;
        $asset->loan_paid_coop_plan = $request->loan_paid_coop_plan;
        $asset->loan_paid_idaama_plan = $request->loan_paid_idaama_plan;

        $asset->save();

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
