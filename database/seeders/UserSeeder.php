<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles (o recuperarlos si ya existen)
        $super_admin_role = Role::firstOrCreate(['name' => 'Super Admin']);
        $administrador_role = Role::firstOrCreate(['name' => 'Administrador']);
        $participante_role = Role::firstOrCreate(['name' => 'Participante']);

        // Crear permisos de eventos
        Permission::firstOrCreate(['name' => 'ver eventos']);
        Permission::firstOrCreate(['name' => 'crear eventos']);
        Permission::firstOrCreate(['name' => 'editar eventos']);
        Permission::firstOrCreate(['name' => 'eliminar eventos']);
        Permission::firstOrCreate(['name' => 'ver mis eventos']);
        Permission::firstOrCreate(['name' => 'inscribirse eventos']);

        // Crear permisos de equipos
        Permission::firstOrCreate(['name' => 'ver equipos']);
        Permission::firstOrCreate(['name' => 'crear equipos']);
        Permission::firstOrCreate(['name' => 'editar equipos']);
        Permission::firstOrCreate(['name' => 'eliminar equipos']);
        Permission::firstOrCreate(['name' => 'ver mis equipos']);
        Permission::firstOrCreate(['name' => 'unirse equipos']);
        Permission::firstOrCreate(['name' => 'invitar miembros']);
        Permission::firstOrCreate(['name' => 'expulsar miembros']);

        // Crear permisos de usuarios
        Permission::firstOrCreate(['name' => 'ver usuarios']);
        Permission::firstOrCreate(['name' => 'crear usuarios']);
        Permission::firstOrCreate(['name' => 'editar usuarios']);
        Permission::firstOrCreate(['name' => 'eliminar usuarios']);
        Permission::firstOrCreate(['name' => 'asignar roles']);

        // Crear permisos de participación
        Permission::firstOrCreate(['name' => 'participar competencias']);
        Permission::firstOrCreate(['name' => 'enviar soluciones']);
        Permission::firstOrCreate(['name' => 'ver resultados']);
        Permission::firstOrCreate(['name' => 'ver ranking']);

        // Crear permisos de administración
        Permission::firstOrCreate(['name' => 'gestionar permisos']);
        Permission::firstOrCreate(['name' => 'ver reportes']);
        Permission::firstOrCreate(['name' => 'moderar contenido']);

        // Asignar permisos a roles (sincronizar para evitar duplicados)
        $super_admin_role->syncPermissions(Permission::all());

        $administrador_role->syncPermissions([
            'ver eventos',
            'crear eventos',
            'ver mis eventos',
            'ver equipos',
            'expulsar miembros',
            'ver resultados',
            'ver ranking',
            'ver reportes',
        ]);

        $participante_role->syncPermissions([
            'ver equipos',
            'ver eventos',
            'inscribirse eventos',
            'ver mis equipos',
            'crear equipos',
            'unirse equipos',
            'invitar miembros',
            'participar competencias',
            'enviar soluciones',
            'ver resultados',
            'ver ranking',
        ]);

        // Crear usuarios de prueba (o recuperarlos si ya existen)
        $super_admin_user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$super_admin_user->hasRole('Super Admin')) {
            $super_admin_user->assignRole('Super Admin');
        }

        $admin_user = User::firstOrCreate(
            ['email' => 'administrador@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$admin_user->hasRole('Administrador')) {
            $admin_user->assignRole('Administrador');
        }

        $participante_user = User::firstOrCreate(
            ['email' => 'participante@gmail.com'],
            [
                'name' => 'Participante',
                'password' => bcrypt('12345678'),
            ]
        );
        if (!$participante_user->hasRole('Participante')) {
            $participante_user->assignRole('Participante');
        }

        // Solo crear usuarios adicionales si no existen muchos ya
        $currentUserCount = User::count();
        if ($currentUserCount < 163) { // 3 usuarios base + 160 adicionales
            $participantesToCreate = max(0, 83 - User::role('Participante')->count());
            if ($participantesToCreate > 0) {
                $users = User::factory($participantesToCreate)->create();
                foreach ($users as $user) {
                    $user->assignRole('Participante');
                }
            }
            
            $administradoresCrear = max(0, 80 - User::role('Administrador')->count());
            if ($administradoresCrear > 0) {
                $users = User::factory($administradoresCrear)->create();
                foreach ($users as $user) {
                    $user->assignRole('Administrador');
                }
            }
        }
    }
}
