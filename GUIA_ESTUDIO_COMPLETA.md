# GUIA DE ESTUDIO COMPLETA - PROYECTO CODEBATTLE

## Sistema Web para Control de Eventos Educativos
### Laravel 12 + Spatie Permission + DomPDF

---

# INDICE

1. [Estructura del Proyecto](#1-estructura-del-proyecto)
2. [Modelos (Models)](#2-modelos-models)
3. [Controladores (Controllers)](#3-controladores-controllers)
4. [Form Requests (Validaciones)](#4-form-requests-validaciones)
5. [Policies (Autorizacion)](#5-policies-autorizacion)
6. [Migraciones (Base de Datos)](#6-migraciones-base-de-datos)
7. [Factories y Seeders](#7-factories-y-seeders)
8. [Rutas (Routes)](#8-rutas-routes)
9. [Ciclo de Vida del Evento](#9-ciclo-de-vida-del-evento)
10. [Flujos de Negocio](#10-flujos-de-negocio)
11. [Sistema de Roles y Permisos](#11-sistema-de-roles-y-permisos)
12. [Generacion de PDFs](#12-generacion-de-pdfs)
13. [Preguntas Frecuentes del Profesor](#13-preguntas-frecuentes-del-profesor)
14. [Comandos Utiles](#14-comandos-utiles)

---

# 1. ESTRUCTURA DEL PROYECTO

```
CodeBattle/
├── app/                              # Codigo principal de la aplicacion
│   ├── Console/
│   │   └── Commands/
│   │       └── MakeSuperAdmin.php    # Comando artisan para crear super admin
│   ├── Http/
│   │   ├── Controllers/              # Controladores (logica de negocio)
│   │   │   ├── Auth/                 # Controladores de autenticacion (Breeze)
│   │   │   ├── DashboardController.php
│   │   │   ├── EventController.php
│   │   │   ├── JuryRatingController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── TeamController.php
│   │   │   └── TeamJoinRequestController.php
│   │   ├── Requests/                 # Validaciones de formularios
│   │   │   ├── EventStoreRequest.php
│   │   │   ├── EventUpdateRequest.php
│   │   │   ├── ProfileUpdateRequest.php
│   │   │   ├── ProjectStoreRequest.php
│   │   │   ├── ProjectUpdateRequest.php
│   │   │   ├── TeamStoreRequest.php
│   │   │   └── TeamUpdateRequest.php
│   │   └── Middleware/               # Middleware de la aplicacion
│   ├── Models/                       # Modelos Eloquent
│   │   ├── Event.php
│   │   ├── EventRule.php
│   │   ├── Project.php
│   │   ├── ProjectJuryRequirement.php
│   │   ├── Requirement.php
│   │   ├── Team.php
│   │   ├── TeamJoinRequest.php
│   │   └── User.php
│   ├── Policies/                     # Politicas de autorizacion
│   │   ├── EventPolicy.php
│   │   ├── ProjectPolicy.php
│   │   └── TeamPolicy.php
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/                        # Archivos de inicio de Laravel
├── config/                           # Configuracion
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── permission.php               # Configuracion de Spatie Permission
│   └── ...
├── database/
│   ├── factories/                   # Fabricas para datos de prueba
│   │   ├── EventFactory.php
│   │   ├── ProjectFactory.php
│   │   ├── RequirementFactory.php
│   │   ├── TeamFactory.php
│   │   └── UserFactory.php
│   ├── migrations/                  # Estructura de tablas
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2025_12_01_000000_create_events_table.php
│   │   ├── 2025_12_01_000001_create_teams_table.php
│   │   ├── 2025_12_01_002148_create_projects_table.php
│   │   ├── 2025_12_01_194001_create_event_rules_table.php
│   │   ├── 2025_12_01_194428_create_team_user_table.php
│   │   ├── 2025_12_03_212226_create_requirements_table.php
│   │   ├── 2025_12_03_213004_create_project_requirement_table.php
│   │   ├── 2025_12_05_061206_add_github_url_to_projects_table.php
│   │   ├── 2025_12_05_061307_create_event_jury_table.php
│   │   ├── 2025_12_05_061347_create_project_jury_requirement_table.php
│   │   ├── 2025_12_05_061402_add_profile_fields_to_users_table.php
│   │   └── 2025_12_07_054355_create_team_join_requests_table.php
│   └── seeders/                     # Datos iniciales
│       ├── DatabaseSeeder.php
│       ├── EventSeeder.php
│       ├── ProjectSeeder.php
│       ├── RequirementSeeder.php
│       ├── TeamSeeder.php
│       └── UserSeeder.php
├── public/                          # Archivos publicos
│   └── storage/                     # Link simbolico a storage/app/public
├── resources/
│   ├── css/
│   │   └── app.css                  # Estilos Tailwind
│   ├── js/
│   │   └── app.js                   # JavaScript principal
│   └── views/                       # Vistas Blade
│       ├── auth/                    # Login, registro, etc.
│       ├── components/              # Componentes reutilizables
│       ├── equipos/                 # Vistas de equipos
│       ├── eventos/                 # Vistas de eventos
│       ├── jury/                    # Vistas de jurados
│       ├── layouts/                 # Layouts base
│       ├── pdf/                     # Plantillas PDF
│       ├── profile/                 # Perfil de usuario
│       ├── projects/                # Vistas de proyectos
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   ├── auth.php                     # Rutas de autenticacion
│   └── web.php                      # Rutas principales
├── storage/
│   └── app/
│       └── public/
│           ├── events/              # Imagenes de eventos
│           └── teams/               # Banners de equipos
├── tests/                           # Tests automatizados
├── .env                             # Variables de entorno
├── composer.json                    # Dependencias PHP
├── package.json                     # Dependencias JS
├── tailwind.config.js               # Configuracion Tailwind
└── vite.config.js                   # Configuracion Vite
```

---

# 2. MODELOS (Models)

## 2.1 User (Usuario)

**Archivo:** `app/Models/User.php`

**Proposito:** Representa a los usuarios del sistema con autenticacion y roles.

### Atributos (Campos de la tabla):
```php
protected $fillable = [
    'name',          // Nombre completo del usuario
    'email',         // Email unico (para login)
    'password',      // Contraseña hasheada
    'direccion',     // Direccion fisica (opcional)
    'avatar_url',    // URL de foto de perfil (opcional)
    'bio',           // Biografia corta (opcional)
];

protected $hidden = [
    'password',          // No se muestra en JSON
    'remember_token',    // Token de "recordarme"
];

protected $casts = [
    'email_verified_at' => 'datetime',  // Convierte a Carbon
    'password' => 'hashed',             // Hash automatico
];
```

### Relaciones:
```php
// Un usuario puede pertenecer a MUCHOS equipos
// Tabla pivote: team_user (con campo 'rol')
public function teams(): BelongsToMany
{
    return $this->belongsToMany(Team::class)
                ->withPivot('rol')      // Incluye el rol en la relacion
                ->withTimestamps();     // Incluye created_at, updated_at
}

// Un usuario puede CREAR muchos eventos (como administrador)
public function events(): HasMany
{
    return $this->hasMany(Event::class, 'admin_id');
}

// Un usuario puede ser JURADO de muchos eventos
// Tabla pivote: event_jury
public function juryEvents(): BelongsToMany
{
    return $this->belongsToMany(Event::class, 'event_jury');
}

// Un usuario puede tener muchas SOLICITUDES de union a equipos
public function teamJoinRequests(): HasMany
{
    return $this->hasMany(TeamJoinRequest::class);
}
```

### Traits usados:
```php
use HasFactory;      // Permite usar User::factory()
use Notifiable;      // Permite enviar notificaciones
use HasRoles;        // De Spatie Permission - agrega roles y permisos
```

### Ejemplo de uso:
```php
// Crear usuario
$user = User::create([
    'name' => 'Juan Perez',
    'email' => 'juan@example.com',
    'password' => '12345678'  // Se hashea automaticamente
]);

// Asignar rol
$user->assignRole('Participante');

// Verificar rol
if ($user->hasRole('Super Admin')) { ... }

// Verificar permiso
if ($user->hasPermissionTo('crear eventos')) { ... }

// Obtener equipos del usuario
$equipos = $user->teams;

// Obtener eventos que administra
$misEventos = $user->events;
```

---

## 2.2 Event (Evento)

**Archivo:** `app/Models/Event.php`

**Proposito:** Representa eventos/competencias con ciclo de vida automatizado.

### Atributos:
```php
protected $fillable = [
    'nombre',        // Nombre del evento
    'descripcion',   // Descripcion detallada
    'fecha_inicio',  // Cuando comienza
    'fecha_fin',     // Cuando termina
    'direccion',     // Ubicacion fisica
    'estado',        // pendiente|activo|en_calificacion|finalizado
    'url_imagen',    // Imagen/banner del evento
    'admin_id',      // Usuario que lo creo (FK)
];

protected $casts = [
    'fecha_inicio' => 'datetime',  // Convierte a Carbon
    'fecha_fin' => 'datetime',
];
```

### Estados del Evento:
```
'pendiente'        -> El evento aun no ha comenzado
                      Se pueden: crear equipos, enviar solicitudes

'activo'           -> El evento esta en curso
                      Se pueden: subir proyectos, editar GitHub URL
                      NO se pueden: crear equipos

'en_calificacion'  -> El evento termino, es hora de calificar
                      Se pueden: calificar proyectos
                      NO se pueden: editar proyectos

'finalizado'       -> Evento completamente terminado
                      Se pueden: descargar constancias, ver ranking
                      NO se puede: modificar nada
```

### Boot del Modelo (Auto-actualizacion de estado):
```php
protected static function booted()
{
    // Cada vez que se OBTIENE un evento de la BD...
    static::retrieved(function ($event) {
        // Si no esta finalizado, verificar si debe cambiar estado
        if (in_array($event->estado, ['pendiente', 'activo', 'en_calificacion'])) {
            $currentState = $event->getCurrentState();
            if ($event->estado !== $currentState) {
                // Actualizar sin disparar eventos (quietly)
                $event->updateQuietly(['estado' => $currentState]);
            }
        }
    });
}
```

### Relaciones:
```php
// Un evento tiene MUCHOS equipos
public function teams(): HasMany
{
    return $this->hasMany(Team::class);
}

// Un evento PERTENECE A un usuario (admin)
public function admin(): BelongsTo
{
    return $this->belongsTo(User::class, 'admin_id');
}

// Un evento tiene MUCHAS reglas
public function eventRules(): HasMany
{
    return $this->hasMany(EventRule::class);
}

// Un evento tiene MUCHOS requisitos (criterios de calificacion)
public function requirements(): HasMany
{
    return $this->hasMany(Requirement::class);
}

// Un evento tiene MUCHOS jurados
// Tabla pivote: event_jury
public function juries(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'event_jury');
}
```

### Metodos importantes:
```php
// ¿El evento esta activo AHORA?
public function isActive(): bool
{
    $now = now();
    return $now >= $this->fecha_inicio &&
           ($this->fecha_fin === null || $now <= $this->fecha_fin);
}

// ¿El evento ya termino?
public function hasEnded(): bool
{
    return $this->fecha_fin !== null && now() > $this->fecha_fin;
}

// ¿El evento aun no comienza?
public function isPending(): bool
{
    return now() < $this->fecha_inicio;
}

// Obtener el estado ACTUAL basado en fechas
public function getCurrentState(): string
{
    if ($this->estado === 'finalizado') {
        return 'finalizado';  // Respetar si ya fue finalizado manualmente
    }

    if ($this->isPending()) {
        return 'pendiente';
    }

    if ($this->isActive()) {
        return 'activo';
    }

    if ($this->hasEnded()) {
        return 'en_calificacion';
    }

    return $this->estado;
}

// ¿Se pueden editar calificaciones?
public function canEditRatings(): bool
{
    return $this->estado === 'en_calificacion';
}

// ¿Se pueden editar proyectos?
public function canEditProjects(): bool
{
    return in_array($this->estado, ['activo', 'en_calificacion']);
}

// ¿Se pueden unir/crear equipos?
public function canJoinTeams(): bool
{
    return $this->estado === 'pendiente';
}
```

### Ejemplo de uso:
```php
// Crear evento
$evento = Event::create([
    'nombre' => 'Hackathon 2025',
    'descripcion' => 'Competencia de programacion',
    'fecha_inicio' => '2025-01-15 08:00:00',
    'fecha_fin' => '2025-01-17 18:00:00',
    'direccion' => 'Universidad XYZ',
    'admin_id' => auth()->id()
]);

// Obtener equipos del evento
$equipos = $evento->teams;

// Verificar si se pueden crear equipos
if ($evento->canJoinTeams()) {
    // Mostrar boton "Crear Equipo"
}

// Obtener jurados
$jurados = $evento->juries;
```

---

## 2.3 Team (Equipo)

**Archivo:** `app/Models/Team.php`

**Proposito:** Representa equipos que participan en eventos.

### Atributos:
```php
protected $fillable = [
    'nombre',       // Nombre del equipo
    'descripcion',  // Descripcion
    'posicion',     // Posicion final (1, 2, 3...) - null si no ha terminado
    'url_banner',   // Imagen del equipo
    'event_id',     // Evento al que pertenece (FK)
];
```

### Relaciones:
```php
// Un equipo PERTENECE A un evento
public function event(): BelongsTo
{
    return $this->belongsTo(Event::class);
}

// Un equipo tiene MUCHOS usuarios (miembros)
// Tabla pivote: team_user (con campo 'rol')
public function users(): BelongsToMany
{
    return $this->belongsToMany(User::class)
                ->withPivot('rol')
                ->withTimestamps();
}

// Un equipo tiene UN proyecto
public function project(): HasOne
{
    return $this->hasOne(Project::class);
}

// Un equipo tiene MUCHAS solicitudes de union
public function joinRequests(): HasMany
{
    return $this->hasMany(TeamJoinRequest::class);
}

// Solo solicitudes PENDIENTES
public function pendingJoinRequests(): HasMany
{
    return $this->hasMany(TeamJoinRequest::class)
                ->where('status', 'pending');
}
```

### Metodos importantes:
```php
// Obtener el LIDER del equipo
public function leader()
{
    return $this->users()
                ->wherePivot('rol', 'lider')
                ->first();
}

// ¿Este usuario es lider?
public function isLeader($userId): bool
{
    return $this->users()
                ->wherePivot('user_id', $userId)
                ->wherePivot('rol', 'lider')
                ->exists();
}
```

### Roles de miembros:
```
'lider'         -> Creador del equipo, puede editar todo
'miembro'       -> Miembro normal
'por asignar'   -> Recien aceptado, sin rol especifico
'desarrollador' -> Rol personalizado
'diseñador'     -> Rol personalizado
```

### Ejemplo de uso:
```php
// Crear equipo
$equipo = Team::create([
    'nombre' => 'Los Programadores',
    'descripcion' => 'Equipo de desarrollo',
    'event_id' => $evento->id
]);

// Agregar miembro como lider
$equipo->users()->attach(auth()->id(), ['rol' => 'lider']);

// Obtener lider
$lider = $equipo->leader();

// Verificar si soy lider
if ($equipo->isLeader(auth()->id())) {
    // Mostrar opciones de edicion
}

// Obtener proyecto
$proyecto = $equipo->project;

// Obtener solicitudes pendientes
$solicitudes = $equipo->pendingJoinRequests;
```

---

## 2.4 Project (Proyecto)

**Archivo:** `app/Models/Project.php`

**Proposito:** Representa proyectos presentados por equipos.

### Atributos:
```php
protected $fillable = [
    'nombre',            // Nombre del proyecto
    'descripcion',       // Descripcion detallada
    'estado_validacion', // Estado de validacion (opcional)
    'url_archivo',       // URL de archivo descargable (opcional)
    'github_url',        // URL del repositorio GitHub
    'fecha_subida',      // Cuando se subio
    'team_id',           // Equipo propietario (FK)
];

protected $casts = [
    'fecha_subida' => 'datetime',
];
```

### Relaciones:
```php
// Un proyecto PERTENECE A un equipo
public function team(): BelongsTo
{
    return $this->belongsTo(Team::class);
}

// Un proyecto tiene MUCHOS requisitos (con calificacion promedio)
// Tabla pivote: project_requirement
public function requirements(): BelongsToMany
{
    return $this->belongsToMany(Requirement::class, 'project_requirement')
                ->withPivot('rating')
                ->withTimestamps();
}

// Calificaciones de CADA jurado para CADA requisito
public function juryRatings(): HasMany
{
    return $this->hasMany(ProjectJuryRequirement::class);
}
```

### Metodos importantes:
```php
// Obtener PROMEDIO GENERAL de todas las calificaciones
public function getAverageRating(): float
{
    $avg = $this->requirements()->avg('project_requirement.rating');
    return round($avg ?? 0, 2);
}

// Obtener promedio de UN requisito especifico
public function getRequirementAverage($requirementId): float
{
    $requirement = $this->requirements()
                        ->where('requirements.id', $requirementId)
                        ->first();

    return $requirement ? ($requirement->pivot->rating ?? 0) : 0;
}
```

### Ejemplo de uso:
```php
// Crear proyecto
$proyecto = Project::create([
    'nombre' => 'Sistema de Gestion',
    'descripcion' => 'App para gestionar tareas',
    'github_url' => 'https://github.com/user/repo',
    'fecha_subida' => now(),
    'team_id' => $equipo->id
]);

// Obtener equipo del proyecto
$equipo = $proyecto->team;

// Obtener promedio de calificacion
$promedio = $proyecto->getAverageRating();

// Obtener calificaciones de jurados
$calificaciones = $proyecto->juryRatings;
```

---

## 2.5 Requirement (Requisito/Criterio)

**Archivo:** `app/Models/Requirement.php`

**Proposito:** Criterios de evaluacion para proyectos.

### Atributos:
```php
protected $fillable = [
    'name',         // Nombre del requisito (ej: "Funcionalidad")
    'description',  // Descripcion del criterio
    'event_id',     // Evento al que pertenece (FK)
];
```

### Relaciones:
```php
// Un requisito PERTENECE A un evento
public function event(): BelongsTo
{
    return $this->belongsTo(Event::class);
}

// Un requisito se aplica a MUCHOS proyectos
public function projects(): BelongsToMany
{
    return $this->belongsToMany(Project::class, 'project_requirement')
                ->withPivot('rating')
                ->withTimestamps();
}

// Calificaciones individuales de jurados
public function juryRatings(): HasMany
{
    return $this->hasMany(ProjectJuryRequirement::class);
}
```

### Ejemplo:
```php
// Crear requisitos para un evento
Requirement::create(['name' => 'Funcionalidad', 'description' => 'El sistema funciona correctamente', 'event_id' => $evento->id]);
Requirement::create(['name' => 'Diseño UI/UX', 'description' => 'Interfaz atractiva y usable', 'event_id' => $evento->id]);
Requirement::create(['name' => 'Documentacion', 'description' => 'Codigo documentado', 'event_id' => $evento->id]);
```

---

## 2.6 TeamJoinRequest (Solicitud de Union)

**Archivo:** `app/Models/TeamJoinRequest.php`

**Proposito:** Solicitudes de usuarios para unirse a equipos.

### Atributos:
```php
protected $fillable = [
    'team_id',   // Equipo al que quiere unirse (FK)
    'user_id',   // Usuario que solicita (FK)
    'status',    // pending|accepted|rejected
    'message',   // Mensaje del solicitante (opcional)
];
```

### Estados:
```
'pending'   -> Esperando respuesta del lider
'accepted'  -> Lider acepto, usuario ya es miembro
'rejected'  -> Lider rechazo la solicitud
```

### Relaciones:
```php
public function team(): BelongsTo
{
    return $this->belongsTo(Team::class);
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

### Scopes (Filtros reutilizables):
```php
// Uso: TeamJoinRequest::pending()->get()
public function scopePending($query)
{
    return $query->where('status', 'pending');
}

public function scopeAccepted($query)
{
    return $query->where('status', 'accepted');
}

public function scopeRejected($query)
{
    return $query->where('status', 'rejected');
}
```

---

## 2.7 ProjectJuryRequirement (Calificacion de Jurado)

**Archivo:** `app/Models/ProjectJuryRequirement.php`

**Proposito:** Almacena cada calificacion que un jurado da a un proyecto en un requisito.

### Atributos:
```php
protected $table = 'project_jury_requirement'; // Nombre de tabla personalizado

protected $fillable = [
    'project_id',      // Proyecto calificado (FK)
    'requirement_id',  // Requisito evaluado (FK)
    'jury_id',         // Jurado que califica (FK -> users)
    'rating',          // Calificacion 1-10
];

protected $casts = [
    'rating' => 'integer',
];
```

### Relaciones:
```php
public function project(): BelongsTo
{
    return $this->belongsTo(Project::class);
}

public function requirement(): BelongsTo
{
    return $this->belongsTo(Requirement::class);
}

public function jury(): BelongsTo
{
    return $this->belongsTo(User::class, 'jury_id');
}
```

---

## 2.8 EventRule (Regla del Evento)

**Archivo:** `app/Models/EventRule.php`

**Proposito:** Reglas especificas de cada evento.

### Atributos:
```php
protected $fillable = [
    'event_id',  // Evento (FK)
    'regla',     // Texto de la regla
];
```

### Relacion:
```php
public function event(): BelongsTo
{
    return $this->belongsTo(Event::class);
}
```

---

# 3. CONTROLADORES (Controllers)

## 3.1 EventController

**Archivo:** `app/Http/Controllers/EventController.php`

**Responsabilidad:** Gestionar todo lo relacionado con eventos.

### Metodos:

#### index(Request $request)
```php
/**
 * Lista todos los eventos con busqueda y filtros
 *
 * URL: GET /eventos
 * Middleware: permission:ver eventos
 */
public function index(Request $request)
{
    // 1. Actualizar estados automaticamente
    $this->updateEventStatuses();

    // 2. Obtener parametro de busqueda
    $search = $request->input('search');
    $estado = $request->input('estado');

    // 3. Construir query con relaciones
    $query = Event::with(['admin', 'teams']);

    // 4. Aplicar busqueda si existe
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('descripcion', 'like', "%{$search}%")
              ->orWhere('direccion', 'like', "%{$search}%");
        });
    }

    // 5. Filtrar por estado si se especifica
    if ($estado && $estado !== 'todos') {
        $query->where('estado', $estado);
    }

    // 6. Paginar resultados
    $events = $query->orderBy('fecha_inicio', 'desc')
                    ->paginate(10);

    // 7. Retornar vista
    return view('eventos.index', [
        'events' => $events,
        'title' => 'Eventos'
    ]);
}
```

#### store(EventStoreRequest $request)
```php
/**
 * Crea un nuevo evento
 *
 * URL: POST /eventos
 * Validacion: EventStoreRequest
 */
public function store(EventStoreRequest $request)
{
    // 1. Crear evento con estado inicial 'pendiente'
    $event = Event::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'direccion' => $request->direccion,
        'estado' => 'pendiente',
        'admin_id' => auth()->id(),
    ]);

    // 2. Actualizar estado segun fechas
    $event->update(['estado' => $event->getCurrentState()]);

    // 3. Manejar imagen si existe
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        // Crear directorio: storage/app/public/events/{id}/
        $directory = "events/{$event->id}";
        Storage::disk('public')->makeDirectory($directory);

        // Guardar archivo
        $path = $file->storeAs($directory, "image.{$extension}", 'public');

        // Actualizar BD con ruta
        $event->update(['url_imagen' => $path]);
    }

    // 4. Crear reglas del evento
    if ($request->has('reglas')) {
        foreach ($request->reglas as $regla) {
            if (!empty($regla)) {
                EventRule::create([
                    'event_id' => $event->id,
                    'regla' => $regla
                ]);
            }
        }
    }

    // 5. Crear requisitos (criterios de calificacion)
    if ($request->has('requisitos')) {
        foreach ($request->requisitos as $requisito) {
            if (!empty($requisito)) {
                Requirement::create([
                    'name' => $requisito,
                    'event_id' => $event->id
                ]);
            }
        }
    }

    return redirect()->route('eventos.index')
                     ->with('success', 'Evento creado exitosamente');
}
```

#### show(Event $evento)
```php
/**
 * Muestra detalle de un evento
 *
 * URL: GET /eventos/{evento}
 */
public function show(Event $evento)
{
    // 1. Actualizar estado si es necesario
    if (in_array($evento->estado, ['pendiente', 'activo', 'en_calificacion'])) {
        $currentState = $evento->getCurrentState();
        if ($evento->estado !== $currentState) {
            $evento->update(['estado' => $currentState]);
        }
    }

    // 2. Cargar relaciones necesarias
    $evento->load(['eventRules', 'requirements', 'juries', 'admin']);

    // 3. Determinar permisos del usuario actual
    $user_is_admin = auth()->id() === $evento->admin_id ||
                     auth()->user()->hasRole('Super Admin');
    $user_is_jury = $evento->juries->contains(auth()->id());

    // 4. Obtener equipo del usuario en este evento (si tiene)
    $user_team = auth()->user()->teams()
                       ->where('event_id', $evento->id)
                       ->first();

    // 5. Si evento finalizado, mostrar vista de ranking
    if ($evento->estado === 'finalizado') {
        $teams = $evento->teams()
                        ->whereNotNull('posicion')
                        ->with(['users', 'project'])
                        ->orderBy('posicion', 'asc')
                        ->get();

        // Calcular promedio y lider para cada equipo
        foreach ($teams as $team) {
            $team->average_rating = $team->project
                ? $team->project->getAverageRating()
                : 0;
            $team->leader_name = $team->leader()?->name ?? 'Sin lider';
        }

        return view('eventos.finalizado', compact(
            'evento', 'teams', 'user_is_admin', 'user_is_jury', 'user_team'
        ));
    }

    // 6. Vista normal
    $teams = $evento->teams()
                    ->with(['users', 'project'])
                    ->paginate(10);

    return view('eventos.evento', compact(
        'evento', 'teams', 'user_is_admin', 'user_is_jury', 'user_team'
    ));
}
```

#### dashboard(Event $evento)
```php
/**
 * Panel de administracion del evento
 * Muestra calificaciones y estado de equipos
 *
 * URL: GET /eventos/{evento}/dashboard
 * Solo: admin del evento
 */
public function dashboard(Event $evento)
{
    $this->authorize('update', $evento);

    // Cargar equipos con todas las relaciones necesarias
    $teams = $evento->teams()
        ->with([
            'users' => function ($query) {
                $query->wherePivot('rol', 'lider');
            },
            'project.requirements',
            'project.juryRatings.jury'
        ])
        ->get();

    // Calcular datos para cada equipo
    $totalJuries = $evento->juries->count();
    $totalRequirements = $evento->requirements->count();

    foreach ($teams as $team) {
        // Promedio de calificacion
        $team->average_rating = $team->project
            ? $team->project->getAverageRating()
            : 0;

        // Nombre del lider
        $team->leader_name = $team->users->first()?->name ?? 'Sin lider';

        // ¿Todos los jurados han calificado todos los requisitos?
        $expectedRatings = $totalJuries * $totalRequirements;
        $actualRatings = $team->project
            ? $team->project->juryRatings->count()
            : 0;
        $team->all_rated = $actualRatings >= $expectedRatings;
    }

    // Ordenar por promedio descendente
    $teams = $teams->sortByDesc('average_rating')->values();

    return view('eventos.dashboard', compact('evento', 'teams'));
}
```

#### generateCertificate(Event $evento)
```php
/**
 * Genera constancia de participacion en PDF
 *
 * URL: GET /eventos/{evento}/certificate
 */
public function generateCertificate(Event $evento)
{
    // 1. Verificar que evento este finalizado
    if ($evento->estado !== 'finalizado') {
        return back()->with('error', 'El evento no ha finalizado');
    }

    // 2. Obtener equipo del usuario
    $team = auth()->user()->teams()
                  ->where('event_id', $evento->id)
                  ->first();

    if (!$team) {
        return back()->with('error', 'No participaste en este evento');
    }

    // 3. Preparar datos para el PDF
    $data = [
        'evento' => $evento->load('admin'),
        'team' => $team,
        'user' => auth()->user(),
        'fecha' => now()->format('d/m/Y'),
    ];

    // 4. Generar PDF desde vista
    $pdf = PDF::loadView('pdf.certificate', $data);

    // 5. Descargar
    $filename = "Constancia_{$evento->nombre}_{$team->nombre}.pdf";
    return $pdf->download($filename);
}
```

---

## 3.2 TeamController

**Archivo:** `app/Http/Controllers/TeamController.php`

### store(TeamStoreRequest $request)
```php
/**
 * Crea un nuevo equipo
 *
 * URL: POST /equipos
 */
public function store(TeamStoreRequest $request)
{
    // 1. Obtener evento
    $event = Event::findOrFail($request->event_id);

    // 2. Verificar que evento este en 'pendiente'
    if (!$event->canJoinTeams()) {
        return back()->with('error',
            'No se pueden crear equipos. El evento no esta en estado pendiente.');
    }

    // 3. Verificar que usuario no este en otro equipo del mismo evento
    $existingTeam = auth()->user()->teams()
                          ->where('event_id', $event->id)
                          ->first();

    if ($existingTeam) {
        return back()->with('error',
            'Ya perteneces a un equipo en este evento.');
    }

    // 4. Crear equipo
    $team = Team::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'event_id' => $event->id,
    ]);

    // 5. Agregar usuario como LIDER
    $team->users()->attach(auth()->id(), ['rol' => 'lider']);

    // 6. Manejar banner si existe
    if ($request->hasFile('banner')) {
        $file = $request->file('banner');
        $extension = $file->getClientOriginalExtension();
        $directory = "teams/{$team->id}";
        Storage::disk('public')->makeDirectory($directory);
        $path = $file->storeAs($directory, "banner.{$extension}", 'public');
        $team->update(['url_banner' => $path]);
    }

    return redirect()->route('equipos.show', $team)
                     ->with('success', 'Equipo creado exitosamente');
}
```

### inviteUser(Team $equipo, Request $request)
```php
/**
 * Invita directamente a un usuario al equipo
 *
 * URL: POST /equipos/{equipo}/invite-user
 * Solo: lider del equipo
 */
public function inviteUser(Team $equipo, Request $request)
{
    // 1. Verificar que soy lider
    if (!$equipo->isLeader(auth()->id())) {
        return response()->json(['error' => 'No autorizado'], 403);
    }

    // 2. Validar user_id
    $request->validate(['user_id' => 'required|exists:users,id']);

    // 3. Verificar evento en pendiente
    if (!$equipo->event->canJoinTeams()) {
        return response()->json([
            'error' => 'El evento no permite unirse a equipos'
        ], 400);
    }

    // 4. Verificar que equipo no tenga 5 miembros
    if ($equipo->users->count() >= 5) {
        return response()->json([
            'error' => 'El equipo ya tiene 5 miembros'
        ], 400);
    }

    // 5. Verificar que usuario no este en equipo
    if ($equipo->users->contains($request->user_id)) {
        return response()->json([
            'error' => 'El usuario ya es miembro'
        ], 400);
    }

    // 6. Verificar que usuario no este en otro equipo del evento
    $user = User::find($request->user_id);
    $otherTeam = $user->teams()
                      ->where('event_id', $equipo->event_id)
                      ->first();

    if ($otherTeam) {
        return response()->json([
            'error' => 'El usuario ya esta en otro equipo de este evento'
        ], 400);
    }

    // 7. Agregar usuario con rol 'miembro'
    $equipo->users()->attach($request->user_id, ['rol' => 'miembro']);

    return response()->json(['success' => true]);
}
```

---

## 3.3 TeamJoinRequestController

**Archivo:** `app/Http/Controllers/TeamJoinRequestController.php`

### store(Request $request, Team $team)
```php
/**
 * Crea solicitud para unirse a un equipo
 *
 * URL: POST /equipos/{team}/join-request
 */
public function store(Request $request, Team $team)
{
    $user = auth()->user();
    $event = $team->event;

    // 1. Verificar evento en pendiente
    if (!$event->canJoinTeams()) {
        return back()->with('error',
            'El evento no permite unirse a equipos.');
    }

    // 2. Verificar que no sea ya miembro
    if ($team->users->contains($user->id)) {
        return back()->with('error',
            'Ya eres miembro de este equipo.');
    }

    // 3. Verificar que no este en otro equipo del evento
    $existingTeam = $user->teams()
                         ->where('event_id', $event->id)
                         ->first();

    if ($existingTeam) {
        return back()->with('error',
            'Ya perteneces a otro equipo en este evento.');
    }

    // 4. Verificar que no tenga solicitud pendiente/aceptada
    $existingRequest = TeamJoinRequest::where('team_id', $team->id)
                                      ->where('user_id', $user->id)
                                      ->whereIn('status', ['pending', 'accepted'])
                                      ->first();

    if ($existingRequest) {
        return back()->with('error',
            'Ya tienes una solicitud para este equipo.');
    }

    // 5. Crear solicitud
    TeamJoinRequest::create([
        'team_id' => $team->id,
        'user_id' => $user->id,
        'status' => 'pending',
        'message' => $request->message,
    ]);

    return back()->with('success',
        'Solicitud enviada. El lider del equipo la revisara.');
}
```

### accept(TeamJoinRequest $request)
```php
/**
 * Acepta una solicitud de union
 *
 * URL: POST /join-requests/{request}/accept
 * Solo: lider del equipo
 */
public function accept(TeamJoinRequest $joinRequest)
{
    $team = $joinRequest->team;
    $user = auth()->user();

    // 1. Verificar que soy lider
    if (!$team->isLeader($user->id)) {
        return back()->with('error', 'Solo el lider puede aceptar solicitudes.');
    }

    // 2. Verificar que solicitud este pendiente
    if ($joinRequest->status !== 'pending') {
        return back()->with('error', 'Esta solicitud ya fue procesada.');
    }

    // 3. Verificar que equipo no tenga 5 miembros
    if ($team->users->count() >= 5) {
        return back()->with('error', 'El equipo ya tiene 5 miembros.');
    }

    // 4. Verificar que solicitante no este en otro equipo del evento
    $applicant = $joinRequest->user;
    $otherTeam = $applicant->teams()
                           ->where('event_id', $team->event_id)
                           ->first();

    if ($otherTeam) {
        $joinRequest->update(['status' => 'rejected']);
        return back()->with('error',
            'El usuario ya se unio a otro equipo.');
    }

    // 5. En transaccion: agregar usuario y actualizar solicitud
    DB::transaction(function () use ($team, $joinRequest) {
        // Agregar con rol 'por asignar'
        $team->users()->attach($joinRequest->user_id, ['rol' => 'por asignar']);

        // Marcar solicitud como aceptada
        $joinRequest->update(['status' => 'accepted']);
    });

    return back()->with('success', 'Usuario aceptado en el equipo.');
}
```

---

## 3.4 JuryRatingController

**Archivo:** `app/Http/Controllers/JuryRatingController.php`

### storeRatings(Request $request, Event $event, Project $project)
```php
/**
 * Guarda calificaciones de un jurado
 *
 * URL: POST /jury/events/{event}/projects/{project}/rate
 * Solo: jurados del evento
 */
public function storeRatings(Request $request, Event $event, Project $project)
{
    $user = auth()->user();

    // 1. Verificar que soy jurado
    if (!$event->juries->contains($user->id)) {
        abort(403, 'No eres jurado de este evento.');
    }

    // 2. Verificar que evento permita calificar
    if (!$event->canEditRatings()) {
        return back()->with('error',
            'El evento no permite editar calificaciones.');
    }

    // 3. Verificar que proyecto pertenezca al evento
    if ($project->team->event_id !== $event->id) {
        abort(404);
    }

    // 4. Construir reglas de validacion dinamicamente
    $rules = [];
    foreach ($event->requirements as $req) {
        $rules["rating_{$req->id}"] = 'required|integer|min:1|max:10';
    }
    $request->validate($rules);

    // 5. Guardar cada calificacion
    foreach ($event->requirements as $requirement) {
        $rating = $request->input("rating_{$requirement->id}");

        // updateOrCreate: actualiza si existe, crea si no
        ProjectJuryRequirement::updateOrCreate(
            [
                'project_id' => $project->id,
                'requirement_id' => $requirement->id,
                'jury_id' => $user->id,
            ],
            ['rating' => $rating]
        );
    }

    // 6. Actualizar promedios en project_requirement
    $this->updateProjectRequirementAverages($project, $event);

    return redirect()->route('jury.event.projects', $event)
                     ->with('success', 'Calificaciones guardadas.');
}

/**
 * Actualiza promedios de calificacion por requisito
 */
private function updateProjectRequirementAverages(Project $project, Event $event)
{
    foreach ($event->requirements as $requirement) {
        // Calcular promedio de todos los jurados para este requisito
        $avg = ProjectJuryRequirement::where('project_id', $project->id)
                                     ->where('requirement_id', $requirement->id)
                                     ->avg('rating');

        // Actualizar tabla pivote project_requirement
        $project->requirements()->syncWithoutDetaching([
            $requirement->id => ['rating' => round($avg, 2)]
        ]);
    }
}
```

---

## 3.5 ProjectController

**Archivo:** `app/Http/Controllers/ProjectController.php`

### store(ProjectStoreRequest $request)
```php
/**
 * Crea un nuevo proyecto
 *
 * URL: POST /projects
 * Solo: lider del equipo
 */
public function store(ProjectStoreRequest $request)
{
    // 1. Obtener equipo
    $team = Team::findOrFail($request->team_id);

    // 2. Verificar que soy lider
    if (!$team->isLeader(auth()->id())) {
        return back()->with('error', 'Solo el lider puede crear proyectos.');
    }

    // 3. Verificar que equipo no tenga proyecto
    if ($team->project) {
        return redirect()->route('projects.edit', $team->project)
                         ->with('info', 'El equipo ya tiene un proyecto.');
    }

    // 4. Crear proyecto
    $project = Project::create([
        'nombre' => $request->nombre,
        'descripcion' => $request->descripcion,
        'github_url' => $request->github_url,
        'team_id' => $team->id,
        'fecha_subida' => now(),
    ]);

    return redirect()->route('projects.show', $project)
                     ->with('success', 'Proyecto creado exitosamente.');
}
```

---

# 4. FORM REQUESTS (Validaciones)

## 4.1 EventStoreRequest

**Archivo:** `app/Http/Requests/EventStoreRequest.php`

```php
class EventStoreRequest extends FormRequest
{
    /**
     * ¿El usuario puede hacer esta solicitud?
     */
    public function authorize(): bool
    {
        // Solo Super Admin o Administrador pueden crear eventos
        return $this->user()->hasRole('Super Admin') ||
               $this->user()->hasRole('Administrador');
    }

    /**
     * Reglas de validacion
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'direccion' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:204800', // 200MB
            'reglas' => 'nullable|array',
            'reglas.*' => 'nullable|string|max:500',
            'requisitos' => 'nullable|array',
            'requisitos.*' => 'nullable|string|max:255',
        ];
    }

    /**
     * Mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'descripcion.required' => 'La descripcion del evento es obligatoria.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha valida.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'direccion.required' => 'La direccion del evento es obligatoria.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPG, JPEG o PNG.',
            'image.max' => 'La imagen no puede exceder 200MB.',
        ];
    }
}
```

## 4.2 TeamStoreRequest

**Archivo:** `app/Http/Requests/TeamStoreRequest.php`

```php
class TeamStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cualquier usuario autenticado
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png|max:204800',
            'event_id' => 'required|exists:events,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'descripcion.required' => 'La descripcion del equipo es obligatoria.',
            'event_id.required' => 'Debes seleccionar un evento.',
            'event_id.exists' => 'El evento seleccionado no existe.',
            'banner.image' => 'El archivo debe ser una imagen.',
            'banner.max' => 'La imagen no puede exceder 200MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del equipo',
            'descripcion' => 'descripcion',
            'banner' => 'banner del equipo',
            'event_id' => 'evento',
        ];
    }
}
```

## 4.3 ProjectStoreRequest

**Archivo:** `app/Http/Requests/ProjectStoreRequest.php`

```php
class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Se valida en controlador con contexto del equipo
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'github_url' => 'nullable|url|max:255',
            'team_id' => 'required|exists:teams,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'descripcion.required' => 'La descripcion del proyecto es obligatoria.',
            'github_url.url' => 'La URL de GitHub debe ser valida.',
            'team_id.required' => 'Debes seleccionar un equipo.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }
}
```

---

# 5. POLICIES (Autorizacion)

## 5.1 EventPolicy

**Archivo:** `app/Policies/EventPolicy.php`

```php
class EventPolicy
{
    /**
     * ¿Puede ver la lista de eventos?
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver eventos');
    }

    /**
     * ¿Puede ver un evento especifico?
     */
    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('ver eventos');
    }

    /**
     * ¿Puede crear eventos?
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') ||
               $user->hasRole('Administrador');
    }

    /**
     * ¿Puede editar este evento?
     */
    public function update(User $user, Event $event): bool
    {
        // Super Admin puede todo
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Solo el admin del evento puede editarlo
        return $user->id === $event->admin_id;
    }

    /**
     * ¿Puede eliminar este evento?
     */
    public function delete(User $user, Event $event): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->id === $event->admin_id;
    }

    /**
     * ¿Puede gestionar jurados?
     */
    public function manageJuries(User $user, Event $event): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $user->id === $event->admin_id;
    }

    /**
     * ¿Puede ver estadisticas del evento?
     */
    public function viewStatistics(User $user, Event $event): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->id === $event->admin_id) {
            return true;
        }

        // Los jurados tambien pueden ver estadisticas
        return $event->juries->contains($user->id);
    }
}
```

## 5.2 TeamPolicy

**Archivo:** `app/Policies/TeamPolicy.php`

```php
class TeamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ver equipos');
    }

    public function view(User $user, Team $team): bool
    {
        return $user->hasPermissionTo('ver equipos');
    }

    public function create(User $user): bool
    {
        return true; // Cualquiera puede crear equipos
    }

    public function update(User $user, Team $team): bool
    {
        // Super Admin puede todo
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // Admin del evento puede editar equipos
        if ($user->id === $team->event->admin_id) {
            return true;
        }

        // El lider puede editar su equipo
        return $team->isLeader($user->id);
    }

    public function delete(User $user, Team $team): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->id === $team->event->admin_id) {
            return true;
        }

        return $team->isLeader($user->id);
    }

    public function manageMembers(User $user, Team $team): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        if ($user->id === $team->event->admin_id) {
            return true;
        }

        return $team->isLeader($user->id);
    }

    public function updateMemberRole(User $user, Team $team): bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return $team->isLeader($user->id);
    }

    public function leave(User $user, Team $team): bool
    {
        // Solo miembros que NO son lideres pueden abandonar
        $isMember = $team->users->contains($user->id);
        $isLeader = $team->isLeader($user->id);

        return $isMember && !$isLeader;
    }
}
```

---

# 6. MIGRACIONES (Base de Datos)

## 6.1 Tabla: users

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();                                    // BIGINT PRIMARY KEY
    $table->string('name');                          // VARCHAR(255)
    $table->string('email')->unique();               // VARCHAR(255) UNIQUE
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');                      // VARCHAR(255)
    $table->string('direccion')->nullable();         // VARCHAR(255) NULL
    $table->string('avatar_url')->nullable();        // VARCHAR(255) NULL
    $table->text('bio')->nullable();                 // TEXT NULL
    $table->rememberToken();                         // VARCHAR(100) NULL
    $table->timestamps();                            // created_at, updated_at
});
```

