<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    //
    protected $table = 'sensor_details';
    protected $primaryKey = 'sensor_details_id';

    public $timestamps = false;

    public function readings()
    {
        return $this->hasMany('App\Readings', 'sensor_details_id', 'sensor_details_id');
    }

    public function location()
    {
        return $this->belongsTo('App\Location', 'sensor_location_id', 'sensor_location_id');
    }

}
