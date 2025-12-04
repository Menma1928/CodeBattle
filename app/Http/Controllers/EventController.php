<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\EventRule;
use App\Models\Requirement;

class EventController extends Controller
{
    public function index(Request $request){
        $title = "Eventos";
        $query = Event::query();
        
        // Búsqueda por nombre o descripción
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%' . $search . '%')
                  ->orWhere('descripcion', 'like', '%' . $search . '%')
                  ->orWhere('direccion', 'like', '%' . $search . '%');
            });
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado != 'todos') {
            $query->where('estado', $request->estado);
        }
        
        $events = $query->paginate(10)->withQueryString();
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
        $event = Event::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
            'url_imagen' => $request->url_imagen,
            'admin_id' => auth()->id(),
        ]);

        // Guardar reglas si existen
        if ($request->has('reglas')) {
            foreach ($request->reglas as $regla) {
                if (!empty($regla)) {
                    EventRule::create([
                        'event_id' => $event->id,
                        'regla' => $regla,
                    ]);
                }
            }
        }

        // Guardar requisitos si existen
        if ($request->has('requisitos')) {
            foreach ($request->requisitos as $requisito) {
                if (!empty($requisito)) {
                    Requirement::create([
                        'event_id' => $event->id,
                        'name' => $requisito,
                        'description' => null,
                    ]);
                }
            }
        }

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
        $user_is_admin = auth()->id() === $evento->admin_id; // saber si el usuario es administrador del evento
        $user_team = null;
        if (!$user_is_admin) {
            $user_team = auth()->user()->teams()
            ->where('event_id', $evento->id)
            ->with('users')
            ->first();
        }

        $teams = Team::where('event_id', $evento->id)->paginate(5);
        return view('eventos.evento', compact('evento', 'teams', 'user_is_admin', 'user_team'));
    }

    

}
