<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\ProductiveFriut;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class OrchardProductionController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.productive', compact('cropTypes', 'planYears'));
    }

    // Store the form data
    public function store(Request $request)
    {
        $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
        ]);

        ProductiveFriut::create([
            'baseline_year' => $request->baseline_year,
            'plan_year' => $request->plan_year,

            // Banana
            'banana_production_baseline' => $request->banana_production_baseline,
            'banana_production_plan' => $request->banana_production_plan,
            'banana_land_area_baseline' => $request->banana_land_area_baseline,
            'banana_land_area_plan' => $request->banana_land_area_plan,

            // Avocado
            'avocado_production_baseline' => $request->avocado_production_baseline,
            'avocado_production_plan' => $request->avocado_production_plan,
            'avocado_land_area_baseline' => $request->avocado_land_area_baseline,
            'avocado_land_area_plan' => $request->avocado_land_area_plan,

            // Papaya
            'papaya_production_baseline' => $request->papaya_production_baseline,
            'papaya_production_plan' => $request->papaya_production_plan,
            'papaya_land_area_baseline' => $request->papaya_land_area_baseline,
            'papaya_land_area_plan' => $request->papaya_land_area_plan,

            // Apple
            'apple_production_baseline' => $request->apple_production_baseline,
            'apple_production_plan' => $request->apple_production_plan,
            'apple_land_area_baseline' => $request->apple_land_area_baseline,
            'apple_land_area_plan' => $request->apple_land_area_plan,

            // Other
            'other_production_baseline' => $request->other_production_baseline,
            'other_production_plan' => $request->other_production_plan,
            'other_land_area_baseline' => $request->other_land_area_baseline,
            'other_land_area_plan' => $request->other_land_area_plan,

            // Different Spices
            'spices_production_baseline' => $request->spices_production_baseline,
            'spices_production_plan' => $request->spices_production_plan,
            'spices_land_area_baseline' => $request->spices_land_area_baseline,
            'spices_land_area_plan' => $request->spices_land_area_plan,

            // Coffee
            'coffee_production_baseline' => $request->coffee_production_baseline,
            'coffee_production_plan' => $request->coffee_production_plan,
            'coffee_land_area_baseline' => $request->coffee_land_area_baseline,
            'coffee_land_area_plan' => $request->coffee_land_area_plan,

            // Tea
            'tea_production_baseline' => $request->tea_production_baseline,
            'tea_production_plan' => $request->tea_production_plan,
            'tea_land_area_baseline' => $request->tea_land_area_baseline,
            'tea_land_area_plan' => $request->tea_land_area_plan,

            // Totals
            'total_productive_land_baseline' => $request->total_productive_land_baseline,
            'total_productive_land_plan' => $request->total_productive_land_plan,
            'total_production_baseline' => $request->total_production_baseline,
            'total_production_plan' => $request->total_production_plan,
        ]);

        return redirect()->back()->with('success', 'Agricultural Production Data saved successfully.');
    }
}
