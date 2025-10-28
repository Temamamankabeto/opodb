<?php

namespace App\Http\Controllers;

use App\Models\LivestockPlanAchievement;
use Illuminate\Http\Request;

class LiveStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $records = LivestockPlanAchievement::all();
        return view('livestock.index', compact('records'));
    }

    public function create()
    {
        return view('livestock.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_year' => 'required',
            'production_year' => 'nullable',
            'loon_ai_argatee' => 'required',
            'cuccii_guyyaa_tokkoo' => 'required',
            'lukkuu_sookkee' => 'required',
            'cuucii_qurxummii' => 'required',
            'gaagura_ammayaa' => 'required',
            'oomishaa_nyaata_beeylada' => 'required',
        ]);

        LivestockPlanAchievement::create($validated);

        return redirect()->route('livestock.index')->with('success', 'Record created successfully.');
    }

    public function edit($id)
    {
        $record = LivestockPlanAchievement::findOrFail($id);
        return view('livestock.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
        $record = LivestockPlanAchievement::findOrFail($id);

        $validated = $request->validate([
            'plan_year' => 'required',
            'production_year' => 'required',
            'loon_ai_argatee' => 'required|numeric',
            'cuccii_guyyaa_tokkoo' => 'required|numeric',
            'lukkuu_sookkee' => 'required|numeric',
            'cuucii_qurxummii' => 'required|numeric',
            'gaagura_ammayaa' => 'required|numeric',
            'oomishaa_nyaata_beeylada' => 'required|numeric',
        ]);

        $record->update($validated);

        return redirect()->route('livestock.index')->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $record = LivestockPlanAchievement::findOrFail($id);
        $record->delete();

        return redirect()->route('livestock.index')->with('success', 'Record deleted successfully.');
    }
}
