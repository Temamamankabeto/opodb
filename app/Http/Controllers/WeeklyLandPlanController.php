<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use App\Models\WeeklyLandPlan;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class WeeklyLandPlanController extends Controller
{
    public function index()
    {
        $plans = WeeklyLandPlan::with(['cropType', 'crop']) ;
        $plans = WeeklyLandPlan::with(['cropType', 'crop'])
    ->orderBy('plan_year')
    ->orderBy('croptype_id')
    ->orderBy('crop_id')
    ->orderBy('month')
    ->orderBy('week')
    ->get()
    ->groupBy(function($item) {
        return $item->croptype_id . '-' . $item->plan_year;
    });

        return view('weekly_plans.land.index', compact('plans'));
    }


    public function indexana()
    {
        $plans = WeeklyLandPlan::with(['kebele', 'cropType', 'crop'])
            ->orderBy('plan_year')
            ->orderBy('kebele_id')
            ->orderBy('croptype_id')
            ->orderBy('crop_id')
            ->orderBy('month')
            ->orderBy('week')
            ->get()
            ->groupBy(function ($item) {
                return optional($item->kebele)->name . '-' . $item->croptype_id . '-' . $item->plan_year;
            });
    
        return view('reports.crop_plan_report', compact('plans'));
    }


    
    

    public function indexcropana()
    {
        $plans = WeeklyLandPlan::with(['cropType', 'crop']) ;
        $plans = WeeklyLandPlan::with(['cropType', 'crop'])
    ->orderBy('plan_year')
    ->orderBy('croptype_id')
    ->orderBy('crop_id')
    ->orderBy('month')
    ->orderBy('week')
    ->get()
    ->groupBy(function($item) {
        return $item->croptype_id . '-' . $item->plan_year;
    });

        return view('weekly_plans.land.index', compact('plans'));
    }


    public function indexregion()
    {
        $plans = WeeklyLandPlan::with(['cropType', 'crop']) ;
        $plans = WeeklyLandPlan::with(['cropType', 'crop'])
    ->orderBy('plan_year')
    ->orderBy('croptype_id')
    ->orderBy('crop_id')
    ->orderBy('month')
    ->orderBy('week')
    ->get()
    ->groupBy(function($item) {
        return $item->croptype_id . '-' . $item->plan_year;
    });

        return view('weekly_plans.land.index', compact('plans'));
    }











    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
        ->distinct()
        ->orderBy('plan_year', 'desc')
        ->pluck('plan_year');
        $months = [
            'July', 'August', 'September', 'October', 'November', 'December',
            'January', 'February', 'March', 'April', 'May', 'June'
        ];
        return view('weekly_plans.land.create', compact('months','cropTypes','planYears'));
    }

 public function store(Request $request) 
    {
        $request->validate([
            'croptype_id' => 'required|exists:crop_types,id',
            'kebele_id' => 'required|exists:kebeles,id',

            'crop_id' => 'required|exists:crops,id',
            'plan_year' => 'required|integer',
            'land_area_plan_idaama' => 'required|string',
            'month' => 'required|string',
            'day' => 'required|integer|min:1|max:30',
            'planned_value' => 'nullable|numeric',
            'achieved_value' => 'nullable|numeric',
        ]);
    
        $week = ceil($request->day / 7);
    
        // Check if already exists
        $existing = WeeklyLandPlan::where([
            ['croptype_id', $request->croptype_id],
            ['kebele_id', $request->kebele_id],
            
            ['crop_id', $request->crop_id],
            ['plan_year', $request->plan_year],
            ['month', $request->month],
            ['week', $week],
            ['land_area_plan_idaama', $request->land_area_plan_idaama],
        ])->first();
    
        if ($existing) {
            return back()->withErrors('Plan already exists for this activity/week.');
        }
    
        // Calculate achievement percentage
        $planned = $request->planned_value ?? 0;
        $achieved = $request->filled('achieved_value') ? $request->achieved_value : null;

         
        $achievementPercentage = ($planned > 0 && $achieved !== null)
        ? round(($achieved / $planned) * 100, 2)
        : null;

         // Sum previous weekly records for this crop, type, and year
    $previousPlans = WeeklyLandPlan::where([
        ['croptype_id', $request->croptype_id],
        ['kebele_id', $request->kebele_id],
        
        ['crop_id', $request->crop_id],
        ['plan_year', $request->plan_year],
    ])->get();

    $uptoPlanSum = $previousPlans->sum('planned_value') + $planned;
    $uptoAchievedSum = $previousPlans->sum('achieved_value') + $achieved;
    $uptoAchievedPercentage = $uptoPlanSum > 0 ? round(($uptoAchievedSum / $uptoPlanSum) * 100, 2) : null;

    // Fetch yearly plan from YearlyCropPlan model
    $yearlyPlan = \App\Models\YearlyCropPlan::where([
        ['croptype_id', $request->croptype_id],
        ['kebele_id', $request->kebele_id],
        
        ['crop_id', $request->crop_id], // Assuming `maqaa_midhaanii` maps to crop_id
        ['plan_year', $request->plan_year],
    ])->value('land_area_plan_idaama') ?? 0;

    $yearlyAchievedPercentage = $yearlyPlan > 0 ? round(($uptoAchievedSum / $yearlyPlan) * 100, 2) : null;



    WeeklyLandPlan::create([
            'croptype_id' => $request->croptype_id,
            'kebele_id' => $request->kebele_id,
            
            'crop_id' => $request->crop_id,
            'plan_year' => $request->plan_year,
            'land_area_plan_idaama' => $request->land_area_plan_idaama,
            'month' => $request->month,
            'week' => $week,
           'planned_value' => $planned,
        'achieved_value' => $achieved,
        'weekly_achievement_percentage' => $achievementPercentage,
        'upto_this_week_plan' => $uptoPlanSum,
        'upto_this_week_achievement' => $uptoAchievedSum,
        'upto_this_week_achieved_percentage' => $uptoAchievedPercentage,
        'yearly_achieved_percentage' => $yearlyAchievedPercentage,
    ]);

    return redirect()->route('weekly-plans.land.index')->with('success', 'Weekly Plan Created.');
    }
    
    
    public function edit($id)
    {
        $plan = WeeklyLandPlan::findOrFail($id);
        return view('weekly_plans.land.edit', compact('plan'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'achieved_value' => 'required|numeric',
        ]);
    
        $plan = WeeklyLandPlan::findOrFail($id);
    
        $planned = $plan->planned_value ;
        $achieved = $request->achieved_value;
        $achievementPercentage = $planned > 0 ? round(($achieved / $planned) * 100, 2) : null;
    
        // Update this plan first
        $plan->update([
            'achieved_value' => $achieved,
            'weekly_achievement_percentage' => $achievementPercentage,
        ]);
    
        // Recalculate cumulative sums for this crop, type, and year
        $allPlans = WeeklyLandPlan::where([
            ['croptype_id', $plan->croptype_id],
            ['crop_id', $plan->crop_id],
            ['plan_year', $plan->plan_year],
        ])->get();
    
        $uptoPlanSum = $allPlans->sum('planned_value');
        $uptoAchievedSum = $allPlans->sum('achieved_value');
        $uptoAchievedPercentage = $uptoPlanSum > 0 ? round(($uptoAchievedSum / $uptoPlanSum) * 100, 2) : null;
    
        // Get yearly planned value
        $yearlyPlan = \App\Models\YearlyCropPlan::where([
            ['croptype_id', $plan->croptype_id],
            ['crop_id', $plan->crop_id],
            ['plan_year', $plan->plan_year],
        ])->value('land_area_plan_idaama') ?? 0;
    
        $yearlyAchievedPercentage = $yearlyPlan > 0 ? round(($uptoAchievedSum / $yearlyPlan) * 100, 2) : null;
    
        // Update this week's record again with cumulative values
        $plan->update([
            'upto_this_week_plan' => $uptoPlanSum,
            'upto_this_week_achievement' => $uptoAchievedSum,
            'upto_this_week_achieved_percentage' => $uptoAchievedPercentage,
            'yearly_achieved_percentage' => $yearlyAchievedPercentage,
        ]);
    
        return redirect()->route('weekly-plans.land.index')->with('success', 'Achievement and cumulative stats updated.');
    }
    
    
    

    public function getByType($id)
    {
        $crops =  Crop::where('croptype_id', $id) // Changed from croptype_id
        ->select('id', 'name')
            ->get();
        return response()->json($crops);
    }


    // In your appropriate controller



    public function getPlanValue($crop_id, $plan_year)
{
    $plan = YearlyCropPlan::where('crop_id', $crop_id)
        ->where('plan_year', $plan_year)
        ->first();

    return response()->json(['land_area_plan_idaama' => $plan?->land_area_plan_idaama]);
}
}
