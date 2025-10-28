<?php

namespace App\Http\Controllers;

use App\Models\EggumsaNaannoo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EggumsaNaannooController extends Controller
{
    public function create()
    {
        return view('egumsa-nano.create'); // or the path where your Blade lives
    } 
     //

  

public function store(Request $request)
{
    $validated = $request->validate([
        'data' => 'required|array',
        'data.*.year' => 'required|integer',
        'data.*.limited_forest_land' => 'required|numeric',
        'data.*.land_free_from_contact' => 'required|numeric',
        'data.*.associations_organized' => 'required|integer',
        'data.*.number_of_members' => 'required|integer',
        'data.*.dry_waste_sites' => 'required|integer',
        'data.*.private_bathroom' => 'required|integer',
        'data.*.community_toilets' => 'required|integer',
        'data.*.sum_bathroom' => 'nullable|integer',
    ]);

    $user = Auth::user();
    $duplicates = [];

    foreach ($validated['data'] as $entry) {
        $exists = EggumsaNaannoo::where('year', $entry['year'])
            ->where('zone_id', $user->zone_id)
            ->where('woreda_id', $user->woreda_id)
            ->where('kebele_id', $user->kebele_id)
            ->exists();

        if ($exists) {
            $duplicates[] = "Year {$entry['year']}";
        }
    }

    if (count($duplicates) > 0) {
        return redirect()->back()->with('error', 'Duplicate entries for: ' . implode(', ', $duplicates));
    }

    foreach ($validated['data'] as $entry) {
        EggumsaNaannoo::create([
            'year' => $entry['year'],
            'limited_forest_land' => $entry['limited_forest_land'],
            'land_free_from_contact' => $entry['land_free_from_contact'],
            'associations_organized' => $entry['associations_organized'],
            'number_of_members' => $entry['number_of_members'],
            'dry_waste_sites' => $entry['dry_waste_sites'],
            'private_bathroom' => $entry['private_bathroom'],
            'community_toilets' => $entry['community_toilets'],
            'region' => $user->region ?? 'Unknown',
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'created_by' => $user->id,
        ]);
    }

    return redirect()->back()->with('success', 'Data saved successfully!');
}


}
