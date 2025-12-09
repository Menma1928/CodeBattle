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
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::group(['middleware' => ['permission:ver eventos']], function(){
        Route::resource('eventos', EventController::class);
        Route::get('/eventos/{evento}/manage-juries', [EventController::class, 'manageJuries'])->name('eventos.manageJuries');
        Route::post('/eventos/{evento}/assign-jury', [EventController::class, 'assignJury'])->name('eventos.assignJury');
        Route::delete('/eventos/{evento}/remove-jury/{user}', [EventController::class, 'removeJury'])->name('eventos.removeJury');
        Route::get('/eventos/{evento}/dashboard', [EventController::class, 'dashboard'])->name('eventos.dashboard');
        Route::post('/eventos/{evento}/assign-positions', [EventController::class, 'assignPositions'])->name('eventos.assignPositions');
        Route::post('/eventos/{evento}/finalize', [EventController::class, 'finalize'])->name('eventos.finalize');
        Route::get('/eventos/{evento}/certificate', [EventController::class, 'generateCertificate'])->name('eventos.certificate');
        Route::get('/eventos/{evento}/report/pdf', [EventController::class, 'generateReportPDF'])->name('eventos.report.pdf');
        Route::get('/eventos/{evento}/report/excel', [EventController::class, 'generateReportExcel'])->name('eventos.report.excel');
        Route::get('/eventos/{evento}/certificates/all', [EventController::class, 'generateAllCertificates'])->name('eventos.certificates.all');
    });
    Route::get('/mis-eventos', [EventController::class, 'myEvents'])->name('eventos.misEventos')->middleware(['permission:ver mis eventos']);
    Route::group(['middleware' => ['permission:ver equipos']], function(){
        
        Route::resource('equipos', TeamController::class);
        Route::delete('/equipos/{equipo}/remove/{user}', [TeamController::class, 'removeMember'])->name('equipos.removeMember');
        Route::delete('/equipos/{equipo}/leave', [TeamController::class, 'leaveTeam'])->name('equipos.leave');
        Route::post('/equipos/{equipo}/update-role/{user}', [TeamController::class, 'updateMemberRole'])->name('equipos.updateMemberRole');
        Route::get('/equipos/{equipo}/search-users', [TeamController::class, 'searchUsers'])->name('equipos.searchUsers');
        Route::post('/equipos/{equipo}/invite-user', [TeamController::class, 'inviteUser'])->name('equipos.inviteUser');
    });
    Route::get('/mis-equipos', [TeamController::class, 'myTeams'])->name('equipos.misEquipos')->middleware(['permission:ver mis equipos']);

    // Team join requests routes
    Route::post('/equipos/{team}/join-request', [App\Http\Controllers\TeamJoinRequestController::class, 'store'])->name('equipos.joinRequest');
    Route::post('/join-requests/{request}/accept', [App\Http\Controllers\TeamJoinRequestController::class, 'accept'])->name('joinRequests.accept');
    Route::post('/join-requests/{request}/reject', [App\Http\Controllers\TeamJoinRequestController::class, 'reject'])->name('joinRequests.reject');
    Route::delete('/join-requests/{request}/cancel', [App\Http\Controllers\TeamJoinRequestController::class, 'cancel'])->name('joinRequests.cancel');

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
