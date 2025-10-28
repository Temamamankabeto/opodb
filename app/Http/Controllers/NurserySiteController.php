<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\NurserySite;
use App\Models\Region;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NurserySiteController extends Controller
{
    public function index()
    {
        $nurserySites = NurserySite::with(['kebele', 'woreda', 'zone', 'region', 'creator'])->latest()->get();
        return view('nursery_sites.index', compact('nurserySites'));
    }

    public function create()
    {
        return view('nursery_sites.create', [
            'kebeles' => Kebele::all(),
            'woredas' => Woreda::all(),
            'zones' => Zone::all(),
            'regions' => Region::all()
        ]);
    }
    public function store(Request $request)
    {
       $request->validate([
    'nurseries.*.year' => 'required|integer|min:2011', // year must be >= 2011
    'nurseries.*.baayyina' => 'required|string',
    'nurseries.*.ballina' => 'required|numeric|min:0',
    'nurseries.*.nama_hirmaate' => 'required|numeric|min:0',
    'nurseries.*.baayina_guyyaa' => 'required|numeric|min:0',
]);
    
        $user = auth()->user();
    
        // Extract all years from input to check duplicates in batch
        $inputYears = collect($request->nurseries)->pluck('year')->unique();
    
        // Check if any year already exists for this kebele
        $existingYears = NurserySite::where('kebele_id', $user->kebele_id)
            ->whereIn('year', $inputYears)
            ->pluck('year')
            ->unique();
    
        if ($existingYears->count() > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['year' => 'Nursery data for year(s) ' . $existingYears->implode(', ') . ' already exists. You can only submit one year once.']);
        }
    
        foreach ($request->nurseries as $data) {
            NurserySite::create([
                'year' => $data['year'],
                'baayyina' => $data['baayyina'],
                'ballina' => $data['ballina'],
                'nama_hirmaate' => $data['nama_hirmaate'],
                'baayina_guyyaa' => $data['baayina_guyyaa'],
                'created_by' => $user->id,
                'kebele_id' => $user->kebele_id,
                'woreda_id' => $user->woreda_id,
                'zone_id' => $user->zone_id,
                'region_id' => $user->region_id,
                'phone' => $user->phone,
            ]);
        }
    
        return redirect()->route('nursery-sites.create')->with('success', 'Nursery sites saved successfully.');
    }
    
    


    public function edit($id)
    {
        $nurserySite = NurserySite::findOrFail($id);

        return view('nursery_sites.edit', [
            'nurserySite' => $nurserySite,
            'kebeles' => Kebele::all(),
            'woredas' => Woreda::all(),
            'zones' => Zone::all(),
            'regions' => Region::all()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|integer',
            'baayyina' => 'required|string',
            'ballina' => 'required|numeric',
            'nama_hirmaate' => 'required|numeric',
            'baayina_guyyaa' => 'required|numeric',
            'kebele_id' => 'required|exists:kebeles,id',
            'woreda_id' => 'required|exists:woredas,id',
            'zone_id' => 'required|exists:zones,id',
            'region_id' => 'required|exists:regions,id',
            'phone' => 'nullable|string',
        ]);

        $nurserySite = NurserySite::findOrFail($id);
        $nurserySite->update($request->all());

        return redirect()->route('nursery-sites.index')->with('success', 'Nursery site updated.');
    }

    public function destroy($id)
    {
        $nurserySite = NurserySite::findOrFail($id);
        $nurserySite->delete();

        return redirect()->route('nursery-sites.index')->with('success', 'Nursery site deleted.');
    }
}
