<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotelSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name')->nullable();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('distance')->nullable();
            $table->string('price_range')->nullable();
            $table->string('website')->nullable();
            $table->string('remark')->nullable();
            $table->string('property_amenities_description')->nullable();
            $table->string('transportation_method_description')->nullable();
            $table->string('notes_description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('map_photo')->nullable();
            $table->string('map_url')->nullable();

            $table->string('room_type_name_1')->nullable();
            $table->string('room_type_description_1')->nullable();
            $table->string('room_type_thumbnail_1')->nullable();

            $table->string('room_type_name_2')->nullable();
            $table->string('room_type_description_2')->nullable();
            $table->string('room_type_thumbnail_2')->nullable();

            $table->string('room_type_name_3')->nullable();
            $table->string('room_type_description_3')->nullable();
            $table->string('room_type_thumbnail_3')->nullable();
            
            $table->enum('status', ['Enabled', 'Disabled'])->nullable();
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
        Schema::dropIfExists('hotel_settings');
    }
}
