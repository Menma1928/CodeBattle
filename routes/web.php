<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\JuryRatingController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::group(['middleware' => ['permission:ver eventos']], function(){
        Route::resource('eventos', EventController::class);
        Route::get('/eventos/{evento}/manage-juries', [EventController::class, 'manageJuries'])->name('eventos.manageJuries');
        Route::post('/eventos/{evento}/assign-jury', [EventController::class, 'assignJury'])->name('eventos.assignJury');
        Route::delete('/eventos/{evento}/remove-jury/{user}', [EventController::class, 'removeJury'])->name('eventos.removeJury');
    });
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('eventos.misEventos')->middleware(['permission:ver mis eventos']);
    Route::group(['middleware' => ['permission:ver equipos']], function(){
        
        Route::resource('equipos', TeamController::class);
        Route::delete('/equipos/{equipo}/remove/{user}', [TeamController::class, 'removeMember'])->name('equipos.removeMember');
        Route::delete('/equipos/{equipo}/leave', [TeamController::class, 'leaveTeam'])->name('equipos.leave');
        Route::post('/equipos/{equipo}/update-role/{user}', [TeamController::class, 'updateMemberRole'])->name('equipos.updateMemberRole');
    });
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])->name('equipos.misEquipos')->middleware(['permission:ver mis equipos']);

    // Projects routes
    Route::resource('projects', ProjectController::class);

    // Jury rating routes
    Route::prefix('jury')->name('jury.')->group(function() {
        Route::get('/events/{event}/projects', [JuryRatingController::class, 'indexByEvent'])->name('event.projects');
        Route::get('/events/{event}/projects/{project}/rate', [JuryRatingController::class, 'rateProject'])->name('rate.project');
        Route::post('/events/{event}/projects/{project}/rate', [JuryRatingController::class, 'storeRatings'])->name('store.ratings');
        Route::get('/events/{event}/statistics', [JuryRatingController::class, 'showEventStatistics'])->name('event.statistics');
    });
});



Route::get('/usuario/create', function () {
    return view('usuario-create');
})->middleware(['auth', 'verified'])->name('usuario.create');

require __DIR__.'/auth.php';
