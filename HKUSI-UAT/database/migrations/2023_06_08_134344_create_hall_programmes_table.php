<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHallProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hall_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qouta_hall_id')->nullable();
            $table->foreign('qouta_hall_id')->references('id')->on('quota_halls')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('hall_programmes', function($table)
        {
            $table->dropForeign('hall_programmes_qouta_hall_id_foreign');
            $table->foreign('qouta_hall_id')->references('id')->on('quota_halls');
            $table->dropForeign('hall_programmes_programme_id_foreign');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
        Schema::dropIfExists('hall_programmes');
    }
}
