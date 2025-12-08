<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CulturalRevival;

class CulturalRevivalController extends Controller
{
    public function index()
    {
        $items = CulturalRevival::orderBy('id', 'DESC')->get();
        return view('cultural_revival.index', compact('items'));
    }

    public function create()
    {
        return view('cultural_revival.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:cultural_revivals,name',
            'unit' => 'nullable|string|max:100',
        ]);

        CulturalRevival::create($request->only('name', 'unit'));

        return redirect()->route('cultural_revival.index')
                         ->with('success', 'Cultural Revival added successfully.');
    }

    public function edit($id)
    {
        $item = CulturalRevival::findOrFail($id);
        return view('cultural_revival.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = CulturalRevival::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:cultural_revivals,name,' . $item->id,
            'unit' => 'nullable|string|max:100',
        ]);

        $item->update($request->only('name', 'unit'));

        return redirect()->route('cultural_revival.index')
                         ->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        CulturalRevival::destroy($id);

        return redirect()->route('cultural_revival.index')
                         ->with('success', 'Deleted successfully.');
    }
}