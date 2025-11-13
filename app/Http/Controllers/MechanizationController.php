<?php

namespace App\Http\Controllers;

use App\Models\mechanization;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MechanizationController extends Controller
{
    // Show all mechanizations
    public function index()
    {
        $user = Auth::user();
        $mechanizations = mechanization::where('sector_id', $user->sector_id)
            ->where('kebele_id', $user->kebele_id)
            ->latest()
            ->paginate(10);

        return view('mechanizations.index', compact('mechanizations'));
    }

    // Show create form
    public function create()
    {
        return view('mechanizations.create');
    }

    // Store new mechanization
public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'mechanization_type' => 'nullable|string|max:255',
        'amount' => 'nullable|numeric',
        'unit' => 'nullable|string|max:50',
        'year' => 'required|digits:4|integer',
        'loan_name.*' => 'nullable|string|max:255',
        'loan_type.*' => 'nullable|string|max:255',
        'loan_amount.*' => 'nullable|numeric',
        'loan_duration.*' => 'nullable|integer',
        'loan_interest.*' => 'nullable|numeric',
    ]);

    $loanDetails = [];
    
    // Process loan data only if loan_type exists and has values
    if ($request->has('loan_type') && is_array($request->loan_type)) {
        foreach ($request->loan_type as $index => $type) {
            // Only create loan entry if loan_type is provided
            if (!empty(trim($type))) {
                $loanDetails[] = [
                    'loan_name' => $request->loan_name[$index] ?? null,
                    'loan_type' => $type,
                    'loan_amount' => $request->loan_amount[$index] ?? null,
                    'loan_duration' => $request->loan_duration[$index] ?? null,
                    'loan_interest' => $request->loan_interest[$index] ?? null,
                ];
            }
        }
    }

    // Get the first loan for individual columns (backward compatibility)
    $firstLoan = !empty($loanDetails) ? $loanDetails[0] : null;

    Mechanization::create([
        'name' => $validated['name'],
        'mechanization_type' => $validated['mechanization_type'] ?? null,
        'amount' => $validated['amount'] ?? null,
        'unit' => $validated['unit'] ?? null,
        'year' => $validated['year'],
        // Individual loan columns (first loan only)
        'loan_name' => $firstLoan['loan_name'] ?? null,
        'loan_type' => $firstLoan['loan_type'] ?? null,
        'loan_amount' => $firstLoan['loan_amount'] ?? null,
        'loan_duration' => $firstLoan['loan_duration'] ?? null,
        'loan_interest' => $firstLoan['loan_interest'] ?? null,
        // All loans in JSON
        'loan_details' => !empty($loanDetails) ? json_encode($loanDetails) : null,
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
    ]);

    return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization added successfully.');
}


    // Show edit form
    public function edit($id)
    {
        $mechanization = Mechanization::findOrFail($id);
        return view('mechanizations.edit', compact('mechanization'));
    }

    // Update existing record
   public function update(Request $request, $id)
{
    $mechanization = Mechanization::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'mechanization_type' => 'nullable|string|max:255',
        'amount' => 'nullable|numeric',
        'unit' => 'nullable|string|max:50',
        'year' => 'required|digits:4|integer',
        'loan_name.*' => 'nullable|string|max:255',
        'loan_type.*' => 'nullable|string|max:255',
        'loan_amount.*' => 'nullable|numeric',
        'loan_duration.*' => 'nullable|integer',
        'loan_interest.*' => 'nullable|numeric',
    ]);

    $loanDetails = [];
    
    // Process loan data
    if ($request->has('loan_type') && is_array($request->loan_type)) {
        foreach ($request->loan_type as $index => $type) {
            // Only create loan entry if loan_type is provided
            if (!empty(trim($type))) {
                $loanDetails[] = [
                    'loan_name' => $request->loan_name[$index] ?? null,
                    'loan_type' => $type,
                    'loan_amount' => $request->loan_amount[$index] ?? null,
                    'loan_duration' => $request->loan_duration[$index] ?? null,
                    'loan_interest' => $request->loan_interest[$index] ?? null,
                ];
            }
        }
    }

    // Get the first loan for individual columns (backward compatibility)
    $firstLoan = !empty($loanDetails) ? $loanDetails[0] : null;

    $updateData = [
        'name' => $validated['name'],
        'mechanization_type' => $validated['mechanization_type'] ?? null,
        'amount' => $validated['amount'] ?? null,
        'unit' => $validated['unit'] ?? null,
        'year' => $validated['year'],
        // Individual loan columns (first loan only)
        'loan_name' => $firstLoan['loan_name'] ?? null,
        'loan_type' => $firstLoan['loan_type'] ?? null,
        'loan_amount' => $firstLoan['loan_amount'] ?? null,
        'loan_duration' => $firstLoan['loan_duration'] ?? null,
        'loan_interest' => $firstLoan['loan_interest'] ?? null,
        // All loans in JSON
        'loan_details' => !empty($loanDetails) ? json_encode($loanDetails) : null,
    ];

    $mechanization->update($updateData);

    return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization updated successfully.');
}

    // Show single record
    public function show($id)
    {
        $mechanization = Mechanization::findOrFail($id);
        return view('mechanizations.show', compact('mechanization'));
    }

    // Delete
    public function destroy($id)
    {
        Mechanization::findOrFail($id)->delete();
        return redirect()->route('sector.mechanizations.index')->with('success', 'Mechanization deleted successfully.');
    }
}
