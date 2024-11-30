<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportPrivateEventBookingInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_private_event_booking_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_data_info_id')->nullable();
            $table->foreign('export_data_info_id')->references('id')->on('export_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('booking_id')->nullable();
            $table->string('application_number')->nullable();
            $table->string('event_id')->nullable();
            $table->string('event_name')->nullable();
            $table->bigInteger('event_date')->nullable();
            $table->bigInteger('start_time')->nullable();
            $table->bigInteger('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('assembly_location')->nullable();
            $table->bigInteger('assembly_start_time')->nullable();
            $table->bigInteger('assembly_end_time')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->bigInteger('check_operator')->nullable();
            $table->string('booking_status')->nullable();
            $table->string('event_status')->nullable();
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
        Schema::dropIfExists('export_private_event_booking_infos');
    }
}
