<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_name');
            $table->unsignedInteger('admin_id');
            $table->string('account_no', 10);
            $table->string('client_name', 50);
            $table->string('consumer_name', 50);
            $table->decimal('amount', 8, 2);
            $table->date('payment_date')->default("01-01-2013");
            $table->string('payment_type', 20);
            $table->Integer('last_4_bank_no');
            $table->unsignedInteger('refund_status')->default(0);
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
        Schema::dropIfExists('refund_requests');
    }
}
