<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_programmes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('application_number')->nullable();
            $table->string('programme_code','255')->nullable();
            $table->string('programme_name','255')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->string('reason','255')->nullable();
            $table->boolean('status')->default(1)->comment('1:Enabled, 0:Disabled');
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
        Schema::table('import_programmes', function($table)
        {
            $table->dropForeign('import_programmes_import_data_info_id_foreign');
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos');
        });
        Schema::dropIfExists('import_programmes');
    }
}