## 6.2 Tabla: events

```php
Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->dateTime('fecha_inicio');
    $table->dateTime('fecha_fin')->nullable();
    $table->string('direccion')->nullable();
    $table->string('estado')->default('pendiente');  // Estados del evento
    $table->string('url_imagen')->nullable();
    $table->foreignId('admin_id')                    // FK -> users
          ->constrained('users')
          ->onDelete('cascade');
    $table->timestamps();
});
```

## 6.3 Tabla: teams

```php
Schema::create('teams', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->integer('posicion')->nullable();         // Ranking final
    $table->string('url_banner')->nullable();
    $table->foreignId('event_id')                    // FK -> events
          ->constrained('events')
          ->onDelete('cascade');
    $table->timestamps();
});
```

## 6.4 Tabla: projects

```php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->text('descripcion')->nullable();
    $table->string('estado_validacion')->nullable();
    $table->string('url_archivo')->nullable();
    $table->string('github_url')->nullable();        // URL de GitHub
    $table->dateTime('fecha_subida')->nullable();
    $table->foreignId('team_id')
          ->constrained('teams')
          ->onDelete('cascade');
    $table->timestamps();
});
```

## 6.5 Tabla: team_user (Pivote)

```php
Schema::create('team_user', function (Blueprint $table) {
    $table->id();
    $table->foreignId('team_id')
          ->constrained('teams')
          ->onDelete('cascade');
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');
    $table->string('rol')->default('por asignar');   // lider, miembro, etc.
    $table->timestamps();
});
```

