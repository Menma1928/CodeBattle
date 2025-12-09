<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Unión</title>
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
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
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
        .info-box {
            background-color: #f9fafb;
            border-left: 4px solid #9333ea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box h3 {
            margin-top: 0;
            color: #9333ea;
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
        .message-box {
            background-color: #ede9fe;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            border: 1px solid #ddd6fe;
        }
        .button {
            display: inline-block;
            background-color: #9333ea;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #7c3aed;
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
            <h1>📬 Nueva Solicitud de Unión</h1>
        </div>

        <div class="content">
            <p>Hola,</p>
            <p>Tienes una nueva solicitud para unirse a tu equipo <strong>{{ $team->nombre }}</strong>.</p>

            <div class="info-box">
                <h3>👤 Información del Solicitante</h3>
                <div class="info-item">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $applicant->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $applicant->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fecha de solicitud:</div>
                    <div class="info-value">{{ $joinRequest->created_at ? $joinRequest->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</div>
                </div>
            </div>

            @if($joinRequest->message)
            <div class="message-box">
                <strong>💬 Mensaje del solicitante:</strong>
                <p style="margin: 10px 0 0 0;">{{ $joinRequest->message }}</p>
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ route('equipos.show', $team) }}" class="button">
                    Ver Solicitud en el Equipo
                </a>
            </div>

            <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
                Puedes revisar esta solicitud y aceptarla o rechazarla desde la página de tu equipo.
            </p>
        </div>

        <div class="footer">
            <p>Este correo fue enviado automáticamente por {{ config('app.name') }}</p>
            <p>Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
