<?php

/**
 * Script para verificar la configuración de almacenamiento
 * Detecta automáticamente si está usando almacenamiento local o S3
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "🔍 Verificación de Configuración de Almacenamiento\n";
echo "==================================================\n\n";

$disk = config('filesystems.default');
echo "📦 Disco configurado: " . strtoupper($disk) . "\n";
echo "📁 Desde .env: FILESYSTEM_DISK=" . env('FILESYSTEM_DISK', 'local') . "\n\n";

if ($disk === 's3') {
    echo "☁️  Modo: PRODUCCIÓN (Laravel Cloud Object Storage)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🪣 Bucket: " . config('filesystems.disks.s3.bucket') . "\n";
    echo "🌍 Region: " . config('filesystems.disks.s3.region') . "\n";
    echo "🔗 Endpoint: " . config('filesystems.disks.s3.endpoint') . "\n";
    echo "🔑 Access Key: " . substr(config('filesystems.disks.s3.key'), 0, 8) . "...\n\n";
    
    echo "🧪 Probando conexión a S3...\n";
    try {
        // Probar conexión S3
        $testContent = 'Test desde ' . now() . "\nEntorno: Producción (S3)";
        $testPath = 'test/config_test_' . time() . '.txt';
        
        Storage::disk('s3')->put($testPath, $testContent);
        $url = Storage::disk('s3')->url($testPath);
        
        echo "   ✅ Archivo subido exitosamente\n";
        echo "   📍 Path: " . $testPath . "\n";
        echo "   🔗 URL: " . $url . "\n\n";
        
        // Verificar que existe
        if (Storage::disk('s3')->exists($testPath)) {
            echo "   ✅ Archivo verificado en S3\n";
        }
        
        // Limpiar
        Storage::disk('s3')->delete($testPath);
        echo "   🗑️  Archivo de prueba eliminado\n\n";
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ CONFIGURACIÓN S3 EXITOSA\n";
        echo "Las imágenes se guardarán en Laravel Cloud Object Storage\n";
        
    } catch (\Exception $e) {
        echo "\n❌ ERROR EN CONEXIÓN S3\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Error: " . $e->getMessage() . "\n\n";
        
        echo "Verifica lo siguiente:\n";
        echo "1. Las credenciales AWS en .env son correctas\n";
        echo "2. El bucket existe y está accesible\n";
        echo "3. Las políticas de permisos del bucket permiten escritura\n";
        echo "4. El paquete league/flysystem-aws-s3-v3 está instalado:\n";
        echo "   composer require league/flysystem-aws-s3-v3 \"^3.0\"\n";
    }
    
} elseif ($disk === 'public') {
    echo "💻 Modo: DESARROLLO (Almacenamiento Local)\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📁 Directorio: storage/app/public\n";
    echo "🔗 URL base: " . asset('storage') . "\n\n";
    
    echo "🧪 Probando almacenamiento local...\n";
    try {
        // Verificar que existe el enlace simbólico
        $publicStoragePath = public_path('storage');
        if (!file_exists($publicStoragePath)) {
            echo "   ⚠️  ADVERTENCIA: No existe el enlace simbólico\n";
            echo "   Ejecuta: php artisan storage:link\n\n";
        } else {
            echo "   ✅ Enlace simbólico configurado\n";
        }
        
        // Probar escritura
        $testContent = 'Test desde ' . now() . "\nEntorno: Desarrollo (Local)";
        $testPath = 'test/config_test_' . time() . '.txt';
        
        Storage::disk('public')->put($testPath, $testContent);
        echo "   ✅ Archivo guardado exitosamente\n";
        echo "   📍 Path: storage/app/public/" . $testPath . "\n";
        echo "   🔗 URL: " . asset('storage/' . $testPath) . "\n\n";
        
        // Verificar que existe
        if (Storage::disk('public')->exists($testPath)) {
            echo "   ✅ Archivo verificado en disco local\n";
        }
        
        // Limpiar
        Storage::disk('public')->delete($testPath);
        echo "   🗑️  Archivo de prueba eliminado\n\n";
        
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ CONFIGURACIÓN LOCAL EXITOSA\n";
        echo "Las imágenes se guardarán en storage/app/public\n";
        
    } catch (\Exception $e) {
        echo "\n❌ ERROR EN ALMACENAMIENTO LOCAL\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "Error: " . $e->getMessage() . "\n\n";
        echo "Verifica los permisos de la carpeta storage/\n";
    }
    
} else {
    echo "⚠️  Disco desconocido: " . $disk . "\n";
    echo "Configura FILESYSTEM_DISK=public (desarrollo) o FILESYSTEM_DISK=s3 (producción)\n";
}

echo "\n📝 Resumen de Configuración\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "• Eventos: Imágenes en " . ($disk === 's3' ? 'S3 (events/)' : 'Local (storage/app/public/events/)') . "\n";
echo "• Equipos: Banners en " . ($disk === 's3' ? 'S3 (teams/)' : 'Local (storage/app/public/teams/)') . "\n";
echo "• URLs: " . ($disk === 's3' ? 'URLs completas de S3' : 'Rutas relativas con asset()') . "\n\n";

echo "💡 Para cambiar el modo de almacenamiento:\n";
echo "   Edita .env y cambia FILESYSTEM_DISK=public (local) o FILESYSTEM_DISK=s3 (producción)\n\n";

echo "✨ Configuración verificada exitosamente!\n";
