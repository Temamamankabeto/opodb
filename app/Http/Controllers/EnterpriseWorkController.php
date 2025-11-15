<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnterpriseWork;

class EnterpriseWorkController extends Controller
{
    public function index()
    {
        $items = EnterpriseWork::orderBy('id', 'DESC')->get();
        return view('enterprise.index', compact('items'));
    }

    public function create()
    {
        return view('enterprise.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:enterprise_works,name'
        ]);

        EnterpriseWork::create([
            'name' => $request->name
        ]);

        return redirect()->route('enterprise.index')->with('success', 'Enterprise Work added successfully!');
    }

    public function edit($id)
    {
        $item = EnterpriseWork::findOrFail($id);
        return view('enterprise.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = EnterpriseWork::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:enterprise_works,name,' . $item->id
        ]);

        $item->update([
            'name' => $request->name
        ]);

        return redirect()->route('enterprise.index')->with('success', 'Enterprise Work updated successfully!');
    }

    public function destroy($id)
    {
        EnterpriseWork::destroy($id);

        return redirect()->route('enterprise.index')->with('success', 'Enterprise Work deleted successfully!');
    }
}
