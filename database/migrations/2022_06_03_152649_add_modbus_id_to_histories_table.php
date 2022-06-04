<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModbusIdToHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->foreignId('modbus_id')->after('device_id')->default(0);
            $table->foreignId('digital_input_id')->after('modbus_id')->default(0);
            $table->string('time')->after('val');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('histories', function (Blueprint $table) {
            $table->dropIfExists('modbus_id');
            $table->dropIfExists('digital_input_id');
            $table->dropIfExists('time');
        });
    }
}
