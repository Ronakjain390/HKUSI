<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssemblyStartTimeColumnImportEventDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_event_details', function (Blueprint $table) {
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
        Schema::table('import_event_details', function (Blueprint $table) {
            //
        });
    }
}
