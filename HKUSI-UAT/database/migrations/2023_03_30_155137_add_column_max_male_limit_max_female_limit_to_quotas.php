<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMaxMaleLimitMaxFemaleLimitToQuotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotas', function (Blueprint $table) {
            $table->integer('male_max_quota')->nullable()->after('female');
            $table->integer('female_max_quota')->nullable()->after('male_max_quota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotas', function (Blueprint $table) {
            //
        });
    }
}
