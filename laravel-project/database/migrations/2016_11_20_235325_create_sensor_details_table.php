<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_details', function (Blueprint $table) {
            $table->increments('sensor_details_id');
            $table->integer('sensor_location_id')->unsigned();
            $table->foreign('sensor_location_id')->references('sensor_location_id')->on('sensor_location');
            $table->string('sensor_name');
            $table->string('sensor_brand');
            $table->string('sensor_type');
            $table->string('sensor_model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sensor_details');
    }
}
