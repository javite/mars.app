<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            Schema::table('devices', function (Blueprint $table) {
                $table->string('IP', 50)->after('serial_number')
                ->nullable()
                ->default(null);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            Schema::table('devices', function (Blueprint $table) {
                $table->dropColumn('IP');
            });
        });
    }
}
