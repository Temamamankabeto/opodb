<?php

namespace App\Http\Controllers;

use App\Models\LivestockProduct;
use Illuminate\Http\Request;

class LivestockProductController extends Controller
{
     public function index()
    {
        $supplies = LivestockProduct::orderBy('id', 'desc')->get();
        return view('livestock_product.index', compact('supplies'));
    }

    public function create()
    {
        return view('livestock_product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:livestock_products,name|max:255',
        ]);

        LivestockProduct::create(['name' => $request->name]);

        return redirect()->route('livestock_product.index')->with('success', 'Livestock supply added successfully.');
    }

    public function edit($id)
    {
        $supply = LivestockProduct::findOrFail($id);
        return view('livestock_product.edit', compact('supply'));
    }

    public function update(Request $request, $id)
    {
        $supply = LivestockProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:livestock_products,name,' . $supply->id,
        ]);

        $supply->update(['name' => $request->name]);

        return redirect()->route('livestock_product.index')->with('success', 'Livestock supply updated successfully.');
    }

    public function destroy($id)
    {
        $supply = LivestockProduct::findOrFail($id);
        $supply->delete();

        return redirect()->route('livestock_product.index')->with('success', 'Livestock supply deleted successfully.');
    }
}
