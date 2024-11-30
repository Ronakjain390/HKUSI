<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuotaHallIdToHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('quota_hall_id')->nullable()->after('quota_id');
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('member_infos_quota_hall_id_foreign');
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls');
        });
    }
}
