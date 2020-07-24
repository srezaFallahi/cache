<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Http\Requests\cacheRequest;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(cacheRequest $request)
    {
        $request1 = $request->all();
        $mapType = $request['map_type'];
        $cache['map_type'] = $mapType;
        $cache['type'] = $request['cache_type'];
        if ($request['memory_type'] == 'Kb') {
            $cache['address_size'] = log($request['memory_size'] * 1024, 2);
        } else {
            $cache['address_size'] = log($request['memory_size'] * 1024 * 1024, 2);
        }
        $cache['cache_access_time'] = $request['cache_access_time'];
        $cache['cache_miss_time'] = $request['cache_miss_time'];
        $cache['size'] = $request['cache_size'];
        $cache['way'] = $request['way'];
        /////////////////////////////////////////////////////////////////////////////
        if ($mapType == "direct") {
            $cache = $this->direct($request1, $cache);
        } elseif ($mapType == "SA") {
            $cache = $this->SA($request1, $cache,1);
        } else
            $cache = $this->SA($request1, $cache,2);


//        return $cache;
        Cache::create($cache);
        $caches = Cache::all();
        return redirect('/');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function direct($request, $cache)
    {
        $cache['bo_size'] = log($request['block_size'], 2);
        $cache['index_size'] = log(($request['cache_size'] / $request['block_size'] * 1024), 2);
        $cache['tag_size'] = $cache['address_size'] - ($cache["bo_size"] + $cache['index_size']);
        return $cache;
    }

    public function SA($request, $cache,$type)
    {
        if ($request['cache_type']=='mb'){
            $cache_size=$request['cache_size']*1024*1024;
        }
        else
            $cache_size=$request['cache_size']*1024;

        if ($type == 1) {
            $cache['bo_size'] = log($request['block_size'], 2);
            $cache['index_size'] = log($cache_size, 2) - ($cache['bo_size'] + log($request['way'], 2));
            $cache['tag_size'] = $cache['address_size'] - ($cache["bo_size"] + $cache['index_size']);
        } else {
            $cache['bo_size'] = log($request['block_size'], 2);
            $cache['tag_size'] = $cache['address_size'] - ($cache["bo_size"] );
        }
        return $cache;
    }
}
