<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Evento</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .info-section {
            background: #f7f7f7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-row {
            margin: 8px 0;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #667eea;
        }
        .statistics {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            gap: 10px;
        }
        .stat-card {
            background: #667eea15;
            border-left: 4px solid #667eea;
            padding: 15px;
            text-align: center;
            flex: 1;
        }
        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        thead {
            background: #667eea;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        .position-badge {
            background: #ffd700;
            color: #333;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .rating-high {
            color: #10b981;
            font-weight: bold;
        }
        .rating-medium {
            color: #f59e0b;
            font-weight: bold;
        }
        .rating-low {
            color: #ef4444;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .requirement-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .requirement-title {
            background: #667eea;
            color: white;
            padding: 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .jury-ratings {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .jury-card {
            background: #f0f0f0;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">CODEBATTLE</div>
        <div class="title">Reporte de Evento</div>
        <div class="subtitle">{{ $evento->nombre }}</div>
    </div>

    <!-- Event Information -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Organizador:</span> {{ $evento->admin->name }}
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Inicio:</span> {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Fin:</span> {{ $evento->fecha_fin->format('d/m/Y H:i') }}
        </div>
        @if($evento->direccion)
        <div class="info-row">
            <span class="info-label">Ubicación:</span> {{ $evento->direccion }}
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Estado:</span> {{ strtoupper($evento->estado) }}
        </div>
        <div class="info-row">
            <span class="info-label">Fecha de Generación:</span> {{ $fecha_generacion }}
        </div>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <div class="stat-card">
            <div class="stat-number">{{ $teams->count() }}</div>
            <div class="stat-label">Equipos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $evento->juries->count() }}</div>
            <div class="stat-label">Jurados</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $evento->requirements->count() }}</div>
            <div class="stat-label">Requisitos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $teams->sum(function($t) { return $t->users->count(); }) }}</div>
            <div class="stat-label">Participantes</div>
        </div>
    </div>

    <!-- Teams Ranking Table -->
    <h3 style="margin-top: 30px; color: #667eea;">Ranking de Equipos</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 25%;">Equipo</th>
                <th style="width: 20%;">Líder</th>
                <th style="width: 15%;">Cal. Promedio</th>
                <th style="width: 10%;">Posición</th>
                <th style="width: 10%;">Miembros</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $index => $team)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $team->nombre }}</strong></td>
                <td>{{ $team->leader_name }}</td>
                <td class="{{ $team->average_rating >= 8 ? 'rating-high' : ($team->average_rating >= 6 ? 'rating-medium' : 'rating-low') }}">
                    {{ number_format($team->average_rating, 2) }}
                </td>
                <td>
                    @if($team->posicion)
                    <span class="position-badge">#{{ $team->posicion }}</span>
                    @else
                    -
                    @endif
                </td>
                <td style="text-align: center;">{{ $team->users->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Detailed Ratings by Requirement -->
    @if($teams->first() && $teams->first()->project)
    <div style="page-break-before: always;"></div>
    <h3 style="margin-top: 30px; color: #667eea;">Calificaciones Detalladas por Requisito</h3>
    
    @foreach($evento->requirements as $requirement)
    <div class="requirement-section">
        <div class="requirement-title">{{ $requirement->nombre }}</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">Equipo</th>
                    @foreach($evento->juries as $jury)
                    <th style="width: {{ 70 / $evento->juries->count() }}%; text-align: center;">{{ $jury->name }}</th>
                    @endforeach
                    <th style="width: 15%; text-align: center;">Promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $team)
                @if($team->project)
                <tr>
                    <td><strong>{{ $team->nombre }}</strong></td>
                    @foreach($evento->juries as $jury)
                    @php
                        $rating = $team->project->juryRatings
                            ->where('jury_id', $jury->id)
                            ->where('requirement_id', $requirement->id)
                            ->first();
                    @endphp
                    <td style="text-align: center;">
                        @if($rating)
                        <span class="{{ $rating->rating >= 8 ? 'rating-high' : ($rating->rating >= 6 ? 'rating-medium' : 'rating-low') }}">
                            {{ $rating->rating }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    @endforeach
                    <td style="text-align: center;">
                        <strong>{{ number_format($team->project->getRequirementAverage($requirement->id), 2) }}</strong>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Este documento fue generado automáticamente por el sistema CodeBattle</p>
        <p>© {{ date('Y') }} CodeBattle - Todos los derechos reservados</p>
    </div>
</body>
</html>
