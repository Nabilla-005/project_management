<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission list from your task
        $permissions = [
            'manage users',
            'create project',
            'update project',
            'delete project',
            'assign tasks',
            'update tasks',
            'comment tasks',
            'view dashboard',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'api'
            ]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
        $pm    = Role::firstOrCreate(['name' => 'Project Manager', 'guard_name' => 'api']);
        $tm    = Role::firstOrCreate(['name' => 'Team Member', 'guard_name' => 'api']);


        // Assign permissions
        $admin->syncPermissions([
            'manage users', 'create project', 'update project', 'delete project',
            'assign tasks', 'update tasks', 'comment tasks', 'view dashboard'
        ]);

        $pm->syncPermissions([
            'create project', 'update project',
            'assign tasks', 'update tasks', 'comment tasks', 'view dashboard'
        ]);

        $tm->syncPermissions([
            'update tasks', 'comment tasks', 'view dashboard'
        ]);
    }
}