<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLetterRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dbr_no', 10);
            $table->string('creator_name'); //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
            $table->morphs('requestable');
            $table->text('notes');
            $table->unsignedInteger('request_method');
            $table->unsignedInteger('type');
            $table->unsignedInteger('status')->default(0);
            $table->unsignedInteger('borrower_type');
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
        Schema::dropIfExists('letter_requests');
    }
}