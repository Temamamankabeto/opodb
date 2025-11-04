<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FertilizerType;

class FertilizerTypeController extends Controller
{
    public function index()
    {
        $fertilizers = FertilizerType::all();
        return view('fertilizer_types.index', compact('fertilizers'));
    }

    public function create()
    {
        return view('fertilizer_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:fertilizer_types,name',
        ]);

        FertilizerType::create($request->only('name'));

        return redirect()->route('fertilizer.type.index')->with('success', 'Fertilizer type added successfully.');
    }

    public function edit($id)
    {
        $fertilizer = FertilizerType::findOrFail($id);
        return view('fertilizer_types.edit', compact('fertilizer'));
    }

    public function update(Request $request, $id)
    {
        $fertilizer = FertilizerType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:fertilizer_types,name,' . $fertilizer->id,
        ]);

        $fertilizer->update($request->only('name'));

        return redirect()->route('fertilizer.index')->with('success', 'Fertilizer type updated successfully.');
    }

    public function destroy($id)
    {
        FertilizerType::findOrFail($id)->delete();
        return redirect()->route('fertilizer.type.index')->with('success', 'Fertilizer type deleted successfully.');
    }
}
