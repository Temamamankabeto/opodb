<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\CropType;
use App\Models\Crop;
use App\Models\SoilType;
use App\Models\LandType;
use App\Models\CropConfiguration;
use App\Models\ParcelCropPlan;
use Illuminate\Http\Request;

class ParcelCropPlanController extends Controller
{


    public function index($farmer_id)
    {
        $farmer = Farmer::findOrFail($farmer_id);

        $plans = ParcelCropPlan::with(['cropType', 'crop', 'farmer'])
            ->where('farmer_id', $farmer_id)
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate statistics
        $totalPlans = $plans->total();
        $totalArea = $plans->sum('area');
        $totalExpectedYield = $plans->sum('expected_yield');
        $uniqueCrops = $plans->unique('crop_id')->count();

        return view('parcel_crop_plans.index', compact(
            'farmer',
            'plans',
            'totalPlans',
            'totalArea',
            'totalExpectedYield',
            'uniqueCrops'
        ));
    }
    public function create($farmer_id)
    {
        $farmer = Farmer::findOrFail($farmer_id);

        // Get distinct years from crop configurations
        $years = CropConfiguration::distinct()->pluck('year')->sortDesc();

        $cropTypes = collect();
        $crops = collect();

        $landTypeDetails = json_decode($farmer->land_type_details, true) ?? [];
        $parcels = [];

        foreach ($landTypeDetails as $type) {
            foreach ($type['parcels'] ?? [] as $index => $parcel) {
                $parcelId = $type['land_type_id'] . '_' . $index;
                $soilTypeId = $parcel['soil_type'] ?? null;

                $soilTypeName = $soilTypeId
                    ? SoilType::find($soilTypeId)?->name ?? '—'
                    : '—';

                $area = $parcel['size'] ?? 0;

                $parcels[] = [
                    'id' => $parcelId,
                    'land_type' => LandType::find($type['land_type_id'])->name ?? '—',
                    'soil_type_id' => $soilTypeId,
                    'soil_type' => $soilTypeName,
                    'gps' => [$parcel['gps_x'] ?? null, $parcel['gps_y'] ?? null],
                    'area' => $area,
                    'kebele_id' => $farmer->kebele_id,
                ];
            }
        }

        return view('parcel_crop_plans.create', compact('farmer', 'years', 'cropTypes', 'crops', 'parcels'));
    }

