<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAppVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_app_versions', function (Blueprint $table) {
            $table->id();
            $table->date('ios_release_date')->nullable();
            $table->string('ios_version')->nullable();
            $table->string('ios_app_store_url')->nullable();
            $table->enum('ios_force_update', ['Yes', 'No'])->nullable()->default('No');
            $table->date('android_release_date')->nullable();
            $table->string('android_version')->nullable();
            $table->string('android_app_store_url')->nullable();
            $table->enum('android_force_update', ['Yes', 'No'])->nullable()->default('No');
            $table->text('updates_remark')->nullable();
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
        Schema::dropIfExists('student_app_versions');
    }
}
