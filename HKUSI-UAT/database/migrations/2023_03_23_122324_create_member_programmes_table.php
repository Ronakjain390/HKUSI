<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_info_id')->nullable();
            $table->foreign('member_info_id')->references('id')->on('member_infos')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('member_programmes', function($table)
        {
            $table->dropForeign('member_programmes_member_info_id_foreign');
            $table->foreign('member_info_id')->references('id')->on('member_infos');
            $table->dropForeign('member_programmes_programme_id_foreign');
            $table->foreign('programme_id')->references('id')->on('programmes');
        });
        Schema::dropIfExists('member_programmes');
    }
}
