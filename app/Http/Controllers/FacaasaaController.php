<?php

namespace App\Http\Controllers;

use App\Models\Facaasaa;
use Illuminate\Http\Request;

class FacaasaaController extends Controller
{
    // Display all Facaasaas
    public function index()
    {
        $facaasaas = Facaasaa::all();
        return view('facaasaas.index', compact('facaasaas'));
    }

    // Show the form for creating a new Facaasaa
    public function create()
    {
        return view('calla-guddiftuu.create');
    }

    // Store a new Facaasaa
    public function store(Request $request)
    {
        $request->validate([
            'lafa_facae' => 'nullable|numeric',
            'mala_facaasaa' => 'nullable|string|max:255',
            'qonnaan_bulaa_dhi' => 'nullable|integer',
            'qonnaan_bulaa_du' => 'nullable|integer',
            'qonnaan_bulaa_idaama' => 'nullable|integer',
        ]);

        Facaasaa::create($request->all());

        return redirect()->route('facaasaas.index')->with('success', 'Facaasaa created successfully.');
    }

    // Show the form for editing an existing Facaasaa
    public function edit(Facaasaa $facaasaa)
    {
        return view('facaasaas.edit', compact('facaasaa'));
    }

    // Update an existing Facaasaa
    public function update(Request $request, Facaasaa $facaasaa)
    {
        $request->validate([
            'lafa_facae' => 'nullable|numeric',
            'mala_facaasaa' => 'nullable|string|max:255',
            'qonnaan_bulaa_dhi' => 'nullable|integer',
            'qonnaan_bulaa_du' => 'nullable|integer',
            'qonnaan_bulaa_idaama' => 'nullable|integer',
        ]);

        $facaasaa->update($request->all());

        return redirect()->route('facaasaas.index')->with('success', 'Facaasaa updated successfully.');
    }

    // Delete a Facaasaa
    public function destroy(Facaasaa $facaasaa)
    {
        $facaasaa->delete();
        return redirect()->route('facaasaas.index')->with('success', 'Facaasaa deleted successfully.');
    }
}
