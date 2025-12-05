<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Determine if the user can view any projects
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view projects
    }

    /**
     * Determine if the user can view the project
     */
    public function view(User $user, Project $project): bool
    {
        return true; // All authenticated users can view a project
    }

    /**
     * Determine if the user can create projects
     * Only team leaders can create projects for their team
     */
    public function create(User $user): bool
    {
        // This will be checked in the controller with team context
        return true;
    }

    /**
     * Determine if the user can update the project
     * Only the team leader can update the project
     */
    public function update(User $user, Project $project): bool
    {
        return $project->team->isLeader($user->id);
    }

    /**
     * Determine if the user can delete the project
     * Only the team leader can delete the project
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->team->isLeader($user->id);
    }

    /**
     * Determine if the user can update the GitHub URL
     * Only the team leader can update, and only if event is active
     */
    public function updateGithubUrl(User $user, Project $project): bool
    {
        $isLeader = $project->team->isLeader($user->id);
        $eventIsActive = $project->team->event->estado === 'activo';

        return $isLeader && $eventIsActive;
    }
}
