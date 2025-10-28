<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    //
    public function create()
    {
        $activities = [
            'Ijaarsaa fi Suphaa',
            'Ijaarsa Madda Baayoo Gaazii',
            'Sarara Ibsaa Diriisuu',
            'Bishaan Boolla',
            'Burqaa Misoomsuu',
            'Bishaan Roobaa Cimmisuu',
            'Ijaarsa Giddu-gala Soolaarii',
            'Suphaa Faasilitii Bishaan Dhugaatii',
            'Daandii Haaraa Saaquu',
            'Suphaa Daandii',
            'Ijaarsa Bo\'oo Lolaa',
            'Ijaarsa Riqaa Xixiqqaa'
        ];
        $currentYear = date('Y');
        $years = range($currentYear - 10, $currentYear);
        $planYears = ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019'];
        return view('water.new', compact('years', 'activities'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'data' => 'required|array',
        ]);

        $zoneId = auth()->user()->zone_id ?? null;
        $woredaId = auth()->user()->woreda_id ?? null;
        $kebeleId = auth()->user()->kebele_id ?? null;
        $sectorId = auth()->user()->sector_id ?? null;
        foreach ($request->data as $activityName => $values) {
            // Fallback to 0 if null
            $raawwii_maatii = $values['raawwii_maatii'] ?? 0;
            $raawwii_hawaasaa = $values['raawwii_hawaasaa'] ?? 0;
            $karoora_maatii = $values['karoora_maatii'] ?? 0;
            $karoora_hawaasaa = $values['karoora_hawaasaa'] ?? 0;

            // Automatically calculate ida'ama
            $raawwii_idaama = $raawwii_maatii + $raawwii_hawaasaa;
            $karoora_idaama = $karoora_maatii + $karoora_hawaasaa;

            Facility::create([
                'base_line_year' => $base_line_year,
                'plan_year' => $plan_year,
                'activity_name' => $activityName,
                'zone_id' => $zoneId,
                'woreda_id' => $woredaId,
                'kebele_id' => $kebeleId,
                'sector_id' => $sectorId,

                'raawwii_maatii' => $raawwii_maatii,
                'raawwii_hawaasaa' => $raawwii_hawaasaa,
                'raawwii_idaama' => $raawwii_idaama,

                'karoora_maatii' => $karoora_maatii,
                'karoora_hawaasaa' => $karoora_hawaasaa,
                'karoora_idaama' => $karoora_idaama,

                'baasii' => $values['baasii'] ?? 0,
            ]);
        }

        return redirect()->back()->with('success', 'Data saved successfully!');
    }

}
