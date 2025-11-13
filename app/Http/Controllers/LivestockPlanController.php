<?php

namespace App\Http\Controllers;

use App\Models\LivestockPlan;
use App\Models\LivestockSupply;
use App\Models\LivestockProduct;
use App\Models\KebeleSectors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $plans = LivestockPlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'user'])
        ->where('sector_id', Auth::user()->sector_id)
        ->orderBy('year', 'desc')
        ->get()
        ->map(function ($plan) {
            $plan->supply_data = is_array($plan->supply_data) ? $plan->supply_data : json_decode($plan->supply_data, true);
            $plan->product_data = is_array($plan->product_data) ? $plan->product_data : json_decode($plan->product_data, true);
            return $plan;
        });

    return view('sector.livestock-plans.index', compact('plans'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supplies = LivestockSupply::all();
        $products = LivestockProduct::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.livestock-plans.create', compact('supplies', 'products', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // Decode JSON data
    $supplyData = json_decode($request->supply_data, true);
    $productData = json_decode($request->product_data ?? '[]', true);

    if (!is_array($supplyData)) {
        return back()->withErrors(['supply_data' => 'Invalid supply data format.'])->withInput();
    }

    $request->merge([
        'supply_data' => $supplyData,
        'product_data' => $productData,
    ]);

    // Validate inputs
    $request->validate([
        'year' => 'required|string',
        'supply_data' => 'required|array|min:1',
        'supply_data.*.name' => 'required|string',
        'supply_data.*.amount' => 'required|numeric|min:0',
        'supply_data.*.unit' => 'required|string',
        'product_data' => 'sometimes|array',
        'product_data.*.name' => 'required_with:product_data|string',
        'product_data.*.amount' => 'required_with:product_data|numeric|min:0',
        'product_data.*.unit' => 'required_with:product_data|string',
    ]);

    $user = Auth::user();

    // 🚫 Prevent creating duplicate year for same kebele
    $yearExists = LivestockPlan::where('year', $request->year)
        ->where('kebele_id', $user->kebele_id)
        ->where('sector_id', $user->sector_id)
        ->exists();

    if ($yearExists) {
        return back()->withErrors([
            'year' => "A livestock plan already exists for year {$request->year} in your kebele and sector. Please edit the existing plan instead of creating a new one."
        ])->withInput();
    }

    // 🧩 Add supply/product IDs
    foreach ($supplyData as &$supply) {
        $model = LivestockSupply::where('name', $supply['name'])->first();
        $supply['id'] = $model?->id;
    }

    foreach ($productData as &$product) {
        $model = LivestockProduct::where('name', $product['name'])->first();
        $product['id'] = $model?->id;
    }

    // ✅ Create new plan
    LivestockPlan::create([
        'year' => $request->year,
        'supply_data' => $supplyData,
        'product_data' => $productData,
        'sector_id' => $user->sector_id,
        'kebele_id' => $user->kebele_id,
        'woreda_id' => $user->woreda_id,
        'zone_id' => $user->zone_id,
        'region_id' => $user->region_id,
        'created_by' => $user->id,
    ]);

    return redirect()->route('sector.livestock_plans.index')
        ->with('success', 'Livestock plan created successfully!');
}



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $plan = LivestockPlan::with(['sector', 'kebele', 'woreda', 'zone', 'region', 'user'])
            ->where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        return view('sector.livestock-plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plan = LivestockPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $supplies = LivestockSupply::all();
        $products = LivestockProduct::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.livestock-plans.edit', compact('plan', 'supplies', 'products', 'years'));
    }
       public function editproduct($id)
    {
        $plan = LivestockPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $supplies = LivestockSupply::all();
        $products = LivestockProduct::all();
        $currentYear = \App\Helpers\EthiopianYear::now();
        $years = range($currentYear - 2, $currentYear + 3);

        return view('sector.livestock-plans.editproduct', compact('plan', 'supplies', 'products', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $livestockPlan = LivestockPlan::findOrFail($id);

    // Decode JSON data
    $supplyData = json_decode($request->supply_data, true);
    $productData = json_decode($request->product_data ?? '[]', true);

    if (!is_array($supplyData)) {
        return back()->withErrors(['supply_data' => 'Invalid supply data format.'])->withInput();
    }

    $request->merge([
        'supply_data' => $supplyData,
        'product_data' => $productData,
    ]);

    // Validate inputs
    $request->validate([
        'year' => 'required|string',
        'supply_data' => 'required|array|min:1',
        'supply_data.*.name' => 'required|string',
        'supply_data.*.amount' => 'required|numeric|min:0',
        'supply_data.*.unit' => 'required|string',
        'product_data' => 'sometimes|array',
        'product_data.*.name' => 'required_with:product_data|string',
        'product_data.*.amount' => 'required_with:product_data|numeric|min:0',
        'product_data.*.unit' => 'required_with:product_data|string',
    ]);

    // Add IDs
    foreach ($supplyData as &$supply) {
        $model = LivestockSupply::where('name', $supply['name'])->first();
        $supply['id'] = $model?->id;
    }

    foreach ($productData as &$product) {
        $model = LivestockProduct::where('name', $product['name'])->first();
        $product['id'] = $model?->id;
    }

    // Update plan
    $livestockPlan->update([
        'year' => $request->year,
        'supply_data' => $supplyData,
        'product_data' => $productData,
        'updated_by' => Auth::id(),
    ]);

    return redirect()->route('sector.livestock_plans.index')
        ->with('success', 'Livestock plan updated successfully!');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = LivestockPlan::where('sector_id', Auth::user()->sector_id)
            ->findOrFail($id);

        $plan->delete();

        return redirect()->route('sector.livestock_plans.index')->with('success', 'Livestock plan deleted successfully!');
    }
}