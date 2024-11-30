<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallBookingAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_booking_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_booking_info_id')->nullable();
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('actual_check_in_date');
            $table->bigInteger('actual_check_in_time');
            $table->string('check_in_operator');
            $table->bigInteger('actual_check_out_date');
            $table->bigInteger('actual_check_out_time');
            $table->string('check_out_operator');
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
            $table->dropForeign('hall_booking_attendances_hall_booking_info_id_foreign');
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos');
        });
        Schema::dropIfExists('hall_booking_attendances');
    }
}
