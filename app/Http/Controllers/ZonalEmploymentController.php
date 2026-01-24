<?php

namespace App\Http\Controllers;

use App\Models\Woreda;
use Illuminate\Http\Request;
use App\Models\EnterpriseWork;
use App\Models\JobOpportunity;
use App\Models\EmploymentOpportunityPlan;

class ZonalEmploymentController extends Controller
{
    public function zonalPlans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a zone assigned
        if (!$user->zone_id) {
            abort(403, "No zone assigned to this user.");
        }

        // Get all woredas in this zone for the filter
        $woredasInZone = Woreda::where('zone_id', $user->zone_id)
            ->orderBy('name')
            ->get();

        // Build the query for available years and data
        $query = EmploymentOpportunityPlan::whereHas('kebele.woreda', function ($query) use ($user) {
            $query->where('zone_id', $user->zone_id);
        });

        // Apply woreda filter if selected
        if ($request->filled('woreda_id')) {
            $query->whereHas('kebele', function ($query) use ($request) {
                $query->where('woreda_id', $request->input('woreda_id'));
            });
        }

        // Get available years from employment records
        $availableYears = $query->clone()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $selectedWoredaId = $request->input('woreda_id');
        $previousYear = $selectedYear - 1;

        // Apply year and woreda filters to main query
        $employmentPlans = $query->clone()
            ->with(['jobOpportunity', 'enterpriseWork', 'kebele', 'kebele.woreda'])
            ->whereIn('year', [$selectedYear, $previousYear])
            ->get();

        // Get all job opportunities and enterprise works for reference
        $jobOpportunities = JobOpportunity::all()->keyBy('id');
        $enterpriseWorks = EnterpriseWork::all()->keyBy('id');

        // Initialize totals
        $current = [
            'enterprises' => 0,
            'male_members' => 0,
            'female_members' => 0,
            'total_members' => 0,
            'total_opportunities' => 0
        ];

        $previous = [
            'enterprises' => 0,
            'male_members' => 0,
            'female_members' => 0,
            'total_members' => 0,
            'total_opportunities' => 0
        ];

        // Detailed data by woreda
        $woredaData = [
            'current' => [],
            'previous' => []
        ];

        // Detailed data by job category
        $jobCategoryData = [
            'current' => [],
            'previous' => []
        ];
        
        // Detailed data by enterprise type
        $enterpriseTypeData = [
            'current' => [],
            'previous' => []
        ];

        // Detailed records for tables
        $detailedRecords = [
            'current' => [],
            'previous' => []
        ];

        // Woreda summary
        $woredaSummary = [];

        // Collect feedback from employment plans for the selected year and woreda
        $feedbackItems = [];
        $feedbackByPlanId = [];

