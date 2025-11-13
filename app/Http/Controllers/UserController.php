<?php

namespace App\Http\Controllers;

use App\Models\Role;

use App\Models\User;
use App\Models\Zone;
use App\Models\Kebele;
use App\Models\Region;
use App\Models\Woreda;
use Illuminate\Http\Request;
use App\Models\KebeleSectors;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;




class UserController extends Controller
{
    //
    public function login()
    {
        return view('user.login');
    }


    public function checkuser(Request $request)
    {
        // validate
        $credentials = $request->validate([
            'phone' => [
                'required',
                'regex:/^(\+251|0)(9|7)\d{8}$/'
            ],
            'password' => 'required'
        ], [
            'phone.regex' => 'Phone number must be a valid Ethiopian number.'
        ]);

        $phone = $request->input('phone');
        $attemptsKey = "login:attempts:{$phone}";
        $lockKey     = "login:lock:{$phone}";

        // 1) If locked, return with remaining time
        $lockExpiresAt = Cache::get($lockKey); // we'll store an expiry timestamp here
        if ($lockExpiresAt && $lockExpiresAt > time()) {
            $secondsLeft = $lockExpiresAt - time();
            $minutesLeft = ceil($secondsLeft / 60);
            return back()
                ->withInput($request->only('phone'))
                ->withErrors(['phone' => "Too many login attempts. Try again in {$minutesLeft} minute(s)."]);
        } else {
            // cleanup expired lock if present
            Cache::forget($lockKey);
        }

        // 2) Try to authenticate
        if (Auth::attempt($credentials)) {
            // success -> clear counters & lock
            Cache::forget($attemptsKey);
            Cache::forget($lockKey);

            $user = Auth::user();
            switch ($user->role_id) {
                case 1:
                    return redirect()->route('region.dashboard');
                case 2:
                    return redirect()->route('zone.dashboard');
                case 3:
                    return redirect()->route('woreda.dashboard');
                case 4:
                    return redirect()->route('kebele.dashboard');
                case 5:
                    return redirect('sector/dashboard');
                case 6:
                    return redirect()->route('da.dashboard');
                default:
                    return redirect()->route('users.login');
            }
        }

        // 3) Failed login -> increment attempt counter and possibly set a lock
        $attempts = (int) Cache::get($attemptsKey, 0);
        $nextAttempt = $attempts + 1;

        // lock durations (seconds) based on the attempt number being made now
        if ($nextAttempt <= 2) {
            $lockSeconds = 0;        // 1st & 2nd fails: no lock (just "Invalid credentials.")
        } elseif ($nextAttempt == 3) {
            $lockSeconds = 60;       // 3rd fail -> 1 minute
        } elseif ($nextAttempt == 4) {
            $lockSeconds = 5 * 60;   // 4th fail -> 5 minutes
        } elseif ($nextAttempt <= 6) {
            $lockSeconds = 10 * 60;  // 5th-6th -> 10 minutes
        } else {
            $lockSeconds = 60 * 60;  // 7th+ -> 60 minutes
        }

        // keep attempts count for a reasonable period so escalation works (e.g. 1 hour)
        $attemptsTtlSeconds = 60 * 60; // 1 hour
        Cache::put($attemptsKey, $nextAttempt, now()->addSeconds($attemptsTtlSeconds));

        if ($lockSeconds > 0) {
            // set lock: store expiry timestamp and set cache TTL to same duration
            $expiresAt = time() + $lockSeconds;
            Cache::put($lockKey, $expiresAt, now()->addSeconds($lockSeconds));

            $minutes = ceil($lockSeconds / 60);
            $message = "Invalid credentials. You are locked for {$minutes} minute(s).";
        } else {
            // 1st & 2nd fail: only show invalid (no mention of remaining attempts, per your request)
            $message = "Invalid credentials.";
        }

        return back()
            ->withInput($request->only('phone'))
            ->withErrors(['password' => $message]);
    }


