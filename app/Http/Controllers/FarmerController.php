<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Models\Union;
use App\Models\Association;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $farmers = Farmer::with(['zone', 'woreda', 'kebele', 'union', 'association'])
            ->latest()->paginate(10);

        return view('farmers.index', compact('farmers'));
    }

    public function create()
    {
        $zones = Zone::all();
        $woredas = Woreda::all();
        $kebeles = Kebele::all();
        $unions = Union::all();
        $associations = Association::all();
        return view('farmers.create', compact('zones', 'woredas', 'kebeles', 'unions', 'associations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'farmer_name' => 'required|string|max:255',
            'gender' => 'required',
            'association_id' => 'required|exists:associations,id',
            'union_id' => 'required|exists:unions,id',
        ]);

        Farmer::create($request->all());

        return redirect()->route('farmers.index')->with('success', 'Farmer created successfully!');
    }

    public function edit($id)
    {
        $farmer = Farmer::findOrFail($id);
        $zones = Zone::all();
        $woredas = Woreda::all();
        $kebeles = Kebele::all();
        $unions = Union::all();
        $associations = Association::all();

        return view('farmers.edit', compact('farmer', 'zones', 'woredas', 'kebeles', 'unions', 'associations'));
    }

    public function update(Request $request, $id)
    {
        $farmer = Farmer::findOrFail($id);
        $farmer->update($request->all());

        return redirect()->route('farmers.index')->with('success', 'Farmer updated successfully!');
    }

    public function destroy($id)
    {
        Farmer::findOrFail($id)->delete();
        return redirect()->route('farmers.index')->with('success', 'Farmer deleted successfully!');
    }

    public function show($id)
    {
        $farmer = Farmer::with(['zone', 'woreda', 'kebele', 'union', 'association'])->findOrFail($id);
        return view('farmers.show', compact('farmer'));
    }
}
