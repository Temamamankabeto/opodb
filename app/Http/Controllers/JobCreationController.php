<?php

namespace App\Http\Controllers;

use App\Models\JobCreation;
use App\Models\JobType;
use App\Models\Kebele;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobCreationController extends Controller
{
     public function create()
    {
        $jobTypes =  JobType::all();
        return view('job_creation.create', compact('jobTypes'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'data.*.year' => 'required|integer',
        'data.*.enterprise_type' => 'required|string',
        'data.*.job_type_id' => 'required|exists:job_types,id',
        'data.*.male' => 'required|integer|min:0',
        'data.*.number' => 'required|integer|min:0',
        'data.*.female' => 'required|integer|min:0',
        'data.*.sum' => 'required|integer|min:0',
    ]);

    $user = Auth::user();
    $duplicates = [];
    $savedCount = 0;

    foreach ($validated['data'] as $entry) {
        $exists = JobCreation::where('year', $entry['year'])
            ->where('enterprise_type', $entry['enterprise_type'])
            ->where('job_type_id', $entry['job_type_id'])
            ->where('zone_id', $user->zone_id)
            ->where('woreda_id', $user->woreda_id)
            ->where('kebele_id', $user->kebele_id)
            ->exists();

        if ($exists) {
            $duplicates[] = "Year {$entry['year']} - Enterprise '{$entry['enterprise_type']}' - Job Type ID {$entry['job_type_id']}";
            continue;
        }

        JobCreation::create([
            'year' => $entry['year'],
            'enterprise_type' => $entry['enterprise_type'],
            'job_type_id' => $entry['job_type_id'],
            'number' => $entry['number'],
            'male' => $entry['male'],
            'female' => $entry['female'],
            'sum' => $entry['sum'],
            'region' => 'oromiyaa',
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'created_by' => $user->id,
        ]);
        $savedCount++;
    }

    return redirect()->back()
        ->with('success', $savedCount > 0 ? "$savedCount job record(s) saved successfully." : null)
        ->with('error', !empty($duplicates) ? 'Some records were skipped due to duplication: ' . implode(', ', $duplicates) : null);
}
public function jobReport(Request $request)
{
    $user = Auth::user();
     $kebeleId = $user->kebele_id;

    $year = $request->input('year');
    $jobTypeId = $request->input('job_type_id');
    $enterpriseType = $request->input('enterprise_type');

    $query = JobCreation::with('jobType')
        ->where('kebele_id', $user->kebele_id);

    if ($year) {
        $query->whereIn('year', [$year, $year - 1]);
    }

    if ($jobTypeId) {
        $query->where('job_type_id', $jobTypeId);
    }

    if ($enterpriseType) {
        $query->where('enterprise_type', $enterpriseType);
    }

    $jobs = $query->get();

    // Group by year, job_type_id and enterprise_type
    $grouped = $jobs->groupBy(function ($item) {
        return $item->year . '|' . $item->job_type_id . '|' . $item->enterprise_type;
    })->map(function ($group) {
        return [
            'year' => $group->first()->year,
            'job_type_id' => $group->first()->job_type_id,
            'job_type_name' => $group->first()->jobType->name ?? 'N/A',
            'enterprise_type' => $group->first()->enterprise_type,
            'male' => $group->sum('male'),
            'female' => $group->sum('female'),
            'total' => $group->sum('sum'),
        ];
    });
// Aggregate totals by year only for chart
$yearlyTotals = $jobs->groupBy('year')->map(function ($group) {
    return [
        'male' => $group->sum('male'),
        'female' => $group->sum('female'),
        'total' => $group->sum('sum'),
    ];
});
    // Get distinct years for the chart (optional)
    $yearsForChart = $jobs->pluck('year')->unique()->sort()->values();

    $jobTypes = \App\Models\JobType::all();

    return view('job_creation.kebele_report', compact('grouped', 'year', 'jobTypeId', 'enterpriseType', 'jobTypes', 'yearlyTotals'));
}

public function jobReportworeda(Request $request)
{
    $user = Auth::user();
    $woredaId = $user->woreda_id;

    $query = JobCreation::whereHas('kebele', function ($q) use ($woredaId) {
        $q->where('woreda_id', $woredaId);
    });

    if ($request->filled('year')) {
        $query->where('year', $request->year);
    }

    if ($request->filled('enterprise_type')) {
        $query->where('enterprise_type', $request->enterprise_type);
    }

    if ($request->filled('job_type_id')) {
        $query->where('job_type_id', $request->job_type_id);
    }

    if ($request->filled('kebele_id')) {
        $query->where('kebele_id', $request->kebele_id);
    }

    $jobs = $query->with(['kebele', 'jobType'])
    ->orderBy('year')
              ->orderBy('enterprise_type')
              ->orderBy('job_type_id')
              ->orderBy('kebele_id')
              ->get();
     

    $years = JobCreation::select('year')->distinct()->pluck('year');
    $enterpriseTypes = JobCreation::select('enterprise_type')->distinct()->pluck('enterprise_type');
    $jobTypes = JobType::all();
    $kebeles = Kebele::where('woreda_id', $woredaId)->get();

    $jobs = $query->with(['kebele', 'jobType'])
              ->orderBy('year')
              ->orderBy('enterprise_type')
              ->orderBy('job_type_id')
              ->orderBy('kebele_id')
              ->get();

    return view('job_creation.woreda_report', compact('jobs', 'years', 'enterpriseTypes', 'jobTypes', 'kebeles'));
}


public function jobReportZone(Request $request)
{
    $user = Auth::user();
    $zoneId = $user->zone_id;

    // Get all woreda IDs in this zone
    $woredaIds = Woreda::where('zone_id', $zoneId)->pluck('id');

    // Query jobs for woredas that belong to this zone
    $query = JobCreation::whereIn('woreda_id', $woredaIds);

    if ($request->filled('year')) {
        $query->where('year', $request->year);
    }

    if ($request->filled('enterprise_type')) {
        $query->where('enterprise_type', $request->enterprise_type);
    }

    if ($request->filled('job_type_id')) {
        $query->where('job_type_id', $request->job_type_id);
    }

    if ($request->filled('woreda_id')) {
        $query->where('woreda_id', $request->woreda_id);
    }

    $jobs = $query->with(['woreda', 'jobType'])
                  ->orderBy('year')
                  ->orderBy('enterprise_type')
                  ->orderBy('job_type_id')
                  ->orderBy('woreda_id')
                  ->get();

    $years = JobCreation::select('year')->distinct()->pluck('year');
    $enterpriseTypes = JobCreation::select('enterprise_type')->distinct()->pluck('enterprise_type');
    $jobTypes = JobType::all();

    // Get woredas in this zone
    $woredas = Woreda::where('zone_id', $zoneId)->get();

    $comparisonTotals = collect();
if ($request->filled('year')) {
    $selectedYear = (int)$request->year;
    $previousYear = $selectedYear - 1;

    $comparisonData = JobCreation::whereIn('woreda_id', $woredaIds)
        ->whereIn('year', [$selectedYear, $previousYear])
        ->get()
        ->groupBy(['woreda_id', 'year']);

    foreach ($woredas as $woreda) {
        $woredaId = $woreda->id;

        $current = $comparisonData[$woredaId][$selectedYear] ?? collect([]);
        $previous = $comparisonData[$woredaId][$previousYear] ?? collect([]);

        $comparisonTotals->push([
            'woreda_name' => $woreda->name,
            'selected_year_male' => $current->sum('male'),
            'selected_year_female' => $current->sum('female'),
            'selected_year_total' => $current->sum('sum'),
            'previous_year_male' => $previous->sum('male'),
            'previous_year_female' => $previous->sum('female'),
            'previous_year_total' => $previous->sum('sum'),
        ]);
    }
}


    return view('job_creation.zone_report', compact('jobs', 'years', 'enterpriseTypes', 'jobTypes', 'woredas', 'comparisonTotals'));

}


public function jobReportregion(Request $request)
{
    $user = auth()->user();
    $regionId = $user->region_id;

    // Get all zones in the region for filters
    $zones = Zone::where('region_id', $regionId)->orderBy('name')->get();

    // Start building the query
    $query = JobCreation::with([
            'woreda.zone', 
            'kebele',
            'jobType',
             
        ])
        ->whereHas('woreda', function($q) use ($regionId) {
            $q->whereHas('zone', function($q) use ($regionId) {
                $q->where('region_id', $regionId);
            });
        });

    // Apply filters
    if ($request->filled('year')) {
        $query->where('year', $request->year);
    }

    if ($request->filled('enterprise_type')) {
        $query->where('enterprise_type', $request->enterprise_type);
    }

    if ($request->filled('job_type_id')) {
        $query->where('job_type_id', $request->job_type_id);
    }

    if ($request->filled('zone_id')) {
        $query->whereHas('woreda', function($q) use ($request) {
            $q->where('zone_id', $request->zone_id);
        });
    }

    if ($request->filled('woreda_id')) {
        $query->where('woreda_id', $request->woreda_id);
    }

    // Get the filtered data
    $jobCreations = $query->orderBy('year', 'desc')
        ->orderBy('woreda_id')
        ->orderBy('kebele_id')
        ->get();

    // Get woredas for the filter dropdown (based on zone filter if applied)
    $woredas = Woreda::when($request->filled('zone_id'), function($q) use ($request) {
            $q->where('zone_id', $request->zone_id);
        })
        ->whereHas('zone', function($q) use ($regionId) {
            $q->where('region_id', $regionId);
        })
        ->orderBy('name')
        ->get();

    // Group data for display - by zone > woreda > kebele
    $groupedData = $jobCreations->groupBy([
        function ($item) {
            return $item->woreda->zone->name;
        },
        function ($item) {
            return $item->woreda->name;
        },
        function ($item) {
            return $item->kebele->name;
        }
    ]);

    // Prepare chart data - jobs by zone
    $chartData = $jobCreations->groupBy('woreda.zone.name')
        ->map(function($items) {
            return [
                'male' => $items->sum('male'),
                'female' => $items->sum('female'),
                'total' => $items->sum('sum')
            ];
        });
$flatData = [];

foreach ($jobCreations as $job) {
    $flatData[] = [
        'zone' => $job->zone_id ?? 'N/A',         // Adjust these fields per your data structure
        'woreda' => $job->woreda_id ?? 'N/A',
        'enterprise_type' => $job->enterprise_type,
        'job_type' => $job->job_type_id ?? 'N/A',
        'male' => $job->male,
        'female' => $job->female,
        'sum' => $job->sum,
    ];
}
    return view('job_creation.region_report', [
        'jobCreations' => $jobCreations,
        'groupedData' => $groupedData,
        'chartData' => $chartData,
         'flatData' => $flatData,
        'zones' => $zones,
        'woredas' => $woredas,
        'jobTypes' => JobType::orderBy('name')->get(),
        'filters' => $request->all()
    ]);
}




 
}
