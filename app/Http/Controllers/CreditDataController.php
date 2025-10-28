<?php

namespace App\Http\Controllers;

use App\Models\CreditDataEntry;
use App\Models\CropType;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class CreditDataController extends Controller
{
    public function create()
    {
        
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
                        ->distinct()
                        ->orderBy('plan_year', 'desc')
                        ->pluck('plan_year');
        
        return view('crop-plan.liqi', compact('cropTypes', 'planYears'));
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',

            'total_debt_baseline_private' => 'nullable|numeric',
            'total_debt_plan_private' => 'nullable|numeric',
            'total_debt_baseline_association' => 'nullable|numeric',
            'total_debt_plan_association' => 'nullable|numeric',
            'total_debt_baseline_total' => 'nullable|numeric',
            'total_debt_plan_total' => 'nullable|numeric',

            'loans_repaid_baseline_private' => 'nullable|numeric',
            'loans_repaid_plan_private' => 'nullable|numeric',
            'loans_repaid_baseline_association' => 'nullable|numeric',
            'loans_repaid_plan_association' => 'nullable|numeric',
            'loans_repaid_baseline_total' => 'nullable|numeric',
            'loans_repaid_plan_total' => 'nullable|numeric',

            'remaining_debt_baseline_private' => 'nullable|numeric',
            'remaining_debt_plan_private' => 'nullable|numeric',
            'remaining_debt_baseline_association' => 'nullable|numeric',
            'remaining_debt_plan_association' => 'nullable|numeric',

            'savings_baseline_association' => 'nullable|numeric',
            'savings_plan_association' => 'nullable|numeric',
            'savings_baseline_private' => 'nullable|numeric',
            'savings_plan_private' => 'nullable|numeric',
            'savings_baseline_total' => 'nullable|numeric',
            'savings_plan_total' => 'nullable|numeric',
        ]);

        // Save to database
        CreditDataEntry::create([
            'baseline_year' => $validated['baseline_year'],
            'plan_year' => $validated['plan_year'],

            'total_debt_baseline_private' => $validated['total_debt_baseline_private'],
            'total_debt_plan_private' => $validated['total_debt_plan_private'],
            'total_debt_baseline_association' => $validated['total_debt_baseline_association'],
            'total_debt_plan_association' => $validated['total_debt_plan_association'],
            'total_debt_baseline_total' => $validated['total_debt_baseline_total'],
            'total_debt_plan_total' => $validated['total_debt_plan_total'],

            'loans_repaid_baseline_private' => $validated['loans_repaid_baseline_private'],
            'loans_repaid_plan_private' => $validated['loans_repaid_plan_private'],
            'loans_repaid_baseline_association' => $validated['loans_repaid_baseline_association'],
            'loans_repaid_plan_association' => $validated['loans_repaid_plan_association'],
            'loans_repaid_baseline_total' => $validated['loans_repaid_baseline_total'],
            'loans_repaid_plan_total' => $validated['loans_repaid_plan_total'],

            'remaining_debt_baseline_private' => $validated['remaining_debt_baseline_private'],
            'remaining_debt_plan_private' => $validated['remaining_debt_plan_private'],
            'remaining_debt_baseline_association' => $validated['remaining_debt_baseline_association'],
            'remaining_debt_plan_association' => $validated['remaining_debt_plan_association'],

            'savings_baseline_association' => $validated['savings_baseline_association'],
            'savings_plan_association' => $validated['savings_plan_association'],
            'savings_baseline_private' => $validated['savings_baseline_private'],
            'savings_plan_private' => $validated['savings_plan_private'],
            'savings_baseline_total' => $validated['savings_baseline_total'],
            'savings_plan_total' => $validated['savings_plan_total'],
        ]);

        return redirect()->route('credit_data.create')->with('success', 'Credit data successfully saved!');
    }
}
