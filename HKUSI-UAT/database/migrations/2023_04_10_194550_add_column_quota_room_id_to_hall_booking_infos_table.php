<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuotaRoomIdToHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('quota_room_id')->nullable()->after('quota_hall_id');
            $table->foreign('quota_room_id')->references('id')->on('quota_rooms')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('member_infos_quota_room_id_foreign');
            $table->foreign('quota_room_id')->references('id')->on('quota_rooms');
        });
    }
}
