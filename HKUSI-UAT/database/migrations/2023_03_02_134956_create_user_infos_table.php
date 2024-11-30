<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('surname','255')->nullable();
            $table->string('given_name','255')->nullable();
            $table->string('title','255')->nullable();
            $table->string('department','255')->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->string('mobile_tel_no','20')->nullable();
            $table->string('location','255')->nullable();
            $table->boolean('status')->default(1)->comment('0:No, 1:Yes');
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
        Schema::table('user_infos', function($table)
        {
            $table->dropForeign('user_infos_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
            $table->dropForeign('user_infos_import_data_info_id_foreign');
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos');
        });
        Schema::dropIfExists('user_infos');
    }
}
