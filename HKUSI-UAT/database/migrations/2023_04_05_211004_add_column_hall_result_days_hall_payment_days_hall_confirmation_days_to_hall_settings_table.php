<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHallResultDaysHallPaymentDaysHallConfirmationDaysToHallSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hall_settings', function (Blueprint $table) {
            $table->smallInteger('hall_result_days')->nullable()->after('unit_price');
            $table->smallInteger('hall_payment_days')->nullable()->after('hall_result_days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hall_settings', function (Blueprint $table) {
            //
        });
    }
}