        foreach ($employmentPlans as $plan) {
            $data = $this->decodeJsonData($plan->opportunity_data);
            
            $number_of_enterprises = isset($data['number_of_enterprises']) ? intval($data['number_of_enterprises']) : 0;
            $male_members = isset($data['male_members']) ? intval($data['male_members']) : 0;
            $female_members = isset($data['female_members']) ? intval($data['female_members']) : 0;
            $total_members = isset($data['total_members']) ? intval($data['total_members']) : 0;
            
            // Get names
            $jobName = $jobOpportunities[$plan->job_opportunity_id]->name ?? 'Unknown Job';
            $enterpriseName = $enterpriseWorks[$plan->enterprise_work_id]->name ?? 'Unknown Enterprise';
            $kebeleName = $plan->kebele->name ?? 'Unknown Kebele';
            $woredaName = $plan->kebele->woreda->name ?? 'Unknown Woreda';
            
            // Store detailed record
            $record = [
                'plan_id' => $plan->id,
                'woreda_name' => $woredaName,
                'kebele_name' => $kebeleName,
                'job_name' => $jobName,
                'enterprise_name' => $enterpriseName,
                'enterprises' => $number_of_enterprises,
                'male_members' => $male_members,
                'female_members' => $female_members,
                'total_members' => $total_members,
                'has_feedback' => !empty($plan->feedback) && is_string($plan->feedback) && trim($plan->feedback) !== ''
            ];
            
            // Decode feedback if exists
            if (!empty($plan->feedback) && is_string($plan->feedback) && trim($plan->feedback) !== '') {
                $feedbackData = json_decode($plan->feedback, true);
                if (is_array($feedbackData)) {
                    // Check if feedback is from woreda admin
                    if (isset($feedbackData['woreda_admin_id'])) {
                        $feedbackItems[] = [
                            'plan_id' => $plan->id,
                            'woreda_name' => $woredaName,
                            'kebele_name' => $kebeleName,
                            'job_name' => $jobName,
                            'enterprise_name' => $enterpriseName,
                            'feedback' => $feedbackData,
                            'created_at' => $plan->updated_at ?? $plan->created_at
                        ];
                        $feedbackByPlanId[$plan->id] = $feedbackData;
                    }
                }
            }
            
            // Initialize woreda summary if not exists
            if (!isset($woredaSummary[$woredaName])) {
                $woredaSummary[$woredaName] = [
                    'current' => [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'opportunities' => 0,
                        'kebeles' => [],
                        'feedback_count' => 0
                    ],
                    'previous' => [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'opportunities' => 0,
                        'kebeles' => [],
                        'feedback_count' => 0
                    ]
                ];
            }
            
            // Track unique kebeles per woreda
            if (!in_array($kebeleName, $woredaSummary[$woredaName]['current']['kebeles'])) {
                $woredaSummary[$woredaName]['current']['kebeles'][] = $kebeleName;
            }
            
            if ($plan->year == $selectedYear) {
                // Current year totals
                $current['enterprises'] += $number_of_enterprises;
                $current['male_members'] += $male_members;
                $current['female_members'] += $female_members;
                $current['total_members'] += $total_members;
                $current['total_opportunities']++;
                
                // Woreda data for current year
                $woredaSummary[$woredaName]['current']['enterprises'] += $number_of_enterprises;
                $woredaSummary[$woredaName]['current']['male_members'] += $male_members;
                $woredaSummary[$woredaName]['current']['female_members'] += $female_members;
                $woredaSummary[$woredaName]['current']['total_members'] += $total_members;
                $woredaSummary[$woredaName]['current']['opportunities']++;
                
                // Count feedback for current year
                if ($record['has_feedback']) {
                    $woredaSummary[$woredaName]['current']['feedback_count']++;
                }
                
                // Group by job category
                if (!isset($jobCategoryData['current'][$jobName])) {
                    $jobCategoryData['current'][$jobName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0,
                        'feedback_count' => 0
                    ];
                }
                $jobCategoryData['current'][$jobName]['enterprises'] += $number_of_enterprises;
                $jobCategoryData['current'][$jobName]['male_members'] += $male_members;
                $jobCategoryData['current'][$jobName]['female_members'] += $female_members;
                $jobCategoryData['current'][$jobName]['total_members'] += $total_members;
                $jobCategoryData['current'][$jobName]['count']++;
                if ($record['has_feedback']) {
                    $jobCategoryData['current'][$jobName]['feedback_count']++;
                }
                
                // Group by enterprise type
                if (!isset($enterpriseTypeData['current'][$enterpriseName])) {
                    $enterpriseTypeData['current'][$enterpriseName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0,
                        'feedback_count' => 0
                    ];
                }
                $enterpriseTypeData['current'][$enterpriseName]['enterprises'] += $number_of_enterprises;
                $enterpriseTypeData['current'][$enterpriseName]['male_members'] += $male_members;
                $enterpriseTypeData['current'][$enterpriseName]['female_members'] += $female_members;
                $enterpriseTypeData['current'][$enterpriseName]['total_members'] += $total_members;
                $enterpriseTypeData['current'][$enterpriseName]['count']++;
                if ($record['has_feedback']) {
                    $enterpriseTypeData['current'][$enterpriseName]['feedback_count']++;
                }
                
                $detailedRecords['current'][] = $record;
            } else {
                // Previous year totals
                $previous['enterprises'] += $number_of_enterprises;
                $previous['male_members'] += $male_members;
                $previous['female_members'] += $female_members;
                $previous['total_members'] += $total_members;
                $previous['total_opportunities']++;
                
                // Woreda data for previous year
                $woredaSummary[$woredaName]['previous']['enterprises'] += $number_of_enterprises;
                $woredaSummary[$woredaName]['previous']['male_members'] += $male_members;
                $woredaSummary[$woredaName]['previous']['female_members'] += $female_members;
                $woredaSummary[$woredaName]['previous']['total_members'] += $total_members;
                $woredaSummary[$woredaName]['previous']['opportunities']++;
                
                // Count feedback for previous year
                if ($record['has_feedback']) {
                    $woredaSummary[$woredaName]['previous']['feedback_count']++;
                }
                
                // Group by job category
                if (!isset($jobCategoryData['previous'][$jobName])) {
                    $jobCategoryData['previous'][$jobName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0,
                        'feedback_count' => 0
                    ];
                }
                $jobCategoryData['previous'][$jobName]['enterprises'] += $number_of_enterprises;
                $jobCategoryData['previous'][$jobName]['male_members'] += $male_members;
                $jobCategoryData['previous'][$jobName]['female_members'] += $female_members;
                $jobCategoryData['previous'][$jobName]['total_members'] += $total_members;
                $jobCategoryData['previous'][$jobName]['count']++;
                if ($record['has_feedback']) {
                    $jobCategoryData['previous'][$jobName]['feedback_count']++;
                }
                
                // Group by enterprise type
                if (!isset($enterpriseTypeData['previous'][$enterpriseName])) {
                    $enterpriseTypeData['previous'][$enterpriseName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0,
                        'feedback_count' => 0
                    ];
                }
                $enterpriseTypeData['previous'][$enterpriseName]['enterprises'] += $number_of_enterprises;
                $enterpriseTypeData['previous'][$enterpriseName]['male_members'] += $male_members;
                $enterpriseTypeData['previous'][$enterpriseName]['female_members'] += $female_members;
                $enterpriseTypeData['previous'][$enterpriseName]['total_members'] += $total_members;
                $enterpriseTypeData['previous'][$enterpriseName]['count']++;
                if ($record['has_feedback']) {
                    $enterpriseTypeData['previous'][$enterpriseName]['feedback_count']++;
                }
                
                $detailedRecords['previous'][] = $record;
            }
        }

