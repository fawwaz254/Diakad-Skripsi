<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

use App\Models\Guru;
use App\Models\KomplainSarpras;
use App\Models\Pengguna;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\PresensiMpPelanggaran;
use App\Models\TindakanPelanggaran;
use App\Models\Semester;
use App\Models\Siswa;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SaranaPrasarana\LibDataSarpras;
use App\Libraries\SumberDaya\LibGuru;

use DB;
use Validator;

class Apiv1Controller extends BaseController{
    public function actionSignIn(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'username' =>'required',
            'password' =>'required'
        ]);
  
        if($validator->fails()) {
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
            }else{
                return response()->json([
                    'status_code' 	=> 300,
                    'status_text' 	=> 'Failed',
                    'message' 	=> 'Password invalid'
                ]);
            }
        }else{
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' 	=> 'User cant found'
            ]);
        }
    }

    public function actionGetKelasKBM(Request $request){
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

    public function actionGetJadwal(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        if(!empty($input->type)){
            switch($input->type){
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

    public function actionGetPertemuanByJadwalKelasKBM(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' =>'required'
        ]);
  
        if($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $id_jadwal_kelas_mp = $input->id_jadwal_kelas_mp;

        $data_pertemuan = array();
        $data_presensiMp = PresensiMp::where('id_jadwal_kelas_mp','=',$id_jadwal_kelas_mp   )->get();

        for ($i=1; $i <= 25; $i++) {     
            $presensiMp = $data_presensiMp->firstWhere('pertemuan_ke', $i);
            if ($presensiMp) {
                if(!empty($input->query) && $input->query == 'absensi_is_null'){
                    
                }else{
                    $pertemuan = array(
                        'text' => 'Pertemuan '.$i." (Sudah)",
                        'value' => $i
                    );
                    $data_pertemuan[] = $pertemuan;
                }
            } else {
                if(!empty($input->query) && $input->query == 'absensi_is_not_null'){

                }else{
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

    public function actionGetSiswaByJadwalKelasKBM(Request $request){
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $input->id_jadwal_kelas_mp);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp','=',$input->id_jadwal_kelas_mp)->where('pertemuan_ke','=',$input->pertemuan_ke)->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $data_kelas->id_kelas_mp, $input->pertemuan_ke);

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

    public function actionGetPresensiKBM(Request $request){
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $input->id_jadwal_kelas_mp);

        $presensi_mp_aktif = PresensiMp::where('id_jadwal_kelas_mp','=',$input->id_jadwal_kelas_mp)->where('pertemuan_ke','=',$input->pertemuan_ke)->first();

        $data_siswa = LibSiswa::fetchDataSiswaKelasMp($auth_data, $data_kelas->id_kelas_mp, $input->pertemuan_ke);
        if($presensi_mp_aktif){
            $data_presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp','=',$presensi_mp_aktif->id_presensi_mp)->get();
            $presensi_mp_aktif = $presensi_mp_aktif->only('id_presensi_mp', 'id_kelas_mp', 'id_jadwal_kelas_mp', 'pertemuan_ke', 'uraian_materi', 'waktu_mulai', 'waktu_selesai', 'tgl_presensi', 'id_guru_pengganti', 'alasan_tidak_hadir', 'tgl_entry', 'persentase_presensi_mp', 'keterangan');
        }else{
            $data_presensi_mp_siswa = null;
            $presensi_mp_aktif = null;
        }

        foreach($data_siswa as $siswa){
            $kehadiran = null;
            if($data_presensi_mp_siswa && $presensi_mp_siswa = $data_presensi_mp_siswa->firstWhere('id_siswa', $siswa->id_siswa)){
                $kehadiran = $presensi_mp_siswa->kehadiran;
            }
            $siswa->status_kehadiran = $kehadiran;
            switch($kehadiran){
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

    public function actionAbsensiSiswa(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_jadwal_kelas_mp' => 'required',
            'pertemuan_ke' => 'required',
            'uraian_materi' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'tgl_presensi' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }

        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, null, $input->id_jadwal_kelas_mp);

        $presensi_mp = PresensiMp::where('id_jadwal_kelas_mp','=',$input->id_jadwal_kelas_mp)->where('pertemuan_ke','=',$input->pertemuan_ke)->first();
        
        DB::beginTransaction();
        
        try {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if($presensi_mp) {
                $presensi_mp->updated_by         = $input->auth_data->pengguna->id_pengguna;
            }else{
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
                if(! empty($alasan)) {
                    $kehadiran = $alasan;
                }
                else {
                    $kehadiran = 1;
                }

                if($presensi_mp_siswa = PresensiMpSiswa::where('id_presensi_mp','=',$presensi_mp->id_presensi_mp)->where('id_siswa','=',$id_siswa)->first()){
                    $presensi_mp_siswa->updated_by                = $input->auth_data->pengguna->id_pengguna;
                }else{
                    if($siswa = Siswa::find($id_siswa)){
                        $presensi_mp_siswa                            = new PresensiMpSiswa;
                        $presensi_mp_siswa->id_presensi_mp_siswa      = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $presensi_mp_siswa->id_presensi_mp            = $presensi_mp->id_presensi_mp;
                        $presensi_mp_siswa->id_siswa                  = $id_siswa;   
                        $presensi_mp_siswa->created_by                = $input->auth_data->pengguna->id_pengguna;
                    }else{
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

    public function actionGetKomplainRuangan(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ruangan' => 'required'
        ]);

        if($validator->fails()) {
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

    public function actionGetKomplainBukuAlat(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_buku_alat' => 'required'
        ]);

        if($validator->fails()) {
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

    public function actionKomplainSarpras(Request $request, $mode){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            /*'id_ruangan' => 'required',
            'id_buku_alat' => 'required',*/
            'keterangan_komplain' => 'required',
            'is_urgent' => 'required'
        ]);

        $mode_delete = array("delete-ruangan", "delete-bukualat");

        if($validator->fails() && !in_array($mode, $mode_delete)) {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }
        else {
            DB::beginTransaction();
        
            try {
                // mengambil waktu sekarang
                $now = Carbon::now(env('APP_TIMEZONE', ''));

                // get id_guru
                $guru = Guru::where('id_pengguna','=',$input->auth_data->pengguna->id_pengguna)->first();
                $id_guru = $guru->id_guru;

                //** MODE UNTUK RUANGAN
                if($mode == 'add-ruangan') {
                    $id_komplain_sarpras = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $komplainSarpras                            = new KomplainSarpras;
                    $komplainSarpras->id_komplain_sarpras       = $id_komplain_sarpras;
                    $komplainSarpras->id_ruangan                = $input->id_ruangan;
                    if(! empty($input->id_inventaris_ruangan)) {
                        $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                    }
                    else {
                        $komplainSarpras->id_inventaris_ruangan     = null;
                    }
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->is_sudah_perbaikan        = 0;
                    $komplainSarpras->created_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $message = 'Save Komplain Sarpras successfully';
                }
                elseif($mode == 'edit-ruangan') {
                    // make object to find id
                    $komplainSarpras                            = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->id_ruangan                = $input->id_ruangan;
                    if(! empty($input->id_inventaris_ruangan)) {
                        $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                    }
                    else {
                        $komplainSarpras->id_inventaris_ruangan     = null;
                    }
                    $komplainSarpras->id_guru_komplain          = $id_guru;
                    $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                    $komplainSarpras->is_urgent                 = $input->is_urgent;
                    $komplainSarpras->updated_by                = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->updated_at                = $now;
                    $komplainSarpras->save();

                    $message = 'Update Komplain Sarpras successfully';
                }
                elseif($mode == 'delete-ruangan') {
                    // make object to find id
                    $komplainSarpras               = KomplainSarpras::find($input->id_komplain_sarpras);
                    $komplainSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $komplainSarpras->save();

                    $komplainSarpras->delete();

                    $message = 'Delete Komplain Sarpras successfully';
                }
                //** MODE UNTUK BUKU/ALAT
                elseif($mode == 'add-bukualat') {
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
                }
                elseif($mode == 'edit-bukualat') {
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
                }
                elseif($mode == 'delete-bukualat') {
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

    public function actionGetRuangan(Request $request){
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

    public function actionGetInventarisRuangan(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_ruangan' => 'required'
        ]);

        if($validator->fails()) {
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

    public function actionGetBukuAlat(Request $request){
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

    public function actionGetPelanggaranSiswa(Request $request){
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

    public function actionPelanggaranSiswa(Request $request, $mode){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_presensi_mp'              => 'required',
            'id_siswa'              => 'required',
            'catatan_pelanggaran'   => 'required',
        ]);
        
        if($validator->fails() && $mode != 'delete') {
            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => $validator->errors()->first()
            ]);
        }
        else{
            DB::beginTransaction();
        
            try {
                // mengambil waktu sekarang
                $now = Carbon::now(env('APP_TIMEZONE', ''));

                if($mode == 'add') {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $presensiMpPelanggaran                               = new PresensiMpPelanggaran;
                    $presensiMpPelanggaran->id_presensi_mp_pelanggaran   = $id;
                    $presensiMpPelanggaran->id_presensi_mp               = $input->id_presensi_mp;
                    $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
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
                }
                elseif($mode == 'edit'){
                    $id = $input->id;
                    
                    // make object to find id
                    $presensiMpPelanggaran                               = PresensiMpPelanggaran::find($id);
                    $presensiMpPelanggaran->id_siswa                     = $input->id_siswa;
                    $presensiMpPelanggaran->catatan_pelanggaran          = $input->catatan_pelanggaran;
                    // convert format date
                    $presensiMpPelanggaran->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                    $presensiMpPelanggaran->updated_at                   = $now;
                    $presensiMpPelanggaran->save();

                    $return_array = [
                        'status_code' 	=> 200,
                        'status_text' 	=> 'Success',
                        'message' 	=> 'Update Pelanggaran Siswa successfully'
                    ];
                }
                elseif($mode == 'delete'){
                    $id = $input->id;

                    if($tindakanPelanggaran = TindakanPelanggaran::where('id_presensi_mp_pelanggaran',$id)->first()){
                        $return_array = [
                            'status_code' 	=> 300,
                            'status_text' 	=> 'Failed',
                            'message' 	=> 'Failed To Delete, sudah diambil tindakan atas Pelanggaran siswa'
                        ];
                    }else{
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
                    'message' => 'Terdapat error'
                ]);
            }   
        }
    }

    public function actionSignOut(Request $request){
        $input = (object) $request->input();

        $pengguna = $input->auth_data->pengguna;
        $pengguna->is_online = 0;
        $pengguna->save();

        Auth::logout();
        return redirect('/');
    }

}