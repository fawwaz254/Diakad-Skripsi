<?php

namespace App\Http\Controllers\Guru\Biodata;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru as Guru;
use App\Models\Kota as Kota;
use App\Models\Agama as Agama;
use App\Models\Provinsi as Provinsi;
use App\Models\JenisPekerjaan as JenisPekerjaan;
use App\Models\JenisKepegawaian as JenisKepegawaian;
use App\Models\JenisPtk as JenisPtk;
use App\Models\JenisKeahlianLab as JenisKeahlianLab;
use App\Models\JenisSumberGaji as JenisSumberGaji;
use App\Models\JenisLembagaPengangkat as JenisLembagaPengangkat;

use App\Libraries\SumberDaya\LibDataSumberDaya;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class DataPribadiController extends BaseController{

    public function viewDataPribadi(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_status_aktif_guru = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $temp_guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $guru = LibGuru::fetchDataAllGuru($auth_data, $temp_guru->id_guru);

        $kota = Kota::where('kota.is_aktif','=',1)->get();
        $provinsi = Provinsi::where('provinsi.is_aktif','=',1)->get();
        $agama = Agama::get();
        $pegawai = JenisKepegawaian::get();
        $pekerjaan = JenisPekerjaan::get();
        $ptk = JenisPtk::get();
        $pengangkat = JenisLembagaPengangkat::get();
        $gaji = JenisSumberGaji::get();
        $lab = JenisKeahlianLab::get();

        return view('guru/biodata/data-pribadi/view-data-pribadi',compact('auth_data','data_status_aktif_guru','data_unit_kerja','guru','kota','provinsi','agama','pegawai','pekerjaan','ptk','pengangkat','gaji','lab'));
    
    }

}