## 6.6 Tabla: requirements

```php
Schema::create('requirements', function (Blueprint $table) {
    $table->id();
    $table->string('name');                          // Nombre del criterio
    $table->text('description')->nullable();
    $table->foreignId('event_id')
          ->constrained('events')
          ->onDelete('cascade');
    $table->timestamps();
});
```

## 6.7 Tabla: project_requirement (Pivote)

```php
Schema::create('project_requirement', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')
          ->constrained('projects')
          ->onDelete('cascade');
    $table->foreignId('requirement_id')
          ->constrained('requirements')
          ->onDelete('cascade');
    $table->integer('rating')->nullable();           // Promedio de calificaciones
    $table->timestamps();
});
```

## 6.8 Tabla: event_jury (Pivote)

```php
Schema::create('event_jury', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')
          ->constrained('events')
          ->onDelete('cascade');
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');
    $table->timestamps();

    $table->unique(['event_id', 'user_id']);         // No repetir jurados
});
```

## 6.9 Tabla: project_jury_requirement

```php
Schema::create('project_jury_requirement', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')
          ->constrained('projects')
          ->onDelete('cascade');
    $table->foreignId('requirement_id')
          ->constrained('requirements')
          ->onDelete('cascade');
    $table->foreignId('jury_id')                     // FK -> users
          ->constrained('users')
          ->onDelete('cascade');
    $table->unsignedTinyInteger('rating');           // 1-10
    $table->timestamps();
});
```

