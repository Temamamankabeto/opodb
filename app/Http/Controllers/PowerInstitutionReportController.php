<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PowerInstitutionPlan;

class PowerInstitutionReportController extends Controller
{
   public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from power institution plans for this kebele
        $availableYears = PowerInstitutionPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch plans for current and previous year for this kebele only
        $plans = PowerInstitutionPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->with('institution')
                        ->get();

        $reportData = [];
        
        // Initialize arrays for power data
        $newPowerData = [
            'current' => [],
            'previous' => []
        ];
        
        $improvementPowerData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = [
            'new_power' => [
                'public_count' => 0,
                'private_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'improvements' => [
                'public_count' => 0,
                'private_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'total_institutions' => 0
        ];

        $previous = [
            'new_power' => [
                'public_count' => 0,
                'private_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'improvements' => [
                'public_count' => 0,
                'private_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'total_institutions' => 0
        ];

        // Track unique institutions and project types
        $institutionNames = [];
        $projectTypes = [];

        foreach ($plans as $plan) {
            // Debug: Check raw data
            // \Log::info('Plan ID: ' . $plan->id . ', Year: ' . $plan->year);
            // \Log::info('New Building Data: ', is_array($plan->new_building_data) ? $plan->new_building_data : []);
            // \Log::info('Improvement Data: ', is_array($plan->improvement_data) ? $plan->improvement_data : []);

            // Safe access to arrays
            $newPowerItems = is_array($plan->new_building_data) ? $plan->new_building_data : json_decode($plan->new_building_data ?? '[]', true);
            $improvementItems = is_array($plan->improvement_data) ? $plan->improvement_data : json_decode($plan->improvement_data ?? '[]', true);

            $yearlyNewPublic = 0;
            $yearlyNewPrivate = 0;
            $yearlyNewPublicCost = 0;
            $yearlyNewPrivateCost = 0;
            $yearlyImprovePublic = 0;
            $yearlyImprovePrivate = 0;
            $yearlyImprovePublicCost = 0;
            $yearlyImprovePrivateCost = 0;

            // Get institution name
            $institutionName = $plan->institution->name ?? 'Unknown Institution';
            if (!in_array($institutionName, $institutionNames)) {
                $institutionNames[] = $institutionName;
            }

            // Process new power data
            foreach ($newPowerItems as $item) {
                // Debug: Check item structure
                // \Log::info('New Power Item: ', $item);
                
                $projectName = $item['name'] ?? 'Unknown Project';
                $publicNumber = isset($item['public_number']) ? intval($item['public_number']) : 0;
                $privateNumber = isset($item['private_number']) ? intval($item['private_number']) : 0;
                $publicCost = isset($item['public_cost']) ? floatval($item['public_cost']) : 0;
                $privateCost = isset($item['private_cost']) ? floatval($item['private_cost']) : 0;
                
                // Debug: Log extracted values
                // \Log::info("Extracted - Public: $publicNumber, Private: $privateNumber, Public Cost: $publicCost, Private Cost: $privateCost");
                
                $yearlyNewPublic += $publicNumber;
                $yearlyNewPrivate += $privateNumber;
                $yearlyNewPublicCost += $publicCost;
                $yearlyNewPrivateCost += $privateCost;
                
                // Store project type
                if (!in_array($projectName, $projectTypes)) {
                    $projectTypes[] = $projectName;
                }
                
                // Store new power data by project type
                $key = $projectName . ' (' . $institutionName . ')';
                if ($plan->year == $selectedYear) {
                    if (!isset($newPowerData['current'][$key])) {
                        $newPowerData['current'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0
                        ];
                    }
                    $newPowerData['current'][$key]['public_count'] += $publicNumber;
                    $newPowerData['current'][$key]['private_count'] += $privateNumber;
                    $newPowerData['current'][$key]['public_cost'] += $publicCost;
                    $newPowerData['current'][$key]['private_cost'] += $privateCost;
                } else {
                    if (!isset($newPowerData['previous'][$key])) {
                        $newPowerData['previous'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0
                        ];
                    }
                    $newPowerData['previous'][$key]['public_count'] += $publicNumber;
                    $newPowerData['previous'][$key]['private_count'] += $privateNumber;
                    $newPowerData['previous'][$key]['public_cost'] += $publicCost;
                    $newPowerData['previous'][$key]['private_cost'] += $privateCost;
                }
            }

            // Process improvement data
            foreach ($improvementItems as $item) {
                // Debug: Check item structure
                // \Log::info('Improvement Item: ', $item);
                
                $projectName = $item['name'] ?? 'Unknown Project';
                $publicNumber = isset($item['public_number']) ? intval($item['public_number']) : 0;
                $privateNumber = isset($item['private_number']) ? intval($item['private_number']) : 0;
                $publicCost = isset($item['public_cost']) ? floatval($item['public_cost']) : 0;
                $privateCost = isset($item['private_cost']) ? floatval($item['private_cost']) : 0;
                
                // Debug: Log extracted values
                // \Log::info("Extracted - Public: $publicNumber, Private: $privateNumber, Public Cost: $publicCost, Private Cost: $privateCost");
                
                $yearlyImprovePublic += $publicNumber;
                $yearlyImprovePrivate += $privateNumber;
                $yearlyImprovePublicCost += $publicCost;
                $yearlyImprovePrivateCost += $privateCost;
                
                // Store project type
                if (!in_array($projectName, $projectTypes)) {
                    $projectTypes[] = $projectName;
                }
                
                // Store improvement data by project type
                $key = $projectName . ' (' . $institutionName . ')';
                if ($plan->year == $selectedYear) {
                    if (!isset($improvementPowerData['current'][$key])) {
                        $improvementPowerData['current'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0
                        ];
                    }
                    $improvementPowerData['current'][$key]['public_count'] += $publicNumber;
                    $improvementPowerData['current'][$key]['private_count'] += $privateNumber;
                    $improvementPowerData['current'][$key]['public_cost'] += $publicCost;
                    $improvementPowerData['current'][$key]['private_cost'] += $privateCost;
                } else {
                    if (!isset($improvementPowerData['previous'][$key])) {
                        $improvementPowerData['previous'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0
                        ];
                    }
                    $improvementPowerData['previous'][$key]['public_count'] += $publicNumber;
                    $improvementPowerData['previous'][$key]['private_count'] += $privateNumber;
                    $improvementPowerData['previous'][$key]['public_cost'] += $publicCost;
                    $improvementPowerData['previous'][$key]['private_cost'] += $privateCost;
                }
            }

            if ($plan->year == $selectedYear) {
                // New power totals
                $current['new_power']['public_count'] += $yearlyNewPublic;
                $current['new_power']['private_count'] += $yearlyNewPrivate;
                $current['new_power']['public_cost'] += $yearlyNewPublicCost;
                $current['new_power']['private_cost'] += $yearlyNewPrivateCost;
                $current['new_power']['total_count'] = $current['new_power']['public_count'] + $current['new_power']['private_count'];
                $current['new_power']['total_cost'] = $current['new_power']['public_cost'] + $current['new_power']['private_cost'];
                if (count($newPowerItems) > 0) $current['new_power']['institutions']++;
                $current['new_power']['projects'] += count($newPowerItems);
                
                // Improvement totals
                $current['improvements']['public_count'] += $yearlyImprovePublic;
                $current['improvements']['private_count'] += $yearlyImprovePrivate;
                $current['improvements']['public_cost'] += $yearlyImprovePublicCost;
                $current['improvements']['private_cost'] += $yearlyImprovePrivateCost;
                $current['improvements']['total_count'] = $current['improvements']['public_count'] + $current['improvements']['private_count'];
                $current['improvements']['total_cost'] = $current['improvements']['public_cost'] + $current['improvements']['private_cost'];
                if (count($improvementItems) > 0) $current['improvements']['institutions']++;
                $current['improvements']['projects'] += count($improvementItems);
                
                $current['total_institutions'] = count($institutionNames);
            } else {
                // New power totals
                $previous['new_power']['public_count'] += $yearlyNewPublic;
                $previous['new_power']['private_count'] += $yearlyNewPrivate;
                $previous['new_power']['public_cost'] += $yearlyNewPublicCost;
                $previous['new_power']['private_cost'] += $yearlyNewPrivateCost;
                $previous['new_power']['total_count'] = $previous['new_power']['public_count'] + $previous['new_power']['private_count'];
                $previous['new_power']['total_cost'] = $previous['new_power']['public_cost'] + $previous['new_power']['private_cost'];
                if (count($newPowerItems) > 0) $previous['new_power']['institutions']++;
                $previous['new_power']['projects'] += count($newPowerItems);
                
                // Improvement totals
                $previous['improvements']['public_count'] += $yearlyImprovePublic;
                $previous['improvements']['private_count'] += $yearlyImprovePrivate;
                $previous['improvements']['public_cost'] += $yearlyImprovePublicCost;
                $previous['improvements']['private_cost'] += $yearlyImprovePrivateCost;
                $previous['improvements']['total_count'] = $previous['improvements']['public_count'] + $previous['improvements']['private_count'];
                $previous['improvements']['total_cost'] = $previous['improvements']['public_cost'] + $previous['improvements']['private_cost'];
                if (count($improvementItems) > 0) $previous['improvements']['institutions']++;
                $previous['improvements']['projects'] += count($improvementItems);
                
                $previous['total_institutions'] = count($institutionNames);
            }
        }

        // Debug: Check final totals
        // \Log::info('Current Year Totals: ', $current);
        // \Log::info('Previous Year Totals: ', $previous);

        // Sort power data by total cost (descending)
        uasort($newPowerData['current'], function($a, $b) {
            $totalA = $a['public_cost'] + $a['private_cost'];
            $totalB = $b['public_cost'] + $b['private_cost'];
            return $totalB - $totalA;
        });

        uasort($improvementPowerData['current'], function($a, $b) {
            $totalA = $a['public_cost'] + $a['private_cost'];
            $totalB = $b['public_cost'] + $b['private_cost'];
            return $totalB - $totalA;
        });

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $totalCurrentProjects = $current['new_power']['total_count'] + $current['improvements']['total_count'];
        $totalCurrentCost = $current['new_power']['total_cost'] + $current['improvements']['total_cost'];
        $totalPreviousProjects = $previous['new_power']['total_count'] + $previous['improvements']['total_count'];
        $totalPreviousCost = $previous['new_power']['total_cost'] + $previous['improvements']['total_cost'];
        
        $totalProjectsChange = $totalPreviousProjects > 0 ? 
            (($totalCurrentProjects - $totalPreviousProjects) / $totalPreviousProjects) * 100 : 0;
        
        $totalCostChange = $totalPreviousCost > 0 ? 
            (($totalCurrentCost - $totalPreviousCost) / $totalPreviousCost) * 100 : 0;

        // Debug: Final summary
        // \Log::info('Total Current Projects: ' . $totalCurrentProjects);
        // \Log::info('Total Current Cost: ' . $totalCurrentCost);

        return view('gabaasa_torbanii.power_institution.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'newPowerData',
            'improvementPowerData',
            'totalCurrentProjects',
            'totalCurrentCost',
            'totalPreviousProjects',
            'totalPreviousCost',
            'totalProjectsChange',
            'totalCostChange'
        ));
    }
}
