<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use App\Models\Kelas as Kelas;
use App\Models\Siswa as Siswa;
use App\Models\WaliMurid as WaliMurid;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;

use Auth;
use DB;
use Excel;
use Session;
use Validator;

class SettingWaliMuridController extends BaseController
{
    public function viewSettingWaliMurid(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_wali_murid      = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();


        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $act = null;
        if (!empty($request->segment(4))) {
            $act = $request->segment(4);
        }

        return view('pendidikan/siswa/setting-wali-murid/view-kelas-setting-wali-murid', compact('auth_data', 'data_kelas', 'id_wali_murid', 'act'));
    }

    public function viewUploadSettingWaliMurid(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')
            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();

        $kelas = Kelas::where('id_kelas', '=', $id_kelas)->first();

        return view('pendidikan/siswa/setting-wali-murid/upload-setting-wali-murid', compact('auth_data', 'data_kelas', 'kelas', 'id_kelas'));
    }

    public function viewDownloadSettingWaliMurid(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::where('id_kelas', '=', $id_kelas)->first();
        $list_data = Siswa::with('pengguna', 'wali_murid', 'wali_murid.pengguna')->where('id_kelas', '=', $id_kelas)->get();

        return Excel::create('Download Data Siswa dan Wali Murid Kelas ' . $kelas->nm_kelas, function ($excel) use ($list_data) {
            $excel->sheet('New sheet', function ($sheet) use ($list_data) {
                $sheet->setColumnFormat(array(
                    'F' => '@'
                ))->loadView('pendidikan/siswa/setting-wali-murid/setting-wali-murid-download', compact('list_data'));
            });
        })->download('xls');
    }

