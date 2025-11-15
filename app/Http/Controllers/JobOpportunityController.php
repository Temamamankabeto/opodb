<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use Illuminate\Http\Request;
use App\Models\JobOpportunity;
use App\Models\YearlyCropPlan;

class JobOpportunityController extends Controller
{
     public function index()
    {
        $items = JobOpportunity::orderBy('id', 'DESC')->get();
        return view('job.index', compact('items'));
    }

    public function create()
    {
        return view('job.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:job_opportunities,name'
        ]);

        JobOpportunity::create([
            'name' => $request->name
        ]);

        return redirect()->route('job.index')->with('success', 'Job Opportunity added successfully!');
    }

    public function edit($id)
    {
        $item = JobOpportunity::findOrFail($id);
        return view('job.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = JobOpportunity::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255|unique:job_opportunities,name,' . $item->id
        ]);

        $item->update([
            'name' => $request->name
        ]);

        return redirect()->route('job.index')->with('success', 'Job Opportunity updated successfully!');
    }

    public function destroy($id)
    {
        JobOpportunity::destroy($id);

        return redirect()->route('job.index')->with('success', 'Job Opportunity deleted successfully!');
    }
}
