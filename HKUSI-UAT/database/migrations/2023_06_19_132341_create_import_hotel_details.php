<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportHotelDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_hotel_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('hotel_name')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('distance')->nullable();
            $table->string('price_range')->nullable();
            $table->string('website')->nullable();
            $table->string('download_form_url')->nullable();
            $table->string('remark')->nullable();
            $table->string('property_amenities_description')->nullable();
            $table->string('transportation_method_description')->nullable();
            $table->string('notes_description')->nullable();
            $table->string('map_url')->nullable();

            $table->string('room_type_name_1')->nullable();
            $table->string('room_type_description_1')->nullable();

            $table->string('room_type_name_2')->nullable();
            $table->string('room_type_description_2')->nullable();

            $table->string('room_type_name_3')->nullable();
            $table->string('room_type_description_3')->nullable();

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
        Schema::dropIfExists('import_hotel_details');
    }
}
