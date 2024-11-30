<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateEventSettingImages extends Migration
{
    /**
     * Run the migrations.
     * Migration created By Akash
     * @return void
     */
    public function up()
    {
        Schema::create('private_event_setting_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_setting_id')->nullable();
            $table->foreign('event_setting_id')->references('id')->on('private_event_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->string('main_image','255')->nullable();
            $table->string('thumb_image','255')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('private_event_setting_images', function (Blueprint $table) {
            $table->dropForeign('private_event_setting_images_event_setting_id_foreign');
            $table->foreign('event_setting_id')->references('id')->on('private_event_settings');
        });
        Schema::dropIfExists('private_event_setting_images');
    }
}
