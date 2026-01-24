<?php

namespace App\Http\Controllers;

use App\Models\Barnoota;
use Illuminate\Http\Request;

class BarnootaReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from barnoota records for this kebele
        $availableYears = Barnoota::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch barnoota records for current and previous year for this kebele only
        $barnootas = Barnoota::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        $reportData = [];
        
        // Initialize arrays for education data
        $hirmaannaaData = [
            'current' => [],
            'previous' => []
        ];
        
        $qabeessummaaData = [
            'current' => [],
            'previous' => []
        ];
        
        $nyaataData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = [
            'hirmaannaa' => [
                'ga_eessota' => ['total' => 0, 'k1_6' => 0, 'k7_8' => 0, 'k9_12' => 0],
                'barataa_idilee' => ['total' => 0, 'k1_6' => 0, 'k7_8' => 0, 'k9_12' => 0],
                'total_students' => 0
            ],
            'qabeessummaa' => [
                'exams_taken' => 0,
                'exams_passed' => 0,
                'grades_completed' => 0,
                'total_students' => 0
            ],
            'nyaata' => [
                'school_produced' => ['amount' => 0, 'cost' => 0],
                'external_source' => ['amount' => 0, 'cost' => 0],
                'total_amount' => 0,
                'total_cost' => 0
            ],
            'total_programs' => 0
        ];

        $previous = [
            'hirmaannaa' => [
                'ga_eessota' => ['total' => 0, 'k1_6' => 0, 'k7_8' => 0, 'k9_12' => 0],
                'barataa_idilee' => ['total' => 0, 'k1_6' => 0, 'k7_8' => 0, 'k9_12' => 0],
                'total_students' => 0
            ],
            'qabeessummaa' => [
                'exams_taken' => 0,
                'exams_passed' => 0,
                'grades_completed' => 0,
                'total_students' => 0
            ],
            'nyaata' => [
                'school_produced' => ['amount' => 0, 'cost' => 0],
                'external_source' => ['amount' => 0, 'cost' => 0],
                'total_amount' => 0,
                'total_cost' => 0
            ],
            'total_programs' => 0
        ];

        foreach ($barnootas as $barnoota) {
            // Decode JSON data with proper handling for escaped JSON strings
            $hirmaannaaItems = $this->decodeJsonData($barnoota->Hirmaannaa_itti_fufiinsa);
            $qabeessummaaItems = $this->decodeJsonData($barnoota->Bu_aa_qabeessummaa);
            $nyaataItems = $this->decodeJsonData($barnoota->Dhiyeesii_nyaata_barattootaa);

            // Process Hirmaannaa data
            foreach ($hirmaannaaItems as $item) {
                $itemName = $item['name'] ?? '';
                $studentType = $item['student_type'] ?? '';
                $amount = isset($item['amount']) ? intval($item['amount']) : 0;
                
                // Store by student type and grade level
                $key = $studentType . ' - ' . $itemName;
                if ($barnoota->year == $selectedYear) {
                    if (!isset($hirmaannaaData['current'][$key])) {
                        $hirmaannaaData['current'][$key] = 0;
                    }
                    $hirmaannaaData['current'][$key] += $amount;
                } else {
                    if (!isset($hirmaannaaData['previous'][$key])) {
                        $hirmaannaaData['previous'][$key] = 0;
                    }
                    $hirmaannaaData['previous'][$key] += $amount;
                }

                // Update totals
                if ($barnoota->year == $selectedYear) {
                    if (strpos($itemName, 'Kutaa 1-6') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $current['hirmaannaa']['ga_eessota']['k1_6'] += $amount;
                            $current['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $current['hirmaannaa']['barataa_idilee']['k1_6'] += $amount;
                            $current['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    } elseif (strpos($itemName, 'Kutaa 7-8') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $current['hirmaannaa']['ga_eessota']['k7_8'] += $amount;
                            $current['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $current['hirmaannaa']['barataa_idilee']['k7_8'] += $amount;
                            $current['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    } elseif (strpos($itemName, 'Kutaa 9-12') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $current['hirmaannaa']['ga_eessota']['k9_12'] += $amount;
                            $current['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $current['hirmaannaa']['barataa_idilee']['k9_12'] += $amount;
                            $current['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    }
                    $current['hirmaannaa']['total_students'] += $amount;
                } else {
                    if (strpos($itemName, 'Kutaa 1-6') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $previous['hirmaannaa']['ga_eessota']['k1_6'] += $amount;
                            $previous['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $previous['hirmaannaa']['barataa_idilee']['k1_6'] += $amount;
                            $previous['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    } elseif (strpos($itemName, 'Kutaa 7-8') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $previous['hirmaannaa']['ga_eessota']['k7_8'] += $amount;
                            $previous['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $previous['hirmaannaa']['barataa_idilee']['k7_8'] += $amount;
                            $previous['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    } elseif (strpos($itemName, 'Kutaa 9-12') !== false) {
                        if ($studentType == 'Ga`eessota') {
                            $previous['hirmaannaa']['ga_eessota']['k9_12'] += $amount;
                            $previous['hirmaannaa']['ga_eessota']['total'] += $amount;
                        } else {
                            $previous['hirmaannaa']['barataa_idilee']['k9_12'] += $amount;
                            $previous['hirmaannaa']['barataa_idilee']['total'] += $amount;
                        }
                    }
                    $previous['hirmaannaa']['total_students'] += $amount;
                }
            }

            // Process Qabeessummaa data
            foreach ($qabeessummaaItems as $item) {
                $itemName = $item['name'] ?? '';
                $amount = isset($item['amount']) ? intval($item['amount']) : 0;
                
                // Store by exam type
                $key = $itemName;
                if ($barnoota->year == $selectedYear) {
                    if (!isset($qabeessummaaData['current'][$key])) {
                        $qabeessummaaData['current'][$key] = 0;
                    }
                    $qabeessummaaData['current'][$key] += $amount;
                } else {
                    if (!isset($qabeessummaaData['previous'][$key])) {
                        $qabeessummaaData['previous'][$key] = 0;
                    }
                    $qabeessummaaData['previous'][$key] += $amount;
                }

                // Update totals
                if ($barnoota->year == $selectedYear) {
                    if (strpos($itemName, 'galmeessisan') !== false || 
                        strpos($itemName, 'fudhatan') !== false || 
                        strpos($itemName, 'Taa´n') !== false) {
                        $current['qabeessummaa']['exams_passed'] += $amount;
                    } elseif (strpos($itemName, 'darban') !== false) {
                        $current['qabeessummaa']['exams_taken'] += $amount;
                    } elseif (strpos($itemName, 'Xumuran') !== false) {
                        $current['qabeessummaa']['grades_completed'] += $amount;
                    }
                    $current['qabeessummaa']['total_students'] += $amount;
                } else {
                    if (strpos($itemName, 'galmeessisan') !== false || 
                        strpos($itemName, 'fudhatan') !== false || 
                        strpos($itemName, 'Taa´n') !== false) {
                        $previous['qabeessummaa']['exams_passed'] += $amount;
                    } elseif (strpos($itemName, 'darban') !== false) {
                        $previous['qabeessummaa']['exams_taken'] += $amount;
                    } elseif (strpos($itemName, 'Xumuran') !== false) {
                        $previous['qabeessummaa']['grades_completed'] += $amount;
                    }
                    $previous['qabeessummaa']['total_students'] += $amount;
                }
            }

            // Process Nyaata data
            foreach ($nyaataItems as $item) {
                $source = $item['source'] ?? '';
                $amount = isset($item['amount']) ? intval($item['amount']) : 0;
                $cost = isset($item['cost']) ? floatval($item['cost']) : 0;
                
                // Store by source
                $key = $source;
                if ($barnoota->year == $selectedYear) {
                    if (!isset($nyaataData['current'][$key])) {
                        $nyaataData['current'][$key] = ['amount' => 0, 'cost' => 0];
                    }
                    $nyaataData['current'][$key]['amount'] += $amount;
                    $nyaataData['current'][$key]['cost'] += $cost;
                } else {
                    if (!isset($nyaataData['previous'][$key])) {
                        $nyaataData['previous'][$key] = ['amount' => 0, 'cost' => 0];
                    }
                    $nyaataData['previous'][$key]['amount'] += $amount;
                    $nyaataData['previous'][$key]['cost'] += $cost;
                }

                // Update totals
                if ($barnoota->year == $selectedYear) {
                    if (strpos($source, 'man-ni barumsaa') !== false) {
                        $current['nyaata']['school_produced']['amount'] += $amount;
                        $current['nyaata']['school_produced']['cost'] += $cost;
                    } else {
                        $current['nyaata']['external_source']['amount'] += $amount;
                        $current['nyaata']['external_source']['cost'] += $cost;
                    }
                    $current['nyaata']['total_amount'] += $amount;
                    $current['nyaata']['total_cost'] += $cost;
                } else {
                    if (strpos($source, 'man-ni barumsaa') !== false) {
                        $previous['nyaata']['school_produced']['amount'] += $amount;
                        $previous['nyaata']['school_produced']['cost'] += $cost;
                    } else {
                        $previous['nyaata']['external_source']['amount'] += $amount;
                        $previous['nyaata']['external_source']['cost'] += $cost;
                    }
                    $previous['nyaata']['total_amount'] += $amount;
                    $previous['nyaata']['total_cost'] += $cost;
                }
            }

            if ($barnoota->year == $selectedYear) {
                $current['total_programs'] = count($hirmaannaaItems) + count($qabeessummaaItems) + count($nyaataItems);
            } else {
                $previous['total_programs'] = count($hirmaannaaItems) + count($qabeessummaaItems) + count($nyaataItems);
            }
        }

        // Sort data
        arsort($hirmaannaaData['current']);
        arsort($qabeessummaaData['current']);
        arsort($nyaataData['current']);

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $totalCurrentStudents = $current['hirmaannaa']['total_students'] + $current['qabeessummaa']['total_students'];
        $totalPreviousStudents = $previous['hirmaannaa']['total_students'] + $previous['qabeessummaa']['total_students'];
        
        $studentChange = $totalPreviousStudents > 0 ? 
            (($totalCurrentStudents - $totalPreviousStudents) / $totalPreviousStudents) * 100 : 0;
        
        $passRateCurrent = $current['qabeessummaa']['exams_taken'] > 0 ? 
            ($current['qabeessummaa']['exams_passed'] / $current['qabeessummaa']['exams_taken']) * 100 : 0;
        
        $passRatePrevious = $previous['qabeessummaa']['exams_taken'] > 0 ? 
            ($previous['qabeessummaa']['exams_passed'] / $previous['qabeessummaa']['exams_taken']) * 100 : 0;
        
        $passRateChange = $passRatePrevious > 0 ? 
            (($passRateCurrent - $passRatePrevious) / $passRatePrevious) * 100 : 0;

        return view('gabaasa_torbanii.barnoota.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'hirmaannaaData',
            'qabeessummaaData',
            'nyaataData',
            'totalCurrentStudents',
            'totalPreviousStudents',
            'studentChange',
            'passRateCurrent',
            'passRatePrevious',
            'passRateChange'
        ));
    }

    /**
     * Helper method to decode JSON data that might be double-encoded
     */
    private function decodeJsonData($data)
    {
        if (is_array($data)) {
            return $data;
        }
        
        if (is_string($data)) {
            // Try to decode as JSON
            $decoded = json_decode($data, true);
            
            // If decoding fails or returns null, try to decode a second time
            if ($decoded === null || !is_array($decoded)) {
                // Check if it's a JSON string within a JSON string
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
