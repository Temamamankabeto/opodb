<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\LivestockSupply;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockSupplyController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');

                return view('crop-plan.livestock_supply', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',

            'ai_cows_baseline' => 'nullable|numeric',
            'ai_cows_plan' => 'nullable|numeric',

            'one_day_chick_baseline' => 'nullable|numeric',
            'one_day_chick_plan' => 'nullable|numeric',

            'twentyone_days_chicks_baseline' => 'nullable|numeric',
            'twentyone_days_chicks_plan' => 'nullable|numeric',

            'fry_fish_baseline' => 'nullable|numeric',
            'fry_fish_plan' => 'nullable|numeric',

            'beehives_baseline' => 'nullable|numeric',
            'beehives_plan' => 'nullable|numeric',

            'animal_feed_land_baseline' => 'nullable|numeric',
            'animal_feed_land_plan' => 'nullable|numeric',

            'oomsha_nyaata_baseline' => 'nullable|numeric',
            'oomsha_nyaata_plan' => 'nullable|numeric',

            'productivity_baseline' => 'nullable|numeric',
            'productivity_plan' => 'nullable|numeric',
        ]);
        $validatedData['created_by'] = Auth::user()->id;
        $validatedData['zone_id'] = Auth::user()->zone_id;
        $validatedData['woreda_id'] = Auth::user()->woreda_id;
        $validatedData['kebele_id'] = Auth::user()->kebele_id;


        LivestockSupply::create($validatedData);

        return redirect()->route('productive.create')->with('success', 'Data saved successfully!');
    }
}
