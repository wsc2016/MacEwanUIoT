<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $count = 5;
        foreach (range(1, 40) as $index) {

            DB::table('sensor_location')->insert([
                'garbage_bin_location_name' => $faker->randomElement($array = array('Food', 'Nursing',
                        'Admin', 'CompSci', 'Phys', 'Psych', 'Chem', 'Bio', 'Science',
                        'Lib', 'Park', 'Gym', 'Arts')). '-' . $index,
                'building_number' => $count,
                'hallway_description' => $faker->randomElement($array = array('NW', 'SW', 'NE',
                        'SE', 'N', 'S', 'W', 'E')).' '
                    .$faker->randomElement($array = array('Entrance', 'Exit', 'Corridor')),
                'room_number' => $faker->numberBetween($min = 100, $max = 399)
            ]);
            $count++;

            if ($count > 9) {
                $count = 5;
            }
        }










        /*
        //
        DB::table('sensor_location')->insert([
            'garbage_bin_location_name' => 'FoodCourt-1',
            'building_number' => '5-2',
            'hallway_description' => 'East Entrance',
            'room_number' => '225C'
        ]);

        DB::table('sensor_location')->insert([
            'garbage_bin_location_name' => 'Nursing-2',
            'building_number' => '6-1',
            'hallway_description' => 'North West',
            'room_number' => '121S'
        ]);

        DB::table('sensor_location')->insert([
            'garbage_bin_location_name' => 'Science-3',
            'building_number' => '7-3',
            'hallway_description' => 'Main Entrance',
            'room_number' => '387W'
        ]);

        DB::table('sensor_location')->insert([
            'garbage_bin_location_name' => 'Administration-4',
            'building_number' => '8-2',
            'hallway_description' => 'Gym Back Entrance',
            'room_number' => '232G'
        ]);
        */
    }
}
