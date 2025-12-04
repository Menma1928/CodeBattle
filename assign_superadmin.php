<?php

use App\Models\User;

$user = User::where('email', 'jonmartinez902@gmail.com')->first();
if ($user) {
    $user->assignRole('Super Admin');
    echo "Rol 'Super Admin' asignado correctamente.\n";
} else {
    echo "Usuario no encontrado.\n";
}
