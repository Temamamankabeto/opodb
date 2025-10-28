<?php

namespace App\Http\Controllers;

use App\Models\Haamaa;
use Illuminate\Http\Request;

class HaamaaController extends Controller
{
    // Display all records
    public function index()
    {
        $haamaas = Haamaa::all();
        return view('haamaas.index', compact('haamaas'));
    }

    // Show form for creating a new record
    public function create()
    {
        return view('haamaas.create');
    }

    // Store a new record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lafa_maashinaan_haamame' => 'nullable|numeric',
            'maashina_haamaa_hirmaate' => 'nullable|integer',
            'lafa_harkaan_haamame' => 'nullable|numeric',
            'qotee_bulaa_dhi_haamame' => 'nullable|integer',
            'qotee_bulaa_du_haamame' => 'nullable|integer',
            'kan_tumame' => 'nullable|integer',
        ]);

        Haamaa::create($validated);

        return redirect()->route('haamaas.index')->with('success', 'Record created successfully!');
    }

    // Show form to edit an existing record
    public function edit($id)
    {
        $haamaa = Haamaa::findOrFail($id);
        return view('haamaas.edit', compact('haamaa'));
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'lafa_maashinaan_haamame' => 'nullable|numeric',
            'maashina_haamaa_hirmaate' => 'nullable|integer',
            'lafa_harkaan_haamame' => 'nullable|numeric',
            'qotee_bulaa_dhi_haamame' => 'nullable|integer',
            'qotee_bulaa_du_haamame' => 'nullable|integer',
            'kan_tumame' => 'nullable|integer',
        ]);

        $haamaa = Haamaa::findOrFail($id);
        $haamaa->update($validated);

        return redirect()->route('haamaas.index')->with('success', 'Record updated successfully!');
    }

    // Delete an existing record
    public function destroy($id)
    {
        $haamaa = Haamaa::findOrFail($id);
        $haamaa->delete();

        return redirect()->route('haamaas.index')->with('success', 'Record deleted successfully!');
    }
}
