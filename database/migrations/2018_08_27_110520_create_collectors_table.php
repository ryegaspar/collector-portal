<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tiger_user_id');
            $table->string('desk');
            $table->string('username')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->unsignedInteger('sub_site_id');
            $table->unsignedInteger('team_leader_id')->nullable();
            $table->unsignedInteger('department_id')->default(1);
            $table->unsignedInteger('commission_structure_id')->default(1);
            $table->unsignedInteger('status_id')->default(1);
            $table->unsignedInteger('batch_id')->nullable();
            $table->date('start_date')->default("01-01-2013");
            $table->date('start_full_month_date')->default("01-01-2013");
            $table->date('change_pass_at')->nullable();

            $table->string('password')->default(bcrypt('Password1'));
            $table->boolean('active')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectors');
    }
}
