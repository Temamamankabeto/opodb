<?php

namespace App\Http\Controllers;

use App\Models\SoilType;
use Illuminate\Http\Request;

class SoilTypeController extends Controller
{
    public function index()
    {
        $soils = SoilType::all();
        return view('soil_types.index', compact('soils'));
    }

    public function create()
    {
        return view('soil_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:soil_types,name|max:255',
        ]);

        SoilType::create($request->only('name'));

        return redirect()->route('soil.index')->with('success', 'Soil type added successfully.');
    }

    public function edit($id)
    {
        $soil = SoilType::findOrFail($id);
        return view('soil_types.edit', compact('soil'));
    }

    public function update(Request $request, $id)
    {
        $soil = SoilType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:soil_types,name,' . $soil->id,
        ]);

        $soil->update($request->only('name'));

        return redirect()->route('soil.index')->with('success', 'Soil type updated successfully.');
    }

    public function destroy($id)
    {
        SoilType::findOrFail($id)->delete();

        return redirect()->route('soil.index')->with('success', 'Soil type deleted successfully.');
    }
}
