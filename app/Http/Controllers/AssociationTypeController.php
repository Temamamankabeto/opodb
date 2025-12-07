<?php

namespace App\Http\Controllers;

use App\Models\AssociationType;
use Illuminate\Http\Request;

class AssociationTypeController extends Controller
{
    // Display all types
    public function index()
    {
        $types = AssociationType::orderBy('id', 'desc')->get();
        return view('association_types.index', compact('types'));
    }

    // Show create form
    public function create()
    {
        return view('association_types.create');
    }

    // Store new type
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:association_types,name']);
        AssociationType::create($request->only('name'));
        return redirect()->route('sector.association_types.index')->with('success', 'Association type added successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $type = AssociationType::findOrFail($id);
        return view('association_types.edit', compact('type'));
    }

    // Update type
    public function update(Request $request, $id)
    {
        $type = AssociationType::findOrFail($id);
        $request->validate(['name' => 'required|unique:association_types,name,' . $id]);
        $type->update($request->only('name'));
        return redirect()->route('association_types.index')->with('success', 'Association type updated successfully!');
    }

    // Delete type
    public function destroy($id)
    {
        AssociationType::destroy($id);
        return redirect()->route('association_types.index')->with('success', 'Association type deleted successfully!');
    }
}
