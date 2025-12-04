<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;

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
    Route::group(['middleware' => ['permission:ver eventos']], function(){
        Route::resource('eventos', EventController::class);
    });
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('eventos.misEventos')->middleware(['permission:ver mis eventos']);
    Route::group(['middleware' => ['permission:ver equipos']], function(){
        
        Route::resource('equipos', TeamController::class);
        Route::delete('/equipos/{equipo}/remove/{user}', [TeamController::class, 'removeMember'])->name('equipos.removeMember');
        Route::delete('/equipos/{equipo}/leave', [TeamController::class, 'leaveTeam'])->name('equipos.leave');
    });
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])->name('equipos.misEquipos')->middleware(['permission:ver mis equipos']);
});



Route::get('/usuario/create', function () {
    return view('usuario-create');
})->middleware(['auth', 'verified'])->name('usuario.create');

require __DIR__.'/auth.php';
