<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentMealSupply;

class StudentMealSupplyController extends Controller
{
     public function index()
    {
        $items = StudentMealSupply::orderBy('id', 'DESC')->get();
        return view('student_meal_supply.index', compact('items'));
    }

    public function create()
    {
        return view('student_meal_supply.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:student_meal_supplies,name',
        ]);

        StudentMealSupply::create($request->only('name'));

        return redirect()->route('student_meal_supply.index')
                         ->with('success', 'Student Meal Supply created successfully.');
    }

    public function edit($id)
    {
        $item = StudentMealSupply::findOrFail($id);
        return view('student_meal_supply.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = StudentMealSupply::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:student_meal_supplies,name,' . $item->id,
        ]);

        $item->update($request->only('name'));

        return redirect()->route('student_meal_supply.index')
                         ->with('success', 'Updated successfully.');
    }

    public function destroy($id)
    {
        StudentMealSupply::destroy($id);

        return redirect()->route('student_meal_supply.index')
                         ->with('success', 'Deleted Successfully.');
    }
}
