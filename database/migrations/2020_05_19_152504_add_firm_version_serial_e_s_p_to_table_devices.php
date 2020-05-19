<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFirmVersionSerialESPToTableDevices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->string('firmware_version', 80)->after('version')
            ->nullable()
            ->default(null);
        });
        Schema::table('devices', function (Blueprint $table) {
            $table->string('serial_number', 80)->after('firmware_version')
            ->nullable()
            ->default(null);
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
            $table->dropColumn('firmware_version');
            $table->dropColumn('serial_number');
        });
    }
}