    public function store(Request $request, $farmer_id)
    {
        $request->validate([
            'parcel_id' => 'required',
            'year' => 'required|digits:4',
            'croptype_id' => 'required|exists:crop_types,id',
            'crop_id' => 'required|exists:crops,id',
            'season' => 'required|string',
            'area' => 'required|numeric|min:0.1',
            'productivity_per_ha' => 'required|numeric|min:0',
            'seed_rate' => 'required|numeric|min:0',
        ]);

        $farmer = Farmer::findOrFail($farmer_id);

        // Check for duplicate entry for the same farmer and kebele
        $existingPlan = ParcelCropPlan::where('farmer_id', $farmer_id)
            ->where('kebele_id', $farmer->kebele_id)
            ->where('year', $request->year)
            ->where('croptype_id', $request->croptype_id)
            ->where('crop_id', $request->crop_id)
            ->where('season', $request->season)
            ->first();

        if ($existingPlan) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'duplicate' => 'A crop plan already exists for this farmer in this kebele for the same year, crop type, crop, and season combination. Please edit the existing plan instead.'
                ]);
        }

        $expectedYield = $request->area * $request->productivity_per_ha;

        $fertilizerRecommendations = [];
        if ($request->has('fertilizer_data')) {
            $fertilizerData = json_decode($request->fertilizer_data, true);
            foreach ($fertilizerData as $fert) {
                $fertilizerRecommendations[] = [
                    'fertilizer' => $fert['fertilizer'],
                    'rate_kg_per_ha' => (float) $fert['rate_kg_per_ha'],
                    'required_kg' => (float) $fert['required_kg'],
                ];
            }
        }

        ParcelCropPlan::create([
            'farmer_id' => $farmer_id,
            'region_id' => $farmer->region_id,
            'zone_id' => $farmer->zone_id,
            'woreda_id' => $farmer->woreda_id,
            'kebele_id' => $farmer->kebele_id,
            'assocition_id' => $farmer->assocition_id,
            'union_id' => $farmer->union_id,
            'federation_id' => $farmer->federation_id,
            'office_id' => $farmer->office_id,
            'parcel_id' => $request->parcel_id,
            'year' => $request->year,
            'croptype_id' => $request->croptype_id,
            'crop_id' => $request->crop_id,
            'season' => $request->season,
            'area' => $request->area,
            'productivity_per_ha' => $request->productivity_per_ha,
            'expected_yield' => $expectedYield,
            'seed_rate' => $request->seed_rate,
            'fertilizer_recommendations' => $fertilizerRecommendations,
        ]);

        return redirect()->route('parcel-crop-plans.index', $farmer_id)->with('success', 'Parcel crop plan added successfully.');
    }
    // Get crop types available for a specific year
    public function getCropTypesByYear($year)
    {
        $cropTypes = CropType::whereHas('cropConfigurations', function ($query) use ($year) {
            $query->where('year', $year);
        })->get();

        return response()->json($cropTypes);
    }

    // Get crops available for a specific crop type and year
    public function getCropsByTypeAndYear($croptype_id, $year)
    {
        $crops = Crop::where('croptype_id', $croptype_id)
            ->whereHas('cropConfigurations', function ($query) use ($year) {
                $query->where('year', $year);
            })->get();

        return response()->json($crops);
    }

    // Get crop configuration details (productivity, seed rate, fertilizers)
    public function getConfiguration(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'croptype_id'   => 'required|integer',
            'crop_id'       => 'required|integer',
            'year'          => 'required|digits:4',
            'soil_type_id'  => 'nullable|integer',
            'kebele_id'     => 'nullable|integer',
            'area'          => 'required|numeric|min:0.1',
        ]);

        $config = null;

        // Try multiple matching strategies
        // Strategy 1: Exact match with all criteria
        if ($validated['soil_type_id'] && $validated['kebele_id']) {
            $config = CropConfiguration::where('croptype_id', $validated['croptype_id'])
                ->where('crop_id', $validated['crop_id'])
                ->where('soil_type_id', $validated['soil_type_id'])
                ->where('kebele_id', $validated['kebele_id'])
                ->where('year', $validated['year'])
                ->first();
        }

        // Strategy 2: Match without kebele
        if (!$config && $validated['soil_type_id']) {
            $config = CropConfiguration::where('croptype_id', $validated['croptype_id'])
                ->where('crop_id', $validated['crop_id'])
                ->where('soil_type_id', $validated['soil_type_id'])
                ->where('year', $validated['year'])
                ->first();
        }

        // Strategy 3: Match without soil type
        if (!$config && $validated['kebele_id']) {
            $config = CropConfiguration::where('croptype_id', $validated['croptype_id'])
                ->where('crop_id', $validated['crop_id'])
                ->where('kebele_id', $validated['kebele_id'])
                ->where('year', $validated['year'])
                ->first();
        }

        // Strategy 4: Match just crop and year
        if (!$config) {
            $config = CropConfiguration::where('croptype_id', $validated['croptype_id'])
                ->where('crop_id', $validated['crop_id'])
                ->where('year', $validated['year'])
                ->first();
        }

        // Strategy 5: Match crop only (any year)
        if (!$config) {
            $config = CropConfiguration::where('croptype_id', $validated['croptype_id'])
                ->where('crop_id', $validated['crop_id'])
                ->orderBy('year', 'desc')
                ->first();
        }

        if (!$config) {
            return response()->json([
                'success' => false,
                'message' => 'No crop configuration found for the selected criteria.'
            ]);
        }

        // Process fertilizer data
        $fertilizerData = $config->fertilizer_requirements ?? [];
        if (is_string($fertilizerData)) {
            $fertilizerData = json_decode($fertilizerData, true) ?? [];
        }

        $fertilizers = [];
        foreach ($fertilizerData as $f) {
            // Handle different JSON structures
            $fertilizerName = $f['fertilizer_name'] ?? $f['name'] ?? $f['fertilizer'] ?? 'Unknown Fertilizer';
            $rate = $f['rate_kg_per_ha'] ?? $f['rate'] ?? $f['recommended_rate'] ?? 0;

            if (!empty($fertilizerName) && $rate > 0) {
                $fertilizers[] = [
                    'fertilizer' => $fertilizerName,
                    'rate_kg_per_ha' => (float) $rate,
                    'required_kg' => round($rate * $validated['area'], 2),
                ];
            }
        }

        // Calculate expected yield
        $expected_yield = round(($config->productivity_per_ha ?? 0) * $validated['area'], 2);

        return response()->json([
            'success' => true,
            'productivity_per_ha' => $config->productivity_per_ha,
            'seed_rate_kg_per_ha' => $config->seed_rate_kg_per_ha,
            'expected_yield' => $expected_yield,
            'fertilizers' => $fertilizers,
        ]);
    }


    public function update(Request $request, $id)
    {
        $plan = ParcelCropPlan::findOrFail($id);

        $request->validate([
            'parcel_id' => 'required',
            'year' => 'required|digits:4',
            'croptype_id' => 'required|exists:crop_types,id',
            'crop_id' => 'required|exists:crops,id',
            'season' => 'required|string',
            'area' => 'required|numeric|min:0.1',
            'productivity_per_ha' => 'required|numeric|min:0',
            'seed_rate' => 'required|numeric|min:0',
        ]);

        // Check for duplicate entry (excluding current record)
        $existingPlan = ParcelCropPlan::where('farmer_id', $plan->farmer_id)
            ->where('year', $request->year)
            ->where('croptype_id', $request->croptype_id)
            ->where('crop_id', $request->crop_id)
            ->where('season', $request->season)
            ->where('id', '!=', $id) // Exclude current record
            ->first();

        if ($existingPlan) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'duplicate' => 'Another crop plan already exists for this year, crop type, crop, and season combination.'
                ]);
        }

        $expectedYield = $request->area * $request->productivity_per_ha;

        $fertilizerRecommendations = [];
        if ($request->has('fertilizer_data')) {
            $fertilizerData = json_decode($request->fertilizer_data, true);
            foreach ($fertilizerData as $fert) {
                $fertilizerRecommendations[] = [
                    'fertilizer' => $fert['fertilizer'],
                    'rate_kg_per_ha' => (float) $fert['rate_kg_per_ha'],
                    'required_kg' => (float) $fert['required_kg'],
                ];
            }
        }

        $plan->update([
            'farmer_id' => $plan->farmer_id,
            'region_id' => $plan->region_id,
            'zone_id' => $plan->zone_id,
            'woreda_id' => $plan->woreda_id,
            'kebele_id' => $plan->kebele_id,
            'assocition_id' => $plan->assocition_id,
            'union_id' => $plan->union_id,
            'federation_id' => $plan->federation_id,
            'office_id' => $plan->office_id,
            'parcel_id' => $request->parcel_id,
            'year' => $request->year,
            'croptype_id' => $request->croptype_id,
            'crop_id' => $request->crop_id,
            'season' => $request->season,
            'area' => $request->area,
            'productivity_per_ha' => $request->productivity_per_ha,
            'expected_yield' => $expectedYield,
            'seed_rate' => $request->seed_rate,
            'fertilizer_recommendations' => $fertilizerRecommendations,
        ]);

        return redirect()->route('parcel-crop-plans.index', $plan->farmer_id)->with('success', 'Crop plan updated successfully.');
    }
}
