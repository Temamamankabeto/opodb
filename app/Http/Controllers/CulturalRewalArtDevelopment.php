<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\CulturalDevelopment;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CulturalRewalArtDevelopment extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');
        return view('cultural.new', compact('cropTypes', 'planYears'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
            'awareness_renewal_baseline' => 'nullable|integer',
            'awareness_renewal_plan' => 'nullable|integer',
            'youth_participation_baseline' => 'nullable|integer',
            'youth_participation_plan' => 'nullable|integer',
        ]);

        $culturalPlan = new CulturalDevelopment();
        $culturalPlan->baseline_year = $request->baseline_year;
        $culturalPlan->plan_year = $request->plan_year;
        $culturalPlan->awareness_renewal_baseline = $request->awareness_renewal_baseline;
        $culturalPlan->awareness_renewal_plan = $request->awareness_renewal_plan;
        $culturalPlan->youth_participation_baseline = $request->youth_participation_baseline;
        $culturalPlan->youth_participation_plan = $request->youth_participation_plan;
        $culturalPlan->zone_id = Auth::user()->zone_id;
        $culturalPlan->woreda_id = Auth::user()->woreda_id;
        $culturalPlan->kebele_id = Auth::user()->kebele_id;
        $culturalPlan->user_id = Auth::user()->id;

        $culturalPlan->save();

        return redirect()->back()->with('success', 'Cultural Plan data saved successfully!');
    }
}
