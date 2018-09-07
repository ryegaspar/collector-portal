<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('site_id');
            $table->boolean('has_team_leaders');
            $table->text('description')->nullable();
            $table->unsignedInteger('min_desk_number');
            $table->unsignedInteger('max_desk_number');
            $table->unsignedInteger('collectone_id_assignment_method');
            $table->string('prefixes')->nullable();
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
        Schema::dropIfExists('subsites');
    }
}
