<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissionsCalendars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'read calendar', 'guard_name' => 'admin']);


        $superadmin = Role::findByName('super-admin', 'admin');
        $superadmin->givePermissionTo([
            'read calendar'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $superadmin = Role::findByName('super-admin', 'admin');
        $superadmin->revokePermissionTo(['read calendar']);

        Permission::findByName('read calendar', 'admin')->delete();
    }
}
