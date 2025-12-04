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

        $super_admin_role = Role::create(['name' => 'Super Admin']);
        $administrador_role = Role::create(['name' => 'Administrador']);
        $participante_role = Role::create(['name' => 'Participante']);


        Permission::create(['name' => 'ver eventos']);
        Permission::create(['name' => 'crear eventos']);
        Permission::create(['name' => 'editar eventos']);
        Permission::create(['name' => 'eliminar eventos']);
        Permission::create(['name' => 'ver mis eventos']);
        Permission::create(['name' => 'inscribirse eventos']);


        Permission::create(['name' => 'ver equipos']);
        Permission::create(['name' => 'crear equipos']);
        Permission::create(['name' => 'editar equipos']);
        Permission::create(['name' => 'eliminar equipos']);
        Permission::create(['name' => 'ver mis equipos']);
        Permission::create(['name' => 'unirse equipos']);
        Permission::create(['name' => 'invitar miembros']);
        Permission::create(['name' => 'expulsar miembros']);

        Permission::create(['name' => 'ver usuarios']);
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);
        Permission::create(['name' => 'asignar roles']);

        Permission::create(['name' => 'participar competencias']);
        Permission::create(['name' => 'enviar soluciones']);
        Permission::create(['name' => 'ver resultados']);
        Permission::create(['name' => 'ver ranking']);


        Permission::create(['name' => 'gestionar permisos']);
        Permission::create(['name' => 'ver reportes']);
        Permission::create(['name' => 'moderar contenido']);


        $super_admin_role->givePermissionTo(Permission::all());

        $administrador_role->givePermissionTo([
            'ver eventos',
            'crear eventos',
            'ver mis eventos',
            'ver equipos',
            'expulsar miembros',
            'ver resultados',
            'ver ranking',
            'ver reportes',
        ]);

        $participante_role->givePermissionTo([
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


        $super_admin_user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $super_admin_user->assignRole('Super Admin');

        $admin_user = User::create([
            'name' => 'Administrador',
            'email' => 'administrador@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $admin_user->assignRole('Administrador');

        $participante_user = User::create([
            'name' => 'Participante',
            'email' => 'participante@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        $participante_user->assignRole('Participante');

        $users = User::factory(80)->create();
        foreach ($users as $user) {
            $user->assignRole('Participante');
        }
        $users = User::factory(80)->create();
        foreach ($users as $user) {
            $user->assignRole('Administrador');
        }
        
    }
}
