<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberHallSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_hall_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('member_info_id')->nullable();
            $table->foreign('member_info_id')->references('id')->on('member_infos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_hall_settings', function($table)
        {
            $table->dropForeign('member_hall_settings_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
            $table->dropForeign('member_hall_settings_member_info_id_foreign');
            $table->foreign('member_info_id')->references('id')->on('member_infos');
        });
        Schema::dropIfExists('member_hall_settings');
    }
}
