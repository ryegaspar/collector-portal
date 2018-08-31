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

        Permission::create(['name' => 'read adjustment', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update adjustment', 'guard_name' => 'admin']);

        Permission::create(['name' => 'create script', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read script', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update script', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete script', 'guard_name' => 'admin']);

        Permission::create(['name' => 'create collector', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read collector', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update collector', 'guard_name' => 'admin']);
        Permission::create(['name' => 'disable collector', 'guard_name' => 'admin']);

        Permission::create(['name' => 'create admin', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read admin', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update admin', 'guard_name' => 'admin']);
        Permission::create(['name' => 'disable admin', 'guard_name' => 'admin']);

        Permission::create(['name' => 'read roles_permission', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update roles_permission', 'guard_name' => 'admin']);

        Permission::create(['name' => 'create site', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read site', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update site', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete site', 'guard_name' => 'admin']);

        $superadmin = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $office_support = Role::create(['name' => 'office-support', 'guard_name' => 'admin']);
        $manager = Role::create(['name' => 'manager', 'guard_name' => 'admin']);
        $site_manager = Role::create(['name' => 'site-manager', 'guard_name' => 'admin']);
        $sub_site_manager = Role::create(['name' => 'sub-site-manager', 'guard_name' => 'admin']);
        $team_leader = Role::create(['name' => 'team-leader', 'guard_name' => 'admin']);

        $superadmin->givePermissionTo(['read adjustment', 'update adjustment']);
        $superadmin->givePermissionTo(['create script', 'read script', 'update script', 'delete script']);
        $superadmin->givePermissionTo(['create collector', 'read collector', 'update collector', 'disable collector']);
        $superadmin->givePermissionTo(['create admin', 'read admin', 'update admin', 'disable admin']);
        $superadmin->givePermissionTo(['read roles_permission', 'update roles_permission']);
        $superadmin->givePermissionTo(['create site', 'read site', 'update site', 'delete site']);

        $admin->givePermissionTo(['create script', 'read script', 'update script', 'delete script']);

        $manager->givePermissionTo(['read script']);
    }
}
