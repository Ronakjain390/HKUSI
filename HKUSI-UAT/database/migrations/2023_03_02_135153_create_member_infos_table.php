<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('image_bank_id')->nullable();
            $table->foreign('image_bank_id')->references('id')->on('image_banks')->onUpdate('cascade')->onDelete('cascade');
            $table->string('application_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('surname','255')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('given_name','255')->nullable();
            $table->string('mobile_tel_no','20')->nullable();
            $table->string('title','255')->nullable();
            $table->string('chinese_name','255')->nullable();
            $table->string('hkid_card_no','255')->nullable();
            $table->string('passport_no','255')->nullable();
            $table->bigInteger('date_of_birth')->nullable();
            $table->string('nationality','255')->nullable();
            $table->string('study_country','255')->nullable();
            $table->string('contact_english_name','255')->nullable();
            $table->string('contact_chinese_name','255')->nullable();
            $table->string('contact_relationship','255')->nullable();
            $table->string('contact_tel_no','20')->nullable();
            $table->string('language','100')->nullable();
            $table->string('push_notification','100')->nullable();
            $table->boolean('status')->default(0)->comment('0:No, 1:Yes');
            $table->softDeletes();
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
        Schema::table('member_infos', function($table)
        {
            $table->dropForeign('member_infos_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
            $table->dropForeign('member_infos_import_data_info_id_foreign');
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos');
            $table->dropForeign('member_infos_image_bank_id_foreign');
            $table->foreign('image_bank_id')->references('id')->on('image_banks');
        });
        Schema::dropIfExists('member_infos');
    }
}
