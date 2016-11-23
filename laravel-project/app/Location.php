<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = 'sensor_location';
    protected $primaryKey = 'sensor_location_id';

    public $timestamps = false;

    public function readings()
    {
        return $this->hasManyThrough('App\Readings', 'App\Sensor', 'sensor_location_id', 'sensor_details_id', 'sensor_location_id');
    }

    public function sensors()
    {
        return $this->hasMany('App\Sensor', 'sensor_location_id', 'sensor_location_id');
    }
}
