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
        $i = 0;
        $j = 0;
        $setHour = 0;
        $setMinute = 0;
        $setDay = 1;
        $setMonth = 1;
        //$today = date("Y-m-d H:i:s");
        $createDate = array();

        foreach(range(1,440) as $index){
            $date = new DateTime('2016-'.$setMonth.'-'.$setDay.' '.$setHour.':'.$setMinute.':18');
            $date->format('Y-m-d H:i:s');
                array_push($createDate,$date);

            $setHour = $setHour + 1;
            $setMinute = $setMinute + 1;
            $setDay = $setDay + 1;

            if ($index % 40 == 0) {
                $setMonth = $setMonth + 1;
            }

            if ($setHour > 23) {
                $setHour = 0;
            }

            if ($setMinute > 59) {
                $setMinute = 0;
            }

            if ($setDay > 27) {
                $setDay = 1;
            }

            if ($setMonth > 11) {
                $setMonth = 1;
            }
        }




        foreach(range(1,440) as $index){
        $i = $i + 1;
            DB::table('sensor_readings')->insert([
                //'sensor_details_id' => $faker->randomElement($sensors),
                'sensor_details_id' => $i,
                'sensor_reading' => $faker->numberBetween($min = 5, $max = 150),
                //'time_created' => $faker->dateTimeBetween($startDate = '-6 month', $endDate = 'now')
                'time_created' => $createDate[$index-1]
            ]);
            //$j = $j + 1;

            //if ($j == 12) {
            //    $j = 0;
            //}

            if ($i == 40) {
                $i = 0;
            }

        }







    }
}
