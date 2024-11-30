<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_booking_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_type_id')->nullable();
            $table->enum('user_type', ['Member', 'User']);
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->bigInteger('check_in_date');
            $table->bigInteger('check_in_time');
            $table->bigInteger('check_out_date');
            $table->bigInteger('check_out_time');
            $table->double('amount', 10, 2)->nullable();
            $table->string('programme_code','255')->nullable();
            $table->string('application_id');
            $table->softDeletes();
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
        Schema::dropIfExists('hall_booking_infos');
    }
}
