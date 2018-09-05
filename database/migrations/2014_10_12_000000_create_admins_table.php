<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('password')->default(bcrypt('Password1'));
            $table->string('tiger_user_id');
//            $table->unsignedSmallInteger('access_level')->default(5);
            $table->boolean('active')->default(true);
            $table->unsignedInteger('site_id')->nullable();
            $table->unsignedInteger('sub_site_id')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
