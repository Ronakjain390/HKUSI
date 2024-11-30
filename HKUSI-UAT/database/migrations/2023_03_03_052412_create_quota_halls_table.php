<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotaHallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_halls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quota_id');
            $table->foreign('hall_setting_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('quota_id')->references('id')->on('quotas')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->integer('total_quotas')->nullable();
            $table->integer('male')->nullable();
            $table->integer('female')->nullable();
            $table->string('college_name','255')->nullable();
            $table->string('address','255')->nullable();
            $table->string('room_type','255')->nullable();
            $table->string('ass_name','255')->nullable();
            $table->string('ass_mobile','255')->nullable();
            $table->string('ass_email','255')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->bigInteger('check_out_date')->nullable();
            $table->bigInteger('check_out_time')->nullable();
            $table->string('pdf','255')->nullable();
            $table->boolean('status')->default(1)->comment('0:No, 1:Yes');
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
        Schema::table('quota_halls', function($table)
        {
            $table->dropForeign('quota_halls_quota_id_foreign');
            $table->foreign('quota_id')->references('id')->on('quotas');
        });
        Schema::dropIfExists('quota_halls');
    }
}
