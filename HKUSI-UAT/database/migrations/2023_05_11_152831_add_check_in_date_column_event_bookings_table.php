<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckInDateColumnEventBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
           $table->bigInteger('check_in_date')->nullable()->after('no_of_seats');
           $table->bigInteger('check_in_time')->nullable()->after('check_in_date');
           $table->bigInteger('check_operater')->nullable()->after('check_in_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_bookings', function (Blueprint $table) {
            //
        });
    }
}
