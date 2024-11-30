<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInHallBookingAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_attendances', function (Blueprint $table) {
            $table->enum('status', ['Check-in', 'Check-out'])->nullable()->after('check_out_operator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hall_booking_attendances', function (Blueprint $table) {
            $table->dropColumn([
                'status',
            ]);
        });
    }
}
