<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;

class TeamController extends Controller
{
    public function index(Request $request){
        $title = "Equipos";
        $query = Team::with('event');
        
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
        $teams = auth()->user()->teams()->paginate(10);
        return view('equipos.index', compact('teams', 'title'));
    }

    public function create(){
        $teams = Team::all();
        return view('equipos.create', compact('teams'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'url_banner' => 'nullable|string|max:255'
        ]);
        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'url_banner' => $request->url_banner,
            'event_id' => $request->event_id,
        ]);
        return redirect()->route('equipos.index');
    }
    
    public function edit(Team $equipo){
        return view('equipos.edit', compact('equipo'));
    }

    public function update(Request $request, Team $equipo){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'url_banner' => 'nullable|string|max:255'
        ]);
        $equipo->update([
            'name' => $request->name,
            'description' => $request->description,
            'url_banner' => $request->url_banner,
        ]);
        return redirect()->route('equipos.index');
    }

    public function destroy(Team $equipo){
        $equipo->delete();
        return redirect()->route('equipos.index');
    }

    public function show(Team $equipo){
        $equipo->load('users', 'event');
        $members = $equipo->users;
        
        // Verificar si el usuario autenticado es líder del equipo
        $is_leader = false;
        $is_member = false;
        $user_role_in_team = $equipo->users()->where('user_id', auth()->id())->first();
        if ($user_role_in_team) {
            $is_member = true;
            if ($user_role_in_team->pivot->rol === 'Líder') {
                $is_leader = true;
            }
        }
        
        return view('equipos.equipo', compact('equipo', 'members', 'is_leader', 'is_member'));
    }

    public function removeMember(Team $equipo, User $user){
        // Verificar que el usuario autenticado sea Super Admin, administrador del evento o líder del equipo
        $is_leader = $equipo->users()->where('user_id', auth()->id())->first()?->pivot?->rol === 'Líder';
        $is_event_admin = $equipo->event->user_id == auth()->id();
        
        if (!auth()->user()->hasRole('Super Admin') && !$is_event_admin && !$is_leader) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar miembros.');
        }
        
        // No permitir eliminar al líder
        $member_role = $equipo->users()->where('user_id', $user->id)->first()?->pivot?->rol;
        if ($member_role === 'Líder') {
            return redirect()->back()->with('error', 'No se puede eliminar al líder del equipo.');
        }
        
        $equipo->users()->detach($user->id);
        return redirect()->back()->with('success', 'Miembro eliminado del equipo.');
    }

    public function leaveTeam(Team $equipo){
        $user_role = $equipo->users()->where('user_id', auth()->id())->first()?->pivot?->rol;
        
        // No permitir al líder abandonar el equipo
        if ($user_role === 'Líder') {
            return redirect()->back()->with('error', 'El líder no puede abandonar el equipo. Transfiere el liderazgo o elimina el equipo.');
        }
        
        $equipo->users()->detach(auth()->id());
        return redirect()->route('equipos.index')->with('success', 'Has abandonado el equipo.');
    }


}


