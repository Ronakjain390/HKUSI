<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAssemblyTimeInImportPrivateEventDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_private_event_details', function (Blueprint $table) {
            $table->dropColumn('assembly_time');
            $table->bigInteger('assembly_start_time')->nullable();
            $table->bigInteger('assembly_end_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_private_event_details', function (Blueprint $table) {
            //
        });
    }
}