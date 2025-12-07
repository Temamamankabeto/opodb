<?php

namespace App\Http\Controllers;

use App\Models\NaturalResource;
use Illuminate\Http\Request;

class NaturalResourceController extends Controller
{
   public function index()
{
    $naturals = NaturalResource::orderBy('id', 'desc')->get();
    return view('naturals.index', compact('naturals'));
}

public function create()
{
    return view('naturals.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:natural_resources,name|max:255',
        'unit' => 'nullable|string|max:50',
    ]);

    NaturalResource::create([
        'name' => $request->name,
        'unit' => $request->unit
    ]);

    return redirect()->route('naturals.index')->with('success', 'Natural resource added successfully.');
}

public function edit($id)
{
    $naturals = NaturalResource::findOrFail($id);
    return view('naturals.edit', compact('naturals'));
}

public function update(Request $request, $id)
{
    $naturals = NaturalResource::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:natural_resources,name,' . $naturals->id,
        'unit' => 'nullable|string|max:50',
    ]);

    $naturals->update([
        'name' => $request->name,
        'unit' => $request->unit
    ]);

    return redirect()->route('naturals.index')->with('success', 'Natural resource updated successfully.');
}

public function destroy($id)
{
    $naturals = NaturalResource::findOrFail($id);
    $naturals->delete();

    return redirect()->route('naturals.index')->with('success', 'Natural resource deleted successfully.');
}
}

