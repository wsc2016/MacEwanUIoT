<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Readings extends Model
{
    //
    protected $table = 'sensor_readings';
    protected $primaryKey = 'sensor_readings_id';

    public $timestamps = false;

    public function sensors()
    {
        return $this->belongsTo('App\Sensor', 'sensor_details_id', 'sensor_details_id');
    }
}
