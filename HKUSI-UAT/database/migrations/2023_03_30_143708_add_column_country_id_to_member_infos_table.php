<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCountryIdToMemberInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('study_country_id')->nullable()->after('import_data_info_id');
            $table->foreign('study_country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_infos', function (Blueprint $table) {
            $table->dropForeign('member_infos_study_country_id_foreign');
            $table->foreign('study_country_id')->references('id')->on('countries');
        });
    }
}