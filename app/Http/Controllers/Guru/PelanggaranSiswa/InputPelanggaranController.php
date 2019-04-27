<?php

namespace App\Http\Controllers\Guru\PelanggaranSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PresensiMp as PresensiMp;
use App\Models\PresensiMpSiswa as PresensiMpSiswa;
use App\Models\UjianMpPresensi as UjianMpPresensi;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class InputPelanggaranController extends BaseController{


    public function viewAbsensiSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kbm = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

    	return view('guru/presensi/absensi-siswa/view-absensi-siswa',compact('auth_data','semester_aktif','data_kbm','data_uts','data_uas'));

    }

    // ==== ACTION PRESENSI KBM ====
    public function actionViewKBMAbsensiSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required',
            'pertemuan_ke' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'presensi/absensi-siswa/view-kbm/'.$input->id_kelas_mp.'/'.$input->pertemuan_ke
                    ];   
        }
    }

    public function viewKBMAbsensiSiswa(Request $request, $id_kelas_mp, $pertemuan_ke){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_kelas = LibGuru::fetchDataJadwalKBM($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester, $id_kelas_mp);

        return view('guru/presensi/absensi-siswa/view-kbm-absensi-siswa',compact('auth_data','semester_aktif','data_kelas', 'id_kelas_mp', 'pertemuan_ke'));

    }

    public function datatablesKBMAbsensiSiswa(Request $request, $id_kelas_mp, $pertemuan_ke){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchDataSiswaKelasMp($auth_data, $id_kelas_mp, $pertemuan_ke);

        return Datatables::of($list_data)
            ->addColumn('checkbox', function($item){
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('alasan', function($item){
                $data = array(
                    'hadir' => "Hadir",
                    'alasan_2' => "Sakit",
                    'alasan_3' => "Izin",
                    'alasan_4' => "Alpa"
                );
                return $data;
            })
            ->make(true);
    }

        // Action POST
    public function actionAbsensiSiswa(Request $request, $mode, $id = null, $pertemuan_ke = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'uraian_materi' => 'required',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required'
        ]);

        if($validator->fails() && $mode == 'add-kbm') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            
            $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

            // ACTION ADD
            if($mode == 'add-kbm') {
                $id_kelas_mp = $id;

                $presensiMpSet = PresensiMp::where('id_kelas_mp','=',$id_kelas_mp)->where('pertemuan_ke','=',$pertemuan_ke)->first();

                if($presensiMpSet) {
                    DB::beginTransaction();

                    try {
                        $presensiMp                     = PresensiMp::find($presensiMpSet->id_presensi_mp);
                        $presensiMp->uraian_materi      = $input->uraian_materi;
                        $presensiMp->waktu_mulai        = $input->waktu_mulai;
                        $presensiMp->waktu_selesai      = $input->waktu_selesai;
                        $presensiMp->save();

                        // PresensiMpSiswa
                        foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                            $presensiMpSiswaSet = PresensiMpSiswa::where('id_presensi_mp','=',$presensiMpSet->id_presensi_mp)->where('id_siswa','=',$id_siswa)->first();

                            $presensiMpSiswa                = PresensiMpSiswa::find($presensiMpSiswaSet->id_presensi_mp_siswa);
                            if(! empty($alasan)) {
                                $kehadiran = $alasan;
                            }
                            else {
                                $kehadiran = 1;
                            }
                            $presensiMpSiswa->kehadiran     = $kehadiran;
                            $presensiMpSiswa->save();
                        }

                        DB::commit();
                        // all good

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'presensi/absensi-siswa/view-kbm/'.$id_kelas_mp.'/'.$pertemuan_ke,
                            'message' => 'Save Absensi KBM Siswa successfully'
                        ];

                    } catch (\Exception $e) {
                        DB::rollback();
                        // something went wrong

                        return [
                                    'status' => 203, // GAGAL
                                    'message' => 'Absensi KBM Gagal!'
                                ];
                    }
                    
                }
                else {
                    DB::beginTransaction();

                    try {
                        // make id
                        $id_presensi_mp = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                        $presensiMp                     = new PresensiMp;
                        $presensiMp->id_presensi_mp     = $id_presensi_mp;
                        $presensiMp->id_kelas_mp        = $id_kelas_mp;
                        $presensiMp->pertemuan_ke       = $pertemuan_ke;
                        $presensiMp->uraian_materi      = $input->uraian_materi;
                        $presensiMp->waktu_mulai        = $input->waktu_mulai;
                        $presensiMp->waktu_selesai      = $input->waktu_selesai;
                        $presensiMp->tgl_entry          = $now;
                        $presensiMp->save();

                        // PresensiMpSiswa
                        foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                            $presensiMpSiswa                    = new PresensiMpSiswa;
                            $presensiMpSiswa->id_presensi_mp    = $id_presensi_mp;
                            $presensiMpSiswa->id_siswa          = $id_siswa;   
                            if(! empty($alasan)) {
                                $kehadiran = $alasan;
                            }
                            else {
                                $kehadiran = 1;
                            }
                            $presensiMpSiswa->kehadiran     = $kehadiran;
                            $presensiMpSiswa->save();   
                        }

                        DB::commit();
                        // all good

                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'presensi/absensi-siswa/view-kbm/'.$id_kelas_mp.'/'.$pertemuan_ke,
                            'message' => 'Save Absensi KBM Siswa successfully'
                        ];

                    } catch (\Exception $e) {
                        DB::rollback();
                        // something went wrong

                        return [
                                    'status' => 203, // GAGAL
                                    'message' => 'Absensi KBM Gagal!'
                                ];
                    }
                }
            }
            elseif($mode == 'add-uts') {
                $id_ujian_mp = $id;

                DB::beginTransaction();

                try {
                    // UjianMpPresensi
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        $ujianMpPresensiSet = UjianMpPresensi::where('id_ujian_mp','=',$id_ujian_mp)->where('id_siswa','=',$id_siswa)->first();

                        $ujianMpPresensi                = UjianMpPresensi::find($ujianMpPresensiSet->id_ujian_mp_presensi);
                        if(! empty($alasan)) {
                            $kehadiran = $alasan;
                        }
                        else {
                            $kehadiran = 1;
                        }
                        $ujianMpPresensi->kehadiran     = $kehadiran;
                        $ujianMpPresensi->save();
                    }

                    DB::commit();
                    // all good

                    return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'presensi/absensi-siswa/view-uts/'.$id_ujian_mp,
                            'message' => 'Save Absensi UTS Siswa successfully'
                    ];

                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'Absensi UTS Gagal!'
                            ];
                }      
            }
            elseif($mode == 'add-uas') {
                $id_ujian_mp = $id;

                DB::beginTransaction();

                try {
                    // UjianMpPresensi
                    foreach (array_combine($input->id_siswa, $input->alasan) as $id_siswa => $alasan) {
                        $ujianMpPresensiSet = UjianMpPresensi::where('id_ujian_mp','=',$id_ujian_mp)->where('id_siswa','=',$id_siswa)->first();

                        $ujianMpPresensi                = UjianMpPresensi::find($ujianMpPresensiSet->id_ujian_mp_presensi);
                        if(! empty($alasan)) {
                            $kehadiran = $alasan;
                        }
                        else {
                            $kehadiran = 1;
                        }
                        $ujianMpPresensi->kehadiran     = $kehadiran;
                        $ujianMpPresensi->save();
                    }

                    DB::commit();
                    // all good

                    return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'presensi/absensi-siswa/view-uas/'.$id_ujian_mp,
                            'message' => 'Save Absensi UAS Siswa successfully'
                    ];
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'Absensi UAS Gagal!'
                            ];
                }
            }
        }
    }

}