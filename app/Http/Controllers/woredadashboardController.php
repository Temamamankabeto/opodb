<?php

namespace App\Http\Controllers;

use App\Models\Kebele;
use Illuminate\Http\Request;
use App\Models\LivestockPlan;
use Illuminate\Support\Facades\Auth;

class WoredadashboardController extends Controller
{
   public function livestock()
{
    $user = Auth::user();
    
    // For Woreda users, get plans from all kebeles in their woreda
    if ($user->woreda_id) {
        $plans = LivestockPlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'user'])
            ->whereHas('kebele', function($query) use ($user) {
                $query->where('woreda_id', $user->woreda_id);
            })
            ->orderBy('year', 'desc')
            ->orderBy('kebele_id')
            ->get()
            ->map(function ($plan) {
                $plan->supply_data = is_array($plan->supply_data) ? $plan->supply_data : json_decode($plan->supply_data, true);
                $plan->product_data = is_array($plan->product_data) ? $plan->product_data : json_decode($plan->product_data, true);
                return $plan;
            });

        // Get kebeles that belong to this woreda AND have livestock plans
        $kebelesWithPlans = Kebele::where('woreda_id', $user->woreda_id)
            ->whereHas('livestockPlans')
            ->orderBy('name')
            ->get();

        // Get all kebeles in the woreda (for count)
        $allKebelesInWoreda = Kebele::where('woreda_id', $user->woreda_id)->get();
        
        $currentYear = max($plans->pluck('year')->toArray()) ?? date('Y');
        
        // Calculate totals
        $totalSupplies = $plans->sum(function($plan) {
            return count($plan->supply_data ?? []);
        });
        
        $totalProducts = $plans->sum(function($plan) {
            return count($plan->product_data ?? []);
        });
        
        // Get unique types
        $uniqueSupplyTypes = $plans->flatMap(function($plan) {
            return collect($plan->supply_data ?? [])->pluck('name');
        })->unique()->count();
        
        $uniqueProductTypes = $plans->flatMap(function($plan) {
            return collect($plan->product_data ?? [])->pluck('name');
        })->unique()->count();
        
        // Group plans by year for display
        $groupedPlans = $plans->groupBy('year');

        // Calculate kebeles count for the statistics card
        $kebelesCount = $kebelesWithPlans->count();

        return view('woreda.livestock', compact(
            'plans', 
            'groupedPlans',
            'kebelesCount', // Add this
            'currentYear',
            'totalSupplies',
            'totalProducts',
            'uniqueSupplyTypes',
            'uniqueProductTypes',
            'kebelesWithPlans',
            'allKebelesInWoreda'
        ));
    }

    // Fallback for kebele users (users belonging to a specific kebele)
    $plans = LivestockPlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'user'])
        ->where('kebele_id', Auth::user()->kebele_id)
        ->orderBy('year', 'desc')
        ->get()
        ->map(function ($plan) {
            $plan->supply_data = is_array($plan->supply_data) ? $plan->supply_data : json_decode($plan->supply_data, true);
            $plan->product_data = is_array($plan->product_data) ? $plan->product_data : json_decode($plan->product_data, true);
            return $plan;
        });

    // For kebele users, set kebelesCount to 1 since they only belong to one kebele
    $kebelesCount = 1;

    return view('woreda.livestock', compact('plans', 'kebelesCount'));
}
}