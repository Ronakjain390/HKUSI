<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventGroupToPrivateEventOrders extends Migration
{
    /**
     * Run the migrations.
     * Add event group column migration created by Akash
     * @return void
     */
    public function up()
    {
        Schema::table('private_event_orders', function (Blueprint $table) {
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
        Schema::table('private_event_orders', function (Blueprint $table) {
            $table->dropColumn('event_group');
        });
    }
}
