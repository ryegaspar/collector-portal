<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlement_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_name');
            $table->unsignedInteger('admin_id');
            $table->string('account_no', 10);
            $table->string('client_ref_number', 20);
            $table->string('client_name', 50);
            $table->string('consumer_name', 50);
            $table->string('account_status');
            $table->decimal('allowed_sif_amount', 8, 2);
            $table->decimal('allowed_sif_percent', 5, 2);
            $table->decimal('proposed_sif_amount', 8, 2);
            $table->decimal('proposed_sif_percent', 5, 2);
            $table->string('payment_method');
            $table->Integer('number_of_payments');
            $table->date('first_payment_date')->default("01-01-2013");
            $table->string('approval_reason');
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
        Schema::dropIfExists('settlement_requests');
    }
}
