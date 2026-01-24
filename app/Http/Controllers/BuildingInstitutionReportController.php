<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuildingInstitutionPlan;

class BuildingInstitutionReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from building institution plans for this kebele
        $availableYears = BuildingInstitutionPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch plans for current and previous year for this kebele only
        $plans = BuildingInstitutionPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->with('institution')
                        ->get();

        $reportData = [];
        
        // Initialize arrays for building data
        $newBuildingData = [
            'current' => [],
            'previous' => []
        ];
        
        $improvementData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = [
            'new_buildings' => ['count' => 0, 'cost' => 0, 'institutions' => 0],
            'improvements' => ['count' => 0, 'cost' => 0, 'institutions' => 0],
            'total_institutions' => 0
        ];

        $previous = [
            'new_buildings' => ['count' => 0, 'cost' => 0, 'institutions' => 0],
            'improvements' => ['count' => 0, 'cost' => 0, 'institutions' => 0],
            'total_institutions' => 0
        ];

        // Track unique institutions
        $institutionNames = [];

        foreach ($plans as $plan) {
            // Safe access to arrays
            $newBuildings = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data ?? '[]', true);
            $improvements = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data ?? '[]', true);

            $yearlyNewCount = 0;
            $yearlyNewCost = 0;
            $yearlyImproveCount = 0;
            $yearlyImproveCost = 0;

            // Get institution name
            $institutionName = $plan->institution->name ?? 'Unknown Institution';
            if (!in_array($institutionName, $institutionNames)) {
                $institutionNames[] = $institutionName;
            }

            // Process new building data
            foreach ($newBuildings as $building) {
                $buildingName = $building['name'] ?? 'Unknown Building';
                $buildingCount = intval($building['number'] ?? 0);
                $buildingCost = floatval($building['cost'] ?? 0);
                $yearlyNewCount += $buildingCount;
                $yearlyNewCost += $buildingCost;
                
                // Store new building data by institution
                $key = $institutionName . ' - ' . $buildingName;
                if ($plan->year == $selectedYear) {
                    if (!isset($newBuildingData['current'][$key])) {
                        $newBuildingData['current'][$key] = [
                            'count' => 0,
                            'cost' => 0
                        ];
                    }
                    $newBuildingData['current'][$key]['count'] += $buildingCount;
                    $newBuildingData['current'][$key]['cost'] += $buildingCost;
                } else {
                    if (!isset($newBuildingData['previous'][$key])) {
                        $newBuildingData['previous'][$key] = [
                            'count' => 0,
                            'cost' => 0
                        ];
                    }
                    $newBuildingData['previous'][$key]['count'] += $buildingCount;
                    $newBuildingData['previous'][$key]['cost'] += $buildingCost;
                }
            }

            // Process improvement data
            foreach ($improvements as $improvement) {
                $improvementName = $improvement['name'] ?? 'Unknown Improvement';
                $improvementCount = intval($improvement['number'] ?? 0);
                $improvementCost = floatval($improvement['cost'] ?? 0);
                $yearlyImproveCount += $improvementCount;
                $yearlyImproveCost += $improvementCost;
                
                // Store improvement data by institution
                $key = $institutionName . ' - ' . $improvementName;
                if ($plan->year == $selectedYear) {
                    if (!isset($improvementData['current'][$key])) {
                        $improvementData['current'][$key] = [
                            'count' => 0,
                            'cost' => 0
                        ];
                    }
                    $improvementData['current'][$key]['count'] += $improvementCount;
                    $improvementData['current'][$key]['cost'] += $improvementCost;
                } else {
                    if (!isset($improvementData['previous'][$key])) {
                        $improvementData['previous'][$key] = [
                            'count' => 0,
                            'cost' => 0
                        ];
                    }
                    $improvementData['previous'][$key]['count'] += $improvementCount;
                    $improvementData['previous'][$key]['cost'] += $improvementCost;
                }
            }

            if ($plan->year == $selectedYear) {
                $current['new_buildings']['count'] += $yearlyNewCount;
                $current['new_buildings']['cost'] += $yearlyNewCost;
                if ($yearlyNewCount > 0) $current['new_buildings']['institutions']++;
                
                $current['improvements']['count'] += $yearlyImproveCount;
                $current['improvements']['cost'] += $yearlyImproveCost;
                if ($yearlyImproveCount > 0) $current['improvements']['institutions']++;
                
                $current['total_institutions'] = count($institutionNames);
            } else {
                $previous['new_buildings']['count'] += $yearlyNewCount;
                $previous['new_buildings']['cost'] += $yearlyNewCost;
                if ($yearlyNewCount > 0) $previous['new_buildings']['institutions']++;
                
                $previous['improvements']['count'] += $yearlyImproveCount;
                $previous['improvements']['cost'] += $yearlyImproveCost;
                if ($yearlyImproveCount > 0) $previous['improvements']['institutions']++;
                
                $previous['total_institutions'] = count($institutionNames);
            }
        }

        // Sort building data by cost (descending)
        uasort($newBuildingData['current'], function($a, $b) {
            return $b['cost'] - $a['cost'];
        });

        uasort($improvementData['current'], function($a, $b) {
            return $b['cost'] - $a['cost'];
        });

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $newBuildingsChange = $previous['new_buildings']['count'] > 0 ? 
            (($current['new_buildings']['count'] - $previous['new_buildings']['count']) / $previous['new_buildings']['count']) * 100 : 0;
        
        $newBuildingsCostChange = $previous['new_buildings']['cost'] > 0 ? 
            (($current['new_buildings']['cost'] - $previous['new_buildings']['cost']) / $previous['new_buildings']['cost']) * 100 : 0;
        
        $improvementsChange = $previous['improvements']['count'] > 0 ? 
            (($current['improvements']['count'] - $previous['improvements']['count']) / $previous['improvements']['count']) * 100 : 0;
        
        $improvementsCostChange = $previous['improvements']['cost'] > 0 ? 
            (($current['improvements']['cost'] - $previous['improvements']['cost']) / $previous['improvements']['cost']) * 100 : 0;

        return view('gabaasa_torbanii.building_institution.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'newBuildingData',
            'improvementData',
            'newBuildingsChange',
            'newBuildingsCostChange',
            'improvementsChange',
            'improvementsCostChange'
        ));
    }
}
