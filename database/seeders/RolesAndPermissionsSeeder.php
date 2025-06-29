<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()["cache"]->forget("spatie.permission.cache");

        // Create permissions
        Permission::firstOrCreate(["name" => "manage users"]);
        Permission::firstOrCreate(["name" => "view dashboard"]);
        Permission::firstOrCreate(["name" => "import sms"]);
        Permission::firstOrCreate(["name" => "send sms"]);
        Permission::firstOrCreate(["name" => "view profile"]);

        // Create roles and assign existing permissions
        $adminRole = Role::firstOrCreate(["name" => "admin"]);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::firstOrCreate(["name" => "user"]);
        $userRole->givePermissionTo(["view dashboard", "view profile"]);

        // Create supervisor role and assign all permissions except 'manage users'
        $supervisorRole = Role::firstOrCreate(["name" => "supervisor"]);
        $allPermissions = Permission::all();
        $permissionsForSupervisor = $allPermissions->filter(function ($permission) {
            return $permission->name !== 'manage users'; // Exclude 'manage users' permission
        });
        $supervisorRole->syncPermissions($permissionsForSupervisor);

        // Assign admin role to a default user (e.g., the first user created)
        $user = \App\Models\User::first();
        if ($user) {
            $user->assignRole("admin");
        }
    }
}
