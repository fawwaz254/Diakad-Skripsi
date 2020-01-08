<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\KomplainSarpras;
use App\Models\Pengguna;
use App\Models\PresensiHarian;
use App\Models\PresensiHarianSiswa;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\PresensiMpPelanggaran;
use App\Models\TindakanPelanggaran;
use App\Models\Semester;
use App\Models\Siswa;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\SumberDaya\LibGuru;

use DB;
use Validator;

class Apiv1Controller extends BaseController
{
    public function actionSignIn(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'username' =>'required',
            'password' =>'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        if ($pengguna = Pengguna::where(['username' => $input->username])->first()) {
            if (Hash::check($input->password, $pengguna->password)) {
                $api_key = hash('sha256', uniqid());
                $pengguna->api_key = $api_key;
                $pengguna->save();

                $data_pengguna = array(
                    'id_pengguna' => $pengguna->id_pengguna,
                    'id_status_pengguna' => $pengguna->id_status_pengguna,
                    'id_sekolah' => $pengguna->id_sekolah,
                    'nm_pengguna' => $pengguna->nm_pengguna,
                    'username' => $pengguna->username,
                    'actor' => $pengguna->status_join_to_text(),
                    'gelar_depan' => $pengguna->gelar_depan,
                    'gelar_belakang' => $pengguna->gelar_belakang,
                    'api_key' => $pengguna->api_key
                );
                return response()->json([
                    'status_code' 	=> 200,
                    'status_text' 	=> 'Success',
                    'message' 	=> 'Login success',
                    'data' => array(
                        'pengguna' => $data_pengguna
                    )
                ]);
            } else {
                return response()->json([
                    'status_code' 	=> 300,
                    'status_text' 	=> 'Failed',
                    'message' 	=> 'Password invalid'
                ]);
            }
        } else {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' 	=> 'User cant found'
            ]);
        }
    }

    public function actionGetKelasKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = Semester::where(['id_sekolah' => $auth_data->pengguna->id_sekolah, 'is_aktif_semester' => 1])->first();
        
        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_kbm' => $data_kbm
            )
        ]);
    }

    public function actionGetKelasAll(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas' => $data_kelas
            )
        ]);
    }

    public function actionGetSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'semester' => $data_semester
            )
        ]);
    }

    public function actionGetAbsensiHarianKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' =>'required',
            'id_semester' =>'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $id_semester = $input->id_semester;
        $id_kelas = $input->id_kelas;
        
        $list_data = PresensiHarian::with('jadwal_hari', 'guru_entry', 'siswa_entry')
                                        ->where('id_semester', $id_semester)
                                        ->where('id_kelas', $id_kelas);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'absensi_harian' =>
                    Datatables::of($list_data)
                        ->addColumn('tanggal', function ($item) {
                            return $item->convertDateFormat('tgl_entry', 'd M Y H:i');
                        })
                        ->addColumn('petugas', function ($item) {
                            if (!empty($item->id_guru_entry)) {
                                return $item->guru_entry->nm_pengguna.' (Guru Piket)';
                            } elseif (!empty($item->id_siswa_entry)) {
                                return $item->siswa_entry->nm_pengguna.' (Siswa)';
                            }
                        })
                        ->addColumn('action', function ($item) {
                            $data = array(
                                'id' => $item->id_presensi_harian
                            );
                            return $data;
                        })
                        ->toArray()['data']
            )
        ]);
    }

    public function actionGetAbsensiHarianSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas' =>'required',
            'id_semester' =>'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $id_semester = $input->id_semester;
        $id_kelas = $input->id_kelas;
        
        $list_data = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);
        
        if (!empty($input->presensi_harian)) {
            $presensi_harian = PresensiHarian::find($input->presensi_harian);
            $presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', '=', $presensi_harian->id_presensi_harian)->get();
        } else {
            $presensi_harian = null;
            $presensi_harian_siswa = null;
        }

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'tgl_entry' => (!empty($presensi_harian))? date_format(date_create($presensi_harian->tgl_entry), "Y-m-d") : null,
                'time_entry' => (!empty($presensi_harian))? date_format(date_create($presensi_harian->tgl_entry), "H:i:s") : null,
                'siswa' =>
                    Datatables::of($list_data)
                        ->addColumn('kehadiran', function ($item) use ($presensi_harian_siswa) {
                            $kehadiran = null;
                            if ($presensi_harian_siswa && $selected_presensi_harian_siswa = $presensi_harian_siswa->firstWhere('id_siswa', $item->id_siswa)) {
                                $kehadiran = $selected_presensi_harian_siswa->kehadiran;
                            }
                            return $kehadiran;
                        })
                        ->toArray()['data']
            )
        ]);
    }

    public function actionGetAbsensiHarianSave(Request $request, $mode)
    {
        $input = (object) $request->input();

        switch ($mode) {
            case 'add':
                $required_params = [
                    'id_kelas' => 'required',
                    'id_semester' => 'required',
                    'tgl_entry' => 'required',
                    'time_entry' => 'required',
                ]; break;
                case 'edit':
                $required_params = [
                    'id_kelas' => 'required',
                    'id_semester' => 'required',
                    'tgl_entry' => 'required',
                    'time_entry' => 'required',
                    'id_presensi_harian' => 'required',
                ]; break;
            case 'delete':
                $required_params = [
                    'id_presensi_harian' => 'required',
                ]; break;
            default:
                $required_params = [];
        }

        $validator = Validator::make($request->all(), $required_params);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            
            // ACTION ADD
            if ($mode == 'add' || $mode == 'edit') {
                $tgl_entry = Carbon::parse($input->tgl_entry.' '.$input->time_entry);
                DB::beginTransaction();
                try {
                    if (!empty($input->id_presensi_harian)) {
                        $presensi_harian = PresensiHarian::find($input->id_presensi_harian);
                    } else {
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $presensi_harian = new PresensiHarian;
                        $presensi_harian->id_presensi_harian = $id;
                        $presensi_harian->id_guru_entry = $input->auth_data->pengguna->id_pengguna;
                        $presensi_harian->id_kelas = $input->id_kelas;
                        $presensi_harian->id_semester = $input->id_semester;
                    }
                    $presensi_harian->id_jadwal_hari = ($tgl_entry->dayOfWeek == 0)? 7 : $tgl_entry->dayOfWeek;
                    $presensi_harian->tgl_entry = $tgl_entry;
                    $presensi_harian->save();

                    $total_siswa = 0;
                    $total_siswa_masuk = 0;
                    
                    // presensi_harian_siswa
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        if (! empty($alasan)) {
                            $kehadiran = $alasan;
                        } else {
                            $kehadiran = 1;
                        }
                        
                        if ($presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', '=', $presensi_harian->id_presensi_harian)->where('id_siswa', '=', $id_siswa)->first()) {
                            $presensi_harian_siswa->updated_by                = $input->auth_data->pengguna->id_pengguna;
                        } else {
                            // make id
                            $id_presensi_harian_siswa = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                            $presensi_harian_siswa                            = new PresensiHarianSiswa;
                            $presensi_harian_siswa->id_presensi_harian        = $presensi_harian->id_presensi_harian;
                            $presensi_harian_siswa->id_presensi_harian_siswa  = $id_presensi_harian_siswa;
                            $presensi_harian_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;
                        }

                        $presensi_harian_siswa->id_siswa                    = $id_siswa;
                        $presensi_harian_siswa->kehadiran                   = $kehadiran;
                        $presensi_harian_siswa->save();

                        if ($kehadiran == 1) {
                            $total_siswa++;
                            $total_siswa_masuk++;
                        } else {
                            $total_siswa++;
                        }
                    }

                    $presensi_harian->persentase_presensi_harian = ($total_siswa_masuk / $total_siswa);
                    $presensi_harian->save();

                    DB::commit();
                    // all good

                    return response()->json([
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' => 'Save Absensi Harian Siswa successfully'
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return response()->json([
                        'status_code' 	=> 300,
                        'status_text' 	=> 'Failed',
                        'message' => 'Absensi Harian Gagal!'
                    ]);
                }
            } elseif ($mode == 'delete') {
                DB::beginTransaction();
                try {
                    $presensi_harian = PresensiHarian::where('id_presensi_harian', $input->id_presensi_harian)->delete();
                    $presensi_harian_siswa = PresensiHarianSiswa::where('id_presensi_harian', $input->id_presensi_harian)->delete();

                    DB::commit();
                    // all good

                    return response()->json([
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' => 'Delete Absensi Harian Siswa successfully'
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return response()->json([
                        'status_code' 	=> 300,
                        'status_text' 	=> 'Failed',
                        'message' => 'Absensi Harian Gagal!'
                    ]);
                }
            }
        }
    }

    public function actionGetMonitoringKelasKosong(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $now = Carbon::now();
        $now = Carbon::createfromformat('Y-m-d H:i', '2019-11-06 09:00');
        $tgl = $now->toDateString();
        $hari = $now->dayOfWeekIso;
        $jam = $now->hour;
        $menit = $now->minute;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas_kosong = DB::select('SELECT jkm.id_jadwal_kelas_mp, mp.nm_mata_pelajaran, k.nm_kelas, r.nm_ruangan, pmp.id_presensi_mp, p.nm_pengguna, p.gelar_depan, p.gelar_belakang
                            FROM jadwal_kelas_mp jkm
                            JOIN ruangan r ON r.id_ruangan = jkm.id_ruangan
                            JOIN kelas_mp kmp ON kmp.id_kelas_mp = jkm.id_kelas_mp
                            JOIN kelas k ON k.id_kelas = kmp.id_kelas
                            JOIN mata_pelajaran mp ON mp.id_mata_pelajaran = kmp.id_mata_pelajaran
                            JOIN jadwal_jam jj ON jj.id_jadwal_jam = jkm.id_jadwal_jam
                            JOIN jadwal_jam jjs ON jjs.id_jadwal_jam = jkm.id_jadwal_jam_selesai
                            LEFT JOIN pengampu_mp pm ON pm.id_kelas_mp = kmp.id_kelas_mp AND pm.pjmp_pengampu_mp = 1
                            JOIN guru g ON g.id_guru = pm.id_guru
                            JOIN pengguna p ON p.id_pengguna = g.id_pengguna
                            LEFT JOIN presensi_mp pmp ON pmp.id_kelas_mp = kmp.id_kelas_mp 
                                AND DATE(pmp.tgl_entry) = DATE(NOW()) 
                                AND WEEKDAY(pmp.tgl_entry) = '.$hari.'-1
                            WHERE jkm.id_jadwal_hari = '.$hari.' 
                            AND kmp.id_semester = "'.$semester_aktif->id_semester.'"
                            AND pmp.id_presensi_mp IS NULL
                            AND TIME("'.$now.'") BETWEEN TIME(CONCAT(jj.jam_mulai, ":", jj.menit_mulai)) and TIME(CONCAT(jjs.jam_selesai, ":", jjs.menit_selesai))
                            ORDER BY k.tingkat, k.nm_kelas');

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_kosong' => $data_kelas_kosong
            )
        ]);
    }

    public function actionGetJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        if (!empty($input->type)) {
            switch ($input->type) {
                case 'akademik':
                    $list_data = LibDataAkademik::fetchDataKalenderAkademik($auth_data, $semester_aktif->id_semester);
                    break;
                case 'kbm':
                    $list_data = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);
                    break;
                case 'uts':
                    $list_data = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);
                    break;
                case 'uas':
                    $list_data = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);
                    break;
                default:
                    $list_data = null;
                    break;
            }
        }


        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'jadwal' => $list_data
            )
        ]);
    }

    public function actionGetPertemuanByJadwalKelasKBM(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' =>'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $id_jadwal_kelas_mp = $input->id_jadwal_kelas_mp;

        $data_pertemuan = array();
        $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp', '=', $id_jadwal_kelas_mp)->get();

        for ($i=1; $i <= 25; $i++) {
            $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $i);
            if ($presensiMp) {
                if (!empty($input->query) && $input->query == 'absensi_is_null') {
                } else {
                    $pertemuan = array(
                        'text' => 'Pertemuan '.$i." (Sudah)",
                        'value' => $i
                    );
                    $data_pertemuan[] = $pertemuan;
                }
            } else {
                if (!empty($input->query) && $input->query == 'absensi_is_not_null') {
                } else {
                    $pertemuan = array(
                        'text' => 'Pertemuan '.$i,
                        'value' => $i
                    );
                    $data_pertemuan[] = $pertemuan;
                }
            }
        }

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'pertemuan' => $data_pertemuan
            )
        ]);
    }

    public function actionGetSiswaByJadwalKelasKBM(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $input->id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $input->pertemuan_ke)->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $input->id_jadwal_kelas_mp, $input->pertemuan_ke);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'presensi_mp' => $presensi_mp_aktif,
                'siswa' => $data_siswa,
            )
        ]);
    }

    public function actionGetPresensiKBM(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp', '=', $input->id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $input->pertemuan_ke)->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $input->id_jadwal_kelas_mp, $input->pertemuan_ke);
        if ($presensi_mp_aktif) {
            $data_presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp_aktif->id_presensi_mp)->get();
            $presensi_mp_aktif = $presensi_mp_aktif->only('id_presensi_mp', 'id_kelas_mp', 'id_jadwal_kelas_mp', 'pertemuan_ke', 'uraian_materi', 'waktu_mulai', 'waktu_selesai', 'tgl_presensi', 'id_guru_pengganti', 'alasan_tidak_hadir', 'tgl_entry', 'persentase_presensi_mp', 'keterangan');
        } else {
            $data_presensi_mp_siswa = null;
            $presensi_mp_aktif = null;
        }

        foreach ($data_siswa as $siswa) {
            $kehadiran = null;
            if ($data_presensi_mp_siswa && $presensi_mp_siswa = $data_presensi_mp_siswa->firstWhere('id_siswa', $siswa->id_siswa)) {
                $kehadiran = $presensi_mp_siswa->kehadiran;
            }
            $siswa->status_kehadiran = $kehadiran;
            switch ($kehadiran) {
                case 1: $text = 'Hadir'; break;
                case 2: $text = 'Sakit'; break;
                case 3: $text = 'Izin'; break;
                case 4: $text = 'Alpa'; break;
                default: $text = 'Belum diset'; break;
            }
            $siswa->status_text = $text;
        }

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'presensi_mp' => $presensi_mp_aktif,
                'siswa' => $data_siswa,
            )
        ]);
    }

    public function actionAbsensiSiswa(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required',
            'uraian_materi' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tgl_presensi' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $input->id_jadwal_kelas_mp);

        $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp', '=', $input->id_jadwal_kelas_mp)->where('pertemuan_ke', '=', $input->pertemuan_ke)->first();
        
        DB::beginTransaction();
        
        try {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($presensi_mp) {
                $presensi_mp->updated_by         = $input->auth_data->pengguna->id_pengguna;
            } else {
                $presensi_mp                     = new PresensiMp;
                $presensi_mp->id_presensi_mp     = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $presensi_mp->id_jadwal_kelas_mp = $input->id_jadwal_kelas_mp;
                $presensi_mp->id_kelas_mp        = $data_kelas->id_kelas_mp;
                $presensi_mp->pertemuan_ke       = $input->pertemuan_ke;
                $presensi_mp->tgl_entry          = $now;
                $presensi_mp->created_by         = $input->auth_data->pengguna->id_pengguna;
            }
            
            $presensi_mp->uraian_materi      = $input->uraian_materi;
            $presensi_mp->waktu_mulai        = $input->waktu_mulai;
            $presensi_mp->waktu_selesai      = $input->waktu_selesai;
            $presensi_mp->tgl_presensi       = $input->tgl_presensi;
            $presensi_mp->save();
            
            foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                if (! empty($alasan)) {
                    $kehadiran = $alasan;
                } else {
                    $kehadiran = 1;
                }

                if ($presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp', '=', $presensi_mp->id_presensi_mp)->where('id_siswa', '=', $id_siswa)->first()) {
                    $presensi_mp_siswa->updated_by                = $input->auth_data->pengguna->id_pengguna;
                } else {
                    if ($siswa = Siswa::find($id_siswa)) {
                        $presensi_mp_siswa                            = new PresensiMpSiswa;
                        $presensi_mp_siswa->id_presensi_mp_siswa      = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $presensi_mp_siswa->id_presensi_mp            = $presensi_mp->id_presensi_mp;
                        $presensi_mp_siswa->id_siswa                  = $id_siswa;
                        $presensi_mp_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;
                    } else {
                        return response()->json([
                            'status_code' 	=> 300,
                            'status_text' 	=> 'Failed',
                            'message' 	=> 'Error'
                        ]);
                    }
                }

                $presensi_mp_siswa->kehadiran     = $kehadiran;
                $presensi_mp_siswa->save();
            }

            DB::commit();

            return response()->json([
                'status_code' 	=> 200,
                'status_text' 	=> 'Success',
                'message' 	=> 'Absensi success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => 'Absensi gagal'
            ]);
        }
    }

    public function actionGetKomplainRuangan(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ruangan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $list_data = LibDataSarpras::fetchDataKomplainRuangan($auth_data, $input->id_ruangan);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'komplain-ruangan' => $list_data
            )
        ]);
    }

    public function actionGetKomplainBukuAlat(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_buku_alat' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $list_data = LibDataSarpras::fetchDataKomplainBukuAlat($auth_data, $input->id_buku_alat);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'komplain-ruangan' => $list_data
            )
        ]);
    }

    public function actionKomplainSarpras(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            /*'id_ruangan' => 'required',
            'id_buku_alat' => 'required',*/
            'keterangan_komplain' => 'required',
            'is_urgent' => 'required'
        ]);

        $mode_delete = array("delete-ruangan", "delete-bukualat");

        if ($validator->fails() && !in_array($mode, $mode_delete)) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        } else {
            DB::beginTransaction();
        
            try {
                // mengambil waktu sekarang
                $now = Carbon::now(env('APP_TIMEZONE', ''));

                // get id_guru
                $guru = Guru::where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)->first();
                $id_guru = $guru->id_guru;

                //** MODE UNTUK RUANGAN
                if ($mode == 'add-ruangan') {
                    $id_komplain_sarpras = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $komplainSarpras                            = new KomplainSarpras;
                    $komplainSarpras->id_komplain_sarpras       = $id_komplain_sarpras;
                    $komplainSarpras->id_ruangan                = $input->id_ruangan;
                    if (! empty($input->id_inventaris_ruangan)) {
                        $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                    } else {
                        $komplainSarpras->id_inventaris_ruangan     = null;
                    }
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->is_sudah_perbaikan        = 0;
                    $komplainSarpras->created_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $message = 'Save Komplain Sarpras successfully';
                } elseif ($mode == 'edit-ruangan') {
                    // make object to find id
                    $komplainSarpras                            = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->id_ruangan                = $input->id_ruangan;
                    if (! empty($input->id_inventaris_ruangan)) {
                        $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                    } else {
                        $komplainSarpras->id_inventaris_ruangan     = null;
                    }
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->updated_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->updated_at                = $now;
                    $komplainSarpras->save();

                    $message = 'Update Komplain Sarpras successfully';
                } elseif ($mode == 'delete-ruangan') {
                    // make object to find id
                    $komplainSarpras               = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $komplainSarpras->delete();

                    $message = 'Delete Komplain Sarpras successfully';
                }
                //** MODE UNTUK BUKU/ALAT
                elseif ($mode == 'add-bukualat') {
                    $id_komplain_sarpras = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $komplainSarpras                            = new KomplainSarpras;
                    $komplainSarpras->id_komplain_sarpras       = $id_komplain_sarpras;
                    $komplainSarpras->id_buku_alat              = $input->id_buku_alat;
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->is_sudah_perbaikan        = 0;
                    $komplainSarpras->created_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $message = 'Save Komplain Sarpras successfully';
                } elseif ($mode == 'edit-bukualat') {
                    // make object to find id
                    $komplainSarpras                            = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->id_buku_alat              = $input->id_buku_alat;
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->updated_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->updated_at                = $now;
                    $komplainSarpras->save();

                    $message = 'Update Komplain Sarpras successfully';
                } elseif ($mode == 'delete-bukualat') {
                    // make object to find id
                    $komplainSarpras               = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $komplainSarpras->delete();

                    $message = 'Delete Komplain Sarpras successfully';
                }

                DB::commit();

                return response()->json([
                    'status_code' 	=> 200,
                    'status_text' 	=> 'Success',
                    'message' 	=> $message
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' 	=> 300,
                    'status_text' 	=> 'Failed',
                    'message' => 'Terdapat error'
                ]);
            }
        }
    }

    public function actionGetRuangan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'ruangan' => $data_ruangan
            )
        ]);
    }

    public function actionGetInventarisRuangan(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ruangan' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $data_inventaris_ruangan = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $input->id_ruangan);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'inventaris_ruangan' => $data_inventaris_ruangan
            )
        ]);
    }

    public function actionGetBukuAlat(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'buku_alat' => $data_buku_alat
            )
        ]);
    }

    public function actionGetPelanggaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataPresensiPelanggaran($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'pelanggaran_siswa' => $data_pelanggaran_siswa
            )
        ]);
    }

    public function actionGetKategoriPelanggaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kategori_pelanggaran' => $data_kategori
            )
        ]);
    }

    public function actionGetSubkategoriPelanggaranSiswa(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kategori' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $input->id_kategori);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'subkategori_pelanggaran' => $data_subkategori
            )
        ]);
    }

    public function actionPelanggaranSiswa(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_presensi_mp'              => 'required',
            'id_siswa'              => 'required',
            'id_subkategori_pelanggaran'   => 'required',
            'catatan_pelanggaran'   => 'required',
        ]);
        
        if ($validator->fails() && $mode != 'delete') {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        } else {
            DB::beginTransaction();
        
            try {
                // mengambil waktu sekarang
                $now = Carbon::now(env('APP_TIMEZONE', ''));

                if ($mode == 'add') {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $siswa = Siswa::where('id_siswa', '=', $input->id_siswa)->first();

                    $presensiMpPelanggaran                               = new PresensiMpPelanggaran;
                    $presensiMpPelanggaran->id_presensi_mp_pelanggaran   = $id;
                    $presensiMpPelanggaran->id_presensi_mp               = $input->id_presensi_mp;
                    $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
                    $presensiMpPelanggaran->id_kelas                     = $siswa->id_kelas;
                    $presensiMpPelanggaran->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                    $presensiMpPelanggaran->catatan_pelanggaran          = $input->catatan_pelanggaran;
                    // convert format date
                    $presensiMpPelanggaran->is_sudah_tindakan            = 0;
                    $presensiMpPelanggaran->created_by                   = $input->auth_data->pengguna->id_pengguna;
                    $presensiMpPelanggaran->save();

                    $return_array = [
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' 	=> 'Save Pelanggaran Siswa successfully'
                    ];
                } elseif ($mode == 'edit') {
                    $id = $input->id;
                    
                    // make object to find id
                    $presensiMpPelanggaran                               = PresensiMpPelanggaran::find($id);
                    // $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
                    $presensiMpPelanggaran->catatan_pelanggaran          = $input->catatan_pelanggaran;
                    $presensiMpPelanggaran->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                    // convert format date
                    $presensiMpPelanggaran->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                    $presensiMpPelanggaran->updated_at                   = $now;
                    $presensiMpPelanggaran->save();

                    $return_array = [
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' 	=> 'Update Pelanggaran Siswa successfully'
                    ];
                } elseif ($mode == 'delete') {
                    $id = $input->id;

                    if ($tindakanPelanggaran = TindakanPelanggaran::where('id_presensi_mp_pelanggaran', $id)->first()) {
                        $return_array = [
                            'status_code' 	=> 300,
                            'status_text' 	=> 'Failed',
                            'message' 	=> 'Failed To Delete, sudah diambil tindakan atas Pelanggaran siswa'
                        ];
                    } else {
                        // make object to find id
                        $presensiMpPelanggaran               = PresensiMpPelanggaran::find($id);
                        $presensiMpPelanggaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                        $presensiMpPelanggaran->save();

                        $presensiMpPelanggaran->delete();

                        $return_array = [
                            'status_code' 	=> 200,
                            'status_text' 	=> 'Success',
                            'message' 	=> 'Delete Pelanggaran Siswa successfully'
                        ];
                    }
                }
                DB::commit();

                return response()->json($return_array);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' 	=> 300,
                    'status_text' 	=> 'Failed',
                    'message' => 'Terdapat error '.$e->getMessage()
                ]);
            }
        }
    }
}
