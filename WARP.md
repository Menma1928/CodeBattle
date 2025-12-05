# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Tooling and common commands

### Initial setup
- Install PHP and Node.js dependencies, configure the app key, run migrations, and build frontend assets in one step:

```bash
composer setup
```

This script runs `composer install`, creates `.env` if missing, generates the app key, runs database migrations, installs Node dependencies, and builds the frontend assets.

### Local development
- Run the full dev environment (Laravel HTTP server, queue worker, and Vite dev server) with a single command:

```bash
composer dev
```

This uses `npx concurrently` to run:
- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `npm run dev`

You can also run these commands individually if needed.

### Frontend build and assets
- Run the Vite dev server with hot reloading for CSS/JS:

```bash
npm run dev
```

- Build production frontend assets:

```bash
npm run build
```

Vite is configured via `vite.config.js` using `laravel-vite-plugin`, with entry points at `resources/css/app.css` and `resources/js/app.js`.

### Testing
- Run the full backend test suite (Feature + Unit tests) with Laravel's test runner:

```bash
composer test
```

This clears configuration and then runs `php artisan test`, which is configured to use Pest with PHPUnit under the hood.

- Run a single test file:

```bash
php artisan test tests/Feature/AuthenticationTest.php
```

- Filter tests by name:

```bash
php artisan test --filter="test_users_can_authenticate_using_the_login_screen"
```

- Run Pest directly if you prefer:

```bash
./vendor/bin/pest
```

The test environment (via `phpunit.xml`) uses an in-memory SQLite database and `array` drivers for cache, mail, queues, and sessions.

### Custom Artisan commands
- Promote a user to **Super Admin** (Spatie roles) by email:

```bash
php artisan user:make-superadmin you@example.com
```

This uses the custom `user:make-superadmin` command defined in `App\Console\Commands\MakeSuperAdmin` and the `HasRoles` trait on `App\Models\User`.

## High-level architecture

### Backend (Laravel)
- **Framework & packages**
  - Laravel 12 application using the standard Laravel project structure (`app/`, `config/`, `routes/`, `resources/`, `tests/`).
  - Authentication scaffolding is provided by Laravel Breeze (dev dependency).
  - Authorization and RBAC are handled by `spatie/laravel-permission`, configured via `config/permission.php` and mixed into `App\Models\User` via the `HasRoles` trait.

- **Domain models (in `app/Models`)**
  - `Event`: represents a competitive event.
    - Fillable attributes use Spanish field names: `nombre`, `descripcion`, `fecha_inicio`, `fecha_fin`, `direccion`, `estado`, `url_imagen`, `admin_id`.
    - Relationships:
      - `teams()`: `hasMany(Team::class)` — teams participating in the event.
      - `admin()`: `belongsTo(User::class, 'admin_id')` — event owner.
      - `eventRules()`: `hasMany(EventRule::class)` — per-event rule entries.
      - `requirements()`: `hasMany(Requirement::class)` — high-level requirements tied to the event.
  - `Team`:
    - Fillable: `nombre`, `descripcion`, `posicion`, `url_banner`, `event_id`.
    - Relationships:
      - `event()`: `belongsTo(Event::class)`.
      - `users()`: `belongsToMany(User::class)->withPivot('rol')->withTimestamps()` — pivot holds a `rol` string indicating the member's role.
      - `project()`: `hasOne(Project::class)` — each team can have a single associated project.
  - `Project`:
    - Fillable: `nombre`, `descripcion`, `url_repositorio`, `team_id`.
    - Relationships:
      - `team()`: `belongsTo(Team::class)`.
      - `requirements()`: `belongsToMany(Requirement::class)` — projects can be linked to multiple requirements.
  - `Requirement`:
    - Fillable: `name`, `description`, `event_id`.
    - Relationships:
      - `event()`: `belongsTo(Event::class)`.
      - `user()`: `belongsToMany(User::class)` — associates requirements with users.
  - `EventRule`:
    - Fillable: `event_id`, `regla`.
    - Relationship: `event()`: `belongsTo(Event::class)`.
  - `User`:
    - Extends Laravel's `Authenticatable`, uses `HasFactory`, `Notifiable`, and `HasRoles`.
    - Fillable: `name`, `email`, `password`, `role`.
    - Relationships:
      - `teams()`: `belongsToMany(Team::class)->withPivot('rol')->withTimestamps()`.
      - `events()`: `hasMany(Event::class)` — events where the user is the admin.

