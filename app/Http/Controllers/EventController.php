<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;

class EventController extends Controller
{
    public function index(){
        $title = "Eventos";
        $events = Event::query()->paginate(10);
        return view('eventos.index', compact('events', 'title'));
    }

    public function myEvents(){
        $title = "Mis Eventos";
        $events = Event::where('admin_id', auth()->id())->paginate(10);
        return view('eventos.index', compact('events', 'title'));
    }

    public function create(){
        $events = Event::all();
        return view('eventos.create', compact('events'));
    }

    public function store(Request $request){ 
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'date',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|string|max:100',
            'url_imagen' => 'nullable|string|max:255',
            'admin_id' => 'required|integer|exists:users,id',
        ]);
        Event::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
            'url_imagen' => $request->url_imagen,
            'admin_id' => $request->admin_id,
        ]);
        return redirect()->route('eventos.index');
    }

    public function edit(Event $evento){
        return view('eventos.edit', compact('evento'));
    }

    public function update(Request $request, Event $evento){
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
        ]);
        $evento->update([
            'name' => $request->name,
            'date' => $request->date,
            'location' => $request->location,
        ]);
        return redirect()->route('eventos.index');
    }

    public function destroy(Event $evento){
        $evento->delete();
        return redirect()->route('eventos.index');
    }

    public function show(Event $evento){
        $teams = Team::where('event_id', $evento->id)->get();
        return view('eventos.evento', compact('evento', 'teams'));
    }

    

}
