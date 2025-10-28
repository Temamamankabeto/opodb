<?php

namespace App\Http\Controllers;

use App\Models\Seedling;
use App\Models\SeedlingType;
use Illuminate\Http\Request;

class SeedlingTypeController extends Controller
{
    public function index()
    {
        $types = SeedlingType::all();
        return view('seedling_types.index', compact('types'));
    }

    public function create()
    {
        return view('seedling_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        SeedlingType::create($request->all());
        return redirect()->route('seedling-types.index')->with('success', 'Seedling type added.');
    }

    public function edit(SeedlingType $seedlingType)
    {
        return view('seedling_types.edit', compact('seedlingType'));
    }

    public function update(Request $request, SeedlingType $seedlingType)
    {
        $request->validate(['name' => 'required|string']);
        $seedlingType->update($request->all());
        return redirect()->route('seedling-types.index')->with('success', 'Seedling type updated.');
    }

    public function destroy(SeedlingType $seedlingType)
    {
        $seedlingType->delete();
        return redirect()->route('seedling-types.index')->with('success', 'Seedling type deleted.');
    }





    public function indexs()
    {
        $seedlings = Seedling::with('seedlingType')->get();
        return view('seedlings.index', compact('seedlings'));
    }

    public function creates()
    {
        $types = SeedlingType::all();
        return view('seedlings.create', compact('types'));
    }

    public function stores(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'seedlingtype_id' => 'required|exists:seedling_types,id',
        ]);

        Seedling::create($request->all());
        return redirect()->route('seedlings.index')->with('success', 'Seedling created successfully.');
    }

    public function edits($id)
    {
        $seedling = Seedling::findOrFail($id);
        $types = SeedlingType::all();
        return view('seedlings.edit', compact('seedling', 'types'));
    }

    public function updates(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'seedlingtype_id' => 'required|exists:seedling_types,id',
        ]);

        $seedling = Seedling::findOrFail($id);
        $seedling->update($request->all());

        return redirect()->route('seedlings.index')->with('success', 'Seedling updated successfully.');
    }

    public function destroys($id)
    {
        $seedling = Seedling::findOrFail($id);
        $seedling->delete();
        return redirect()->route('seedlings.index')->with('success', 'Seedling deleted.');
    }
}
