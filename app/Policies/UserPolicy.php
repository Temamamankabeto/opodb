<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Super admin and admin can view all users
        return in_array($user->role_id, [1, 2, 3, 4]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Super admin can view anyone
        if ($user->role_id === 1) return true;

        // Admin can view users they created
        if ($user->role_id === 2) return $model->created_by === $user->id;

        // Zone admin can view users in their zone
        if ($user->role_id === 3) return $model->zone_id === $user->zone_id;

        // Kebele admin can view users in their kebele
        if ($user->role_id === 4) return $model->kebele_id === $user->kebele_id;

        // Other users cannot view
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin and admin can create users
        return in_array($user->role_id, [1, 2]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Super admin can edit anyone
        if ($user->role_id === 1) return true;

        // Admin can edit users they created
        if ($user->role_id ===2) return $model->created_by === $user->id;

        // Zone admin can edit users in their zone
        if ($user->role_id === 3) return $model->zone_id === $user->zone_id;

        // Kebele admin can edit users in their kebele
        if ($user->role_id === 4) return $model->kebele_id === $user->kebele_id;

        // Regular users cannot edit anyone
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Super admin can delete anyone
        if ($user->role_id === 1) return true;

        // Admin can delete users they created
        if ($user->role_id === 2) return $model->created_by === $user->id;

        return false; // Zone/kebele admins and regular users cannot delete
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $this->delete($user, $model); // Same logic as delete
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $this->delete($user, $model); // Same logic as delete
    }
}
