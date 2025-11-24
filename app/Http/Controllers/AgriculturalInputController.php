<?php

namespace App\Http\Controllers;

use App\Exports\InputPlanningExport;
use App\Models\AgriculturalInput;   
use App\Models\CallaGuddiftuu;
use App\Models\CropType;
use App\Models\Kebele;
use App\Models\Woreda;
use App\Models\YearlyCropPlan;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Symfony\Component\Console\Input\Input;


class AgriculturalInputController extends Controller
{
 



public function index(Request $request)
    {
        // Get filtered and grouped data
        $groupedInputs = CallaGuddiftuu::query()
            ->when($request->year, fn($q, $year) => $q->where('year', $year))
            ->when($request->season, fn($q, $season) => $q->where('season', $season))
            ->when($request->type, fn($q, $type) => $q->where('type', $type))
            ->when($request->kebele, fn($q, $kebele) => $q->where('kebele_id', $kebele))
            ->orderBy('kebele_id')
            ->orderBy('season')
            ->orderBy('type')
            ->get()
            ->groupBy(['kebele_id', 'season', 'type']);

        // Get filter options
        $years = CallaGuddiftuu::select('year')->distinct()->orderBy('year')->pluck('year');
        $seasons = CallaGuddiftuu::select('season')->distinct()->pluck('season');
        $types = CallaGuddiftuu::select('type')->distinct()->pluck('type');
        $kebeles = CallaGuddiftuu::select('kebele_id')->distinct()->orderBy('kebele_id')->pluck('kebele_id');

        return view('karora.karoora-gandaa.list', compact('groupedInputs', 'years', 'seasons', 'types', 'kebeles'));
    }
 

public function createlatest()
{
    return view('karora.karoora-gandaa.callaguddi');
}

public function storelatest(Request $request)
{
    $user = Auth::user();

    // Validate incoming request
    $validated = $request->validate([
        'year' => 'required|integer|min:2000|max:'.(date('Y')+1),
        'season' => 'required|string|in:Ganna,Bona,Arfaasaa',
        'inputs' => 'required|array|min:1',
        'inputs.*.type' => 'required|string|max:255',
        'inputs.*.quantity' => 'required|numeric|min:0',
        'inputs.*.unit' => 'required|string|in:kg,litre,quintal',
    ]);

    // Check for duplicates before saving anything
    $duplicates = [];
    foreach ($request->inputs as $input) {
        $exists = CallaGuddiftuu::where('year', $request->year)
                                ->where('season', $request->season)
                                ->where('type', $input['type'])
                                ->where('kebele_id', $request->kebele_id)
                                
                                ->exists();

        if ($exists) {
            $duplicates[] = $input['type'];
        }
    }

    if (!empty($duplicates)) {
        $errorMessage = count($duplicates) > 1 
            ? 'These input types already exist for this season and year: ' . implode(', ', $duplicates)
            : 'The input type "' . $duplicates[0] . '" already exists for this season and year.';
        
        return redirect()->back()
                        ->withInput()
                        ->withErrors(['inputs' => $errorMessage]);
    }

    // Save all inputs if no duplicates found
    $savedInputs = [];
    foreach ($request->inputs as $input) {
        $savedInput = CallaGuddiftuu::create([
            'year' => $request->year,
            'season' => $request->season,
            'type' => $input['type'],
            'quantity' => $input['quantity'],
            'unit' => $input['unit'],
            'kebele_id' => $user->kebele_id, 
            'woreda_id' => $user->woreda_id,  
            'zone_id' => $user->zone_id,  
            'created_by' => $user->id,
            'phone' => $user->phone,
            'user_id' => $user->id,
            'status' => 'pending', // Assuming you might want a status field
        ]);
        
        $savedInputs[] = $savedInput;
    }

    // Add additional success information if needed
    $successData = [
        'count' => count($savedInputs),
        'year' => $request->year,
        'season' => $request->season
    ];

    return redirect()->back()
                    ->with('success', 'Inputs saved successfully!')
                    ->with('success_data', $successData);
}



 
public function kebeleReport(Request $request)
{

    
    $user = Auth::user();

    if (!$user || !$user->kebele_id) {
        abort(403, 'Unauthorized');
    }


    $year = $request->get('year');
    $season = $request->get('season');
    $type = $request->get('type');

    // Fetch all reports if no year is selected, else filter by the year
    $reportsQuery = CallaGuddiftuu::query();

    if ($year) {
        $reportsQuery->where('year', $year);
    }

    if ($season) {
        $reportsQuery->where('season', $season);
    }

    if ($type) {
        $reportsQuery->where('type', $type);
    }

    $reports = $reportsQuery->get();

    // Determine current and previous year
    $currentYear = $request->filled('year') ? (int)$request->year : now()->year;
    $previousYear = $currentYear - 1;

    // Query current year
    $currentQuery = CallaGuddiftuu::where('kebele_id', $user->kebele_id)
        ->where('year', $currentYear)
        ->with(['user', 'woreda', 'zone']);

    // Apply season and type filters to both
    if ($request->filled('season')) {
        $currentQuery->where('season', $request->season);
    }
    if ($request->filled('type')) {
        $currentQuery->where('type', $request->type);
    }

    $currentReports = $currentQuery->orderBy('season')->orderBy('type')->get();

    // Query previous year
    $previousQuery = CallaGuddiftuu::where('kebele_id', $user->kebele_id)
        ->where('year', $previousYear)
        ->with(['user', 'woreda', 'zone']);

    if ($request->filled('season')) {
        $previousQuery->where('season', $request->season);
    }
    if ($request->filled('type')) {
        $previousQuery->where('type', $request->type);
    }

    $previousReports = $previousQuery->orderBy('season')->orderBy('type')->get();

    // Dropdown data
    $years = CallaGuddiftuu::distinct()->orderBy('year', 'desc')->pluck('year');
    $seasons = CallaGuddiftuu::distinct()->pluck('season');
    $types = CallaGuddiftuu::distinct()->pluck('type');

    return view('reports.kebele-report.callagiddiftuu.kebele', [
        'reports' => $currentReports,
        'previousReports' => $previousReports,
        'years' => $years,
        'seasons' => $seasons,
        'types' => $types,
        'currentYear' => $currentYear,
        'previousYear' => $previousYear,
    ]);
}


public function woredaReport(Request $request)
    {
        // Authentication check
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access this report');
        }

