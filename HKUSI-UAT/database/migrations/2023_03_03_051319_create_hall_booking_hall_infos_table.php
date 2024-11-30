<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallBookingHallInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_booking_hall_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_booking_info_id')->nullable();
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('college_name');
            $table->string('address')->nullable();
            $table->string('room_type');
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
        Schema::table('hall_booking_hall_infos', function($table)
        {
            $table->dropForeign('hall_booking_hall_infos_hall_booking_info_id_foreign');
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos');
        });
        Schema::dropIfExists('hall_booking_hall_infos');
    }
}
