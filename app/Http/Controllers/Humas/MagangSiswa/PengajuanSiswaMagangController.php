<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;

use Auth;
use DB;
use Session;
use Validator;


class PengajuanSiswaMagangController extends BaseController
{
    public function viewPengajuanSiswaMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data);


        return view('humas/magang-siswa/pengajuan-siswa-magang/view-pengajuan-siswa-magang', compact('auth_data', 'data_periode_magang', 'data_rekanan_magang'));
    }

    public function actionViewDetailPengajuanMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            /*'id_semester' => 'required'*/
            'id_rekanan_magang' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if (!empty($input->nis_nama_siswa)) {
                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/pengajuan-siswa-magang/view-detail/' . $input->id_periode_magang . '/' . $input->id_rekanan_magang . '/' . $input->nis_nama_siswa
                ];
            } else {
                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/pengajuan-siswa-magang/view-detail/' . $input->id_periode_magang . '/' . $input->id_rekanan_magang . '/0'
                ];
            }
        }
    }

    public function viewDetailPengajuanMagang(Request $request, $id_periode_magang, $id_rekanan_magang, $nis_nama_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data, $id_periode_magang);
        $data_rekanan_magang = LibMagangSiswa::fetchDataRekananMagang($auth_data, $id_rekanan_magang);


        return view('humas/magang-siswa/pengajuan-siswa-magang/view-detail-pengajuan-siswa-magang', compact('auth_data', 'id_periode_magang', 'id_rekanan_magang', 'data_periode_magang', 'nis_nama_siswa', 'data_rekanan_magang'));
    }
    public function datatablesPengajuanMagang(Request $request, $id_periode_magang, $id_rekanan_magang, $nis_nama_siswa)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibMagangSiswa::fetchDataPengajuanSiswaMagang($auth_data, $id_periode_magang, $id_rekanan_magang, $nis_nama_siswa);

        return Datatables::of($list_data)
            ->addColumn('nm_periode_magang', function ($item) {
                if (!empty($item->id_pengambilan_magang) && empty($item->id_periode_magang)) {
                    return "Diajukan Di Periode Lain";
                } else {
                    return $item->nm_periode_magang;
                }
            })
            ->addColumn('semester', function ($item) {
                if (!empty($item->id_pengambilan_magang) && empty($item->id_periode_magang)) {
                    return "Diajukan Di Periode Lain";
                } else {
                    return $item->nm_semester;
                }
            })
            ->addColumn('nm_rekanan_magang', function ($item) {
                if (!empty($item->id_rekanan_magang) && empty($item->id_rekanan_magang)) {
                    return "Diajukan pada rekanan lain";
                } else {
                    return $item->nm_rekanan_magang;
                }
            })
            ->addColumn('kuota_rekanan_magang', function ($item) {
                if (!empty($item->kuota_rekanan_magang) && empty($item->kuota_rekanan_magang)) {
                    return "Diajukan pada rekanan lain";
                } else {
                    return $item->kuota_rekanan_magang;
                }
            })
            ->addColumn('action', function ($item) {
                if (!empty($item->id_pengambilan_magang)) {
                    $data = array(
                        'id' => $item->id_pengambilan_magang,
                        'id_siswa' => $item->id_siswa,
                        'id_periode_magang' => $item->id_periode_magang,
                        'id_rekanan_magang' => $item->id_rekanan_magang,
                        'status_magang' => $item->status_magang
                    );
                } else {
                    $data = array(
                        'id' => null,
                        'id_siswa' => $item->id_siswa,
                        'id_periode_magang' => $item->id_periode_magang,
                        'id_rekanan_magang' => $item->id_rekanan_magang,
                        'status_magang' => $item->status_magang
                    );
                }

                return $data;
            })
            ->make(true);
    }

    public function cancelPengajuanMagang(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_pengambilan_magang = LibMagangSiswa::fetchDataPengajuanSiswaMagangDetail($auth_data, $id);

        return view('humas/magang-siswa/pengajuan-siswa-magang/cancel-pengajuan-siswa-magang', compact('auth_data', 'data_pengambilan_magang', 'id'));
    }

    public function actionPengajuanMagang(Request $request, $mode, $id = null, $id_siswa = null, $id_periode_magang = null, $id_rekanan_magang = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            // keperluan get url
            'nis_nama_siswa' => 'required',
            'id_periode_magang' => 'required',
            'id_rekanan_magang' => 'required',
            // ------------------
            'keterangan_batal' => 'required'
        ]);

        if ($validator->fails() && $mode == 'cancel') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION cancel
            if ($mode == 'cancel') {
                $PengajuanSiswaMagangCek = PengajuanSiswaMagang::where('id_pengambilan_magang', '=', $id)->where('status_magang', '=', 1)->first();

                if ($PengajuanSiswaMagangCek || empty($input->id_periode_magang)) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Cancel Pengajuan magang!'
                    ];
                } else {
                    $PengajuanSiswaMagang        = PengajuanSiswaMagang::find($id);

                    // get status_pengguna kode AKTIF
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna', '=', "AKTIF")
                        ->where('status_join_table', '=', 3)
                        ->where('id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                        ->first();

                    // get siswa->id_pengguna
                    $siswa  = Siswa::find($PengajuanSiswaMagang->id_siswa);


                    // -- UPDATE tabel pengajuan_magang --
                    $PengajuanSiswaMagang->keterangan_batal      = $input->keterangan_batal;
                    $PengajuanSiswaMagang->status_magang         = 10;
                    $PengajuanSiswaMagang->status_apv_pengambilan_magang = 0;
                    $PengajuanSiswaMagang->updated_by            = auth_data()->pengguna->id_pengguna;
                    $PengajuanSiswaMagang->updated_at            = $now;
                    $PengajuanSiswaMagang->save();


                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'magang-siswa/pengajuan-siswa-magang/view-detail/' . $input->id_periode_magang . '/' . $input->id_rekanan_magang . '/0',
                        'message' => 'Cancel Pengajuan Magang Successfully'
                    ];
                }
            } elseif ($mode == 'pengajuan') {
                try {
                    // make id
                    $id_pengambilan_magang = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                    // get status_pengguna kode AKTIF
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna', '=', "AKTIF")
                        ->where('status_join_table', '=', 3)
                        ->where('id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                        ->first();

                    // get siswa->id_pengguna
                    $siswa  = Siswa::find($id_siswa);

                    if ($id_periode_magang != "0") {

                        // -- INSERT tabel pengambilan_magang --
                        $PengajuanSiswaMagang                        = new PengajuanSiswaMagang;
                        $PengajuanSiswaMagang->id_pengambilan_magang = $id_pengambilan_magang;
                        $PengajuanSiswaMagang->id_siswa              = $id_siswa;
                        $PengajuanSiswaMagang->id_kelas              = $siswa->id_kelas;
                        $PengajuanSiswaMagang->id_periode_magang     = $id_periode_magang;
                        $PengajuanSiswaMagang->id_rekanan_magang     = $id_rekanan_magang;
                        $PengajuanSiswaMagang->status_magang         = 0;
                        $PengajuanSiswaMagang->status_apv_pengambilan_magang = 0;
                        $PengajuanSiswaMagang->created_by            = auth_data()->pengguna->id_pengguna;
                        $PengajuanSiswaMagang->save();

                        return [
                            'status' => 203, // SUCCESS AND LOAD TABLE
                            'message' => 'Pengajuan magang Successfully'
                        ];
                    } else {
                        // cek periode magang aktif sesuai semester aktif
                        $periodeMagang = PeriodeMagang::join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                            ->where('periode_magang.is_aktif', '=', 1)
                            ->where('semester.is_aktif_semester', '=', 1)
                            ->first();

                        if (!empty($periodeMagang->id_periode_magang)) {

                            // -- INSERT tabel pengambilan_magang --
                            $PengajuanSiswaMagang                        = new PengajuanSiswaMagang;
                            $PengajuanSiswaMagang->id_pengambilan_magang = $id_pengambilan_magang;
                            $PengajuanSiswaMagang->id_siswa              = $id_siswa;
                            $PengajuanSiswaMagang->id_kelas              = $siswa->id_kelas;
                            $PengajuanSiswaMagang->id_periode_magang     = $periodeMagang->id_periode_magang;
                            $PengajuanSiswaMagang->id_rekanan_magang     = $id_rekanan_magang;
                            $PengajuanSiswaMagang->status_magang         = 0;
                            $PengajuanSiswaMagang->status_apv_pengambilan_magang = 0;
                            $PengajuanSiswaMagang->created_by            = auth_data()->pengguna->id_pengguna;
                            $PengajuanSiswaMagang->save();

                            return [
                                'status' => 203, // SUCCESS AND LOAD TABLE
                                'message' => 'Pengajuan Magang Successfully'
                            ];
                        } else {
                            return [
                                'status' => 203, // GAGAL
                                'message' => 'Setting Periode magang Dengan Benar!'
                            ];
                        }
                    }

                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Pengajuan magang Gagal!'
                    ];
                }
            } elseif ($mode == 'approve') {
                try {
                    // get status_pengguna kode AKTIF
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna', '=', "AKTIF")
                        ->where('status_join_table', '=', 3)
                        ->where('id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                        ->first();

                    // get siswa->id_pengguna
                    $siswa  = Siswa::find($id_siswa);
                    $PengajuanSiswaMagang        = PengajuanSiswaMagang::find($id);
                    if ($id_periode_magang != "0") {

                        // -- INSERT tabel pengambilan_magang --
                        $PengajuanSiswaMagang->status_magang         = 0;
                        $PengajuanSiswaMagang->status_apv_pengambilan_magang = 1;
                        $PengajuanSiswaMagang->updated_by            = auth_data()->pengguna->id_pengguna;
                        $PengajuanSiswaMagang->save();

                        return [
                            'status' => 203, // SUCCESS AND LOAD TABLE
                            'message' => 'Approve Magang Berhasil'
                        ];
                    } else {
                        // cek periode magang aktif sesuai semester aktif
                        $periodeMagang = PeriodeMagang::join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
                            ->where('periode_magang.is_aktif', '=', 1)
                            ->where('semester.is_aktif_semester', '=', 1)
                            ->first();

                        if (!empty($periodeMagang->id_periode_magang)) {

                            // -- INSERT tabel pengambilan_magang --
                            $PengajuanSiswaMagang->status_magang         = 0;
                            $PengajuanSiswaMagang->status_apv_pengambilan_magang = 1;
                            $PengajuanSiswaMagang->updated_by           = auth_data()->pengguna->id_pengguna;
                            $PengajuanSiswaMagang->save();

                            return [
                                'status' => 203, // SUCCESS AND LOAD TABLE
                                'message' => 'Approve Magang Successfully'
                            ];
                        } else {
                            return [
                                'status' => 203, // GAGAL
                                'message' => 'Setting Periode magang Dengan Benar!'
                            ];
                        }
                    }

                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Approve Magang Gagal!'
                    ];
                }
            }
        }
    }
}
