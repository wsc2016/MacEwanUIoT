<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_location', function (Blueprint $table) {
            $table->increments('sensor_location_id');
            $table->string('garbage_bin_location_name');
            $table->string('building_number');
            $table->string('hallway_description');
            $table->string('room_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sensor_location');
    }
}
