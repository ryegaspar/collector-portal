<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreatePermissionsDeskTransferRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create(['name' => 'review desk-transfer-request', 'guard_name' => 'admin']);

        $superAdmin = Role::findByName('super-admin', 'admin');
        $superAdmin->givePermissionTo([
            'review desk-transfer-request'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $superAdmin = Role::findByName('super-admin', 'admin');
        $superAdmin->revokePermissionTo(['review desk-transfer-request']);

        Permission::findByName('review desk-transfer-request', 'admin')->delete();
    }
}
