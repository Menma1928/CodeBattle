<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Team;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::with('team.event');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('descripcion', 'like', '%' . $search . '%');
            });
        }

        $projects = $query->paginate(10)->withQueryString();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create(Request $request)
    {
        $teamId = $request->get('team_id');
        $team = Team::with('event')->findOrFail($teamId);

        // Check if user is leader of the team
        $this->authorize('update', $team);

        // Check if team already has a project
        if ($team->project) {
            return redirect()->route('projects.edit', $team->project)
                ->with('info', 'Este equipo ya tiene un proyecto. Puedes editarlo aquí.');
        }

        return view('projects.create', compact('team'));
    }

    /**
     * Store a newly created project
     */
    public function store(ProjectStoreRequest $request)
    {

        $team = Team::with('event')->findOrFail($request->team_id);

        // Check if user is leader
        $this->authorize('update', $team);

        // Check if team already has a project
        if ($team->project) {
            return redirect()->back()->withErrors([
                'error' => 'Este equipo ya tiene un proyecto.'
            ]);
        }

        $project = Project::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'github_url' => $request->github_url,
            'team_id' => $request->team_id,
            'fecha_subida' => now(),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto creado exitosamente.');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load('team.event', 'team.users', 'juryRatings.jury', 'juryRatings.requirement');

        $isLeader = $project->team->isLeader(auth()->id());
        $eventIsActive = $project->team->event->estado === 'activo';

        return view('projects.show', compact('project', 'isLeader', 'eventIsActive'));
    }

    /**
     * Show the form for editing the specified project
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        $project->load('team.event');

        $eventIsActive = $project->team->event->estado === 'activo';

        return view('projects.edit', compact('project', 'eventIsActive'));
    }

    /**
     * Update the specified project
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->load('team.event');

        $eventIsActive = $project->team->event->estado === 'activo';

        $updateData = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ];

        // Only update github_url if event is active
        if ($eventIsActive && $request->has('github_url')) {
            $updateData['github_url'] = $request->github_url;
        }

        $project->update($updateData);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado exitosamente.');
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->load('team');

        $teamId = $project->team_id;
        $project->delete();

        return redirect()->route('equipos.show', $teamId)
            ->with('success', 'Proyecto eliminado exitosamente.');
    }
}
