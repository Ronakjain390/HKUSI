<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTermsConditonColumnEventSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_settings', function (Blueprint $table) {
            $table->longText('terms_condition')->nullable()->after('additional_info');
            $table->longText('terms_link')->nullable()->after('terms_condition');
            $table->longText('pre_arrival')->nullable()->after('terms_link');
            $table->longText('pre_link')->nullable()->after('pre_arrival');
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
