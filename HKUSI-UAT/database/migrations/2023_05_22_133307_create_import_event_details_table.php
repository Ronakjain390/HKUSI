<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportEventDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_event_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('hall_setting_id')->nullable();
            $table->foreign('hall_setting_id')->references('id')->on('hall_settings')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('event_category_id')->nullable(); 
            $table->integer('language_id')->nullable();
            $table->string('event_name')->nullable();
            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('location','255')->nullable();
            $table->bigInteger('date')->nullable();
            $table->bigInteger('time')->nullable();
            $table->bigInteger('application_deadline')->nullable();
            $table->integer('quota')->nullable();
            $table->integer('quota_balance')->nullable();
            $table->double('unit_price', 10, 2)->nullable();
            $table->string('additional_info','255')->nullable();
            $table->string('notes','255')->nullable();
            $table->integer('booking_limit')->nullable();
            $table->string('type','50')->nullable();
            $table->string('thumbanil','255')->nullable();
            $table->string('main_image','255')->nullable();
            $table->string('thumb_image','255')->nullable();
            $table->bigInteger('assembly_time')->nullable();
            $table->string('assembly_location','255')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->longText('terms_link')->nullable();
            $table->longText('pre_arrival')->nullable();
            $table->longText('pre_link')->nullable();
            $table->string('reason')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('import_event_details');
    }
}
