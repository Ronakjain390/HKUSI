<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportPrivateEventOrderDetails extends Migration
{
    /**
     * Run the migrations.
     * Migration Created by Akash
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('import_private_event_order_details');

        Schema::create('import_private_event_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('booking_id')->nullable();
            $table->string('application_id')->nullable();
            $table->double('unit_price', 10, 2)->nullable();
            $table->string('no_of_seats')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->string('check_operator')->nullable();
            $table->enum('booking_status', ['Pending', 'Paid','Cancelled'])->nullable();
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
        Schema::dropIfExists('import_private_event_order_details');
    }
}
