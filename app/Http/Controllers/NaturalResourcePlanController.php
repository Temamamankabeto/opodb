<?php

namespace App\Http\Controllers;

use App\Models\NaturalResource;
use Illuminate\Http\Request;
use App\Models\NaturalResourcePlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Add this import

class NaturalResourcePlanController extends Controller
{
    public function index()
    {
        $plans = NaturalResourcePlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'user'])
            ->where('sector_id', Auth::user()->sector_id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($plan) {
                $plan->natural_data = is_array($plan->natural_data) ? $plan->natural_data : json_decode($plan->natural_data, true);
                return $plan;
            });

        return view('sector.natural_resource.index', compact('plans'));
    }

    public function create()
    {
        $natural = NaturalResource::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.natural_resource.create', compact('natural', 'years'));
    }

    public function store(Request $request)
    {
        // Validate basic fields and ensure natural_data is a string (JSON)
        $validated = $request->validate([
            'year' => 'required|string',
            'natural_data' => 'required|string',
        ]);

        // Decode JSON data
        $naturalData = json_decode($request->natural_data, true);

        if (!is_array($naturalData)) {
            return back()->withErrors(['natural_data' => 'Invalid natural data format.'])->withInput();
        }

        // Create a custom validator for the array data
        $validator = Validator::make(['natural_data' => $naturalData], [
            'natural_data' => 'required|array|min:1',
            'natural_data.*.name' => 'required|string',
            'natural_data.*.amount' => 'required|numeric|min:0.01',
            'natural_data.*.unit' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();

        // Prevent duplicate year for same kebele and sector
        $yearExists = NaturalResourcePlan::where('year', $request->year)
            ->where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
            ->exists();

        if ($yearExists) {
            return back()->withErrors([
                'year' => "A natural resource plan already exists for year {$request->year} in your kebele and sector. Please edit the existing plan instead of creating a new one."
            ])->withInput();
        }

        // Add resource IDs
        foreach ($naturalData as &$resource) {
            $model = NaturalResource::where('name', $resource['name'])->first();
            $resource['id'] = $model?->id;
        }

        // Create new plan
        NaturalResourcePlan::create([
            'year' => $request->year,
            'natural_data' => $naturalData,
            'sector_id' => $user->sector_id,
            'kebele_id' => $user->kebele_id,
            'woreda_id' => $user->woreda_id,
            'zone_id' => $user->zone_id,
            'region_id' => $user->region_id,
            'created_by' => $user->id,
        ]);

        return redirect()->route('sector.natural-plans.index')
            ->with('success', 'Natural resource plan created successfully!');
    }

    public function edit($id)
    {
        $plan = NaturalResourcePlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $natural = NaturalResource::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.natural-resource.edit', compact('plan', 'natural', 'years'));
    }

    public function update(Request $request, $id)
    {
        $naturalResourcePlan = NaturalResourcePlan::findOrFail($id);

        // Validate basic fields
        $validated = $request->validate([
            'year' => 'required|string',
            'natural_data' => 'required|string',
        ]);

        // Decode JSON data
        $naturalData = json_decode($request->natural_data, true);

        if (!is_array($naturalData)) {
            return back()->withErrors(['natural_data' => 'Invalid natural data format.'])->withInput();
        }

        // Create a custom validator for the array data
        $validator = Validator::make(['natural_data' => $naturalData], [
            'natural_data' => 'required|array|min:1',
            'natural_data.*.name' => 'required|string',
            'natural_data.*.amount' => 'required|numeric|min:0.01',
            'natural_data.*.unit' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Add resource IDs
        foreach ($naturalData as &$resource) {
            $model = NaturalResource::where('name', $resource['name'])->first();
            $resource['id'] = $model?->id;
        }

        // Update plan
        $naturalResourcePlan->update([
            'year' => $request->year,
            'natural_data' => $naturalData,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.natural-plans.index')
            ->with('success', 'Natural resource plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = NaturalResourcePlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.natural-plans.index')->with('success', 'Natural resource plan deleted successfully!');
    }
}