<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\KelasMp;
use App\Models\KelasMpGrup;
use App\Models\PresensiMp;
use App\Models\Siswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class RekapAbsensiKelasDaringController extends BaseController
{
    public function viewRekapAbsensiKelasDaring(Request $request){

        $input = (object) $request->input();
        $auth_data = auth_data();

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $data_kelas = KelasMp::where([
            'id_kelas'    => $wali_kelas->id_kelas,
            'id_semester' => $semester_aktif->id_semester
        ])->whereNotNull('id_kelas_mp_grup')->get();

        return view('guru.wali-kelas.rekap-absensi-kelas-daring.view-rekap-absensi-kelas-daring',compact('semester_aktif','data_kelas','wali_kelas'));

    }

    public function actionViewRekapAbsensiKelasDaring(Request $request){

        $input = (object) $request->input();
        $auth_data = auth_data();

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
                'path' => 'wali-kelas/rekap-absensi-kelas-daring/view/'.$input->id_kelas_mp_grup
            ];
        }

    }

    public function viewDetailRekapAbsensiKelasDaring(Request $request,$id_kelas_mp_grup){

        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $kelas_mp_grup = KelasMpGrup::find($id_kelas_mp_grup);

        $kelas_mp = KelasMp::where('id_kelas_mp_grup',$kelas_mp_grup->id_kelas_mp_grup)->first();

        $data_presensi = PresensiMp::where('id_kelas_mp',$kelas_mp->id_kelas_mp)->where('is_daring',1)->get();

        $siswa = Siswa::select('siswa.id_siswa', 'siswa.id_pengguna', 'kelas_mp.id_kelas_mp', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'siswa.thn_masuk_siswa', 'pengguna.nm_pengguna', 'status_pengguna.aktif_status_pengguna', 'status_pengguna.nm_status_pengguna', 'kelas.nm_kelas')
                        ->join('pengguna', function ($join) {
                            $join->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                            ->whereNull('pengguna.deleted_at');
                        })
                        ->join('status_pengguna', function ($join) {
                            $join->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                            ->whereNull('status_pengguna.deleted_at');
                        })
                        ->join('pengambilan_mp', function ($join) {
                            $join->on('pengambilan_mp.id_siswa', '=', 'siswa.id_siswa')
                            ->whereNull('pengambilan_mp.deleted_at');
                        })
                        ->join('kelas_mp', function ($join) {
                            $join->on('kelas_mp.id_kelas_mp', '=', 'pengambilan_mp.id_kelas_mp')
                            ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('kelas', function ($join) {
                            $join->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                            ->whereNull('kelas.deleted_at');
                        })
                        ->join('jadwal_kelas_mp', function ($join) {
                            $join->on('jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                            ->whereNull('jadwal_kelas_mp.deleted_at');
                        })
                        ->where('kelas_mp.id_kelas_mp', '=', $kelas_mp->id_kelas_mp);

        $data_siswa = $siswa->where('pengambilan_mp.status_apv_pengambilan_mp', '=', 1)
                        ->orderBy('siswa.nis_siswa', 'asc')
                        ->orderBy('kelas.nm_kelas', 'asc')
                        ->orderBy('kelas.tingkat', 'asc')
                        ->orderBy('pengguna.nm_pengguna', 'asc')
                        ->get();

        return view('guru/wali-kelas/rekap-absensi-kelas-daring/view-rekap-absensi-kelas-daring-detail', compact('auth_data','semester_aktif','kelas_mp_grup','id_kelas_mp_grup','data_presensi','data_siswa'));

    }

}