<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Project;
use App\Models\ProjectJuryRequirement;

class JuryRatingController extends Controller
{
    /**
     * Show projects for a jury to rate in a specific event
     */
    public function indexByEvent(Event $event)
    {
        // Check if user is a jury for this event
        if (!$event->juries()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->withErrors([
                'error' => 'No eres jurado de este evento.'
            ]);
        }

        $event->load('requirements');

        // Get all projects from teams in this event
        $projects = Project::whereHas('team', function($query) use ($event) {
            $query->where('event_id', $event->id);
        })->with('team', 'juryRatings')->get();

        // Check which projects have been rated by this jury
        foreach ($projects as $project) {
            $project->jury_has_rated = $project->juryRatings()
                ->where('jury_id', auth()->id())
                ->count() === $event->requirements->count();
        }

        return view('jury.projects-index', compact('event', 'projects'));
    }

    /**
     * Show rating form for a specific project
     */
    public function rateProject(Event $event, Project $project)
    {
        // Check if user is a jury for this event
        if (!$event->juries()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->withErrors([
                'error' => 'No eres jurado de este evento.'
            ]);
        }

        // Check if project belongs to this event
        if ($project->team->event_id !== $event->id) {
            return redirect()->back()->withErrors([
                'error' => 'Este proyecto no pertenece a este evento.'
            ]);
        }

        $event->load('requirements');
        $project->load('team');

        // Get existing ratings by this jury for this project
        $existingRatings = ProjectJuryRequirement::where('project_id', $project->id)
            ->where('jury_id', auth()->id())
            ->get()
            ->keyBy('requirement_id');

        return view('jury.rate-project', compact('event', 'project', 'existingRatings'));
    }

    /**
     * Store or update ratings for a project
     */
    public function storeRatings(Request $request, Event $event, Project $project)
    {
        // Check if user is a jury for this event
        if (!$event->juries()->where('user_id', auth()->id())->exists()) {
            return redirect()->back()->withErrors([
                'error' => 'No eres jurado de este evento.'
            ]);
        }

        // Check if project belongs to this event
        if ($project->team->event_id !== $event->id) {
            return redirect()->back()->withErrors([
                'error' => 'Este proyecto no pertenece a este evento.'
            ]);
        }

        $event->load('requirements');

        // Validate ratings (1-10 for each requirement)
        $rules = [];
        foreach ($event->requirements as $requirement) {
            $rules['rating_' . $requirement->id] = 'required|integer|min:1|max:10';
        }

        $request->validate($rules);

        // Store or update ratings
        foreach ($event->requirements as $requirement) {
            ProjectJuryRequirement::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'requirement_id' => $requirement->id,
                    'jury_id' => auth()->id(),
                ],
                [
                    'rating' => $request->input('rating_' . $requirement->id),
                ]
            );
        }

        // Calculate and update average rating for project_requirement table
        $this->updateProjectRequirementAverages($project, $event);

        return redirect()->route('jury.event.projects', $event)
            ->with('success', 'Calificaciones guardadas exitosamente.');
    }

    /**
     * Calculate average ratings from all juries and update project_requirement table
     */
    private function updateProjectRequirementAverages(Project $project, Event $event)
    {
        foreach ($event->requirements as $requirement) {
            $averageRating = ProjectJuryRequirement::where('project_id', $project->id)
                ->where('requirement_id', $requirement->id)
                ->avg('rating');

            if ($averageRating) {
                // Update or create in project_requirement pivot table
                $project->requirements()->syncWithoutDetaching([
                    $requirement->id => ['rating' => round($averageRating, 2)]
                ]);
            }
        }
    }

    /**
     * Show overall ratings and statistics for an event (for admin/jury)
     */
    public function showEventStatistics(Event $event)
    {
        // Check if user is admin or jury
        $isAdmin = $event->admin_id === auth()->id();
        $isJury = $event->juries()->where('user_id', auth()->id())->exists();

        if (!$isAdmin && !$isJury) {
            return redirect()->back()->withErrors([
                'error' => 'No tienes permiso para ver estas estadísticas.'
            ]);
        }

        $event->load('requirements');

        // Get all projects with their average ratings
        $projects = Project::whereHas('team', function($query) use ($event) {
            $query->where('event_id', $event->id);
        })
        ->with(['team', 'requirements', 'juryRatings.jury', 'juryRatings.requirement'])
        ->get();

        // Calculate overall average for each project
        foreach ($projects as $project) {
            $totalRating = $project->requirements->sum('pivot.rating');
            $requirementCount = $project->requirements->count();
            $project->overall_average = $requirementCount > 0
                ? round($totalRating / $requirementCount, 2)
                : 0;
        }

        // Sort projects by overall average (descending)
        $projects = $projects->sortByDesc('overall_average')->values();

        return view('jury.event-statistics', compact('event', 'projects'));
    }
}
