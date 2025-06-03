<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StudentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el rol de estudiante si no existe
        if (!Role::where('name', 'Estudiante')->exists()) {
            Role::create(['name' => 'Estudiante', 'guard_name' => 'web']);
        }

        // Asignar permisos básicos para estudiantes
        $permissions = [
            'view_profile',
            'edit_profile',
            'view_courses',
            'enroll_courses'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::where('name', 'Estudiante')->first()->syncPermissions($permissions);
    }
}