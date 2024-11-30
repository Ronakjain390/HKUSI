<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallBookingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_booking_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_booking_info_id')->nullable();
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quota_id')->nullable();
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quota_hall_id')->nullable();
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quota_room_id')->nullable();
            $table->foreign('quota_room_id')->references('id')->on('quota_rooms')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_type_id')->nullable();
            $table->enum('user_type', ['Member', 'User']);
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->bigInteger('check_in_date');
            $table->bigInteger('check_in_time');
            $table->bigInteger('check_out_date');
            $table->bigInteger('check_out_time');
            $table->double('amount', 10, 2)->nullable();
            $table->string('programme_code','255')->nullable();
            $table->string('application_id','50');
            $table->enum('booking_type', ['s','g'])->default('s');
            $table->enum('status', ['Completed','Pending','Accepted','Paid','Cancelled','Updated','Rejected'])->nullable();
            $table->string('booking_number','50');
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
        Schema::table('hall_booking_groups', function (Blueprint $table) {
            $table->dropForeign('hall_booking_groups_hall_booking_info_id_foreign');
            $table->foreign('hall_booking_info_id')->references('id')->on('hall_booking_infos');
            $table->dropForeign('hall_booking_groups_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
            $table->dropForeign('hall_booking_groups_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
            $table->dropForeign('hall_booking_groups_quota_hall_id_foreign');
            $table->foreign('quota_hall_id')->references('id')->on('quota_halls');
            $table->dropForeign('hall_booking_groups_quota_room_id_foreign');
            $table->foreign('quota_room_id')->references('id')->on('quota_rooms');
        });
        Schema::dropIfExists('hall_booking_groups');
    }
}
