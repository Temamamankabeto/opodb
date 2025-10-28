<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\Region;
use App\Models\Seedling;
use App\Models\SeedlingPreparation;
use App\Models\SeedlingProduction;
use App\Models\SeedlingType;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;

class SeedlingPreparationController extends Controller
{
    public function create()
    {
        // Get all related models for select dropdowns
        $seedlingTypes = SeedlingType::all();
         

        return view('seedling-preparations.create', compact(
            'seedlingTypes',
             
        ));
    }

   public function store(Request $request)
{
    // ✅ Validate all fields as arrays with rules on each element
    $validated = $request->validate([
        'year' => 'required|array',
        'year.*' => 'required|integer|min:2011',
        'seedlingtype_id' => 'required|array',
        'seedlingtype_id.*' => 'required|exists:seedling_types,id',
        'seedling_id' => 'required|array',
        'seedling_id.*' => 'required|exists:seedlings,id',
        'nama_hirmaate' => 'required|array',
        'nama_hirmaate.*' => 'required|numeric|min:0.01',
        'baayina_guyyaa' => 'required|array',
        'baayina_guyyaa.*' => 'required|numeric|min:0.01',
        'hanga' => 'required|array',
        'hanga.*' => 'required|numeric|min:0.01',
    ]);

    $user = auth()->user();

    // ✅ Make sure we have the same count for all array fields
    $count = count($validated['seedling_id'] ?? []);
    if ($count === 0) {
        return back()->withErrors(['seedling_id' => 'No seedling data submitted.']);
    }

    // ✅ Check for duplicate seedling_id + year in the current submission
    $seen = [];
    for ($i = 0; $i < $count; $i++) {
        $key = $validated['year'][$i] . '-' . $validated['seedling_id'][$i];
        if (isset($seen[$key])) {
            return back()
                ->withInput()
                ->withErrors([
                    'seedling_id' => "Duplicate seedling entry for year {$validated['year'][$i]} and seedling ID {$validated['seedling_id'][$i]} in your submission."
                ]);
        }
        $seen[$key] = true;
    }

    // ✅ Check for duplicates already existing in the DB for this kebele
    for ($i = 0; $i < $count; $i++) {
        $exists = SeedlingPreparation::where('kebele_id', $user->kebele_id)
            ->where('year', $validated['year'][$i])
            ->where('seedling_id', $validated['seedling_id'][$i])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'seedling_id' => "Seedling ID {$validated['seedling_id'][$i]} for year {$validated['year'][$i]} already exists."
                ]);
        }
    }

    // ✅ If everything is valid, save all records
    for ($i = 0; $i < $count; $i++) {
        SeedlingPreparation::create([
            'year' => $validated['year'][$i],
            'seedlingtype_id' => $validated['seedlingtype_id'][$i],
            'seedling_id' => $validated['seedling_id'][$i],
            'nama_hirmaate' => $validated['nama_hirmaate'][$i],
            'baayina_guyyaa' => $validated['baayina_guyyaa'][$i],
            'hanga' => $validated['hanga'][$i],
            'created_by' => $user->id,
            'kebele_id' => $user->kebele_id,
            'woreda_id' => $user->woreda_id,
            'zone_id' => $user->zone_id,
            'region_id' => $user->region_id,
            'phone' => $user->phone,
        ]);
    }

    return back()->with('success', 'Seedling preparations saved successfully!');
}

    
    
    


    public function getSeedlingsByType($seedlingtypeId)
{
    $seedlings = \App\Models\Seedling::where('seedlingtype_id', $seedlingtypeId)->get();
    return response()->json($seedlings);
}





public function createp()
{
    // Get all related models for select dropdowns
    $seedlingTypes = SeedlingType::all();
     

    return view('seedling-preparations.create-production', compact(
        'seedlingTypes',
         
    ));
}

public function storep(Request $request)
{
    $data = $request->validate([
        'year.*' => 'required|integer',
        'seedlingtype_id.*' => 'required|exists:seedling_types,id',
        'seedling_id.*' => 'required|exists:seedlings,id',
        'nama_hirmaate.*' => 'required|numeric',
        'baayina_guyyaa.*' => 'required|numeric',
        'hanga.*' => 'required|numeric',
        'area.*' => 'required|numeric',
    ]);

    $user = auth()->user();

    $count = count($request->seedling_id);

    // Check for duplicate seedling_id per year in the input itself
    $seen = [];
    for ($i = 0; $i < $count; $i++) {
        $key = $request->year[$i] . '-' . $request->seedling_id[$i];
        if (isset($seen[$key])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['seedling_id' => "Duplicate seedling entry for year {$request->year[$i]} and seedling ID {$request->seedling_id[$i]} in your submission."]);
        }
        $seen[$key] = true;
    }

    // Check if same year + seedling_id already exists in DB for this kebele
    for ($i = 0; $i < $count; $i++) {
        $exists = SeedlingProduction::where('kebele_id', $user->kebele_id)
            ->where('year', $request->year[$i])
            ->where('seedling_id', $request->seedling_id[$i])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['seedling_id' => "Seedling ID {$request->seedling_id[$i]} for year {$request->year[$i]} already exists."]);
        }
    }

    // If no duplicates, save all
    for ($i = 0; $i < $count; $i++) {
        SeedlingProduction::create([
            'year' => $request->year[$i],
            'seedlingtype_id' => $request->seedlingtype_id[$i],
            'seedling_id' => $request->seedling_id[$i],
            'nama_hirmaate' => $request->nama_hirmaate[$i],
            'baayina_guyyaa' => $request->baayina_guyyaa[$i],
            'hanga' => $request->hanga[$i],
            'area' => $request->area[$i],
            'created_by' => $user->id,
            'kebele_id' => $user->kebele_id,
            'woreda_id' => $user->woreda_id,
            'zone_id' => $user->zone_id,
            'region_id' => $user->region_id,
            'phone' => $user->phone,
        ]);
    }

    return redirect()->back()->with('success', 'Seedling preparations saved successfully!');
}

}
