<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KebeleAssetPlan;

class KebeleAssetReportController extends Controller
{
    public function plans(Request $request)
    {
        $user = auth()->user();
        
        // Ensure the user has a kebele assigned
        if (!$user->kebele_id) {
            abort(403, "No kebele assigned to this user.");
        }

        // Get available years from kebele asset records for this kebele
        $availableYears = KebeleAssetPlan::where('kebele_id', $user->kebele_id)
                        ->orderBy('year', 'desc')
                        ->pluck('year')
                        ->unique();

        // Selected year from request or latest
        $selectedYear = $request->input('year', $availableYears->first() ?? now()->year);
        $previousYear = $selectedYear - 1;

        // Fetch kebele asset records for current and previous year for this kebele only
        $assetPlans = KebeleAssetPlan::where('kebele_id', $user->kebele_id)
                        ->whereIn('year', [$selectedYear, $previousYear])
                        ->get();

        // Initialize totals
        $current = [
            'government_revenue' => [
                'amount' => 0,
                'description' => 'Galii Mootummaa'
            ],
            'public_contribution' => [
                'members' => 0,
                'membership_fee' => 0,
                'contribution_kind' => 0,
                'monetary_contribution' => 0,
                'description' => 'Buusaa Gonofaa'
            ],
            'system_assets' => [
                'transferred_persons' => 0,
                'daily_served_persons' => 0,
                'description' => 'Gaachena Sirnaa'
            ],
            'community_services' => [
                'amount' => 0,
                'description' => 'Hojiiwwan Tajaajila Lammummaa'
            ],
            'loan_receipt' => [
                'received_this_year' => 0,
                'returned_this_year' => 0,
                'not_returned' => 0,
                'description' => 'Fudhannaa Liqi'
            ],
            'bank_saving' => [
                'family_amount' => 0,
                'association_amount' => 0,
                'description' => 'Qusannaa Baankii Siinqee'
            ],
            'family_development' => [
                'quantity' => 0,
                'cost' => 0,
                'description' => 'Badhaadhina Maatii Socho\'e'
            ],
            'total_amount' => 0,
            'total_members' => 0,
            'total_persons_served' => 0
        ];

        $previous = [
            'government_revenue' => [
                'amount' => 0,
                'description' => 'Galii Mootummaa'
            ],
            'public_contribution' => [
                'members' => 0,
                'membership_fee' => 0,
                'contribution_kind' => 0,
                'monetary_contribution' => 0,
                'description' => 'Buusaa Gonofaa'
            ],
            'system_assets' => [
                'transferred_persons' => 0,
                'daily_served_persons' => 0,
                'description' => 'Gaachena Sirnaa'
            ],
            'community_services' => [
                'amount' => 0,
                'description' => 'Hojiiwwan Tajaajila Lammummaa'
            ],
            'loan_receipt' => [
                'received_this_year' => 0,
                'returned_this_year' => 0,
                'not_returned' => 0,
                'description' => 'Fudhannaa Liqi'
            ],
            'bank_saving' => [
                'family_amount' => 0,
                'association_amount' => 0,
                'description' => 'Qusannaa Baankii Siinqee'
            ],
            'family_development' => [
                'quantity' => 0,
                'cost' => 0,
                'description' => 'Badhaadhina Maatii Socho\'e'
            ],
            'total_amount' => 0,
            'total_members' => 0,
            'total_persons_served' => 0
        ];

        // Detailed data for charts
        $activityData = [
            'current' => [],
            'previous' => []
        ];

        foreach ($assetPlans as $plan) {
            $activities = $this->decodeJsonData($plan->kebele_asset_plan);
            
            foreach ($activities as $activity) {
                $name = $activity['name'] ?? '';
                
                // Store activity data for charts
                $key = $name;
                
                // Categorize and sum data
                if ($plan->year == $selectedYear) {
                    // Government Revenue
                    if (strpos($name, 'Galii Mootummaa') !== false) {
                        $amount = isset($activity['amount']) ? floatval($activity['amount']) : 
                                 (isset($activity['quantity']) ? floatval($activity['quantity']) : 0);
                        $current['government_revenue']['amount'] += $amount;
                        $current['total_amount'] += $amount;
                    }
                    // Public Contribution
                    elseif (strpos($name, 'Buusaa Gonofaa') !== false) {
                        $current['public_contribution']['members'] += isset($activity['members']) ? intval($activity['members']) : 0;
                        $current['public_contribution']['membership_fee'] += isset($activity['membership_fee']) ? floatval($activity['membership_fee']) : 0;
                        $current['public_contribution']['contribution_kind'] += isset($activity['contribution_kind']) ? floatval($activity['contribution_kind']) : 0;
                        $current['public_contribution']['monetary_contribution'] += isset($activity['monetary_contribution']) ? floatval($activity['monetary_contribution']) : 0;
                        $current['total_members'] += isset($activity['members']) ? intval($activity['members']) : 0;
                        $current['total_amount'] += isset($activity['monetary_contribution']) ? floatval($activity['monetary_contribution']) : 0;
                    }
                    // System Assets
                    elseif (strpos($name, 'Gaachena Sirnaa') !== false) {
                        $current['system_assets']['transferred_persons'] += isset($activity['transferred_persons']) ? intval($activity['transferred_persons']) : 0;
                        $current['system_assets']['daily_served_persons'] += isset($activity['daily_served_persons']) ? intval($activity['daily_served_persons']) : 0;
                        $current['total_persons_served'] += isset($activity['daily_served_persons']) ? intval($activity['daily_served_persons']) : 0;
                    }
                    // Community Services
                    elseif (strpos($name, 'Hojiiwwan Tajaajila Lammummaa') !== false) {
                        $amount = isset($activity['amount']) ? floatval($activity['amount']) : 
                                 (isset($activity['quantity']) ? floatval($activity['quantity']) : 0);
                        $current['community_services']['amount'] += $amount;
                        $current['total_amount'] += $amount;
                    }
                    // Loan Receipt
                    elseif (strpos($name, 'Fudhannaa Liqi') !== false) {
                        $current['loan_receipt']['received_this_year'] += isset($activity['received_this_year']) ? floatval($activity['received_this_year']) : 0;
                        $current['loan_receipt']['returned_this_year'] += isset($activity['returned_this_year']) ? floatval($activity['returned_this_year']) : 0;
                        $current['loan_receipt']['not_returned'] += isset($activity['not_returned']) ? floatval($activity['not_returned']) : 0;
                        $current['total_amount'] += isset($activity['received_this_year']) ? floatval($activity['received_this_year']) : 0;
                    }
                    // Bank Saving
                    elseif (strpos($name, 'Qusannaa Baankii Siinqee') !== false) {
                        $current['bank_saving']['family_amount'] += isset($activity['family_amount']) ? floatval($activity['family_amount']) : 0;
                        $current['bank_saving']['association_amount'] += isset($activity['association_amount']) ? floatval($activity['association_amount']) : 0;
                        $current['total_amount'] += isset($activity['family_amount']) ? floatval($activity['family_amount']) : 0;
                        $current['total_amount'] += isset($activity['association_amount']) ? floatval($activity['association_amount']) : 0;
                    }
                    // Family Development
                    elseif (strpos($name, 'Badhaadhina Maatii Socho\'e') !== false) {
                        $current['family_development']['quantity'] += isset($activity['quantity']) ? intval($activity['quantity']) : 0;
                        $current['family_development']['cost'] += isset($activity['cost']) ? floatval($activity['cost']) : 0;
                        $current['total_amount'] += isset($activity['cost']) ? floatval($activity['cost']) : 0;
                    }
                } else {
                    // Previous year data - same categorization
                    if (strpos($name, 'Galii Mootummaa') !== false) {
                        $amount = isset($activity['amount']) ? floatval($activity['amount']) : 
                                 (isset($activity['quantity']) ? floatval($activity['quantity']) : 0);
                        $previous['government_revenue']['amount'] += $amount;
                        $previous['total_amount'] += $amount;
                    }
                    elseif (strpos($name, 'Buusaa Gonofaa') !== false) {
                        $previous['public_contribution']['members'] += isset($activity['members']) ? intval($activity['members']) : 0;
                        $previous['public_contribution']['membership_fee'] += isset($activity['membership_fee']) ? floatval($activity['membership_fee']) : 0;
                        $previous['public_contribution']['contribution_kind'] += isset($activity['contribution_kind']) ? floatval($activity['contribution_kind']) : 0;
                        $previous['public_contribution']['monetary_contribution'] += isset($activity['monetary_contribution']) ? floatval($activity['monetary_contribution']) : 0;
                        $previous['total_members'] += isset($activity['members']) ? intval($activity['members']) : 0;
                        $previous['total_amount'] += isset($activity['monetary_contribution']) ? floatval($activity['monetary_contribution']) : 0;
                    }
                    elseif (strpos($name, 'Gaachena Sirnaa') !== false) {
                        $previous['system_assets']['transferred_persons'] += isset($activity['transferred_persons']) ? intval($activity['transferred_persons']) : 0;
                        $previous['system_assets']['daily_served_persons'] += isset($activity['daily_served_persons']) ? intval($activity['daily_served_persons']) : 0;
                        $previous['total_persons_served'] += isset($activity['daily_served_persons']) ? intval($activity['daily_served_persons']) : 0;
                    }
                    elseif (strpos($name, 'Hojiiwwan Tajaajila Lammummaa') !== false) {
                        $amount = isset($activity['amount']) ? floatval($activity['amount']) : 
                                 (isset($activity['quantity']) ? floatval($activity['quantity']) : 0);
                        $previous['community_services']['amount'] += $amount;
                        $previous['total_amount'] += $amount;
                    }
                    elseif (strpos($name, 'Fudhannaa Liqi') !== false) {
                        $previous['loan_receipt']['received_this_year'] += isset($activity['received_this_year']) ? floatval($activity['received_this_year']) : 0;
                        $previous['loan_receipt']['returned_this_year'] += isset($activity['returned_this_year']) ? floatval($activity['returned_this_year']) : 0;
                        $previous['loan_receipt']['not_returned'] += isset($activity['not_returned']) ? floatval($activity['not_returned']) : 0;
                        $previous['total_amount'] += isset($activity['received_this_year']) ? floatval($activity['received_this_year']) : 0;
                    }
                    elseif (strpos($name, 'Qusannaa Baankii Siinqee') !== false) {
                        $previous['bank_saving']['family_amount'] += isset($activity['family_amount']) ? floatval($activity['family_amount']) : 0;
                        $previous['bank_saving']['association_amount'] += isset($activity['association_amount']) ? floatval($activity['association_amount']) : 0;
                        $previous['total_amount'] += isset($activity['family_amount']) ? floatval($activity['family_amount']) : 0;
                        $previous['total_amount'] += isset($activity['association_amount']) ? floatval($activity['association_amount']) : 0;
                    }
                    elseif (strpos($name, 'Badhaadhina Maatii Socho\'e') !== false) {
                        $previous['family_development']['quantity'] += isset($activity['quantity']) ? intval($activity['quantity']) : 0;
                        $previous['family_development']['cost'] += isset($activity['cost']) ? floatval($activity['cost']) : 0;
                        $previous['total_amount'] += isset($activity['cost']) ? floatval($activity['cost']) : 0;
                    }
                }
            }
        }

        // Prepare report data for the kebele
        $reportData = [
            'kebele_name' => $user->kebele->name ?? 'N/A',
            'current' => $current,
            'previous' => $previous,
        ];

        // Calculate summary metrics
        $totalChange = $previous['total_amount'] > 0 ? 
            (($current['total_amount'] - $previous['total_amount']) / $previous['total_amount']) * 100 : 0;
            
        $membersChange = $previous['total_members'] > 0 ? 
            (($current['total_members'] - $previous['total_members']) / $previous['total_members']) * 100 : 0;

        return view('gabaasa_torbanii.kebele_assets.plans_report', compact(
            'availableYears', 
            'selectedYear', 
            'previousYear', 
            'reportData',
            'totalChange',
            'membersChange'
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