## 6.10 Tabla: team_join_requests

```php
Schema::create('team_join_requests', function (Blueprint $table) {
    $table->id();
    $table->foreignId('team_id')
          ->constrained('teams')
          ->onDelete('cascade');
    $table->foreignId('user_id')
          ->constrained('users')
          ->onDelete('cascade');
    $table->enum('status', ['pending', 'accepted', 'rejected'])
          ->default('pending');
    $table->text('message')->nullable();
    $table->timestamps();

    $table->unique(['team_id', 'user_id']);          // Una solicitud por usuario/equipo
});
```

## 6.11 Tabla: event_rules

```php
Schema::create('event_rules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('event_id')
          ->constrained('events')
          ->onDelete('cascade');
    $table->text('regla');
    $table->timestamps();
});
```

---

# 7. FACTORIES Y SEEDERS

## 7.1 UserSeeder

**Archivo:** `database/seeders/UserSeeder.php`

```php
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR ROLES
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $administrador = Role::firstOrCreate(['name' => 'Administrador']);
        $participante = Role::firstOrCreate(['name' => 'Participante']);

        // 2. CREAR PERMISOS

        // Permisos de Eventos
        $eventPermissions = [
            'ver eventos',
            'crear eventos',
            'editar eventos',
            'eliminar eventos',
            'ver mis eventos',
            'inscribirse eventos',
        ];

        // Permisos de Equipos
        $teamPermissions = [
            'ver equipos',
            'crear equipos',
            'editar equipos',
            'eliminar equipos',
            'ver mis equipos',
            'unirse equipos',
            'invitar miembros',
            'expulsar miembros',
        ];

        // Permisos de Usuarios
        $userPermissions = [
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'asignar roles',
        ];

        // Permisos de Participacion
        $participationPermissions = [
            'participar competencias',
            'enviar soluciones',
            'ver resultados',
            'ver ranking',
        ];

        // Permisos de Administracion
        $adminPermissions = [
            'gestionar permisos',
            'ver reportes',
            'moderar contenido',
        ];

        // Crear todos los permisos
        $allPermissions = array_merge(
            $eventPermissions,
            $teamPermissions,
            $userPermissions,
            $participationPermissions,
            $adminPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. ASIGNAR PERMISOS A ROLES

        // Super Admin: TODOS los permisos
        $superAdmin->syncPermissions($allPermissions);

        // Administrador: permisos selectos
        $administrador->syncPermissions([
            'ver eventos', 'crear eventos', 'ver mis eventos',
            'ver equipos', 'ver mis equipos', 'expulsar miembros',
            'ver usuarios',
            'ver reportes',
        ]);

        // Participante: permisos basicos
        $participante->syncPermissions([
            'ver eventos', 'inscribirse eventos',
            'ver equipos', 'crear equipos', 'ver mis equipos',
            'unirse equipos', 'invitar miembros',
            'participar competencias', 'enviar soluciones',
            'ver resultados', 'ver ranking',
        ]);

        // 4. CREAR USUARIOS DE PRUEBA

        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');

        // Administrador
        $adminUser = User::firstOrCreate(
            ['email' => 'administrador@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('Administrador');

        // Participante
        $participant = User::firstOrCreate(
            ['email' => 'participante@gmail.com'],
            [
                'name' => 'Participante',
                'password' => bcrypt('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $participant->assignRole('Participante');

        // 5. CREAR USUARIOS ADICIONALES

        // Participantes adicionales
        $participantCount = User::role('Participante')->count();
        if ($participantCount < 83) {
            $toCreate = 83 - $participantCount;
            User::factory($toCreate)->create()->each(function ($user) {
                $user->assignRole('Participante');
            });
        }

        // Administradores adicionales
        $adminCount = User::role('Administrador')->count();
        if ($adminCount < 80) {
            $toCreate = 80 - $adminCount;
            User::factory($toCreate)->create()->each(function ($user) {
                $user->assignRole('Administrador');
            });
        }
    }
}
```

