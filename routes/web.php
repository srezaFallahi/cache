<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    $x = hexdec('5900f');
//    return decbin($x);

    $caches = \App\Cache::all();
    $step=0;
    $calculate=0;
    return view('layout', compact("caches","step","calculate"));
});


//Route::resource('/memory',"MemoryController");
Route::resource('/cache', "CacheController");
Route::resource('/address', "AddressController");
Route::post('/address/step', "AddressController@step")->name('step');
