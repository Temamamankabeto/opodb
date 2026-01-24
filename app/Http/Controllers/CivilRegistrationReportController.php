<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CivilRegistrationPlan;

class CivilRegistrationReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from civil registration records for this kebele
        $availableYears = CivilRegistrationPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch civil registration records for current and previous year for this kebele only
        $civilRegistrationPlans = CivilRegistrationPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        // Initialize totals
        $current = [
            'birth_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa dhalootaa kenname'
            ],
            'marriage_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa gaa\'elaa kenname'
            ],
            'id_cards' => [
                'quantity' => 0,
                'description' => 'Waraqaa eenyummaa kenname'
            ],
            'divorce_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa diigamuu gaa\'elaa kenname'
            ],
            'death_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa du\'aatii kenname'
            ],
            'total_documents' => 0,
            'total_categories' => 0
        ];

        $previous = [
            'birth_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa dhalootaa kenname'
            ],
            'marriage_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa gaa\'elaa kenname'
            ],
            'id_cards' => [
                'quantity' => 0,
                'description' => 'Waraqaa eenyummaa kenname'
            ],
            'divorce_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa diigamuu gaa\'elaa kenname'
            ],
            'death_certificates' => [
                'quantity' => 0,
                'description' => 'Ragaa du\'aatii kenname'
            ],
            'total_documents' => 0,
            'total_categories' => 0
        ];

        // Detailed data for charts
        $activityData = [
            'current' => [],
            'previous' => []
        ];

        foreach ($civilRegistrationPlans as $plan) {
            $activities = $this->decodeJsonData($plan->civil_registration_activities);
            
            foreach ($activities as $activity) {
                $name = $activity['name'] ?? '';
                $quantity = isset($activity['quantity']) ? intval($activity['quantity']) : 0;
                $unit = $activity['unit'] ?? '';
                
                // Store activity data for charts
                $key = $name;
                if ($plan->year == $selectedYear) {
                    if (!isset($activityData['current'][$key])) {
                        $activityData['current'][$key] = 0;
                    }
                    $activityData['current'][$key] += $quantity;
                } else {
                    if (!isset($activityData['previous'][$key])) {
                        $activityData['previous'][$key] = 0;
                    }
                    $activityData['previous'][$key] += $quantity;
                }

                // Categorize and sum data
                if ($plan->year == $selectedYear) {
                    $current['total_documents'] += $quantity;
                    $current['total_categories'] = max($current['total_categories'], count($activities));
                    
                    // Categorize by document type
                    if (strpos($name, 'Ragaa dhalootaa') !== false) {
                        $current['birth_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Ragaa gaa\'elaa') !== false && strpos($name, 'diigamuu') === false) {
                        $current['marriage_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Waraqaa eenyummaa') !== false) {
                        $current['id_cards']['quantity'] += $quantity;
                    } elseif (strpos($name, 'diigamuu gaa\'elaa') !== false) {
                        $current['divorce_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Ragaa du\'aatii') !== false) {
                        $current['death_certificates']['quantity'] += $quantity;
                    }
                } else {
                    $previous['total_documents'] += $quantity;
                    $previous['total_categories'] = max($previous['total_categories'], count($activities));
                    
                    // Same categorization for previous year
                    if (strpos($name, 'Ragaa dhalootaa') !== false) {
                        $previous['birth_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Ragaa gaa\'elaa') !== false && strpos($name, 'diigamuu') === false) {
                        $previous['marriage_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Waraqaa eenyummaa') !== false) {
                        $previous['id_cards']['quantity'] += $quantity;
                    } elseif (strpos($name, 'diigamuu gaa\'elaa') !== false) {
                        $previous['divorce_certificates']['quantity'] += $quantity;
                    } elseif (strpos($name, 'Ragaa du\'aatii') !== false) {
                        $previous['death_certificates']['quantity'] += $quantity;
                    }
                }
            }
        }

        // Sort activity data
        arsort($activityData['current']);
        arsort($activityData['previous']);

        // Prepare report data for the kebele
        $reportData = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary metrics
        $documentChange = $previous['total_documents'] > 0 ? 
            (($current['total_documents'] - $previous['total_documents']) / $previous['total_documents']) * 100 : 0;

        return view('gabaasa_torbanii.civil_registration.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'activityData',
            'documentChange'
        ));
    }

    /**
     * Helper method to decode JSON data
     */
    private function decodeJsonData($data)
    {
        if (is_array($data)) {
            return $data;
        }
        
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            
            if ($decoded === null || !is_array($decoded)) {
                if (is_string($data) && strpos($data, '\\"') !== false) {
                    $data = stripslashes($data);
                    $decoded = json_decode($data, true);
                }
            }
            
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
}