## 7.2 EventFactory

**Archivo:** `database/factories/EventFactory.php`

```php
class EventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'fecha_inicio' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'fecha_fin' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'direccion' => fake()->address(),
            'estado' => fake()->randomElement(['activo', 'pendiente', 'finalizado']),
            'url_imagen' => 'https://placehold.co/600x600',
            'admin_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
```

## 7.3 TeamFactory

```php
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->company(),
            'descripcion' => fake()->paragraph(),
            'posicion' => fake()->randomDigit(),
            'url_banner' => 'https://placehold.co/400x400',
            'event_id' => Event::inRandomOrder()->first()->id,
        ];
    }
}
```

---

# 8. RUTAS (Routes)

**Archivo:** `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JuryRatingController;
use App\Http\Controllers\TeamJoinRequestController;
use App\Http\Controllers\ProfileController;

// =============================================
// RUTAS PUBLICAS
// =============================================

Route::get('/', function () {
    return view('welcome');
});

// =============================================
// RUTAS AUTENTICADAS
// =============================================

Route::middleware('auth')->group(function () {

    // -----------------------------------------
    // DASHBOARD
    // -----------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');

    // -----------------------------------------
    // PERFIL DE USUARIO
    // -----------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
         ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
         ->name('profile.destroy');
    Route::get('/users/{user}', [ProfileController::class, 'show'])
         ->name('users.show');

    // -----------------------------------------
    // EVENTOS
    // -----------------------------------------
    Route::middleware('permission:ver eventos')->group(function () {

        // CRUD basico
        Route::resource('eventos', EventController::class);

        // Gestion de jurados
        Route::get('/eventos/{evento}/manage-juries',
            [EventController::class, 'manageJuries'])
            ->name('eventos.manageJuries');
        Route::post('/eventos/{evento}/assign-jury',
            [EventController::class, 'assignJury'])
            ->name('eventos.assignJury');
        Route::delete('/eventos/{evento}/remove-jury/{user}',
            [EventController::class, 'removeJury'])
            ->name('eventos.removeJury');

        // Panel de administracion
        Route::get('/eventos/{evento}/dashboard',
            [EventController::class, 'dashboard'])
            ->name('eventos.dashboard');
        Route::post('/eventos/{evento}/assign-positions',
            [EventController::class, 'assignPositions'])
            ->name('eventos.assignPositions');
        Route::post('/eventos/{evento}/finalize',
            [EventController::class, 'finalize'])
            ->name('eventos.finalize');

        // Reportes y constancias
        Route::get('/eventos/{evento}/certificate',
            [EventController::class, 'generateCertificate'])
            ->name('eventos.certificate');
        Route::get('/eventos/{evento}/report/pdf',
            [EventController::class, 'generateReportPDF'])
            ->name('eventos.report.pdf');
        Route::get('/eventos/{evento}/report/excel',
            [EventController::class, 'generateReportExcel'])
            ->name('eventos.report.excel');
        Route::get('/eventos/{evento}/certificates/all',
            [EventController::class, 'generateAllCertificates'])
            ->name('eventos.certificates.all');
    });

    // Mis eventos
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])
         ->middleware('permission:ver mis eventos')
         ->name('eventos.myEvents');

    // -----------------------------------------
    // EQUIPOS
    // -----------------------------------------
    Route::middleware('permission:ver equipos')->group(function () {

        // CRUD basico
        Route::resource('equipos', TeamController::class);

        // Gestion de miembros
        Route::delete('/equipos/{equipo}/remove/{user}',
            [TeamController::class, 'removeMember'])
            ->name('equipos.removeMember');
        Route::delete('/equipos/{equipo}/leave',
            [TeamController::class, 'leaveTeam'])
            ->name('equipos.leave');
        Route::post('/equipos/{equipo}/update-role/{user}',
            [TeamController::class, 'updateMemberRole'])
            ->name('equipos.updateMemberRole');

        // Busqueda e invitacion
        Route::get('/equipos/{equipo}/search-users',
            [TeamController::class, 'searchUsers'])
            ->name('equipos.searchUsers');
        Route::post('/equipos/{equipo}/invite-user',
            [TeamController::class, 'inviteUser'])
            ->name('equipos.inviteUser');
    });

    // Mis equipos
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])
         ->middleware('permission:ver mis equipos')
         ->name('equipos.myTeams');

    // -----------------------------------------
    // SOLICITUDES DE UNION A EQUIPOS
    // -----------------------------------------
    Route::post('/equipos/{team}/join-request',
        [TeamJoinRequestController::class, 'store'])
        ->name('equipos.joinRequest');
    Route::post('/join-requests/{request}/accept',
        [TeamJoinRequestController::class, 'accept'])
        ->name('joinRequests.accept');
    Route::post('/join-requests/{request}/reject',
        [TeamJoinRequestController::class, 'reject'])
        ->name('joinRequests.reject');
    Route::delete('/join-requests/{request}/cancel',
        [TeamJoinRequestController::class, 'cancel'])
        ->name('joinRequests.cancel');

    // -----------------------------------------
    // PROYECTOS
    // -----------------------------------------
    Route::resource('projects', ProjectController::class);

    // -----------------------------------------
    // CALIFICACIONES DE JURADOS
    // -----------------------------------------
    Route::prefix('jury')->name('jury.')->group(function () {
        Route::get('/events/{event}/projects',
            [JuryRatingController::class, 'indexByEvent'])
            ->name('event.projects');
        Route::get('/events/{event}/projects/{project}/rate',
            [JuryRatingController::class, 'rateProject'])
            ->name('rate.project');
        Route::post('/events/{event}/projects/{project}/rate',
            [JuryRatingController::class, 'storeRatings'])
            ->name('rate.store');
        Route::get('/events/{event}/statistics',
            [JuryRatingController::class, 'showEventStatistics'])
            ->name('event.statistics');
    });
});

// Rutas de autenticacion (Laravel Breeze)
require __DIR__.'/auth.php';
```

