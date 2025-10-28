<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\QabeenyaUmama;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QabenyaUmamaController extends Controller
{
    public function createp()
    {
        

        return view('qabeny-umama.create-production' );
    }

    
    
    public function storep(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
    'entries' => 'required|array|min:1',

    // Year must be >= 2011
    'entries.*.year' => 'required|integer|min:2011',

    // All numbers must be > 0
    'entries.*.dherina_d_h' => 'required|numeric|min:0.01',
    'entries.*.ballina' => 'required|numeric|min:0.01',
    'entries.*.humna_nama' => 'required|numeric|min:1',   // people count should be at least 1
    'entries.*.turtii' => 'required|numeric|min:1',       // days should be at least 1
]);
    
        // Extract input years from validated data
        $inputYears = collect($validated['entries'])->pluck('year')->unique();
    
        // Check if any year already exists for the user's kebele
        $existingYears = QabeenyaUmama::where('kebele_id', $user->kebele_id)
            ->whereIn('year', $inputYears)
            ->pluck('year')
            ->unique();
    
        if ($existingYears->isNotEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'year' => 'qabenya uumamaa for year(s) ' . $existingYears->implode(', ') . ' already exists. You can only submit one year once.'
                ]);
        }
    
        // Save each entry
        foreach ($validated['entries'] as $entry) {
            QabeenyaUmama::create([
                'year' => $entry['year'],
                'dherina_d_h' => $entry['dherina_d_h'],
                'ballina' => $entry['ballina'],
                'humna_nama' => $entry['humna_nama'],
                'turtii' => $entry['turtii'],
                 
    
                // From authenticated user
                'created_by' => $user->id,
                'kebele_id' => $user->kebele_id,
                'woreda_id' => $user->woreda_id,
                'zone_id' => $user->zone_id,
                'region_id' => $user->region_id,
                'phone' => $user->phone,
            ]);
        }
    
        return redirect()->route('qabenya.create')->with('success', 'All records saved successfully.');
    }
}
