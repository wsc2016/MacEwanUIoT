<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        // following line retrieve all the user_ids from DB
        //$sensors = Sensor::lists('sensor_details_id')->All();
        //$details = DB::table('sensor_details')->lists('sensor_details_id');
        //$location = DB::table('sensor_location')->lists('sensor_location_id');
        //$ids= Model::get()->lists('id');
        //$sensors = Sensor::all()->lists('sensor_details_id');
        foreach (range(1, 40) as $index) {

            DB::table('sensor_details')->insert([
                'sensor_name' => $faker->word . '-' . $index,
                'sensor_brand' => $faker->randomElement($array = array('Sharp', 'JVC', 'Panasonic', 'Canon', 'Hitachi', 'Mitsubishi', 'RCA')),
                'sensor_type' => $faker->randomElement($array = array('Analog Distance', 'Digital Distance', 'Audio', 'Infrared Proximity', 'Infrared Motion')),
                'sensor_model' => $faker->isbn10,
                'sensor_location_id' => $index
            ]);

        }


        /*

        //
        DB::table('sensor_details')->insert([
            'sensor_name' => 'PGMX-01',
            'sensor_brand' => 'Sharp',
            'sensor_type' => 'Analog Distance',
            'sensor_model' => 'GPZ9237FEF738230',
            'sensor_location_id' => '1'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'DFGV-02',
            'sensor_brand' => 'Canon',
            'sensor_type' => 'Infrared Proximity',
            'sensor_model' => 'CPZ9237FXF738232',
            'sensor_location_id' => '2'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'AGNB-07',
            'sensor_brand' => 'RCA',
            'sensor_type' => 'Analog Distance',
            'sensor_model' => 'NZ9237FWF73823077',
            'sensor_location_id' => '3'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'JGNB-08',
            'sensor_brand' => 'JVC',
            'sensor_type' => 'Infrared Proximity',
            'sensor_model' => 'MZ9237FWF73823074',
            'sensor_location_id' => '4'
        ]);
        */

    }
}
