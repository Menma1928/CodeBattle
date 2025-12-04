<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

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
        Route::get('/eventos/mis-eventos', [EventController::class, 'myEvents'])->name('eventos.misEventos');
        Route::resource('eventos', EventController::class);
    });
});



Route::get('/usuario/create', function () {
    return view('usuario-create');
})->middleware(['auth', 'verified'])->name('usuario.create');

require __DIR__.'/auth.php';
