<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
 
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class YearlyCropPlanController extends Controller
{
    public function index()
    {
        $plans = YearlyCropPlan::with(['cropType', 'crop'])
                    // ->where('created_by', Auth::id())  // filter by logged-in user
                    ->latest()
                    ->paginate(100);
    
        $cropTypes = CropType::all();
        $crops = Crop::all();
    
        return view('misooma-midhaanii.index', compact('plans', 'cropTypes', 'crops'));
    }
    public function indexana()
    {
        $plans = YearlyCropPlan::with(['kebele', 'cropType', 'crop'])->latest()->paginate(10);
    
        return view('misooma-midhaanii.ana', compact('plans'));
    }

    
    public function report()
    {
        $reports = DB::table('yearly_crop_plans')
            ->join('kebeles', 'yearly_crop_plans.kebele_id', '=', 'kebeles.id')
            ->join('crop_types', 'yearly_crop_plans.croptype_id', '=', 'crop_types.id')
            ->join('crops', 'yearly_crop_plans.crop_id', '=', 'crops.id')
            ->select(
                'kebeles.name as kebele_name',
                'crop_types.name as crop_type_name',
                'crops.name as crop_name',
                'yearly_crop_plans.plan_year',
                DB::raw('SUM(CAST(land_area_plan_ganna AS DECIMAL(10,2))) as total_land_ganna'),
                DB::raw('SUM(CAST(production_plan_ganna AS DECIMAL(10,2))) as total_production_ganna'),
                DB::raw('SUM(CAST(productivity_plan_ganna AS DECIMAL(10,2))) as total_productivity_ganna')
            )
            ->groupBy(
                'yearly_crop_plans.kebele_id',
                'yearly_crop_plans.croptype_id',
                'yearly_crop_plans.crop_id',
                'yearly_crop_plans.plan_year',
                'kebeles.name',
                'crop_types.name',
                'crops.name'
            )
            ->orderBy('kebeles.name')
            ->get();

    return view('misooma-midhaanii.report', compact('reports'));
}
    
        
    

           
            public function create1()
            { 
                $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.livestock_report', compact('cropTypes', 'planYears'));
            }
            public function createganaplan()
            {
            // Fetch distinct production years from YearlyCropPlan
            $plans = YearlyCropPlan::distinct()->pluck('production_year')->sort();
                $cropTypes = CropType::all();
                $crops = Crop::all();
                return view('misooma-midhaanii.create', compact('cropTypes', 'crops','plans'));
            }

     
            public function create()
            { 
                $cropTypes = CropType::all();
                $planYears = YearlyCropPlan::select('plan_year')
                                ->distinct()
                                ->orderBy('plan_year', 'desc')
                                ->pluck('plan_year');
                
                return view('crop-plan.mecheni', compact('cropTypes', 'planYears'));
            }
             
            public function store(Request $request)
                {
                    // Validate incoming request
                    $validated = $request->validate([
                        'plan_year' => 'required|string',
                        'baseline_year' => 'required|string',
                        
                        'kebele_id' => 'required|exists:kebeles,id',
                        'croptype_id' => 'required|exists:crop_types,id',
                        'crop_id' => 'required|exists:crops,id',
            
                        // Plan inputs
                        'land_area_plan_ganna' => 'nullable|string',
                        'land_area_plan_bona' => 'nullable|string',
                        'land_area_plan_arfaasaa' => 'nullable|string',
                        'land_area_plan_idaama' => 'nullable|string',

                        
        'productivity_plan_ganna'=> 'nullable|string',
        'productivity_plan_bona'=> 'nullable|string',
        'productivity_plan_arfaasaa'=> 'nullable|string',
        'productivity_plan_idaama'=> 'nullable|string',

            'productivity_baseline_gana'=> 'nullable|string',
        'productivity_baseline_bona'=> 'nullable|string',
        'productivity_baseline_arfasa'=> 'nullable|string',
        'productivity_baseline_idaama'=> 'nullable|string',
                        'production_plan_ganna' => 'nullable|string',
                        'production_plan_bona' => 'nullable|string',
                        'production_plan_arfaasaa' => 'nullable|string',
                        'production_plan_idaama' => 'nullable|string',
            
                        'land_area_baseline_ganna' => 'nullable|string',
                        'land_area_baseline_bona' => 'nullable|string',
                        'land_area_baseline_arfaasaa' => 'nullable|string',
                        'land_area_baseline_idaama' => 'nullable|string',
            
                        'production_baseline_ganna' => 'nullable|string',
                        'production_baseline_bona' => 'nullable|string',
                        'production_baseline_arfaasaa' => 'nullable|string',
                        'production_baseline_idaama' => 'nullable|string',
                    ]);
            
                    // Add created_by if you want to track who added it
                    $validated['created_by'] = auth()->user()->name ?? 'system';
            
                    // Save the record
                    YearlyCropPlan::create($validated);
            

                return redirect()->route('yearly_crop_plans.index')->with('success', 'Yearly crop plan created successfully.');
            }


            public function edit($id)
            {
            $plan = YearlyCropPlan::findOrFail($id);
            $plans = YearlyCropPlan::pluck('production_year')->unique();
            $cropTypes = CropType::all();
            $crops = Crop::all(); // or load dynamically via JS
            return view('misooma-midhaanii.edit', compact('plan', 'plans', 'cropTypes', 'crops'));
            }

    

            public function update(Request $request, $id)
            {
                $plan = YearlyCropPlan::findOrFail($id);

                $validated = $request->validate([
                    
                    'plan_year' => 'required|string',
                    'production_year' => 'required|string',
                    'croptype_id' => 'nullable|exists:crop_types,id',
                    'crop_id' => 'nullable|exists:crops,id',

                    // Land Area
                    'summury_land_area_plan' => 'nullable|numeric',
                    'spring_land_area_plan' => 'nullable|numeric',
                    'autumn_land_area_plan' => 'nullable|numeric',
                    'total_land_area_plan' => 'nullable|numeric',

                    // Productivity
                    'summury_productivity_plan' => 'nullable|numeric',
                    'spring_productivity_plan' => 'nullable|numeric',
                    'autumn_productivity_plan' => 'nullable|numeric',

                    // Production (will be recalculated)
                    'summury_production_plan' => 'nullable|numeric',
                    'spring_production_plan' => 'nullable|numeric',
                    'autumn_production_plan' => 'nullable|numeric',
                    'total_production_plan' => 'nullable|numeric',
                ]);

                // Optional: Calculate total values server-side as a fallback or check
                $validated['total_land_area_plan'] = 
                    ($validated['summury_land_area_plan'] ?? 0) +
                    ($validated['spring_land_area_plan'] ?? 0) +
                    ($validated['autumn_land_area_plan'] ?? 0);

                $validated['summury_production_plan'] = 
                    ($validated['summury_land_area_plan'] ?? 0) *
                    ($validated['summury_productivity_plan'] ?? 0);

                $validated['spring_production_plan'] = 
                    ($validated['spring_land_area_plan'] ?? 0) *
                    ($validated['spring_productivity_plan'] ?? 0);

                $validated['autumn_production_plan'] = 
                    ($validated['autumn_land_area_plan'] ?? 0) *
                    ($validated['autumn_productivity_plan'] ?? 0);

                $validated['total_production_plan'] =
                    $validated['summury_production_plan'] +
                    $validated['spring_production_plan'] +
                    $validated['autumn_production_plan'];

                $plan->update($validated);

                return redirect()->route('yearly_crop_plans.index')->with('success', 'Yearly crop plan updated successfully.');
            }


            public function destroy($id)
            {
                $plan = YearlyCropPlan::findOrFail($id);
                $plan->delete();
                return redirect()->route('yearly_crop_plans.index')->with('success', 'Yearly crop plan deleted successfully.');
            }


            public function getByType($id)
            {
                $crops =  Crop::where('croptype_id', $id) // Changed from croptype_id
                ->select('id', 'name')
                    ->get();
                return response()->json($crops);
            }


 
            public function getPlanYear($productionYear)
            {
                // Fetch the plan where production_year matches the selected production year
                $plan = YearlyCropPlan::where('production_year', $productionYear)->first(); // Adjust based on your table structure

                if ($plan) {
                    return response()->json(['plan_year' => $plan->plan_year]);
                }

                return response()->json(['plan_year' => null], 404); // If not found, return null
            }
}