        // Authorization check
        if (!$user->woreda_id) {
            abort(403, 'Unauthorized - Your account is not associated with any Woreda');
        }

        // Get the woreda with its kebeles
        $woreda = Woreda::with('kebeles')->find($user->woreda_id);
        if (!$woreda) {
            abort(403, 'Unauthorized - Your assigned Woreda could not be found');
        }

        // Get filter parameters
        $year = $request->input('year');
        $season = $request->input('season');
        $type = $request->input('type');
        $kebeleId = $request->input('kebele');

        // Validate kebele belongs to this woreda if specified
        if ($kebeleId) {
            $kebeleExists = $woreda->kebeles->contains('id', $kebeleId);
            if (!$kebeleExists) {
                abort(403, 'Unauthorized - The specified Kebele does not belong to your Woreda');
            }
        }

        // Base query for current reports
        $currentQuery = CallaGuddiftuu::with(['user', 'kebele'])
            ->whereIn('kebele_id', $woreda->kebeles->pluck('id'));

        // Determine current and previous year
        $currentYear = $request->filled('year') ? (int)$request->year : now()->year;
        $previousYear = $currentYear - 1;

        // Apply filters to current year query
        $currentQuery->where('year', $currentYear);
        
        if ($season) {
            $currentQuery->where('season', $season);
        }
        if ($type) {
            $currentQuery->where('type', $type);
        }
        if ($kebeleId) {
            $currentQuery->where('kebele_id', $kebeleId);
        }

        $currentReports = $currentQuery->orderBy('kebele_id')
            ->orderBy('season')
            ->orderBy('type')
            ->get();

        // Query for previous year comparison
        $previousQuery = CallaGuddiftuu::with(['user', 'kebele'])
            ->whereIn('kebele_id', $woreda->kebeles->pluck('id'))
            ->where('year', $previousYear);

        if ($season) {
            $previousQuery->where('season', $season);
        }
        if ($type) {
            $previousQuery->where('type', $type);
        }
        if ($kebeleId) {
            $previousQuery->where('kebele_id', $kebeleId);
        }

        $previousReports = $previousQuery->orderBy('kebele_id')
            ->orderBy('season')
            ->orderBy('type')
            ->get();