---

# 9. CICLO DE VIDA DEL EVENTO

```
┌─────────────────────────────────────────────────────────────────┐
│                    ESTADOS DEL EVENTO                            │
└─────────────────────────────────────────────────────────────────┘

                    ┌──────────────┐
                    │   CREACION   │
                    │  (por admin) │
                    └──────┬───────┘
                           │
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│  PENDIENTE                                                        │
│  ─────────                                                        │
│  Condicion: fecha_actual < fecha_inicio                          │
│                                                                   │
│  Acciones PERMITIDAS:                                            │
│  ✓ Crear equipos                                                 │
│  ✓ Enviar solicitudes de union                                   │
│  ✓ Aceptar/rechazar solicitudes                                  │
│  ✓ Invitar usuarios a equipos                                    │
│  ✓ Editar datos del evento                                       │
│  ✓ Asignar jurados                                               │
│                                                                   │
│  Acciones NO PERMITIDAS:                                         │
│  ✗ Crear proyectos                                               │
│  ✗ Calificar proyectos                                           │
└──────────────────────────────────────────────────────────────────┘
                           │
                           │ (automatico cuando fecha_actual >= fecha_inicio)
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│  ACTIVO                                                           │
│  ──────                                                           │
│  Condicion: fecha_inicio <= fecha_actual <= fecha_fin            │
│                                                                   │
│  Acciones PERMITIDAS:                                            │
│  ✓ Crear proyectos                                               │
│  ✓ Editar proyectos                                              │
│  ✓ Actualizar URL de GitHub                                      │
│  ✓ Ver dashboard (admin)                                         │
│                                                                   │
│  Acciones NO PERMITIDAS:                                         │
│  ✗ Crear equipos                                                 │
│  ✗ Unirse a equipos                                              │
│  ✗ Calificar proyectos                                           │
└──────────────────────────────────────────────────────────────────┘
                           │
                           │ (automatico cuando fecha_actual > fecha_fin)
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│  EN_CALIFICACION                                                  │
│  ───────────────                                                  │
│  Condicion: fecha_actual > fecha_fin AND estado != 'finalizado'  │
│                                                                   │
│  Acciones PERMITIDAS:                                            │
│  ✓ Jurados califican proyectos (1-10 por requisito)              │
│  ✓ Ver estadisticas                                              │
│  ✓ Ver dashboard con calificaciones                              │
│  ✓ Asignar posiciones (1er, 2do, 3er lugar)                      │
│                                                                   │
│  Acciones NO PERMITIDAS:                                         │
│  ✗ Crear/editar proyectos                                        │
│  ✗ Crear equipos                                                 │
│  ✗ Cambiar miembros de equipos                                   │
└──────────────────────────────────────────────────────────────────┘
                           │
                           │ (manual: admin hace clic en "Finalizar")
                           ▼
┌──────────────────────────────────────────────────────────────────┐
│  FINALIZADO                                                       │
│  ──────────                                                       │
│  Condicion: admin finalizo manualmente                           │
│                                                                   │
│  Acciones PERMITIDAS:                                            │
│  ✓ Ver tabla de ganadores (ranking)                              │
│  ✓ Descargar constancias de participacion (PDF)                  │
│  ✓ Descargar reportes (PDF/Excel)                                │
│  ✓ Ver calificaciones finales (solo lectura)                     │
│                                                                   │
│  Acciones NO PERMITIDAS:                                         │
│  ✗ Modificar NADA                                                │
│  ✗ Editar calificaciones                                         │
│  ✗ Cambiar posiciones                                            │
└──────────────────────────────────────────────────────────────────┘
```

---

# 10. FLUJOS DE NEGOCIO

## 10.1 Flujo: Crear Equipo y Unirse

