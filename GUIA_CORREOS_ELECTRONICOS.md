# GUÍA COMPLETA: IMPLEMENTACIÓN DE CORREOS ELECTRÓNICOS EN CODEBATTLE

> Documentación exhaustiva para entender cómo funciona el sistema de envío de correos en el proyecto.

---

## ÍNDICE

1. [Resumen Ejecutivo](#1-resumen-ejecutivo)
2. [Arquitectura del Sistema de Correos](#2-arquitectura-del-sistema-de-correos)
3. [Configuración Base](#3-configuración-base)
4. [Clases Mailable](#4-clases-mailable)
5. [Plantillas de Correo (Vistas Blade)](#5-plantillas-de-correo-vistas-blade)
6. [Integración en el Controlador](#6-integración-en-el-controlador)
7. [Flujos de Envío Paso a Paso](#7-flujos-de-envío-paso-a-paso)
8. [Modelos Involucrados](#8-modelos-involucrados)
9. [Cómo Probar los Correos](#9-cómo-probar-los-correos)
10. [Configuración para Producción](#10-configuración-para-producción)
11. [Preguntas Frecuentes para el Profesor](#11-preguntas-frecuentes-para-el-profesor)

---

## 1. RESUMEN EJECUTIVO

### ¿Qué se implementó?
El proyecto CodeBattle tiene un sistema completo de notificaciones por correo electrónico para el flujo de solicitudes de unión a equipos:

| Correo | Cuándo se envía | A quién |
|--------|-----------------|---------|
| `NewJoinRequestMail` | Cuando un usuario solicita unirse a un equipo | Al líder del equipo |
| `JoinRequestAcceptedMail` | Cuando el líder acepta una solicitud | Al usuario que solicitó unirse |

### Archivos Creados
```
app/
├── Mail/
│   ├── NewJoinRequestMail.php        ← Clase Mailable #1
│   └── JoinRequestAcceptedMail.php   ← Clase Mailable #2
│
resources/views/
├── emails/
│   ├── new-join-request.blade.php         ← Plantilla HTML #1
│   └── join-request-accepted.blade.php    ← Plantilla HTML #2
```

### Archivos Modificados
```
app/Http/Controllers/TeamJoinRequestController.php  ← Se agregó envío de correos
```

---

## 2. ARQUITECTURA DEL SISTEMA DE CORREOS

### ¿Cómo funciona el envío de correos en Laravel?

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ARQUITECTURA DE CORREOS                              │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│   CONTROLADOR                 MAILABLE                   VISTA              │
│   ┌──────────────┐           ┌──────────────┐          ┌──────────────┐     │
│   │ TeamJoin     │  llama    │ NewJoinReq   │  usa     │ emails/      │     │
│   │ Request      │ ───────►  │ uestMail.php │ ───────► │ new-join-    │     │
│   │ Controller   │           │              │          │ request.blade│     │
│   └──────────────┘           └──────────────┘          └──────────────┘     │
│         │                           │                         │              │
│         │                           │                         │              │
│         ▼                           ▼                         ▼              │
│   Mail::to($email)           Define: asunto,           Renderiza el         │
│   ->send(Mailable)           datos, vista              HTML final            │
│                                                                              │
│                                     │                                        │
│                                     ▼                                        │
│                           ┌──────────────────┐                              │
│                           │   config/mail.php │                             │
│                           │   .env            │                             │
│                           └────────┬─────────┘                              │
│                                    │                                         │
│                                    ▼                                         │
│                    ┌───────────────────────────────┐                        │
│                    │  DRIVER DE ENVÍO:             │                        │
│                    │  • log (desarrollo)           │                        │
│                    │  • smtp (producción)          │                        │
│                    │  • ses, postmark, etc.        │                        │
│                    └───────────────────────────────┘                        │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Componentes Clave

1. **Facade `Mail`**: Es la interfaz de Laravel para enviar correos
2. **Clase `Mailable`**: Define la estructura del correo (asunto, datos, vista)
3. **Vista Blade**: El HTML que verá el usuario en su bandeja de entrada
4. **Configuración**: Define cómo se envía el correo (SMTP, log, etc.)

---

## 3. CONFIGURACIÓN BASE

### Archivo: `.env` (Variables de Entorno)

```env
# ═══════════════════════════════════════════════════════════════
# CONFIGURACIÓN DE CORREO
# ═══════════════════════════════════════════════════════════════

MAIL_MAILER=log
# ↑ Opciones: log, smtp, ses, postmark, sendmail, mailgun
# "log" significa que los correos se guardan en storage/logs/laravel.log
# Esto es ideal para desarrollo porque no necesitas un servidor de correo real

MAIL_SCHEME=null
# ↑ Esquema de conexión (tls, ssl, null)

MAIL_HOST=127.0.0.1
# ↑ Servidor SMTP (ejemplo: smtp.gmail.com, smtp.mailtrap.io)

MAIL_PORT=2525
# ↑ Puerto SMTP (común: 25, 465, 587, 2525)

MAIL_USERNAME=null
# ↑ Usuario para autenticación SMTP

MAIL_PASSWORD=null
# ↑ Contraseña para autenticación SMTP

MAIL_FROM_ADDRESS="hello@example.com"
# ↑ Dirección "De:" que aparece en los correos

MAIL_FROM_NAME="${APP_NAME}"
# ↑ Nombre que aparece como remitente (usa el nombre de la app)
```

### Archivo: `config/mail.php` (Configuración Completa)

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    | Define el driver por defecto para enviar correos.
    */
    'default' => env('MAIL_MAILER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    | Configuraciones disponibles para cada tipo de mailer.
    */
    'mailers' => [

        // SMTP - Servidor de correo tradicional
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),
            'url' => env('MAIL_URL'),
            'host' => env('MAIL_HOST', '127.0.0.1'),
            'port' => env('MAIL_PORT', 2525),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'timeout' => null,
        ],

        // SES - Amazon Simple Email Service
        'ses' => [
            'transport' => 'ses',
        ],

        // Postmark - Servicio de correo transaccional
        'postmark' => [
            'transport' => 'postmark',
        ],

        // Resend - Servicio moderno de email
        'resend' => [
            'transport' => 'resend',
        ],

        // Sendmail - Usa el sendmail del servidor
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        // Log - Guarda correos en logs (DESARROLLO)
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        // Array - Guarda en memoria (para tests)
        'array' => [
            'transport' => 'array',
        ],

        // Failover - Respaldo automático
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    | Dirección y nombre del remitente por defecto.
    */
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],
];
```

### ¿Por qué usamos `MAIL_MAILER=log`?

En desarrollo, no queremos enviar correos reales porque:
1. No tenemos un servidor SMTP configurado
2. Podríamos enviar correos accidentalmente a usuarios reales
3. Es más fácil ver el contenido del correo en los logs

Los correos se guardan en: `storage/logs/laravel.log`

---

## 4. CLASES MAILABLE

### ¿Qué es una clase Mailable?

Una clase `Mailable` es una clase PHP que representa un correo electrónico. Define:
- **Asunto** del correo
- **Datos** que se pasan a la vista
- **Vista Blade** que renderiza el HTML
- **Adjuntos** (opcional)

### Archivo: `app/Mail/NewJoinRequestMail.php`

```php
<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\TeamJoinRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * CLASE: NewJoinRequestMail
 *
 * Propósito: Notificar al líder de un equipo que alguien quiere unirse.
 *
 * Cuándo se envía: Cuando un usuario ejecuta TeamJoinRequestController@store()
 *
 * A quién: Al email del líder del equipo
 */
class NewJoinRequestMail extends Mailable
{
    use Queueable, SerializesModels;
    // ↑ Queueable: Permite encolar el correo para envío asíncrono
    // ↑ SerializesModels: Serializa los modelos Eloquent correctamente

    /**
     * Constructor - Recibe los datos necesarios para el correo
     *
     * @param TeamJoinRequest $joinRequest - La solicitud creada
     * @param Team $team - El equipo al que quiere unirse
     * @param User $applicant - El usuario que solicita unirse
     */
    public function __construct(
        public TeamJoinRequest $joinRequest,
        public Team $team,
        public User $applicant
    ) {}
    // ↑ Al usar "public" en los parámetros, PHP automáticamente:
    //   1. Crea propiedades de clase con esos nombres
    //   2. Las asigna con los valores recibidos
    //   3. Las hace disponibles en la vista Blade

    /**
     * Envelope - Define el "sobre" del correo (asunto, de, para)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva Solicitud para Unirse a tu Equipo - ' . $this->team->nombre,
            // ↑ El asunto incluye el nombre del equipo para contexto
        );
    }

    /**
     * Content - Define el contenido del correo
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-join-request',
            // ↑ Usa la vista: resources/views/emails/new-join-request.blade.php
            // Las variables $joinRequest, $team, $applicant están disponibles
            // automáticamente porque son propiedades públicas
        );
    }

    /**
     * Attachments - Define archivos adjuntos (opcional)
     */
    public function attachments(): array
    {
        return [];
        // ↑ Este correo no tiene adjuntos
        // Ejemplo de adjunto: Attachment::fromPath('/ruta/archivo.pdf')
    }
}
```

### Archivo: `app/Mail/JoinRequestAcceptedMail.php`

```php
<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * CLASE: JoinRequestAcceptedMail
 *
 * Propósito: Notificar a un usuario que su solicitud fue aceptada.
 *
 * Cuándo se envía: Cuando el líder ejecuta TeamJoinRequestController@accept()
 *
 * A quién: Al email del usuario que solicitó unirse
 */
class JoinRequestAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Constructor
     *
     * @param Team $team - El equipo al que fue aceptado
     * @param User $user - El usuario que fue aceptado
     */
    public function __construct(
        public Team $team,
        public User $user
    ) {}

    /**
     * Envelope - El asunto del correo
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Tu solicitud fue aceptada! - ' . $this->team->nombre,
            // ↑ Asunto positivo con el nombre del equipo
        );
    }

    /**
     * Content - La vista que renderiza el correo
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.join-request-accepted',
            // ↑ Usa: resources/views/emails/join-request-accepted.blade.php
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
```

### Diferencias entre las dos clases Mailable

| Aspecto | NewJoinRequestMail | JoinRequestAcceptedMail |
|---------|-------------------|------------------------|
| **Destinatario** | Líder del equipo | Usuario solicitante |
| **Momento** | Al crear solicitud | Al aceptar solicitud |
| **Datos** | joinRequest, team, applicant | team, user |
| **Tono** | Informativo (nueva solicitud) | Celebratorio (aceptado) |
| **Color** | Púrpura | Verde |

---

## 5. PLANTILLAS DE CORREO (VISTAS BLADE)

### ¿Por qué usar estilos inline en correos?

Los clientes de correo (Gmail, Outlook, etc.) **no soportan CSS externo** ni `<style>` en el `<head>`. Por eso, todos los estilos deben ir **inline** en cada elemento.

### Archivo: `resources/views/emails/new-join-request.blade.php`

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Solicitud de Unión</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <!--
    ╔═══════════════════════════════════════════════════════════════╗
    ║  ESTRUCTURA DEL CORREO:                                       ║
    ║  1. Contenedor principal (centrado, max-width 600px)          ║
    ║  2. Header con gradiente púrpura                              ║
    ║  3. Cuerpo con información del solicitante                    ║
    ║  4. Botón de acción                                           ║
    ║  5. Footer con créditos                                       ║
    ╚═══════════════════════════════════════════════════════════════╝
    -->

    <!-- Contenedor principal -->
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">

        <!-- Tarjeta del correo -->
        <div style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- HEADER - Gradiente púrpura con título                   -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div style="background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%); padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">
                    📬 Nueva Solicitud de Unión
                </h1>
            </div>

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- CUERPO - Información del solicitante                    -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div style="padding: 30px;">

                <!-- Saludo e introducción -->
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                    Hola,
                </p>
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                    Tienes una nueva solicitud para unirse a tu equipo
                    <strong style="color: #9333ea;">{{ $team->nombre }}</strong>.
                    {{-- ↑ $team viene del constructor de NewJoinRequestMail --}}
                </p>

                <!-- Caja de información del solicitante -->
                <div style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                    <h3 style="color: #1f2937; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 15px 0;">
                        Información del Solicitante
                    </h3>

                    <!-- Nombre del solicitante -->
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">Nombre:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $applicant->name }}
                            {{-- ↑ $applicant es el User que solicita unirse --}}
                        </span>
                    </div>

                    <!-- Email del solicitante -->
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">Email:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $applicant->email }}
                        </span>
                    </div>

                    <!-- Fecha de solicitud -->
                    <div>
                        <span style="color: #6b7280; font-size: 14px;">Fecha de solicitud:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $joinRequest->created_at->format('d/m/Y H:i') }}
                            {{-- ↑ Formatea la fecha en formato día/mes/año hora:minuto --}}
                        </span>
                    </div>
                </div>

                <!-- Mensaje del solicitante (condicional) -->
                @if($joinRequest->message)
                {{-- ↑ Solo se muestra si el solicitante escribió un mensaje --}}
                <div style="background-color: #faf5ff; border-left: 4px solid #9333ea; padding: 15px; margin-bottom: 20px; border-radius: 0 8px 8px 0;">
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 8px 0;">
                        Mensaje del solicitante:
                    </p>
                    <p style="color: #374151; font-size: 14px; line-height: 1.6; margin: 0; font-style: italic;">
                        "{{ $joinRequest->message }}"
                    </p>
                </div>
                @endif

                <!-- ═══════════════════════════════════════════════════ -->
                <!-- BOTÓN DE ACCIÓN                                      -->
                <!-- ═══════════════════════════════════════════════════ -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('equipos.show', $team) }}"
                       style="display: inline-block;
                              background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
                              color: #ffffff;
                              text-decoration: none;
                              padding: 14px 28px;
                              border-radius: 8px;
                              font-weight: 600;
                              font-size: 14px;">
                        Ver Solicitud en el Equipo
                    </a>
                    {{-- ↑ Link directo a la página del equipo donde puede aceptar/rechazar --}}
                </div>
            </div>

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- FOOTER                                                   -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                    Este correo fue enviado automáticamente por CodeBattle.
                </p>
                <p style="color: #9ca3af; font-size: 12px; margin: 8px 0 0 0;">
                    Por favor, no responda a este mensaje.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
```

### Archivo: `resources/views/emails/join-request-accepted.blade.php`

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud Aceptada</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f3f4f6;">
    <!--
    ╔═══════════════════════════════════════════════════════════════╗
    ║  ESTRUCTURA DEL CORREO DE ACEPTACIÓN:                         ║
    ║  1. Header verde con celebración                              ║
    ║  2. Icono de éxito grande                                     ║
    ║  3. Información del equipo                                    ║
    ║  4. Datos del evento                                          ║
    ║  5. Botón para ver el equipo                                  ║
    ╚═══════════════════════════════════════════════════════════════╝
    -->

    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- HEADER - Gradiente verde celebratorio                   -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold;">
                    🎉 ¡Felicidades!
                </h1>
            </div>

            <!-- ═══════════════════════════════════════════════════════ -->
            <!-- CUERPO                                                   -->
            <!-- ═══════════════════════════════════════════════════════ -->
            <div style="padding: 30px;">

                <!-- Icono de éxito grande -->
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="display: inline-block; width: 80px; height: 80px; background-color: #d1fae5; border-radius: 50%; line-height: 80px; font-size: 40px;">
                        ✅
                    </div>
                </div>

                <!-- Título de éxito -->
                <h2 style="color: #059669; text-align: center; font-size: 20px; margin-bottom: 20px;">
                    Tu solicitud ha sido aceptada
                </h2>

                <!-- Saludo personalizado -->
                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                    Hola <strong>{{ $user->name }}</strong>,
                    {{-- ↑ $user viene del constructor de JoinRequestAcceptedMail --}}
                </p>

                <p style="color: #374151; font-size: 16px; line-height: 1.6; margin-bottom: 20px;">
                    ¡Tenemos excelentes noticias! Tu solicitud para unirte al equipo ha sido aceptada.
                </p>

                <!-- Caja de destaque con nombre del equipo -->
                <div style="background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 20px;">
                    <p style="color: #6b7280; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 8px 0;">
                        Ahora eres parte de
                    </p>
                    <h3 style="color: #059669; font-size: 24px; font-weight: bold; margin: 0;">
                        {{ $team->nombre }}
                    </h3>
                </div>

                <!-- Información del equipo y evento -->
                <div style="background-color: #f9fafb; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
                    <h4 style="color: #1f2937; font-size: 14px; font-weight: 600; margin: 0 0 15px 0;">
                        Detalles del equipo:
                    </h4>

                    <!-- Evento asociado -->
                    @if($team->event)
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">Evento:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $team->event->nombre }}
                            {{-- ↑ Accede a la relación event del Team --}}
                        </span>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 14px;">Fecha del evento:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $team->event->fecha_inicio->format('d/m/Y') }}
                        </span>
                    </div>
                    @endif

                    <!-- Conteo de miembros -->
                    <div>
                        <span style="color: #6b7280; font-size: 14px;">Miembros actuales:</span>
                        <span style="color: #1f2937; font-size: 14px; font-weight: 600; margin-left: 8px;">
                            {{ $team->users->count() }}/5
                            {{-- ↑ Muestra cuántos miembros hay de un máximo de 5 --}}
                        </span>
                    </div>
                </div>

                <!-- Botón de acción -->
                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('equipos.show', $team) }}"
                       style="display: inline-block;
                              background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                              color: #ffffff;
                              text-decoration: none;
                              padding: 14px 28px;
                              border-radius: 8px;
                              font-weight: 600;
                              font-size: 14px;">
                        Ver Mi Equipo
                    </a>
                </div>

                <!-- Mensaje motivacional -->
                <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 20px; line-height: 1.6;">
                    Coordina con tu equipo y prepárense para el evento. ¡Mucha suerte! 🚀
                </p>
            </div>

            <!-- Footer -->
            <div style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                    Este correo fue enviado automáticamente por CodeBattle.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
```

---

## 6. INTEGRACIÓN EN EL CONTROLADOR

### Archivo: `app/Http/Controllers/TeamJoinRequestController.php`

Este es el controlador donde se **dispara** el envío de correos. Veamos los métodos relevantes:

### Método `store()` - Crear Solicitud

```php
<?php

namespace App\Http\Controllers;

use App\Mail\NewJoinRequestMail;
use App\Mail\JoinRequestAcceptedMail;
use App\Models\Team;
use App\Models\TeamJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;  // ← Facade para enviar correos

class TeamJoinRequestController extends Controller
{
    /**
     * Crear una nueva solicitud de unión a un equipo
     *
     * FLUJO:
     * 1. Validar que el usuario puede solicitar unirse
     * 2. Crear la solicitud en la BD
     * 3. Notificar al líder por correo
     * 4. Redirigir con mensaje de éxito
     */
    public function store(Request $request, Team $team)
    {
        $user = auth()->user();

        // ═══════════════════════════════════════════════════════════════
        // VALIDACIONES DE NEGOCIO
        // ═══════════════════════════════════════════════════════════════

        // 1. El evento debe estar en estado "pendiente"
        if ($team->event->estado !== 'pendiente') {
            return back()->with('error', 'Solo puedes unirte a equipos de eventos pendientes.');
        }

        // 2. El usuario no puede ser ya miembro del equipo
        if ($team->users()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Ya eres miembro de este equipo.');
        }

        // 3. El usuario no puede estar en otro equipo del mismo evento
        $existingTeam = $user->teams()
            ->where('event_id', $team->event_id)
            ->first();
        if ($existingTeam) {
            return back()->with('error', 'Ya perteneces al equipo "' . $existingTeam->nombre . '" en este evento.');
        }

        // 4. No puede haber solicitud pendiente o aceptada previa
        $existingRequest = TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->first();
        if ($existingRequest) {
            return back()->with('error', 'Ya tienes una solicitud pendiente o aceptada para este equipo.');
        }

        // ═══════════════════════════════════════════════════════════════
        // CREAR LA SOLICITUD
        // ═══════════════════════════════════════════════════════════════

        $joinRequest = TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'message' => $request->input('message'),  // Mensaje opcional
        ]);

        // ═══════════════════════════════════════════════════════════════
        // ENVIAR CORREO AL LÍDER DEL EQUIPO
        // ═══════════════════════════════════════════════════════════════

        // Obtener el líder del equipo (usuario con rol='lider' en el pivot)
        $leader = $team->users()->wherePivot('rol', 'lider')->first();

        // Solo enviar si existe líder y tiene email
        if ($leader && $leader->email) {
            // Crear instancia del Mailable y enviarlo
            Mail::to($leader->email)                    // Destinatario
                ->send(new NewJoinRequestMail(          // Clase Mailable
                    $joinRequest,                       // Datos: la solicitud
                    $team,                              // Datos: el equipo
                    $user                               // Datos: el solicitante
                ));
        }

        return back()->with('success', 'Tu solicitud ha sido enviada correctamente.');
    }
}
```

### Método `accept()` - Aceptar Solicitud

```php
/**
 * Aceptar una solicitud de unión
 *
 * FLUJO:
 * 1. Validar que el usuario actual es el líder
 * 2. Validar que hay espacio en el equipo
 * 3. Agregar usuario al equipo (en transacción)
 * 4. Notificar al solicitante por correo
 * 5. Redirigir con mensaje de éxito
 */
public function accept(TeamJoinRequest $request)
{
    $team = $request->team;
    $currentUser = auth()->user();

    // ═══════════════════════════════════════════════════════════════
    // VALIDACIONES
    // ═══════════════════════════════════════════════════════════════

    // Solo el líder puede aceptar solicitudes
    if (!$team->isLeader($currentUser->id)) {
        return back()->with('error', 'Solo el líder del equipo puede aceptar solicitudes.');
    }

    // La solicitud debe estar pendiente
    if ($request->status !== 'pending') {
        return back()->with('error', 'Esta solicitud ya fue procesada.');
    }

    // El equipo no puede tener más de 5 participantes
    $participantCount = $team->users()
        ->wherePivot('rol', '!=', 'lider')
        ->count();
    if ($participantCount >= 5) {
        return back()->with('error', 'El equipo ya tiene el máximo de 5 participantes.');
    }

    // El solicitante no puede estar en otro equipo del mismo evento
    $applicantInOtherTeam = $request->user->teams()
        ->where('event_id', $team->event_id)
        ->exists();
    if ($applicantInOtherTeam) {
        return back()->with('error', 'El solicitante ya pertenece a otro equipo en este evento.');
    }

    // ═══════════════════════════════════════════════════════════════
    // PROCESAR ACEPTACIÓN (en transacción para integridad)
    // ═══════════════════════════════════════════════════════════════

    DB::transaction(function () use ($team, $request) {
        // 1. Agregar usuario al equipo con rol "por asignar"
        $team->users()->attach($request->user_id, [
            'rol' => 'por asignar',
        ]);

        // 2. Actualizar estado de la solicitud
        $request->update(['status' => 'accepted']);
    });

    // ═══════════════════════════════════════════════════════════════
    // ENVIAR CORREO AL SOLICITANTE ACEPTADO
    // ═══════════════════════════════════════════════════════════════

    // Cargar relaciones necesarias para el correo
    $team->load('event', 'users');
    // ↑ Esto asegura que $team->event y $team->users estén disponibles
    //   en la vista del correo sin queries adicionales

    // Enviar correo de confirmación
    if ($request->user && $request->user->email) {
        Mail::to($request->user->email)
            ->send(new JoinRequestAcceptedMail(
                $team,           // El equipo
                $request->user   // El usuario aceptado
            ));
    }

    return back()->with('success', 'Solicitud aceptada correctamente.');
}
```

### Método `reject()` - Rechazar Solicitud

```php
/**
 * Rechazar una solicitud de unión
 *
 * NOTA: Actualmente NO envía correo de rechazo.
 * Esto podría implementarse en el futuro.
 */
public function reject(TeamJoinRequest $request)
{
    $team = $request->team;
    $currentUser = auth()->user();

    // Validaciones
    if (!$team->isLeader($currentUser->id)) {
        return back()->with('error', 'Solo el líder del equipo puede rechazar solicitudes.');
    }

    if ($request->status !== 'pending') {
        return back()->with('error', 'Esta solicitud ya fue procesada.');
    }

    // Actualizar estado
    $request->update(['status' => 'rejected']);

    // TODO: Aquí se podría agregar un JoinRequestRejectedMail

    return back()->with('success', 'Solicitud rechazada correctamente.');
}
```

---

## 7. FLUJOS DE ENVÍO PASO A PASO

### FLUJO 1: Usuario solicita unirse a un equipo

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FLUJO: NUEVA SOLICITUD DE UNIÓN                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PASO 1: Usuario ve equipo                                                  │
│  ────────────────────────                                                    │
│  • Usuario navega a /equipos/{id}                                           │
│  • Ve botón "Solicitar Unirme"                                              │
│                                                                              │
│  PASO 2: Usuario hace clic en el botón                                      │
│  ──────────────────────────────────────                                      │
│  • Se abre modal para escribir mensaje (opcional)                           │
│  • Usuario hace clic en "Enviar Solicitud"                                  │
│                                                                              │
│  PASO 3: Request llega al servidor                                          │
│  ───────────────────────────────────                                         │
│  • POST /equipos/{id}/solicitudes                                           │
│  • Llega a TeamJoinRequestController@store()                                │
│                                                                              │
│  PASO 4: Validaciones                                                        │
│  ─────────────────────                                                       │
│  • ¿Evento está pendiente? ✓                                                │
│  • ¿Usuario ya es miembro? ✗                                                │
│  • ¿Usuario está en otro equipo del evento? ✗                               │
│  • ¿Ya tiene solicitud pendiente? ✗                                         │
│                                                                              │
│  PASO 5: Crear solicitud en BD                                              │
│  ─────────────────────────────                                               │
│  TeamJoinRequest::create([                                                  │
│      'team_id' => 5,                                                        │
│      'user_id' => 12,                                                       │
│      'status' => 'pending',                                                 │
│      'message' => 'Me encantaría unirme...',                               │
│  ]);                                                                         │
│                                                                              │
│  PASO 6: Obtener líder del equipo                                           │
│  ─────────────────────────────────                                           │
│  $leader = $team->users()                                                   │
│      ->wherePivot('rol', 'lider')                                           │
│      ->first();                                                              │
│  // Resultado: User { id: 3, email: 'lider@ejemplo.com' }                   │
│                                                                              │
│  PASO 7: Enviar correo                                                       │
│  ─────────────────────                                                       │
│  Mail::to('lider@ejemplo.com')                                              │
│      ->send(new NewJoinRequestMail($joinRequest, $team, $user));            │
│                                                                              │
│  PASO 8: Procesar Mailable                                                   │
│  ─────────────────────────                                                   │
│  • NewJoinRequestMail crea el "sobre" (asunto, de)                          │
│  • Renderiza vista emails.new-join-request                                  │
│  • Pasa variables: $joinRequest, $team, $applicant                          │
│                                                                              │
│  PASO 9: Enviar al driver                                                    │
│  ─────────────────────────                                                   │
│  • Como MAIL_MAILER=log, se guarda en storage/logs/laravel.log              │
│  • En producción con smtp, se enviaría por SMTP                             │
│                                                                              │
│  PASO 10: Redirección                                                        │
│  ────────────────────                                                        │
│  return back()->with('success', 'Tu solicitud ha sido enviada.');           │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### FLUJO 2: Líder acepta la solicitud

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FLUJO: ACEPTACIÓN DE SOLICITUD                           │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  PASO 1: Líder ve notificación                                              │
│  ──────────────────────────────                                              │
│  • Líder recibió correo/ve solicitudes pendientes                           │
│  • Navega a la página del equipo                                            │
│                                                                              │
│  PASO 2: Líder revisa solicitud                                             │
│  ───────────────────────────────                                             │
│  • Ve información del solicitante                                           │
│  • Lee mensaje (si existe)                                                  │
│  • Hace clic en "Aceptar"                                                   │
│                                                                              │
│  PASO 3: Request llega al servidor                                          │
│  ───────────────────────────────────                                         │
│  • POST /solicitudes/{id}/accept                                            │
│  • Llega a TeamJoinRequestController@accept()                               │
│                                                                              │
│  PASO 4: Validaciones                                                        │
│  ─────────────────────                                                       │
│  • ¿Usuario actual es líder? ✓                                              │
│  • ¿Solicitud está pendiente? ✓                                             │
│  • ¿Equipo tiene menos de 5 miembros? ✓                                     │
│  • ¿Solicitante no está en otro equipo? ✓                                   │
│                                                                              │
│  PASO 5: Transacción de BD                                                   │
│  ─────────────────────────                                                   │
│  DB::transaction(function() {                                               │
│      // Agregar usuario al equipo                                           │
│      $team->users()->attach($userId, ['rol' => 'por asignar']);             │
│                                                                              │
│      // Actualizar solicitud                                                │
│      $request->update(['status' => 'accepted']);                            │
│  });                                                                         │
│                                                                              │
│  PASO 6: Cargar relaciones para el correo                                   │
│  ─────────────────────────────────────────                                   │
│  $team->load('event', 'users');                                             │
│  // Esto precarga el evento y los usuarios para evitar N+1 en la vista     │
│                                                                              │
│  PASO 7: Enviar correo al solicitante                                       │
│  ────────────────────────────────────                                        │
│  Mail::to('solicitante@ejemplo.com')                                        │
│      ->send(new JoinRequestAcceptedMail($team, $user));                     │
│                                                                              │
│  PASO 8: Renderizar correo                                                   │
│  ─────────────────────────                                                   │
│  • JoinRequestAcceptedMail crea el sobre                                    │
│  • Renderiza vista emails.join-request-accepted                             │
│  • Incluye: nombre del equipo, evento, conteo de miembros                   │
│                                                                              │
│  PASO 9: Enviar al driver                                                    │
│  ─────────────────────────                                                   │
│  • Se guarda en logs (desarrollo) o se envía por SMTP (producción)          │
│                                                                              │
│  PASO 10: Redirección                                                        │
│  ────────────────────                                                        │
│  return back()->with('success', 'Solicitud aceptada correctamente.');       │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. MODELOS INVOLUCRADOS

### Modelo: `Team.php`

```php
// Relación para obtener el líder
public function leader()
{
    return $this->users()->wherePivot('rol', 'lider')->first();
}

// Verificar si un usuario es líder
public function isLeader($userId): bool
{
    return $this->users()
        ->wherePivot('user_id', $userId)
        ->wherePivot('rol', 'lider')
        ->exists();
}

// Todas las solicitudes de unión
public function joinRequests(): HasMany
{
    return $this->hasMany(TeamJoinRequest::class);
}

// Solo solicitudes pendientes
public function pendingJoinRequests(): HasMany
{
    return $this->hasMany(TeamJoinRequest::class)
        ->where('status', 'pending');
}
```

### Modelo: `TeamJoinRequest.php`

```php
class TeamJoinRequest extends Model
{
    protected $fillable = [
        'team_id',
        'user_id',
        'status',    // 'pending', 'accepted', 'rejected'
        'message',   // Mensaje opcional del solicitante
    ];

    // Relación con el equipo
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Relación con el usuario solicitante
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes para filtrar por estado
    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeAccepted($query) { return $query->where('status', 'accepted'); }
    public function scopeRejected($query) { return $query->where('status', 'rejected'); }
}
```

### Modelo: `User.php`

```php
use Illuminate\Notifications\Notifiable;  // ← Trait necesario para notificaciones

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // Equipos a los que pertenece
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('rol')
            ->withTimestamps();
    }

    // Solicitudes de unión que ha creado
    public function teamJoinRequests(): HasMany
    {
        return $this->hasMany(TeamJoinRequest::class);
    }
}
```

---

## 9. CÓMO PROBAR LOS CORREOS

### Opción 1: Ver en los logs (actual)

Como `MAIL_MAILER=log`, los correos se guardan en:
```
storage/logs/laravel.log
```

Para ver el contenido:
```bash
# Ver las últimas 100 líneas del log
tail -100 storage/logs/laravel.log

# O buscar específicamente correos
grep -A 50 "Content-Type: text/html" storage/logs/laravel.log
```

### Opción 2: Usar Mailtrap (recomendado para demos)

1. Crear cuenta gratuita en https://mailtrap.io
2. Obtener credenciales SMTP
3. Actualizar `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_de_mailtrap
MAIL_PASSWORD=tu_password_de_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="codebattle@example.com"
MAIL_FROM_NAME="CodeBattle"
```

4. Los correos aparecerán en tu bandeja de Mailtrap

### Opción 3: Usar tinker para probar

```bash
php artisan tinker
```

```php
// Simular envío de correo
$team = App\Models\Team::first();
$user = App\Models\User::first();
$joinRequest = new App\Models\TeamJoinRequest([
    'team_id' => $team->id,
    'user_id' => $user->id,
    'status' => 'pending',
    'message' => 'Mensaje de prueba'
]);

// Enviar correo de prueba
Mail::to('test@example.com')->send(
    new App\Mail\NewJoinRequestMail($joinRequest, $team, $user)
);

// Ver en storage/logs/laravel.log
```

---

## 10. CONFIGURACIÓN PARA PRODUCCIÓN

### Opción A: Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="CodeBattle"
```

> Nota: Gmail requiere "App Password" si tienes 2FA activado

### Opción B: SendGrid

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=tu_api_key_de_sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@codebattle.com
MAIL_FROM_NAME="CodeBattle"
```

### Opción C: Amazon SES

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=tu_access_key
AWS_SECRET_ACCESS_KEY=tu_secret_key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@codebattle.com
MAIL_FROM_NAME="CodeBattle"
```

---

## 11. PREGUNTAS FRECUENTES PARA EL PROFESOR

### P: ¿Cómo funciona el envío de correos en Laravel?

**R:** Laravel usa el patrón Mailable. Se crea una clase que extiende `Mailable`, define el asunto y la vista, y se envía usando `Mail::to($email)->send(new MiMailable())`. El driver configurado en `.env` determina cómo se envía (SMTP, log, etc.).

### P: ¿Por qué usan `MAIL_MAILER=log`?

**R:** Es para desarrollo. En lugar de enviar correos reales, se guardan en `storage/logs/laravel.log`. Esto evita enviar correos accidentalmente y no requiere configurar un servidor SMTP real.

### P: ¿Qué son los traits `Queueable` y `SerializesModels`?

**R:**
- `Queueable`: Permite encolar el correo para envío asíncrono (mejor rendimiento)
- `SerializesModels`: Serializa correctamente los modelos Eloquent cuando se encolan

### P: ¿Por qué los estilos están inline en las plantillas de correo?

**R:** Porque los clientes de correo (Gmail, Outlook, etc.) no soportan CSS externo ni `<style>` en el `<head>`. Todos los estilos deben ir inline.

### P: ¿Cómo se pasan datos a la vista del correo?

**R:** Las propiedades públicas del Mailable están disponibles automáticamente en la vista. Si tienes `public Team $team` en el constructor, puedes usar `{{ $team->nombre }}` en la vista.

### P: ¿Por qué se usa `$team->load('event', 'users')` antes de enviar el correo de aceptación?

**R:** Para precargar las relaciones que se usarán en la vista del correo. Esto evita el problema N+1 de queries y asegura que los datos estén disponibles.

### P: ¿Qué pasa si el correo falla al enviarse?

**R:** Depende del driver. Con SMTP real, se lanzaría una excepción. Se podría manejar con try-catch o usar Queue con reintentos automáticos.

### P: ¿Se podrían agregar más correos al sistema?

**R:** Sí. Por ejemplo:
- `JoinRequestRejectedMail` - Cuando rechazan una solicitud
- `EventStartingMail` - Cuando un evento está por comenzar
- `TeamPositionMail` - Cuando se asignan posiciones finales

### P: ¿Cómo probarían esto en producción?

**R:** Cambiaríamos `MAIL_MAILER=smtp` y configuraríamos un servicio como Mailtrap, SendGrid, o Amazon SES con credenciales reales. También se podría usar un dominio verificado para evitar que los correos lleguen a spam.

---

## RESUMEN FINAL

La implementación de correos en CodeBattle es **completa y funcional**:

| Componente | Estado | Archivo |
|------------|--------|---------|
| Configuración | ✅ Listo | `.env`, `config/mail.php` |
| Mailable #1 | ✅ Listo | `app/Mail/NewJoinRequestMail.php` |
| Mailable #2 | ✅ Listo | `app/Mail/JoinRequestAcceptedMail.php` |
| Vista #1 | ✅ Listo | `resources/views/emails/new-join-request.blade.php` |
| Vista #2 | ✅ Listo | `resources/views/emails/join-request-accepted.blade.php` |
| Integración | ✅ Listo | `app/Http/Controllers/TeamJoinRequestController.php` |

**Esto te da los 10 puntos extra de la rúbrica por funcionalidad de correos.**

---

*Documento generado para el proyecto CodeBattle - Sistema de Gestión de Competencias*
