<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventGroupToImportPrivateEventOrderDetails extends Migration
{
    /**
     * Run the migrations.
     * Add event group column migration created by Akash
     * @return void
     */
    public function up()
    {
        Schema::table('import_private_event_order_details', function (Blueprint $table) {
            $table->string('event_group')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_private_event_order_details', function (Blueprint $table) {
            $table->dropColumn('event_group');
            
        });
    }
}
