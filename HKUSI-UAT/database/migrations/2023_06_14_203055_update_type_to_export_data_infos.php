<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeToExportDataInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE export_data_infos MODIFY COLUMN type ENUM("Member", "Programme", "User", "Hall", "Room", "EventBooking", "Payment", "HallBooking", "PrivateEventBooking")');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('export_data_infos', function (Blueprint $table) {
            //
        });
    }
}
