<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quota_id')->nullable();
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_countries', function($table)
        {
            $table->dropForeign('quota_countries_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
            $table->dropForeign('quota_countries_country_id_foreign');
            $table->foreign('country_id')->references('id')->on('countries');
        });
        Schema::dropIfExists('quota_countries');
    }
}
