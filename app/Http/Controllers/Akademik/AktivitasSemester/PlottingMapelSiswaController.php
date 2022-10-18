<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;
use App\Models\Kelas as Kelas;
use App\Models\Jurusan as Jurusan;
use App\Models\KelasMp as KelasMp;
use App\Models\Siswa as Siswa;
use App\Models\Semester as Semester;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\MataPelajaran as MataPelajaran;

use App\Jobs\PlottingMapelSiswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class PlottingMapelSiswaController extends BaseController
{
    public function viewPlottingMapelSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')->distinct()->orderBy('thn_masuk_siswa', 'ASC')->get();

        return view('akademik/aktivitas-semester/plotting-mapel-siswa/view-plotting-mapel-siswa', compact('auth_data', 'data_semester', 'thn_masuk_siswa', 'semester_aktif'));
    }

    public function actionViewPlottingMapelSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' =>'required',
            'angkatan' 	=> 'required'
        ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/'.$input->id_semester.'/'.$input->angkatan
                ];
        }
    }

    public function actionViewDaftarPlottingMapelSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' =>'required',
            'angkatan'  => 'required',
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'aktivitas-semester/plotting-mapel-siswa/view-daftar-kelas-plotting/'.$input->id_semester.'/'.$input->angkatan.'/'.$input->id_kelas
                ];
        }
    }

    public function viewKelasPlottingMapelSiswa(Request $request, $id_semester, $angkatan)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')->distinct()->orderBy('thn_masuk_siswa', 'ASC')->get();
        
        
        return view('akademik/aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting-mapel-siswa', compact('auth_data', 'data_semester', 'thn_masuk_siswa', 'semester_aktif', 'id_semester', 'angkatan'));
    }

    public function viewMapelPlottingMapelSiswa(Request $request, $id_semester, $angkatan, $id_jurusan)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')->distinct()->orderBy('thn_masuk_siswa', 'ASC')->get();
        $kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('kelas.id_jurusan', $id_jurusan)->get();

        $list_data = MataPelajaran::select('mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas.nm_kelas', 'kelas_mp.id_kelas_mp')
            ->join('kelas_mp', 'kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
            ->leftjoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftjoin('guru', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
            ->leftjoin('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->where('kelas_mp.id_semester', '=', $id_semester)
            ->get();
        
        return view('akademik/aktivitas-semester/plotting-mapel-siswa/view-mapel-plotting-mapel-siswa', compact('auth_data', 'data_semester', 'thn_masuk_siswa', 'semester_aktif', 'id_semester', 'angkatan', 'kelas'));
    }

    public function viewDaftarKelasPlottingMapelSiswa(Request $request, $id_semester, $angkatan, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $semester_aktif = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('is_aktif_semester', '=', '1')->first();
        $thn_masuk_siswa = Siswa::select('thn_masuk_siswa')->distinct()->orderBy('thn_masuk_siswa', 'ASC')->get();
        $kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        $list_data = MataPelajaran::select('mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas.nm_kelas', 'kelas_mp.id_kelas_mp')
            ->join('kelas_mp', 'kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
            ->leftjoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftjoin('guru', 'pengampu_mp.id_guru', '=', 'guru.id_guru')
            ->leftjoin('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->where('kelas_mp.id_semester', '=', $id_semester)
            ->get();
        
        return view('akademik/aktivitas-semester/plotting-mapel-siswa/view-daftar-plotting-mapel-siswa', compact('auth_data', 'data_semester', 'thn_masuk_siswa', 'semester_aktif', 'id_semester', 'angkatan', 'kelas', 'id_kelas'));
    }

    public function datatablesPlottingMapelSiswa(Request $request, $id, $angkatan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Jurusan::select(
            'jurusan.id_jurusan',
            'jurusan.nm_jurusan',
            DB::raw("(SELECT COUNT(*) FROM siswa 
            JOIN kelas ON kelas.id_kelas = siswa.id_kelas
            JOIN pengguna ON pengguna.id_pengguna = siswa.id_pengguna
            JOIN status_pengguna ON status_pengguna.id_status_pengguna = pengguna.id_status_pengguna 
            WHERE status_pengguna.aktif_status_pengguna = '1'
            AND kelas.id_jurusan = jurusan.id_jurusan 
            AND siswa.deleted_at IS NULL 
            ) AS jml_siswa")
        )
        ->selectRaw("(SELECT COUNT(distinct pengambilan_mp.id_siswa) FROM pengambilan_mp 
            JOIN kelas_mp ON kelas_mp.id_kelas_mp = pengambilan_mp.id_kelas_mp
            JOIN kelas ON kelas.id_kelas = kelas_mp.id_kelas 
            WHERE kelas.id_jurusan = jurusan.id_jurusan AND pengambilan_mp.deleted_at IS NULL AND pengambilan_mp.id_semester = ?) AS jml_siswa_krs", [$id])
        ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_jurusan,
                    );
                    return $data;
                })->addColumn('auto', function ($item) use($auth_data) {
                    $semua_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('kelas.id_jurusan', $item->id_jurusan)->get();
   
                    // $data = array(
                    //     'id' => $item->id_jurusan,
                    // );
                    // return $data;

                    $data = [];
                  
                        foreach ($semua_kelas as $key => $value) {
                            $data[$key]['id_kelas'] = $value->id_kelas;
                            $data[$key]['nm_kelas'] = $value->nm_kelas;
                            // $data[$key]['status'] = $item->laporan_kerja_harian_tendik[$key]->status;
                        }
                    return $data;

                })
                ->make(true);
    }

    public function datatablesMataPelajaran(Request $request, $id, $angkatan, $tingkat)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($tingkat == "0") {
            $list_data = MataPelajaran::select('mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas.nm_kelas', 'kelas_mp.id_kelas_mp')
            ->join('kelas_mp', function ($q) {
                $q->on('kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
                ->whereNull('kelas_mp.deleted_at');
            })
            ->leftjoin('pengampu_mp', function ($q) {
                $q->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                ->whereNull('pengampu_mp.deleted_at');
            })
            ->leftjoin('guru', function ($q) {
                $q->on('pengampu_mp.id_guru', '=', 'guru.id_guru')
                ->whereNull('guru.deleted_at');
            })
            ->leftjoin('pengguna', function ($q) {
                $q->on('pengguna.id_pengguna', '=', 'guru.id_pengguna')
                ->whereNull('pengguna.deleted_at');
            })
            ->join('kelas', function ($q) {
                $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                ->whereNull('kelas.deleted_at');
            })
            ->where('kelas_mp.id_semester', '=', $id)
            ->get();
        } else {
            $list_data = MataPelajaran::select('mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas.nm_kelas', 'kelas_mp.id_kelas_mp')
            ->join('kelas_mp', function ($q) {
                $q->on('kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
                ->whereNull('kelas_mp.deleted_at');
            })
            ->leftjoin('pengampu_mp', function ($q) {
                $q->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                ->whereNull('pengampu_mp.deleted_at');
            })
            ->leftjoin('guru', function ($q) {
                $q->on('pengampu_mp.id_guru', '=', 'guru.id_guru')
                ->whereNull('guru.deleted_at');
            })
            ->leftjoin('pengguna', function ($q) {
                $q->on('pengguna.id_pengguna', '=', 'guru.id_pengguna')
                ->whereNull('pengguna.deleted_at');
            })
            ->join('kelas', function ($q) {
                $q->on('kelas.id_kelas', '=', 'kelas_mp.id_kelas')
                ->whereNull('kelas.deleted_at');
            })
            ->where('kelas_mp.id_semester', '=', $id)
            ->where('kelas.id_kelas', '=', $tingkat)
            ->get();
        }

        return Datatables::of($list_data)
                ->addColumn('pjma', function ($item) {
                    return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                })
                ->addColumn('checkbox', function ($item) {
                    $data = array(
                        'id_kelas_mp' => $item->id_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesSiswa(Request $request, $angkatan, $kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Siswa::join('pengguna', function ($q) {
                                $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                                    ->whereNull('pengguna.deleted_at');
                            })
                            ->join('status_pengguna', function ($q) use ($input) {
                                $q->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                                    ->where('status_pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                                    ->where('status_pengguna.aktif_status_pengguna', '=', '1')
                                    ->whereNull('status_pengguna.deleted_at');
                            })
                            ->join('kelas', function ($q) {
                                $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                                    ->whereNull('kelas.deleted_at');
                            })
                            ->join('calon_siswa_baru', function ($q) {
                                $q->on('siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                                    ->whereNull('calon_siswa_baru.deleted_at');
                            })
                            ->where('siswa.id_kelas', '=', $kelas)->get();

        return Datatables::of($list_data)
                ->addColumn('checkbox', function ($item) {
                    $data = array(
                        'id_siswa' => $item->id_siswa
                    );
                    return $data;
                })
                ->editColumn('jenis_kelamin', function ($item) {
                    if($item->jenis_kelamin == 1){
                        return 'Laki-Laki';
                    }else if($item->jenis_kelamin == 2){
                        return 'Perempuan';
                    }else{
                        return 'Belum diset';
                    }
                })
                ->make(true);
    }

    public function actionPlottingMapelSiswa(Request $request, $mode)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'angkatan' => 'required',
            'id_kelas' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'add-krs') {
                set_time_limit(300);
                DB::beginTransaction();

                try {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    foreach ($input->id_kelas_mp as $id_kelas_mp) {
                        $pengambilan_mp_insert = array();
                        foreach ($input->id_siswa as $id_siswa) {
                            $cekSiswa = PengambilanMp::where('pengambilan_mp.id_siswa', '=', $id_siswa)->where('pengambilan_mp.id_kelas_mp', '=', $id_kelas_mp)->first();
                            if ($cekSiswa) {
                                // DB::rollback();
                                // return [
                                //                 'status' => 203, // GAGAL
                                //                 'message' => 'KRS Gagal Dilakukan'
                                //             ];
                            } else {
                                $id = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();
    
                                $pengambilan_mp_insert[] = [
                                    'id_pengambilan_mp' => $id,
                                    'id_kelas_mp' => $id_kelas_mp,
                                    'id_siswa' => $id_siswa,
                                    'id_semester' => $input->id_semester,
                                    'status_apv_pengambilan_mp' => '1',
                                    'created_at' => $now,
                                    'created_by' =>$auth_data->pengguna->id_pengguna,
                                    'updated_at' => $now,
                                    'updated_by' =>$auth_data->pengguna->id_pengguna,
                                ];
                            }
                            // $pengambilan_mp						= new PengambilanMp;
                            // $pengambilan_mp->id_pengambilan_mp	= $id;
                            // $pengambilan_mp->id_kelas_mp		= $id_kelas_mp;
                            // $pengambilan_mp->id_siswa			= $id_siswa;
                            // $pengambilan_mp->id_semester		= $input->id_semester;
                            // $pengambilan_mp->status_apv_pengambilan_mp = '1';
                            // $pengambilan_mp->save();
                        }
                        if (!empty($pengambilan_mp_insert)) {
                            PlottingMapelSiswa::dispatch($pengambilan_mp_insert);
                            // PengambilanMp::insert($pengambilan_mp_insert);
                        }
                    }

                    DB::commit();
                    return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'message' => 'KRS Manual Berhasil Dilakukan',
                            'path' => 'aktivitas-semester/plotting-mapel-siswa/view-daftar-kelas-plotting/'.$input->id_semester.'/'.$input->angkatan.'/'.$input->id_kelas
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'KRS Gagal Dilakukan '
                            ];
                }
            }
        }
    }
    public function actionAutoPlottingMapelSiswa(Request $request, $id_semester, $angkatan, $id_jurusan){
        set_time_limit(9800);
   # code...
   $input = (object) $request->input();
   $auth_data = $input->auth_data;

   $semua_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('kelas.id_jurusan', $id_jurusan)->get();
   
   DB::beginTransaction();
   try {
       foreach($semua_kelas as $kelas){
        //ambil data id_kelas_mp
        $list_mapel = MataPelajaran::select( 'kelas_mp.id_kelas_mp')
        ->join('kelas_mp', 'kelas_mp.id_mata_pelajaran', '=', 'mata_pelajaran.id_mata_pelajaran')
        ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
        ->where('kelas_mp.id_semester', '=', $id_semester)
        ->where('kelas.id_kelas', '=', $kelas->id_kelas)
        ->whereNull('kelas_mp.deleted_at')
        ->get();

        //ambil data siswaa
        $list_siswa = Siswa::join('pengguna', function ($q) {
            $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->whereNull('pengguna.deleted_at');
        })
        ->join('status_pengguna', function ($q) use ($input) {
            $q->on('status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
                ->where('status_pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                ->where('status_pengguna.aktif_status_pengguna', '=', '1')
                ->whereNull('status_pengguna.deleted_at');
        })
        ->join('kelas', function ($q) {
            $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                ->whereNull('kelas.deleted_at');
        })
        ->join('calon_siswa_baru', function ($q) {
            $q->on('siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                ->whereNull('calon_siswa_baru.deleted_at');
        })
        ->where('siswa.id_kelas', '=', $kelas->id_kelas)->get();


        foreach ($list_mapel as $id_kelas_mp) {
            // dd($id_kelas_mp);
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $pengambilan_mp_insert = array();
            foreach ($list_siswa as $id_siswa) {
                $cekSiswa = PengambilanMp::where('pengambilan_mp.id_siswa', '=', $id_siswa->id_siswa)->where('pengambilan_mp.id_kelas_mp', '=', $id_kelas_mp->id_kelas_mp)->first();
                if ($cekSiswa) {
                } else {
                    $id = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $pengambilan_mp_insert[] = [
                        'id_pengambilan_mp' => $id,
                        'id_kelas_mp' => $id_kelas_mp->id_kelas_mp,
                        'id_siswa' => $id_siswa->id_siswa,
                        'id_semester' => $id_semester,
                        'status_apv_pengambilan_mp' => '1',
                        'created_at' => $now,
                        'created_by' =>$auth_data->pengguna->id_pengguna,
                        'updated_at' => $now,
                        'updated_by' =>$auth_data->pengguna->id_pengguna,
                    ];
                    // dd($pengambilan_mp_insert);
                }
            }
            if (!empty($pengambilan_mp_insert)) {
                PlottingMapelSiswa::dispatch($pengambilan_mp_insert);
                }
        }}

        DB::commit();
        return redirect('akademik#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/' . $id_semester . '/' . $angkatan);
        //   return redirect()->back();
        // plotting-mapel-siswa/view-kelas-plotting/
        // return redirect("akademik#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/$id_semester/$angkatan");
        // return [
        //         'status' => 202, // SUCCESS AND LOAD CONTENT
        //         'message' => 'KRS Manual Berhasil Dilakukan',
        //         'path' => 'aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/'.$input->id_semester.'/'.$input->angkatan.'/'.$input->id_kelas
        // ];
    } catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        //   return redirect()->back();
          return redirect('akademik#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/' . $id_semester . '/' . $angkatan);
        // return redirect("akademik#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/$id_semester/$angkatan");
        // return [
        //             'status' => 203, // GAGAL
        //             'message' => 'KRS Gagal Dilakukan ' . $e
        //         ];
    }

}
}