<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExitNoteStoreRequest extends FormRequest
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
            'date' => 'required|date',
            'type_exit' => 'required',
            'comment' => 'nullable',
            'document_number' => 'nullable',
            'materials' => 'required',
        ];
    }
}
