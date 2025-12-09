# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CodeBattle is a competitive event management platform for team-based coding competitions. It handles event creation, team management, project submissions, and jury-based evaluation with role-based access control.

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+), Spatie Laravel Permission for RBAC
- **Frontend:** Blade templates, Alpine.js, Tailwind CSS, Vite
- **Database:** MySQL with Eloquent ORM
- **Testing:** Pest PHP with PHPUnit

## Commands

```bash
# Setup (installs deps, creates .env, runs migrations, builds assets)
composer setup

# Development (runs Laravel server, queue worker, and Vite concurrently)
composer dev

# Testing
composer test                                    # Full test suite
php artisan test tests/Feature/SomeTest.php     # Single file
php artisan test --filter="test_name"           # By name

# Custom commands
php artisan user:make-superadmin email@example.com  # Promote to Super Admin
```

## Architecture

### Key Models and Relationships

- **Event** → has many Teams, Requirements, EventRules; belongs to admin (User)
- **Team** → belongs to Event; has many Users (via pivot with `rol` field); has one Project
- **Project** → belongs to Team; has many Requirements and JuryRatings
- **User** → has many Teams (with pivot role), Events (as admin); uses Spatie HasRoles trait

### Controllers

- `EventController`: Event CRUD, jury management, rules/requirements handling
- `TeamController`: Team CRUD, member management, role updates, join/leave logic
- `ProjectController`: Project CRUD, submissions
- `JuryRatingController`: Jury evaluation and statistics

### Authorization

- Route middleware: `permission:ver eventos`, `permission:ver equipos`, etc.
- Policy classes in `app/Policies/` for fine-grained authorization
- Key roles: "Super Admin", "Administrador"
- Team roles stored in pivot: "lider" (leader) vs regular members

### Views Organization

```
resources/views/
├── eventos/          # Event management
├── equipos/          # Team management
├── projects/         # Project submissions
├── jury/             # Jury evaluation
├── components/       # Reusable Blade components
└── layouts/          # App layouts
```

## Important Conventions

### Spanish Field Names

Database columns and form fields use Spanish names consistently:
- `nombre` (name), `descripcion` (description)
- `fecha_inicio`, `fecha_fin` (start/end dates)
- `direccion` (address), `estado` (status)
- `url_imagen`, `url_repositorio`, `github_url` (URLs)
- `rol` (role in pivot tables)

Always match form input names to these database column names.

### Team Leadership

The `rol` field in the team_user pivot distinguishes leaders ("lider") from members. The `Team` model has `leader()` and `isLeader($userId)` helper methods.

### Form Validation

Uses Form Request classes in `app/Http/Requests/` (e.g., `EventStoreRequest`, `TeamStoreRequest`). Validation rules must use Spanish field names matching the database schema.

## Test Environment

Configured in `phpunit.xml`:
- In-memory SQLite database
- Array drivers for cache, mail, queue, session
- Feature tests use `RefreshDatabase` trait
