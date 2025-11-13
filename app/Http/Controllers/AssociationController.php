<?php

namespace App\Http\Controllers;

use App\Models\Association;
use App\Models\Federation;
use App\Models\Union;
use App\Models\Zone;
use App\Models\Woreda;
use App\Models\Kebele;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssociationController extends Controller
{
    public function index()
    {
        $associations = Association::with(['federation', 'union', 'zone', 'woreda', 'kebele'])
            ->latest()
            ->paginate(10);

        return view('associations.index', compact('associations'));
    }

    public function create()
    {
        $federations = Federation::all();
        $unions = Union::all();
        $services = Service::where('is_active', true)->get();
        return view('associations.create', compact('federations', 'unions', 'services'));
    }

    public function store(Request $request)
    {
        // First validate basic fields including service_data as string
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'association_type' => 'required|string|max:100',
            'federation_id' => 'required|exists:federations,id',
            'union_id' => 'required|exists:unions,id',
            'service_data' => 'required|string',
        ]);

        // Decode the service_data JSON string
        $serviceData = json_decode($request->service_data, true);

        if (!is_array($serviceData) || empty($serviceData)) {
            return back()->withErrors(['service_data' => 'Please select at least one service.'])->withInput();
        }

        // Manually validate the service data
        $errors = [];
        foreach ($serviceData as $index => $service) {
            if (empty($service['id']) || !is_numeric($service['id'])) {
                $errors['service_data'] = "Invalid service ID selected.";
                break;
            }
            if (empty($service['name'])) {
                $errors['service_data'] = "Invalid service name selected.";
                break;
            }
            
            // Check if service exists
            $serviceExists = Service::where('id', $service['id'])
                ->where('name', $service['name'])
                ->exists();
                
            if (!$serviceExists) {
                $errors['service_data'] = "Invalid service selected: " . $service['name'];
                break;
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $user = Auth::user();

        Association::create([
            'name' => $request->name,
            'association_type' => $request->association_type,
            'federation_id' => $request->federation_id,
            'union_id' => $request->union_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'service_data' => $serviceData,
        ]);

        return redirect()->route('associations.index')
            ->with('success', 'Association created successfully!');
    }

    public function edit($id)
    {
        $association = Association::findOrFail($id);
        $federations = Federation::all();
        $unions = Union::where('federation_id', $association->federation_id)->get();
        $services = Service::where('is_active', true)->get();

        return view('associations.edit', compact('association', 'federations', 'unions', 'services'));
    }

    public function update(Request $request, $id)
    {
        // First validate basic fields including service_data as string
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'association_type' => 'required|string|max:100',
            'federation_id' => 'required|exists:federations,id',
            'union_id' => 'required|exists:unions,id',
            'service_data' => 'required|string',
        ]);

        // Decode the service_data JSON string
        $serviceData = json_decode($request->service_data, true);

        if (!is_array($serviceData) || empty($serviceData)) {
            return back()->withErrors(['service_data' => 'Please select at least one service.'])->withInput();
        }

        // Manually validate the service data
        $errors = [];
        foreach ($serviceData as $index => $service) {
            if (empty($service['id']) || !is_numeric($service['id'])) {
                $errors['service_data'] = "Invalid service ID selected.";
                break;
            }
            if (empty($service['name'])) {
                $errors['service_data'] = "Invalid service name selected.";
                break;
            }
            
            // Check if service exists
            $serviceExists = Service::where('id', $service['id'])
                ->where('name', $service['name'])
                ->exists();
                
            if (!$serviceExists) {
                $errors['service_data'] = "Invalid service selected: " . $service['name'];
                break;
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        $association = Association::findOrFail($id);
        $user = Auth::user();

        $association->update([
            'name' => $request->name,
            'association_type' => $request->association_type,
            'federation_id' => $request->federation_id,
            'union_id' => $request->union_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'service_data' => $serviceData,
        ]);

        return redirect()->route('associations.index')
            ->with('success', 'Association updated successfully!');
    }

    public function destroy($id)
    {
        $association = Association::findOrFail($id);
        $association->delete();

        return redirect()->route('associations.index')
            ->with('success', 'Association deleted successfully!');
    }

    public function getUnions($federation_id)
    {
        $unions = Union::where('federation_id', $federation_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($unions);
    }

    public function getWoredas($zone_id)
    {
        return response()->json(Woreda::where('zone_id', $zone_id)->get());
    }

    public function getKebeles($woreda_id)
    {
        return response()->json(Kebele::where('woreda_id', $woreda_id)->get());
    }

    public function getServices()
    {
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'description')
            ->orderBy('name')
            ->get();
        return response()->json($services);
    }
}