```
┌─────────────────────────────────────────────────────────────────┐
│              FLUJO DE CREACION DE EQUIPO                         │
└─────────────────────────────────────────────────────────────────┘

Usuario ve evento en estado 'pendiente'
         │
         ▼
    ┌────────────────────┐
    │ Click "Crear Equipo" │
    └─────────┬──────────┘
              │
              ▼
    ┌─────────────────────────────────────────┐
    │ VERIFICACIONES:                          │
    │ 1. Evento en estado 'pendiente'? ✓      │
    │ 2. Usuario no en otro equipo del        │
    │    mismo evento? ✓                       │
    └─────────┬───────────────────────────────┘
              │
              ▼
    ┌────────────────────┐
    │ Formulario:         │
    │ - Nombre            │
    │ - Descripcion       │
    │ - Banner (opcional) │
    └─────────┬──────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Sistema:                            │
    │ 1. Crea Team en BD                  │
    │ 2. Agrega usuario a team_user       │
    │    con rol = 'lider'                │
    │ 3. Guarda banner si existe          │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌─────────────────────┐
    │ Usuario es LIDER     │
    │ del nuevo equipo     │
    └─────────────────────┘


┌─────────────────────────────────────────────────────────────────┐
│            FLUJO DE SOLICITUD DE UNION                           │
└─────────────────────────────────────────────────────────────────┘

Usuario ve equipo existente
         │
         ▼
    ┌──────────────────────┐
    │ Click "Solicitar     │
    │ Unirme"              │
    └─────────┬────────────┘
              │
              ▼
    ┌─────────────────────────────────────────┐
    │ VERIFICACIONES:                          │
    │ 1. Evento en 'pendiente'? ✓             │
    │ 2. No soy ya miembro? ✓                 │
    │ 3. No estoy en otro equipo del evento? ✓│
    │ 4. No tengo solicitud pendiente? ✓      │
    └─────────┬───────────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Crear TeamJoinRequest:              │
    │ - team_id                           │
    │ - user_id                           │
    │ - status = 'pending'                │
    │ - message (opcional)                │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌─────────────────────────────────────────┐
    │ LIDER del equipo ve solicitud           │
    │ en su panel                             │
    └─────────┬───────────────────────────────┘
              │
              ├──────────────────────┬─────────────────────┐
              │                      │                     │
              ▼                      ▼                     ▼
    ┌─────────────┐        ┌─────────────┐       ┌─────────────┐
    │ ACEPTAR     │        │ RECHAZAR    │       │ IGNORAR     │
    └─────┬───────┘        └─────┬───────┘       └─────────────┘
          │                      │
          ▼                      ▼
    ┌──────────────┐      ┌──────────────┐
    │ Verificar:    │      │ Cambiar      │
    │ - < 5 miembros│      │ status a     │
    │ - No en otro  │      │ 'rejected'   │
    │   equipo      │      └──────────────┘
    └─────┬────────┘
          │
          ▼
    ┌──────────────────────────┐
    │ EN TRANSACCION:           │
    │ 1. Agregar a team_user    │
    │    con rol='por asignar'  │
    │ 2. Cambiar status a       │
    │    'accepted'             │
    └──────────────────────────┘
```

## 10.2 Flujo: Calificacion por Jurados

```
┌─────────────────────────────────────────────────────────────────┐
│            FLUJO DE CALIFICACION POR JURADOS                     │
└─────────────────────────────────────────────────────────────────┘

Admin del evento va a "Gestionar Jurados"
         │
         ▼
    ┌────────────────────────────────────┐
    │ Asignar Jurados (max 3):            │
    │ - Solo usuarios con rol             │
    │   Administrador o Super Admin       │
    │ - Se guardan en tabla event_jury    │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Evento pasa a 'en_calificacion'     │
    │ (automatico cuando fecha_fin pasa)  │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Jurado accede a:                    │
    │ /jury/events/{id}/projects          │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Lista de proyectos a calificar:     │
    │ - Proyecto 1: [Calificar] o ✓       │
    │ - Proyecto 2: [Calificar] o ✓       │
    │ - Proyecto 3: [Calificar] o ✓       │
    └─────────┬──────────────────────────┘
              │
              ▼ (click en "Calificar")
    ┌────────────────────────────────────┐
    │ Formulario de calificacion:         │
    │                                     │
    │ Requisito 1: [___] (1-10)           │
    │ Requisito 2: [___] (1-10)           │
    │ Requisito 3: [___] (1-10)           │
    │                                     │
    │ [Guardar Calificaciones]            │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Para cada requisito:                │
    │                                     │
    │ updateOrCreate en                   │
    │ project_jury_requirement:           │
    │ - project_id                        │
    │ - requirement_id                    │
    │ - jury_id                           │
    │ - rating = valor ingresado          │
    └─────────┬──────────────────────────┘
              │
              ▼
    ┌────────────────────────────────────┐
    │ Actualizar promedios:               │
    │                                     │
    │ Para cada requisito:                │
    │ 1. Obtener todas las calificaciones │
    │    de TODOS los jurados             │
    │ 2. Calcular promedio                │
    │ 3. Guardar en project_requirement   │
    └─────────────────────────────────────┘


┌─────────────────────────────────────────────────────────────────┐
│              CALCULO DE PROMEDIOS                                │
└─────────────────────────────────────────────────────────────────┘

Ejemplo con 3 jurados y 2 requisitos:

project_jury_requirement:
┌─────────────┬────────────────┬──────────┬────────┐
│ project_id  │ requirement_id │ jury_id  │ rating │
├─────────────┼────────────────┼──────────┼────────┤
│ 1           │ 1              │ 10       │ 8      │
│ 1           │ 1              │ 11       │ 9      │
│ 1           │ 1              │ 12       │ 7      │
│ 1           │ 2              │ 10       │ 6      │
│ 1           │ 2              │ 11       │ 8      │
│ 1           │ 2              │ 12       │ 7      │
└─────────────┴────────────────┴──────────┴────────┘

Promedios calculados:
- Requisito 1: (8+9+7)/3 = 8.0
- Requisito 2: (6+8+7)/3 = 7.0

project_requirement:
┌─────────────┬────────────────┬────────┐
│ project_id  │ requirement_id │ rating │
├─────────────┼────────────────┼────────┤
│ 1           │ 1              │ 8.0    │
│ 1           │ 2              │ 7.0    │
└─────────────┴────────────────┴────────┘

Promedio general del proyecto:
(8.0 + 7.0) / 2 = 7.5
```

---

# 11. SISTEMA DE ROLES Y PERMISOS

## 11.1 Jerarquia de Roles

```
┌─────────────────────────────────────────────────────────────────┐
│                    JERARQUIA DE ROLES                            │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  SUPER ADMIN                                                     │
│  ───────────                                                     │
│  Acceso: TOTAL                                                  │
│                                                                  │
│  Puede:                                                         │
│  ✓ Administrar cualquier evento (no solo los suyos)            │
│  ✓ Ser jurado de cualquier evento                              │
│  ✓ Expulsar miembros de cualquier equipo                       │
│  ✓ Ver y modificar cualquier recurso                           │
│  ✓ Asignar roles a usuarios                                    │
│  ✓ Gestionar permisos del sistema                              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  ADMINISTRADOR                                                   │
│  ─────────────                                                   │
│  Acceso: LIMITADO A SUS RECURSOS                                │
│                                                                  │
│  Puede:                                                         │
│  ✓ Crear eventos                                                │
│  ✓ Administrar sus propios eventos                             │
│  ✓ Asignar jurados a sus eventos                               │
│  ✓ Ser jurado de eventos (si lo asignan)                       │
│  ✓ Expulsar miembros de equipos de sus eventos                 │
│  ✓ Ver reportes de sus eventos                                 │
│                                                                  │
│  NO puede:                                                      │
│  ✗ Modificar eventos de otros                                  │
│  ✗ Asignar roles                                               │
│  ✗ Ver reportes globales                                       │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│  PARTICIPANTE                                                    │
│  ────────────                                                    │
│  Acceso: SOLO PARTICIPACION                                     │
│                                                                  │
│  Puede:                                                         │
│  ✓ Ver eventos publicos                                        │
│  ✓ Ver equipos publicos                                        │
│  ✓ Crear equipos (se convierte en lider)                       │
│  ✓ Solicitar unirse a equipos                                  │
│  ✓ Invitar miembros (si es lider)                              │
│  ✓ Crear/editar proyectos (si es lider)                        │
│  ✓ Ver resultados y ranking                                    │
│  ✓ Descargar constancias                                       │
│                                                                  │
│  NO puede:                                                      │
│  ✗ Crear eventos                                               │
│  ✗ Ser jurado                                                  │
│  ✗ Ver dashboard de admin                                      │
│  ✗ Expulsar miembros (a menos que sea lider de su equipo)     │
└─────────────────────────────────────────────────────────────────┘
```

## 11.2 Lista de Permisos

```
PERMISOS DE EVENTOS:
├── ver eventos          → Ver lista de eventos
├── crear eventos        → Crear nuevos eventos
├── editar eventos       → Editar eventos propios
├── eliminar eventos     → Eliminar eventos propios
├── ver mis eventos      → Ver eventos que administro
└── inscribirse eventos  → Inscribirse a eventos

PERMISOS DE EQUIPOS:
├── ver equipos          → Ver lista de equipos
├── crear equipos        → Crear nuevos equipos
├── editar equipos       → Editar equipos propios
├── eliminar equipos     → Eliminar equipos propios
├── ver mis equipos      → Ver equipos donde participo
├── unirse equipos       → Solicitar union a equipos
├── invitar miembros     → Invitar usuarios a mi equipo
└── expulsar miembros    → Expulsar miembros de equipos

PERMISOS DE USUARIOS:
├── ver usuarios         → Ver lista de usuarios
├── crear usuarios       → Crear nuevos usuarios
├── editar usuarios      → Editar usuarios
├── eliminar usuarios    → Eliminar usuarios
└── asignar roles        → Asignar roles a usuarios

PERMISOS DE PARTICIPACION:
├── participar competencias → Participar en eventos
├── enviar soluciones       → Subir proyectos
├── ver resultados          → Ver resultados de eventos
└── ver ranking             → Ver ranking de equipos

PERMISOS DE ADMINISTRACION:
├── gestionar permisos   → Configurar permisos
├── ver reportes         → Ver reportes del sistema
└── moderar contenido    → Moderar contenido
```

## 11.3 Como se usa en el codigo

```php
// EN UN CONTROLADOR
// -----------------

// Verificar rol
if (auth()->user()->hasRole('Super Admin')) {
    // Hacer algo especial
}

// Verificar permiso
if (auth()->user()->hasPermissionTo('crear eventos')) {
    // Mostrar boton de crear
}

// Usar Policy
$this->authorize('update', $evento);  // Lanza 403 si no autorizado

// Verificar Policy sin excepcion
if (auth()->user()->can('update', $evento)) {
    // Mostrar boton de editar
}


// EN UNA VISTA BLADE
// ------------------

{{-- Verificar rol --}}
@hasrole('Super Admin')
    <p>Eres Super Admin</p>
@endhasrole

{{-- Verificar permiso --}}
@can('crear eventos')
    <a href="{{ route('eventos.create') }}">Crear Evento</a>
@endcan

{{-- Verificar Policy --}}
@can('update', $evento)
    <a href="{{ route('eventos.edit', $evento) }}">Editar</a>
@endcan

{{-- Verificar multiples roles --}}
@hasanyrole('Super Admin|Administrador')
    <p>Tienes acceso de admin</p>
@endhasanyrole


// EN RUTAS
// --------

// Middleware de permiso
Route::get('/eventos', [EventController::class, 'index'])
     ->middleware('permission:ver eventos');

// Middleware de rol
Route::get('/admin', [AdminController::class, 'index'])
     ->middleware('role:Super Admin');
```

