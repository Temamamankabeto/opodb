<?php

namespace App\Http\Controllers;

use App\Models\CulturalPlan;
use Illuminate\Http\Request;

class CulturalReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from cultural records for this kebele
        $availableYears = CulturalPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch cultural records for current and previous year for this kebele only
        $culturalPlans = CulturalPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        // Initialize totals
        $current = [
            'culture_awareness' => [
                'quantity' => 0,
                'unit' => 'Lakk',
                'description' => 'Haaromsa aadaa irratti ummata hubannoon kennameef'
            ],
            'youth_culture' => [
                'quantity' => 0,
                'unit' => 'Lakk',
                'description' => 'Dargaggoota aadaa fi aartii keessatti gurmaa\'anii socho\'an'
            ],
            'total_participants' => 0,
            'total_activities' => 0
        ];

        $previous = [
            'culture_awareness' => [
                'quantity' => 0,
                'unit' => 'Lakk',
                'description' => 'Haaromsa aadaa irratti ummata hubannoon kennameef'
            ],
            'youth_culture' => [
                'quantity' => 0,
                'unit' => 'Lakk',
                'description' => 'Dargaggoota aadaa fi aartii keessatti gurmaa\'anii socho\'an'
            ],
            'total_participants' => 0,
            'total_activities' => 0
        ];

        // Detailed data for charts
        $activityData = [
            'current' => [],
            'previous' => []
        ];

        foreach ($culturalPlans as $plan) {
            $activities = $this->decodeJsonData($plan->cultural_activities);
            
            foreach ($activities as $activity) {
                $name = $activity['name'] ?? '';
                $quantity = isset($activity['quantity']) ? intval($activity['quantity']) : 0;
                $unit = $activity['unit'] ?? 'Lakk';
                
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
                    $current['total_activities']++;
                    $current['total_participants'] += $quantity;
                    
                    // Categorize by activity type
                    if (strpos($name, 'Haaromsa aadaa') !== false) {
                        $current['culture_awareness']['quantity'] += $quantity;
                        $current['culture_awareness']['unit'] = $unit;
                    } elseif (strpos($name, 'Dargaggoota aadaa') !== false) {
                        $current['youth_culture']['quantity'] += $quantity;
                        $current['youth_culture']['unit'] = $unit;
                    }
                } else {
                    $previous['total_activities']++;
                    $previous['total_participants'] += $quantity;
                    
                    // Same categorization for previous year
                    if (strpos($name, 'Haaromsa aadaa') !== false) {
                        $previous['culture_awareness']['quantity'] += $quantity;
                        $previous['culture_awareness']['unit'] = $unit;
                    } elseif (strpos($name, 'Dargaggoota aadaa') !== false) {
                        $previous['youth_culture']['quantity'] += $quantity;
                        $previous['youth_culture']['unit'] = $unit;
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
        $participantChange = $previous['total_participants'] > 0 ? 
            (($current['total_participants'] - $previous['total_participants']) / $previous['total_participants']) * 100 : 0;

        return view('gabaasa_torbanii.cultural.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'activityData',
            'participantChange'
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
