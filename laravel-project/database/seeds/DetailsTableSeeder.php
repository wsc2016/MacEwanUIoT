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
        foreach (range(1, 40) as $index) {

            DB::table('sensor_details')->insert([
                'sensor_name' => $faker->word . '-' . $index,
                'sensor_brand' => $faker->randomElement($array = array('Sharp', 'JVC', 'Panasonic', 'Canon', 'Hitachi', 'Mitsubishi', 'RCA')),
                'sensor_type' => $faker->randomElement($array = array('Analog Distance', 'Digital Distance', 'Audio', 'Infrared Proximity', 'Infrared Motion')),
                'sensor_model' => $faker->isbn10,
                'sensor_location_id' => $index
            ]);

        }
    }
}
