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
        $cacheType = Cache::find($cache_id);
        if ($cacheType->map_type == 'direct') {
            $this->addDirect($address);
        } elseif ($cacheType->map_type == 'SA') {
            $this->addSA($address);
        } else {
            $this->addFA($address);
//            return $cacheType;

        }


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
        $cache = Cache::find(1);
        $cache_access_time = $cache['cache_access_time'];
        $cache_miss_time = $cache['cache_miss_time'];
        $miss = Status::all()->where('status', '=', 'm');
        $addresses = Status::all();
        $missRating = count($miss) / count($addresses);
        $time = $cache_access_time + ($cache_miss_time * $missRating);
        $calculate = 1;
        $step = 0;
        $caches = Cache::all();

        return view('layout', compact('step', 'time', 'calculate', 'caches'));


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
        $calculate = 0;

        $caches = Cache::all();

        return view('layout', compact('step', 'index', 'tag', 'bo', 'caches', 'status', 'calculate'));
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
        $address['bo'] = substr($binary, $index_size + $tag_size, $bo_size);
        return $address;
    }


    public function getSizes($cache_id)
    {
        $cache = Cache::find($cache_id);
        return $cache;
    }

    public function addDirect($address)
    {
        $addressCheck = DB::table('addresses')->where('cache_id', '=', $address['cache_id'])->where('index', '=', $address['index'])->get();
        if (count($addressCheck) == 0) {
            $address['status'] = 'm';
            Address::create($address);
            Status::create(['status' => 'm']);
        } else {
            foreach ($addressCheck as $address1) {
                if ($address1->tag == $address['tag']) {
                    $address['status'] = 'h';

                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'h']);
                } else {
                    $address['status'] = 'm';
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'm']);
                }
            }
        }
    }

    public function addSA($address)
    {
        global $check;
        $addressCheck = DB::table('addresses')->where('cache_id', '=', $address['cache_id'])->where('index', '=', $address['index'])->get();
        $way = Cache::find($address['cache_id']);
        $addressNumber = count($addressCheck);


        if ($addressNumber < $way->way) {

            $check = $this->isAddressInSA($addressCheck, $address);
            if ($check != 1) {
                $address['status'] = 'm';
                $address['counter'] = 0;
                Address::create($address);
                Status::create(['status' => 'm']);
            }

        } elseif ($addressNumber == $way->way) {
            $check = $this->isAddressInSA($addressCheck, $address);
            if ($check != 1) {
                $this->deleteAddress($addressCheck);
                $address['status'] = 'm';
                $address['counter'] = 0;
                Address::create($address);
                Status::create(['status' => 'm']);
            }


        } elseif ($addressNumber == 0) {
            $address['status'] = 'm';
            Address::create($address);
            Status::create(['status' => 'm']);
        }


    }

    public function addFA($address)
    {


        global $freeSpace;
        global $check;
        $addressCheck = Address::all();
        $cache = Cache::find($address['cache_id']);
        if ($cache->type == 'kb') {
            $freeSpace = log($cache->size * 1024, 2) - $cache->bo_size;
        } else {
            $freeSpace = log($cache->size * 1024 * 1024, 2) - $cache->bo_size;
        }
        $addressNumber = count($addressCheck);

        if ($addressNumber < $freeSpace) {
            $check = $this->isAddressIn($addressCheck, $address);
            if ($check != 1) {
                $address['status'] = 'm';
                $address['counter'] = 0;
                Address::create($address);
                Status::create(['status' => 'm']);
            }
        } else {
            $check = $this->isAddressIn($addressCheck, $address);
            if ($check != 1) {
                $this->deleteAddress($addressCheck);
                $address['status'] = 'm';
                $address['counter'] = 0;
                Address::create($address);
                Status::create(['status' => 'm']);

            }

        }
    }


    public function deleteAddress($addressCheck)
    {
        global $min;
        global $delete;
        $ccount = 0;
        foreach ($addressCheck as $address) {
            $ccount++;
            if ($ccount == 1) {
                $min = $address->counter;
                $delete = $address;
            }
            if ($address->counter < $min) {
                $min = $address->counter;
                $delete = $address;

            }
        }
        Address::find($delete->id)->delete();

    }

    public function isAddressIn($addressCheck, $address)
    {
        global $check;

        foreach ($addressCheck as $address1) {
            if ($address1->bo == $address['bo']) {
                $check = 1;
                if ($address1->tag == $address['tag']) {
                    $address['status'] = 'h';
                    $address['counter'] = $address1->counter + 1;
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'h']);
                } else {
                    $address['status'] = 'm';
                    $address['counter'] = 0;
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'm']);
                }
            }
        }
        return $check;
    }
    public function isAddressInSA($addressCheck, $address)
    {
        global $check;

        foreach ($addressCheck as $address1) {
                if ($address1->tag == $address['tag']) {
                    $check = 1;
                    $address['status'] = 'h';
                    $address['counter'] = $address1->counter + 1;
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'h']);
                } else {
                    $check = 1;
                    $address['status'] = 'm';
                    $address['counter'] = 0;
                    Address::find($address1->id)->update($address);
                    Status::create(['status' => 'm']);
                }

        }
        return $check;
    }

}
