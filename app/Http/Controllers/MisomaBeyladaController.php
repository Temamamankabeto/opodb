<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\MisomaBeylada;
use App\Models\MisomaBeyladaProduction;
use App\Models\Region;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MisomaBeyladaController extends Controller
{
    public function create()
    {
        
        $kebeles = Kebele::all();
        $woredas = Woreda::all();
        $zones = Zone::all();
        $regions = Region::all();

        return view('misoma_beyladas.create', compact(
              'kebeles', 'woredas', 'zones', 'regions'
        ));
    }

    
    
    public function store(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
            'entries' => 'required|array',
            'entries.*.year' => 'required|string',
            'entries.*.raaddeen' => 'required|numeric',
            'entries.*.lukkuu_cuucii' => 'required|numeric',
            'entries.*.lukkuu_sookee' => 'required|numeric',
            'entries.*.cuucii_qurxummii' => 'required|numeric',
            'entries.*.gaagura_hammayya' => 'required|numeric',
            'entries.*.lafa_omisha_beylada' => 'required|numeric',
            'entries.*.omisha_beylada' => 'required|numeric',
        ]);
    
        // Extract input years from validated data
        $inputYears = collect($validated['entries'])->pluck('year')->unique();
    
        // Check if any year already exists for the user's kebele
        $existingYears = MisomaBeylada::where('kebele_id', $user->kebele_id)
            ->whereIn('year', $inputYears)
            ->pluck('year')
            ->unique();
    
        if ($existingYears->isNotEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'year' => 'Misoma Beylada data for year(s) ' . $existingYears->implode(', ') . ' already exists. You can only submit one year once.'
                ]);
        }
    
        // Save each entry
        foreach ($validated['entries'] as $entry) {
            MisomaBeylada::create([
                'year' => $entry['year'],
                'raaddeen' => $entry['raaddeen'],
                'lukkuu_cuucii' => $entry['lukkuu_cuucii'],
                'lukkuu_sookee' => $entry['lukkuu_sookee'],
                'cuucii_qurxummii' => $entry['cuucii_qurxummii'],
                'gaagura_hammayya' => $entry['gaagura_hammayya'],
                'lafa_omisha_beylada' => $entry['lafa_omisha_beylada'],
                'omisha_beylada' => $entry['omisha_beylada'],
    
                // From authenticated user
                'created_by' => $user->id,
                'kebele_id' => $user->kebele_id,
                'woreda_id' => $user->woreda_id,
                'zone_id' => $user->zone_id,
                'region_id' => $user->region_id,
                'phone' => $user->phone,
            ]);
        }
    
        return redirect()->route('misoma_beyladas.create')->with('success', 'All records saved successfully.');
    }



    public function createp()
    {
        
        $kebeles = Kebele::all();
        $woredas = Woreda::all();
        $zones = Zone::all();
        $regions = Region::all();

        return view('misoma_beyladas.create-production', compact(
              'kebeles', 'woredas', 'zones', 'regions'
        ));
    }

    
    
    public function storep(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
            'entries' => 'required|array',
            'entries.*.year' => 'required|string',
            'entries.*.Jabbii_ai' => 'required|numeric',
            'entries.*.killee_oomishame' => 'required|numeric',
            'entries.*.lukkuu_foonii' => 'required|numeric',
            'entries.*.damma_oomishame' => 'required|numeric',
            'entries.*.qurxummii_gabaaf' => 'required|numeric',
            'entries.*.aannan_elmame' => 'required|numeric',
        ]);
    
        // Extract input years from validated data
        $inputYears = collect($validated['entries'])->pluck('year')->unique();
    
        // Check if any year already exists for the user's kebele
        $existingYears = MisomaBeyladaProduction::where('kebele_id', $user->kebele_id)
            ->whereIn('year', $inputYears)
            ->pluck('year')
            ->unique();
    
        if ($existingYears->isNotEmpty()) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'year' => 'Misoma Beylada data for year(s) ' . $existingYears->implode(', ') . ' already exists. You can only submit one year once.'
                ]);
        }
    
        // Save each entry
        foreach ($validated['entries'] as $entry) {
            MisomaBeyladaProduction::create([
                'year' => $entry['year'],
                'Jabbii_ai' => $entry['Jabbii_ai'],
                'killee_oomishame' => $entry['killee_oomishame'],
                'lukkuu_foonii' => $entry['lukkuu_foonii'],
                'damma_oomishame' => $entry['damma_oomishame'],
                'qurxummii_gabaaf' => $entry['qurxummii_gabaaf'],
                'aannan_elmame' => $entry['aannan_elmame'],
    
                // From authenticated user
                'created_by' => $user->id,
                'kebele_id' => $user->kebele_id,
                'woreda_id' => $user->woreda_id,
                'zone_id' => $user->zone_id,
                'region_id' => $user->region_id,
                'phone' => $user->phone,
            ]);
        }
    
        return redirect()->route('misoma_beyladas_production.create')->with('success', 'Misooma beeyladaa haalaan galchitanii jirtu');
    }
    
    
}