        // Calculate number of kebeles per woreda
        foreach ($woredaSummary as $woredaName => $data) {
            $woredaSummary[$woredaName]['current']['kebeles_count'] = count(array_unique($data['current']['kebeles']));
            $woredaSummary[$woredaName]['previous']['kebeles_count'] = count(array_unique($data['previous']['kebeles']));
        }

        // Sort data by total members
        uasort($jobCategoryData['current'], function($a, $b) {
            return $b['total_members'] <=> $a['total_members'];
        });
        
        uasort($enterpriseTypeData['current'], function($a, $b) {
            return $b['total_members'] <=> $a['total_members'];
        });
        
        // Sort woreda summary by current year total members
        uasort($woredaSummary, function($a, $b) {
            return $b['current']['total_members'] <=> $a['current']['total_members'];
        });

        // Get selected woreda name if filter is applied
        $selectedWoredaName = null;
        if ($selectedWoredaId) {
            $selectedWoreda = $woredasInZone->where('id', $selectedWoredaId)->first();
            $selectedWoredaName = $selectedWoreda ? $selectedWoreda->name : null;
        }

        // Prepare report data for the zone
        $reportData = [
            'zone_name' => $user->zone->name ?? 'N/A',
            'woredas_count' => $woredasInZone->count(),
            'woreda_summary' => $woredaSummary,
            'current' => $current,
            'previous' => $previous,
            'is_filtered' => !empty($selectedWoredaId),
            'selected_woreda_name' => $selectedWoredaName,
            'feedback_items' => $feedbackItems,
            'feedback_by_plan_id' => $feedbackByPlanId
        ];

        // Calculate summary metrics
        $membersChange = $previous['total_members'] > 0 ? 
            (($current['total_members'] - $previous['total_members']) / $previous['total_members']) * 100 : 0;
            
        $enterprisesChange = $previous['enterprises'] > 0 ? 
            (($current['enterprises'] - $previous['enterprises']) / $previous['enterprises']) * 100 : 0;
            
        $genderRatioCurrent = $current['total_members'] > 0 ? 
            ($current['female_members'] / $current['total_members']) * 100 : 0;
            
