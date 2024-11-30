<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportPaymentInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_payment_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_data_info_id')->nullable();
            $table->foreign('export_data_info_id')->references('id')->on('export_data_infos')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('transaction_id')->nullable();
            $table->string('application_id')->nullable();
            $table->string('payment_id','50')->nullable();            
            $table->bigInteger('service_id')->nullable();            
            $table->string('reference_no','255')->nullable();
            $table->string('card_no','255')->nullable();
            $table->string('approval_code','255')->nullable();
            $table->string('merchant_id','255')->nullable();
            $table->bigInteger('expiry_time')->nullable();
            $table->bigInteger('pay_time')->nullable();
            $table->double('amount', 10, 2);
            $table->string('payment_method')->nullable();
            $table->enum('payment_type', ['Hall Booking', 'Event Booking','Dining Token Order'])->nullable();
            $table->json('pay_result')->nullable();
            $table->boolean('status')->default(1)->comment('0:No, 1:Yes');
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
        Schema::table('export_payment_infos', function (Blueprint $table) {
            $table->dropForeign('export_payment_infos_export_data_info_id_foreign');
            $table->foreign('export_data_info_id')->references('id')->on('export_data_infos');
        });
        Schema::dropIfExists('export_payment_infos');
    }
}
