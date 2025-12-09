<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamJoinRequest;
use App\Models\Team;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Obtener solicitudes pendientes para equipos donde el usuario es líder
        $pendingRequests = TeamJoinRequest::with(['user', 'team.event'])
            ->where('status', 'pending')
            ->whereHas('team', function($query) use ($user) {
                $query->whereHas('users', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('rol', 'lider');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener las solicitudes que el usuario ha enviado y están pendientes
        $myPendingRequests = TeamJoinRequest::with(['team.event'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('pendingRequests', 'myPendingRequests'));
    }
}
