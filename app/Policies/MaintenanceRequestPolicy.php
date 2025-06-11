<?php

namespace App\Policies;

use App\Models\MaintenanceRequest;
use App\Models\User;

class MaintenanceRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        if ($user->role === 'landlord') {
            // Landlord can view if they own the property the request was made.
            return $maintenanceRequest->room
                && $maintenanceRequest->room->property
                && $maintenanceRequest->room->property->landlord_id === $user->id;
        }

        if ($user->role === 'tenant') {
            $tenant = $user->tenant;
            if (!$tenant) {
                return false;
            }
            // Tenant can view if the request is for their room
            return $maintenanceRequest->room_id === $tenant->room_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'tenant';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->role === 'tenant';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->role === 'tenant';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return false;
    }
}
