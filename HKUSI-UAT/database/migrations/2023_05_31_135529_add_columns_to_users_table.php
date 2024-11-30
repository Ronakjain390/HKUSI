<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->nullable()->after('email');
            $table->string('gender')->nullable()->after('title');
            $table->string('surname')->nullable()->after('gender');
            $table->string('given_name')->nullable()->after('surname');
            $table->string('mobile_tel_no')->nullable()->after('given_name');
            $table->string('department')->nullable()->after('mobile_tel_no');
            $table->boolean('admin_app_permission')->default(false)->after('department');
            $table->boolean('admin_panel_permission')->default(false)->after('admin_app_permission');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'gender',
                'surname',
                'given_name',
                'mobile_tel_no',
                'department',
                'admin_app_permission',
                'admin_panel_permission',
            ]);
        });
    }
}
