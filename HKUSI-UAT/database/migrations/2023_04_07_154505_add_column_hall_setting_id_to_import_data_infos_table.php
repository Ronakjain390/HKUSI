<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHallSettingIdToImportDataInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_data_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('hall_setting_id')->nullable()->after('user_id');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_data_infos', function (Blueprint $table) {
            $table->dropForeign('member_infos_hall_setting_id_foreign');
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings');
        });
    }
}
