<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;

class TeamPolicy
{
    /**
     * Determine if the user can view any teams
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver equipos');
    }

    /**
     * Determine if the user can view the team
     */
    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('ver equipos');
    }

    /**
     * Determine if the user can create teams
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create a team
    }

    /**
     * Determine if the user can update the team
     * Only the team leader, event admin, or superadmin can update
     */
    public function update(User $user, Team $team): bool
    {
        $isLeader = $team->isLeader($user->id);
        $isEventAdmin = $team->event->admin_id === $user->id;
        $isSuperAdmin = $user->hasRole('Super Admin');

        return $isLeader || $isEventAdmin || $isSuperAdmin;
    }

    /**
     * Determine if the user can delete the team
     * Only the team leader, event admin, or superadmin can delete
     */
    public function delete(User $user, Team $team): bool
    {
        $isLeader = $team->isLeader($user->id);
        $isEventAdmin = $team->event->admin_id === $user->id;
        $isSuperAdmin = $user->hasRole('Super Admin');

        return $isLeader || $isEventAdmin || $isSuperAdmin;
    }

    /**
     * Determine if the user can manage members (add/remove)
     * Only the team leader, event admin, or superadmin can manage members
     */
    public function manageMembers(User $user, Team $team): bool
    {
        $isLeader = $team->isLeader($user->id);
        $isEventAdmin = $team->event->admin_id === $user->id;
        $isSuperAdmin = $user->hasRole('Super Admin');

        return $isLeader || $isEventAdmin || $isSuperAdmin;
    }

    /**
     * Determine if the user can update member roles
     * Only the team leader or superadmin can update roles
     */
    public function updateMemberRole(User $user, Team $team): bool
    {
        $isLeader = $team->isLeader($user->id);
        $isSuperAdmin = $user->hasRole('Super Admin');

        return $isLeader || $isSuperAdmin;
    }

    /**
     * Determine if the user can leave the team
     * Any member except the leader can leave
     */
    public function leave(User $user, Team $team): bool
    {
        $isMember = $team->users()->where('user_id', $user->id)->exists();
        $isLeader = $team->isLeader($user->id);

        return $isMember && !$isLeader;
    }
}
