<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class ReadingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create();
        // following line retrieve all the user_ids from DB
        //$sensors = Sensor::lists('sensor_details_id')->All();
        $sensors = DB::table('sensor_details')->lists('sensor_details_id');
        //$ids= Model::get()->lists('id');
        //$sensors = Sensor::all()->lists('sensor_details_id');
        foreach(range(1,30) as $index){

            DB::table('sensor_readings')->insert([
                'sensor_details_id' => $faker->randomElement($sensors),
                'sensor_reading' => $faker->numberBetween($min = 15, $max = 130),
                'time_created' => $faker->dateTimeBetween($startDate = '-1 month', $endDate = 'now')
            ]);

        }







    }
}
