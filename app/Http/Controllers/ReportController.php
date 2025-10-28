<?php

namespace App\Http\Controllers;

use App\Models\CropPlanAchievement;
use App\Models\CropType;
use App\Models\Kebele;
use App\Models\LivestockPlanAchievement;
use App\Models\LivestockSupply;
use App\Models\LivestockType;
use App\Models\WeeklyCropPlanAchievement;
use App\Models\YealrlyCropPlan;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    
    public function regionreport()
    {

        return view('reports.region-report.all_report');
    }
    public function baadiyaa()
    {

        return view('dashboard.misooma-baadiyaa.baadiyyaa');
    }

    public function akaakuu()
    {
        $croptypes = CropType::all();
        return view('dashboard.misooma-baadiyaa.akaakuu.akaakuu_midhaanii', compact('croptypes'));
    }

    public function akaakuuBeeyladaa()
    {
        $livestocktypes = LivestockType::all();
        return view('dashboard.misooma-baadiyaa.akaakuu.akaakuu_beeyladaa', compact('livestocktypes'));
    }

    public function livestocktype($id)
    {
        $livestocktype = LivestockType::find($id);
        return view('dashboard.misooma-baadiyaa.beeyladaa', compact('livestocktype'));
    }

    public function agada()
    {

        return view('dashboard.misooma-baadiyaa.agada');
    }

    public function type($id)
    {
        $croptype = CropType::find($id);
        return view('dashboard.misooma-baadiyaa.agada', compact('croptype'));
    }

    public function karooraagada()
    {
        $achievements = CropPlanAchievement::with(['crop', 'cropType'])
            ->get()
            ->groupBy(function ($item) {
                return $item->cropType->name ?? 'Gosa Hinbeekamne';
            })->map(function ($groupedItems) {
                return $groupedItems->groupBy(function ($item) {
                    return $item->maqaa_midhaanii;
                });
            });

        return view('dashboard.misooma-baadiyaa.karoora.index', compact('achievements'));
    }


    public function karoorabeeylada()
    {
        $achievements = LivestockPlanAchievement::all(); // paginate by 10 rows
        return view('dashboard.misooma-baadiyaa.karoora.beeylada', compact('achievements'));
    }


    public function index(Request $request)
    {
        $achievements = CropPlanAchievement::with('cropType', 'crops')->paginate(100); // paginate by 10 rows
        return view('dashboard.misooma-baadiyaa.agada', compact('achievements'));
    }


    public function karooraboqqoolloo()
    {
        $boqqolloo = YearlyCropPlan::where('crop_id', '1')->get();
        $garbuu = YearlyCropPlan::where('crop_id', '2')->get();
        $qamadii = YearlyCropPlan::where('crop_id', '4')->get();

        return view('dashboard.misooma-baadiyaa.karoora.midhaan_agadaa', compact('boqqolloo', 'garbuu', 'qamadii'));
    }

    public function weekplanreport(Request $request)
    {
        $yearlyCropPlans = WeeklyCropPlanAchievement::all();
        return view('reports.crop_plan_report', compact('yearlyCropPlans'));
    }

    public function Kebelereport()
    {

        return view('reports.kebele-report.crop_report');
    }

    public function kebeleLivestockAnalysis(Request $request)
    {
        $years=['2013','2014','2015','2016','2017','2018','2019','2020'];
        $selectedYear = request('year') ?? now()->year;

        $currentYear = $selectedYear;
        $previousYear = $selectedYear - 1;

// Fetch totals for current year
        $totals = LivestockSupply::where('plan_year', $currentYear)->selectRaw('
    SUM(ai_cows_baseline) as ai_cows_baseline,
    SUM(ai_cows_plan) as ai_cows_plan,
    SUM(one_day_chick_plan) as one_day_chick_plan,
    SUM(twentyone_days_chicks_baseline) as twentyone_days_chicks_baseline,
    SUM(twentyone_days_chicks_plan) as twentyone_days_chicks_plan,
    SUM(fry_fish_baseline) as fry_fish_baseline,
    SUM(fry_fish_plan) as fry_fish_plan,
    SUM(beehives_baseline) as beehives_baseline,
    SUM(beehives_plan) as beehives_plan
')->where('kebele_id',Auth::user()->kebele_id)->first()->toArray();

// Fetch totals for previous year
        $previousTotals = LivestockSupply::where('plan_year', $previousYear)->selectRaw('
    SUM(ai_cows_baseline) as ai_cows_baseline,
    SUM(ai_cows_plan) as ai_cows_plan,
    SUM(one_day_chick_plan) as one_day_chick_plan,
    SUM(twentyone_days_chicks_baseline) as twentyone_days_chicks_baseline,
    SUM(twentyone_days_chicks_plan) as twentyone_days_chicks_plan,
    SUM(fry_fish_baseline) as fry_fish_baseline,
    SUM(fry_fish_plan) as fry_fish_plan,
    SUM(beehives_baseline) as beehives_baseline,
    SUM(beehives_plan) as beehives_plan
')->where('kebele_id',Auth::user()->kebele_id)->first()->toArray();

        return view('reports.kebele-report.livestock-analysis',
            compact(
                'selectedYear',
                'totals',
                'years',
                'previousTotals')
        );

    }


    public function allreport(){

        return view('reports.gabasa');
    }
}
