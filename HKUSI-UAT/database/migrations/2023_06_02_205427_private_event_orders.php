<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrivateEventOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_event_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('member_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('booking_id')->nullable();
            $table->string('event_id')->nullable();
            $table->string('application_id')->nullable();
            $table->string('event_name')->nullable();
            $table->bigInteger('event_date')->nullable();
            $table->bigInteger('start_time')->nullable();
            $table->bigInteger('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('assembly_location')->nullable();
            $table->bigInteger('assembly_time')->nullable();
            $table->bigInteger('no_of_tickets')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->string('check_in_operator')->nullable();
            $table->enum('status', ['Enrolled and Confirmed', 'Updated','Pending', 'Cancelled'])->nullable();
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
        Schema::dropIfExists('private_event_orders');
    }
}
