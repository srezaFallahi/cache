<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cacheRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'memory_size'=>'required',
            'cache_size'=>'required',
            'memory_type'=>'required',
            'cache_type'=>'required',
            'block_size'=>'required',
            'cache_access_time'=>'required',
            'cache_miss_time'=>'required',
        ];
    }
}
