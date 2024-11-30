<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHotelTypeToImportDataInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_data_infos', function (Blueprint $table) {
            DB::statement('ALTER TABLE import_data_infos MODIFY COLUMN type ENUM("Member", "Programme", "User", "Hall", "Room", "Event", "Country", "Private Event Booking", "Private Event", "Hotel")');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_data_infos', function (Blueprint $table) {
            //
        });
    }
}
