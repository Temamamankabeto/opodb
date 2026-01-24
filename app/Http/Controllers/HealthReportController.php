<?php

namespace App\Http\Controllers;

use App\Models\HealthPlan;
use Illuminate\Http\Request;

class HealthReportController extends Controller
{
   public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from health records for this kebele
        $availableYears = HealthPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch health records for current and previous year for this kebele only
        $healthPlans = HealthPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        // Initialize totals
        $current = [
            'pregnant_women' => [
                'first_visit' => 0,
                'delivery' => 0,
                'postnatal' => 0,
                'nutrition_support' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'children' => [
                'newborn_followup' => 0,
                'vaccinated' => 0,
                'diarrhea_cases' => 0,
                'child_development' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'other' => [
                'clean_toilets' => 0,
                'family_planning' => 0,
                'insurance_members' => 0,
                'insurance_collected' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'total_activities' => 0,
            'total_budget' => 0
        ];

        $previous = [
            'pregnant_women' => [
                'first_visit' => 0,
                'delivery' => 0,
                'postnatal' => 0,
                'nutrition_support' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'children' => [
                'newborn_followup' => 0,
                'vaccinated' => 0,
                'diarrhea_cases' => 0,
                'child_development' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'other' => [
                'clean_toilets' => 0,
                'family_planning' => 0,
                'insurance_members' => 0,
                'insurance_collected' => 0,
                'total' => 0,
                'budget' => 0
            ],
            'total_activities' => 0,
            'total_budget' => 0
        ];

        // Detailed data for charts
        $activityData = [
            'current' => [],
            'previous' => []
        ];

        foreach ($healthPlans as $plan) {
            $activities = $this->decodeJsonData($plan->health_activity_name);
            
            foreach ($activities as $activity) {
                $name = $activity['name'] ?? '';
                $amount = isset($activity['amount']) ? intval($activity['amount']) : 0;
                $budget = isset($activity['budget']) ? floatval($activity['budget']) : 0;
                $type = $activity['type'] ?? 'other';
                
                // Store activity data for charts
                $key = $name;
                if ($plan->year == $selectedYear) {
                    if (!isset($activityData['current'][$key])) {
                        $activityData['current'][$key] = 0;
                    }
                    $activityData['current'][$key] += $amount;
                } else {
                    if (!isset($activityData['previous'][$key])) {
                        $activityData['previous'][$key] = 0;
                    }
                    $activityData['previous'][$key] += $amount;
                }

                // Categorize and sum data
                if ($plan->year == $selectedYear) {
                    $current['total_activities']++;
                    $current['total_budget'] += $budget;
                    
                    // Categorize by type
                    if (strpos($name, 'Haadholee ulfaa') !== false) {
                        $current['pregnant_women']['first_visit'] += $amount;
                        $current['pregnant_women']['total'] += $amount;
                        $current['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'Haadholee dhaabbata') !== false && strpos($name, 'dahan') !== false) {
                        $current['pregnant_women']['delivery'] += $amount;
                        $current['pregnant_women']['total'] += $amount;
                        $current['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'Haadholee dahumsa booda') !== false) {
                        $current['pregnant_women']['postnatal'] += $amount;
                        $current['pregnant_women']['total'] += $amount;
                        $current['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'harka-qalleeyyii nyaati') !== false) {
                        $current['pregnant_women']['nutrition_support'] += $amount;
                        $current['pregnant_women']['total'] += $amount;
                        $current['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'reefuu dhalatan') !== false) {
                        $current['children']['newborn_followup'] += $amount;
                        $current['children']['total'] += $amount;
                        $current['children']['budget'] += $budget;
                    } elseif (strpos($name, 'duulaan talaallii') !== false) {
                        $current['children']['vaccinated'] += $amount;
                        $current['children']['total'] += $amount;
                        $current['children']['budget'] += $budget;
                    } elseif (strpos($name, 'dhibeen garaa kaasaa') !== false) {
                        $current['children']['diarrhea_cases'] += $amount;
                        $current['children']['total'] += $amount;
                        $current['children']['budget'] += $budget;
                    } elseif (strpos($name, 'Guddina Daa\'immanii') !== false) {
                        $current['children']['child_development'] += $amount;
                        $current['children']['total'] += $amount;
                        $current['children']['budget'] += $budget;
                    } elseif (strpos($name, 'bookee busaa qulqullaa') !== false) {
                        $current['other']['clean_toilets'] += $amount;
                        $current['other']['total'] += $amount;
                        $current['other']['budget'] += $budget;
                    } elseif (strpos($name, 'karoora maatii') !== false) {
                        $current['other']['family_planning'] += $amount;
                        $current['other']['total'] += $amount;
                        $current['other']['budget'] += $budget;
                    } elseif (strpos($name, 'Inshuraansii Fayyaa Hawaasaa ta\'e') !== false) {
                        $current['other']['insurance_members'] += $amount;
                        $current['other']['total'] += $amount;
                        $current['other']['budget'] += $budget;
                    } elseif (strpos($name, 'Buusii Inshuraansii') !== false) {
                        $current['other']['insurance_collected'] += $amount;
                        $current['other']['total'] += $amount;
                        $current['other']['budget'] += $budget;
                    } elseif ($type == 'dhimma haadholee') {
                        $current['pregnant_women']['total'] += $amount;
                        $current['pregnant_women']['budget'] += $budget;
                    } elseif ($type == 'dhimma Daa\'immanii') {
                        $current['children']['total'] += $amount;
                        $current['children']['budget'] += $budget;
                    } else {
                        $current['other']['total'] += $amount;
                        $current['other']['budget'] += $budget;
                    }
                } else {
                    $previous['total_activities']++;
                    $previous['total_budget'] += $budget;
                    
                    // Same categorization for previous year
                    if (strpos($name, 'Haadholee ulfaa') !== false) {
                        $previous['pregnant_women']['first_visit'] += $amount;
                        $previous['pregnant_women']['total'] += $amount;
                        $previous['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'Haadholee dhaabbata') !== false && strpos($name, 'dahan') !== false) {
                        $previous['pregnant_women']['delivery'] += $amount;
                        $previous['pregnant_women']['total'] += $amount;
                        $previous['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'Haadholee dahumsa booda') !== false) {
                        $previous['pregnant_women']['postnatal'] += $amount;
                        $previous['pregnant_women']['total'] += $amount;
                        $previous['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'harka-qalleeyyii nyaati') !== false) {
                        $previous['pregnant_women']['nutrition_support'] += $amount;
                        $previous['pregnant_women']['total'] += $amount;
                        $previous['pregnant_women']['budget'] += $budget;
                    } elseif (strpos($name, 'reefuu dhalatan') !== false) {
                        $previous['children']['newborn_followup'] += $amount;
                        $previous['children']['total'] += $amount;
                        $previous['children']['budget'] += $budget;
                    } elseif (strpos($name, 'duulaan talaallii') !== false) {
                        $previous['children']['vaccinated'] += $amount;
                        $previous['children']['total'] += $amount;
                        $previous['children']['budget'] += $budget;
                    } elseif (strpos($name, 'dhibeen garaa kaasaa') !== false) {
                        $previous['children']['diarrhea_cases'] += $amount;
                        $previous['children']['total'] += $amount;
                        $previous['children']['budget'] += $budget;
                    } elseif (strpos($name, 'Guddina Daa\'immanii') !== false) {
                        $previous['children']['child_development'] += $amount;
                        $previous['children']['total'] += $amount;
                        $previous['children']['budget'] += $budget;
                    } elseif (strpos($name, 'bookee busaa qulqullaa') !== false) {
                        $previous['other']['clean_toilets'] += $amount;
                        $previous['other']['total'] += $amount;
                        $previous['other']['budget'] += $budget;
                    } elseif (strpos($name, 'karoora maatii') !== false) {
                        $previous['other']['family_planning'] += $amount;
                        $previous['other']['total'] += $amount;
                        $previous['other']['budget'] += $budget;
                    } elseif (strpos($name, 'Inshuraansii Fayyaa Hawaasaa ta\'e') !== false) {
                        $previous['other']['insurance_members'] += $amount;
                        $previous['other']['total'] += $amount;
                        $previous['other']['budget'] += $budget;
                    } elseif (strpos($name, 'Buusii Inshuraansii') !== false) {
                        $previous['other']['insurance_collected'] += $amount;
                        $previous['other']['total'] += $amount;
                        $previous['other']['budget'] += $budget;
                    } elseif ($type == 'dhimma haadholee') {
                        $previous['pregnant_women']['total'] += $amount;
                        $previous['pregnant_women']['budget'] += $budget;
                    } elseif ($type == 'dhimma Daa\'immanii') {
                        $previous['children']['total'] += $amount;
                        $previous['children']['budget'] += $budget;
                    } else {
                        $previous['other']['total'] += $amount;
                        $previous['other']['budget'] += $budget;
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
        $totalCurrentBeneficiaries = $current['pregnant_women']['total'] + $current['children']['total'] + $current['other']['total'];
        $totalPreviousBeneficiaries = $previous['pregnant_women']['total'] + $previous['children']['total'] + $previous['other']['total'];
        
        $beneficiaryChange = $totalPreviousBeneficiaries > 0 ? 
            (($totalCurrentBeneficiaries - $totalPreviousBeneficiaries) / $totalPreviousBeneficiaries) * 100 : 0;

        return view('gabaasa_torbanii.health.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'activityData',
            'totalCurrentBeneficiaries',
            'totalPreviousBeneficiaries',
            'beneficiaryChange'
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
