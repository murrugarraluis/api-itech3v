<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierUpdateRequest extends FormRequest
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
            'type_document'=>'required',
            'number_document'=>'required|unique:suppliers,number_document,'.$this->route('supplier')->id,
            'name'=>'required',
            'lastname'=>'required',
        ];
    }
}
