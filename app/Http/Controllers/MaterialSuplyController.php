<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use App\Models\MaterialSupply;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialSuplyController extends Controller
{
   public function create(){

    return view('karora.karoora-anaa.material_supply');
   }

   public function store(Request $request)
{
   $request->validate([
    'year' => 'required|integer|min:2011',
    'inputs.*.material_name' => 'required|string',
    'inputs.*.private_qty' => 'nullable|numeric|min:0',
    'inputs.*.group_qty' => 'nullable|numeric|min:0',
    'kebele_id' => 'required|exists:kebeles,id',
]);
    $errors = [];
    
    foreach ($request->inputs as $index => $input) {
        // Check if record already exists
        $exists = MaterialSupply::where('year', $request->year)
            ->where('kebele_id', $request->kebele_id)
            ->where('material_name', $input['material_name'])
            ->exists();

        if ($exists) {
            $errors["inputs.$index.material_name"] = "Qabeenyi '{$input['material_name']}' bara '{$request->year}' keessatti Kebele kanaaf jira. Galmeessuu hindandeenye!";
            continue;
        }

        MaterialSupply::create([
            'year' => $request->year,
            'material_name' => $input['material_name'],
            'private_qty' => $input['private_qty'],
            'group_qty' => $input['group_qty'],
            'total_qty' => $input['private_qty'] + $input['group_qty'],
            'kebele_id' => $request->kebele_id,
            'woreda_id' => $request->woreda_id,
            'zone_id' => $request->zone_id,
            'region_id' => $request->region_id,
            'created_by' => $request->created_by,
            'phone' => $request->phone,
        ]);
    }

    if (!empty($errors)) {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors($errors)
            ->with('error', 'Qabeenyi tokko tokko duraan galmeeffame jira. Galmeessuu hindandeenye!');
    }

    return redirect()
        ->back()
        ->with('success', 'Odeeffannoon Qabeenya Mekaanayizeeshinii milkaa\'inaan olkaa\'ame!');
}

public function kebelereport(Request $request)
{
    $kebeleId = auth()->user()->kebele_id;

    $selectedYear = $request->input('year', now()->year);
    $previousYear = $selectedYear - 1;
    $materialFilter = $request->input('material');

    $query = MaterialSupply::where('kebele_id', $kebeleId);

    if ($materialFilter) {
        $query->where('material_name', $materialFilter);
    }

    $inputs = $query->whereIn('year', [$selectedYear, $previousYear])
                    ->get();

    $grouped = $inputs->groupBy('material_name');

    $reportData = [];
    $chartLabels = [];
    $chartCurrentTotals = [];
    $chartPreviousTotals = [];
    $chartPercentageChange = [];

    foreach ($grouped as $material => $entries) {
        $current = $entries->where('year', $selectedYear);
        $previous = $entries->where('year', $previousYear);

        $currentTotal = $current->sum('total_qty');
        $previousTotal = $previous->sum('total_qty');

        $change = $previousTotal > 0
            ? round((($currentTotal - $previousTotal) / $previousTotal) * 100, 1)
            : null;

        $reportData[] = [
            'material' => $material,
            'previous_total' => $previousTotal,
            'current_total' => $currentTotal,
            'percentage_change' => $change
        ];

        $chartLabels[] = $material;
        $chartCurrentTotals[] = $currentTotal;
        $chartPreviousTotals[] = $previousTotal;
        $chartPercentageChange[] = $change;
    }

    $allYears = MaterialSupply::distinct()->pluck('year');
    $allMaterials = MaterialSupply::distinct()->pluck('material_name');

    return view('reports.kebele-report.dhiyeessii-mesha.kebele', compact(
        'reportData', 'selectedYear', 'previousYear', 'allYears', 'allMaterials',
        'chartLabels', 'chartCurrentTotals', 'chartPreviousTotals', 'chartPercentageChange'
    ));
}






   }



