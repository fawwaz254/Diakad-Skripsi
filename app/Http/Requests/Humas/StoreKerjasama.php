<?php

namespace App\Http\Requests\Humas;

use App\Models\Instansi;
use App\Models\JenisKerjasama;
use Illuminate\Foundation\Http\FormRequest;

class StoreKerjasama extends FormRequest
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
            "id_instansi"           => "required | string | exists:instansi,id_instansi",
            "id_jenis_kerjasama"    => "required | string | exists:jenis_kerjasama,id_jenis_kerjasama",
            "nm_kerjasama"          => "required | string",
            "tanggal_kerjasama"     => "required | date",
            "status"                => "required | boolean",
        ];
    }
}
