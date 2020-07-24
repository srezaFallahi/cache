<?php

use App\Cache;
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

    $caches = \App\Cache::all();
//    if (count($caches)==0){
//        $freshDataBase=0;
//    }
//    else {
        $freshDataBase = 0;
        $s = 0;
//    }
    $step=0;
    $calculate=0;
    return view('layout', compact("caches","step","calculate","freshDataBase","s"));
});
Route::get('/test',function (){
    \App\Cache::truncate();
    \App\Status::truncate();
    \App\Address::truncate();
    return redirect('/');

});

//Route::resource('/memory',"MemoryController");
Route::resource('/cache', "CacheController");
Route::resource('/address', "AddressController");
Route::post('/address/step', "AddressController@step")->name('step');
