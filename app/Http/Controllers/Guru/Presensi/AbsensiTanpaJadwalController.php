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
use App\Models\PengampuMp;


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
        $tanggal            = Carbon::now(env('APP_TIMEZONE', ''))->format('d F Y');

        return view('guru/presensi/absensi-tanpa-jadwal/view-kbm-absensi-tanpa-jadwal', compact('auth_data', 'id_guru', 'id_mata_pelajaran', 'id_kelas', 'opsi', 'tanggal'));
    }

    public function datatablesKBMAbsensiTanpaJadwal(Request $request, $id_guru, $id_mata_pelajaran, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, null, 'only_aktif');
        
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
            ->addColumn('alasan', function ($item) {
                $options = array(
                    array('id' => 1, 'text' => 'Hadir'),
                    array('id' => 2, 'text' => 'Sakit'),
                    array('id' => 3, 'text' => 'Izin'),
                    array('id' => 4, 'text' => 'Alpa'),
                );
                $data = array(
                    'options' => $options,
                    'kehadiran' => null,
                    'status_pengguna' => array(
                        'status' => $item->aktif_status_pengguna,
                        'nm_status' => $item->nm_status_pengguna
                    )
                );
                return $data;
            })
            ->make(true);
    }
    
    // Action POST
    public function actionAbsensiTanpaJadwal(Request $request, $mode, $id_guru, $id_mata_pelajaran, $id_kelas)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'uraian_materi'     => 'required',
            'waktu_mulai'       => 'required',
            'waktu_selesai'     => 'required'
        ]);

        if ($validator->fails() && $mode == 'add-kbm') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'add-kbm') {                
                DB::beginTransaction();
                try {
                    // cek kelas_mp
                    $semester_aktif     = LibDataAkademik::fetchDataSemesterAktif($auth_data);

                    if($kelas_mp = KelasMp::where('id_kelas', '=', $id_kelas)
                                            ->where('id_semester', '=', $semester_aktif->id_semester_aktif)
                                            ->where('id_mata_pelajaran', '=', $id_mata_pelajaran)
                                            ->first()) {
                        // skip
                    } else {

                        // insert kelas_mp
                        $kelas_mp                       = new KelasMp;
                        $kelas_mp->id_kelas_mp          = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();;
                        $kelas_mp->id_kelas             = $id_kelas;
                        $kelas_mp->id_semester          = $semester_aktif->id_semester;
                        $kelas_mp->id_mata_pelajaran    = $id_mata_pelajaran;
                        $kelas_mp->status_entry         = 2;
                        $kelas_mp->created_at           = $now;
                        $kelas_mp->created_by           = $auth_data->pengguna->id_pengguna;
                        $kelas_mp->save();
                    }

                    // cek pengampu_mp
                    if($pengampu_mp = PengampuMp::where('id_kelas_mp', '=', $kelas_mp->id_kelas_mp)->where('pjmp_pengampu_mp', '=', 1)->first()) {
                        // skip
                    } else {
                        // insert pengampu_mp
                        $pengampu_mp                    = new PengampuMp;
                        $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $pengampu_mp->id_kelas_mp       = $kelas_mp->id_kelas_mp;
                        $pengampu_mp->id_guru           = $id_guru;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->pjmp_uts          = 1;
                        $pengampu_mp->pjmp_uas          = 1;
                        $pengampu_mp->created_at           = $now;
                        $pengampu_mp->created_by           = $auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    }

                    // insert presensi_mp
                    $presensi_mp                        = new PresensiMp;
                    $presensi_mp->id_presensi_mp        = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $presensi_mp->id_kelas_mp           = $kelas_mp->id_kelas_mp;
                    $presensi_mp->id_pengampu_mp        = $pengampu_mp->id_pengampu_mp;

                    // ambilpertemuan terakhir
                    $max_pertemuan                      = PresensiMp::where('id_kelas_mp', '=', $kelas_mp->id_kelas_mp)->max('pertemuan_ke');
                    $max_pertemuan++;

                    $presensi_mp->pertemuan_ke          = $max_pertemuan;
                    $presensi_mp->uraian_materi         = $input->uraian_materi;
                    $presensi_mp->waktu_mulai           = $input->waktu_mulai;
                    $presensi_mp->waktu_selesai         = $input->waktu_selesai;
                    $presensi_mp->tgl_presensi          = $now->format('Y-m-d');
                    $presensi_mp->tgl_entry             = $now;
                    $presensi_mp->keterangan            = "Absensi Tanpa Jadwal";
                    $presensi_mp->created_at               = $now;
                    $presensi_mp->created_by               = $auth_data->pengguna->id_pengguna;
                    $presensi_mp->save();

                    // insert presensi_mp_siswa
                    $presensi_mp_siswa                          = new PresensiMpSiswa;
                    $presensi_mp_siswa->id_presensi_mp_siswa    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $presensi_mp_siswa->id_presensi_mp          = $presensi_mp->id_presensi_mp;

                    // PresensiMpSiswa
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        if (! empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }

                    $presensi_mp_siswa->id_siswa                = $id_siswa;
                    // $presensi_mp_siswa->kehadiran               = 99;
                    $presensi_mp_siswa->created_at              = $now;
                    $presensi_mp_siswa->created_by              = $auth_data->pengguna->id_pengguna;
                    $presensi_mp_siswa->save();

                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-tanpa-jadwal',
                        'message' => 'Save Absensi KBM Tanpa Jadwal successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Absensi KBM Tanpa Jadwal Gagal!'.$e->getMessage()
                    ];
                }
            }
        }
    }
}