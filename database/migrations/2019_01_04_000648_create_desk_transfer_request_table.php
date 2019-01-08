<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeskTransferRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desk_transfer_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dbr_no', 10);
            $table->unsignedInteger('fulfilled_by')->nullable();
            $table->string('creator_name'); //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
            $table->morphs('requestable');
            $table->text('notes')->nullable();
            $table->unsignedInteger('request_reason');
            $table->unsignedInteger('status')->default(0);
            $table->string('desk_from', 3);
            $table->string('desk', 3);
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
        Schema::dropIfExists('desk_transfer_requests');
    }
}
