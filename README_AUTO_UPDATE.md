# Actualización Automática de Estados de Eventos

## ¿Cómo funciona?

El sistema actualiza **automáticamente** el estado de los eventos basándose en sus fechas de inicio y fin:

- **Pendiente**: Antes de la fecha de inicio
- **Activo**: Entre la fecha de inicio y fin
- **En Calificación**: Después de la fecha de fin

## ✅ Actualización Automática (Implementado)

**¡No necesitas ejecutar ningún comando adicional!**

Los estados se actualizan automáticamente cuando:
- Se visualiza la lista de eventos
- Se accede a un evento específico
- Se recupera cualquier evento de la base de datos

Esto funciona mediante el método `booted()` del modelo Event que intercepta la recuperación de registros y actualiza el estado si es necesario.

## Comando Manual (Opcional)

Si deseas actualizar todos los eventos de una sola vez, puedes ejecutar:

```bash
php artisan events:update-status
```

## Scheduler Automático (Opcional - Para Producción)

Si prefieres que los estados se actualicen periódicamente en segundo plano sin esperar a que alguien visite la página:

### Opción 1: En Desarrollo (Local)

En una terminal separada, ejecuta:

```bash
php artisan schedule:work
```

Este comando quedará corriendo y actualizará los estados cada minuto.

### Opción 2: En Producción (Servidor)

Agrega esta línea al crontab de tu servidor:

```bash
* * * * * cd /ruta/a/tu/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

**Pasos para configurar en Linux:**

1. Abre el crontab:
   ```bash
   crontab -e
   ```

2. Agrega la línea:
   ```bash
   * * * * * cd /ruta/completa/CodeBattle && php artisan schedule:run >> /dev/null 2>&1
   ```

3. Guarda y cierra el editor

**En Windows con Task Scheduler:**

1. Abre "Programador de tareas" (Task Scheduler)
2. Crea una nueva tarea básica
3. Configura para que se ejecute cada 1 minuto
4. Acción: Ejecutar programa
   - Programa: `php.exe`
   - Argumentos: `artisan schedule:run`
   - Directorio de inicio: Ruta completa a tu proyecto CodeBattle

## Verificar que funciona

1. Crea un evento con fecha de inicio en 2-3 minutos
2. Observa que el estado está en "pendiente"
3. Después de la fecha de inicio, actualiza la página
4. El estado debería cambiar automáticamente a "activo"

También puedes ejecutar el comando manual para ver los cambios:

```bash
php artisan events:update-status
```

## Notas Importantes

- ✅ La actualización automática está **siempre activa**
- ✅ **No necesitas ejecutar ningún comando** para que funcione en desarrollo
- Los eventos marcados manualmente como "finalizado" no se actualizan automáticamente
- La zona horaria configurada es America/Mexico_City
- El sistema solo actualiza eventos en estados: pendiente, activo, o en_calificacion
