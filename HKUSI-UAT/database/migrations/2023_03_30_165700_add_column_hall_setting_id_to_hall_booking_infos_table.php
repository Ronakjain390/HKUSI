<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHallSettingIdToHallBookingInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_booking_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('hall_setting_id')->nullable()->after('id');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('hall_booking_infos_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
        });
    }
}