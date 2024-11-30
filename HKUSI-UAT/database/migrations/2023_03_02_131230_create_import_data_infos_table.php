<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportDataInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_data_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('type', ['Member', 'Programme', 'User', 'Hall', 'Room', 'Event']);
            $table->string('reason','255')->nullable();
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
        Schema::table('import_data_infos', function($table)
        {
            $table->dropForeign('import_data_infos_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::dropIfExists('import_data_infos');
    }
}
