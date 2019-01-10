<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectorGroupInSubsitesAndCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subsites', function (Blueprint $table) {
            $table->text('default_collector_group')->after('description')->default('COL');
        });

        Schema::table('collectors', function (Blueprint $table) {
            $table->text('group')->after('sub_site_id')->default('COL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Laravel issue: https://github.com/laravel/framework/issues/4402
     * Dropping a column with a default value constraint in mssql.
     * Last checked: 12/28/2018
     * Temporary fix: manually delete the constraint on the table, then re-run migration rollback.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subsites', function (Blueprint $table) {
            $table->dropColumn('default_collector_group');
        });

        Schema::table('collectors', function (Blueprint $table) {
            $table->dropColumn('group');
        });
    }
}
