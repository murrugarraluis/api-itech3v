<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationStoreRequest extends FormRequest
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
            'supplier' => 'required',
            'date_agreed' => 'required|date',
            'way_to_pay' => 'required',
            'type_quotation' => 'required',
            'document_number' => 'nullable',
            'materials' => 'required',
            'status' => 'nullable',
        ];
    }
}
