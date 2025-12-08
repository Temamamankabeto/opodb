<?php

namespace App\Http\Controllers;

use App\Models\Health;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthController extends Controller
{
    public function index()
    {
        $items = Health::orderBy('id', 'DESC')->get();
        return view('health.index', compact('items'));
    }

    public function create()
    {
        return view('health.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:healths,name',
            'unit' => 'required|string|max:50',
            'type' => 'required|string|max:100',
        ]);

        Health::create($request->only('name', 'unit', 'type'));

        return redirect()->route('health.index')
                         ->with('success', 'Health entry created successfully.');
    }

    public function edit($id)
    {
        $item = Health::findOrFail($id);
        return view('health.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Health::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:healths,name,' . $item->id,
            'unit' => 'required|string|max:50',
            'type' => 'required|string|max:100',
        ]);

        $item->update($request->only('name', 'unit', 'type'));

        return redirect()->route('health.index')
                         ->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        Health::destroy($id);

        return redirect()->route('health.index')
                         ->with('success', 'Deleted Successfully.');
    }
}