---

# 12. GENERACION DE PDFs

## 12.1 Configuracion

El proyecto usa **barryvdh/laravel-dompdf** para generar PDFs.

```php
// En composer.json
"barryvdh/laravel-dompdf": "^3.1"

// Uso basico
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = PDF::loadView('pdf.template', $data);
return $pdf->download('archivo.pdf');
```

## 12.2 Plantilla de Constancia

**Archivo:** `resources/views/pdf/certificate.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            text-align: center;
            padding: 50px;
        }
        .titulo {
            font-size: 28px;
            font-weight: bold;
            color: #6B21A8;
            margin-bottom: 30px;
        }
        .contenido {
            font-size: 16px;
            line-height: 1.8;
            margin: 40px 0;
        }
        .nombre {
            font-size: 24px;
            font-weight: bold;
            color: #1F2937;
            margin: 20px 0;
        }
        .equipo {
            font-size: 18px;
            color: #6B7280;
        }
        .posicion {
            font-size: 20px;
            font-weight: bold;
            color: #059669;
            margin: 20px 0;
        }
        .firma {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            padding-top: 10px;
        }
        .fecha {
            margin-top: 40px;
            font-size: 12px;
            color: #9CA3AF;
        }
    </style>
</head>
<body>
    <div class="titulo">
        CONSTANCIA DE PARTICIPACION
    </div>

    <div class="contenido">
        Se otorga la presente constancia a:
    </div>

    <div class="nombre">
        {{ $user->name }}
    </div>

    <div class="contenido">
        Por su participacion en el evento:
        <br>
        <strong>{{ $evento->nombre }}</strong>
    </div>

    <div class="equipo">
        Como integrante del equipo: {{ $team->nombre }}
    </div>

    @if($team->posicion)
    <div class="posicion">
        @if($team->posicion == 1)
            🥇 Primer Lugar
        @elseif($team->posicion == 2)
            🥈 Segundo Lugar
        @elseif($team->posicion == 3)
            🥉 Tercer Lugar
        @else
            Posicion #{{ $team->posicion }}
        @endif
    </div>
    @endif

    <div class="firma">
        {{ $evento->admin->name }}
        <br>
        <small>Organizador del Evento</small>
    </div>

    <div class="fecha">
        Generado el {{ $fecha }}
    </div>
</body>
</html>
```

## 12.3 Plantilla de Reporte

**Archivo:** `resources/views/pdf/event-report.blade.php`

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        h1 { color: #6B21A8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #6B21A8; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .high { color: #059669; font-weight: bold; }
        .medium { color: #D97706; }
        .low { color: #DC2626; }
    </style>
</head>
<body>
    <h1>Reporte del Evento: {{ $evento->nombre }}</h1>

    <p><strong>Fecha:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    <p><strong>Administrador:</strong> {{ $evento->admin->name }}</p>
    <p><strong>Total de Equipos:</strong> {{ $teams->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>Lider</th>
                <th>Promedio</th>
                <th>Posicion</th>
                @foreach($evento->requirements as $req)
                    <th>{{ $req->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $index => $team)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $team->nombre }}</td>
                <td>{{ $team->leader_name }}</td>
                <td class="{{ $team->average_rating >= 8 ? 'high' : ($team->average_rating >= 5 ? 'medium' : 'low') }}">
                    {{ number_format($team->average_rating, 2) }}
                </td>
                <td>{{ $team->posicion ?? '-' }}</td>
                @foreach($evento->requirements as $req)
                    <td>
                        {{ $team->project ? $team->project->getRequirementAverage($req->id) : '-' }}
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

---

# 13. PREGUNTAS FRECUENTES DEL PROFESOR

## ¿Como funciona la autenticacion?

**Respuesta:** Usamos Laravel Breeze que proporciona:
- Registro de usuarios (`/register`)
- Login (`/login`)
- Recuperacion de contraseña
- Verificacion de email
- Las contraseñas se hashean automaticamente con bcrypt

```php
// Las contraseñas NUNCA se guardan en texto plano
'password' => 'hashed',  // Cast automatico en User model
```

## ¿Como se protegen las rutas?

**Respuesta:** Con middleware:

```php
// Requiere autenticacion
Route::middleware('auth')->group(function () { ... });

// Requiere permiso especifico
Route::middleware('permission:ver eventos')->group(function () { ... });

// Requiere rol especifico
Route::middleware('role:Super Admin')->group(function () { ... });
```

## ¿Que es Eloquent ORM?

**Respuesta:** Es el sistema de Laravel para interactuar con la base de datos usando objetos PHP en lugar de SQL directo.

```php
// En lugar de escribir SQL:
// SELECT * FROM users WHERE email = 'test@example.com'

// Escribimos:
$user = User::where('email', 'test@example.com')->first();

// En lugar de:
// INSERT INTO events (nombre, admin_id) VALUES ('Hackathon', 1)

// Escribimos:
$event = Event::create(['nombre' => 'Hackathon', 'admin_id' => 1]);
```

## ¿Que es una relacion BelongsToMany?

**Respuesta:** Es una relacion muchos-a-muchos que requiere una tabla pivote.

```
Ejemplo: Un usuario puede estar en MUCHOS equipos
         Un equipo puede tener MUCHOS usuarios

Tabla pivote: team_user
┌─────────┬─────────┬─────────┐
│ team_id │ user_id │ rol     │
├─────────┼─────────┼─────────┤
│ 1       │ 1       │ lider   │
│ 1       │ 2       │ miembro │
│ 2       │ 1       │ miembro │
└─────────┴─────────┴─────────┘

Codigo:
// En User.php
public function teams(): BelongsToMany
{
    return $this->belongsToMany(Team::class)
                ->withPivot('rol');  // Incluir campo extra
}

// Uso:
$user->teams;  // Todos los equipos del usuario
$team->users;  // Todos los usuarios del equipo
```

## ¿Como funcionan las Policies?

**Respuesta:** Las Policies centralizan la logica de autorizacion.

```php
// En EventPolicy.php
public function update(User $user, Event $event): bool
{
    // Solo el admin del evento puede editarlo
    return $user->id === $event->admin_id;
}

// En el controlador
$this->authorize('update', $evento);  // Lanza 403 si no autorizado

// En la vista
@can('update', $evento)
    <button>Editar</button>
@endcan
```

## ¿Que son los Form Requests?

**Respuesta:** Clases que validan datos ANTES de llegar al controlador.

```php
// EventStoreRequest.php
public function rules(): array
{
    return [
        'nombre' => 'required|string|max:255',
        'fecha_inicio' => 'required|date',
    ];
}

// En el controlador
public function store(EventStoreRequest $request)
{
    // Si llegas aqui, los datos YA estan validados
    Event::create($request->validated());
}
```

## ¿Como se generan los PDFs?

**Respuesta:** Con la libreria DomPDF:

```php
use Barryvdh\DomPDF\Facade\Pdf;

// 1. Preparar datos
$data = ['evento' => $evento, 'user' => $user];

// 2. Cargar vista como PDF
$pdf = PDF::loadView('pdf.certificate', $data);

// 3. Descargar
return $pdf->download('constancia.pdf');
```

## ¿Que es Spatie Permission?

**Respuesta:** Paquete que agrega roles y permisos a Laravel.

```php
// Crear rol
$role = Role::create(['name' => 'Administrador']);

// Crear permiso
$permission = Permission::create(['name' => 'crear eventos']);

// Asignar permiso a rol
$role->givePermissionTo('crear eventos');

// Asignar rol a usuario
$user->assignRole('Administrador');

// Verificar
$user->hasRole('Administrador');        // true
$user->hasPermissionTo('crear eventos'); // true
```

---

# 14. COMANDOS UTILES

## Desarrollo

```bash
# Iniciar servidor de desarrollo
php artisan serve

# Iniciar todo (servidor + queue + vite)
composer dev

# Compilar assets para produccion
npm run build
```

## Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir ultima migracion
php artisan migrate:rollback

# Resetear BD y ejecutar migraciones + seeders
php artisan migrate:fresh --seed

# Solo ejecutar seeders
php artisan db:seed
```

## Cache

```bash
# Limpiar toda la cache
php artisan optimize:clear

# Limpiar cache especifica
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

## Consola Interactiva (Tinker)

```bash
php artisan tinker

# Dentro de tinker:
>>> User::all()                           # Ver todos los usuarios
>>> User::find(1)                         # Buscar por ID
>>> Event::with('teams')->get()           # Con relaciones
>>> User::where('email', 'test@test.com')->first()
>>> $user = User::find(1); $user->assignRole('Super Admin');
```

## Crear Super Admin

```bash
php artisan user:make-superadmin email@example.com
```

## Storage

```bash
# Crear enlace simbolico para archivos publicos
php artisan storage:link
```

## Pruebas

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar prueba especifica
php artisan test --filter=NombreDelTest
```

---

# RESUMEN FINAL

## Tecnologias Usadas

| Tecnologia | Uso |
|------------|-----|
| Laravel 12 | Framework PHP principal |
| Spatie Permission | Roles y permisos |
| Laravel Breeze | Autenticacion |
| DomPDF | Generacion de PDFs |
| Tailwind CSS | Estilos |
| Alpine.js | Interactividad JS |
| MySQL | Base de datos |
| Vite | Build de assets |

## Flujo General del Sistema

1. **Usuario se registra** → Se le asigna rol "Participante"
2. **Admin crea evento** → Estado "pendiente"
3. **Participantes crean equipos** → Se unen mediante solicitudes
4. **Evento pasa a "activo"** → Equipos suben proyectos
5. **Evento pasa a "en_calificacion"** → Jurados califican
6. **Admin finaliza** → Se generan constancias y reportes

## Archivos Clave

| Archivo | Funcion |
|---------|---------|
| `routes/web.php` | Define todas las URLs |
| `app/Models/*.php` | Define estructura de datos |
| `app/Http/Controllers/*.php` | Logica de negocio |
| `app/Http/Requests/*.php` | Validaciones |
| `app/Policies/*.php` | Autorizacion |
| `database/migrations/*.php` | Estructura de BD |
| `database/seeders/*.php` | Datos iniciales |

---

**Documento generado para estudio del proyecto CodeBattle**
**Fecha: Diciembre 2025**