        // Get dropdown filter options
        $years = CallaGuddiftuu::whereIn('kebele_id', $woreda->kebeles->pluck('id'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $seasons = CallaGuddiftuu::whereIn('kebele_id', $woreda->kebeles->pluck('id'))
            ->distinct()
            ->pluck('season');

        $types = CallaGuddiftuu::whereIn('kebele_id', $woreda->kebeles->pluck('id'))
            ->distinct()
            ->pluck('type');

        return view('reports.woreda-report.callagiddiftuu.woreda', [
            'reports' => $currentReports,
            'previousReports' => $previousReports,
            'years' => $years,
            'seasons' => $seasons,
            'types' => $types,
            'kebeles' => $woreda->kebeles,
            'woreda' => $woreda,
            'currentYear' => $currentYear,
            'previousYear' => $previousYear,
            'filters' => [
                'year' => $year,
                'season' => $season,
                'type' => $type,
                'kebele' => $kebeleId,
            ]
        ]);
    }

   
     
        public function zoneReport(Request $request)
        {
            // 1. Authentication and Authorization
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please log in to access the report');
            }
    
            if (!$user->zone_id) {
                return back()->with('error', 'Your account is not authorized to view zone-level reports');
            }
    
            $zone = Zone::with('woredas.kebeles')->find($user->zone_id);
            
            if (!$zone) {
                return back()->with('error', 'The zone associated with your account was not found');
            }
    
            // 2. Get Filter Parameters
            $filters = [
                'year' => $request->input('year', date('Y')),
                'season' => $request->input('season'),
                'type' => $request->input('type'),
                'woreda' => $request->input('woreda'),
                'kebele' => $request->input('kebele')
            ];
    
            // 3. Validate Woreda and Kebele Filters
            if ($filters['woreda'] && !$zone->woredas->contains('id', $filters['woreda'])) {
                return back()->with('error', 'The selected woreda does not belong to your zone');
            }
    
            if ($filters['kebele']) {
                $validKebele = false;
                foreach ($zone->woredas as $woreda) {
                    if ($woreda->kebeles->contains('id', $filters['kebele'])) {
                        $validKebele = true;
                        break;
                    }
                }
                if (!$validKebele) {
                    return back()->with('error', 'The selected kebele does not belong to your zone');
                }
            }
    
            // 4. Get all kebele IDs in the zone
            $kebeleIds = collect();
            foreach ($zone->woredas as $woreda) {
                $kebeleIds = $kebeleIds->merge($woreda->kebeles->pluck('id'));
            }
    
            // 5. Get Filter Options
            $baseQuery = CallaGuddiftuu::whereIn('kebele_id', $kebeleIds);
            
            $filterOptions = [
                'years' => $baseQuery->clone()
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year'),
                'seasons' => $baseQuery->clone()
                    ->distinct()
                    ->orderBy('season')
                    ->pluck('season'),
                'types' => $baseQuery->clone()
                    ->distinct()
                    ->orderBy('type')
                    ->pluck('type'),
                'kebeles' => Kebele::whereIn('id', $kebeleIds)
                    ->orderBy('name')
                    ->get()
            ];
    
            // 6. Get Report Data - Current Year
            $currentQuery = CallaGuddiftuu::with(['user', 'kebele.woreda'])
                ->whereIn('kebele_id', $kebeleIds)
                ->where('year', $filters['year']);
    
            // 7. Get Report Data - Previous Year
            $previousQuery = CallaGuddiftuu::with(['user', 'kebele.woreda'])
                ->whereIn('kebele_id', $kebeleIds)
                ->where('year', $filters['year'] - 1);
    
            // 8. Apply filters to both queries
            foreach (['season', 'type'] as $filter) {
                if ($filters[$filter]) {
                    $currentQuery->where($filter, $filters[$filter]);
                    $previousQuery->where($filter, $filters[$filter]);
                }
            }
    
            // 9. Apply woreda filter if specified
            if ($filters['woreda']) {
                $woredaKebeleIds = Woreda::find($filters['woreda'])->kebeles->pluck('id');
                $currentQuery->whereIn('kebele_id', $woredaKebeleIds);
                $previousQuery->whereIn('kebele_id', $woredaKebeleIds);
            }
    
            // 10. Apply kebele filter if specified
            if ($filters['kebele']) {
                $currentQuery->where('kebele_id', $filters['kebele']);
                $previousQuery->where('kebele_id', $filters['kebele']);
            }
    
            // 11. Execute queries
            $currentReports = $currentQuery->orderBy('kebele_id')
                ->orderBy('season')
                ->orderBy('type')
                ->get();
    
            $previousReports = $previousQuery->orderBy('kebele_id')
                ->orderBy('season')
                ->orderBy('type')
                ->orderBy('year')
                ->get();
    
            // 12. Return View with Data
            return view('reports.zone-report.callagiddiftuu.zone', [
                'reports' => $currentReports,
                'previousReports' => $previousReports,
                'zone' => $zone,
                'currentYear' => $filters['year'],
                'previousYear' => $filters['year'] - 1,
                'years' => $filterOptions['years'],
                'seasons' => $filterOptions['seasons'],
                'types' => $filterOptions['types'],
                'woredas' => $zone->woredas,
                'allKebeles' => $filterOptions['kebeles'],
                'filters' => $filters
            ]);
        }
    




public function export(Request $request)
{
    $query = CallaGuddiftuu::with(['kebele', 'creator', 'user']); // Eager load all possible relationships
    
    // Apply your filters here...
    
    $inputs = $query->get();
    $fileName = 'input_planning_report_' . now()->format('Y_m_d');

    return Excel::download(new InputPlanningExport($inputs), $fileName . '.xlsx');
}


// public function kebelereport()
// {
//     $userKebele = Auth::user()->kebele_id;
    
//     $report = CallaGuddiftuu::where('kebele_id', $userKebele)
//         ->selectRaw('year, season, type, sum(quantity) as total_quantity, unit')
//         ->groupBy('year', 'season', 'type', 'unit')
//         ->orderBy('year', 'desc')
//         ->orderBy('season')
//         ->orderBy('type')
//         ->get();

//     return view('fertilizer.report', compact('report'));
// }


}
