<?php

namespace App\Http\Controllers;

use App\Models\CompostType;
use Illuminate\Http\Request;

class CompostTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compostTypes = CompostType::all();
        return view('compost_types.index', compact('compostTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('compost_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        CompostType::create($request->all());

        return redirect()->route('compost-types.index')
                         ->with('success', 'Compost type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompostType $compostType)
    {
        return view('compost_types.show', compact('compostType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompostType $compostType)
    {
        return view('compost_types.edit', compact('compostType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompostType $compostType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $compostType->update($request->all());

        return redirect()->route('compost-types.index')
                         ->with('success', 'Compost type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompostType $compostType)
    {
        $compostType->delete();

        return redirect()->route('compost-types.index')
                         ->with('success', 'Compost type deleted successfully');
    }
}
