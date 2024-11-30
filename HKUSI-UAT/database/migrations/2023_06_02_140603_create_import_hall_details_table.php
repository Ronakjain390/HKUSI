<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportHallDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_hall_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->unsignedBigInteger('quota_id')->nullable();
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
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('import_hall_details');
    }
}
