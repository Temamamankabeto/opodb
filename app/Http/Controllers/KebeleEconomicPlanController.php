<?php

namespace App\Http\Controllers;

use App\Models\KebeleEconomicPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebeleEconomicPlanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = KebeleEconomicPlan::where('kebele_id', $user->kebele_id)
            ->where('sector_id', $user->sector_id)
            ->orderBy('year', 'desc')
            ->get();

        return view('sector.economic.index', compact('plans'));
    }

    // Show create form
    public function create()
    {
        return view('sector.economic.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'year' => 'required|digits:4',
            'activity' => 'required|string|max:255',
            'target_group' => 'required|string|max:255',
            'timeline' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'responsible_staff' => 'nullable|string|max:255',
            'expected_outcome' => 'nullable|string',
        ]);

        KebeleEconomicPlan::create([
            'region_id' => $user->region_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'sector_id' => $user->sector_id,
            'created_by' => $user->id,
            ...$validated,
        ]);

        return redirect()->route('sector.economic.index')->with('success', 'Economic Plan added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $plan = KebeleEconomicPlan::findOrFail($id);
        return view('sector.economic.edit', compact('plan'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $plan = KebeleEconomicPlan::findOrFail($id);

        $validated = $request->validate([
            'year' => 'required|digits:4',
            'activity' => 'required|string|max:255',
            'target_group' => 'required|string|max:255',
            'timeline' => 'required|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'responsible_staff' => 'nullable|string|max:255',
            'expected_outcome' => 'nullable|string',
        ]);

        $plan->update($validated);

        return redirect()->route('sector.economic.index')->with('success', 'Economic plan updated successfully!');
    }

    // Delete record
    public function destroy($id)
    {
        $plan = KebeleEconomicPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('sector.economic.index')->with('success', 'Economic plan deleted successfully!');
    }
}
