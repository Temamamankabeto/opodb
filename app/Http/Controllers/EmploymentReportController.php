<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use Illuminate\Http\Request;
use App\Models\EnterpriseWork;
use App\Models\JobOpportunity;
use App\Models\EmploymentOpportunityPlan;

class EmploymentReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from employment records for this kebele
        $availableYears = EmploymentOpportunityPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch employment records for current and previous year for this kebele only
        $employmentPlans = EmploymentOpportunityPlan::with(['jobOpportunity', 'enterpriseWork'])
                        ->where('kebele_id', $user->kebele_id)
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
            'job_categories' => [],
            'enterprise_types' => [],
            'total_opportunities' => 0
        ];

        $previous = [
            'enterprises' => 0,
            'male_members' => 0,
            'female_members' => 0,
            'total_members' => 0,
            'job_categories' => [],
            'enterprise_types' => [],
            'total_opportunities' => 0
        ];

        // Detailed data by job category and enterprise type
        $jobCategoryData = [
            'current' => [],
            'previous' => []
        ];
        
        $enterpriseTypeData = [
            'current' => [],
            'previous' => []
        ];

        // Detailed records for tables
        $detailedRecords = [
            'current' => [],
            'previous' => []
        ];

        foreach ($employmentPlans as $plan) {
            $data = $this->decodeJsonData($plan->opportunity_data);
            
            $number_of_enterprises = isset($data['number_of_enterprises']) ? intval($data['number_of_enterprises']) : 0;
            $male_members = isset($data['male_members']) ? intval($data['male_members']) : 0;
            $female_members = isset($data['female_members']) ? intval($data['female_members']) : 0;
            $total_members = isset($data['total_members']) ? intval($data['total_members']) : 0;
            
            // Get job and enterprise names
            $jobName = $jobOpportunities[$plan->job_opportunity_id]->name ?? 'Unknown Job';
            $enterpriseName = $enterpriseWorks[$plan->enterprise_work_id]->name ?? 'Unknown Enterprise';
            
            // Store detailed record
            $record = [
                'job_name' => $jobName,
                'enterprise_name' => $enterpriseName,
                'enterprises' => $number_of_enterprises,
                'male_members' => $male_members,
                'female_members' => $female_members,
                'total_members' => $total_members
            ];
            
            if ($plan->year == $selectedYear) {
                $current['enterprises'] += $number_of_enterprises;
                $current['male_members'] += $male_members;
                $current['female_members'] += $female_members;
                $current['total_members'] += $total_members;
                $current['total_opportunities']++;
                
                // Group by job category
                if (!isset($jobCategoryData['current'][$jobName])) {
                    $jobCategoryData['current'][$jobName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0
                    ];
                }
                $jobCategoryData['current'][$jobName]['enterprises'] += $number_of_enterprises;
                $jobCategoryData['current'][$jobName]['male_members'] += $male_members;
                $jobCategoryData['current'][$jobName]['female_members'] += $female_members;
                $jobCategoryData['current'][$jobName]['total_members'] += $total_members;
                $jobCategoryData['current'][$jobName]['count']++;
                
                // Group by enterprise type
                if (!isset($enterpriseTypeData['current'][$enterpriseName])) {
                    $enterpriseTypeData['current'][$enterpriseName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0
                    ];
                }
                $enterpriseTypeData['current'][$enterpriseName]['enterprises'] += $number_of_enterprises;
                $enterpriseTypeData['current'][$enterpriseName]['male_members'] += $male_members;
                $enterpriseTypeData['current'][$enterpriseName]['female_members'] += $female_members;
                $enterpriseTypeData['current'][$enterpriseName]['total_members'] += $total_members;
                $enterpriseTypeData['current'][$enterpriseName]['count']++;
                
                $detailedRecords['current'][] = $record;
            } else {
                $previous['enterprises'] += $number_of_enterprises;
                $previous['male_members'] += $male_members;
                $previous['female_members'] += $female_members;
                $previous['total_members'] += $total_members;
                $previous['total_opportunities']++;
                
                // Group by job category
                if (!isset($jobCategoryData['previous'][$jobName])) {
                    $jobCategoryData['previous'][$jobName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0
                    ];
                }
                $jobCategoryData['previous'][$jobName]['enterprises'] += $number_of_enterprises;
                $jobCategoryData['previous'][$jobName]['male_members'] += $male_members;
                $jobCategoryData['previous'][$jobName]['female_members'] += $female_members;
                $jobCategoryData['previous'][$jobName]['total_members'] += $total_members;
                $jobCategoryData['previous'][$jobName]['count']++;
                
                // Group by enterprise type
                if (!isset($enterpriseTypeData['previous'][$enterpriseName])) {
                    $enterpriseTypeData['previous'][$enterpriseName] = [
                        'enterprises' => 0,
                        'male_members' => 0,
                        'female_members' => 0,
                        'total_members' => 0,
                        'count' => 0
                    ];
                }
                $enterpriseTypeData['previous'][$enterpriseName]['enterprises'] += $number_of_enterprises;
                $enterpriseTypeData['previous'][$enterpriseName]['male_members'] += $male_members;
                $enterpriseTypeData['previous'][$enterpriseName]['female_members'] += $female_members;
                $enterpriseTypeData['previous'][$enterpriseName]['total_members'] += $total_members;
                $enterpriseTypeData['previous'][$enterpriseName]['count']++;
                
                $detailedRecords['previous'][] = $record;
            }
        }

        // Sort data by total members
        uasort($jobCategoryData['current'], function($a, $b) {
            return $b['total_members'] <=> $a['total_members'];
        });
        
        uasort($enterpriseTypeData['current'], function($a, $b) {
            return $b['total_members'] <=> $a['total_members'];
        });

        // Prepare report data for the kebele
        $reportData = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
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

        return view('gabaasa_torbanii.employment.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
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


public function woredaplans(Request $request)
{
    $user = auth()->user();
    
    // Ensure the user has a woreda assigned
    if (!$user->woreda_id) {
        abort(403, "No woreda assigned to this user.");
    }

    // Get all kebeles in this woreda for the filter
    $kebelesInWoreda = Kebele::where('woreda_id', $user->woreda_id)
        ->orderBy('name')
        ->get();

    // Build the query for available years and data
    $query = EmploymentOpportunityPlan::whereHas('kebele', function ($query) use ($user) {
        $query->where('woreda_id', $user->woreda_id);
    });

    // Apply kebele filter if selected
    if ($request->filled('kebele_id')) {
        $query->where('kebele_id', $request->input('kebele_id'));
    }

    // Get available years from employment records
    $availableYears = $query->clone()
        ->orderBy('year', 'desc')
        ->pluck('year')
        ->unique();

    // Selected year from request or latest
    $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
    $selectedKebeleId = $request->input('kebele_id');
    $previousYear = $selectedYear - 1;

    // Apply year and kebele filters to main query
    $employmentPlans = $query->clone()
        ->with(['jobOpportunity', 'enterpriseWork', 'kebele'])
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

    // Detailed data by kebele (for summary even when filtered)
    $kebeleData = [
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

    // Kebele summary
    $kebeleSummary = [];

    // Collect feedback from employment plans for the selected year and kebele
    $feedbackItems = [];
    $feedbackByPlanId = [];

    foreach ($employmentPlans as $plan) {
        $data = $this->wdecodeJsonData($plan->opportunity_data);
        
        $number_of_enterprises = isset($data['number_of_enterprises']) ? intval($data['number_of_enterprises']) : 0;
        $male_members = isset($data['male_members']) ? intval($data['male_members']) : 0;
        $female_members = isset($data['female_members']) ? intval($data['female_members']) : 0;
        $total_members = isset($data['total_members']) ? intval($data['total_members']) : 0;
        
        // Get names
        $jobName = $jobOpportunities[$plan->job_opportunity_id]->name ?? 'Unknown Job';
        $enterpriseName = $enterpriseWorks[$plan->enterprise_work_id]->name ?? 'Unknown Enterprise';
        $kebeleName = $kebelesInWoreda->where('id', $plan->kebele_id)->first()->name ?? 'Unknown Kebele';
        
        // Store detailed record
        $record = [
            'plan_id' => $plan->id,
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
                // Add basic feedback info
                $feedbackItems[] = [
                    'plan_id' => $plan->id,
                    'kebele_name' => $kebeleName,
                    'job_name' => $jobName,
                    'enterprise_name' => $enterpriseName,
                    'feedback' => $feedbackData,
                    'created_at' => $plan->updated_at ?? $plan->created_at
                ];
                $feedbackByPlanId[$plan->id] = $feedbackData;
            }
        }
        
        // Initialize kebele summary if not exists
        if (!isset($kebeleSummary[$kebeleName])) {
            $kebeleSummary[$kebeleName] = [
                'current' => [
                    'enterprises' => 0,
                    'male_members' => 0,
                    'female_members' => 0,
                    'total_members' => 0,
                    'opportunities' => 0,
                    'feedback_count' => 0
                ],
                'previous' => [
                    'enterprises' => 0,
                    'male_members' => 0,
                    'female_members' => 0,
                    'total_members' => 0,
                    'opportunities' => 0,
                    'feedback_count' => 0
                ]
            ];
        }
        
        if ($plan->year == $selectedYear) {
            // Current year totals
            $current['enterprises'] += $number_of_enterprises;
            $current['male_members'] += $male_members;
            $current['female_members'] += $female_members;
            $current['total_members'] += $total_members;
            $current['total_opportunities']++;
            
            // Kebele data for current year
            $kebeleSummary[$kebeleName]['current']['enterprises'] += $number_of_enterprises;
            $kebeleSummary[$kebeleName]['current']['male_members'] += $male_members;
            $kebeleSummary[$kebeleName]['current']['female_members'] += $female_members;
            $kebeleSummary[$kebeleName]['current']['total_members'] += $total_members;
            $kebeleSummary[$kebeleName]['current']['opportunities']++;
            
            // Count feedback for current year
            if ($record['has_feedback']) {
                $kebeleSummary[$kebeleName]['current']['feedback_count']++;
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
            
            // Kebele data for previous year
            $kebeleSummary[$kebeleName]['previous']['enterprises'] += $number_of_enterprises;
            $kebeleSummary[$kebeleName]['previous']['male_members'] += $male_members;
            $kebeleSummary[$kebeleName]['previous']['female_members'] += $female_members;
            $kebeleSummary[$kebeleName]['previous']['total_members'] += $total_members;
            $kebeleSummary[$kebeleName]['previous']['opportunities']++;
            
            // Count feedback for previous year
            if ($record['has_feedback']) {
                $kebeleSummary[$kebeleName]['previous']['feedback_count']++;
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

    // Sort data by total members
    uasort($jobCategoryData['current'], function($a, $b) {
        return $b['total_members'] <=> $a['total_members'];
    });
    
    uasort($enterpriseTypeData['current'], function($a, $b) {
        return $b['total_members'] <=> $a['total_members'];
    });
    
    // Sort kebele summary by current year total members
    uasort($kebeleSummary, function($a, $b) {
        return $b['current']['total_members'] <=> $a['current']['total_members'];
    });

    // Get selected kebele name if filter is applied
    $selectedKebeleName = null;
    if ($selectedKebeleId) {
        $selectedKebele = $kebelesInWoreda->where('id', $selectedKebeleId)->first();
        $selectedKebeleName = $selectedKebele ? $selectedKebele->name : null;
    }

    // Prepare report data for the woreda
    $reportData = [
        'woreda_name' => $user->woreda->name ?? 'N/A',
        'kebeles_count' => $kebelesInWoreda->count(),
        'kebele_summary' => $kebeleSummary,
        'current' => $current,
        'previous' => $previous,
        'is_filtered' => !empty($selectedKebeleId),
        'selected_kebele_name' => $selectedKebeleName,
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

    return view('gabaasa_torbanii.employment.woreda_plans_report', compact(
        'availableYears', 
        'selectedYear', 
        'previousYear',
        'selectedKebeleId',
        'kebelesInWoreda',
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
 * Store feedback for a specific employment plan
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
    
    // Check if woreda admin has access to this plan (through kebele woreda relationship)
    $user = auth()->user();
    if ($plan->kebele->woreda_id !== $user->woreda_id) {
        abort(403, 'You do not have permission to provide feedback for this kebele.');
    }

    // Prepare feedback data
    $feedbackData = [
        'woreda_admin_id' => $user->id,
        'woreda_admin_name' => $user->name,
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
    if (!empty($plan->woreafeedback) && is_string($plan->woreafeedback)) {
        $existingFeedback = json_decode($plan->woreafeedback, true);
        if (!is_array($existingFeedback)) {
            $existingFeedback = [];
        }
    }

    // Add new feedback to array (limit to last 10 feedback entries)
    if (!isset($existingFeedback['woreafeedbacks'])) {
        $existingFeedback['woreafeedbacks'] = [];
    }

    $existingFeedback['woreafeedbacks'][] = $feedbackData;
    
    // Keep only last 10 feedback entries
    if (count($existingFeedback['woreafeedbacks']) > 10) {
        $existingFeedback['woreafeedbacks'] = array_slice($existingFeedback['woreafeedbacks'], -10);
    }
    
    // Update last feedback summary
    $existingFeedback['last_feedback'] = [
        'given_at' => $feedbackData['given_at'],
        'by' => $feedbackData['woreda_admin_name'],
        'type' => $feedbackData['feedback_type'],
        'priority' => $feedbackData['priority'],
        'title' => $feedbackData['title'],
    ];

    // Save feedback to database
    $plan->woredafeedback = json_encode($existingFeedback);
    $plan->save();

    return redirect()->back()->with('success', 'Feedback submitted successfully!');
}


/**
 * Store general feedback for a kebele (separate from plan-specific feedback)
 */
public function storeGeneralFeedback(Request $request)
{
    $validated = $request->validate([
        'kebele_id' => 'required|exists:kebeles,id',
        'year' => 'required|integer',
        'feedback_type' => 'required|in:overall_performance,data_quality,timeliness,compliance,other',
        'priority' => 'required|in:low,medium,high',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'action_items' => 'nullable|string',
    ]);

    $user = auth()->user();
    
    // Check if woreda admin has access to this kebele
    $kebele = Kebele::findOrFail($validated['kebele_id']);
    if ($kebele->woreda_id !== $user->woreda_id) {
        abort(403, 'You do not have permission to provide feedback for this kebele.');
    }

    // Store general feedback in a separate table or use the same approach
    // For now, we'll use the same approach as plan-specific feedback but store in a different way
    
    // You could create a new model for general feedback, or use a different approach
    // For example, store in employment_opportunity_plans with a special job_opportunity_id
    
    return redirect()->route('woreda.plans', [
        'year' => $validated['year'],
        'kebele_id' => $validated['kebele_id'],
    ])->with('success', 'General feedback submitted successfully!');
}
// Rest of the controller remains the same...

/**
 * Helper method to decode JSON data
 */
private function wdecodeJsonData($data)
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
