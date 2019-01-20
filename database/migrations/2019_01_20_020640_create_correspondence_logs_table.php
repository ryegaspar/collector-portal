<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorrespondenceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correspondence_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_name');
            $table->unsignedInteger('admin_id');
            $table->string('account_no', 10);
            $table->string('client_name', 50);
            $table->string('consumer_name', 50);
            $table->string('correspondence_from', 20);
            $table->date('correspondence_date')->default("01-01-2013");
            $table->string('correspondence_type', 20);
            $table->string('correspondence_contact', 20)->nullable();
            $table->string('assigned_department');
            $table->string('assigned_user')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('attachment_mime')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_size')->nullable();
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
        Schema::dropIfExists('correspondence_logs');
    }
}

