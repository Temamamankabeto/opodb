<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\LoanMechanization;
use App\Models\MaterialSupply;
use App\Models\Woreda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class LoanController extends Controller
{
    public function create(){
        return view('karora.karoora-anaa.liqii',[
            'loanTypes' => [
                'Liqii waliigalaa' => 'Liqii waliigalaa (General Loan)',
                'Liqii deebi\'e' => 'Liqii deebi\'e (Repaid Loan)',
                'iqii hafe' => 'iqii hafe (Remaining Loan)',
                'Qusannaa' => 'Qusannaa (Mechanization)'
            ]
        ]);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'inputs.*.loan_type' => 'required|string|max:255',
            'inputs.*.private_quantity' => 'required|numeric|min:0',
            'inputs.*.group_quantity' => 'required|numeric|min:0',
        ]);

        try {
            foreach ($request->inputs as $input) {
                LoanMechanization::create([
                    'year' => $request->year,
                    'loan_type' => $input['loan_type'],
                    'private_quantity' => $input['private_quantity'],
                    'group_quantity' => $input['group_quantity'],
                    'total_quantity' => $input['private_quantity'] + $input['group_quantity'],
                    'kebele_id' => $request->kebele_id,
                    'woreda_id' => $request->woreda_id,
                    'zone_id' => $request->zone_id,
                    'created_by' => $request->created_by,
                    'phone' => $request->phone,
                ]);
            }

            return back()->with('success', 'loan   saved successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving materials: ' . $e->getMessage());
        }
    }


    
    
        public function kebeleReport(Request $request)
    {
        $user = Auth::user();
        $kebeleId = $user->kebele_id;
    
        // 1. Get selected year or default to current year
        $year = $request->input('year', now()->year);
        $prevYear = $year - 1;
    
        // 2. Fetch material data
        $materialsCurrent = MaterialSupply::where('kebele_id', $kebeleId)
            ->where('year', $year)
            ->select('material_name', DB::raw('SUM(total_qty) as total'))
            ->groupBy('material_name')
            ->pluck('total', 'material_name')
            ->toArray();
    
        $materialsPrevious = MaterialSupply::where('kebele_id', $kebeleId)
            ->where('year', $prevYear)
            ->select('material_name', DB::raw('SUM(total_qty) as total'))
            ->groupBy('material_name')
            ->pluck('total', 'material_name')
            ->toArray();
    
        $allMaterialNames = array_unique(array_merge(array_keys($materialsCurrent), array_keys($materialsPrevious)));
    
        $materialData = [];
        foreach ($allMaterialNames as $name) {
            $materialData[$name] = [
                'current' => $materialsCurrent[$name] ?? 0,
                'previous' => $materialsPrevious[$name] ?? 0,
            ];
        }
    
        // 3. Fetch loan data
        $loansCurrent = LoanMechanization::where('kebele_id', $kebeleId)
            ->where('year', $year)
            ->select('loan_type', DB::raw('SUM(total_quantity) as total'))
            ->groupBy('loan_type')
            ->pluck('total', 'loan_type')
            ->toArray();
    
        $loansPrevious = LoanMechanization::where('kebele_id', $kebeleId)
            ->where('year', $prevYear)
            ->select('loan_type', DB::raw('SUM(total_quantity) as total'))
            ->groupBy('loan_type')
            ->pluck('total', 'loan_type')
            ->toArray();
    
        $allLoanTypes = array_unique(array_merge(array_keys($loansCurrent), array_keys($loansPrevious)));
    
        $loanData = [];
        foreach ($allLoanTypes as $type) {
            $loanData[$type] = [
                'current' => $loansCurrent[$type] ?? 0,
                'previous' => $loansPrevious[$type] ?? 0,
            ];
        }
    
        // 4. Combine for chart
        $chartLabels = array_merge($allMaterialNames, $allLoanTypes);
        $prevData = [];
        $currData = [];
    
        foreach ($chartLabels as $label) {
            $prevData[] = $materialData[$label]['previous'] ?? $loanData[$label]['previous'] ?? 0;
            $currData[] = $materialData[$label]['current'] ?? $loanData[$label]['current'] ?? 0;
        }
    
        return view('reports.kebele-report.dhiyeessii-mesha.kebele', compact(
            'year',
            'prevYear',
            'materialData',
            'loanData',
            'chartLabels',
            'prevData',
            'currData'
        ));
    
   
   
    }

    public function woredaReport(Request $request)
    {
        // You can get the woreda from logged-in user or pass it via request
        $woredaId = auth()->user()->woreda_id ?? $request->woreda_id;

        // Validate woreda ID presence
        if (!$woredaId) {
            abort(403, 'Woreda not specified.');
        }

        // Year selection from request or current year default
        $year = $request->input('year', now()->year);
        $prevYear = $year - 1;

        // Get all kebeles in the woreda
        $kebeles = Kebele::where('woreda_id', $woredaId)->pluck('name', 'id');

        // --- Woreda-level summary ---

        // Material Supplies summary for woreda total (sum over all kebeles)
        $materialTotalsRaw = MaterialSupply::whereIn('kebele_id', $kebeles->keys())
            ->whereIn('year', [$year, $prevYear])
            ->select('material_name', 'year', DB::raw('SUM(total_qty) as total'))
            ->groupBy('material_name', 'year')
            ->get();

        // Structure totals by material and year
        $materialTotals = [];
        foreach ($materialTotalsRaw as $item) {
            $yearKey = $item->year == $year ? 'current' : 'previous';
            $materialTotals[$item->material_name][$yearKey] = $item->total;
        }

        // Loan Mechanizations summary for woreda total
        $loanTotalsRaw = LoanMechanization::whereIn('kebele_id', $kebeles->keys())
            ->whereIn('year', [$year, $prevYear])
            ->select('loan_type', 'year', DB::raw('SUM(total_quantity) as total'))
            ->groupBy('loan_type', 'year')
            ->get();

        $loanTotals = [];
        foreach ($loanTotalsRaw as $item) {
            $yearKey = $item->year == $year ? 'current' : 'previous';
            $loanTotals[$item->loan_type][$yearKey] = $item->total;
        }

        // --- Detailed kebele breakdown ---

        // Material Supplies by kebele for both years
        $materialByKebeleRaw = MaterialSupply::whereIn('kebele_id', $kebeles->keys())
            ->whereIn('year', [$year, $prevYear])
            ->select('kebele_id', 'year', 'material_name', DB::raw('SUM(total_qty) as total'))
            ->groupBy('kebele_id', 'year', 'material_name')
            ->get();

        $materialByKebele = [];
        foreach ($materialByKebeleRaw as $item) {
            $kebeleName = $kebeles[$item->kebele_id];
            $yearKey = $item->year == $year ? 'current_qty' : 'prev_qty';

            $key = $kebeleName . '_' . $item->material_name;
            if (!isset($materialByKebele[$key])) {
                $materialByKebele[$key] = (object)[
                    'kebele_name' => $kebeleName,
                    'material_name' => $item->material_name,
                    'current_qty' => 0,
                    'prev_qty' => 0,
                ];
            }
            $materialByKebele[$key]->$yearKey = $item->total;
        }

        // Loan Mechanizations by kebele
        $loanByKebeleRaw = LoanMechanization::whereIn('kebele_id', $kebeles->keys())
            ->whereIn('year', [$year, $prevYear])
            ->select('kebele_id', 'year', 'loan_type', DB::raw('SUM(total_quantity) as total'))
            ->groupBy('kebele_id', 'year', 'loan_type')
            ->get();

        $loanByKebele = [];
        foreach ($loanByKebeleRaw as $item) {
            $kebeleName = $kebeles[$item->kebele_id];
            $yearKey = $item->year == $year ? 'current_qty' : 'prev_qty';

            $key = $kebeleName . '_' . $item->loan_type;
            if (!isset($loanByKebele[$key])) {
                $loanByKebele[$key] = (object)[
                    'kebele_name' => $kebeleName,
                    'loan_type' => $item->loan_type,
                    'current_qty' => 0,
                    'prev_qty' => 0,
                ];
            }
            $loanByKebele[$key]->$yearKey = $item->total;
        }

        // --- Chart data preparation ---

        // Chart labels - union of material names and loan types
        $chartLabels = array_unique(array_merge(
            array_keys($materialTotals),
            array_keys($loanTotals)
        ));
        sort($chartLabels);

        // Data for previous and current year for chart
        $prevChartData = [];
        $currChartData = [];

        foreach ($chartLabels as $label) {
            // Try to get from material totals, fallback to loan totals
            $prevChartData[] = $materialTotals[$label]['previous'] ?? $loanTotals[$label]['previous'] ?? 0;
            $currChartData[] = $materialTotals[$label]['current'] ?? $loanTotals[$label]['current'] ?? 0;
        }

        return view('reports.woreda-report.dhiyeessii-mesha.woreda', [
            'year' => $year,
            'prevYear' => $prevYear,
            'materialData' => $materialTotals,
            'loanData' => $loanTotals,
            'materialByKebele' => $materialByKebele,
            'loanByKebele' => $loanByKebele,
            'chartLabels' => $chartLabels,
            'prevData' => $prevChartData,
            'currData' => $currChartData,
        ]);
    }
    
    

 public function generate(Request $request)
    {
        $request->validate([
            'year' => 'required|integer|min:2000|max:'.(date('Y')+1),
        ]);
        
        $year = $request->input('year');
        $kebeleId = Auth::user()->kebele_id;
        
        if (!$kebeleId) {
            return redirect()->back()->with('error', 'You are not associated with any kebele.');
        }
        
        // Get material supplies for this kebele and year
        $materialSupplies = MaterialSupply::where('kebele_id', $kebeleId)
            ->where('year', $year)
            ->select('material_name', 'total_qty')
            ->orderBy('material_name')
            ->get();
            
        // Get loan mechanizations for this kebele and year
        $loanMechanizations = LoanMechanization::where('kebele_id', $kebeleId)
            ->where('year', $year)
            ->select('loan_type', 'total_quantity')
            ->orderBy('loan_type')
            ->get();
            
        return view('reports.kebele-report.dhiyeessii-mesha.kebele', [
            'materialSupplies' => $materialSupplies,
            'loanMechanizations' => $loanMechanizations,
            'selectedYear' => $year
        ]);
    
    }
    
            
    public function zoneReport(Request $request)
{
    // Get the zone from logged-in user or pass it via request
    $zoneId = auth()->user()->zone_id ?? $request->zone_id;

    // Validate zone ID presence
    if (!$zoneId) {
        abort(403, 'Zone not specified.');
    }

    // Year selection from request or current year default
    $year = $request->input('year', now()->year);
    $prevYear = $year - 1;

    // Get all woredas in the zone
    $woredas = Woreda::where('zone_id', $zoneId)->pluck('name', 'id');

    // Get all kebeles in the zone (via woredas)
    $kebeles = Kebele::whereIn('woreda_id', $woredas->keys())
        ->with('woreda')
        ->get()
        ->mapWithKeys(fn($k) => [$k->id => $k->woreda->name . ' - ' . $k->name]);

    // --- Zone-level summary ---

    // Material Supplies summary for zone total (sum over all kebeles)
    $materialTotalsRaw = MaterialSupply::whereIn('kebele_id', $kebeles->keys())
        ->whereIn('year', [$year, $prevYear])
        ->select('material_name', 'year', DB::raw('SUM(total_qty) as total'))
        ->groupBy('material_name', 'year')
        ->get();

    // Structure totals by material and year
    $materialTotals = [];
    foreach ($materialTotalsRaw as $item) {
        $yearKey = $item->year == $year ? 'current' : 'previous';
        $materialTotals[$item->material_name][$yearKey] = $item->total;
    }

    // Loan Mechanizations summary for zone total
    $loanTotalsRaw = LoanMechanization::whereIn('kebele_id', $kebeles->keys())
        ->whereIn('year', [$year, $prevYear])
        ->select('loan_type', 'year', DB::raw('SUM(total_quantity) as total'))
        ->groupBy('loan_type', 'year')
        ->get();

    $loanTotals = [];
    foreach ($loanTotalsRaw as $item) {
        $yearKey = $item->year == $year ? 'current' : 'previous';
        $loanTotals[$item->loan_type][$yearKey] = $item->total;
    }

    // --- Detailed woreda breakdown ---

    // Material Supplies by woreda for both years
    $materialByWoredaRaw = DB::table('material_supplies')
        ->join('kebeles', 'material_supplies.kebele_id', '=', 'kebeles.id')
        ->whereIn('kebeles.woreda_id', $woredas->keys())
        ->whereIn('material_supplies.year', [$year, $prevYear])
        ->select(
            'kebeles.woreda_id',
            'material_supplies.year',
            'material_supplies.material_name',
            DB::raw('SUM(material_supplies.total_qty) as total')
        )
        ->groupBy('kebeles.woreda_id', 'material_supplies.year', 'material_supplies.material_name')
        ->get();

    $materialByWoreda = [];
    foreach ($materialByWoredaRaw as $item) {
        $woredaName = $woredas[$item->woreda_id];
        $yearKey = $item->year == $year ? 'current_qty' : 'prev_qty';

        $key = $woredaName . '_' . $item->material_name;
        if (!isset($materialByWoreda[$key])) {
            $materialByWoreda[$key] = (object)[
                'woreda_name' => $woredaName,
                'material_name' => $item->material_name,
                'current_qty' => 0,
                'prev_qty' => 0,
            ];
        }
        $materialByWoreda[$key]->$yearKey = $item->total;
    }

    // Loan Mechanizations by woreda
    $loanByWoredaRaw = DB::table('loan_mechanizations')
        ->join('kebeles', 'loan_mechanizations.kebele_id', '=', 'kebeles.id')
        ->whereIn('kebeles.woreda_id', $woredas->keys())
        ->whereIn('loan_mechanizations.year', [$year, $prevYear])
        ->select(
            'kebeles.woreda_id',
            'loan_mechanizations.year',
            'loan_mechanizations.loan_type',
            DB::raw('SUM(loan_mechanizations.total_quantity) as total')
        )
        ->groupBy('kebeles.woreda_id', 'loan_mechanizations.year', 'loan_mechanizations.loan_type')
        ->get();

    $loanByWoreda = [];
    foreach ($loanByWoredaRaw as $item) {
        $woredaName = $woredas[$item->woreda_id];
        $yearKey = $item->year == $year ? 'current_qty' : 'prev_qty';

        $key = $woredaName . '_' . $item->loan_type;
        if (!isset($loanByWoreda[$key])) {
            $loanByWoreda[$key] = (object)[
                'woreda_name' => $woredaName,
                'loan_type' => $item->loan_type,
                'current_qty' => 0,
                'prev_qty' => 0,
            ];
        }
        $loanByWoreda[$key]->$yearKey = $item->total;
    }

    // --- Chart data preparation ---

    // Chart labels - union of material names and loan types
    $chartLabels = array_unique(array_merge(
        array_keys($materialTotals),
        array_keys($loanTotals)
    ));
    sort($chartLabels);

    // Data for previous and current year for chart
    $prevChartData = [];
    $currChartData = [];

    foreach ($chartLabels as $label) {
        $prevChartData[] = $materialTotals[$label]['previous'] ?? $loanTotals[$label]['previous'] ?? 0;
        $currChartData[] = $materialTotals[$label]['current'] ?? $loanTotals[$label]['current'] ?? 0;
    }

    return view('reports.zone-report.dhiyeessii-mesha.zone', [
        'year' => $year,
        'prevYear' => $prevYear,
        'materialData' => $materialTotals,
        'loanData' => $loanTotals,
        'materialByWoreda' => $materialByWoreda,
        'loanByWoreda' => $loanByWoreda,
        'chartLabels' => $chartLabels,
        'prevData' => $prevChartData,
        'currData' => $currChartData,
        'woredas' => $woredas,
    ]);
}
    
}

