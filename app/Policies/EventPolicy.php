<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine if the user can view any events
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver eventos');
    }

    /**
     * Determine if the user can view the event
     */
    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('ver eventos');
    }

    /**
     * Determine if the user can create events
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Administrador']);
    }

    /**
     * Determine if the user can update the event
     * Only the event admin or superadmin can update
     */
    public function update(User $user, Event $event): bool
    {
        return $user->hasRole('Super Admin') || $event->admin_id === $user->id;
    }

    /**
     * Determine if the user can delete the event
     * Only the event admin or superadmin can delete
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->hasRole('Super Admin') || $event->admin_id === $user->id;
    }

    /**
     * Determine if the user can manage juries for the event
     * Only the event admin or superadmin can manage juries
     */
    public function manageJuries(User $user, Event $event): bool
    {
        return $user->hasRole('Super Admin') || $event->admin_id === $user->id;
    }

    /**
     * Determine if the user can view event statistics
     * Event admin, superadmin, or assigned juries can view
     */
    public function viewStatistics(User $user, Event $event): bool
    {
        $isAdmin = $user->hasRole('Super Admin') || $event->admin_id === $user->id;
        $isJury = $event->juries()->where('user_id', $user->id)->exists();

        return $isAdmin || $isJury;
    }
}
