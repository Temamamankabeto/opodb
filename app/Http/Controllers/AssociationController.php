<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Federation;
use App\Models\Union;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssociationController extends Controller
{
    public function index()
    {
        $associations = Association::with(['federation', 'union', 'zone', 'woreda', 'kebele'])
            ->latest()
            ->paginate(10);

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
            'association_type' => 'required|string|max:100',
        ]);

        $user = Auth::user();
        

        Association::create([
            'name' => $request->name,
            'association_type' => $request->association_type,
            'federation_id' => $request->federation_id,
            'union_id' => $request->union_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'region_id' => $user->region_id,
            'created_by' => $user->id,
        ]);

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
            'association_type' => 'required|string|max:100',
        ]);

        $association = Association::findOrFail($id);
        $user = Auth::user();

        $association->update([
            'name' => $request->name,
            'association_type' => $request->association_type,
            'federation_id' => $request->federation_id,
            'union_id' => $request->union_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'region_id' => $user->region_id,
        ]);

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

    // AJAX cascading selects
    public function getUnions($federation_id)
{
    $unions = Union::where('federation_id', $federation_id)
        ->select('id', 'name')
        ->orderBy('name')
        ->get();

    return response()->json($unions);
}

    public function getWoredas($zone_id)
    {
        return response()->json(Woreda::where('zone_id', $zone_id)->get());
    }

    public function getKebeles($woreda_id)
    {
        return response()->json(Kebele::where('woreda_id', $woreda_id)->get());
    }
}
