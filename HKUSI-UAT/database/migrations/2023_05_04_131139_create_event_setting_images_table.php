<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSettingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_setting_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_setting_id')->nullable();
            $table->foreign('event_setting_id')->references('id')->on('event_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->string('main_image','255')->nullable();
            $table->string('thumb_image','255')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_setting_images', function (Blueprint $table) {
            $table->dropForeign('event_setting_images_event_setting_id_foreign');
            $table->foreign('event_setting_id')->references('id')->on('event_settings');
        });
        Schema::dropIfExists('event_setting_images');
    }
}
