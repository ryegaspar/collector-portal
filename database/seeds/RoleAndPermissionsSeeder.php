<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        Permission::create(['guard_name' => 'admin', 'name' => 'read adjustments']);
        Permission::create(['guard_name' => 'admin', 'name' => 'update adjustments']);

        Permission::create(['name' => 'create users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'disable users', 'guard_name' => 'admin']);

        Permission::create(['name' => 'create scripts', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read scripts', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update scripts', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete scripts', 'guard_name' => 'admin']);

        $superadmin = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $manager = Role::create(['name' => 'manager', 'guard_name' => 'admin']);
        $office_support = Role::create(['name' => 'office-support', 'guard_name' => 'admin']);

        $superadmin->givePermissionTo(['read adjustments', 'update adjustments']);
        $superadmin->givePermissionTo(['create users', 'read users', 'update users', 'disable users']);
        $superadmin->givePermissionTo(['create scripts', 'read scripts', 'update scripts', 'delete scripts']);

        $admin->givePermissionTo(['create scripts', 'read scripts', 'update scripts', 'delete scripts']);

        $manager->givePermissionTo(['read scripts']);
    }
}
