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
        // Verificar que el evento esté en estado pendiente
        if ($event->estado !== 'pendiente') {
            return redirect()->route('eventos.show', $event)->withErrors([
                'error' => 'No se pueden crear equipos. El evento debe estar en estado "pendiente" para permitir la creación de equipos.'
            ]);
        }

        $teams = Team::all();
        return view('equipos.create', compact('teams', 'event'));
    }

    public function store(TeamStoreRequest $request){
        // Verificar que el evento esté en estado pendiente
        $event = Event::findOrFail($request->event_id);
        if ($event->estado !== 'pendiente') {
            return redirect()->back()->withErrors([
                'event_id' => 'No se pueden crear equipos. El evento debe estar en estado "pendiente" para permitir la creación de equipos.'
            ])->withInput();
        }

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

            // Asegurar que el directorio existe
            $directory = 'teams/' . $team->id;
            \Storage::disk('public')->makeDirectory($directory);

            // Guardar en storage/app/public/teams/{team_id}/banner.ext
            $banner->storeAs($directory, $bannerName, 'public');

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
                \Storage::disk('public')->deleteDirectory('teams/' . $equipo->id);
            }

            // Asegurar que el directorio existe
            $directory = 'teams/' . $equipo->id;
            \Storage::disk('public')->makeDirectory($directory);

            // Guardar en storage/app/public/teams/{team_id}/banner.ext
            $banner->storeAs($directory, $bannerName, 'public');

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

    public function searchUsers(Team $equipo, Request $request){
        $this->authorize('update', $equipo);

        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        // Buscar usuarios que:
        // 1. NO estén en este equipo
        // 2. NO estén en otro equipo del mismo evento
        // 3. Tengan rol de Participante
        $users = User::whereHas('roles', function($q) {
                $q->where('name', 'Participante');
            })
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->whereDoesntHave('teams', function($q) use ($equipo) {
                $q->where('team_id', $equipo->id);
            })
            ->whereDoesntHave('teams', function($q) use ($equipo) {
                $q->where('event_id', $equipo->event_id);
            })
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json(['users' => $users]);
    }

    public function inviteUser(Team $equipo, Request $request){
        $this->authorize('update', $equipo);

        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        // Verificar que el evento esté en estado pendiente
        if ($equipo->event->estado !== 'pendiente') {
            return response()->json(['error' => 'Solo puedes invitar usuarios cuando el evento está pendiente.'], 400);
        }

        // Verificar que el equipo no tenga ya 5 participantes
        if ($equipo->users()->count() >= 5) {
            return response()->json(['error' => 'El equipo ya tiene el máximo de 5 participantes.'], 400);
        }

        // Verificar que el usuario no esté ya en el equipo
        if ($equipo->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'El usuario ya es miembro de este equipo.'], 400);
        }

        // Verificar que el usuario no esté en otro equipo del mismo evento
        $userTeamInEvent = $user->teams()->where('event_id', $equipo->event_id)->exists();
        if ($userTeamInEvent) {
            return response()->json(['error' => 'El usuario ya pertenece a otro equipo en este evento.'], 400);
        }

        // Agregar usuario al equipo
        $equipo->users()->attach($user->id, ['rol' => 'miembro']);

        return response()->json(['success' => true, 'message' => 'Usuario agregado exitosamente al equipo.']);
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


