<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Federation;
use App\Models\Union;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use Illuminate\Http\Request;

class AssociationController extends Controller
{
    public function index()
    {
        $associations = Association::with(['federation', 'union', 'zone', 'woreda', 'kebele'])->latest()->paginate(10);
        return view('associations.index', compact('associations'));
    }

    public function create()
    {
        $federations = Federation::all();
        $zones = Zone::all();
        return view('associations.create', compact('federations', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federation_id' => 'required|exists:federations,id',
            'union_id' => 'required|exists:unions,id',
            'zone_id' => 'required|exists:zones,id',
            'woreda_id' => 'required|exists:woredas,id',
            'kebele_id' => 'required|exists:kebeles,id',
        ]);

        Association::create($request->all());
        return redirect()->route('associations.index')->with('success', 'Association created successfully!');
    }

    public function edit($id)
    {
        $association = Association::findOrFail($id);
        $federations = Federation::all();
        $zones = Zone::all();
        return view('associations.edit', compact('association', 'federations', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federation_id' => 'required|exists:federations,id',
            'union_id' => 'required|exists:unions,id',
            'zone_id' => 'required|exists:zones,id',
            'woreda_id' => 'required|exists:woredas,id',
            'kebele_id' => 'required|exists:kebeles,id',
        ]);

        $association = Association::findOrFail($id);
        $association->update($request->all());
        return redirect()->route('associations.index')->with('success', 'Association updated successfully!');
    }

    public function destroy($id)
    {
        $association = Association::findOrFail($id);
        $association->delete();
        return redirect()->route('associations.index')->with('success', 'Association deleted successfully!');
    }

    public function show($id)
    {
        $association = Association::with(['federation', 'union', 'zone', 'woreda', 'kebele'])->findOrFail($id);
        return view('associations.show', compact('association'));
    }

    // AJAX for cascading selects
    public function getUnions($federation_id)
    {
        $unions = Union::where('federation_id', $federation_id)->get();
        return response()->json($unions);
    }

    public function getWoredas($zone_id)
    {
        $woredas = Woreda::where('zone_id', $zone_id)->get();
        return response()->json($woredas);
    }

    public function getKebeles($woreda_id)
    {
        $kebeles = Kebele::where('woreda_id', $woreda_id)->get();
        return response()->json($kebeles);
    }
}
