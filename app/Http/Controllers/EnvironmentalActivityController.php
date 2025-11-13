<?php

namespace App\Http\Controllers;

use App\Models\EnvironmentalActivity;
use Illuminate\Http\Request;

class EnvironmentalActivityController extends Controller
{
    public function index()
    {
        $naturals = EnvironmentalActivity::orderBy('id', 'desc')->get();
        return view('enviromental-activity.index', compact('naturals'));
    }

    public function create()
    {
        return view('enviromental-activity.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:environmental_activities,name|max:255',
        ]);

        EnvironmentalActivity::create(['name' => $request->name]);

        return redirect()->route('enviromental-activity.index')->with('success', 'enviromental activity added successfully.');
    }

    public function edit($id)
    {
        $naturals = EnvironmentalActivity::findOrFail($id);
        return view('enviromental-activity.edit', compact('naturals'));
    }

    public function update(Request $request, $id)
    {
        $naturals = EnvironmentalActivity::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:environmental_activities,name,' . $naturals->id,
        ]);

        $naturals->update(['name' => $request->name]);

        return redirect()->route('enviromental-activity.index')->with('success', 'enviromental activity.index updated successfully.');
    }

    public function destroy($id)
    {
        $naturals = EnvironmentalActivity::findOrFail($id);
        $naturals->delete();

        return redirect()->route('enviromental activity.index')->with('success', 'enviromental activity.indexdeleted successfully.');
    }
}
