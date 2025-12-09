# Configuración de Correo Electrónico - CodeBattle

## ✅ Estado: CONFIGURADO Y FUNCIONANDO

La configuración de correo electrónico ha sido completada exitosamente usando Gmail SMTP.

---

## 📧 Configuración Actual

### Variables de Entorno (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=chernandez10.20.30.40@gmail.com
MAIL_PASSWORD=bztxjexsjtuwliya
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="chernandez10.20.30.40@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Detalles de Configuración

- **Servicio**: Gmail SMTP
- **Puerto**: 587 (TLS)
- **Encriptación**: TLS
- **Correo**: chernandez10.20.30.40@gmail.com
- **Contraseña de Aplicación**: bztxjexsjtuwliya

---

## 🔧 Archivos Modificados

### 1. `.env`
Actualizado con las credenciales de Gmail y configuración SMTP.

### 2. `.env.example`
Actualizado con la plantilla de configuración SMTP para futuros desarrolladores.

### 3. `config/mail.php`
Agregada la línea de encriptación SMTP:
```php
'encryption' => env('MAIL_ENCRYPTION', 'tls'),
```

---

## 📨 Funcionalidades de Correo en el Sistema

El sistema actualmente envía correos electrónicos en las siguientes situaciones:

### 1. **Solicitudes de Unión a Equipos**
- **Archivo**: `app/Mail/NewJoinRequestMail.php`
- **Vista**: `resources/views/emails/new-join-request.blade.php`
- **Cuándo se envía**: Cuando un usuario solicita unirse a un equipo
- **Destinatario**: Líder del equipo

### 2. **Aceptación de Solicitudes**
- **Archivo**: `app/Mail/JoinRequestAcceptedMail.php`
- **Vista**: `resources/views/emails/join-request-accepted.blade.php`
- **Cuándo se envía**: Cuando un líder acepta una solicitud de unión
- **Destinatario**: Usuario solicitante

### 3. **Verificación de Email**
- Funcionalidad integrada de Laravel
- Se envía cuando un usuario se registra (si está habilitado)

---

## 🧪 Pruebas

### Ejecutar Prueba de Correo

Puedes probar el envío de correos ejecutando:

```bash
php test_mail.php
```

O desde tinker:

```bash
php artisan tinker
```

Luego ejecuta:

```php
Mail::raw('Test', function($msg) { $msg->to('tu@email.com')->subject('Test'); });
```

---

## 📝 Notas Importantes

### Contraseña de Aplicación de Gmail

La contraseña `bztxjexsjtuwliya` es una **contraseña de aplicación** generada por Google, NO la contraseña normal de la cuenta. Esto es necesario porque:

1. Gmail requiere autenticación de 2 factores para aplicaciones
2. Las contraseñas de aplicación son más seguras
3. Pueden ser revocadas sin afectar el acceso a la cuenta principal

### Generar Nueva Contraseña de Aplicación

Si necesitas generar una nueva:

1. Ve a tu cuenta de Google
2. Seguridad → Verificación en 2 pasos
3. Contraseñas de aplicaciones
4. Genera una nueva contraseña
5. Actualiza el archivo `.env`

---

## 🔒 Seguridad

⚠️ **IMPORTANTE**: 
- El archivo `.env` está en `.gitignore` y NO debe subirse a GitHub
- Nunca compartas las credenciales de correo públicamente
- Usa variables de entorno en producción
- Considera usar servicios como Mailtrap para desarrollo

---

## 🚀 Comandos Útiles

### Limpiar caché de configuración
```bash
php artisan config:clear
```

### Cachear configuración
```bash
php artisan config:cache
```

### Ver configuración de correo
```bash
php artisan tinker
config('mail')
```

---

## 🔍 Troubleshooting

### Error: "Connection timed out"
- Verifica que el puerto 587 no esté bloqueado por firewall
- Intenta usar el puerto 465 con SSL

### Error: "Authentication failed"
- Verifica que la contraseña de aplicación sea correcta
- Asegúrate de que la verificación en 2 pasos esté activada en Google

### Error: "Could not instantiate mail function"
- Ejecuta `php artisan config:clear`
- Verifica que las variables de entorno estén correctamente configuradas

---

## 📚 Referencias

- [Documentación de Laravel Mail](https://laravel.com/docs/11.x/mail)
- [Gmail SMTP Settings](https://support.google.com/mail/answer/7126229)
- [App Passwords Google](https://support.google.com/accounts/answer/185833)

---

**Última actualización**: Diciembre 9, 2025
**Estado**: ✅ Funcionando correctamente
