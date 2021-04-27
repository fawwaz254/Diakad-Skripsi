<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;

use App\Models\Kota as Kota;
use App\Models\Provinsi as Provinsi;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\Admisi as Admisi;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;

use Auth;
use DB;
use Session;
use Validator;

class AdmisiSiswaController extends BaseController
{
    public function viewAdmisiSiswa(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('pendidikan/siswa/admisi-siswa/view-admisi-siswa', compact('auth_data'));
    }

    public function actionViewAdmisiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
          'nis_nama_siswa' =>'required'
      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/admisi-siswa/view-detail/'.$input->nis_nama_siswa
                ];
        }
    }

    public function viewDetailAdmisiSiswa(Request $request, $nis_nama_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchAdmisiSiswa($auth_data, $nis_nama_siswa);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', 1)->first();
        $admisi = Admisi::where('id_siswa', '=', $siswa->id_siswa)->where('id_semester', '=', $semester_aktif->id_semester)->first();
        $status = StatusPengguna::where('status_join_table', '=', '3')->where('kode_status_pengguna', '!=', 'LULUS')->where('kode_status_pengguna', '!=', 'CALON_LULUS')->get();
        $semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();

        return view('pendidikan/siswa/admisi-siswa/view-detail-admisi-siswa', compact('auth_data', 'nis_nama_siswa', 'siswa', 'admisi', 'status', 'semester'));
    }

    public function actionAdmisiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
          'id_siswa'            =>'required',
          'id_status_pengguna'  =>'required',
          'id_semester'         =>'required'

      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            $input_status_pengguna = StatusPengguna::where('id_status_pengguna', '=', $input->id_status_pengguna)->first();
            if ($input_status_pengguna->aktif_status_pengguna == 1) {
                $is_aktif_status_pengguna = 1;
            } else {
                $is_aktif_status_pengguna = 0;
            }

            $admisi 						           = Admisi::where('id_siswa', '=', $input->id_siswa)->where('id_semester', '=', $input->id_semester)->first();

            if ($admisi) {
                $statusPengguna                 = StatusPengguna::where('id_status_pengguna', '=', $admisi->id_status_pengguna)->first();

                if ($statusPengguna->aktif_status_pengguna == 1) {
                    if ($statusPengguna->kode_status_pengguna == 'CALON_LULUS') {
                        return [
                        'status' => 203, // GAGAL
                        'message' => 'Update Admisi Gagal, Siswa Sudah Berstatus Calon Lulus Di Semester Tersebut!'
                    ];
                    } else {
                        $admisi->id_status_pengguna     = $input->id_status_pengguna;
                        if ($is_aktif_status_pengguna == 0) {
                            $admisi->tgl_keluar           = date_format(date_create($input->tgl_keluar), "Y-m-d H:i");
                        }
                        $admisi->keterangan_admisi      = $input->keterangan_admisi;
                        $admisi->updated_by             = $input->auth_data->pengguna->id_pengguna;
                        $admisi->updated_at             = $now;
                        $admisi->save();

                        $siswa                          = Siswa::find($input->id_siswa);

                        $pengguna                       = Pengguna::find($siswa->id_pengguna);
                        $pengguna->id_status_pengguna   = $input->id_status_pengguna;
                        $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                        $pengguna->updated_at           = $now;
                        $pengguna->save();

                        return [
                      'status' => 202, // SUCCESS AND LOAD CONTENTid_periode_magang
                      'path' => 'data-kesiswaan/admisi-siswa/view-detail/'.$input->nis_nama_siswa,
                      'message' => 'Update Admisi successfully'
                  ];
                    }
                } else {
                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Update Admisi Gagal, Siswa Sudah Keluar Di Semester Tersebut!'
                    ];
                }
            } else {
                // proses ambil id_semester sebelumnya
                $semester = Semester::where('id_semester', '=', $input->id_semester)->first();

                $kode_semester = (int) $semester->kode_semester;

                if ($semester->nm_semester == 'Ganjil') {
                    $semester_sebelumnya = (int) $kode_semester - 9;
                } elseif ($semester->nm_semester == 'Genap') {
                    $semester_sebelumnya = (int) $kode_semester - 1;
                }

                $semester_terakhir = Semester::where('kode_semester', '=', $semester_sebelumnya)->where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->first();

                $id_semester_sebelumnya = $semester_terakhir->id_semester;
                /* proses selesai */

                // proses cek admisi sebelumnya
                if (! empty($id_semester_sebelumnya)) {
                    $admisi = Admisi::join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'admisi.id_status_pengguna')
                            ->where('admisi.id_siswa', '=', $input->id_siswa)
                            ->where('admisi.id_semester', '=', $id_semester_sebelumnya)
                            ->first();

                    if ($admisi) {
                        if ($admisi->kode_status_pengguna == 'CALON_LULUS') {
                            return [
                        'status' => 203, // GAGAL
                        'message' => 'Update Admisi Gagal, Siswa Sudah Berstatus Calon Lulus Di Semester Sebelumnya!'
                    ];
                        } else {
                            $admisi                         = new Admisi;
                            $admisi->id_admisi              = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $admisi->id_status_pengguna     = $input->id_status_pengguna;
                            if ($is_aktif_status_pengguna == 0) {
                                $admisi->tgl_keluar           = date_format(date_create($input->tgl_keluar), "Y-m-d H:i");
                            }
                            $admisi->keterangan_admisi      = $input->keterangan_admisi;
                            $admisi->id_semester            = $input->id_semester;
                            $admisi->id_siswa               = $input->id_siswa;
                            $admisi->created_by             = $input->auth_data->pengguna->id_pengguna;
                            $admisi->save();

                            $siswa                          = Siswa::find($input->id_siswa);

                            $pengguna                       = Pengguna::find($siswa->id_pengguna);
                            $pengguna->id_status_pengguna   = $input->id_status_pengguna;
                            $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                            $pengguna->updated_at           = $now;
                            $pengguna->save();

                            $siswa->id_kelas             = null;
                            $siswa->updated_by           = $input->auth_data->pengguna->id_pengguna;
                            $siswa->save();

                            return [
                          'status' => 202, // SUCCESS AND LOAD CONTENTid_periode_magang
                          'path' => 'data-kesiswaan/admisi-siswa/view-detail/'.$input->nis_nama_siswa,
                          'message' => 'Insert Admisi successfully'
                      ];
                        }
                    } else {
                        return [
                        'status' => 203, // GAGAL
                        'message' => 'Update Admisi Gagal, Siswa Belum Mempunyai Admisi Di Semester Sebelumnya!'
                    ];
                    }
                } else {
                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Update Admisi Gagal, Setting Nama Semester Salah!'
                    ];
                }
                /* proses selesai */
            }
        }
    }

    public function generateAdmisiSiswa(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/siswa/admisi-siswa/generate-admisi-siswa', compact('auth_data', 'data_semester', 'data_kelas'));
    }

    public function actionGenerateAdmisiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
          'id_semester'     =>'required',
          'id_kelas'      =>'required',
          'is_override'     =>'required'
      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            DB::beginTransaction();

            try {
                // ambil status_pengguna CUTI
                $status_pengguna_cuti = StatusPengguna::where('kode_status_pengguna', '=', 'CUTI')
                                      ->where('status_join_table', '=', 3)
                                      ->where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                                      ->first();

                $siswa_set = Siswa::select('pengguna.id_pengguna', 'siswa.id_siswa')
                              ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                              ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->where('kelas.id_kelas', '=', $input->id_kelas)
                              ->get();

                foreach ($siswa_set as $siswa) {
                    // action siswa aktif
                    $siswa_aktif_set = Siswa::select('pengguna.id_pengguna', 'siswa.id_siswa', 'status_pengguna.id_status_pengguna')
                              ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                              ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                              ->where('siswa.id_siswa', '=', $siswa->id_siswa)
                              ->where('status_pengguna.kode_status_pengguna', '=', 'AKTIF')
                              ->orderBy('siswa.nis_siswa', 'ASC')
                              ->first();
            
                    if (! empty($siswa_aktif_set->id_siswa)) {
                        $pengambilan_mp_set = PengambilanMp::where('id_siswa', '=', $siswa_aktif_set->id_siswa)
                                    ->where('id_semester', '=', $input->id_semester)
                                    ->where('status_apv_pengambilan_mp', '=', 1)
                                    ->first();

                        // action siswa sudah diplotting mapel dan sudah terapprove, akan diberikan status AKTIF
                        if (! empty($pengambilan_mp_set->id_pengambilan_mp)) {
                            // cek admisi pada semester tsb
                            $admisi_existing = Admisi::where('id_siswa', '=', $siswa_aktif_set->id_siswa)
                                    ->where('id_semester', '=', $input->id_semester)
                                    ->where('id_status_pengguna', '=', $siswa_aktif_set->id_status_pengguna)
                                    ->first();

                            if (! empty($admisi_existing->id_admisi)) {
                                // cek apabila timpa data = Ya
                                if ($input->is_override == 1) {
                                    $admisi_existing->updated_by           = $input->auth_data->pengguna->id_pengguna;
                                    $admisi_existing->updated_at           = $now;
                                    $admisi_existing->save();
                                }
                            } else {
                                $admisi                         = new Admisi;
                                $admisi->id_admisi              = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                $admisi->id_status_pengguna     = $siswa_aktif_set->id_status_pengguna;
                                $admisi->id_semester            = $input->id_semester;
                                $admisi->id_siswa               = $siswa_aktif_set->id_siswa;
                                $admisi->keterangan_admisi      = "Generate Admisi Kesiswaan";
                                $admisi->created_by             = $input->auth_data->pengguna->id_pengguna;
                                $admisi->save();
                            }
                        }
                        // action siswa belum diplotting mapel atau belum terapprove, maka akan diberikan status CUTI
                        else {
                            // cek admisi pada semester tsb
                            $admisi_existing = Admisi::where('id_siswa', '=', $siswa_aktif_set->id_siswa)
                                    ->where('id_semester', '=', $input->id_semester)
                                    ->where('id_status_pengguna', '=', $status_pengguna_cuti->id_status_pengguna)
                                    ->first();

                            if (! empty($admisi_existing->id_admisi)) {
                                // cek apabila timpa data = Ya
                                if ($input->is_override == 1) {
                                    $admisi_existing->updated_by           = $input->auth_data->pengguna->id_pengguna;
                                    $admisi_existing->updated_at           = $now;
                                    $admisi_existing->save();
                                }
                            } else {
                                $admisi                         = new Admisi;
                                $admisi->id_admisi              = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                                $admisi->id_status_pengguna     = $status_pengguna_cuti->id_status_pengguna;
                                $admisi->id_semester            = $input->id_semester;
                                $admisi->id_siswa               = $siswa_aktif_set->id_siswa;
                                $admisi->keterangan_admisi      = "Generate Admisi Kesiswaaan";
                                $admisi->created_by             = $input->auth_data->pengguna->id_pengguna;
                                $admisi->save();
                            }
                        }
                    } else {
                        // action siswa yang terdapat usulan CUTI pada semester terpilih di admisi
                        $admisi_cuti_semester = Admisi::select('admisi.id_admisi')
                                        ->where('id_siswa', '=', $siswa->id_siswa)
                                        ->where('id_semester', '=', $input->id_semester)
                                        ->where('id_status_pengguna', '=', $status_pengguna_cuti->id_status_pengguna)
                                        ->first();
              
                        // action siswa yang terdapat usulan CUTI (pada semester terpilih) di admisi, maka plotting mapelnya akan diset belum terapprove
                        if (! empty($admisi_cuti_semester->id_admisi)) {
                            // cek apabila timpa data = Ya
                            if ($input->is_override == 1) {
                                $admisi_cuti_semester->updated_by           = $input->auth_data->pengguna->id_pengguna;
                                $admisi_cuti_semester->updated_at           = $now;
                                $admisi_cuti_semester->save();
                            }

                            $pengambilan_mp_set = PengambilanMp::where('id_siswa', '=', $siswa->id_siswa)
                                    ->where('id_semester', '=', $input->id_semester)
                                    ->get();

                            foreach ($pengambilan_mp_set as $pengambilan_mp) {
                                $pengambilan_mp_update                              = PengambilanMp::find($pengambilan_mp->id_pengambilan_mp);
                                $pengambilan_mp_update->status_apv_pengambilan_mp   = 0;
                                $pengambilan_mp_update->updated_by                  = $input->auth_data->pengguna->id_pengguna;
                                $pengambilan_mp_update->updated_at                  = $now;
                                $pengambilan_mp_update->save();
                            }
                        }
                    }
                }

                DB::commit();
                return [
                  'status' => 200, // SUCCESS
                  'message' => 'Generate Admisi Berhasil',
                  'path' => 'data-kesiswaan/admisi-siswa/generate'
          ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong

                return [
                        'status' => 300, // GAGAL
                        'message' => 'Generate Admisi Gagal!'
                    ];
            }
        }
    }

    public function laporanGenerateAdmisiSiswa(Request $request, $id_semester = null, $id_kelas = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/siswa/admisi-siswa/laporan-admisi-siswa', compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas'));
    }

    public function actionViewLaporanAdmisiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
          'id_semester' =>'required',
          'id_kelas'    =>'required'
      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/admisi-siswa/view-laporan/'.$input->id_semester.'/'.$input->id_kelas
                ];
        }
    }

    public function viewLaporanAdmisiSiswa(Request $request, $id_semester, $id_kelas)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('pendidikan/siswa/admisi-siswa/laporan-admisi-siswa', compact('auth_data', 'data_semester', 'data_kelas', 'id_semester', 'id_kelas'));
    }

    public function datatablesAdmisiSiswa(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::select('siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'status_pengguna.nm_status_pengguna', 'jalur.nm_jalur')
          ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
          ->join('status_pengguna as sp', 'sp.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
          ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
          ->join('jalur_siswa', function ($join) {
              $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
          })
          ->join('jalur', 'jalur.id_jalur', '=', 'jalur_siswa.id_jalur')
          ->leftJoin('admisi', function ($join) use ($id_semester) {
              $join->on('admisi.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('admisi.id_semester', '=', $id_semester)
                                 ->whereNull('admisi.deleted_at');
          })
          ->leftJoin('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'admisi.id_status_pengguna')
          ->where('kelas.id_kelas', '=', $id_kelas)
          ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
          ->where('sp.aktif_status_pengguna', '=', 1)
          ->orderBy('siswa.nis_siswa', 'ASC')
          ->orderBy('pengguna.nm_pengguna', 'ASC')
          ->get();

        return Datatables::of($siswa)
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->nis_siswa,
                        'admisi' => $item->nm_status_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }
}
