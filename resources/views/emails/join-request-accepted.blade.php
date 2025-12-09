<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Aceptada</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            font-size: 64px;
            margin: 20px 0;
        }
        .info-box {
            background-color: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #059669;
            font-size: 16px;
        }
        .info-item {
            margin: 10px 0;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 14px;
        }
        .info-value {
            color: #1f2937;
            font-size: 15px;
        }
        .button {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #059669;
        }
        .highlight-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 ¡Felicidades!</h1>
        </div>

        <div class="content">
            <div class="success-icon">✅</div>

            <h2 style="text-align: center; color: #059669;">Tu solicitud ha sido aceptada</h2>

            <p>Hola <strong>{{ $user->name }}</strong>,</p>
            <p>¡Tenemos excelentes noticias! Tu solicitud para unirte al equipo ha sido aceptada.</p>

            <div class="highlight-box">
                <h3 style="margin: 0 0 10px 0; color: #059669;">Ahora eres miembro de:</h3>
                <h2 style="margin: 0; color: #1f2937;">{{ $team->nombre }}</h2>
            </div>

            <div class="info-box">
                <h3>📋 Información del Equipo</h3>
                @if($team->event)
                <div class="info-item">
                    <div class="info-label">Evento:</div>
                    <div class="info-value">{{ $team->event->nombre }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha del evento:</div>
                    <div class="info-value">{{ $team->event->fecha_inicio->format('d/m/Y') }}</div>
                </div>
                @endif
                <div class="info-item">
                    <div class="info-label">Miembros actuales:</div>
                    <div class="info-value">{{ $team->users->count() }}/5</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('equipos.show', $team) }}" class="button">
                    Ver Mi Equipo
                </a>
            </div>

            <p style="margin-top: 20px; font-size: 14px; color: #6b7280; text-align: center;">
                Coordina con tu equipo y prepárense para el evento. ¡Mucha suerte! 🚀
            </p>
        </div>

        <div class="footer">
            <p>Este correo fue enviado automáticamente por {{ config('app.name') }}</p>
            <p>Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
