<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constancia de Participación</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .certificate-container {
            width: 100%;
            height: 100vh;
            padding: 60px;
            box-sizing: border-box;
            position: relative;
        }
        .certificate-content {
            background: white;
            border: 15px solid #f7f7f7;
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
            height: 100%;
            position: relative;
        }
        .certificate-border {
            border: 3px solid #667eea;
            padding: 40px;
            height: calc(100% - 80px);
            border-radius: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        .title {
            font-size: 42px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }
        .content {
            text-align: center;
            margin: 40px 0;
            line-height: 2;
        }
        .content p {
            font-size: 16px;
            color: #555;
            margin: 15px 0;
        }
        .participant-name {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin: 30px 0;
            text-decoration: underline;
            text-decoration-color: #667eea;
            text-underline-offset: 8px;
        }
        .team-info {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-left: 4px solid #667eea;
            padding: 20px 30px;
            margin: 30px auto;
            max-width: 600px;
            border-radius: 8px;
        }
        .team-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        .position {
            font-size: 28px;
            font-weight: bold;
            color: #764ba2;
            margin-top: 10px;
        }
        .position-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            padding: 10px 30px;
            border-radius: 50px;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }
        .event-name {
            font-size: 22px;
            font-weight: bold;
            color: #667eea;
            font-style: italic;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
        }
        .signature-section {
            margin-top: 80px;
            display: table;
            width: 100%;
        }
        .signature {
            display: table-cell;
            text-align: center;
            width: 50%;
        }
        .signature-line {
            border-top: 2px solid #333;
            width: 250px;
            margin: 0 auto 10px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 12px;
            color: #666;
        }
        .date {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 40px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: 0;
        }
        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 3px solid #667eea;
        }
        .corner-top-left {
            top: 20px;
            left: 20px;
            border-right: none;
            border-bottom: none;
        }
        .corner-top-right {
            top: 20px;
            right: 20px;
            border-left: none;
            border-bottom: none;
        }
        .corner-bottom-left {
            bottom: 20px;
            left: 20px;
            border-right: none;
            border-top: none;
        }
        .corner-bottom-right {
            bottom: 20px;
            right: 20px;
            border-left: none;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-content">
            <div class="certificate-border">
                <div class="watermark">CodeBattle</div>

                <div class="corner-decoration corner-top-left"></div>
                <div class="corner-decoration corner-top-right"></div>
                <div class="corner-decoration corner-bottom-left"></div>
                <div class="corner-decoration corner-bottom-right"></div>

                <div class="header">
                    <div class="logo">CodeBattle</div>
                    <div class="title">Constancia de Participación</div>
                    <div class="subtitle">Reconocimiento Oficial de Participación en Evento</div>
                </div>

                <div class="content">
                    <p>Se hace constar que</p>

                    <div class="participant-name">
                        {{ $user->name }}
                    </div>

                    <p>participó en el evento</p>

                    <div class="event-name">
                        "{{ $evento->nombre }}"
                    </div>

                    <div class="team-info">
                        <div class="team-name">
                            Equipo: {{ $team->nombre }}
                        </div>

                        @if($team->posicion)
                        <div class="position">
                            <div class="position-badge">
                                @if($team->posicion == 1)
                                    🥇 1er Lugar
                                @elseif($team->posicion == 2)
                                    🥈 2do Lugar
                                @elseif($team->posicion == 3)
                                    🥉 3er Lugar
                                @else
                                    {{ $team->posicion }}° Lugar
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <p style="margin-top: 30px;">
                        Realizado del {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}
                        @if($evento->fecha_fin)
                            al {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y') }}
                        @endif
                    </p>
                </div>

                <div class="signature-section">
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $evento->admin->name }}</div>
                        <div class="signature-title">Organizador del Evento</div>
                    </div>
                    <div class="signature">
                        <div class="signature-line"></div>
                        <div class="signature-name">CodeBattle</div>
                        <div class="signature-title">Plataforma de Eventos</div>
                    </div>
                </div>

                <div class="date">
                    Constancia generada el {{ $fecha_generacion }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
