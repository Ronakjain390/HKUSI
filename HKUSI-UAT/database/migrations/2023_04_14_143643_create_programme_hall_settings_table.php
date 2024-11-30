<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeHallSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programme_hall_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('programme_id')->nullable();
            $table->foreign('programme_id')->references('id')->on('programmes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programme_hall_settings', function($table)
        {
            $table->dropForeign('programme_hall_settings_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
            $table->dropForeign('programme_hall_settings_programme_id_foreign');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
        Schema::dropIfExists('programme_hall_settings');
    }
}
