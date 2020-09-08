<?php

namespace App\Http\Controllers\Guru\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp as PresensiMp;
use App\Models\PresensiMpSiswa as PresensiMpSiswa;
use App\Models\UjianMpPresensi as UjianMpPresensi;
use App\Models\PengampuMapel;
use App\Models\Matapelajaran;
use App\Models\Guru;
use App\Models\KelasMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class AbsensiTanpaJadwalController extends BaseController
{
    public function viewAbsensiTanpaJadwal(Request $request)
    {
        # code...
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tanggal        = Carbon::now(env('APP_TIMEZONE', ''));
        $tanggal_id     = $tanggal->format('d F Y');
        $guru           = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $pengampu_mapel = PengampuMapel::join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'pengampu_mapel.id_mata_pelajaran')
                            ->select('pengampu_mapel.id_pengampu_mapel', 'pengampu_mapel.id_guru', 'pengampu_mapel.id_mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran')
                            ->where('pengampu_mapel.id_guru', '=', $guru->id_guru)->get();

        $kelas          = LibKelas::fetchDataKelas($auth_data, $id = null);

        return view('guru/presensi/absensi-tanpa-jadwal/view-absensi-tanpa-jadwal', compact('auth_data', 'semester_aktif', 'tanggal', 'tanggal_id', 'pengampu_mapel', 'kelas'));
    }

    public function actionViewKBMAbsensiTanpaJadwal(Request $request)
    {
        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas'              => 'required',
            'id_mata_pelajaran'     => 'required',
        ]);

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        if($input->opsi == !null) {
            $opsi = $input->opsi;
        } else {
            $opsi = null;
        }

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'presensi/absensi-tanpa-jadwal/view-kbm/'.$guru->id_guru.'/'.$input->id_mata_pelajaran.'/'.$input->id_kelas.'/'.$opsi
            ];
        }
    }

    public function viewKBMAbsensiTanpaJadwal(Request $request, $id_guru, $id_mata_pelajaran, $id_kelas, $opsi = null)
    {
        # code...
        $input              = (object) $request->input();
        $auth_data          = $input->auth_data;
        $semester_aktif     = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tanggal            = Carbon::now(env('APP_TIMEZONE', ''));

        return view('guru/presensi/absensi-tanpa-jadwal/view-kbm-absensi-tanpa-jadwal', compact('auth_data', 'id_guru', 'id_mata_pelajaran', 'id_kelas', 'opsi'));
    }

    public function datatablesKBMAbsensiTanpaJadwal(Request $request, $id_guru, $id_mata_pelajaran, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'only_aktif');
        
        // $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $pertemuan_ke)->first();
        
        // if ($presensi_mp_aktif) {
        //     $data_presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp_aktif->id_presensi_mp)->get();
        // } else {
        //     $data_presensi_mp_siswa = null;
        // }
        return Datatables::of($list_data)
            ->editColumn('nis_siswa', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa,
                    'nis_siswa' => $item->nis_siswa,
                    'status_pengguna' => array(
                        'status' => $item->aktif_status_pengguna,
                        'nm_status' => $item->nm_status_pengguna
                    )
                );
                return $data;
            })
            // ->addColumn('alasan', function ($item) {
            //     // $kehadiran = null;
            //     // if ($data_presensi_mp_siswa && $presensi_mp_siswa = $data_presensi_mp_siswa->firstWhere('id_siswa', $item->id_siswa)) {
            //     //     $kehadiran = $presensi_mp_siswa->kehadiran;
            //     // }
            //     $options = array(
            //         array('id' => 1, 'text' => 'Hadir'),
            //         array('id' => 2, 'text' => 'Sakit'),
            //         array('id' => 3, 'text' => 'Izin'),
            //         array('id' => 4, 'text' => 'Alpa'),
            //     );
            //     $data = array(
            //         'options' => $options,
            //         'kehadiran' => $kehadiran,
            //         'status_pengguna' => array(
            //             'status' => $item->aktif_status_pengguna,
            //             'nm_status' => $item->nm_status_pengguna
            //         )
            //     );
            //     return $data;
            // })
            ->make(true);
    }
    

    // add {
        public function indexManage()
        {

        }
}
