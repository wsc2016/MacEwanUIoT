<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sensor;
use App\Http\Requests;

class PagesController extends Controller
{
    //
    public function index()
    {
        $sensors = Sensor::all();

        return view('pages.home', compact('sensors'));
    }

    public function show()
    {
        return view('pages.home');
    }
}
