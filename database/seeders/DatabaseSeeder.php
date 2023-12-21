<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Permisos de usuarios
        $admin = Role::factory()->create([
            'name' => 'Admin',
        ]);
        $emp = Role::factory()->create([
            'name' => 'Empleado',
        ]);

        //USUARIOS POR DEFECTO PRECARGADOS EN EL SISTEMA
        User::factory()->create([
            'name' => 'Jolber Chirinos',
            'email' => 'jrchirinos@gruporuiz.com',
            'password' => bcrypt(12345678),
            'id_role' => $admin->id,
        ]);

        //USUARIOS POR DEFECTO PRECARGADOS EN EL SISTEMA
        User::factory()->create([
            'name' => 'Otro Empleado',
            'email' => 'otro@gruporuiz.com',
            'password' => bcrypt(12345678),
            'id_role' => $emp->id,
        ]);
    }
}
