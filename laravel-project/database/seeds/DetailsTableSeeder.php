<?php

use Illuminate\Database\Seeder;

class DetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
            'sensor_name' => 'CVBX-03',
            'sensor_brand' => 'Hitachi',
            'sensor_type' => 'Audio',
            'sensor_model' => 'HPZ9237FDF738233',
            'sensor_location_id' => '1'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'FGNB-04',
            'sensor_brand' => 'Panasonic',
            'sensor_type' => 'Digital Distance',
            'sensor_model' => 'PZ9237FWF73823076',
            'sensor_location_id' => '2'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'DGNB-05',
            'sensor_brand' => 'BenQ',
            'sensor_type' => 'Proximity Distance',
            'sensor_model' => 'XZ9237FWF73823073',
            'sensor_location_id' => '1'
        ]);

        DB::table('sensor_details')->insert([
            'sensor_name' => 'HGNB-06',
            'sensor_brand' => 'Mitsubishi',
            'sensor_type' => 'Audio',
            'sensor_model' => 'BZ9237FWF73823071',
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

    }
}
