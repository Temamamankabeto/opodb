<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropPlanAchievement;
use App\Models\Qonna;
use Illuminate\Http\Request;

class QonnaController extends Controller
{
    public function index()
    {
        $qonnas = Qonna::latest()->get();
        return view('qonnas.index', compact('qonnas'));
    }

    public function create()
    {
        $cropTypes = CropPlanAchievement::all();
        return view('qonnas.create',compact('cropTypes'));
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'haala_qonnaa' => 'nullable|string',
            'lafa_qotame' => 'nullable|numeric',
            'sadarkaa_qonnaa' => 'nullable|string',
            'traaktara_hirmaate' => 'nullable|integer',
            'lafa_tiraakta_tokkoon_qotame' => 'nullable|numeric',

            // new fields
            'rakkoo_muudate' => 'nullable|string',
            'furmaata' => 'nullable|string',
            'carraa_argame' => 'nullable|string',
            'deeggarsa' => 'nullable|string',
            'rakkoo_deddeebie' => 'nullable|string',
            'baayina_dhiiraa' => 'nullable|string',
            'baayina_durbaa' => 'nullable|string',
            'baayina_woliigalaa' => 'nullable|string',
            'dhabbii_dhiiraa' => 'nullable|string',
            'dhabbii_durbaa' => 'nullable|string',
            'dhabbii_woliigalaa' => 'nullable|string',
            'yeroo_dhiiraa' => 'nullable|string',
            'yeroo_durbaa' => 'nullable|string',
            'yeroo_woliigalaa' => 'nullable|string',
            'dargaggoota_dhiiraa' => 'nullable|string',
            'dargaggoota_durbaa' => 'nullable|string',
            'dargaggoota_woliigalaa' => 'nullable|string',
        ]);

        // Create a new Qonna record in the database
        Qonna::create($validated);

        // Redirect to the Qonna index page with a success message
        return redirect()->route('qonnas.index')->with('success', 'Qonna record added successfully.');
    }

    public function edit($id)
    {
        $qonna = Qonna::findOrFail($id);
        return view('qonnas.edit', compact('qonna'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'haala_qonnaa' => 'nullable|string',
            'lafa_qotame' => 'nullable|numeric',
            'sadarkaa_qonnaa' => 'nullable|string',
            'traaktara_hirmaate' => 'nullable|integer',
            'lafa_tiraakta_tokkoon_qotame' => 'nullable|numeric',

            // new fields
            'rakkoo_muudate' => 'nullable|string',
            'furmaata' => 'nullable|string',
            'carraa_argame' => 'nullable|string',
            'deeggarsa' => 'nullable|string',
            'rakkoo_deddeebie' => 'nullable|string',
            'baayina_dhiiraa' => 'nullable|string',
            'baayina_durbaa' => 'nullable|string',
            'baayina_woliigalaa' => 'nullable|string',
            'dhabbii_dhiiraa' => 'nullable|string',
            'dhabbii_durbaa' => 'nullable|string',
            'dhabbii_woliigalaa' => 'nullable|string',
            'yeroo_dhiiraa' => 'nullable|string',
            'yeroo_durbaa' => 'nullable|string',
            'yeroo_woliigalaa' => 'nullable|string',
            'dargaggoota_dhiiraa' => 'nullable|string',
            'dargaggoota_durbaa' => 'nullable|string',
            'dargaggoota_woliigalaa' => 'nullable|string',
        ]);

        // Find the Qonna by ID and update it
        $qonna = Qonna::findOrFail($id);
        $qonna->update($validated);

        // Redirect to the Qonna index page with a success message
        return redirect()->route('qonnas.index')->with('success', 'Qonna updated successfully.');
    }

    public function destroy($id)
    {
        // Find the Qonna by ID and delete it
        $qonna = Qonna::findOrFail($id);
        $qonna->delete();

        // Redirect to the Qonna index page with a success message
        return redirect()->route('qonnas.index')->with('success', 'Qonna deleted successfully.');
    }

    public function getCropsByType($crop_type_id)
    {
        $crops = Crop::where('croptype_id', $crop_type_id)
                    ->select('id', 'name')
                    ->get();
    
        return response()->json($crops);
    }
}
