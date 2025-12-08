<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KebeleGovernmentProperty;

class KebeleGovernmentPropertyController extends Controller
{
    public function index()
    {
        $items = KebeleGovernmentProperty::orderBy('id', 'DESC')->get();
        return view('govprop.index', compact('items'));
    }

    public function create()
    {
        return view('govprop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:kebele_government_properties,name',
            'unit' => 'nullable|string|max:100',
        ]);

        KebeleGovernmentProperty::create([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return redirect()->route('govprop.index')->with('success', 'Added Successfully!');
    }

    public function edit($id)
    {
        $item = KebeleGovernmentProperty::findOrFail($id);
        return view('govprop.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = KebeleGovernmentProperty::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:kebele_government_properties,name,' . $item->id,
            'unit' => 'nullable|string|max:100',
        ]);

        $item->update([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return redirect()->route('govprop.index')->with('success', 'Updated Successfully!');
    }

    public function destroy($id)
    {
        KebeleGovernmentProperty::destroy($id);

        return redirect()->route('govprop.index')->with('success', 'Deleted Successfully!');
    }
}