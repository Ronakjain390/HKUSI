<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventStatusToPrivateEventOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('private_event_orders', function (Blueprint $table) {
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
        Schema::table('private_event_orders', function (Blueprint $table) {
            //
        });
    }
}
