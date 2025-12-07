<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamJoinRequest;
use Illuminate\Support\Facades\DB;

class TeamJoinRequestController extends Controller
{
    /**
     * Crear una solicitud para unirse a un equipo
     */
    public function store(Request $request, Team $team)
    {
        $user = auth()->user();
        $event = $team->event;

        // Validar que el evento esté en estado pendiente
        if ($event->estado !== 'pendiente') {
            return redirect()->back()->withErrors([
                'error' => 'No puedes unirte a equipos de eventos que no están en estado pendiente.'
            ]);
        }

        // Validar que el usuario no sea ya miembro del equipo
        if ($team->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->withErrors([
                'error' => 'Ya eres miembro de este equipo.'
            ]);
        }

        // Validar que el usuario no esté en otro equipo del mismo evento
        $userTeamInEvent = $user->teams()
            ->where('event_id', $event->id)
            ->exists();

        if ($userTeamInEvent) {
            return redirect()->back()->withErrors([
                'error' => 'Ya perteneces a un equipo en este evento.'
            ]);
        }

        // Validar que no exista una solicitud pendiente o aceptada
        $existingRequest = TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return redirect()->back()->withErrors([
                    'error' => 'Ya tienes una solicitud pendiente para este equipo.'
                ]);
            } else {
                return redirect()->back()->withErrors([
                    'error' => 'Ya tienes una solicitud aceptada para este equipo.'
                ]);
            }
        }

        // Crear la solicitud
        TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'message' => $request->input('message'),
        ]);

        return redirect()->back()->with('success', 'Solicitud enviada exitosamente. El líder del equipo la revisará.');
    }

    /**
     * Aceptar una solicitud de unión
     */
    public function accept(TeamJoinRequest $request)
    {
        $team = $request->team;
        $user = auth()->user();

        // Verificar que el usuario autenticado sea el líder del equipo
        if (!$team->isLeader($user->id)) {
            return redirect()->back()->withErrors([
                'error' => 'Solo el líder del equipo puede aceptar solicitudes.'
            ]);
        }

        // Verificar que la solicitud esté pendiente
        if ($request->status !== 'pending') {
            return redirect()->back()->withErrors([
                'error' => 'Esta solicitud ya fue procesada.'
            ]);
        }

        // Verificar que el equipo no tenga ya 5 participantes
        if ($team->users()->count() >= 5) {
            return redirect()->back()->withErrors([
                'error' => 'El equipo ya tiene el máximo de 5 participantes. Elimina a alguien antes de aceptar nuevas solicitudes.'
            ]);
        }

        // Verificar que el usuario solicitante no esté ya en otro equipo del mismo evento
        $userTeamInEvent = $request->user->teams()
            ->where('event_id', $team->event_id)
            ->exists();

        if ($userTeamInEvent) {
            return redirect()->back()->withErrors([
                'error' => 'El usuario solicitante ya pertenece a otro equipo en este evento.'
            ]);
        }

        DB::transaction(function () use ($request, $team) {
            // Añadir usuario al equipo
            $team->users()->attach($request->user_id, ['rol' => 'por asignar']);

            // Marcar la solicitud como aceptada
            $request->update(['status' => 'accepted']);
        });

        return redirect()->back()->with('success', 'Solicitud aceptada. El usuario ha sido añadido al equipo.');
    }

    /**
     * Rechazar una solicitud de unión
     */
    public function reject(TeamJoinRequest $request)
    {
        $team = $request->team;
        $user = auth()->user();

        // Verificar que el usuario autenticado sea el líder del equipo
        if (!$team->isLeader($user->id)) {
            return redirect()->back()->withErrors([
                'error' => 'Solo el líder del equipo puede rechazar solicitudes.'
            ]);
        }

        // Verificar que la solicitud esté pendiente
        if ($request->status !== 'pending') {
            return redirect()->back()->withErrors([
                'error' => 'Esta solicitud ya fue procesada.'
            ]);
        }

        // Marcar la solicitud como rechazada
        $request->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Solicitud rechazada.');
    }

    /**
     * Cancelar una solicitud propia
     */
    public function cancel(TeamJoinRequest $request)
    {
        $user = auth()->user();

        // Verificar que la solicitud pertenezca al usuario autenticado
        if ($request->user_id !== $user->id) {
            return redirect()->back()->withErrors([
                'error' => 'No puedes cancelar una solicitud que no es tuya.'
            ]);
        }

        // Verificar que la solicitud esté pendiente
        if ($request->status !== 'pending') {
            return redirect()->back()->withErrors([
                'error' => 'Solo puedes cancelar solicitudes pendientes.'
            ]);
        }

        // Eliminar la solicitud
        $request->delete();

        return redirect()->back()->with('success', 'Solicitud cancelada.');
    }
}
