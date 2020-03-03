<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\JadwalKelasMp;
use App\Models\JadwalHari;
use App\Models\JadwalJam;
use App\Models\PengampuMp;
use App\Models\Ruangan;
use App\Models\KelasMp;
use App\Models\KomplainSarpras;
use App\Models\Kota;
use App\Models\Pengguna;
use App\Models\PresensiHarian;
use App\Models\PresensiHarianSiswa;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\PresensiMpPelanggaran;
use App\Models\PengambilanMp;
use App\Models\TindakanPelanggaran;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\UjianMpPresensi;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Akademik\LibAkademik;

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
                    'type_actor' => $pengguna->status_join_table,
                    'keterangan_actor' => $pengguna->status_join_to_text(),
                    'path_actor' => $pengguna->path_join_to_text(),
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

    public function actionGetDataPribadi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $data_pribadi = LibGuru::fetchDataAllGuru($auth_data, $guru->id_guru);

        $data_pribadi = $data_pribadi->only('nm_pengguna', 'gelar_depan', 'gelar_belakang', 'nik_ptk', 'jenis_kelamin', 'id_kota_lahir', 'tgl_lahir', 'nm_ibu_kandung', 'nomor_telp', 'nomor_hp', 'email');

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'data_pribadi' => $data_pribadi
            )
        ]);
    }

    public function actionDataPribadi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id_pengguna = $auth_data->pengguna->id_pengguna;
        
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        DB::beginTransaction();
        
        try {
            // make object to find id
            $pengguna                           = Pengguna::find($id_pengguna);
            $pengguna->nm_pengguna              = $input->nm_pengguna;
            
            $pengguna->gelar_depan              = $input->gelar_depan;
            $pengguna->gelar_belakang           = $input->gelar_belakang;
            $pengguna->email_pengguna           = $input->email;
            $pengguna->nomor_hp_pengguna        = $input->nomor_hp;
            $pengguna->updated_by               = $id_pengguna;
            $pengguna->updated_at               = $now;
            $pengguna->save();

            $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
            $guru->nik_ptk                  = $input->nik_ptk;
            $guru->jenis_kelamin            = $input->jenis_kelamin;
            $guru->id_kota_lahir            = $input->id_kota_lahir;
            $guru->tgl_lahir                = date_format(date_create($input->tgl_lahir), "Y-m-d");
            $guru->nm_ibu_kandung           = $input->nm_ibu_kandung;
            $guru->nomor_telp               = $input->nomor_telp;
            $guru->nomor_hp                 = $input->nomor_hp;
            $guru->email                    = $input->email;
            $guru->updated_by               = $id_pengguna;
            $guru->updated_at               = $now;
            $guru->save();
            
            DB::commit();
            // all good

            return response()->json([
                'status_code' 	=> 200,
                'status_text' 	=> 'Success',
                'message' => 'Update data pribadi successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => 'Update data pribadi gagal'
            ]);
        }
    }

    public function actionGetKota(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kota = Kota::select('id_kota', 'id_provinsi', 'nm_kota')->where('kota.is_aktif', '=', 1)->orderBy('nm_kota', 'asc')->get();

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kota' => $kota
            )
        ]);
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

    public function actionGetRekapMonitoringKelasKosong(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'on_date' =>'required'
        ]);
  
        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $on_date = $input->on_date;

        $carbon_on_date = Carbon::createFromFormat('Y-m-d', $on_date);
        $tgl = $carbon_on_date->toDateString();
        $hari = $carbon_on_date->dayOfWeekIso;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas_kosong = DB::select('SELECT jkm.id_jadwal_kelas_mp, mp.nm_mata_pelajaran, k.tingkat, k.nm_kelas, r.nm_ruangan, pmp.id_presensi_mp, p.nm_pengguna, p.gelar_depan, p.gelar_belakang, jj.jam_mulai, jj.menit_mulai, jjs.jam_selesai, jjs.menit_selesai
                                    FROM jadwal_kelas_mp jkm
                                    JOIN ruangan r ON r.id_ruangan = jkm.id_ruangan AND r.deleted_at IS NULL
                                    JOIN kelas_mp kmp ON kmp.id_kelas_mp = jkm.id_kelas_mp AND kmp.deleted_at IS NULL
                                    JOIN kelas k ON k.id_kelas = kmp.id_kelas AND k.deleted_at IS NULL
                                    JOIN mata_pelajaran mp ON mp.id_mata_pelajaran = kmp.id_mata_pelajaran AND mp.deleted_at IS NULL
                                    JOIN jadwal_jam jj ON jj.id_jadwal_jam = jkm.id_jadwal_jam AND jj.deleted_at IS NULL
                                    JOIN jadwal_jam jjs ON jjs.id_jadwal_jam = jkm.id_jadwal_jam_selesai AND jjs.deleted_at IS NULL
                                    LEFT JOIN pengampu_mp pm ON pm.id_kelas_mp = kmp.id_kelas_mp AND pm.pjmp_pengampu_mp = 1 AND pm.deleted_at IS NULL
                                    LEFT JOIN guru g ON g.id_guru = pm.id_guru AND g.deleted_at IS NULL
                                    LEFT JOIN pengguna p ON p.id_pengguna = g.id_pengguna AND p.deleted_at IS NULL
                                    LEFT JOIN presensi_mp pmp ON pmp.id_kelas_mp = kmp.id_kelas_mp 
                                        AND DATE(pmp.tgl_presensi) = DATE("'.$on_date.'") 
                                        AND WEEKDAY(pmp.tgl_presensi) = '.$hari.'-1
                                        AND pmp.deleted_at IS NULL
                                    WHERE jkm.id_jadwal_hari = '.$hari.' 
                                    AND jkm.deleted_at IS NULL
                                    AND pmp.id_presensi_mp IS NULL
                                    AND kmp.id_semester = "'.$semester_aktif->id_semester.'"
                                    ORDER BY jj.jam_mulai, jj.menit_mulai, k.tingkat, k.nm_kelas');
        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_kosong' => $data_kelas_kosong,
            )
        ]);
    }

    public function actionGetMonitoringKelasKosong(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $tgl = $now->toDateString();
        $hari = $now->dayOfWeekIso;
        $jam = $now->hour;
        $menit = $now->minute;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas_kosong = DB::select('SELECT jkm.id_jadwal_kelas_mp, mp.nm_mata_pelajaran, k.tingkat, k.nm_kelas, r.nm_ruangan, pmp.id_presensi_mp, p.nm_pengguna, p.gelar_depan, p.gelar_belakang
                            FROM jadwal_kelas_mp jkm
                            JOIN ruangan r ON r.id_ruangan = jkm.id_ruangan AND r.deleted_at IS NULL
                            JOIN kelas_mp kmp ON kmp.id_kelas_mp = jkm.id_kelas_mp AND kmp.deleted_at IS NULL
                            JOIN kelas k ON k.id_kelas = kmp.id_kelas AND k.deleted_at IS NULL
                            JOIN mata_pelajaran mp ON mp.id_mata_pelajaran = kmp.id_mata_pelajaran AND mp.deleted_at IS NULL
                            JOIN jadwal_jam jj ON jj.id_jadwal_jam = jkm.id_jadwal_jam AND jj.deleted_at IS NULL
                            JOIN jadwal_jam jjs ON jjs.id_jadwal_jam = jkm.id_jadwal_jam_selesai AND jjs.deleted_at IS NULL
                            LEFT JOIN pengampu_mp pm ON pm.id_kelas_mp = kmp.id_kelas_mp AND pm.pjmp_pengampu_mp = 1 AND pm.deleted_at IS NULL
                            JOIN guru g ON g.id_guru = pm.id_guru AND g.deleted_at IS NULL
                            JOIN pengguna p ON p.id_pengguna = g.id_pengguna AND p.deleted_at IS NULL
                            LEFT JOIN presensi_mp pmp ON pmp.id_kelas_mp = kmp.id_kelas_mp 
                                AND DATE(pmp.tgl_entry) = DATE(NOW()) 
                                AND WEEKDAY(pmp.tgl_entry) = '.$hari.'-1
                                AND pmp.deleted_at IS NULL
                            WHERE jkm.id_jadwal_hari = '.$hari.' 
                            AND jkm.deleted_at IS NULL
                            AND kmp.id_semester = "'.$semester_aktif->id_semester.'"
                            AND TIME("'.$now.'") BETWEEN TIME(CONCAT(jj.jam_mulai, ":", jj.menit_mulai)) and TIME(CONCAT(jjs.jam_selesai, ":", jjs.menit_selesai)) ORDER BY k.tingkat, k.nm_kelas');
        
        $group_data_kelas_kosong = collect($data_kelas_kosong)->groupBy('tingkat')->all();

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_kosong' => $group_data_kelas_kosong
            )
        ]);
    }

    public function actionGetRekapAbsen(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = JadwalKelasMp::with(['kelas_mp', 'kelas_mp.mata_pelajaran', 'kelas_mp.kelas', 'ruangan', 'jadwal_hari'])
                                        ->where('id_jadwal_kelas_mp', $input->id_jadwal_kelas_mp)
                                        ->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $input->id_jadwal_kelas_mp);

        $data_presensi = PresensiMp::with('presensi_mp_siswa')->where('id_jadwal_kelas_mp', $input->id_jadwal_kelas_mp)->get();

        $rekap_presensi = [];

        foreach ($data_siswa as $siswa) {
            $rekap_presensi_siswa = [];
            foreach ($data_presensi as $presensi_mp) {
                $rekap_absen[$presensi_mp->pertemuan_ke]['total_siswa'] = $presensi_mp->presensi_mp_siswa->count();
                $rekap_absen[$presensi_mp->pertemuan_ke]['total_hadir'] = $presensi_mp->presensi_mp_siswa->where('kehadiran', 1)->count();

                $presensi_siswa = [];
                $presensi_siswa['pertemuan_ke'] = $presensi_mp->pertemuan_ke;
                $presensi_siswa['tgl_presensi'] = date_format(date_create($presensi_mp->tgl_presensi), "d/m/y");
                if ($presensi_mp_siswa = $presensi_mp->presensi_mp_siswa->firstWhere('id_siswa', $siswa->id_siswa)) {
                    if ($presensi_mp_siswa->kehadiran == 1) {
                        $presensi_siswa['status_absen'] = 'V';
                    } elseif ($presensi_mp_siswa->kehadiran == 2) {
                        $presensi_siswa['status_absen'] = 'S';
                    } elseif ($presensi_mp_siswa->kehadiran == 3) {
                        $presensi_siswa['status_absen'] = 'I';
                    } elseif ($presensi_mp_siswa->kehadiran == 4) {
                        $presensi_siswa['status_absen'] = 'A';
                    } else {
                        $presensi_siswa['status_absen'] = 'X';
                    }
                } else {
                    $presensi_siswa['status_absen'] = 'X';
                }
                $rekap_presensi_siswa[] = $presensi_siswa;
            }

            $siswa->rekap = collect($rekap_presensi_siswa);
        }


        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'rekap_absen_siswa' => $data_siswa
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

    public function actionAbsensiKBMSiswa(Request $request)
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

    public function actionGetKelasUTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = Semester::where(['id_sekolah' => $auth_data->pengguna->id_sekolah, 'is_aktif_semester' => 1])->first();
        
        $data_uts = LibGuru::fetchDataJadwalUTS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_uts' => $data_uts
            )
        ]);
    }

    public function actionGetKelasUAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = Semester::where(['id_sekolah' => $auth_data->pengguna->id_sekolah, 'is_aktif_semester' => 1])->first();
        
        $data_uas = LibGuru::fetchDataJadwalUAS($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, 0);

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_uas' => $data_uas
            )
        ]);
    }

    public function actionGetPresensiUjian(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_ujian_mp' => 'required'
        ]);

        $auth_data = $input->auth_data;
        $data_siswa = LibSiswa::fetchDataSiswaUjianMp($auth_data, $input->id_ujian_mp);

        $data_ujian_mp_presensi = UjianMpPresensi::where('id_ujian_mp', '=', $input->id_ujian_mp)->get();

        foreach ($data_siswa as $siswa) {
            $kehadiran = null;
            if ($data_ujian_mp_presensi && $presensi_ujian_mp_siswa = $data_ujian_mp_presensi->firstWhere('id_siswa', $siswa->id_siswa)) {
                $kehadiran = $presensi_ujian_mp_siswa->kehadiran;
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
                'siswa' => $data_siswa,
            )
        ]);
    }

    public function actionAbsensiUjianSiswa(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ujian_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;
        
        DB::beginTransaction();
        
        try {
            $data_ujian_mp_presensi = UjianMpPresensi::where('id_ujian_mp', '=', $input->id_ujian_mp)->get();

            foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                if ($presensi_ujian_mp_siswa = $data_ujian_mp_presensi->firstWhere('id_siswa', $id_siswa)) {
                    if (! empty($alasan)) {
                        $kehadiran = $alasan;
                    } else {
                        $kehadiran = 1;
                    }
                    $presensi_ujian_mp_siswa->kehadiran     = $kehadiran;
                    $presensi_ujian_mp_siswa->save();
                }
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

    public function actionGetInputJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $id = $semester->id_semester;
        $id_pengguna = $auth_data->pengguna->id_pengguna;
        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;
       
        $data_jadwal = KelasMp::select(
            'mata_pelajaran.nm_mata_pelajaran',
            'mata_pelajaran.kd_mata_pelajaran',
            'kelas.nm_kelas',
            'kelas_mp.id_kelas_mp',
            'mata_pelajaran.kredit_semester',
            'mata_pelajaran.tingkat_semester',
            'kelas_mp.nm_kelas_mp',
            'jenis_mata_pelajaran.nm_jenis_mata_pelajaran',
            'pengampu_mp.id_guru',
            'pengguna.nm_pengguna'
        )
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->leftJoin('pengampu_mp', function ($join) {
                $join->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
                                 ->whereNull('pengampu_mp.deleted_at');
            })
            ->leftJoin('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->where('kelas_mp.id_semester', '=', $id)
            ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'asc')
            ->orderBy('kelas.nm_kelas', 'asc')
            ->orderBy('mata_pelajaran.tingkat_semester', 'asc')
            ->get();

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'data_jadwal' => $data_jadwal,
                'semester_aktif' => $semester,
                'id_guru' => $id_guru
            )
        ]);
    }

    public function actionGetDetailInputJadwal(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;
        $id = $input->id_kelas_mp;

        $kelas_mp   = KelasMp::select('mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"), 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester')
        ->leftJoin('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
        ->leftJoin('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
        ->leftJoin('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
        ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
        ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
        ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
        ->leftJoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
        ->leftJoin('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
        ->leftJoin('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
        ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
        ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
        ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
        ->where('kelas_mp.id_kelas_mp', '=', $id)
        ->first();

        $jadwal     = JadwalKelasMp::where('id_kelas_mp', '=', $id)
                        ->orderBy('id_jadwal_hari', 'asc')
                        ->get();
        $jml_jadwal = count($jadwal);

        $pengampu_mp_pj   = PengampuMp::where('id_kelas_mp', '=', $id)->where('pjmp_pengampu_mp', '=', 1)->first();
        $anggota          = PengampuMp::where('id_kelas_mp', '=', $id)->where('pjmp_pengampu_mp', '=', 2)
                                ->orderBy('id_guru', 'asc')
                                ->get();
        $jml_anggota        = count($anggota);

        $hari       = JadwalHari::get();
        $jam        = JadwalJam::orderBy('jam_ke', 'asc')->get();
        $pjma       = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        $ruangan    = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_ruangan', 'asc')->get();

        return response()->json([
            'status_code' 	=> 200,
            'status_text' 	=> 'Success',
            'message' 	=> '',
            'data' => array(
                'kelas_mp' => $kelas_mp,
                'jadwal' => $jadwal,
                'jml_jadwal' => $jml_jadwal,
                'pengampu_mp_pj' => $pengampu_mp_pj,
                'anggota' => $anggota,
                'jml_anggota' => $jml_anggota,
                'hari' => $hari,
                'jam' => $jam,
                'pjma' => $pjma,
                'ruangan' => $ruangan
            )
        ]);
    }

    public function actionInputJadwal(Request $request, $mode)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        switch ($mode) {
            case 'edit':
                $required_params = [
                    'id_kelas_mp' => 'required',
                    'id_pengampu_mp_pj' => 'required',
                    'id_jadwal_kelas_mp1' => 'required',
                    'ruangan1' => 'required',
                    'hari_jadwal1' => 'required',
                    'jam_jadwal1' => 'required',
                    'jam_jadwal_selesai1' => 'required',
                    'id_jadwal_kelas_mp2' => 'required',
                    'ruangan2' => 'required',
                    'hari_jadwal2' => 'required',
                    'jam_jadwal2' => 'required',
                    'jam_jadwal_selesai2' => 'required',
                    'id_jadwal_kelas_mp3' => 'required',
                    'ruangan3' => 'required',
                    'hari_jadwal3' => 'required',
                    'jam_jadwal3' => 'required',
                    'jam_jadwal_selesai3' => 'required',
                    'id_jadwal_kelas_mp4' => 'required',
                    'ruangan4' => 'required',
                    'hari_jadwal4' => 'required',
                    'jam_jadwal4' => 'required',
                    'jam_jadwal_selesai4' => 'required',
                    'id_jadwal_kelas_mp5' => 'required',
                    'ruangan5' => 'required',
                    'hari_jadwal5' => 'required',
                    'jam_jadwal5' => 'required',
                    'jam_jadwal_selesai5' => 'required',
                    'id_jadwal_kelas_mp6' => 'required',
                    'ruangan6' => 'required',
                    'hari_jadwal6' => 'required',
                    'jam_jadwal6' => 'required',
                    'jam_jadwal_selesai6' => 'required'
                ]; break;
            case 'delete':
                $required_params = [
                    'id_kelas_mp' => 'required',
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
            $id = $input->id_kelas_mp;
            if ($mode == 'edit') {
                $id_pengguna = $auth_data->pengguna->id_pengguna;

                $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
                $id_guru = $guru->id_guru;

                if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1)) {
                    $cek_jadwal = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1);

                    if ($cek_jadwal['guru'] == 0) {
                        return response()->json([
                            'status_code' 	=> 300,
                            'status_text' 	=> 'Failed',
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 1'
                        ]);
                    } elseif ($cek_jadwal['ruangan'] == 0) {
                        return response()->json([
                            'status_code' 	=> 300,
                            'status_text' 	=> 'Failed',
                            'message' => 'Ruangan Pada Jadwal 1 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ]);
                    }
                }

                if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan2, $input->hari_jadwal2, $input->jam_jadwal2, $input->jam_jadwal_selesai2)) {
                        $cek_jadwal2 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan2, $input->hari_jadwal2, $input->jam_jadwal2, $input->jam_jadwal_selesai2);

                        if ($cek_jadwal2['guru'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 2'
                            ]);
                        } elseif ($cek_jadwal2['ruangan'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Ruangan Pada Jadwal 2 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                            ]);
                        }
                    }
                }

                if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan3, $input->hari_jadwal3, $input->jam_jadwal3, $input->jam_jadwal_selesai3)) {
                        $cek_jadwal3 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan3, $input->hari_jadwal3, $input->jam_jadwal3, $input->jam_jadwal_selesai3);

                        if ($cek_jadwal3['guru'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 3'
                            ]);
                        } elseif ($cek_jadwal3['ruangan'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Ruangan Pada Jadwal 3 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                            ]);
                        }
                    }
                }

                if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan4, $input->hari_jadwal4, $input->jam_jadwal4, $input->jam_jadwal_selesai4)) {
                        $cek_jadwal4 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan4, $input->hari_jadwal4, $input->jam_jadwal4, $input->jam_jadwal_selesai4);

                        if ($cek_jadwal4['guru'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 4'
                            ]);
                        } elseif ($cek_jadwal4['ruangan'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Ruangan Pada Jadwal 4 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                            ]);
                        }
                    }
                }

                if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan5, $input->hari_jadwal5, $input->jam_jadwal5, $input->jam_jadwal_selesai5)) {
                        $cek_jadwal5 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan5, $input->hari_jadwal5, $input->jam_jadwal5, $input->jam_jadwal_selesai5);

                        if ($cek_jadwal5['guru'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 5'
                            ]);
                        } elseif ($cek_jadwal5['ruangan'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Ruangan Pada Jadwal 5 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                            ]);
                        }
                    }
                }

                if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan6, $input->hari_jadwal6, $input->jam_jadwal6, $input->jam_jadwal_selesai6)) {
                        $cek_jadwal6 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan6, $input->hari_jadwal6, $input->jam_jadwal6, $input->jam_jadwal_selesai6);

                        if ($cek_jadwal6['guru'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 6'
                            ]);
                        } elseif ($cek_jadwal6['ruangan'] == 0) {
                            return response()->json([
                                'status_code' 	=> 300,
                                'status_text' 	=> 'Failed',
                                'message' => 'Ruangan Pada Jadwal 6 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                            ]);
                        }
                    }
                }

                DB::beginTransaction();

                try { 
                    //input jadwal 1
                    if (! empty($input->id_jadwal_kelas_mp_1)) {
                        $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_1);
                        $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jam_jadwal_selesai1;
                        $jadwal_kelas_mp->id_ruangan            = $input->ruangan1;
                        $jadwal_kelas_mp->updated_at            = $now;
                        $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                        $jadwal_kelas_mp->save();
                    } else {
                        $jadwal_kelas_mp                        = new JadwalKelasMp;
                        $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $jadwal_kelas_mp->id_kelas_mp           = $id;
                        $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jam_jadwal_selesai1;
                        $jadwal_kelas_mp->id_ruangan            = $input->ruangan1;
                        $jadwal_kelas_mp->created_at            = $now;
                        $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                        $jadwal_kelas_mp->save();
                    }
                    

                    //input jadwal 2
                    if (! empty($input->id_jadwal_kelas_mp_2)) {
                        if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_2);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai2)? $input->jam_jadwal_selesai2 : $input->jam_jadwal2);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan2;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal2 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal2 != null or $input->ruangan2 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_2);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai2)? $input->jam_jadwal_selesai2 : $input->jam_jadwal2);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan2;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 3
                    if (! empty($input->id_jadwal_kelas_mp_3)) {
                        if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_3);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai3)? $input->jam_jadwal_selesai3 : $input->jam_jadwal3);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan3;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal3 != null or $input->jam_jadwal_selesai3 != null or $input->hari_jadwal3 != null or $input->ruangan3 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_3);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai3)? $input->jam_jadwal_selesai3 : $input->jam_jadwal3);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan3;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 4
                    if (! empty($input->id_jadwal_kelas_mp_4)) {
                        if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_4);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai4)? $input->jam_jadwal_selesai4 : $input->jam_jadwal4);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan4;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal4 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal4 != null or $input->ruangan4 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_4);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai4)? $input->jam_jadwal_selesai4 : $input->jam_jadwal4);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan4;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 5
                    if (! empty($input->id_jadwal_kelas_mp_5)) {
                        if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_5);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai5)? $input->jam_jadwal_selesai5 : $input->jam_jadwal5);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan5;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal5 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal5 != null or $input->ruangan5 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_5);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai5)? $input->jam_jadwal_selesai5 : $input->jam_jadwal5);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan5;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 6
                    if (! empty($input->id_jadwal_kelas_mp_6)) {
                        if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_6);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai6)? $input->jam_jadwal_selesai6 : $input->jam_jadwal6);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan6;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal6 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal6 != null or $input->ruangan6 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_6);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai6)? $input->jam_jadwal_selesai6 : $input->jam_jadwal6);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan6;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input pjma
                    if (! empty($input->id_pengampu_mp_pj)) {
                        $pengampu_mp                    = PengampuMp::find($input->id_pengampu_mp_pj);
                        $pengampu_mp->id_guru           = $id_guru;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->updated_at        = $now;
                        $pengampu_mp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    } else {
                        $pengampu_mp                    = new PengampuMp;
                        $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $pengampu_mp->id_kelas_mp       = $id;
                        $pengampu_mp->id_guru           = $id_guru;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->created_at        = $now;
                        $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    }

                    DB::commit();
                    
                    // all good
                    return response()->json([
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' => 'Update Usulan Mata Ajar successfully'
                    ]);
                } catch (\Exception $e) {
                    DB::rollback();

                    // something went wrong
                    return response()->json([
                        'status_code' 	=> 300,
                        'status_text' 	=> 'Failed',
                        'message' => 'Edit Usulan Mata Ajar Gagal!'
                    ]);
                }
            } elseif ($mode == 'delete') {
                if ($kelas_mp = PengambilanMp::where('id_kelas_mp', $id)->first()) {
                    return response()->json([
                        'status_code' 	=> 300,
                        'status_text' 	=> 'Failed',
                        'message' => 'Terdapat siswa yang telah mengambil kelas ini'
                    ]);
                } else {
                    JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                    PengampuMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    PengampuMp::where('id_kelas_mp', $id)->delete();

                    return response()->json([
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' => 'Delete Jadwal Mata Ajar Successfully'
                    ]);
                }
            }
        }
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
