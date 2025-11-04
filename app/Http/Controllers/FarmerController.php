<?php

namespace App\Http\Controllers;

use App\Models\SoilType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Farmer, Zone, Woreda, Kebele, Union, Association, LandType};

class FarmerController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // get currently logged-in user ID

        $farmers = Farmer::with(['zone', 'woreda', 'kebele', 'union', 'association'])
            ->where('created_by', $userId) // filter by creator
            ->latest()
            ->paginate(10);

        return view('farmers.index', compact('farmers'));
    }


    public function create()
    {
        $zones = Zone::all();
        $landTypes = LandType::all();
        $soilTypes = SoilType::all();
        return view('farmers.create', compact('zones', 'landTypes', 'soilTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'farmer_name' => 'required|string|max:255',
            'gender' => 'required',
            'zone_id' => 'required',
            'woreda_id' => 'required',
            'kebele_id' => 'required',
            'union_id' => 'required',
            'association_id' => 'required',
           
        ]);

        $landDetails = [];

        if ($request->land_type_id) {
            foreach ($request->land_type_id as $i => $landTypeId) {
                $landDetails[] = [
                    'land_type_id' => $landTypeId,
                    'size' => floatval($request->landholding_size[$i] ?? 0),
                    'gps_x' => $request->gps_x[$i] ?? null,
                    'gps_y' => $request->gps_y[$i] ?? null,
                    'soil_type' => $request->soil_type[$i] ?? null,
                    'irrigation_type' => $request->irrigation_type[$i] ?? null,
                ];
            }
        }

        // === Calculate total per land type ===
        $totals = [];
        foreach ($landDetails as $land) {
            $typeId = $land['land_type_id'];
            $totals[$typeId] = ($totals[$typeId] ?? 0) + floatval($land['size']);
        }
        $overallTotal = array_sum($totals);

        Farmer::create($request->except([
            'land_type_id',
            'create_by',
            'landholding_size',
            'gps_x',
            'gps_y',
            'soil_type',
            'irrigation_type'
        ]) + [
            'land_details' => json_encode($landDetails),
            'total_land' => json_encode([
                'by_type' => $totals,
                'overall' => $overallTotal,
            ]),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('farmers.index')->with('success', 'Farmer added successfully.');
    }

    public function edit($id)
    {
        $farmer = Farmer::findOrFail($id);
        $zones = Zone::all();
        $woredas = Woreda::where('zone_id', $farmer->zone_id)->get();
        $kebeles = Kebele::where('woreda_id', $farmer->woreda_id)->get();
        $unions = Union::where('zone_id', $farmer->zone_id)->get();
        $associations = Association::where('union_id', $farmer->union_id)->get();
        $landTypes = LandType::all();
        $soilTypes = SoilType::all(); // ✅ Add this line

        return view('farmers.edit', compact(
            'farmer',
            'zones',
            'woredas',
            'kebeles',
            'unions',
            'associations',
            'landTypes',
            'soilTypes'
        ));
    }
    public function update(Request $request, $id)
    {
        $farmer = Farmer::findOrFail($id);

        $request->validate([
            'farmer_name' => 'nullable|string|max:255',
            'gender' => 'nullable',
            'zone_id' => 'nullable',
            'woreda_id' => 'nullable',
            'kebele_id' => 'nullable',
            'union_id' => 'nullable',
            'association_id' => 'nullable',
        ]);

        $existingLandDetails = json_decode($farmer->land_details, true) ?? [];

        $landTypeMap = []; // map land_type_id => index in array
        foreach ($existingLandDetails as $index => $land) {
            $landTypeMap[$land['land_type_id']] = $index;
        }

        // Process submitted lands
        if ($request->land_type_id && is_array($request->land_type_id)) {
            foreach ($request->land_type_id as $i => $landTypeId) {
                $parcelData = [
                    'land_type_id' => $landTypeId,
                    'size' => floatval($request->landholding_size[$i] ?? 0),
                    'gps_x' => $request->gps_x[$i] ?? null,
                    'gps_y' => $request->gps_y[$i] ?? null,
                    'soil_type' => $request->soil_type[$i] ?? null,
                    'irrigation_type' => $request->irrigation_type[$i] ?? null,
                ];

                if (isset($landTypeMap[$landTypeId])) {
                    // Update existing land
                    $existingLandDetails[$landTypeMap[$landTypeId]] = $parcelData;
                } else {
                    // Add new land
                    $existingLandDetails[] = $parcelData;
                }
            }
        }

        // Recalculate totals
        $totals = [];
        foreach ($existingLandDetails as $land) {
            $typeId = $land['land_type_id'];
            $totals[$typeId] = ($totals[$typeId] ?? 0) + floatval($land['size']);
        }
        $overallTotal = array_sum($totals);

        // Rebuild land_type_details grouped by land_type_id
        $landTypeDetails = [];
        foreach ($existingLandDetails as $land) {
            $typeId = $land['land_type_id'];
            if (!isset($landTypeDetails[$typeId])) {
                $landTypeDetails[$typeId] = [
                    'land_type_id' => $typeId,
                    'total_size' => $totals[$typeId],
                    'parcels' => []
                ];
            }
            $landTypeDetails[$typeId]['parcels'][] = [
                'size' => floatval($land['size']),
                'gps_x' => $land['gps_x'] ?? null,
                'gps_y' => $land['gps_y'] ?? null,
                'soil_type' => $land['soil_type'] ?? null,
                'irrigation_type' => $land['irrigation_type'] ?? null,
            ];
        }

        $landTypeDetails = array_values($landTypeDetails);

        $farmer->update($request->except([
            'land_type_id',
            'landholding_size',
            'gps_x',
            'gps_y',
            'soil_type',
            'irrigation_type',
        ]) + [
            'land_details' => json_encode($existingLandDetails),
            'land_type_details' => json_encode($landTypeDetails),
            'total_land' => json_encode([
                'by_type' => $totals,
                'overall' => $overallTotal,
            ]),
        ]);

        return redirect()->route('farmers.index')->with('success', 'Farmer updated successfully.');
    }



    public function updateLandTypeDetails(Request $request, $id)
    {
        $farmer = Farmer::findOrFail($id);

        $request->validate([
            'land_type_id' => 'required|integer|exists:land_types,id',
            'landholding_size' => 'required|array',
            'gps_x' => 'required|array',
            'gps_y' => 'required|array',
            'soil_type' => 'required|array',
        ]);

        $landTypeId = $request->land_type_id;

        // Decode existing land type details JSON
        $existingDetails = json_decode($farmer->land_type_details, true) ?? [];

        // Try to find an existing record for this land type
        $foundIndex = collect($existingDetails)->search(fn($d) => $d['land_type_id'] == $landTypeId);

        // Build new parcels array
        $parcels = [];
        $totalSize = 0;

        foreach ($request->landholding_size as $i => $size) {
            $soilTypeId = $request->soil_type[$i] ?? null;

            $parcels[] = [
                'size' => floatval($size),
                'gps_x' => $request->gps_x[$i] ?? null,
                'gps_y' => $request->gps_y[$i] ?? null,
                'soil_type' => $soilTypeId, // ✅ store soil_type_id instead of text
            ];

            $totalSize += floatval($size);
        }

        // Update or add this land type’s parcels
        if ($foundIndex !== false) {
            $existingDetails[$foundIndex]['parcels'] = $parcels;
            $existingDetails[$foundIndex]['total_size'] = $totalSize;
        } else {
            $existingDetails[] = [
                'land_type_id' => $landTypeId,
                'parcels' => $parcels,
                'total_size' => $totalSize,
            ];
        }

        // Save updated JSON to the farmer record
        $farmer->update([
            'land_type_details' => json_encode($existingDetails, JSON_PRETTY_PRINT),
        ]);

        return redirect()
            ->route('farmers.show', $farmer->id)
            ->with('success', 'Land type details updated successfully with soil type.');
    }








    public function show($id)
    {
        $farmer =Farmer::with(['zone', 'woreda', 'kebele', 'union', 'association'])->findOrFail($id);

        // Preload all land types and soil types once
        $landTypes = LandType::pluck('name', 'id')->toArray();
        $soilTypes = SoilType::pluck('name', 'id')->toArray();

        return view('farmers.show', compact('farmer', 'landTypes', 'soilTypes'));
    }


    public function destroy($id)
    {
        $farmer = Farmer::findOrFail($id);
        $farmer->delete();
        return redirect()->route('farmers.index')->with('success', 'Farmer deleted successfully.');
    }

    // === Dependent dropdowns ===
    public function getWoredas($zone_id)
    {
        return response()->json(Woreda::where('zone_id', $zone_id)->get());
    }

    public function getKebeles($woreda_id)
    {
        return response()->json(Kebele::where('woreda_id', $woreda_id)->get());
    }

    public function getUnions($zone_id)
    {
        return response()->json(Union::where('zone_id', $zone_id)->get());
    }

    public function getAssociations($union_id)
    {
        return response()->json(Association::where('union_id', $union_id)->get());
    }

    public function editLand($id)
    {
        $farmer = Farmer::findOrFail($id);
        $landDetails = json_decode($farmer->land_details, true);
        $landTypeDetails = json_decode($farmer->land_type_details, true) ?? [];

        $landTypes = collect($landDetails)->map(function ($ld) {
            $type = \App\Models\LandType::find($ld['land_type_id']);
            $ld['name'] = $type->name ?? '—';

            if (!isset($ld['total_size'])) {
                $ld['total_size'] = isset($ld['parcels'])
                    ? array_sum(array_column($ld['parcels'], 'size'))
                    : floatval($ld['size'] ?? 0);
            }

            $ld['parcels'] = $ld['parcels'] ?? [];
            return $ld;
        });

        $soilTypes = SoilType::select('id', 'name')->get(); // ✅ dynamic soil types

        return view('farmers.land-edit', compact('farmer', 'landTypes', 'landTypeDetails', 'soilTypes'));
    }



    public function updateLandType(Request $request, $id)
    {
        $request->validate([
            'land_type_id' => 'required|integer',
            'parcel_size.*' => 'required|numeric|min:0',
            'parcel_gps_x.*' => 'required|string',
            'parcel_gps_y.*' => 'required|string',
            'parcel_soil_type.*' => 'required|string',
            'parcel_irrigation_type.*' => 'nullable|string',
        ]);

        $farmer = Farmer::findOrFail($id);
        $landTypeId = $request->land_type_id;

        // Decode existing land type details
        $landDetails = json_decode($farmer->land_type_details, true) ?? [];

        // Find the land type to update
        $found = false;
        foreach ($landDetails as &$landType) {
            if ($landType['land_type_id'] == $landTypeId) {
                $parcels = [];
                $totalSize = 0;

                foreach ($request->parcel_size as $index => $size) {
                    $parcels[] = [
                        'size' => floatval($size),
                        'gps_x' => $request->parcel_gps_x[$index] ?? null,
                        'gps_y' => $request->parcel_gps_y[$index] ?? null,
                        'soil_type' => $request->parcel_soil_type[$index] ?? null,
                        'irrigation_type' => $request->parcel_irrigation_type[$index] ?? null,
                    ];
                    $totalSize += floatval($size);
                }

                // Update existing land type
                $landType['parcels'] = $parcels;
                $landType['total_size'] = $totalSize;

                $found = true;
                break;
            }
        }

        if (!$found) {
            // Add new land type if not exist
            $parcels = [];
            $totalSize = 0;

            foreach ($request->parcel_size as $index => $size) {
                $parcels[] = [
                    'size' => floatval($size),
                    'gps_x' => $request->parcel_gps_x[$index] ?? null,
                    'gps_y' => $request->parcel_gps_y[$index] ?? null,
                    'soil_type' => $request->parcel_soil_type[$index] ?? null,
                    'irrigation_type' => $request->parcel_irrigation_type[$index] ?? null,
                ];
                $totalSize += floatval($size);
            }

            $landDetails[] = [
                'land_type_id' => $landTypeId,
                'parcels' => $parcels,
                'total_size' => $totalSize,
            ];
        }

        // Save back to DB
        $farmer->update(['land_type_details' => json_encode($landDetails)]);

        return back()->with('success', 'Land type parcels updated successfully.');
    }
}



