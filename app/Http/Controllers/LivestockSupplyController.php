<?php

namespace App\Http\Controllers;

use App\Models\LivestockSupply;
use Illuminate\Http\Request;

class LivestockSupplyController extends Controller
{
    public function index()
    {
        $supplies = LivestockSupply::orderBy('id', 'desc')->get();
        return view('livestock_supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('livestock_supplies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:livestock_supplies,name|max:255',
        ]);

        LivestockSupply::create(['name' => $request->name]);

        return redirect()->route('livestock_supplies.index')->with('success', 'Livestock supply added successfully.');
    }

    public function edit($id)
    {
        $supply = LivestockSupply::findOrFail($id);
        return view('livestock_supplies.edit', compact('supply'));
    }

    public function update(Request $request, $id)
    {
        $supply = LivestockSupply::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:livestock_supplies,name,' . $supply->id,
        ]);

        $supply->update(['name' => $request->name]);

        return redirect()->route('livestock_supplies.index')->with('success', 'Livestock supply updated successfully.');
    }

    public function destroy($id)
    {
        $supply = LivestockSupply::findOrFail($id);
        $supply->delete();

        return redirect()->route('livestock_supplies.index')->with('success', 'Livestock supply deleted successfully.');
    }
}