    public function actionViewSettingWaliMurid(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
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
                'path' => 'siswa/setting-wali-murid/view-kelas/' . $input->id_kelas
            ];
        }
    }
    public function viewKelasWaliMurid(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')
            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();


        $kelas = Kelas::where('id_kelas', '=', $id_kelas)->first();

        return view('pendidikan/siswa/setting-wali-murid/view-setting-wali-murid', compact('auth_data', 'data_kelas', 'kelas', 'id_kelas'));
    }

    public function editWaliMurid(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_siswa', '=', $id)->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->first();

        //sudah punya wali murid, edit
        if (!empty($siswa->id_wali_murid)) {
            $wali_murid = WaliMurid::where('id_wali_murid', '=', $siswa->id_wali_murid)->first();
        }

        return view('pendidikan/siswa/setting-wali-murid/edit-setting-wali-murid', compact('auth_data', 'siswa', 'wali_murid'));
    }

    public function datatablesWaliMurid(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Siswa::select()->addSelect('pwm.gelar_depan AS gd', 'pwm.gelar_belakang AS gb', 'pengguna.nm_pengguna AS nm_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->leftJoin('wali_murid', 'wali_murid.id_wali_murid', '=', 'siswa.id_wali_murid')
            ->leftjoin('pengguna AS pwm', 'pwm.id_pengguna', '=', 'wali_murid.id_pengguna')
            ->where('id_kelas', '=', $id_kelas)->get();

        return Datatables::of($list_data)
            ->editColumn('nm_wali_murid', function ($item) {
                return $item->gd . ' ' . $item->nm_wali_murid . ' ' . $item->gb;
            })
            ->addColumn('nisn_siswa', function ($item) {
                return $item->nisn_siswa;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function downloadFileExcel()
    {
        $file = public_path() . "/excel/ContohFileExcelSettingWaliMurid.xls";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelSettingWaliMurid.xls', $headers);
    }

    public function actionGetWaliMurid(Request $request)
    {
        if (!empty($request->q)) {
            $data_wali_murid = WaliMurid::select('id_wali_murid', 'id_pengguna', 'nm_wali_murid', 'nomor_hp_wali_murid')
                ->where(function ($q) use ($request) {
                    $q->where('nm_wali_murid', 'LIKE', '%' . $request->q . '%')
                        ->orwhere('nomor_hp_wali_murid', 'LIKE', '%' . $request->q . '%');
                })->where('is_aktif', 1)->take(15)->get();
        } else {
            $data_wali_murid = WaliMurid::select('id_wali_murid', 'id_pengguna', 'nm_wali_murid', 'nomor_hp_wali_murid')->take(15)->where('is_aktif', 1)->get();
        }

        return $data_wali_murid;
    }

    public function actionSettingWaliMurid(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nomor_hp_wali_murid' => 'required'
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
            if ($mode == 'edit') {
                $siswa = Siswa::where('id_siswa', '=', $id)->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->first();
                $data_waliMurid = WaliMurid::where('nomor_hp_wali_murid', '=', $input->nomor_hp_wali_murid)->first();
                $siswa_waliMurid                    = Siswa::find($id);
                $siswa_waliMurid->id_wali_murid        = $data_waliMurid->id_wali_murid;
                $siswa_waliMurid->is_orang_tua      = $input->is_orang_tua;
                $siswa_waliMurid->save();

                return [
                    'status' => 200, // SUCCESS AND LOAD CONTENT
                    'message' => 'Update Data Wali Murid Berhasil!'
                ];
            } elseif ($mode == 'add') {
                $wali_murid = WaliMurid::where('nomor_hp_wali_murid', '=', $input->nomor_hp_wali_murid)->first();

                if ($wali_murid == null) {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $id_pengguna        = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $status_pengguna    = StatusPengguna::where('status_join_table', '=', '4')->where('aktif_status_pengguna', '=', '1')->first();

                    DB::beginTransaction();
                    try {
                        DB::table('pengguna')->insert(
                            [
                                'id_pengguna'           => $id_pengguna,
                                'id_status_pengguna'    => $status_pengguna->id_status_pengguna,
                                'id_sekolah'            => $input->auth_data->pengguna->id_sekolah,
                                'nm_pengguna'           => $input->nm_wali_murid,
                                'username'              => $input->nomor_hp_wali_murid,
                                'password'              => Hash::make($input->nomor_hp_wali_murid),
                                'gelar_depan'           => $input->gelar_depan,
                                'gelar_belakang'        => $input->gelar_belakang,
                                'must_change_password'  => 1,
                                'status_join_table'     => 4,
                                'created_at'            => $now,
                                'created_by'            => $input->auth_data->pengguna->id_pengguna
                            ]
                        );

                        DB::table('wali_murid')->insert(
                            [
                                'id_wali_murid'         => $id,
                                'id_pengguna'           => $id_pengguna,
                                'nm_wali_murid'         => $input->nm_wali_murid,
                                'nomor_hp_wali_murid'   => $input->nomor_hp_wali_murid,
                                'is_aktif'              => 1,
                                'created_at'            => $now,
                                'created_by'            => $input->auth_data->pengguna->id_pengguna
                            ]
                        );
                        DB::table('role_pengguna')->insert(
                            [
                                'id_role'               => 4,
                                'id_pengguna'           => $id_pengguna,
                                'keterangan_role_pengguna'  => "Input Wali Murid",
                                'is_aktif'              => 1,
                                'created_at'            => $now,
                                'created_by'            => $input->auth_data->pengguna->id_pengguna
                            ]
                        );
                        DB::commit();
                        return [
                            'status' => 200, // SUCCESS AND LOAD TABLE
                            'message' => 'Input Data Wali Murid Berhasil'
                        ];
                    } catch (\Exception $e) {
                        DB::rollback();
                        // something went wrong
                        return [
                            'status'    => 200, // GAGAL
                            'message'   => 'Tambah Data Wali Murid Gagal'
                        ];
                    }
                } else {
                    return [
                        'status' => 200, // GAGAL
                        'message' => 'Data Wali Murid Sudah Ada. Silahkan Masukkan Nomor HP Lain!'
                    ];
                }
            }
        }
    }

    public function uploadFileExcel(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if ($request->hasFile('file-excel')) {
            $path = $request->file('file-excel')->getRealPath();
            $data = Excel::load($path)->get();

            $batch_insert_data = [];

            if ($data->count()) {
                foreach ($data as $key => $item) {
                    if (empty($item->nis)) {
                        // return [
                        //     'status' 	=> 300, // GAGAL
                        //     'message'	=> 'Upload Setting Wali Murid Gagal, ditemukan NIS siswa yang tidak diisi dalam file yang diupload'
                        // ];
                        $data->forget($key);
                    }

                    if ($siswa = Siswa::where('nis_siswa', $item->nis)->where('id_kelas', $id_kelas)->first()) {
                        // if (!empty($siswa->id_wali_murid)) {
                        //     if ($wali_murid = WaliMurid::where('nomor_hp_wali_murid', $item->telp_wali_murid)->where('id_wali_murid', '<>', $siswa->id_wali_murid)->first()) {
                        //         return [
                        //             'status' 	=> 300, // GAGAL
                        //             'message'	=> 'Upload Setting Wali Murid Gagal, ditemukan Nomor Telepon Wali Murid yang sama di dalam sistem'
                        //         ];
                        //     }
                        // } else {
                        //     if ($wali_murid = WaliMurid::where('nomor_hp_wali_murid', $item->telp_wali_murid)->first()) {
                        //         return [
                        //             'status' 	=> 300, // GAGAL
                        //             'message'	=> 'Upload Setting Wali Murid Gagal, ditemukan Nomor Telepon Wali Murid yang sama di dalam sistem'
                        //         ];
                        //     }
                        // }
                    } else {
                        return [
                            'status'     => 300, // GAGAL
                            'message'    => 'Upload Setting Wali Murid Gagal, ditemukan NIS ' . $item->nis . ' yang tidak ada pada kelas dalam file yang diupload'
                        ];
                    }
                }

                // foreach ($data as $item_1) {
                //     $jumlah_nomor_telp = 0;
                //     foreach ($data as $item_2) {
                //         if (!empty($item_1->telp_wali_murid) && !empty($item_2->telp_wali_murid)) {
                //             if ($item_1->telp_wali_murid == $item_2->telp_wali_murid) {
                //                 $jumlah_nomor_telp++;
                //             }
                //         }
                //     }

                //     if ($jumlah_nomor_telp > 1) {
                //         return [
                //             'status' 	=> 300, // GAGAL
                //             'message'	=> 'Upload Setting Wali Murid Gagal, ditemukan Nomor Telepon '.$item_1->telp_wali_murid.' yang sama di dalam file yang diupload'
                //         ];
                //     }
                // }

                DB::beginTransaction();
                try {
                    $status_pengguna    = StatusPengguna::where('status_join_table', '=', '4')->where('aktif_status_pengguna', '=', '1')->first();
                    foreach ($data as $item) {
                        if (!empty($item->telp_wali_murid)) {
                            $siswa = Siswa::where('nis_siswa', $item->nis)->first();
                            if (!empty($siswa->id_wali_murid)) {
                                // Update Wali Murid
                                if ($wali_murid = WaliMurid::where('id_wali_murid', $siswa->id_wali_murid)->first()) {

                                    if ($check_wali_murid_lama = WaliMurid::where('nomor_hp_wali_murid', $item->telp_wali_murid)->first()) {
                                        // Check Value same or Not
                                        if ($wali_murid->nm_wali_murid != $item->nama_wali_murid || $wali_murid->nomor_hp_wali_murid != $item->telp_wali_murid) {
                                            $wali_murid->nm_wali_murid         = $item->nama_wali_murid;
                                            $wali_murid->nomor_hp_wali_murid   = $item->telp_wali_murid;
                                            $wali_murid->save();

                                            $pengguna                        = Pengguna::where('id_pengguna', $wali_murid->id_pengguna)->first();
                                            $pengguna->nm_pengguna           = $item->nama_wali_murid;
                                            $pengguna->username              = $item->telp_wali_murid;
                                            $pengguna->password              = Hash::make($item->telp_wali_murid);
                                            $pengguna->gelar_depan           = $item->gelar_depan;
                                            $pengguna->gelar_belakang        = $item->gelar_belakang;
                                            $pengguna->save();
                                        }
                                    } else {
                                        $id_wali_murid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                        $id_pengguna        = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                                        $pengguna = new Pengguna;
                                        $pengguna->id_pengguna           = $id_pengguna;
                                        $pengguna->id_status_pengguna    = $status_pengguna->id_status_pengguna;
                                        $pengguna->id_sekolah            = $input->auth_data->pengguna->id_sekolah;
                                        $pengguna->nm_pengguna           = $item->nama_wali_murid;
                                        $pengguna->username              = $item->telp_wali_murid;
                                        $pengguna->password              = Hash::make($item->telp_wali_murid);
                                        $pengguna->gelar_depan           = $item->gelar_depan;
                                        $pengguna->gelar_belakang        = $item->gelar_belakang;
                                        $pengguna->must_change_password  = 1;
                                        $pengguna->status_join_table     = 4;
                                        $pengguna->created_at            = $now;
                                        $pengguna->created_by            = $input->auth_data->pengguna->id_pengguna;
                                        $pengguna->save();

                                        $wali_murid = new WaliMurid;
                                        $wali_murid->id_pengguna           = $id_pengguna;
                                        $wali_murid->id_wali_murid         = $id_wali_murid;
                                        $wali_murid->nm_wali_murid         = $item->nama_wali_murid;
                                        $wali_murid->nomor_hp_wali_murid   = $item->telp_wali_murid;
                                        $wali_murid->is_aktif              = 1;
                                        $wali_murid->created_at            = $now;
                                        $wali_murid->created_by            = $input->auth_data->pengguna->id_pengguna;
                                        $wali_murid->save();

                                        $role_pengguna = new RolePengguna;
                                        $role_pengguna->id_role               = 4;
                                        $role_pengguna->id_pengguna           = $id_pengguna;
                                        $role_pengguna->keterangan_role_pengguna  = "Upload Wali Murid";
                                        $role_pengguna->is_aktif              = 1;
                                        $role_pengguna->created_at            = $now;
                                        $role_pengguna->created_by            = $input->auth_data->pengguna->id_pengguna;
                                        $role_pengguna->save();

                                        $siswa->id_wali_murid = $id_wali_murid;
                                        $siswa->save();
                                    }
                                }
                            } else {
                                if ($wali_murid = WaliMurid::where('nomor_hp_wali_murid', $item->telp_wali_murid)->first()) {
                                    // wali murid sudah ada (kasus wali murid punya 2 siswa)
                                    $siswa->id_wali_murid       = $wali_murid->id_wali_murid;
                                    $siswa->is_aktif_wali_murid = 0;
                                    $siswa->save();
                                } else {
                                    // Insert Wali Murid
                                    $id_wali_murid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                    $id_pengguna        = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                                    $batch_insert_data['pengguna'][] =
                                        [
                                            'id_pengguna'           => $id_pengguna,
                                            'id_status_pengguna'    => $status_pengguna->id_status_pengguna,
                                            'id_sekolah'            => $input->auth_data->pengguna->id_sekolah,
                                            'nm_pengguna'           => $item->nama_wali_murid,
                                            'username'              => $item->telp_wali_murid,
                                            'password'              => Hash::make($item->telp_wali_murid),
                                            'gelar_depan'           => $item->gelar_depan,
                                            'gelar_belakang'        => $item->gelar_belakang,
                                            'must_change_password'  => 1,
                                            'status_join_table'     => 4,
                                            'created_at'            => $now,
                                            'created_by'            => $input->auth_data->pengguna->id_pengguna
                                        ];

                                    $batch_insert_data['wali_murid'][] = [
                                        'id_pengguna'           => $id_pengguna,
                                        'id_wali_murid'         => $id_wali_murid,
                                        'nm_wali_murid'         => $item->nama_wali_murid,
                                        'nomor_hp_wali_murid'   => $item->telp_wali_murid,
                                        'is_aktif'              => 1,
                                        'created_at'            => $now,
                                        'created_by'            => $input->auth_data->pengguna->id_pengguna
                                    ];

                                    $batch_insert_data['role_pengguna'][] = [
                                        'id_role'               => 4,
                                        'id_pengguna'           => $id_pengguna,
                                        'keterangan_role_pengguna'  => "Upload Wali Murid",
                                        'is_aktif'              => 1,
                                        'created_at'            => $now,
                                        'created_by'            => $input->auth_data->pengguna->id_pengguna
                                    ];

                                    $siswa->id_wali_murid = $id_wali_murid;
                                    $siswa->save();
                                }
                            }
                        }
                    }

                    if (sizeof($batch_insert_data) > 0) {
                        Pengguna::insert($batch_insert_data['pengguna']);
                        WaliMurid::insert($batch_insert_data['wali_murid']);
                        RolePengguna::insert($batch_insert_data['role_pengguna']);
                    }

                    DB::commit();
                    return [
                        'status' => 202,
                        'path' => 'siswa/setting-wali-murid/view-kelas/' . $id_kelas,
                        'message' => 'Upload Setting Wali Murid Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    return [
                        'status'     => 300, // GAGAL
                        'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine()
                    ];
                }
            } else {
                return [
                    'status'     => 300, // FAILED
                    'message'     => "File Excel Anda kosong"
                ];
            }
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }
}
