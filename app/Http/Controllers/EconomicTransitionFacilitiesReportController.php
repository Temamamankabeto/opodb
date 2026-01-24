<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EconomicTransitionFacility;

class EconomicTransitionFacilitiesReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from economic transition facilities for this kebele
        $availableYears = EconomicTransitionFacility::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch facilities for current and previous year for this kebele only
        $facilities = EconomicTransitionFacility::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->with('institution')
                        ->get();

        $reportData = [];
        
        // Initialize arrays for facility data
        $newFacilityData = [
            'current' => [],
            'previous' => []
        ];
        
        $improvementFacilityData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = [
            'new_facilities' => [
                'public_count' => 0,
                'private_count' => 0,
                'association_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'association_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'improvements' => [
                'public_count' => 0,
                'private_count' => 0,
                'association_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'association_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'total_institutions' => 0
        ];

        $previous = [
            'new_facilities' => [
                'public_count' => 0,
                'private_count' => 0,
                'association_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'association_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'improvements' => [
                'public_count' => 0,
                'private_count' => 0,
                'association_count' => 0,
                'public_cost' => 0,
                'private_cost' => 0,
                'association_cost' => 0,
                'total_count' => 0,
                'total_cost' => 0,
                'institutions' => 0,
                'projects' => 0
            ],
            'total_institutions' => 0
        ];

        // Track unique institutions and facility types
        $institutionNames = [];
        $facilityTypes = [];

        foreach ($facilities as $facility) {
            // Safe access to arrays
            $newFacilityItems = is_array($facility->new_building_data) ? $facility->new_building_data : json_decode($facility->new_building_data ?? '[]', true);
            $improvementItems = is_array($facility->improvement_data) ? $facility->improvement_data : json_decode($facility->improvement_data ?? '[]', true);

            $yearlyNewPublic = 0;
            $yearlyNewPrivate = 0;
            $yearlyNewAssociation = 0;
            $yearlyNewPublicCost = 0;
            $yearlyNewPrivateCost = 0;
            $yearlyNewAssociationCost = 0;
            
            $yearlyImprovePublic = 0;
            $yearlyImprovePrivate = 0;
            $yearlyImproveAssociation = 0;
            $yearlyImprovePublicCost = 0;
            $yearlyImprovePrivateCost = 0;
            $yearlyImproveAssociationCost = 0;

            // Get institution name
            $institutionName = $facility->institution->name ?? 'Unknown Institution';
            if (!in_array($institutionName, $institutionNames)) {
                $institutionNames[] = $institutionName;
            }

            // Process new facility data
            foreach ($newFacilityItems as $item) {
                $facilityName = $item['name'] ?? 'Unknown Facility';
                $publicNumber = isset($item['public_number']) ? intval($item['public_number']) : 0;
                $privateNumber = isset($item['private_number']) ? intval($item['private_number']) : 0;
                $associationNumber = isset($item['association_number']) ? intval($item['association_number']) : 0;
                $publicCost = isset($item['public_cost']) ? floatval($item['public_cost']) : 0;
                $privateCost = isset($item['private_cost']) ? floatval($item['private_cost']) : 0;
                $associationCost = isset($item['association_cost']) ? floatval($item['association_cost']) : 0;
                
                $yearlyNewPublic += $publicNumber;
                $yearlyNewPrivate += $privateNumber;
                $yearlyNewAssociation += $associationNumber;
                $yearlyNewPublicCost += $publicCost;
                $yearlyNewPrivateCost += $privateCost;
                $yearlyNewAssociationCost += $associationCost;
                
                // Store facility type
                if (!in_array($facilityName, $facilityTypes)) {
                    $facilityTypes[] = $facilityName;
                }
                
                // Store new facility data by facility type
                $key = $facilityName . ' (' . $institutionName . ')';
                if ($facility->year == $selectedYear) {
                    if (!isset($newFacilityData['current'][$key])) {
                        $newFacilityData['current'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'association_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0,
                            'association_cost' => 0
                        ];
                    }
                    $newFacilityData['current'][$key]['public_count'] += $publicNumber;
                    $newFacilityData['current'][$key]['private_count'] += $privateNumber;
                    $newFacilityData['current'][$key]['association_count'] += $associationNumber;
                    $newFacilityData['current'][$key]['public_cost'] += $publicCost;
                    $newFacilityData['current'][$key]['private_cost'] += $privateCost;
                    $newFacilityData['current'][$key]['association_cost'] += $associationCost;
                } else {
                    if (!isset($newFacilityData['previous'][$key])) {
                        $newFacilityData['previous'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'association_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0,
                            'association_cost' => 0
                        ];
                    }
                    $newFacilityData['previous'][$key]['public_count'] += $publicNumber;
                    $newFacilityData['previous'][$key]['private_count'] += $privateNumber;
                    $newFacilityData['previous'][$key]['association_count'] += $associationNumber;
                    $newFacilityData['previous'][$key]['public_cost'] += $publicCost;
                    $newFacilityData['previous'][$key]['private_cost'] += $privateCost;
                    $newFacilityData['previous'][$key]['association_cost'] += $associationCost;
                }
            }

            // Process improvement data
            foreach ($improvementItems as $item) {
                $facilityName = $item['name'] ?? 'Unknown Facility';
                $publicNumber = isset($item['public_number']) ? intval($item['public_number']) : 0;
                $privateNumber = isset($item['private_number']) ? intval($item['private_number']) : 0;
                $associationNumber = isset($item['association_number']) ? intval($item['association_number']) : 0;
                $publicCost = isset($item['public_cost']) ? floatval($item['public_cost']) : 0;
                $privateCost = isset($item['private_cost']) ? floatval($item['private_cost']) : 0;
                $associationCost = isset($item['association_cost']) ? floatval($item['association_cost']) : 0;
                
                $yearlyImprovePublic += $publicNumber;
                $yearlyImprovePrivate += $privateNumber;
                $yearlyImproveAssociation += $associationNumber;
                $yearlyImprovePublicCost += $publicCost;
                $yearlyImprovePrivateCost += $privateCost;
                $yearlyImproveAssociationCost += $associationCost;
                
                // Store facility type
                if (!in_array($facilityName, $facilityTypes)) {
                    $facilityTypes[] = $facilityName;
                }
                
                // Store improvement data by facility type
                $key = $facilityName . ' (' . $institutionName . ')';
                if ($facility->year == $selectedYear) {
                    if (!isset($improvementFacilityData['current'][$key])) {
                        $improvementFacilityData['current'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'association_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0,
                            'association_cost' => 0
                        ];
                    }
                    $improvementFacilityData['current'][$key]['public_count'] += $publicNumber;
                    $improvementFacilityData['current'][$key]['private_count'] += $privateNumber;
                    $improvementFacilityData['current'][$key]['association_count'] += $associationNumber;
                    $improvementFacilityData['current'][$key]['public_cost'] += $publicCost;
                    $improvementFacilityData['current'][$key]['private_cost'] += $privateCost;
                    $improvementFacilityData['current'][$key]['association_cost'] += $associationCost;
                } else {
                    if (!isset($improvementFacilityData['previous'][$key])) {
                        $improvementFacilityData['previous'][$key] = [
                            'public_count' => 0,
                            'private_count' => 0,
                            'association_count' => 0,
                            'public_cost' => 0,
                            'private_cost' => 0,
                            'association_cost' => 0
                        ];
                    }
                    $improvementFacilityData['previous'][$key]['public_count'] += $publicNumber;
                    $improvementFacilityData['previous'][$key]['private_count'] += $privateNumber;
                    $improvementFacilityData['previous'][$key]['association_count'] += $associationNumber;
                    $improvementFacilityData['previous'][$key]['public_cost'] += $publicCost;
                    $improvementFacilityData['previous'][$key]['private_cost'] += $privateCost;
                    $improvementFacilityData['previous'][$key]['association_cost'] += $associationCost;
                }
            }

            if ($facility->year == $selectedYear) {
                // New facilities totals
                $current['new_facilities']['public_count'] += $yearlyNewPublic;
                $current['new_facilities']['private_count'] += $yearlyNewPrivate;
                $current['new_facilities']['association_count'] += $yearlyNewAssociation;
                $current['new_facilities']['public_cost'] += $yearlyNewPublicCost;
                $current['new_facilities']['private_cost'] += $yearlyNewPrivateCost;
                $current['new_facilities']['association_cost'] += $yearlyNewAssociationCost;
                $current['new_facilities']['total_count'] = $current['new_facilities']['public_count'] + 
                                                           $current['new_facilities']['private_count'] + 
                                                           $current['new_facilities']['association_count'];
                $current['new_facilities']['total_cost'] = $current['new_facilities']['public_cost'] + 
                                                          $current['new_facilities']['private_cost'] + 
                                                          $current['new_facilities']['association_cost'];
                if (count($newFacilityItems) > 0) $current['new_facilities']['institutions']++;
                $current['new_facilities']['projects'] += count($newFacilityItems);
                
                // Improvement totals
                $current['improvements']['public_count'] += $yearlyImprovePublic;
                $current['improvements']['private_count'] += $yearlyImprovePrivate;
                $current['improvements']['association_count'] += $yearlyImproveAssociation;
                $current['improvements']['public_cost'] += $yearlyImprovePublicCost;
                $current['improvements']['private_cost'] += $yearlyImprovePrivateCost;
                $current['improvements']['association_cost'] += $yearlyImproveAssociationCost;
                $current['improvements']['total_count'] = $current['improvements']['public_count'] + 
                                                         $current['improvements']['private_count'] + 
                                                         $current['improvements']['association_count'];
                $current['improvements']['total_cost'] = $current['improvements']['public_cost'] + 
                                                        $current['improvements']['private_cost'] + 
                                                        $current['improvements']['association_cost'];
                if (count($improvementItems) > 0) $current['improvements']['institutions']++;
                $current['improvements']['projects'] += count($improvementItems);
                
                $current['total_institutions'] = count($institutionNames);
            } else {
                // New facilities totals
                $previous['new_facilities']['public_count'] += $yearlyNewPublic;
                $previous['new_facilities']['private_count'] += $yearlyNewPrivate;
                $previous['new_facilities']['association_count'] += $yearlyNewAssociation;
                $previous['new_facilities']['public_cost'] += $yearlyNewPublicCost;
                $previous['new_facilities']['private_cost'] += $yearlyNewPrivateCost;
                $previous['new_facilities']['association_cost'] += $yearlyNewAssociationCost;
                $previous['new_facilities']['total_count'] = $previous['new_facilities']['public_count'] + 
                                                            $previous['new_facilities']['private_count'] + 
                                                            $previous['new_facilities']['association_count'];
                $previous['new_facilities']['total_cost'] = $previous['new_facilities']['public_cost'] + 
                                                           $previous['new_facilities']['private_cost'] + 
                                                           $previous['new_facilities']['association_cost'];
                if (count($newFacilityItems) > 0) $previous['new_facilities']['institutions']++;
                $previous['new_facilities']['projects'] += count($newFacilityItems);
                
                // Improvement totals
                $previous['improvements']['public_count'] += $yearlyImprovePublic;
                $previous['improvements']['private_count'] += $yearlyImprovePrivate;
                $previous['improvements']['association_count'] += $yearlyImproveAssociation;
                $previous['improvements']['public_cost'] += $yearlyImprovePublicCost;
                $previous['improvements']['private_cost'] += $yearlyImprovePrivateCost;
                $previous['improvements']['association_cost'] += $yearlyImproveAssociationCost;
                $previous['improvements']['total_count'] = $previous['improvements']['public_count'] + 
                                                          $previous['improvements']['private_count'] + 
                                                          $previous['improvements']['association_count'];
                $previous['improvements']['total_cost'] = $previous['improvements']['public_cost'] + 
                                                         $previous['improvements']['private_cost'] + 
                                                         $previous['improvements']['association_cost'];
                if (count($improvementItems) > 0) $previous['improvements']['institutions']++;
                $previous['improvements']['projects'] += count($improvementItems);
                
                $previous['total_institutions'] = count($institutionNames);
            }
        }

        // Sort facility data by total cost (descending)
        uasort($newFacilityData['current'], function($a, $b) {
            $totalA = $a['public_cost'] + $a['private_cost'] + $a['association_cost'];
            $totalB = $b['public_cost'] + $b['private_cost'] + $b['association_cost'];
            return $totalB - $totalA;
        });

        uasort($improvementFacilityData['current'], function($a, $b) {
            $totalA = $a['public_cost'] + $a['private_cost'] + $a['association_cost'];
            $totalB = $b['public_cost'] + $b['private_cost'] + $b['association_cost'];
            return $totalB - $totalA;
        });

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $totalCurrentProjects = $current['new_facilities']['total_count'] + $current['improvements']['total_count'];
        $totalCurrentCost = $current['new_facilities']['total_cost'] + $current['improvements']['total_cost'];
        $totalPreviousProjects = $previous['new_facilities']['total_count'] + $previous['improvements']['total_count'];
        $totalPreviousCost = $previous['new_facilities']['total_cost'] + $previous['improvements']['total_cost'];
        
        $totalProjectsChange = $totalPreviousProjects > 0 ? 
            (($totalCurrentProjects - $totalPreviousProjects) / $totalPreviousProjects) * 100 : 0;
        
        $totalCostChange = $totalPreviousCost > 0 ? 
            (($totalCurrentCost - $totalPreviousCost) / $totalPreviousCost) * 100 : 0;

        return view('gabaasa_torbanii.economic_transition_facilities.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'newFacilityData',
            'improvementFacilityData',
            'totalCurrentProjects',
            'totalCurrentCost',
            'totalPreviousProjects',
            'totalPreviousCost',
            'totalProjectsChange',
            'totalCostChange'
        ));
    }
}
