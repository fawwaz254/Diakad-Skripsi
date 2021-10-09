<?php

namespace App\Http\Controllers\Guru\KelasDaring;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\KelasMp;
use App\Models\KelasMpGrup;
use App\Models\PresensiMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class LaporanAbsenController extends BaseController
{
    public function viewLaporanAbsen(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $id_guru = $guru->id_guru;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $kelas_mp_grup = KelasMpGrup::where('id_guru',$id_guru)->where('id_semester',$semester_aktif->id_semester)->get();

        return view('guru/kelas-daring/laporan-absen/view-laporan-absen', compact('auth_data','kelas_mp_grup'));
    }

    public function postLaporanAbsen(Request $request){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp_grup' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'kelas-daring/laporan-absen/view/'.$input->id_kelas_mp_grup
            ];
        }

    }

    public function viewLaporanAbsenDetail(Request $request,$id_kelas_mp_grup){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $kelas_mp_grup = KelasMpGrup::find($id_kelas_mp_grup);

        $kelas_mp = KelasMp::where('id_kelas_mp_grup',$kelas_mp_grup->id_kelas_mp_grup)->first();

        $data_presensi = PresensiMp::where('id_kelas_mp',$kelas_mp->id_kelas_mp)->where('is_daring',1)->get();

        return view('guru/kelas-daring/laporan-absen/view-laporan-absen-detail', compact('auth_data','semester_aktif','kelas_mp_grup','id_kelas_mp_grup','data_presensi'));

    }

}