    public function create()
    {

        return view('user.new', [
            'regions' => Region::all(),
            'zones' => Zone::all(),
            'woredas' => Woreda::all(),
            'kebeles' => Kebele::all(),
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $authUser = Auth::user();

        // ✅ Only Superadmin (1) or Admin (2) can create users
        if (!in_array($authUser->role_id, [1, 2])) {
            abort(403, 'Unauthorized action.');
        }

        // ✅ Validation rules
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],

            // ✅ Ethiopian phone number pattern (+2519XXXXXXXX or 09XXXXXXXX)
            'phone' => [
                'required',
                'string',
                'regex:/^(\+251|0)(9|7)\d{8}$/',
                'unique:users,phone',
            ],

            'gender' => ['nullable', 'in:male,female,other'],
            'zone_id' => ['nullable', 'exists:zones,id'],
            'woreda_id' => ['nullable', 'exists:woredas,id'],
            'kebele_id' => ['nullable', 'exists:kebeles,id'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
            'role_id' => ['required', 'exists:roles,id'],

            // ✅ Strong password validation
            'password' => [
                'required',
                'confirmed',
                'min:8', // Minimum 8 characters
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', // Must include upper, lower, number & special char
            ],
        ]);

        // ✅ Create user safely
        User::create([
            'region_id'   => 1,
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'],
            'gender'      => $validated['gender'] ?? null,
            'zone_id'     => $validated['zone_id'] ?? null,
            'woreda_id'   => $validated['woreda_id'] ?? null,
            'kebele_id'   => $validated['kebele_id'] ?? null,
            'sector_id'   => $validated['sector_id'] ?? null,
            'role_id'     => $validated['role_id'],
            'employee_id' => '123390', // You can make this dynamic later
            'created_by'  => $authUser->id,
            'password'    => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function kebeleadminstore(Request $request)
    {
        //        dd($request->role_id);
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            // ✅ Ethiopian phone number
            'phone' => [
                'required',
                'string',
                'regex:/^(\+251|0)(9|7)\d{8}$/',
                'unique:users,phone', // Ensure phone is unique in 'users' table
            ],
            'gender' => 'nullable|in:male,female,other',
            'zone_id' => 'nullable|exists:zones,id',
            //            'woreda_id' => 'nullable|exists:woredas,id',
            'kebele_id' => 'nullable|exists:kebeles,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'region_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'zone_id' => Auth::user()->zone_id,
            'woreda_id' => $request->woreda_id,
            'kebele_id' => $request->kebele_id,
            'role_id' => $request->role_id,
            'employee_id' => '123390',

            'password' => Hash::make($request->password),
        ]);

        // Assign role (assuming you are using a role system like Spatie)
        //        $user->roles()->attach();
        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function storesectoradmin(Request $request)
    {
        //        dd($request->role_id);

        // Validate incoming request
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/u'],
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => [
                'required',
                'string',
                'regex:/^(\+251|0)(9|7)\d{8}$/',
                'unique:users,phone', // Ensure phone is unique in 'users' table
            ],
            'gender' => 'nullable|in:male,female,other',
            'zone_id' => 'nullable|exists:zones,id',
            'woreda_id' => 'nullable|exists:woredas,id',
            'kebele_id' => 'nullable|exists:kebeles,id',
            'sector_id' => 'nullable|exists:kebele_sectors,id',
            'role_id' => 'required|exists:roles,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'region_id' => 1,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'zone_id' => Auth::user()->zone_id,
            'woreda_id' => Auth::user()->woreda_id,
            'kebele_id' => $request->kebele_id,
            'sector_id' => $request->sector_id,
            'role_id' => $request->role_id,
            'employee_id' => '123390',

            'password' => Hash::make($request->password),
        ]);
        return redirect()->back()->with('success', 'User created successfully.');
    }

