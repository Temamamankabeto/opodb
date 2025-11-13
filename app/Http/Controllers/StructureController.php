<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\CropType;
use App\Models\Kebele;
use App\Models\KebeleSectors;
use App\Models\User;
use App\Models\Woreda;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;



class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function zonelist()
    {
        $user = auth()->user();
        //dd($user->role_id);
        if ($user->role_id === 1) {
            // Superadmin sees all zones, woredas, kebeles, and sectors
            $zones = Zone::with('woredas.kebeles.sectors')
                ->orderBy('name')
                ->get();
        } elseif ($user->role_id === 2) {
            // Zone admin sees only their zone and its nested data
            $zones = Zone::where('id', $user->zone_id)
                ->with(['woredas' => function ($woredasQuery) use ($user) {
                    $woredasQuery->with(['kebeles' => function ($kebelesQuery) {
                        $kebelesQuery->with('sectors');
                    }]);
                }])
                ->orderBy('name')
                ->get();
        } else {
            // Other roles, e.g., woreda admin, you can customize
            $zones = collect(); // empty collection for now
        }

        return view('setting.zones.index', compact('zones'));
    }
    public function structures()
    {
        $zones = Zone::with('woredas.kebeles.sectors')->orderBy('name')->where('id', Auth::user()->zone_id)->get();
        return view('setting.zones.structures', compact('zones'));
    }


    public function woredastructures()
    {
        $user = Auth::user();
        // dd($user->role_id);
        if ($user->role_id == 1) {
            // Super Admin: see all
            $zones = Zone::with('woredas.kebeles.sectors')->get();
        } elseif ($user->role_id == 2) {
            // Zone Admin: see only his Zone
            $zones = Zone::with('woredas.kebeles.sectors')
                ->where('id', $user->zone_id)
                ->get();
        } elseif ($user->role_id == 3) {
            // Woreda Admin: see only his Woreda (and its Kebeles)
            $zones = Zone::where('id', $user->zone_id)
                ->with(['woredas' => function ($q) use ($user) {
                    $q->where('id', $user->woreda_id)
                        ->with('kebeles.sectors');
                }])
                ->get();
        } else {
            $zones = collect(); // No data for others
        }

        return view('setting.zones.woredastructures', compact('zones'));
    }


    public function storezone(Request $request)
    {
        // Allow only users with role_id == 1
        if (Auth::user()->role_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        // Validation
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/u'],
        ]);

        // Create zone
        Zone::create($request->all());

        return redirect()->back()->with('success', 'Zone created.');
    }

    public function sektorsIndex()
    {
        $user = Auth::user();

        // Fetch all sectors created by the user's kebele
        $sectors = KebeleSectors::all();
           
           

        return view('setting.sector.index', compact('sectors'));
    }

    public function destroySector($id)
    {
        $user = Auth::user();
        $sector = KebeleSectors::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->firstOrFail();

        $sector->delete();

        return back()->with('success', 'Sector deleted successfully!');
    }


    public function sectorcreate()
    {
        return view('setting.sector.create');
    }

    public function kebelesectorstore(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Create sector record with IDs from logged-in user
        KebeleSectors::create([
            'name' => $request->name,
            'level'=> $request->level,
             
            'created_by' => $user->id, // optional (for auditing)
        ]);

        return redirect()->route('sectors.index')->with('success', 'Sector added successfully!');
    }



     

    public function editSector($id)
    {
        $sector = KebeleSectors::findOrFail($id);

        // Optional: Authorization check
        // $this->authorize('update', $sector);

        return view('setting.sector.edit', compact('sector'));
    }
    

    public function updateSector(Request $request, $id)
    {
        $user = Auth::user();
        $sector = KebeleSectors::findOrFail($id);

        // Optional: Authorization check
        // $this->authorize('update', $sector);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sector->update([
            'name' => $request->name,
            'updated_by' => $user->id, // optional (for auditing)
        ]);

        return redirect()->route('sectors.index')->with('success', 'Sector updated successfully!');
    }


    public function destroyzone(Zone $zone)
    {
        $zone->delete();
        return redirect()->route('zones.index')->with('success', 'Zone deleted.');
    }

    public function woredalist()
    {
        $zones = Zone::orderBy('name')->get();
        $woredas = Woreda::with('zone')->latest()->paginate(100); // Load many for grouping
        return view('setting.woredas.index', compact('zones', 'woredas'));
    }

    public function kebelelist()
    {
        $currentUser = auth()->user();

        // Only role_id 1 and 2 can access
        if (!in_array($currentUser->role_id, [1, 2])) {
            abort(403, 'Unauthorized action.');
        }

        $woredas = Woreda::with('kebeles')->orderBy('name')->paginate(100);

        return view('setting.kebele.index', compact('woredas'));
    }


    public function storeworeda(Request $request)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => [
                'required',
                'string',
                'max:255',
                // Only letters (including unicode) and spaces, no HTML/script
                'regex:/^[\p{L}\s]+$/u'
            ],
        ]);

        Woreda::create($request->all());
        return redirect()->back()->with('success', 'Woreda created successfully.');
    }

    public function storeKebele(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Reject HTML/script tags
                'regex:/^[a-zA-Z\s\p{L}]+$/u'
            ],
            'woreda_id' => 'required|exists:woredas,id',
            'total_area' => 'nullable|numeric|min:1',
            'total_farm_area' => 'nullable|numeric|min:1',
            'total_population' => 'nullable|numeric|min:1',
            'male' => 'nullable|numeric|min:0',
            'female' => 'nullable|numeric|min:0',
        ]);

        Kebele::create($request->only(
            'name',
            'woreda_id',
            'total_area',
            'total_farm_area',
            'total_population',
            'male',
            'female'
        ));

        return back()->with('success', 'Kebele added successfully.');
    }


    public function edit(Kebele $kebele)
    {

        if (Auth::user()->role_id == 1) {
            $woredas = Woreda::all();
        } else {
            $woredas = Woreda::where('id', Auth::user()->kebele_id)->get();
        }
        return view('setting.zones.index', compact('kebele', 'woredas'));
    }

    public function update(Request $request, Kebele $kebele)
    {

        // ✅ Check if user is logged in and has role_id 1 or 2
        if (!Auth::check() || !in_array(Auth::user()->role_id, [1, 2])) {
            abort(403, 'Unauthorized action.');
        }
        // ✅ Validate incoming data
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                // Reject HTML/script tags
                'regex:/^[a-zA-Z\s\p{L}]+$/u'
            ],
            'woreda_id' => 'required|exists:woredas,id',
            'total_area' => 'nullable|numeric|min:1',
            'total_farm_area' => 'nullable|numeric|min:1',
            'total_population' => 'nullable|numeric|min:1',
            'male' => 'nullable|numeric|min:0',
            'female' => 'nullable|numeric|min:0',
        ]);

        // ✅ Update kebele
        $kebele->update($request->only([
            'name',
            'total_area',
            'total_farm_area',
            'total_population',
            'male',
            'female'
        ]));

        return redirect('admin/zones')
            ->with('success', 'Kebele updated successfully!');
    }


    public function updateworeda(Request $request, Woreda $woreda)
    {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:255',
        ]);

        $woreda->update($request->all());

        return redirect()->route('woredas.index')->with('success', 'Woreda updated successfully.');
    }


    public function croptypelist()
    {
        $croptypes = CropType::with('crops')->orderBy('name')->get();
        return view('setting.croptypes.index', compact('croptypes'));
    }

    public function storecroptype(Request $request)
    {
        $request->validate(['name' => 'required']);
        CropType::create($request->all());
        return redirect()->route('croptype.index')->with('success', 'croptype created.');
    }


    public function croplist()
    {
        $croptypes = CropType::orderBy('name')->get();
        $crops = Crop::with('cropType')->latest()->paginate(100);
        return view('setting.crops.index', compact('croptypes', 'crops')); // Changed 'crop' to 'crops'
    }


    public function storecrop(Request $request)
    {
        $request->validate([
            'croptype_id' => 'required|exists:crop_types,id',
            'name' => 'required|string|max:255',
        ]);

        Crop::create($request->all());
        return redirect()->back()->with('success', 'crop created successfully.');
    }



    public function indexSectorAdmins()
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $sectorAdmins = User::with('sector')
            ->where('kebele_id', $user->kebele_id)
            ->where('role_id', 5) // Sector Admins
            ->orderBy('created_at', 'desc')
            ->get();

        return view('setting.sector.index_admin', compact('sectorAdmins'));
    }

    public function createSectorAdmin()
    {
        $user = Auth::user();

        // Ensure only kebele admins can access
        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch only sectors under this kebele
        $sectors = KebeleSectors::all();

        return view('setting.sector.create_admin', compact('sectors'));
    }

    /**
     * Store new Sector Admin for the kebele admin's kebele
     */
    public function storeSectorAdmin(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'regex:/^(\+251|0)(9|7)\d{8}$/', 'unique:users'],
            'gender' => 'nullable|in:male,female,other',
            'sector_id' => 'required|exists:kebele_sectors,id',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'region_id' => $user->region_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'sector_id' => $request->sector_id,
            'role_id' => 5, // 👈 Sector Admin
            'created_by' => $user->id,
            'employee_id' => 'SEC-' . strtoupper(uniqid()),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('sectoradmin.index')->with('success', 'Sector Admin created successfully!');
    }


    public function editSectorAdmin($id)
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $sectorAdmin = User::where('id', $id)
            
            ->where('role_id', 5)
            ->firstOrFail();

        $sectors = \App\Models\KebeleSectors::all();

        return view('setting.sector.edit_admin', compact('sectorAdmin', 'sectors'));
    }

    public function updateSectorAdmin(Request $request, $id)
    {
        $auth = Auth::user();

        if ($auth->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $sectorAdmin = User::where('id', $id)
            ->where('kebele_id', $auth->kebele_id)
            ->where('role_id', 5)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $sectorAdmin->id,
            'phone' => ['required', 'regex:/^(\+251|0)(9|7)\d{8}$/', 'unique:users,phone,' . $sectorAdmin->id],
            'gender' => 'nullable|in:male,female,other',
            'sector_id' => 'required|exists:kebele_sectors,id',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $sectorAdmin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? null,
            'sector_id' => $validated['sector_id'],
            'password' => !empty($validated['password'])
                ? Hash::make($validated['password'])
                : $sectorAdmin->password,
        ]);

        return redirect()->route('sectoradmin.index')->with('success', 'Sector Admin updated successfully!');
    }









    public function indexdausers()
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch only DA users under Agricultural Development Sector in this kebele
        $dausers = User::with('sector')
            ->where('kebele_id', $user->kebele_id)
            ->where('role_id', 6) // DA users
            ->whereHas('sector', function ($query) {
                $query->where('id', 2); // Assuming ID 2 is Agricultural Development Sector
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('setting.da.index_admin', compact('dausers'));
    }

    public function createdausers()
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch only Agricultural Development Sector for this kebele
        $sectors = \App\Models\KebeleSectors::where('kebele_id', $user->kebele_id)
            ->where('id', 2) // Assuming ID 2 is Agricultural Development Sector
            ->get();

        return view('setting.da.create_admin', compact('sectors'));
    }

    public function storedausers(Request $request)
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'regex:/^(\+251|0)(9|7)\d{8}$/', 'unique:users'],
            'gender' => 'nullable|in:male,female,other',
            'sector_id' => 'required|exists:kebele_sectors,id',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        // Ensure the sector belongs to Agricultural Development only
        $sector = \App\Models\KebeleSectors::where('id', $request->sector_id)
            ->where('kebele_id', $user->kebele_id)
            ->where('id', 2) // Agricultural Development Sector
            ->firstOrFail();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'region_id' => $user->region_id,
            'zone_id' => $user->zone_id,
            'woreda_id' => $user->woreda_id,
            'kebele_id' => $user->kebele_id,
            'sector_id' => $sector->id,
            'role_id' => 6, // DA User
            'created_by' => $user->id,
            'employee_id' => 'DA-' . strtoupper(uniqid()),
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('dausers.index')->with('success', 'Agricultural DA User created successfully!');
    }

    public function editdausers($id)
    {
        $user = Auth::user();

        if ($user->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $dauser = User::where('id', $id)
            ->where('kebele_id', $user->kebele_id)
            ->where('role_id', 6)
            ->whereHas('sector', function ($query) {
                $query->where('id', 2); // Agricultural Development Sector');
            })
            ->firstOrFail();

        $sectors = \App\Models\KebeleSectors::where('kebele_id', $user->kebele_id)
            ->where('id', 2) // Agricultural Development Sector
            ->get();

        return view('setting.da.edit_admin', compact('dauser', 'sectors'));
    }

    public function updatedausers(Request $request, $id)
    {
        $auth = Auth::user();

        if ($auth->role_id != 4) {
            abort(403, 'Unauthorized action.');
        }

        $dauser = User::where('id', $id)
            ->where('kebele_id', $auth->kebele_id)
            ->where('role_id', 6)
            ->whereHas('sector', function ($query) {
                $query->where('id', 2); // Agricultural Development Sector
            })
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $dauser->id,
            'phone' => ['required', 'regex:/^(\+251|0)(9|7)\d{8}$/', 'unique:users,phone,' . $dauser->id],
            'gender' => 'nullable|in:male,female,other',
            'sector_id' => 'required|exists:kebele_sectors,id',
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        // Make sure sector belongs to Agricultural Development Sector
        $sector = \App\Models\KebeleSectors::where('id', $validated['sector_id'])
            ->where('kebele_id', $auth->kebele_id)
            ->where('id', 2) // Agricultural Development Sector
            ->firstOrFail();

        $dauser->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? null,
            'sector_id' => $sector->id,
            'password' => !empty($validated['password'])
                ? Hash::make($validated['password'])
                : $dauser->password,
        ]);

        return redirect()->route('dausers.index')->with('success', 'Agricultural DA User updated successfully!');
    }

    public function destroydausers($id)
    {
        // Find the DA user by ID
        $user = User::findOrFail($id);

        // Optional: you can restrict deletion to DA users only
        if ($user->role_id != 6) { // assuming role_id = 4 is for DA users
            return redirect()->route('dausers.index')
                ->with('error', 'You can only delete DA users.');
        }

        // Delete the record
        $user->delete();

        // Redirect back with a success message
        return redirect()->route('dausers.index')
            ->with('success', 'DA user deleted successfully.');
    }



    /**
     * Show the form for creating a new resource.
     */
}
