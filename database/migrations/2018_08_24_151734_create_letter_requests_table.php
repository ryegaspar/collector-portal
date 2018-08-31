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
            $table->string('collector_name', 30);
            $table->date('send_date');
            $table->text('description');
            $table->string('request_method');
            $table->string('type');
            $table->unsignedInteger('status')->default(0);
            $table->string('borrower_type');
//            $table->
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