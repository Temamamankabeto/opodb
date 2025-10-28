<?php

namespace App\Http\Controllers;

use App\Models\CivilRegistrationPlan;
use App\Models\CropType;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CivilRegistrationController extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');
        return view('civil_registration.new', compact('cropTypes', 'planYears'));
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'year.*' => 'required|numeric',
            'birth_cert.*' => 'required|numeric',
            'marriage_cert.*' => 'required|numeric',
            'adoption.*' => 'required|numeric',
            'divorce_cert.*' => 'required|numeric',
            'death_cert.*' => 'required|numeric',
        ]);

        $region_id = auth()->user()->region_id ?? 1;
        $zone_id = auth()->user()->zone_id ?? 1;
        $woreda_id = auth()->user()->woreda_id ?? 1;
        $kebele_id = auth()->user()->kebele_id ?? 1;

        $entries = count($request->year);
        $duplicateYears = [];

        for ($i = 0; $i < $entries; $i++) {
            $year = $request->year[$i];

            $exists = DB::table('civil_registration_plans')->where([
                ['year', $year],
                ['region', $region_id],
                ['zone_id', $zone_id],
                ['woreda_id', $woreda_id],
                ['kebele_id', $kebele_id],
            ])->exists();

            if ($exists) {
                $duplicateYears[] = $year;
                continue;
            }

            DB::table('civil_registration_plans')->insert([
                'year' => $year,
                'birth_cert' => $request->birth_cert[$i],
                'marriage_cert' => $request->marriage_cert[$i],
                'adoption' => $request->adoption[$i],
                'divorce_cert' => $request->divorce_cert[$i],
                'death_cert' => $request->death_cert[$i],
                'region' => $region_id,
                'zone_id' => $zone_id,
                'woreda_id' => $woreda_id,
                'kebele_id' => $kebele_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (count($duplicateYears)) {
            return redirect()->back()->withErrors(['Duplicate year(s): ' . implode(', ', $duplicateYears)]);
        }

        return redirect()->back()->with('success', 'Civil registration plans saved successfully.');
    }

    public function Report(Request $request)
    {
        $currentYear = $request->input('year', 2017);
        $previousYear = $currentYear - 1;

        $region_id = auth()->user()->region_id ?? 1;
        $zone_id = auth()->user()->zone_id ?? 1;
        $woreda_id = auth()->user()->woreda_id ?? 1;
        $kebele_id = auth()->user()->kebele_id ?? 1;

        $years = [$previousYear, $currentYear];

        $data = DB::table('civil_registration_plans')
            ->select('year', 'birth_cert', 'marriage_cert', 'adoption', 'divorce_cert', 'death_cert')
            ->whereIn('year', $years)
            ->where('region', $region_id)
            ->where('zone_id', $zone_id)
            ->where('woreda_id', $woreda_id)
            ->where('kebele_id', $kebele_id)
            ->get()
            ->keyBy('year');

        return view('civil_registration.report', compact('data', 'years', 'currentYear'));
    }
}
