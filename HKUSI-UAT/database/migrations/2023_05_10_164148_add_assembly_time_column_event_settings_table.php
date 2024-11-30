<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssemblyTimeColumnEventSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_settings', function (Blueprint $table) {
           $table->bigInteger('assembly_time')->nullable()->after('location');
           $table->string('assembly_location','255')->nullable()->after('assembly_time');
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
            //
        });
    }
}
