<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateEventProgrammes extends Migration
{
    /**
     * Run the migrations.
     * Migration created By Akash
     * @return void
     */
    public function up()
    {
        Schema::create('private_event_programmes', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->nullable();
            $table->integer('program_id')->nullable();
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
        Schema::dropIfExists('private_event_programmes');
    }
}
