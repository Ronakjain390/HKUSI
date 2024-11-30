<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('quota_id')->nullable();
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('male_old_qty')->nullable();
            $table->integer('male_new_qty')->nullable();
            $table->integer('female_old_qty')->nullable();
            $table->integer('female_new_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_updates', function($table)
        {
            $table->dropForeign('quota_updates_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
            $table->dropForeign('quota_updates_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
        });
        Schema::dropIfExists('quota_updates');
    }
}
