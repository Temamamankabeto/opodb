<?php

namespace App\Http\Controllers;

use App\Models\Kunuunsa;
use Illuminate\Http\Request;

class KunuunsaController extends Controller
{
    // Display all Kunuunsas
    public function index()
    {
        $kunuunsas = Kunuunsa::all();
        return view('kunuunsas.index', compact('kunuunsas'));
    }

    // Show the form for creating a new Kunuunsa
    public function create()
    {
        return view('calla-guddiftuu.create');
    }

    // Store a newly created Kunuunsa
    public function store(Request $request)
    {
        $request->validate([
            'lafa_harkaan_aramame' => 'nullable|numeric',
            'lafa_keemikaalaan_aramame' => 'nullable|numeric',
            'hamma_keemikaalaa_litiraa' => 'nullable|numeric',
            'lafa_dhibeen_tooatame' => 'nullable|numeric',
            'lafa_ilbiisni_toatame' => 'nullable|numeric',
        ]);

        Kunuunsa::create($request->all());

        return redirect()->route('kunuunsas.index')->with('success', 'Kunuunsa created successfully');
    }

    // Show the form for editing the specified Kunuunsa
    public function edit($id)
    {
        $kunuunsa = Kunuunsa::findOrFail($id);
        return view('kunuunsas.edit', compact('kunuunsa'));
    }

    // Update the specified Kunuunsa
    public function update(Request $request, $id)
    {
        $request->validate([
            'lafa_harkaan_aramame' => 'nullable|numeric',
            'lafa_keemikaalaan_aramame' => 'nullable|numeric',
            'hamma_keemikaalaa_litiraa' => 'nullable|numeric',
            'lafa_dhibeen_tooatame' => 'nullable|numeric',
            'lafa_ilbiisni_toatame' => 'nullable|numeric',
        ]);

        $kunuunsa = Kunuunsa::findOrFail($id);
        $kunuunsa->update($request->all());

        return redirect()->route('kunuunsas.index')->with('success', 'Kunuunsa updated successfully');
    }

    // Remove the specified Kunuunsa
    public function destroy($id)
    {
        $kunuunsa = Kunuunsa::findOrFail($id);
        $kunuunsa->delete();

        return redirect()->route('kunuunsas.index')->with('success', 'Kunuunsa deleted successfully');
    }
}
