<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Readings;
use App\Sensor;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class PagesController extends Controller
{
    //
    public function index()
    {

        $locations = Location::with('readings','sensors')->orderBy('building_number', 'ASC')->get();
        $readings = Readings::with('sensors')->get();
        $sensors = Sensor::with('readings','location')->get();

        return view('pages.home', compact('locations','readings','sensors'));
    }

    public function about()
    {
        $locations = Location::with('readings','sensors')->get();
        $readings = Readings::with('sensors')->get();
        $sensors = Sensor::with('readings','location')->get();

        return view('pages.about', compact('locations','readings','sensors'));

    }

    public function trend()
    {
        $locations = Location::with('readings','sensors')->get();
        $readings = Readings::with('sensors')->get();
        $sensors = Sensor::with('readings','location')->get();

        return view('pages.trend', compact('locations','readings','sensors'));

    }

    public function show($id)
    {
        $locations = Location::with('readings','sensors')->get();
        $readings = Readings::with('sensors')->get();
        $sensor = Sensor::find($id);

        return view('pages.show', compact('locations','readings','sensor'));
    }


}
