<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\InstitutionType;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    // List all
    public function index()
    {
        $institutions = Institution::with('type')->orderBy('id', 'desc')->get();
        return view('institutions.index', compact('institutions'));
    }

    // Show create form
    public function create()
    {
        $types = InstitutionType::all();
        return view('institutions.create', compact('types'));
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:institutions,name',
            'institution_type_id' => 'required|exists:institution_types,id',
        ]);

        Institution::create($request->only('name', 'institution_type_id'));
        return redirect()->route('institutions.index')->with('success', 'Institution added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $institution = Institution::findOrFail($id);
        $types = InstitutionType::all();
        return view('institutions.edit', compact('institution', 'types'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        $request->validate([
            'name' => 'required|unique:institutions,name,' . $id,
            'institution_type_id' => 'required|exists:institution_types,id',
        ]);

        $institution->update($request->only('name', 'institution_type_id'));
        return redirect()->route('institutions.index')->with('success', 'Institution updated successfully!');
    }

    // Delete
    public function destroy($id)
    {
        Institution::destroy($id);
        return redirect()->route('institutions.index')->with('success', 'Institution deleted successfully!');
    }
}
