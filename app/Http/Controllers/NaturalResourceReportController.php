<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NaturalResourcePlan;

class NaturalResourceReportController extends Controller
{
   public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from natural resource plans for this kebele
        $availableYears = NaturalResourcePlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch plans for current and previous year for this kebele only
        $plans = NaturalResourcePlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        $reportData = [];
        
        // Initialize arrays for natural resource data
        $resourceData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = ['total_amount' => 0, 'items' => 0];
        $previous = ['total_amount' => 0, 'items' => 0];

        foreach ($plans as $plan) {
            // Safe access to array
            $resourceItems = is_array($plan->natural_data) ? $plan->natural_data : json_decode($plan->natural_data ?? '[]', true);

            $totalAmount = 0;

            // Process natural resource data
            foreach ($resourceItems as $item) {
                $itemName = $item['name'] ?? 'Unknown';
                $itemAmount = floatval($item['amount'] ?? 0);
                $itemUnit = $item['unit'] ?? 'unit';
                $totalAmount += $itemAmount;
                
                // Store resource item data
                $key = $itemName . ' (' . $itemUnit . ')';
                if ($plan->year == $selectedYear) {
                    if (!isset($resourceData['current'][$key])) {
                        $resourceData['current'][$key] = 0;
                    }
                    $resourceData['current'][$key] += $itemAmount;
                } else {
                    if (!isset($resourceData['previous'][$key])) {
                        $resourceData['previous'][$key] = 0;
                    }
                    $resourceData['previous'][$key] += $itemAmount;
                }
            }

            if ($plan->year == $selectedYear) {
                $current['total_amount'] += $totalAmount;
                $current['items'] += count($resourceItems);
            } else {
                $previous['total_amount'] += $totalAmount;
                $previous['items'] += count($resourceItems);
            }
        }

        // Sort data by amount (descending)
        arsort($resourceData['current']);
        arsort($resourceData['previous']);

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $currentTotal = $current['total_amount'];
        $previousTotal = $previous['total_amount'];
        $totalChange = $previousTotal > 0 ? (($currentTotal - $previousTotal) / $previousTotal) * 100 : 0;

        return view('gabaasa_torbanii.natural_resources.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'resourceData',
            'currentTotal',
            'previousTotal',
            'totalChange'
        ));
    }
}
