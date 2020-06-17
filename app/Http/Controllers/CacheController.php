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
        $cache['type'] = $request['cache_type'];
        if ($request['memory_type'] == 'Kb') {
            $cache['address_size'] = log($request['memory_size'] * 1024, 2);
        } else {
            $cache['address_size'] = log($request['memory_size'] * 1024 * 1024, 2);
        }

        $cache['bo_size'] = log($request['block_size'], 2);
        $cache['index_size'] = log(($request['cache_size'] / $request['block_size'] * 1024), 2);
        $cache['tag_size'] = $cache['address_size'] - ($cache["bo_size"] + $cache['index_size']);
        $cache['cache_access_time'] = $request['cache_access_time'];
        $cache['cache_miss_time'] = $request['cache_miss_time'];
        $cache['size'] = $request['cache_size'];
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
}