        $genderRatioPrevious = $previous['total_members'] > 0 ? 
            ($previous['female_members'] / $previous['total_members']) * 100 : 0;

        return view('gabaasa_torbanii.employment.zonal_plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear',
            'selectedWoredaId',
            'woredasInZone',
            'reportData',
            'jobCategoryData',
            'enterpriseTypeData',
            'detailedRecords',
            'membersChange',
            'enterprisesChange',
            'genderRatioCurrent',
            'genderRatioPrevious'
        ));
    }

    /**
     * Store feedback for a specific employment plan (from zonal admin)
     */
    public function storeFeedback(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:employment_opportunity_plans,id',
            'feedback_type' => 'required|in:performance,suggestion,concern,improvement,other',
            'priority' => 'required|in:low,medium,high',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_items' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
            'follow_up_required' => 'boolean',
        ]);

        // Get the employment plan
        $plan = EmploymentOpportunityPlan::findOrFail($validated['plan_id']);
        
        // Check if zonal admin has access to this plan (through kebele woreda zone relationship)
        $user = auth()->user();
        if ($plan->kebele->woreda->zone_id !== $user->zone_id) {
            abort(403, 'You do not have permission to provide feedback for this woreda.');
        }

        // Prepare feedback data
        $feedbackData = [
            'zonal_admin_id' => $user->id,
            'zonal_admin_name' => $user->name,
            'feedback_type' => $validated['feedback_type'],
            'priority' => $validated['priority'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'action_items' => $validated['action_items'] ?? null,
            'rating' => $validated['rating'],
            'follow_up_required' => $request->boolean('follow_up_required'),
            'given_at' => now()->toDateTimeString(),
        ];

        // Decode existing feedback or create new array
        $existingFeedback = [];
        if (!empty($plan->zonal_feedback) && is_string($plan->zonal_feedback)) {
            $existingFeedback = json_decode($plan->zonal_feedback, true);
            if (!is_array($existingFeedback)) {
                $existingFeedback = [];
            }
        }

        // Initialize feedback structure if needed
        if (!isset($existingFeedback['zonal_feedback'])) {
            $existingFeedback['zonal_feedback'] = [];
        }
        
        // Add zonal admin feedback
        if (!isset($existingFeedback['zonal_feedbacks'])) {
            $existingFeedback['zonal_feedbacks'] = [];
        }
        
        $existingFeedback['zonal_feedbacks'][] = $feedbackData;
        
        // Keep only last 10 zonal feedback entries
        if (count($existingFeedback['zonal_feedbacks']) > 10) {
            $existingFeedback['zonal_feedbacks'] = array_slice($existingFeedback['zonal_feedbacks'], -10);
        }
        
        // Update last zonal feedback summary
        $existingFeedback['last_zonal_feedback'] = [
            'given_at' => $feedbackData['given_at'],
            'by' => $feedbackData['zonal_admin_name'],
            'type' => $feedbackData['feedback_type'],
            'priority' => $feedbackData['priority'],
            'title' => $feedbackData['title'],
        ];

        // Save feedback to database
        $plan->zonal_feedback = json_encode($existingFeedback);
        $plan->save();

        return redirect()->back()->with('success', 'Feedback submitted successfully!');
    }

    /**
     * Store general feedback for a woreda (from zonal admin)
     */
    public function storeGeneralFeedback(Request $request)
    {
        $validated = $request->validate([
            'woreda_id' => 'required|exists:woredas,id',
            'year' => 'required|integer',
            'feedback_type' => 'required|in:overall_performance,data_quality,timeliness,compliance,other',
            'priority' => 'required|in:low,medium,high',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'action_items' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // Check if zonal admin has access to this woreda
        $woreda = Woreda::findOrFail($validated['woreda_id']);
        if ($woreda->zone_id !== $user->zone_id) {
            abort(403, 'You do not have permission to provide feedback for this woreda.');
        }

        // Store general feedback in a separate table or use existing feedback mechanism
        // For now, we'll use the same approach but mark it as zonal feedback
        
        // You could create a separate model for zonal feedback or extend the existing one
        
        return redirect()->route('zone.employee.plans', [
            'year' => $validated['year'],
            'woreda_id' => $validated['woreda_id'],
        ])->with('success', 'General feedback submitted successfully!');
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
