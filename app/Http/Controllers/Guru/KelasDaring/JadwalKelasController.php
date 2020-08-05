<?php

namespace App\Http\Controllers\Guru\KelasDaring;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\JadwalKelasMp;
use App\Models\KelasMpGrup;
use App\Models\PresensiMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class JadwalKelasController extends BaseController
{
    public function viewJadwalKelasDaring(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        return view('guru/kelas-daring/view-jadwal-kelas', compact('auth_data', 'semester_aktif'));
    }

    public function viewAddJadwalKelasDaring(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        return view('guru/kelas-daring/view-add-jadwal-kelas', compact('auth_data', 'semester_aktif'));
    }

    public function datatablesJadwalKelasDaring(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = KelasMpGrup::with('kelas_mp', 'kelas_mp.kelas')->where('id_semester', $semester_aktif->id_semester)->where('id_guru', $guru->id_guru);

        return Datatables::of($list_data)
            ->addColumn('kelas', function ($item) {
                $html = '';
                foreach($item->kelas_mp as $kelas_mp){
                    $html .= $kelas_mp->kelas->nm_kelas.'<br>';
                }
                return $html;
            })
            ->addColumn('jadwal', function ($item) {
                return '';
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->rawColumns(['kelas'])
            ->make(true);
    }

    public function actionAddJadwalKelasDaring(Request $request) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kelas_mp_grup'              => 'required',
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $auth_data = $input->auth_data;

            $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
    
            $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

            $kelas_mp_grup                               = new KelasMpGrup;
            $kelas_mp_grup->id_kelas_mp_grup         = $id;
            $kelas_mp_grup->id_semester                     = $semester_aktif->id_semester;
            $kelas_mp_grup->id_guru                = $guru->id_guru;
            $kelas_mp_grup->nm_kelas_mp_grup                  = $input->nm_kelas_mp_grup;
            $kelas_mp_grup->created_by                   = $auth_data->pengguna->id_pengguna;
            $kelas_mp_grup->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'kelas-daring/jadwal-kelas',
                'message' => 'Save Jadwal Kelas'
            ];
        }
    }
}
