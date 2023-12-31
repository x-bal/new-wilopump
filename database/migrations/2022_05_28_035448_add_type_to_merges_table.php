<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToMergesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merges', function (Blueprint $table) {
            $table->string('type')->after('name');
            $table->integer('is_used')->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merges', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('is_used');
        });
    }
}
