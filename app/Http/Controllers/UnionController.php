<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Union;
use App\Models\Federation;
use Illuminate\Http\Request;

class UnionController extends Controller
{
    public function index(Request $request)
    {
        $query = Union::with('federation');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $unions = $query->latest()->paginate(10)->withQueryString();

        return view('unions.index', compact('unions'));
    }

    public function create()
    {
        $federations = Federation::all();
        $zones = Zone::all();
        return view('unions.create', compact('federations', 'zones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federation_id' => 'required|exists:federations,id',
            'zone_id' => 'required|integer',
        ]);

        Union::create($request->all());

        return redirect()->route('unions.index')->with('success', 'Union created successfully!');
    }

    public function edit($id)
    {
        $union = Union::findOrFail($id);
        $federations = Federation::all();
        $zones = Zone::all();
        return view('unions.edit', compact('union', 'federations', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'federation_id' => 'required|exists:federations,id',
            'zone_id' => 'required|integer',
        ]);

        $union = Union::findOrFail($id);
        $union->update($request->all());

        return redirect()->route('unions.index')->with('success', 'Union updated successfully!');
    }

    public function destroy($id)
    {
        $union = Union::findOrFail($id);
        $union->delete();

        return redirect()->route('unions.index')->with('success', 'Union deleted successfully!');
    }

    public function show($id)
    {
        $union = Union::with('federation')->findOrFail($id);
        return view('unions.show', compact('union'));
    }
}
