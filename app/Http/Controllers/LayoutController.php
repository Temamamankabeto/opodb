<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class LayoutController extends Controller
{
    public static function getLayoutData()
    {
        $user = Auth::user();

        if (! $user) {
            return [
                'user' => null,
                'profileImage' => asset('uploads/default.png'),
                'profileRoute' => null,
                'passwordRoute' => null,
            ];
        }

        $profileImage = $user->profile_picture
            ? asset('uploads/profile_pictures/'.$user->profile_picture)
            : asset('uploads/default.png');

        $profileRoutes = [
            1 => 'admin.users.profile',
            2 => 'zone.profile.users',
            3 => 'woreda.profile.users',
            4 => 'kebele.profile.users',
            5 => 'sector.profile.users',

        ];

        $passwordRoutes = [
            1 => 'admin.users.password',
            2 => 'zone.users.password',
            3 => 'woreda.users.password',
            4 => 'kebele.users.password',
            5 => 'sector.users.password',
        ];

        // 🧩 Here's where you put the caching block:
        return cache()->remember("layout_data_{$user->id}", 60, function () use ($user, $profileImage, $profileRoutes, $passwordRoutes) {
            return [
                'user' => $user,
                'profileImage' => $profileImage,
                'profileRoute' => $profileRoutes[$user->role_id] ?? null,
                'passwordRoute' => $passwordRoutes[$user->role_id] ?? null,
            ];
        });
    }
}
