<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\Livestockproduction;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class LivestockProductionController extends Controller
{
    public function create()
    {
        $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.livestock_production', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
            // Add more validations if necessary
        ]);

        $data = $request->all();

        Livestockproduction::create([
            'baseline_year' => $data['baseline_year'],
            'plan_year' => $data['plan_year'],
            'calves_ai_baseline' => $data['calves_ai_baseline'] ?? null,
            'calves_ai_plan' => $data['calves_ai_plan'] ?? null,
            'eggs_produced_baseline' => $data['eggs_produced_baseline'] ?? null,
            'eggs_produced_plan' => $data['eggs_produced_plan'] ?? null,
            'laying_chickens_baseline' => $data['laying_chickens_baseline'] ?? null,
            'laying_chickens_plan' => $data['laying_chickens_plan'] ?? null,
            'eggs_per_chicken_baseline' => $data['eggs_per_chicken_baseline'] ?? null,
            'eggs_per_chicken_plan' => $data['eggs_per_chicken_plan'] ?? null,
            'meat_chicken_baseline' => $data['meat_chicken_baseline'] ?? null,
            'meat_chicken_plan' => $data['meat_chicken_plan'] ?? null,
            'meat_chickens_count_baseline' => $data['meat_chickens_count_baseline'] ?? null,
            'meat_chickens_count_plan' => $data['meat_chickens_count_plan'] ?? null,
            'meat_per_chicken_baseline' => $data['meat_per_chicken_baseline'] ?? null,
            'meat_per_chicken_plan' => $data['meat_per_chicken_plan'] ?? null,
            'honey_produced_baseline' => $data['honey_produced_baseline'] ?? null,
            'honey_produced_plan' => $data['honey_produced_plan'] ?? null,
            'honey_per_beehive_baseline' => $data['honey_per_beehive_baseline'] ?? null,
            'honey_per_beehive_plan' => $data['honey_per_beehive_plan'] ?? null,
            'fish_supplied_baseline' => $data['fish_supplied_baseline'] ?? null,
            'fish_supplied_plan' => $data['fish_supplied_plan'] ?? null,
            'milk_produced_baseline' => $data['milk_produced_baseline'] ?? null,
            'milk_produced_plan' => $data['milk_produced_plan'] ?? null,
            'lactating_cows_baseline' => $data['lactating_cows_baseline'] ?? null,
            'lactating_cows_plan' => $data['lactating_cows_plan'] ?? null,
            'milk_per_cow_baseline' => $data['milk_per_cow_baseline'] ?? null,
            'milk_per_cow_plan' => $data['milk_per_cow_plan'] ?? null,
        ]);

        return redirect()->route('livestock.production.create')->with('success', 'Livestock and Fisheries data saved successfully!');
    }
}
