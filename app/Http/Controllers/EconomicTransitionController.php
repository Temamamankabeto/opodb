<?php

namespace App\Http\Controllers;

use App\Models\EcononomicTransition;
use Illuminate\Http\Request;

class EconomicTransitionController extends Controller
{
    //
    public function create()
    {
        return view('economic.new');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_year' => 'required|integer',
            'activities.*.amount' => 'required|numeric|min:0',
            'activities.*.family' => 'nullable|numeric|min:0',
            'activities.*.cooperative' => 'nullable|numeric|min:0',
            'activities.*.community' => 'nullable|numeric|min:0',
            'construction_cost' => 'nullable|numeric',
        ]);

        foreach ($validated['activities'] as $key => $data) {
            EcononomicTransition::create([
                'amount' => $data['amount'],
                'plan_year' => $validated['plan_year'],
                'activity_name' => $key, // Pass activity label from Blade if needed
                'family' => $data['family'],
                'cooperative' => $data['cooperative'],
                'community' => $data['community'],
                'total' => $data['total'] ?? ($data['family'] + $data['cooperative'] + $data['community']),
                'construction_cost' => $validated['construction_cost'] ?? null,
                // Include zone_id, woreda_id, etc., if passed
            ]);
        }

        return redirect()->back()->with('success', 'Data saved successfully!');
    }

    public function report(Request $request)
    {
        $facilities = EcononomicTransition::query();

        if ($request->filled('baseline_year')) {
            $facilities->where('baseline_year', $request->baseline_year);
        }

        if ($request->filled('plan_year')) {
            $facilities->where('plan_year', $request->plan_year);
        }

        $data = $facilities->get();

        return view('economic.report', compact('data'));
    }

}
