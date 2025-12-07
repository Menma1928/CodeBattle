# Configuración del Actualizador Automático de Estados de Eventos

## ¿Qué hace?

El sistema ahora actualiza automáticamente el estado de los eventos basándose en sus fechas de inicio y fin:

-   **Pendiente**: Antes de la fecha de inicio
-   **Activo**: Entre la fecha de inicio y fin
-   **En Calificación**: Después de la fecha de fin

## Comando Manual

Para actualizar los estados manualmente en cualquier momento:

```bash
php artisan events:update-status
```

## Automatización (Recomendado)

Para que los estados se actualicen automáticamente cada minuto, necesitas configurar el Laravel Scheduler.

### Opción 1: En Desarrollo (Local)

En una terminal separada, ejecuta:

```bash
php artisan schedule:work
```

Este comando quedará corriendo y ejecutará las tareas programadas cada minuto.

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

1. Ejecuta el comando manual para ver los cambios:

    ```bash
    php artisan events:update-status
    ```

2. Crea un evento de prueba con fecha de inicio dentro de 2-3 minutos

3. Si tienes el scheduler corriendo, después de ese tiempo el evento debería cambiar automáticamente de "pendiente" a "activo"

## Notas Importantes

-   El scheduler solo actualiza eventos en estados: pendiente, activo, o en_calificacion
-   Los eventos marcados manualmente como "finalizado" no se actualizan automáticamente
-   La zona horaria configurada es America/Mexico_City
-   El comando se ejecuta silenciosamente en segundo plano (no interrumpe nada)
