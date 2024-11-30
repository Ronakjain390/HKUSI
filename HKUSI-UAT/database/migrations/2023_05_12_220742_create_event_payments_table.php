<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id','30')->nullable();
            $table->string('application_id')->nullable();
            $table->string('payment_id','50')->nullable(); 
            $table->string('order_no')->nullable();           
            $table->string('service_type')->nullable();            
            $table->string('reference_no','255')->nullable();
            $table->string('card_no','255')->nullable();
            $table->string('approval_code','255')->nullable();
            $table->string('merchant_id','255')->nullable();
            $table->bigInteger('expiry_time')->nullable();
            $table->bigInteger('pay_time')->nullable();
            $table->double('amount', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('pay_type')->nullable();
            $table->enum('payment_type', ['Event Booking']);
            $table->json('pay_result')->nullable();
            $table->string('payment_status','50')->nullable();
            $table->boolean('status')->default(1)->comment('0:No, 1:Yes');
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
        Schema::dropIfExists('event_payments');
    }
}
