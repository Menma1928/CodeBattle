<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRule;
use App\Models\Requirement;

class EventController extends Controller
{
    public function index(){
        $events = Event::query()->paginate(10);
        return view('eventos.index', compact('events'));
    }

    public function create(){
        $events = Event::all();
        return view('eventos.create', compact('events'));
    }

    public function store(Request $request){
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $event = Event::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
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
                        'requisito' => $requisito,
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
        return view('eventos.evento', compact('evento'));
    }

}
