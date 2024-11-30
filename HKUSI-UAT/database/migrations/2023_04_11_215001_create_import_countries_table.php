<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name','255');
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
        Schema::table('import_countries', function($table)
        {
            $table->dropForeign('import_countries_import_data_info_id_foreign');
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos');
        });
        Schema::dropIfExists('import_countries');
    }
}
