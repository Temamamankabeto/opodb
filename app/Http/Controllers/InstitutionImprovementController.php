<?php

namespace App\Http\Controllers;

use App\Models\InstitutionInprovements;
use App\Models\Kebele;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstitutionImprovementController extends Controller
{
    //
    public function create()
    {
        $planYears = ['2012', '2013', '2014', '2015', '2016', '2017', '2018', '2019'];
        return view('inprovement_institution.new', compact('planYears'));
    }

    public function store(Request $request)
    {
        $zoneId = auth()->user()->zone_id ?? null;
        $woredaId = auth()->user()->woreda_id ?? null;
        $kebeleId = auth()->user()->kebele_id ?? null;
        $sectorId = auth()->user()->sector_id ?? null;

        $validated = $request->validate([
            'baseline_year' => 'required|integer',
            'plan_year' => 'required|integer',
            'zone' => 'required|string|max:255',
            'woreda' => 'required|string|max:255',
            'kebele' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'data' => 'required|array',
        ]);


        $baseline_year = $validated['baseline_year'];
        $plan_year = $validated['plan_year'];

        $data = $validated['data'];

        // Loop through data and save each record
        foreach ($data as $category => $subCategories) {
            if (!is_array($subCategories)) continue;

            foreach ($subCategories as $subCategory => $services) {
                if (!is_array($services)) continue;

                foreach ($services as $serviceName => $years) {
                    if (!is_array($years)) continue;

                    foreach ($years as $year => $metrics) {
                        if (!is_array($metrics)) continue;

                        // Save to DB (adjust model and fields as needed)
                        InstitutionInprovements::updateOrCreate(
                            [
                                'zone_id' => $zoneId,
                                'woreda_id' => $woredaId,
                                'kebele_id' => $kebeleId,
                                'sector_id' => $sectorId,
                                'baseline_year' => $baseline_year,
                                'plan_year' => $plan_year,
                                'main_category' => $category,
                                'sub_category' => $subCategory,
                                'service_name' => $serviceName,
                                'year' => $year,
                            ],
                            [
                                'actual' => $metrics['actual'] ?? null,
                                'cost' => $metrics['cost'] ?? null,
                                'plan' => $metrics['plan'] ?? null,
                            ]
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Data saved successfully.');
    }

    public function report(Request $request)
    {
        $zoneId = $request->zone_id;
        $woredaId = $request->woreda_id;
        $kebeleId = $request->kebele_id;
        $year = $request->year;

        // Get distinct years from the table for the filter dropdown
        $years = DB::table('institution_inprovements')
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Assume you already have zones, woredas, kebeles loaded too
        $zones = Zone::all();
        $woredas = Woreda::all();
        $kebeles = Kebele::all();

        $query = InstitutionInprovements::query()
            ->selectRaw('main_category, sub_category, service_name, year, SUM(cost) as total_cost')
            ->when($zoneId, fn($q) => $q->where('zone_id', $zoneId))
            ->when($woredaId, fn($q) => $q->where('woreda_id', $woredaId))
            ->when($kebeleId, fn($q) => $q->where('kebele_id', $kebeleId))
            ->when($year, fn($q) => $q->where('year', $year))
            ->groupBy('main_category', 'sub_category', 'service_name', 'year')
            ->get();

// Chart Data for Bar (by Main Category)
        $barChartData = $query->groupBy('main_category')->map(fn($group) => $group->sum('total_cost')
        );

// Chart Data for Line (by Year)
        $lineChartData = $query->groupBy('year')->map(fn($group) => $group->sum('total_cost')
        );

// Pass to Blade
        return view('inprovement_institution.report', compact(
            'zones', 'woredas', 'kebeles',
            'zoneId', 'woredaId', 'kebeleId', 'year',
            'query',
            'years'
        ))->with('chartData', [
            'bar' => $barChartData,
            'line' => $lineChartData
        ]);

    }


    private function processRecords($records)
    {
        $data = [];

        foreach ($records as $record) {
            $mainCat = $record->main_category;
            $subCat = $record->sub_category;
            $service = $record->service_name;
            $yr = $record->year;
            $type = $record->type;

            $data[$mainCat][$subCat][$service][$yr][$type]['quantity'] = $record->quantity;
            $data[$mainCat][$subCat][$service][$yr][$type]['cost'] = $record->cost;
        }

        return $data;
    }
}
