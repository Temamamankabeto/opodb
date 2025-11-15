<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationalEffectiveness;

class EducationalEffectivenessController extends Controller
{
     public function index()
    {
        $items = EducationalEffectiveness::orderBy('id', 'DESC')->get();
        return view('educational_effectiveness.index', compact('items'));
    }

    public function create()
    {
        return view('educational_effectiveness.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:educational_effectivenesses,name',
        ]);

        EducationalEffectiveness::create($request->only('name'));

        return redirect()->route('educational_effectiveness.index')->with('success', 'Educational Effectiveness created successfully.');
    }

    public function edit($id)
    {
        $item = EducationalEffectiveness::findOrFail($id);
        return view('educational_effectiveness.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = EducationalEffectiveness::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:educational_effectiveness,name,' . $item->id,
        ]);

        $item->update($request->only('name'));

        return redirect()->route('educational_effectiveness.index')->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        EducationalEffectiveness::destroy($id);

        return redirect()->route('educational_effectiveness.index')->with('success', 'Deleted Successfully.');
    }
}
