<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Kebele;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class kebeleUserController extends Controller
{
    
 public function index()
{
    $roles = Role::where('id', 4)->get();
    $woreda = Auth::user()->woreda;

    if (!$woreda) {
        abort(403, 'You do not belong to a Woreda!');
    }

    $kebeles = Kebele::where('woreda_id', $woreda->id)->get();

    $users = User::with(['zone:id,name', 'woreda:id,name', 'kebele:id,name', 'role:id,name'])
        ->where('role_id', 4)
        ->where('woreda_id', $woreda->id)
        ->orderBy('created_at', 'desc')
        ->paginate(100);

    return view('user.kebele.kebele_admin', compact('users', 'roles', 'woreda', 'kebeles'));
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


     public function kebeleadmindelete($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        
        // Optional: Prevent deleting yourself
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        // Optional: Add authorization check
        // $this->authorize('delete', $user);
        
        $user->delete();
        
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
