<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigitalInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('digital_inputs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('digital_input');
            $table->integer('yes')->default(1);
            $table->integer('no')->default(0);
            $table->integer('is_used')->default(1);
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
        Schema::dropIfExists('digital_inputs');
    }
}
