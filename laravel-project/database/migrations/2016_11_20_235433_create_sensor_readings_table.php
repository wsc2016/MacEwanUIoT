<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->increments('sensor_readings_id');
            $table->integer('sensor_details_id')->unsigned();
            $table->foreign('sensor_details_id')->references('sensor_details_id')->on('sensor_details');
            $table->string('sensor_reading');
            $table->timestamp('time_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sensor_readings');
    }
}
