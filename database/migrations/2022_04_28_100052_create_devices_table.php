<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modbus_id')->constrained('modbuses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('digital_input_id')->constrained('digital_inputs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('lat');
            $table->string('long');
            $table->string('satuan');
            $table->string('type');
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
        Schema::dropIfExists('devices');
    }
}
