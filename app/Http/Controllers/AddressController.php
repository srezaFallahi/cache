<?php

namespace App\Http\Controllers;

use App\Address;
use App\Cache;
use App\Http\Requests\addressRequest;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
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
    public function store(addressRequest $request)
    {
        $hex_address = $request['address'];
        $binary_address = $this->convert_to_binary($hex_address);
        $cache_id = $request['cache_id'];
        $cache = $this->getSizes($cache_id);
        $index_size = $cache->index_size;
        $tag_size = $cache->tag_size;
        $bo_size = $cache->bo_size;
        $address_size = $cache->address_size;
        $address = $this->separatorBinary($binary_address, $tag_size, $index_size, $bo_size, $address_size);
        $address['cache_id'] = $cache_id;

        $addressCheck = DB::table('addresses')->where('cache_id', '=', $address['cache_id'])->where('index', '=', $address['index'])->get();
//      return count($addressCheck);

        if (count($addressCheck) == 0) {
            $address['status'] = 'm';
            Address::create($address);
            Status::create(['status'=>'m']);
        } else {
            foreach ($addressCheck as $address1) {
                if ($address1->tag==$address['tag']) {
                    $address['status'] = 'h';

                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'h']);
                }
                else{
                    $address['status'] = 'm';
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'm']);
                }
            }
        }

        return redirect('/');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
       $cache= Cache::find(1);
      $cache_access_time= $cache['cache_access_time'];
      $cache_miss_time= $cache['cache_miss_time'];
      $miss=Status::all()->where('status','=','m');
      $addresses=Status::all();
      $missRating=count($miss)/count($addresses);
      $time=$cache_access_time+($cache_miss_time*$missRating);
      $calculate=1;
      $step=0;
      $caches=Cache::all();

      return view('layout', compact('step','time','calculate','caches'));



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

    public function step(Request $request)
    {
        $hex_address = $request['address'];
        $binary_address = $this->convert_to_binary($hex_address);
        $cache = $this->getSizes(1);
        $index_size = $cache->index_size;
        $tag_size = $cache->tag_size;
        $bo_size = $cache->bo_size;
        $address_size = $cache->address_size;
        $address = $this->separatorBinary($binary_address, $tag_size, $index_size, $bo_size, $address_size);
        $addressCheck = DB::table('addresses')->where('cache_id', '=', 1)->where('index', '=', $address['index'])->where('tag', '=', $address['tag'])->get();
        foreach ($addressCheck as $address1) {
            $address['status'] = $address1->status;
        }
        if (count($addressCheck) > 0) {
            $step = 1;
            $index = $address['index'];
            $tag = $address['tag'];
            $bo = $address['bo'];
            $status = $address['status'];
        } else {
            $step = 1;
            $index = 'not found';
            $tag = 'notfound';
            $bo = 'notfound';
            $status = 'notfound';
        }
        $calculate=0;

        $caches = Cache::all();
        return view('layout', compact('step', 'index', 'tag', 'bo', 'caches', 'status','calculate'));
    }


    public function convert_to_binary($hex)
    {
        $x = hexdec($hex);
        return decbin($x);
    }

    public function separatorBinary($binary, $tag_size, $index_size, $bo_size, $address_size)
    {
        global $tmp;

        if ($address_size > strlen($binary)) {

            for ($i = 0; $i < $address_size - strlen($binary); $i++) {
                $tmp = '0' . $tmp;
            }
        }

        $binary = $tmp . $binary;

        $address['tag'] = substr($binary, 0, $tag_size);
        $address['index'] = substr($binary, $tag_size, $index_size);
        $address['bo'] = substr($binary, $index_size+$tag_size,$bo_size);
        return $address;
    }


    public function getSizes($cache_id)
    {
        $cache = Cache::find($cache_id);
        return $cache;

    }
}
