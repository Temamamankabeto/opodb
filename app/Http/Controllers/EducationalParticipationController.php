<?php

namespace App\Http\Controllers;

use App\Models\EducationalParticipation;
use Illuminate\Http\Request;

class EducationalParticipationController extends Controller
{
    // List all
    public function index()
    {
        $items = EducationalParticipation::orderBy('id', 'desc')->get();
        return view('educational_participations.index', compact('items'));
    }

    // Show create form
    public function create()
    {
        return view('educational_participations.create');
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:educational_participations,name'
        ]);

        EducationalParticipation::create($request->only('name'));

        return redirect()->route('educational_participations.index')
                         ->with('success', 'Educational participation added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        $item = EducationalParticipation::findOrFail($id);
        return view('educational_participations.edit', compact('item'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $item = EducationalParticipation::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:educational_participations,name,' . $id
        ]);

        $item->update($request->only('name'));

        return redirect()->route('educational_participations.index')
                         ->with('success', 'Educational participation updated successfully!');
    }

    // Delete
    public function destroy($id)
    {
        EducationalParticipation::destroy($id);

        return redirect()->route('educational_participations.index')
                         ->with('success', 'Deleted successfully!');
    }
}
