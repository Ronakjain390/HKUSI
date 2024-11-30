<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveServiceIdAndAddServiceTypeFromExportPaymentInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('export_payment_infos', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropColumn('payment_type');
            $table->string('order_no','255')->nullable()->after('payment_id');
            $table->string('service_type','50')->nullable()->after('order_no');
            $table->string('pay_type','100')->nullable()->after('payment_method');
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
            $table->dropColumn('service_id');
            $table->dropColumn('payment_type');
        });
    }
}
