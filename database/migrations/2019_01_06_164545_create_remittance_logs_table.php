<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemittanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittance_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_name'); //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
            $table->unsignedInteger('admin_id');
            $table->string('client_name', 50);
            $table->string('sub_client_name', 50)->nullable();
            $table->date('remit_date')->nullable();
            $table->date('period_start_date')->default("01-01-2013");
            $table->date('period_end_date')->default("01-01-2013");
            $table->decimal('total_collections', 10, 2);
            $table->decimal('total_client_collections', 10, 2);
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('remit_amount', 10, 2);
            $table->unsignedInteger('report_sent')->default(0);
            $table->unsignedInteger('report_sent_by')->nullable();
            $table->date('report_sent_date')->nullable();
            $table->unsignedInteger('remittance_sent')->default(0);
            $table->unsignedInteger('remittance_sent_by')->nullable();
            $table->date('remittance_sent_date')->nullable();
            $table->unsignedInteger('commission_recieved')->default(0);
            $table->unsignedInteger('commission_recieved_by')->nullable();
            $table->date('commission_recieved_date')->nullable();
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
        Schema::dropIfExists('remittance_logs');
    }
}
