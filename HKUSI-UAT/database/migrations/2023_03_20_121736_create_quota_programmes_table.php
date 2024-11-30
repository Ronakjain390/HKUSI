<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quota_id')->nullable();
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('programme_id')->nullable();
            $table->foreign('programme_id')->references('id')->on('programmes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_programmes', function($table)
        {
            $table->dropForeign('quota_programmes_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
            $table->dropForeign('quota_programmes_programme_id_foreign');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
        Schema::dropIfExists('quota_programmes');
    }
}