    public function index()
    {
        $user = auth()->user();

        // Common data for filters
        $zones = Zone::all();
        $roles = Role::where('id', 2)->get(); // Assuming role_id=2 is zone_admin

        // Base query for users
        $query = User::with([
            'zone:id,name',
            'woreda:id,name',
            'kebele:id,name',
            'role:id,name'
        ])->where('role_id', 2); // only zone_admin users

        // Apply role-based access
        if ($user->role_id === 1) {
            $users = $query->orderBy('created_at', 'desc')->paginate(10);
        } elseif ($user->role_id === 2) {
            $users = $query->where('zone_id', $user->zone_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->role_id === 3) {
            $users = $query->where('woreda_id', $user->woreda_id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Return empty paginator for other roles
            $users = new LengthAwarePaginator([], 0, 10, Paginator::resolveCurrentPage());
        }

        return view('user.region.zone_admin', compact('users', 'zones', 'roles'));
    }


    public function woredaadmin()
    {
        $zones = Zone::where('id', Auth::user()->zone_id)->get();

        // Only get role_id = 3 (woreda admin)
        $roles = Role::where('id', 3)->get();

        $users = User::with([
            'zone:id,name',
            'woreda:id,name',
            'kebele:id,name',
            'role:id,name'
        ])
            ->where('role_id', 3)
            ->where('zone_id', Auth::user()->zone_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.zone.woreda_admin', compact('users', 'zones', 'roles'));
    }

    public function kebeleadmin()
    {
        $zones = Zone::all();
        // Only get role_id = 3 (woreda admin)
        $roles = Role::where('id', 4)->get();
        $woredas = Woreda::where(
            'zone_id',
            Auth::user()->zone_id
        )->get();
        $users = User::with([
            'zone:id,name',
            'woreda:id,name',
            'kebele:id,name',
            'role:id,name'
        ])
            ->where('role_id', 4)
            ->where('zone_id', Auth::user()->zone_id)
            //            ->where('woreda_id', Auth::user()->woreda_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.kebele.kebele_admin', compact('users', 'zones', 'roles', 'woredas'));
    }

    public function sectoruser()
    {
        $zones = Zone::all();
        // Only get role_id = 3 (woreda admin)
        $roles = Role::where('id', 5)->get();
        $sectors = KebeleSectors::all();
        $woredas = Woreda::where(
            'zone_id',
            Auth::user()->zone_id
        )->get();

        $kebeles = Kebele::where(
            'woreda_id',
            Auth::user()->zone_id
        )->get();
        $users = User::with([
            'zone:id,name',
            'woreda:id,name',
            'kebele:id,name',
            'role:id,name',
            'sector:id,name'
        ])
            ->where('role_id', 5)
            ->where('zone_id', Auth::user()->zone_id)
            ->where('woreda_id', Auth::user()->woreda_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('user.sector.sector_admin', compact('users', 'zones', 'roles', 'woredas', 'kebeles', 'sectors'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function getZones($region_id)
    {
        $zones = Zone::where('region_id', $region_id)->get();
        return response()->json($zones);
    }

    public function getWoredas($zone_id)
    {
        $woredas = Woreda::where('zone_id', $zone_id)->get();
        return response()->json($woredas);
    }

    public function getKebeles($woreda_id)
    {
        $kebeles = Kebele::where('woreda_id', $woreda_id)->get();
        return response()->json($kebeles);
    }

    public function profile($uuid)
    {
        $user = User::with('zone', 'woreda', 'kebele')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $currentUser = Auth::user();

        // If they are viewing their own profile, always allow
        if ($currentUser->uuid === $user->uuid) {
            return view('user.profile', compact('user'));
        }

        // Role hierarchy rules
        $roleHierarchy = [
            1 => [2, 3, 4],
            2 => [3, 4],
            3 => [4],
            4 => [],   // cannot view anyone except themselves
        ];

        $viewerRole = $currentUser->role_id;
        $targetRole = $user->role_id;

        // Check permission
        if (in_array($targetRole, $roleHierarchy[$viewerRole] ?? [])) {
            return view('user.profile', compact('user'));
        }

        abort(403, 'You are not allowed to view this profile.');
    }

    public function edit($uuid)
    {

        $roles = Role::all();
        $user = User::where('uuid', $uuid)->with('role')->firstOrFail();
        $currentUser = Auth::user();
        // dd($currentUser->role_id);
        // Initialize permission flag
        $canEdit = false;

        if ($currentUser->role_id == 1 || $currentUser->role_id == 2) {
            $canEdit = true;
        }
        // 1. Users can always edit their own profile
        elseif ($currentUser->uuid === $user->uuid) {
            $canEdit = true;
        }
        // 2. Users can edit only the "next role" in hierarchy
        elseif ($currentUser->role_id + 1 === $user->role_id) {
            $canEdit = true;
        }

        // 3. Deny access if not allowed
        if (!$canEdit) {
            abort(403, 'Unauthorized action.');
        }

        // Return edit view
        return view('user.edit', compact('user', 'roles'));
    }
    public function profileedit($uuid)
    {

        $roles = Role::all();
        $user = User::where('uuid', $uuid)->with('role')->firstOrFail();
        $currentUser = Auth::user();
        // dd($currentUser->role_id);
        // Initialize permission flag
        $canEdit = false;

        if ($currentUser->role_id == 1 || $currentUser->role_id == 2) {
            $canEdit = true;
        }
        // 1. Users can always edit their own profile
        elseif ($currentUser->uuid === $user->uuid) {
            $canEdit = true;
        }
        // 2. Users can edit only the "next role" in hierarchy
        elseif ($currentUser->role_id + 1 === $user->role_id) {
            $canEdit = true;
        }

        // 3. Deny access if not allowed
        if (!$canEdit) {
            abort(403, 'Unauthorized action.');
        }

        // Return edit view
        return view('user.profile_edit', compact('user', 'roles'));
    }

    public function kebeleadminedit($uuid)
    {

        $roles = Role::all();
        $user = User::where('uuid', $uuid)->with('role')->firstOrFail();
        $currentUser = Auth::user();
        // dd($currentUser->role_id);
        // Initialize permission flag
        $canEdit = false;

        if ($currentUser->role_id == 1 || $currentUser->role_id == 2) {
            $canEdit = true;
        }
        // 1. Users can always edit their own profile
        elseif ($currentUser->uuid === $user->uuid) {
            $canEdit = true;
        }
        // 2. Users can edit only the "next role" in hierarchy
        elseif ($currentUser->role_id + 1 === $user->role_id) {
            $canEdit = true;
        }

        // 3. Deny access if not allowed
        if (!$canEdit) {
            abort(403, 'Unauthorized action.');
        }

        // Return edit view
        return view('user.kebele.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        // dd($user->role_id);
        $currentUser = Auth::user();
        // Users can always update themselves
        if ($currentUser->uuid !== $user->uuid) {
            // Only immediate lower role can be edited
            if ($user->role_id !== $currentUser->role_id + 1 && $request->role_id !== $currentUser->role_id + 1) {
                abort(403, 'Cannot assign role higher than allowed.');
            }
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'gender' => 'nullable|in:male,female,other',
            'role_id' => 'nullable|integer|exists:roles,id',
        ]);

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }

    public function profileupdate(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        // dd($user->role_id);
        $currentUser = Auth::user();
        // Users can always update themselves
        if ($currentUser->uuid !== $user->uuid) {
            // Only immediate lower role can be edited
            if ($user->role_id !== $currentUser->role_id + 1 && $request->role_id !== $currentUser->role_id + 1) {
                abort(403, 'Cannot assign role higher than allowed.');
            }
        }

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }
    public function kebeleadminupdate(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        // dd($user->role_id);
        $currentUser = Auth::user();
        // Users can always update themselves


        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'gender' => 'nullable|in:male,female,other',

        ]);

        $user->update($validated);

        return redirect()->back()
            ->with('success', 'Profile updated successfully.');
    }

    public function changePasswordForm($uuid)
    {
        // Get currently logged in user
        $authUser = Auth::user();

        // Ensure the given UUID matches the logged-in user's UUID
        if ($authUser->uuid !== $uuid) {
            abort(403, 'Unauthorized action.');
        }

        // Load the user by UUID (optional if you already have $authUser)
        $user = User::where('uuid', $uuid)->firstOrFail();

        return view('user.change_password', compact('user'));
    }
    public function changePassword(Request $request)
    {
        // Must be logged in
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        // Validate input
        $request->validate([
            'old_password' => 'required',
            'phone' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)           // Minimum 8 characters
                    ->mixedCase()          // Must include both uppercase and lowercase letters
                    ->numbers()            // Must include at least one number
                    ->symbols()            // Must include at least one special character
            ],
        ]);

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect']);
        }

        // Verify phone
        if ($request->phone !== $user->phone) {
            return back()->withErrors(['phone' => 'Phone number does not match our records']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }
}
