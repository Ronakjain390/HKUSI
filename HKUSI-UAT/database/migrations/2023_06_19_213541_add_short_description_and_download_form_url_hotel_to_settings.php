<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortDescriptionAndDownloadFormUrlHotelToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_settings', function (Blueprint $table) {
        
            $table->text('short_description')->nullable();
            $table->string('download_form_url')->nullable();
        });

        DB::statement('ALTER TABLE hotel_settings MODIFY description TEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_settisngs', function (Blueprint $table) {
            //
        });
    }
}
