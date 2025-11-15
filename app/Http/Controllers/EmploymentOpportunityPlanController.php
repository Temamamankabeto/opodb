<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnterpriseWork;
use App\Models\JobOpportunity;
use Illuminate\Support\Facades\Auth;
use App\Models\EmploymentOpportunityPlan;

class EmploymentOpportunityPlanController extends Controller
{
     public function index()
    {
        $plans = EmploymentOpportunityPlan::with([
                'jobOpportunity', 
                'enterpriseWork',
                'sector',
                'kebele',
                'createdBy'
            ])
            ->where('sector_id', Auth::user()->sector_id)
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sector.employment-opportunity-plans.index', compact('plans'));
    }

    public function indexkebele()
    {
        $plans = EmploymentOpportunityPlan::with([
                'jobOpportunity', 
                'enterpriseWork',
                'sector',
                'kebele',
                'createdBy'
            ])
            ->where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('sector.employment-opportunity-plans.index', compact('plans'));
    }
    

    public function create()
    {
        // Get job opportunities and enterprise works independently
        $jobOpportunities = JobOpportunity::all();
        $enterpriseWorks = EnterpriseWork::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.employment-opportunity-plans.create', compact('jobOpportunities', 'enterpriseWorks', 'years'));
    }

    public function store(Request $request)
    {
        // Validate basic fields
        $validated = $request->validate([
            'year' => 'required|string',
            'opportunity_data' => 'required|string',
        ]);

        // Decode JSON data
        $opportunityData = json_decode($request->opportunity_data, true);

        // Validate opportunity data format
        if (!is_array($opportunityData)) {
            return back()->withErrors([
                'opportunity_data' => 'Invalid data format for opportunity data.'
            ])->withInput();
        }

        // Custom validation for opportunity data
        $errors = [];
        foreach ($opportunityData as $index => $data) {
            if (empty($data['job_opportunity_id'])) {
                $errors["opportunity_data.{$index}.job_opportunity_id"] = "Job opportunity is required.";
            }
            
            if (!empty($data['enterprise_works'])) {
                foreach ($data['enterprise_works'] as $workIndex => $work) {
                    if (empty($work['enterprise_work_id'])) {
                        $errors["opportunity_data.{$index}.enterprise_works.{$workIndex}.enterprise_work_id"] = "Enterprise work is required.";
                    }
                    if (empty($work['number_of_enterprises']) || !is_numeric($work['number_of_enterprises'])) {
                        $errors["opportunity_data.{$index}.enterprise_works.{$workIndex}.number_of_enterprises"] = "Number of enterprises is required and must be numeric.";
                    }
                    if (empty($work['male_members']) || !is_numeric($work['male_members'])) {
                        $errors["opportunity_data.{$index}.enterprise_works.{$workIndex}.male_members"] = "Male members is required and must be numeric.";
                    }
                    if (empty($work['female_members']) || !is_numeric($work['female_members'])) {
                        $errors["opportunity_data.{$index}.enterprise_works.{$workIndex}.female_members"] = "Female members is required and must be numeric.";
                    }
                    
                    // Calculate and validate total members
                    $totalMembers = ($work['male_members'] ?? 0) + ($work['female_members'] ?? 0);
                    if ($totalMembers <= 0) {
                        $errors["opportunity_data.{$index}.enterprise_works.{$workIndex}.total_members"] = "Total members must be greater than 0.";
                    }
                }
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $user = Auth::user();
        $createdCount = 0;

        // Create records for each job opportunity and enterprise work combination
        foreach ($opportunityData as $data) {
            $jobOpportunityId = $data['job_opportunity_id'];
            
            if (!empty($data['enterprise_works'])) {
                foreach ($data['enterprise_works'] as $work) {
                    $enterpriseWorkId = $work['enterprise_work_id'];

                    // Check if plan already exists for this year, job opportunity, enterprise work and sector
                    $existingPlan = EmploymentOpportunityPlan::where('year', $request->year)
                        ->where('job_opportunity_id', $jobOpportunityId)
                        ->where('enterprise_work_id', $enterpriseWorkId)
                        ->where('sector_id', $user->sector_id)
                        ->where('kebele_id', $user->kebele_id)
                        ->first();

                    if ($existingPlan) {
                        continue; // Skip if already exists
                    }

                    // Create new plan
                    EmploymentOpportunityPlan::create([
                        'year' => $request->year,
                        'job_opportunity_id' => $jobOpportunityId,
                        'enterprise_work_id' => $enterpriseWorkId,
                        'opportunity_data' => [
                            'number_of_enterprises' => $work['number_of_enterprises'],
                            'male_members' => $work['male_members'],
                            'female_members' => $work['female_members'],
                            'total_members' => $work['male_members'] + $work['female_members']
                        ],
                        'sector_id' => $user->sector_id,
                        'kebele_id' => $user->kebele_id,
                        'woreda_id' => $user->woreda_id,
                        'zone_id' => $user->zone_id,
                        'region_id' => $user->region_id,
                        'created_by' => $user->id,
                    ]);

                    $createdCount++;
                }
            }
        }

        if ($createdCount > 0) {
            return redirect()->route('sector.employment-opportunity-plans.index')
                ->with('success', "Employment opportunity plans created successfully! {$createdCount} new plan(s) created.");
        } else {
            return redirect()->route('sector.employment-opportunity-plans.index')
                ->with('warning', "No new plans were created. All selected opportunities already have data for the selected year.");
        }
    }

    public function edit($id)
    {
        $plan = EmploymentOpportunityPlan::with(['jobOpportunity', 'enterpriseWork'])
            ->where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $jobOpportunities = JobOpportunity::all();
        $enterpriseWorks = EnterpriseWork::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.employment-opportunity-plans.edit', compact('plan', 'jobOpportunities', 'enterpriseWorks', 'years'));
    }

    public function update(Request $request, $id)
    {
        $plan = EmploymentOpportunityPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        // Validate basic fields
        $validated = $request->validate([
            'year' => 'required|string',
            'job_opportunity_id' => 'required|exists:job_opportunities,id',
            'enterprise_work_id' => 'required|exists:enterprise_works,id',
            'number_of_enterprises' => 'required|integer|min:0',
            'male_members' => 'required|integer|min:0',
            'female_members' => 'required|integer|min:0',
        ]);

        // Calculate total members
        $totalMembers = $request->male_members + $request->female_members;

        if ($totalMembers <= 0) {
            return back()->withErrors([
                'total_members' => 'Total members must be greater than 0.'
            ])->withInput();
        }

        // Update plan
        $plan->update([
            'year' => $request->year,
            'job_opportunity_id' => $request->job_opportunity_id,
            'enterprise_work_id' => $request->enterprise_work_id,
            'opportunity_data' => [
                'number_of_enterprises' => $request->number_of_enterprises,
                'male_members' => $request->male_members,
                'female_members' => $request->female_members,
                'total_members' => $totalMembers
            ],
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.employment-opportunity-plans.index')
            ->with('success', 'Employment opportunity plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = EmploymentOpportunityPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.employment-opportunity-plans.index')
            ->with('success', 'Employment opportunity plan deleted successfully!');
    }

    public function checkExisting(Request $request)
    {
        $year = $request->query('year');
        $user = Auth::user();

        $existingPlans = EmploymentOpportunityPlan::with(['jobOpportunity', 'enterpriseWork'])
            ->where('year', $year)
            ->where('sector_id', $user->sector_id)
            ->where('kebele_id', $user->kebele_id)
            ->get();

        return response()->json([
            'exists' => $existingPlans->count() > 0,
            'plans' => $existingPlans->map(function($plan) {
                return [
                    'job_opportunity_id' => $plan->job_opportunity_id,
                    'enterprise_work_id' => $plan->enterprise_work_id,
                    'job_opportunity' => [
                        'name' => $plan->jobOpportunity->name
                    ],
                    'enterprise_work' => [
                        'name' => $plan->enterpriseWork->name
                    ]
                ];
            })
        ]);
    }

    // Get enterprise works for a job opportunity (if needed for dynamic loading)
    public function getEnterpriseWorks($jobOpportunityId)
    {
        // Since there's no direct relationship, return all active enterprise works
        $enterpriseWorks = EnterpriseWork::where('status', 'active')->get();
        
        return response()->json($enterpriseWorks);
    }


    public function approve(Request $request, $id)
{
    // Only allow kebele admin to approve
    if (auth()->user()->role_id !== 4) {
        return redirect()->route('kebele.employment-opportunity-plans.indexkebele')
            ->with('error', 'You are not authorized to approve plans.');
    }

    $plan = EmploymentOpportunityPlan::findOrFail($id);

    $validated = $request->validate([
        'approval_description' => 'nullable|string|max:1000'
    ]);

    $plan->update([
        'status' => 'approved',
        'approval_description' => $request->approval_description,
        'approved_at' => now(),
        'approved_by' => auth()->id()
    ]);

    return redirect()->route('employment-opportunity-plans.indexkebele')
        ->with('success', 'Employment opportunity plan approved successfully!');
}
}
