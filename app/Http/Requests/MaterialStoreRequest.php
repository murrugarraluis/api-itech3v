<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialStoreRequest extends FormRequest
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
            'name'=>'required|unique:materials,name',
            'category' => 'required',
            'mark' => 'required',
            'measure_unit' => 'required',
            'image'=>'required|image|dimensions:min_width=200,min_height=200'
        ];
    }
}
