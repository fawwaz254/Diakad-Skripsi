<?php

namespace App\Http\Requests\Humas;

use App\Models\Instansi;
use App\Models\JenisKerjasama;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKerjasama extends FormRequest
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
            "id_instansi"           => "sometimes | required | string | exists:instansi,id_instansi",
            "id_jenis_kerjasama"    => "sometimes | required | string | exists:jenis_kerjasama,id_jenis_kerjasama",
            "nm_kerjasama"          => "sometimes | required | string",
            "tanggal_kerjasama"     => "sometimes | required | date",
            "status"                => "sometimes | required | boolean",
        ];
    }
}
