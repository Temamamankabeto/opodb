<?php

namespace App\Http\Controllers;

use App\Models\ConstructionOfInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConstructionController extends Controller
{
    //
    public function create()
    {
        $planYears = ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019'];
        return view('construction.new', compact('planYears'));
    }

    public function store(Request $request)
    {
        $records = $request->input('data');

        $zoneId = auth()->user()->zone_id ?? null;
        $woredaId = auth()->user()->woreda_id ?? null;
        $kebeleId = auth()->user()->kebele_id ?? null;
        $sectorId = auth()->user()->sector_id ?? null;

        foreach ($records as $index => $record) {
            // Get service name
            $services = [
                'Mana Barumsa Bu\'uura Boruu',
                'Mana Barumsa Sad. 1ffaa',
                'Mana Jireenya Barsiisotaa',
                'Keellaa Fayyaa Dhaloota 2ffaa',
                'Mana Qoricha Hawaasaa',
                'Wiirtuu Leenjii Fooyya\'insa',
                'Ogummaa Qonnaan/ Horsiisee Bulaa:FTC',
                'Waajjira Bulchiinsa Gandaa',
                'Mana Jireenya Hooggansa Gandaa',
                'Mana Murtii Aadaa',
                'Wirtuu Dagaagina Aadaa'
            ];

            $serviceName = $services[$index];

            // Raawwii 2017 (Performance)
            if (!empty($record[2017]['count']) || !empty($record[2017]['cost'])) {
                ConstructionOfInstitution::create([
                    'zone_id' => $zoneId,
                    'woreda_id' => $woredaId,
                    'kebele_id' => $kebeleId,
                    'sector_id' => $sectorId,
                    'service_name' => $serviceName,
                    'type' => 'performance',
                    'year' => 2017,
                    'count' => $record[2017]['count'] ?? 0,
                    'cost' => $record[2017]['cost'] ?? 0,
                ]);
            }

            // Karoora 2018 (Plan)
            if (!empty($record[2018]['count']) || !empty($record[2018]['cost'])) {
                ConstructionOfInstitution::create([
                    'zone_id' => $zoneId,
                    'woreda_id' => $woredaId,
                    'kebele_id' => $kebeleId,
                    'sector_id' => $sectorId,
                    'service_name' => $serviceName,
                    'type' => 'plan',
                    'year' => 2018,
                    'count' => $record[2018]['count'] ?? 0,
                    'cost' => $record[2018]['cost'] ?? 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Construction records saved successfully.');
    }

    public function report(Request $request)
    {
        $baselineYear = $request->get('baseline_year', 2017);
        $planYear = $request->get('plan_year', 2018);

        $data = ConstructionOfInstitution::whereIn('year', [$baselineYear, $planYear])
            ->orderBy('service_name')
            ->get()
            ->groupBy('service_name');

        return view('construction.report', compact('data', 'baselineYear', 'planYear'));
    }
}
