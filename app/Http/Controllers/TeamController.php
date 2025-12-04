<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function index(){
        $title = "Equipos";
        $teams = Team::query()->paginate(10);
        return view('equipos.index', compact('teams', 'title'));
    }

    public function myTeams(){
        $title = "Mis Equipos";
        $teams = Team::where('admin_id', auth()->id())->paginate(10);
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
        $members = $equipo->users;
        return view('equipos.show', compact('equipo', 'members'));
    }


}


