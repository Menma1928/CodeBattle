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
use App\Models\User;

class EventController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Actualiza los estados de eventos que no están finalizados
     */
    private function updateEventStatuses()
    {
        Event::whereIn('estado', ['pendiente', 'activo', 'en_calificacion'])
            ->get()
            ->each(function ($event) {
                $currentState = $event->getCurrentState();
                if ($event->estado !== $currentState) {
                    $event->update(['estado' => $currentState]);
                }
            });
    }
    
    public function index(Request $request){
        // Actualizar estados de eventos automáticamente
        $this->updateEventStatuses();
        
        $title = "Eventos";
        $query = Event::with('admin', 'teams'); // Eager loading

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
        // Actualizar estados de eventos automáticamente
        $this->updateEventStatuses();
        
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
        // Crear evento con estado 'pendiente' temporalmente
        $event = Event::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'direccion' => $request->direccion,
            'estado' => 'pendiente',
            'url_imagen' => null,
            'admin_id' => auth()->id(),
        ]);

        // Actualizar estado automáticamente basado en fechas
        $event->update([
            'estado' => $event->getCurrentState()
        ]);

        // Manejar subida de imagen si existe (después de crear el evento para tener el ID)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'image.' . $extension;

            // Asegurar que el directorio existe
            $directory = 'events/' . $event->id;
            \Storage::disk('public')->makeDirectory($directory);

            // Guardar en storage/app/public/events/{event_id}/image.ext
            $image->storeAs($directory, $imageName, 'public');

            // Guardar ruta relativa en BD: events/{event_id}/image.ext
            $event->update([
                'url_imagen' => 'events/' . $event->id . '/' . $imageName
            ]);
        }

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
        $updateData = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'direccion' => $request->direccion,
            // El estado se actualiza automáticamente según las fechas, no desde el formulario
        ];

        // Manejar subida de nueva imagen si existe
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'image.' . $extension;

            // Eliminar imagen anterior si existe (toda la carpeta del evento)
            if ($evento->url_imagen) {
                \Storage::disk('public')->deleteDirectory('events/' . $evento->id);
            }

            // Asegurar que el directorio existe
            $directory = 'events/' . $evento->id;
            \Storage::disk('public')->makeDirectory($directory);

            // Guardar en storage/app/public/events/{event_id}/image.ext
            $image->storeAs($directory, $imageName, 'public');

            // Guardar ruta relativa en BD: events/{event_id}/image.ext
            $updateData['url_imagen'] = 'events/' . $evento->id . '/' . $imageName;
        }

        $evento->update($updateData);

        // Actualizar reglas: eliminar las existentes y crear las nuevas
        $evento->eventRules()->delete();
        if ($request->has('reglas')) {
            foreach ($request->reglas as $regla) {
                if (!empty($regla)) {
                    EventRule::create([
                        'event_id' => $evento->id,
                        'regla' => $regla,
                    ]);
                }
            }
        }

        // Actualizar requisitos: eliminar los existentes y crear los nuevos
        $evento->requirements()->delete();
        if ($request->has('requisitos')) {
            foreach ($request->requisitos as $requisito) {
                if (!empty($requisito)) {
                    Requirement::create([
                        'event_id' => $evento->id,
                        'name' => $requisito,
                        'description' => null,
                    ]);
                }
            }
        }

        return redirect()->route('eventos.index');
    }

    public function destroy(Event $evento){
        $this->authorize('delete', $evento);
        $evento->delete();
        return redirect()->route('eventos.index');
    }

    public function show(Event $evento){
        // Actualizar estado del evento automáticamente
        $currentState = $evento->getCurrentState();
        if ($evento->estado !== $currentState && in_array($evento->estado, ['pendiente', 'activo', 'en_calificacion'])) {
            $evento->update(['estado' => $currentState]);
            $evento->refresh();
        }
        
        $evento->load('eventRules', 'requirements', 'juries', 'admin');
        $user_is_admin = auth()->id() === $evento->admin_id;
        $user_is_jury = $evento->juries()->where('user_id', auth()->id())->exists();
        $user_team = null;

        if (!$user_is_admin) {
            $user_team = auth()->user()->teams()
                ->where('event_id', $evento->id)
                ->with('users')
                ->first();
        }

        // Si el evento está finalizado, mostrar tabla de ganadores
        if ($evento->estado === 'finalizado') {
            $teams = Team::with(['users' => function($query) {
                    $query->wherePivot('rol', 'lider');
                }, 'project.requirements'])
                ->where('event_id', $evento->id)
                ->whereNotNull('posicion')
                ->orderBy('posicion', 'asc')
                ->get();

            // Calcular calificación promedio para cada equipo
            foreach ($teams as $team) {
                $team->average_rating = $team->project ? $team->project->getAverageRating() : 0;
                $team->leader_name = $team->users->first()?->name ?? 'Sin líder';
            }

            return view('eventos.finalizado', compact('evento', 'teams', 'user_is_admin', 'user_is_jury', 'user_team'));
        }

        // Vista normal para eventos no finalizados
        $teams = Team::with('users', 'project')
            ->where('event_id', $evento->id)
            ->paginate(5);

        return view('eventos.evento', compact('evento', 'teams', 'user_is_admin', 'user_team', 'user_is_jury'));
    }

    /**
     * Show jury management page for an event
     */
    public function manageJuries(Event $evento)
    {
        $this->authorize('manageJuries', $evento);
        $evento->load('juries');

        // Solo mostrar usuarios con rol de Administrador o Super Admin, excluyendo al creador del evento
        $availableUsers = User::whereDoesntHave('juryEvents', function($query) use ($evento) {
            $query->where('event_id', $evento->id);
        })
        ->where('id', '!=', $evento->admin_id)
        ->where(function($query) {
            $query->whereHas('roles', function($q) {
                $q->whereIn('name', ['Administrador', 'Super Admin']);
            });
        })
        ->get();

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

        $user = User::findOrFail($request->user_id);

        // Validar que el usuario tenga rol de Administrador
        if (!$user->hasRole('Administrador') && !$user->hasRole('Super Admin')) {
            return redirect()->back()->withErrors([
                'user_id' => 'Solo usuarios con rol de "Administrador de Eventos" pueden ser asignados como jurados.'
            ]);
        }

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

    /**
     * Dashboard del administrador del evento
     */
    public function dashboard(Event $evento)
    {
        $this->authorize('update', $evento);

        $evento->load('requirements', 'juries');

        // Obtener todos los equipos con sus proyectos y calificaciones
        $teams = Team::with([
            'users' => function($query) {
                $query->wherePivot('rol', 'lider');
            },
            'project.requirements',
            'project.juryRatings.jury',
            'project.juryRatings.requirement'
        ])
        ->where('event_id', $evento->id)
        ->get();

        // Calcular calificaciones promedio
        foreach ($teams as $team) {
            if ($team->project) {
                $team->average_rating = $team->project->getAverageRating();
                $team->leader_name = $team->users->first()?->name ?? 'Sin líder';

                // Verificar si todos los jurados han calificado
                $totalJuries = $evento->juries->count();
                $totalRequirements = $evento->requirements->count();
                $expectedRatings = $totalJuries * $totalRequirements;
                $actualRatings = $team->project->juryRatings->count();
                $team->all_rated = $actualRatings >= $expectedRatings;
            } else {
                $team->average_rating = 0;
                $team->leader_name = $team->users->first()?->name ?? 'Sin líder';
                $team->all_rated = false;
            }
        }

        // Ordenar por calificación promedio (descendente)
        $teams = $teams->sortByDesc('average_rating')->values();

        return view('eventos.dashboard', compact('evento', 'teams'));
    }

    /**
     * Asignar posiciones a los equipos
     */
    public function assignPositions(Request $request, Event $evento)
    {
        $this->authorize('update', $evento);

        $request->validate([
            'positions' => 'required|array',
            'positions.*' => 'required|integer|min:1',
        ]);

        foreach ($request->positions as $teamId => $position) {
            Team::where('id', $teamId)->update(['posicion' => $position]);
        }

        return redirect()->back()->with('success', 'Posiciones asignadas exitosamente.');
    }

    /**
     * Finalizar el evento (cambiar estado a finalizado)
     */
    public function finalize(Event $evento)
    {
        $this->authorize('update', $evento);

        // Verificar que el evento esté en calificación
        if ($evento->estado !== 'en_calificacion') {
            return redirect()->back()->with('error', 'Solo se pueden finalizar eventos en estado de calificación.');
        }

        // Cambiar estado a finalizado
        $evento->update(['estado' => 'finalizado']);

        return redirect()->route('eventos.show', $evento)->with('success', 'El evento ha sido finalizado exitosamente. Los jurados ya no podrán calificar.');
    }
    public function generateCertificate(Event $evento)
    {
        // Verify event is finished
        if ($evento->estado !== 'finalizado') {
            return redirect()->back()->withErrors([
                'error' => 'Solo se pueden generar constancias de eventos finalizados.'
            ]);
        }

        // Get user's team in this event
        $team = auth()->user()->teams()
            ->where('event_id', $evento->id)
            ->first();

        if (!$team) {
            return redirect()->back()->withErrors([
                'error' => 'No participaste en este evento.'
            ]);
        }

        // Load event data
        $evento->load('admin');

        // Prepare data for PDF
        $data = [
            'evento' => $evento,
            'team' => $team,
            'user' => auth()->user(),
            'fecha_generacion' => now()->format('d/m/Y'),
        ];

        // Generate PDF
        $pdf = \PDF::loadView('pdf.certificate', $data);

        // Return PDF download
        return $pdf->download('Constancia_' . $evento->nombre . '_' . $team->nombre . '.pdf');
    }

    /**
     * Generate PDF report with all event statistics
     */
    public function generateReportPDF(Event $evento)
    {
        $this->authorize('update', $evento);

        $evento->load('requirements', 'juries', 'admin');

        // Get all teams with their projects and ratings
        $teams = Team::with([
            'users' => function($query) {
                $query->wherePivot('rol', 'lider');
            },
            'project.requirements',
            'project.juryRatings.jury',
            'project.juryRatings.requirement'
        ])
        ->where('event_id', $evento->id)
        ->get();

        // Calculate ratings
        foreach ($teams as $team) {
            if ($team->project) {
                $team->average_rating = $team->project->getAverageRating();
                $team->leader_name = $team->users->first()?->name ?? 'Sin líder';
            } else {
                $team->average_rating = 0;
                $team->leader_name = $team->users->first()?->name ?? 'Sin líder';
            }
        }

        // Sort by average rating
        $teams = $teams->sortByDesc('average_rating')->values();

        $data = [
            'evento' => $evento,
            'teams' => $teams,
            'fecha_generacion' => now()->format('d/m/Y H:i'),
        ];

        $pdf = \PDF::loadView('pdf.event-report', $data);
        return $pdf->download('Reporte_' . $evento->nombre . '.pdf');
    }

    /**
     * Generate Excel report with all event statistics
     */
    public function generateReportExcel(Event $evento)
    {
        $this->authorize('update', $evento);

        $evento->load('requirements', 'juries', 'admin');

        // Get all teams with their projects and ratings
        $teams = Team::with([
            'users' => function($query) {
                $query->wherePivot('rol', 'lider');
            },
            'project.requirements',
            'project.juryRatings.jury',
            'project.juryRatings.requirement'
        ])
        ->where('event_id', $evento->id)
        ->get();

        // Prepare data for Excel
        $data = [];
        $data[] = ['Reporte de Evento: ' . $evento->nombre];
        $data[] = ['Fecha de generación: ' . now()->format('d/m/Y H:i')];
        $data[] = ['Administrador: ' . $evento->admin->name];
        $data[] = [];
        
        // Headers
        $headers = ['#', 'Equipo', 'Líder', 'Calificación Promedio', 'Posición'];
        foreach ($evento->requirements as $req) {
            $headers[] = $req->nombre;
        }
        $data[] = $headers;

        // Team data
        foreach ($teams as $index => $team) {
            $row = [
                $index + 1,
                $team->nombre,
                $team->users->first()?->name ?? 'Sin líder',
                $team->project ? $team->project->getAverageRating() : 0,
                $team->posicion ?? '-'
            ];

            // Add requirement averages
            if ($team->project) {
                foreach ($evento->requirements as $req) {
                    $row[] = $team->project->getRequirementAverage($req->id);
                }
            } else {
                foreach ($evento->requirements as $req) {
                    $row[] = '-';
                }
            }

            $data[] = $row;
        }

        // Create CSV content
        $filename = 'Reporte_' . $evento->nombre . '_' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Generate all certificates as ZIP file
     */
    public function generateAllCertificates(Event $evento)
    {
        $this->authorize('update', $evento);

        if ($evento->estado !== 'finalizado') {
            return redirect()->back()->withErrors([
                'error' => 'Solo se pueden generar constancias de eventos finalizados.'
            ]);
        }

        $evento->load('admin');
        $teams = Team::with('users')->where('event_id', $evento->id)->get();

        if ($teams->isEmpty()) {
            return redirect()->back()->withErrors([
                'error' => 'No hay equipos en este evento.'
            ]);
        }

        // Create temporary directory
        $tempDir = storage_path('app/temp/certificates_' . $evento->id);
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        // Generate PDF for each team member
        foreach ($teams as $team) {
            foreach ($team->users as $user) {
                $data = [
                    'evento' => $evento,
                    'team' => $team,
                    'user' => $user,
                    'fecha_generacion' => now()->format('d/m/Y'),
                ];

                $pdf = \PDF::loadView('pdf.certificate', $data);
                $filename = $tempDir . '/' . 'Constancia_' . $team->nombre . '_' . $user->name . '.pdf';
                $pdf->save($filename);
            }
        }

        // Create ZIP file
        $zipFile = storage_path('app/temp/Constancias_' . $evento->nombre . '.zip');
        $zip = new \ZipArchive();
        
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempDir),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        // Download and cleanup
        return response()->download($zipFile)->deleteFileAfterSend(true);
    }

}
