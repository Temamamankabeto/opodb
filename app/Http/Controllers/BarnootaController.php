<?php

namespace App\Http\Controllers;

use App\Models\Barnoota;
use App\Models\EducationalParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarnootaController extends Controller
{
    // ---------------------- INDEX ----------------------
   public function index()
{
    // Ensure user is authenticated
    $user = Auth::user();

    if (!$user) {
        abort(403, 'Unauthorized');
    }

    // Fetch barnoota by kebele with eager loading
    $barnootas = Barnoota::with(['region', 'zone', 'woreda', 'kebele', 'sector'])
        ->where('kebele_id', $user->kebele_id)
        ->latest()
        ->get();

    return view('barnoota.index', compact('barnootas'));
}


    // ---------------------- CREATE ----------------------
   public function create()
{
    // Fetch all EducationalParticipation data
    $allItems = EducationalParticipation::all();
    
    // Get unique student types from the model
    $studentTypes = EducationalParticipation::select('student_type')
        ->whereNotNull('student_type')
        ->where('student_type', '!=', '')
        ->distinct()
        ->pluck('student_type')
        ->toArray();
    
    // If no student types found, use defaults
    if (empty($studentTypes)) {
        $studentTypes = ['regular', 'distance', 'evening'];
    }
    
    // Separate by type (if type field exists)
    $hirmaannaaItems = $allItems->where('type', 'Hirmaannaa fi Ittifufiinsa');
    $buaaQabeessummaaItems = $allItems->where('type', 'Bu’a-qabeessummaa Barnootaa');
    
    // If no items with those types exist, use all items
    if ($hirmaannaaItems->isEmpty() && $buaaQabeessummaaItems->isEmpty()) {
        $hirmaannaaItems = $allItems;
        $buaaQabeessummaaItems = $allItems;
    }
    
    // Get years
    $currentYear = date('Y');
    $years = [];
    for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
        $years[$i] = $i;
    }
    
    return view('barnoota.create', compact(
        'hirmaannaaItems',
        'buaaQabeessummaaItems',
        'years',
        'studentTypes'
    ));
}

    // ---------------------- STORE ----------------------
    public function store(Request $request)
{
    $request->validate([
        'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        // Validate Hirmaannaa data
        'hirmaannaa' => 'required|array',
        'hirmaannaa.*.id' => 'required|exists:educational_participations,id',
        'hirmaannaa.*.amount' => 'required|numeric|min:0',
        // Validate Buaa Qabeessummaa data
        'buaa_qabeessummaa' => 'required|array',
        'buaa_qabeessummaa.*.id' => 'required|exists:educational_participations,id',
        'buaa_qabeessummaa.*.amount' => 'required|numeric|min:0',
        // Validate Dhiyeesii Nyaata data
        'dhiyeesii_nyaata' => 'required|array',
        'dhiyeesii_nyaata.*.amount' => 'required|numeric|min:0',
        'dhiyeesii_nyaata.*.source' => 'required|in:Kan manni barumsaa oomishe,Kan hawaasarraa sassaabame',
        'dhiyeesii_nyaata.*.cost' => 'required|numeric|min:0',
    ]);

    // Get location from logged-in user
    $user = Auth::user();

    // Check if a barnoota already exists for this kebele in the same year
    $existingBarnoota = Barnoota::where('year', $request->year)
        ->where('kebele_id', $user->kebele_id)
        ->first();

    if ($existingBarnoota) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'A Barnoota report already exists for Year ' . $request->year . 
                   ' in your kebele. You can edit the existing report instead.');
    }

    // Prepare Hirmaannaa data
    $hirmaannaaData = [];
    foreach ($request->hirmaannaa as $item) {
        $educationalParticipation = EducationalParticipation::find($item['id']);
        $hirmaannaaData[] = [
            'id' => $item['id'],
            'name' => $educationalParticipation->name,
            'type' => $educationalParticipation->type ?? 'Hirmaannaa fi Ittifufiinsa',
            'student_type' => $item['student_type'] ?? $educationalParticipation->student_type ?? 'regular',
            'amount' => $item['amount'],
            'unit' => 'Lakk',
        ];
    }

    // Prepare Buaa Qabeessummaa data
    $buaaQabeessummaaData = [];
    foreach ($request->buaa_qabeessummaa as $item) {
        $educationalParticipation = EducationalParticipation::find($item['id']);
        $buaaQabeessummaaData[] = [
            'id' => $item['id'],
            'name' => $educationalParticipation->name,
            'type' => $educationalParticipation->type ?? 'Bu\'a-qabeessummaa Barnootaa',
            'student_type' => $item['student_type'] ?? $educationalParticipation->student_type ?? 'regular',
            'amount' => $item['amount'],
            'unit' => 'Lakk',
        ];
    }

    // Prepare Dhiyeesii Nyaata data
    $dhiyeesiiNyaataData = [];
    foreach ($request->dhiyeesii_nyaata as $index => $item) {
        $dhiyeesiiNyaataData[] = [
            'id' => $index + 1,
            'amount' => $item['amount'],
            'unit' => 'Kunt',
            'source' => $item['source'],
            'cost' => $item['cost'],
            'cost_unit' => 'Birr',
        ];
    }

    Barnoota::create([
        'year' => $request->year,
        'Hirmaannaa_itti_fufiinsa' => json_encode($hirmaannaaData),
        'Bu_aa_qabeessummaa' => json_encode($buaaQabeessummaaData),
        'Dhiyeesii_nyaata_barattootaa' => json_encode($dhiyeesiiNyaataData),
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
        'created_by' => $user->id,
        'status' => 'pending',
    ]);

    return redirect()->route('sector.barnoota.index')
                     ->with('success', 'Barnoota Created Successfully');
}

    // ---------------------- EDIT ----------------------
   public function edit($id)
{
    // Ensure user can only edit their own location's barnootas
    $barnoota = Barnoota::where('id', $id)
        ->where('region_id', Auth::user()->region_id)
        ->where('zone_id', Auth::user()->zone_id)
        ->where('woreda_id', Auth::user()->woreda_id)
        ->where('kebele_id', Auth::user()->kebele_id)
        
        ->firstOrFail();

    // Fetch all EducationalParticipation data
    $allItems = EducationalParticipation::all();
    
    // Get unique student types from the model
    $studentTypes = EducationalParticipation::select('student_type')
        ->whereNotNull('student_type')
        ->where('student_type', '!=', '')
        ->distinct()
        ->pluck('student_type')
        ->toArray();
    
    // If no student types found, use defaults
    if (empty($studentTypes)) {
        $studentTypes = ['regular', 'distance', 'evening'];
    }
    
    // Separate by type (if type field exists)
    $hirmaannaaItems = $allItems->where('type', 'Hirmaannaa fi Ittifufiinsa');
    $buaaQabeessummaaItems = $allItems->where('type', 'Bu’a-qabeessummaa Barnootaa');
    
    // If no items with those types exist, use all items
    if ($hirmaannaaItems->isEmpty() && $buaaQabeessummaaItems->isEmpty()) {
        $hirmaannaaItems = $allItems;
        $buaaQabeessummaaItems = $allItems;
    }

    // Get years
    $currentYear = date('Y');
    $years = [];
    for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
        $years[$i] = $i;
    }

    // Decode JSON data
    $hirmaannaaData = json_decode($barnoota->Hirmaannaa_itti_fufiinsa, true) ?? [];
    $buaaQabeessummaaData = json_decode($barnoota->Bu_aa_qabeessummaa, true) ?? [];
    $dhiyeesiiNyaataData = json_decode($barnoota->Dhiyeesii_nyaata_barattootaa, true) ?? [];

    return view('barnoota.edit', compact(
        'barnoota',
        'hirmaannaaItems',
        'buaaQabeessummaaItems',
        'years',
        'studentTypes',
        'hirmaannaaData',
        'buaaQabeessummaaData',
        'dhiyeesiiNyaataData'
    ));
}

   public function update(Request $request, $id)
{
    // Ensure user can only update their own location's barnootas
    $barnoota = Barnoota::where('id', $id)
        ->where('region_id', Auth::user()->region_id)
        ->where('zone_id', Auth::user()->zone_id)
        ->where('woreda_id', Auth::user()->woreda_id)
        ->where('kebele_id', Auth::user()->kebele_id)
        ->where('sector_id', Auth::user()->sector_id)
        ->firstOrFail();

    $request->validate([
        'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
        // Validate Hirmaannaa data
        'hirmaannaa' => 'required|array',
        'hirmaannaa.*.id' => 'required|exists:educational_participations,id',
        'hirmaannaa.*.amount' => 'required|numeric|min:0',
        // Validate Buaa Qabeessummaa data
        'buaa_qabeessummaa' => 'required|array',
        'buaa_qabeessummaa.*.id' => 'required|exists:educational_participations,id',
        'buaa_qabeessummaa.*.amount' => 'required|numeric|min:0',
        // Validate Dhiyeesii Nyaata data
        'dhiyeesii_nyaata' => 'required|array',
        'dhiyeesii_nyaata.*.amount' => 'required|numeric|min:0',
        'dhiyeesii_nyaata.*.source' => 'required|in:Kan manni barumsaa oomishe,Kan hawaasarraa sassaabame',
        'dhiyeesii_nyaata.*.cost' => 'required|numeric|min:0',
    ]);

    // Get location from logged-in user
    $user = Auth::user();

    // Check if another barnoota already exists for this kebele in the same year (excluding current one)
    $existingBarnoota = Barnoota::where('year', $request->year)
        ->where('kebele_id', $user->kebele_id)
        ->where('id', '!=', $id) // Exclude current record
        ->first();

    if ($existingBarnoota) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Another Barnoota report already exists for Year ' . $request->year . 
                   ' in your kebele. Please delete the other report first or choose a different year.');
    }

    // Prepare Hirmaannaa data
    $hirmaannaaData = [];
    foreach ($request->hirmaannaa as $item) {
        $educationalParticipation = EducationalParticipation::find($item['id']);
        $hirmaannaaData[] = [
            'id' => $item['id'],
            'name' => $educationalParticipation->name,
            'type' => $educationalParticipation->type ?? 'Hirmaannaa fi Ittifufiinsa',
            'student_type' => $item['student_type'] ?? $educationalParticipation->student_type ?? 'regular',
            'amount' => $item['amount'],
            'unit' => 'Lakk',
        ];
    }

    // Prepare Buaa Qabeessummaa data
    $buaaQabeessummaaData = [];
    foreach ($request->buaa_qabeessummaa as $item) {
        $educationalParticipation = EducationalParticipation::find($item['id']);
        $buaaQabeessummaaData[] = [
            'id' => $item['id'],
            'name' => $educationalParticipation->name,
            'type' => $educationalParticipation->type ?? 'Bu\'a-qabeessummaa Barnootaa',
            'student_type' => $item['student_type'] ?? $educationalParticipation->student_type ?? 'regular',
            'amount' => $item['amount'],
            'unit' => 'Lakk',
        ];
    }

    // Prepare Dhiyeesii Nyaata data
    $dhiyeesiiNyaataData = [];
    foreach ($request->dhiyeesii_nyaata as $index => $item) {
        $dhiyeesiiNyaataData[] = [
            'id' => $index + 1,
            'amount' => $item['amount'],
            'unit' => 'Kunt',
            'source' => $item['source'],
            'cost' => $item['cost'],
            'cost_unit' => 'Birr',
        ];
    }

    $barnoota->update([
        'year' => $request->year,
        'Hirmaannaa_itti_fufiinsa' => json_encode($hirmaannaaData),
        'Bu_aa_qabeessummaa' => json_encode($buaaQabeessummaaData),
        'Dhiyeesii_nyaata_barattootaa' => json_encode($dhiyeesiiNyaataData),
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('sector.barnoota.index')
                     ->with('success', 'Barnoota Updated Successfully');
}

    // ---------------------- DELETE ----------------------
    public function destroy($id)
    {
        // Ensure user can only delete their own location's barnootas
        $barnoota = Barnoota::where('id', $id)
            ->where('region_id', Auth::user()->region_id)
            ->where('zone_id', Auth::user()->zone_id)
            ->where('woreda_id', Auth::user()->woreda_id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->where('sector_id', Auth::user()->sector_id)
            ->firstOrFail();

        $barnoota->delete();

        return redirect()->route('sector.barnoota.index')
                         ->with('success', 'Barnoota Deleted Successfully');
    }

    // ---------------------- SHOW ----------------------
    public function show($id)
    {
        // Ensure user can only view their own location's barnootas
        $barnoota = Barnoota::where('id', $id)
            ->where('region_id', Auth::user()->region_id)
            ->where('zone_id', Auth::user()->zone_id)
            ->where('woreda_id', Auth::user()->woreda_id)
            ->where('kebele_id', Auth::user()->kebele_id)
            ->where('sector_id', Auth::user()->sector_id)
            ->firstOrFail();

        return view('barnoota.show', compact('barnoota'));
    }
}