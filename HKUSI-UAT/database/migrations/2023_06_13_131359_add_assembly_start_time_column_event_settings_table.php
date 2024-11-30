<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssemblyStartTimeColumnEventSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->bigInteger('assembly_start_time')->nullable()->after('assembly_time');
			$table->bigInteger('assembly_end_time')->nullable()->after('assembly_start_time');
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
