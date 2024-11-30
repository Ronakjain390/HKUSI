<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDeadlineDateColumnHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
             $table->bigInteger('payment_deadline_date')->nullable()->after('check_out_time');
            $table->bigInteger('hall_result_date')->nullable()->after('payment_deadline_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
            //
        });
    }
}
