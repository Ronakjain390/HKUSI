<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateEventOrders extends Migration
{
    /**
     * Run the migrations.
     * Migration created By Akash
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('private_event_orders');

        Schema::create('private_event_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->foreign('event_id')->references('id')->on('private_event_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->string('booking_id')->nullable();
            $table->string('application_id')->nullable();
            $table->double('unit_price', 10, 2)->nullable();
            $table->string('no_of_seats')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->string('check_operator')->nullable();
            $table->enum('booking_status', ['Pending', 'Paid','Cancelled'])->nullable();
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
