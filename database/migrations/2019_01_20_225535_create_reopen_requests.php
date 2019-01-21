<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReopenRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reopen_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_name');
            $table->unsignedInteger('admin_id');
            $table->string('account_no', 10);
            $table->string('client_ref_number', 20);
            $table->string('client_name', 50);
            $table->string('consumer_name', 50);
            $table->string('account_status');
            $table->date('assignment_date');
            $table->date('closure_date');
            $table->string('reopen_reason');
            $table->unsignedInteger('status')->default(0);
            $table->unsignedInteger('status_last_updated_by')->nullable();
            $table->date('status_last_update_date')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('reopen_requests');
    }
}
