<?php

namespace App\Http\Controllers;

use App\Models\CropType;
use App\Models\YearlyCropPlan;
use Illuminate\Http\Request;

class JobOpportunityController extends Controller
{
    //
    public function create()
    {
        $cropTypes = CropType::all();
        $planYears = YearlyCropPlan::select('plan_year')
            ->distinct()
            ->orderBy('plan_year', 'desc')
            ->pluck('plan_year');
        return view('jobs.new', compact('cropTypes', 'planYears'));
    }
}