- **HTTP routes and controllers**
  - Routing is defined primarily in `routes/web.php`:
    - `/`: public landing page rendering the `welcome` view.
    - An `auth`-protected group provides:
      - `/dashboard`: simple authenticated dashboard view.
      - Profile routes (`/profile`) handled by `ProfileController` for editing/updating/deleting user profiles.
      - Event management under `Route::resource('eventos', EventController::class)` inside a `permission:ver eventos` middleware group.
      - Team management under `Route::resource('equipos', TeamController::class)` inside a `permission:ver equipos` middleware group, plus extra routes for removing members, leaving teams, and updating member roles.
      - "My events" and "My teams" listing routes (`/mis-eventos`, `/mis-equipos`) guarded by `permission:ver mis eventos` and `permission:ver mis equipos` middleware.
      - A `usuario.create` route rendering `usuario-create` for creating users, guarded by `auth` and `verified`.
    - Auth routes are pulled in from `routes/auth.php` (Laravel Breeze).

  - **EventController**
    - `index(Request)`: lists events with search on `nombre`, `descripcion`, `direccion` and optional `estado` filtering; paginated, title set dynamically, renders `resources/views/eventos/index.blade.php`.
    - `myEvents()`: lists events where `admin_id` matches the current user; reuses the same index view with a different title.
    - `create()`, `store(Request)`: renders a creation form and persists new events; `store` also creates associated `EventRule` and `Requirement` records when arrays are provided in the request.
    - `edit(Event $evento)`, `update(Request, Event $evento)`, `destroy(Event $evento)`: standard CRUD for events.
    - `show(Event $evento)`: computes whether the current user is the event admin, finds the user's team (if any) for that event, fetches paginated teams, and renders the event detail page.

  - **TeamController**
    - `index(Request)`: lists teams with search over `nombre`, `descripcion`, and the related event's `nombre`.
    - `myTeams()`: lists teams the authenticated user belongs to.
    - `create()`, `store(Request)`, `edit(Team $equipo)`, `update(Request, Team $equipo)`, `destroy(Team $equipo)`: CRUD for teams.
    - `show(Team $equipo)`: loads team with users and event, determines whether the current user is leader or member, and renders the team detail page.
    - `removeMember(Team $equipo, User $user)`: enforces that only Super Admins, event admins, or team leaders can remove members, and prevents removing the leader.
    - `leaveTeam(Team $equipo)`: allows non-leader members to leave a team.
    - `updateMemberRole(Request, Team $equipo, User $user)`: AJAX-style endpoint to update the `rol` field on the pivot, restricted to Super Admins and team leaders.

- **Console**
  - `App\Console\Commands\MakeSuperAdmin`: defines the `user:make-superadmin {email}` command to assign the "Super Admin" role to a user using Spatie permissions.

### Frontend (Vite, Tailwind, Alpine)
- **Vite setup** (`vite.config.js`)
  - Uses `laravel-vite-plugin` with entry points:
    - `resources/css/app.css`
    - `resources/js/app.js`
  - `refresh: true` enables automatic browser refresh when backend views change.

- **Tailwind CSS** (`tailwind.config.js`)
  - Scans Blade templates in `resources/views/**`, compiled Blade views in `storage/framework/views`, and Laravel's default pagination views.
  - Extends the default `sans` font stack with `Figtree`.
  - Uses `@tailwindcss/forms` for form styling.

- **JavaScript** (`resources/js/app.js`)
  - Imports `./bootstrap` (Laravel's default JS bootstrap, including axios/echo setup).
  - Registers and starts Alpine.js, exposing it globally as `window.Alpine` for use in Blade templates.

- **Views** (`resources/views`)
  - Standard Laravel layout components under `layouts/` and reusable UI components under `components/`.
  - Feature-specific Blade views for events (`eventos/`), teams (`equipos/`), authentication (`auth/`), and user profile management (`profile/`).

### Testing architecture
- `phpunit.xml` defines separate `Unit` and `Feature` test suites pointing at `tests/Unit` and `tests/Feature`.
- `tests/Pest.php` configures Pest to:
  - Use `Tests\TestCase` as the base test case.
  - Apply `Illuminate\Foundation\Testing\RefreshDatabase` to all tests in the `Feature` directory.
  - Define a custom expectation helper `toBeOne()`.
- `Tests\TestCase` currently extends Laravel's base `TestCase` without additional overrides, so tests use the default Laravel testing kernel and environment configured in `phpunit.xml`.

### Conventions and important notes
- **Field naming**: many database columns and model attributes use Spanish names (`nombre`, `descripcion`, etc.). When adding or modifying controller logic or Blade forms, align request field names and validation rules with these underlying column names to avoid mismatches.
- **Roles and permissions**:
  - Authorization relies heavily on Spatie roles/permissions and route middleware like `permission:ver eventos` and `permission:ver equipos`.
  - Team membership behavior depends on the `rol` value stored in the pivot between `users` and `teams` (for example, distinguishing leaders from regular members). Be consistent with these string values when introducing new behaviors or roles.
- **Queues in development**: the `composer dev` script starts a `queue:listen` worker by default, so any queued jobs you introduce will be processed automatically during local development.
