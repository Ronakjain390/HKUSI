<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
            $table->enum('status', ['Completed', 'Pending','Accepted','Paid','Cancelled','Updated','Rejected'])->nullable()->after('application_id');
            $table->string('booking_number','50')->after('id');
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
