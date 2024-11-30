<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportPrivateEventOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_private_event_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_data_info_id')->nullable();
            $table->foreign('import_data_info_id')->references('id')->on('import_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->string('booking_id')->nullable();
            $table->string('event_id')->nullable();
            $table->string('application_id')->nullable();
            $table->string('event_name')->nullable();
            $table->bigInteger('event_date')->nullable();
            $table->bigInteger('start_time')->nullable();
            $table->bigInteger('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('assembly_location')->nullable();
            $table->bigInteger('assembly_time')->nullable();
            $table->bigInteger('no_of_tickets')->nullable();
            $table->bigInteger('unit_price')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('check_in_date')->nullable();
            $table->bigInteger('check_in_time')->nullable();
            $table->string('check_in_operator')->nullable();
            $table->enum('status', ['Enrolled and Confirmed', 'Updated','Pending', 'Cancelled'])->nullable();
            $table->string('reason')->nullable();
            $table->boolean('import_status')->default(1);

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
