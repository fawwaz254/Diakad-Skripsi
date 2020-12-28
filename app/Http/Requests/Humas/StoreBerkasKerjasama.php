<?php

namespace App\Http\Requests\Humas;

use Illuminate\Foundation\Http\FormRequest;

class StoreBerkasKerjasama extends FormRequest
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
            'file' => 'file|required|max:2048|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,bmp,png'
        ];
    }
}
