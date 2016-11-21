<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    public function readings()
    {
        return $this->hasManyThrough('App\Readings', 'App\Sensor', 'sensor_location_id', 'sensor_details_id');
    }
}
