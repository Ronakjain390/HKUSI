<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuotaIdToQuotaRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quota_rooms', function (Blueprint $table) {
            $table->unsignedBigInteger('quota_id')->nullable()->after('hall_setting_id');
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quota_rooms', function (Blueprint $table) {
            $table->dropForeign('quota_rooms_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
        });
    }
}
