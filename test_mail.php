<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Este es un correo de prueba desde CodeBattle. Si recibes este mensaje, la configuración de correo está funcionando correctamente.', function ($message) {
        $message->to('raulchaconmelchor28@gmail.com')
                ->subject('Prueba de Correo - CodeBattle');
    });
    
    echo "✓ Correo enviado exitosamente!\n";
    echo "Verifica tu bandeja de entrada en: raulchaconmelchor28@gmail.com\n";
} catch (Exception $e) {
    echo "✗ Error al enviar correo:\n";
    echo $e->getMessage() . "\n";
}
