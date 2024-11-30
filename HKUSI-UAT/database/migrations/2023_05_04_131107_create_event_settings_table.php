<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('event_category_id')->nullable(); 
            $table->string('event_name')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('location','255')->nullable();
            $table->bigInteger('date')->nullable();
            $table->bigInteger('application_deadline')->nullable();
            $table->integer('quota')->nullable();
            $table->integer('quota_balance')->nullable();
            $table->double('unit_price', 10, 2)->nullable();
            $table->string('additional_info','255')->nullable();
            $table->string('notes','255')->nullable();
            $table->integer('booking_limit')->nullable();
            $table->string('type','50')->nullable();
            $table->string('thumbanil','255')->nullable();
            $table->string('main_image','255')->nullable();
            $table->string('thumb_image','255')->nullable();
            $table->enum('status', ['Enabled', 'Disabled','Cancelled'])->nullable();
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
        Schema::table('event_settings', function (Blueprint $table) {
            $table->dropForeign('event_settings_programme_id_foreign');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
        Schema::dropIfExists('event_settings');
    }
}
