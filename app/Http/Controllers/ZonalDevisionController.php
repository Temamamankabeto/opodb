<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\YealrlyCropPlan;
use App\Models\Zonaldevision;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZonalDevisionController extends Controller
{
    public function index()
    {
        $devisions = ZonalDevision::with(['zone', 'crop'])->get();
        return view('dashboard.zonal-devision.index', compact('devisions'));
    }
    public function index1()
    {
        $devisions = ZonalDevision::with(['zone', 'crop'])->get();
        return view('dashboard.zonal-devision.index1', compact('devisions'));
    }

    public function create()
    {
        $zones = Zone::all();
        $crops = Crop::all();
        $years = YealrlyCropPlan::select('production_year')->distinct()->orderByDesc('production_year')->pluck('production_year');

        return view('dashboard.zonal-devision.create', compact('zones', 'crops','years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'crop_id' => 'required|exists:crops,id',
            'total_land_area_plan' => 'required',
            'total_production_plan' => 'required',
            'ammount_of_land_for_zone' => 'required',
            'ammount_of_prucuction_for' => 'required',
            'productivity' => 'required',
        ]);

        Zonaldevision::create($request->all());
        return redirect()->route('zonal_devisions.index')->with('success', 'Data saved successfully');
    }

    public function edit($id)
    {
        $devision = ZonalDevision::findOrFail($id);
        $zones = Zone::all();
        $crops = Crop::all();
        return view('zonal_devisions.edit', compact('devision', 'zones', 'crops'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'crop_id' => 'required|exists:crops,id',
            'total_land_area_plan' => 'required',
            'total_production_plan' => 'required',
            'ammount_of_land_for_zone' => 'required',
            'ammount_of_prucuction_for' => 'required',
            'productivity' => 'required',
            'production_year' => 'required',
        ]);

        $devision = ZonalDevision::findOrFail($id);
        $devision->update($request->all());

        return redirect()->route('zonal_devisions.index')->with('success', 'Data updated successfully');
    }

    public function destroy($id)
    {
        $devision = ZonalDevision::findOrFail($id);
        $devision->delete();
        return redirect()->route('zonal_devisions.index')->with('success', 'Data deleted successfully');
    }
    public function fetchCropPlan(Request $request)
    {
        $cropId = $request->crop_id;
        $year = $request->production_year;
    
        $plan = YealrlyCropPlan::where('crop_id', $cropId)
            ->where('production_year', $year)
            ->first();
    
        return response()->json([
            'total_land_area_plan' => $plan->total_land_area_plan ?? '',
            'total_production_plan' => $plan->total_production_plan ?? '',
        ]);
    }

}
