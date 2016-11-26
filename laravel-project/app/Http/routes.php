<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


//Route::get('/', function () {
//    return 'hello iot_waste_management';
    //return view('welcome');
//});

//Route::get('about', function () {
//    return view('pages.about');
    //return view('welcome');
//});

Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/trend', 'PagesController@trend');
Route::get('/{sensor}', 'PagesController@show');
