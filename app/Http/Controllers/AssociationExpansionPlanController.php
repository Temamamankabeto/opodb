<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\AssociationExpansionPlan;
use App\Models\AssociationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssociationExpansionPlanController extends Controller
{
    public function index()
    {
        $plans = AssociationExpansionPlan::with(['association', 'sector', 'kebele', 'woreda', 'zone', 'region', 'createdBy'])
            ->where('kebele_id', Auth::user()->kebele_id)
            ->orderBy('year', 'desc')
            ->get()
            ->map(function ($plan) {
                $plan->expansion_data = is_array($plan->expansion_data) ? $plan->expansion_data : json_decode($plan->expansion_data, true);
                return $plan;
            });

        return view('sector.association-expansion.index', compact('plans'));
    }

    public function create()
    {
        $associations = AssociationType::all();
            
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.association-expansion.create', compact('associations', 'years'));
    }

    public function store(Request $request)
    {
        // Validate basic fields and ensure expansion_data is a string (JSON)
        $validated = $request->validate([
            'year' => 'required|string',
            'expansion_data' => 'required|string',
        ]);

        // Decode JSON data
        $expansionData = json_decode($request->expansion_data, true);

        if (!is_array($expansionData)) {
            return back()->withErrors(['expansion_data' => 'Invalid expansion data format.'])->withInput();
        }

        // Custom validation for expansion data
        $errors = [];
        foreach ($expansionData as $index => $data) {
            // Common validation for all types
            if (empty($data['association_type_id'])) {
                $errors["expansion_data.{$index}.association_type_id"] = "Association type ID is required.";
            }
            if (empty($data['association_name'])) {
                $errors["expansion_data.{$index}.association_name"] = "Association name is required.";
            }
            if (empty($data['association_type'])) {
                $errors["expansion_data.{$index}.association_type"] = "Association type is required.";
            }

            // Common validation: number_of_number is required for ALL types
            if (empty($data['number_of_number']) || !is_numeric($data['number_of_number'])) {
                $errors["expansion_data.{$index}.number_of_number"] = "Number of number is required.";
            }

            // Conditional validation based on association type
            if ($data['association_type_id'] == 1) {
                // Type 1: Validate ALL additional fields
                if (empty($data['number_of_members']) || !is_numeric($data['number_of_members'])) {
                    $errors["expansion_data.{$index}.number_of_members"] = "Number of members is required for this association type.";
                }
                if (empty($data['total_capital']) || !is_numeric($data['total_capital'])) {
                    $errors["expansion_data.{$index}.total_capital"] = "Total capital is required for this association type.";
                }
                if (empty($data['fertilizer_loans_taken']) || !is_numeric($data['fertilizer_loans_taken'])) {
                    $errors["expansion_data.{$index}.fertilizer_loans_taken"] = "Fertilizer loans taken is required for this association type.";
                }
                if (empty($data['fertilizer_loan_repayment']) || !is_numeric($data['fertilizer_loan_repayment'])) {
                    $errors["expansion_data.{$index}.fertilizer_loan_repayment"] = "Fertilizer loan repayment is required for this association type.";
                }
            }
            // For other types, only number_of_number is required (already validated above)
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $user = Auth::user();

        // Prevent duplicate year for same kebele and sector
        $yearExists = AssociationExpansionPlan::where('year', $request->year)
            ->where('kebele_id', $user->kebele_id)
            // ->where('sector_id', $user->sector_id)
            ->exists();

        if ($yearExists) {
            return back()->withErrors([
                'year' => "An association expansion plan already exists for year {$request->year} in your kebele and sector. Please edit the existing plan instead of creating a new one."
            ])->withInput();
        }

        // Create expansion plan
        AssociationExpansionPlan::create([
            'year' => $request->year,
            'expansion_data' => $expansionData,
            // 'sector_id' => $user->sector_id,
            'kebele_id' => $user->kebele_id,
            'woreda_id' => $user->woreda_id,
            'zone_id' => $user->zone_id,
            'region_id' => $user->region_id,
            'created_by' => $user->id,
        ]);

        return redirect()->route('sector.association-expansion-plans.index')
            ->with('success', 'Association expansion plan created successfully!');
    }

    public function edit($id)
    {
        $plan = AssociationExpansionPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $associations = AssociationType::all();
            
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.association-expansion.edit', compact('plan', 'associations', 'years'));
    }

    public function update(Request $request, $id)
    {
        $expansionPlan = AssociationExpansionPlan::findOrFail($id);

        // Validate basic fields
        $validated = $request->validate([
            'year' => 'required|string',
            'expansion_data' => 'required|string',
        ]);

        // Decode JSON data
        $expansionData = json_decode($request->expansion_data, true);

        if (!is_array($expansionData)) {
            return back()->withErrors(['expansion_data' => 'Invalid expansion data format.'])->withInput();
        }

        // Custom validation for expansion data
        $errors = [];
        foreach ($expansionData as $index => $data) {
            // Common validation for all types
            if (empty($data['association_type_id'])) {
                $errors["expansion_data.{$index}.association_type_id"] = "Association type ID is required.";
            }
            if (empty($data['association_name'])) {
                $errors["expansion_data.{$index}.association_name"] = "Association name is required.";
            }
            if (empty($data['association_type'])) {
                $errors["expansion_data.{$index}.association_type"] = "Association type is required.";
            }

            // Common validation: number_of_number is required for ALL types
            if (empty($data['number_of_number']) || !is_numeric($data['number_of_number'])) {
                $errors["expansion_data.{$index}.number_of_number"] = "Number of number is required.";
            }

            // Conditional validation based on association type
            if ($data['association_type_id'] == 1) {
                // Type 1: Validate ALL additional fields
                if (empty($data['number_of_members']) || !is_numeric($data['number_of_members'])) {
                    $errors["expansion_data.{$index}.number_of_members"] = "Number of members is required for this association type.";
                }
                if (empty($data['total_capital']) || !is_numeric($data['total_capital'])) {
                    $errors["expansion_data.{$index}.total_capital"] = "Total capital is required for this association type.";
                }
                if (empty($data['fertilizer_loans_taken']) || !is_numeric($data['fertilizer_loans_taken'])) {
                    $errors["expansion_data.{$index}.fertilizer_loans_taken"] = "Fertilizer loans taken is required for this association type.";
                }
                if (empty($data['fertilizer_loan_repayment']) || !is_numeric($data['fertilizer_loan_repayment'])) {
                    $errors["expansion_data.{$index}.fertilizer_loan_repayment"] = "Fertilizer loan repayment is required for this association type.";
                }
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Update plan
        $expansionPlan->update([
            'year' => $request->year,
            'expansion_data' => $expansionData,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('sector.association-expansion-plans.index')
            ->with('success', 'Association expansion plan updated successfully!');
    }

    public function destroy($id)
    {
        $plan = AssociationExpansionPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.association-expansion-plans.index')->with('success', 'Association expansion plan deleted successfully!');
    }
}