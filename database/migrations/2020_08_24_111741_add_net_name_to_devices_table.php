<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetNameToDevicesTable extends Migration
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
                $table->string('net_name', 60)->after('IP')
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
                $table->dropColumn('net_name');
            });
        });
    }
}
