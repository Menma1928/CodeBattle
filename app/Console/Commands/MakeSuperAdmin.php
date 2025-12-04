<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeSuperAdmin extends Command
{
    protected $signature = 'user:make-superadmin {email}';
    protected $description = 'Asigna el rol Super Admin a un usuario por email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->assignRole('Super Admin');
            $this->info("Rol 'Super Admin' asignado correctamente a {$email}.");
        } else {
            $this->error('Usuario no encontrado.');
        }
    }
}
