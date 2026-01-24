<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssociationExpansionPlan;

class AssociationExpansionReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from association expansion plans for this kebele
        $availableYears = AssociationExpansionPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch plans for current and previous year for this kebele only
        $plans = AssociationExpansionPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        $reportData = [];
        
        // Initialize arrays for association data by type
        $associationData = [
            'current' => [],
            'previous' => []
        ];

        // Initialize totals for the kebele
        $current = [
            'total_associations' => 0,
            'total_members' => 0,
            'total_capital' => 0,
            'total_loans_taken' => 0,
            'total_loan_repayment' => 0,
            'types' => 0
        ];

        $previous = [
            'total_associations' => 0,
            'total_members' => 0,
            'total_capital' => 0,
            'total_loans_taken' => 0,
            'total_loan_repayment' => 0,
            'types' => 0
        ];

        // Track unique association types
        $associationTypes = [];

        foreach ($plans as $plan) {
            // Safe access to array
            $expansionItems = is_array($plan->expansion_data) ? $plan->expansion_data : json_decode($plan->expansion_data ?? '[]', true);

            $yearlyAssociations = 0;
            $yearlyMembers = 0;
            $yearlyCapital = 0;
            $yearlyLoans = 0;
            $yearlyRepayments = 0;

            // Process association expansion data
            foreach ($expansionItems as $item) {
                $typeId = $item['association_type_id'] ?? 'unknown';
                $typeName = $item['association_type'] ?? 'Unknown';
                $associationName = $item['association_name'] ?? 'Unknown';
                $number = intval($item['number'] ?? $item['number_of_number'] ?? 0);
                $members = intval($item['number_of_members'] ?? 0);
                $capital = intval($item['total_capital'] ?? 0);
                $loans = intval($item['fertilizer_loans_taken'] ?? 0);
                $repayments = intval($item['fertilizer_loan_repayment'] ?? 0);

                // Store unique association type
                if (!in_array($typeName, $associationTypes)) {
                    $associationTypes[] = $typeName;
                }

                // Store association data by type
                $key = $typeName;
                if ($plan->year == $selectedYear) {
                    if (!isset($associationData['current'][$key])) {
                        $associationData['current'][$key] = [
                            'associations' => 0,
                            'members' => 0,
                            'capital' => 0,
                            'loans' => 0,
                            'repayments' => 0
                        ];
                    }
                    $associationData['current'][$key]['associations'] += $number;
                    $associationData['current'][$key]['members'] += $members;
                    $associationData['current'][$key]['capital'] += $capital;
                    $associationData['current'][$key]['loans'] += $loans;
                    $associationData['current'][$key]['repayments'] += $repayments;
                } else {
                    if (!isset($associationData['previous'][$key])) {
                        $associationData['previous'][$key] = [
                            'associations' => 0,
                            'members' => 0,
                            'capital' => 0,
                            'loans' => 0,
                            'repayments' => 0
                        ];
                    }
                    $associationData['previous'][$key]['associations'] += $number;
                    $associationData['previous'][$key]['members'] += $members;
                    $associationData['previous'][$key]['capital'] += $capital;
                    $associationData['previous'][$key]['loans'] += $loans;
                    $associationData['previous'][$key]['repayments'] += $repayments;
                }

                $yearlyAssociations += $number;
                $yearlyMembers += $members;
                $yearlyCapital += $capital;
                $yearlyLoans += $loans;
                $yearlyRepayments += $repayments;
            }

            if ($plan->year == $selectedYear) {
                $current['total_associations'] += $yearlyAssociations;
                $current['total_members'] += $yearlyMembers;
                $current['total_capital'] += $yearlyCapital;
                $current['total_loans_taken'] += $yearlyLoans;
                $current['total_loan_repayment'] += $yearlyRepayments;
                $current['types'] = count(array_unique(array_merge(array_keys($associationData['current']), $associationTypes)));
            } else {
                $previous['total_associations'] += $yearlyAssociations;
                $previous['total_members'] += $yearlyMembers;
                $previous['total_capital'] += $yearlyCapital;
                $previous['total_loans_taken'] += $yearlyLoans;
                $previous['total_loan_repayment'] += $yearlyRepayments;
                $previous['types'] = count(array_unique(array_merge(array_keys($associationData['previous']), $associationTypes)));
            }
        }

        // Sort association types by number of associations (descending)
        uasort($associationData['current'], function($a, $b) {
            return $b['associations'] - $a['associations'];
        });

        uasort($associationData['previous'], function($a, $b) {
            return $b['associations'] - $a['associations'];
        });

        // Only one kebele row
        $reportData[] = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary data
        $totalAssociationsChange = $previous['total_associations'] > 0 ? 
            (($current['total_associations'] - $previous['total_associations']) / $previous['total_associations']) * 100 : 0;
        
        $totalMembersChange = $previous['total_members'] > 0 ? 
            (($current['total_members'] - $previous['total_members']) / $previous['total_members']) * 100 : 0;
        
        $totalCapitalChange = $previous['total_capital'] > 0 ? 
            (($current['total_capital'] - $previous['total_capital']) / $previous['total_capital']) * 100 : 0;

        return view('gabaasa_torbanii.association_expansion.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'associationData',
            'totalAssociationsChange',
            'totalMembersChange',
            'totalCapitalChange'
        ));
    }
}
