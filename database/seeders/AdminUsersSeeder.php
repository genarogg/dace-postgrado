<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario super_admin
        $superAdmin = User::create([
            'name' => 'Super Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);

        // Asignar rol super_admin
        if (!Role::where('name', 'super_admin')->exists()) {
            Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        }
        $superAdmin->assignRole('super_admin');

        // Crear usuario panel
        $panelUser = User::create([
            'name' => 'Usuario Panel',
            'email' => 'operador@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        // Asignar rol panel_user
        if (!Role::where('name', 'panel_user')->exists()) {
            Role::create(['name' => 'panel_user', 'guard_name' => 'web']);
        }
        $panelUser->assignRole('panel_user');

        $this->command->info('Usuarios administrativos creados:');
        $this->command->info('Super Admin - Email: admin@admin.com - Contraseña: admin');
        $this->command->info('Panel User - Email: operador@gmail.com - Contraseña: admin');
    }
}
