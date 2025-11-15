<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationOfCivilCertificate;

class RegistrationOfCivilCertificateController extends Controller
{
   // Display list
    public function index()
    {
        $items = RegistrationOfCivilCertificate::orderBy('id', 'DESC')->get();
        return view('civil.index', compact('items'));
    }

    // Show Create Form
    public function create()
    {
        return view('civil.create');
    }

    // Store New
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:registration_of_civil_certificates|max:255'
        ]);

        RegistrationOfCivilCertificate::create([
            'name' => $request->name
        ]);

        return redirect()->route('civil.index')->with('success', 'Added Successfully!');
    }

    // Show Edit Form
    public function edit($id)
    {
        $item = RegistrationOfCivilCertificate::findOrFail($id);
        return view('civil.edit', compact('item'));
    }

    // Update Existing
    public function update(Request $request, $id)
    {
        $item = RegistrationOfCivilCertificate::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:registration_of_civil_certificates,name,' . $item->id
        ]);

        $item->update([
            'name' => $request->name
        ]);

        return redirect()->route('civil.index')->with('success', 'Updated Successfully!');
    }

    // Delete
    public function destroy($id)
    {
        RegistrationOfCivilCertificate::destroy($id);
        return redirect()->route('civil.index')->with('success', 'Deleted Successfully!');
    }
}