<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LivestockPlan;

class LivestockReportController extends Controller
{
   public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from livestock plans for this kebele
        $availableYears = LivestockPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch plans for current and previous year for this kebele only
        $plans = LivestockPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        $reportData = [];
        
        // Initialize arrays for supply and product data
        $supplyData = [
            'current' => [],
            'previous' => []
        ];
        
        $productData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = ['supply' => ['amount' => 0, 'items' => 0],
                    'product' => ['amount' => 0, 'items' => 0]];

        $previous = ['supply' => ['amount' => 0, 'items' => 0],
                     'product' => ['amount' => 0, 'items' => 0]];

        foreach ($plans as $plan) {
            // Safe access to arrays
            $supplyItems = is_array($plan->supply_data) ? $plan->supply_data : json_decode($plan->supply_data ?? '[]', true);
            $productItems = is_array($plan->product_data) ? $plan->product_data : json_decode($plan->product_data ?? '[]', true);

            $totalSupplyAmount = 0;
            $totalProductAmount = 0;

            // Process supply data
            foreach ($supplyItems as $item) {
                $itemName = $item['name'] ?? 'Unknown';
                $itemAmount = $item['amount'] ?? 0;
                $itemUnit = $item['unit'] ?? 'unit';
                $totalSupplyAmount += $itemAmount;
                
                // Store supply item data
                $key = $itemName . ' (' . $itemUnit . ')';
                if ($plan->year == $selectedYear) {
                    if (!isset($supplyData['current'][$key])) {
                        $supplyData['current'][$key] = 0;
                    }
                    $supplyData['current'][$key] += $itemAmount;
                } else {
                    if (!isset($supplyData['previous'][$key])) {
                        $supplyData['previous'][$key] = 0;
                    }
                    $supplyData['previous'][$key] += $itemAmount;
                }
            }

            // Process product data
            foreach ($productItems as $item) {
                $itemName = $item['name'] ?? 'Unknown';
                $itemAmount = $item['amount'] ?? 0;
                $itemUnit = $item['unit'] ?? 'unit';
                $totalProductAmount += $itemAmount;
                
                // Store product item data
                $key = $itemName . ' (' . $itemUnit . ')';
                if ($plan->year == $selectedYear) {
                    if (!isset($productData['current'][$key])) {
                        $productData['current'][$key] = 0;
                    }
                    $productData['current'][$key] += $itemAmount;
                } else {
                    if (!isset($productData['previous'][$key])) {
                        $productData['previous'][$key] = 0;
                    }
                    $productData['previous'][$key] += $itemAmount;
                }
            }

            if ($plan->year == $selectedYear) {
                $current['supply']['amount'] += $totalSupplyAmount;
                $current['supply']['items'] += count($supplyItems);
                $current['product']['amount'] += $totalProductAmount;
                $current['product']['items'] += count($productItems);
            } else {
                $previous['supply']['amount'] += $totalSupplyAmount;
                $previous['supply']['items'] += count($supplyItems);
                $previous['product']['amount'] += $totalProductAmount;
                $previous['product']['items'] += count($productItems);
            }
        }

        // Sort data by amount (descending)
        arsort($supplyData['current']);
        arsort($supplyData['previous']);
        arsort($productData['current']);
        arsort($productData['previous']);

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $currentSupplyTotal = $current['supply']['amount'];
        $currentProductTotal = $current['product']['amount'];
        $previousSupplyTotal = $previous['supply']['amount'];
        $previousProductTotal = $previous['product']['amount'];
        
        $totalCurrent = $currentSupplyTotal + $currentProductTotal;
        $totalPrevious = $previousSupplyTotal + $previousProductTotal;
        
        $supplyChange = $previousSupplyTotal > 0 ? (($currentSupplyTotal - $previousSupplyTotal) / $previousSupplyTotal) * 100 : 0;
        $productChange = $previousProductTotal > 0 ? (($currentProductTotal - $previousProductTotal) / $previousProductTotal) * 100 : 0;
        $totalChange = $totalPrevious > 0 ? (($totalCurrent - $totalPrevious) / $totalPrevious) * 100 : 0;

        return view('gabaasa_torbanii.livestock.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'supplyData',
            'productData',
            'currentSupplyTotal',
            'currentProductTotal',
            'previousSupplyTotal',
            'previousProductTotal',
            'totalCurrent',
            'totalPrevious',
            'supplyChange',
            'productChange',
            'totalChange'
        ));
    }
}
