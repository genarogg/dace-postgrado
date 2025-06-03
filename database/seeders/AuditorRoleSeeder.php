<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuditorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el rol de Auditor
        $role = Role::create(['name' => 'Auditor', 'guard_name' => 'web']);

        // Asignar permisos de auditoría
        $permissions = [
            'audit',
            'restore_audit',
            'view_any_role',
            'view_role'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $role->givePermissionTo($permissions);
    }
}
