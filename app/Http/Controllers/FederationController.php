<?php

namespace App\Http\Controllers;

use App\Models\Federation;
use Illuminate\Http\Request;

class FederationController extends Controller
{
    public function index(Request $request)
    {
        $query = Federation::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $federations = $query->latest()->paginate(10)->withQueryString();

        return view('federations.index', compact('federations'));
    }


    public function create()
    {
        return view('federations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Federation::create(['name' => $request->name]);

        return redirect()->route('federations.index')->with('success', 'Federation created successfully!');
    }

    public function show($id)
    {
        $federation = Federation::findOrFail($id);
        return view('federations.show', compact('federation'));
    }

    public function edit($id)
    {
        $federation = Federation::findOrFail($id);
        return view('federations.edit', compact('federation'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $federation = Federation::findOrFail($id);
        $federation->update(['name' => $request->name]);

        return redirect()->route('federations.index')->with('success', 'Federation updated successfully!');
    }

    public function destroy($id)
    {
        $federation = Federation::findOrFail($id);
        $federation->delete();

        return redirect()->route('federations.index')->with('success', 'Federation deleted successfully!');
    }
}
