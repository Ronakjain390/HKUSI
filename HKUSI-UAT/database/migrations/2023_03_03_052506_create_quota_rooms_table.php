<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_setting_id');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quota_hall_id');
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls')->onUpdate('cascade')->onDelete('cascade');
            $table->string('college_name','255')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->boolean('status')->default(1)->comment('0:No, 1:Yes');
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
        Schema::table('quota_rooms', function($table)
        {
            $table->dropForeign('quota_rooms_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
            $table->dropForeign('quota_rooms_quota_hall_id_foreign');
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls');
        });
        Schema::dropIfExists('quota_rooms');
    }
}
