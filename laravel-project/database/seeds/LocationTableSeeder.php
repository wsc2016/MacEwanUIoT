<?php

use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
