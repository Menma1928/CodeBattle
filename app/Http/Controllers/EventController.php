<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Team;
use App\Models\EventRule;
use App\Models\Requirement;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;
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
        $events = Event::with('teams')
            ->where('admin_id', auth()->id())
            ->paginate(10);
        return view('eventos.index', compact('events', 'title'));
    }

    public function create(){
        $this->authorize('create', Event::class);
        $events = Event::all();
        return view('eventos.create', compact('events'));
    }

    public function store(EventStoreRequest $request){
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
        $this->authorize('update', $evento);
        return view('eventos.edit', compact('evento'));
    }

    public function update(EventUpdateRequest $request, Event $evento){
        $evento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'direccion' => $request->direccion,
            'estado' => $request->estado,
            'url_imagen' => $request->url_imagen,
        ]);
        return redirect()->route('eventos.index');
    }

    public function destroy(Event $evento){
        $this->authorize('delete', $evento);
        $evento->delete();
        return redirect()->route('eventos.index');
    }

    public function show(Event $evento){
        $evento->load('eventRules', 'requirements', 'juries');
        $user_is_admin = auth()->id() === $evento->admin_id;
        $user_team = null;
        if (!$user_is_admin) {
            $user_team = auth()->user()->teams()
                ->where('event_id', $evento->id)
                ->with('users')
                ->first();
        }

        $teams = Team::with('users', 'project')
            ->where('event_id', $evento->id)
            ->paginate(5);
        return view('eventos.evento', compact('evento', 'teams', 'user_is_admin', 'user_team'));
    }

    /**
     * Show jury management page for an event
     */
    public function manageJuries(Event $evento)
    {
        $this->authorize('manageJuries', $evento);
        $evento->load('juries');
        $availableUsers = User::whereDoesntHave('juryEvents', function($query) use ($evento) {
            $query->where('event_id', $evento->id);
        })->get();

        return view('eventos.manage-juries', compact('evento', 'availableUsers'));
    }

    /**
     * Assign a jury to an event (max 3)
     */
    public function assignJury(Request $request, Event $evento)
    {
        $this->authorize('manageJuries', $evento);
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if event already has 3 juries
        if ($evento->juries()->count() >= 3) {
            return redirect()->back()->withErrors([
                'user_id' => 'Este evento ya tiene el máximo de 3 jurados asignados.'
            ]);
        }

        // Check if user is already a jury for this event
        if ($evento->juries()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()->withErrors([
                'user_id' => 'Este usuario ya es jurado de este evento.'
            ]);
        }

        $evento->juries()->attach($request->user_id);

        return redirect()->back()->with('success', 'Jurado asignado exitosamente.');
    }

    /**
     * Remove a jury from an event
     */
    public function removeJury(Event $evento, User $user)
    {
        $this->authorize('manageJuries', $evento);
        $evento->juries()->detach($user->id);

        return redirect()->back()->with('success', 'Jurado removido exitosamente.');
    }

}
