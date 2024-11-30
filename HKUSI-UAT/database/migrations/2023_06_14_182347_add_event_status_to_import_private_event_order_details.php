<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventStatusToImportPrivateEventOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_private_event_order_details', function (Blueprint $table) {
            $table->enum('event_status', ['Completed', 'Updated','Cancelled', 'Paid', 'Pending'])->nullable();
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
            //
        });
    }
}
