<?php

namespace App\Http\Controllers;

use App\Models\Fertilizer;
use Illuminate\Http\Request;
use App\Models\FertilizerType;

class FertilizerController extends Controller
{
    public function index()
    {
        $fertilizers = Fertilizer::with('fertilizerType')->get();
        return view('fertilizers.index', compact('fertilizers'));
    }

    public function create()
    {
        $types = FertilizerType::all();
        return view('fertilizers.create', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fertilizer_type_id' => 'required|exists:fertilizer_types,id',
            'name' => 'required|string|max:255',
        ]);

        Fertilizer::create($request->only('fertilizer_type_id', 'name'));

        return redirect()->route('fertilizer.index')->with('success', 'Fertilizer added successfully.');
    }

    public function edit($id)
    {
        $fertilizer = Fertilizer::findOrFail($id);
        $types = FertilizerType::all();
        return view('fertilizers.edit', compact('fertilizer', 'types'));
    }

    public function update(Request $request, $id)
    {
        $fertilizer = Fertilizer::findOrFail($id);

        $request->validate([
            'fertilizer_type_id' => 'required|exists:fertilizer_types,id',
            'name' => 'required|string|max:255',
        ]);

        $fertilizer->update($request->only('fertilizer_type_id', 'name'));

        return redirect()->route('fertilizer.index')->with('success', 'Fertilizer updated successfully.');
    }

    public function destroy($id)
    {
        Fertilizer::findOrFail($id)->delete();
        return redirect()->route('fertilizer.index')->with('success', 'Fertilizer deleted successfully.');
    }
}
