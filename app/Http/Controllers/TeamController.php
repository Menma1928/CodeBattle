<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Requests\TeamUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use App\Models\Event; 

class TeamController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request){
        $title = "Equipos";
        $query = Team::with(['event', 'users', 'project']); // Eager loading optimizado

        // Búsqueda por nombre, descripción o evento
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('descripcion', 'like', '%' . $search . '%')
                  ->orWhereHas('event', function($q) use ($search) {
                      $q->where('nombre', 'like', '%' . $search . '%');
                  });
            });
        }

        $teams = $query->paginate(10)->withQueryString();
        return view('equipos.index', compact('teams', 'title'));
    }

    public function myTeams(){
        $title = "Mis Equipos";
        $teams = auth()->user()->teams()
            ->with(['event', 'users', 'project']) // Eager loading
            ->paginate(10);
        return view('equipos.index', compact('teams', 'title'));
    }

    public function create(Event $event){
        $teams = Team::all();
        return view('equipos.create', compact('teams', 'event'));
    }

    public function store(TeamStoreRequest $request){

        // Validate that user is not already in a team for this event
        $existingTeam = auth()->user()->teams()
            ->where('event_id', $request->event_id)
            ->exists();

        if ($existingTeam) {
            return redirect()->back()->withErrors([
                'event_id' => 'Ya estás en un equipo para este evento.'
            ])->withInput();
        }

        $team = Team::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'url_banner' => null,
            'event_id' => $request->event_id,
        ]);

        // Assign creator as leader
        $team->users()->attach(auth()->id(), ['rol' => 'lider']);

        // Manejar subida de banner si existe (después de crear el equipo para tener el ID)
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $extension = $banner->getClientOriginalExtension();
            $bannerName = 'banner.' . $extension;

            // Guardar en storage/app/public/teams/{team_id}/banner.ext
            $banner->storeAs('public/teams/' . $team->id, $bannerName);

            // Guardar ruta relativa en BD: teams/{team_id}/banner.ext
            $team->update([
                'url_banner' => 'teams/' . $team->id . '/' . $bannerName
            ]);
        }

        return redirect()->route('equipos.show', $team)->with('success', 'Equipo creado exitosamente.');
    }
    
    public function edit(Team $equipo){
        $this->authorize('update', $equipo);
        return view('equipos.edit', compact('equipo'));
    }

    public function update(TeamUpdateRequest $request, Team $equipo){
        $updateData = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ];

        // Manejar subida de nuevo banner si existe
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $extension = $banner->getClientOriginalExtension();
            $bannerName = 'banner.' . $extension;

            // Eliminar banner anterior si existe (toda la carpeta del equipo)
            if ($equipo->url_banner) {
                \Storage::deleteDirectory('public/teams/' . $equipo->id);
            }

            // Guardar en storage/app/public/teams/{team_id}/banner.ext
            $banner->storeAs('public/teams/' . $equipo->id, $bannerName);

            // Guardar ruta relativa en BD: teams/{team_id}/banner.ext
            $updateData['url_banner'] = 'teams/' . $equipo->id . '/' . $bannerName;
        }

        $equipo->update($updateData);

        return redirect()->route('equipos.show', $equipo)->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Team $equipo){
        $this->authorize('delete', $equipo);
        $equipo->delete();
        return redirect()->route('equipos.index');
    }

    public function show(Team $equipo){
        $equipo->load('users', 'event', 'pendingJoinRequests.user', 'project');
        $members = $equipo->users;

        // Verificar si el usuario autenticado es líder del equipo
        $is_leader = false;
        $is_member = false;
        $user_role_in_team = $equipo->users()->where('user_id', auth()->id())->first();
        if ($user_role_in_team) {
            $is_member = true;
            if ($user_role_in_team->pivot->rol === 'lider') {
                $is_leader = true;
            }
        }

        // Verificar si el usuario tiene una solicitud pendiente
        $has_pending_request = \App\Models\TeamJoinRequest::where('team_id', $equipo->id)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->exists();

        // Verificar si el usuario ya está en un equipo de este evento
        $user_team_in_event = auth()->user()->teams()
            ->where('event_id', $equipo->event_id)
            ->exists();

        return view('equipos.equipo', compact(
            'equipo',
            'members',
            'is_leader',
            'is_member',
            'has_pending_request',
            'user_team_in_event'
        ));
    }

    public function removeMember(Team $equipo, User $user){
        $this->authorize('manageMembers', $equipo);

        // No permitir eliminar al líder
        $member_role = $equipo->users()->where('user_id', $user->id)->first()?->pivot?->rol;
        if ($member_role === 'lider') {
            return redirect()->back()->with('error', 'No se puede eliminar al líder del equipo.');
        }

        $equipo->users()->detach($user->id);
        return redirect()->back()->with('success', 'Miembro eliminado del equipo.');
    }

    public function leaveTeam(Team $equipo){
        $this->authorize('leave', $equipo);

        $equipo->users()->detach(auth()->id());
        return redirect()->route('equipos.index')->with('success', 'Has abandonado el equipo.');
    }

    public function updateMemberRole(Request $request, Team $equipo, User $user){
        $this->authorize('updateMemberRole', $equipo);

        // No permitir cambiar el rol del líder
        $current_role = $equipo->users()->where('user_id', $user->id)->first()?->pivot?->rol;
        if ($current_role === 'lider') {
            return response()->json(['error' => 'No se puede cambiar el rol del líder.'], 403);
        }

        // Validar el nuevo rol
        $request->validate([
            'rol' => 'required|in:miembro,lider,desarrollador,diseñador'
        ]);

        // Actualizar el rol en la tabla intermedia
        $equipo->users()->updateExistingPivot($user->id, ['rol' => $request->rol]);

        return response()->json(['success' => true, 'message' => 'Rol actualizado correctamente.']);
    }


}


