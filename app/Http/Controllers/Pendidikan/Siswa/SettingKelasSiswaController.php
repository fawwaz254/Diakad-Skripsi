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
use App\Models\LogKelasSiswa as LogKelasSiswa;
use App\Models\TagihanBiaya;

use Auth;
use DB;
use Session;
use Validator;

class SettingKelasSiswaController extends BaseController
{
    public function viewSettingKelasSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        return view('pendidikan/siswa/setting-kelas-siswa/view-kelas-setting-kelas-siswa', compact('auth_data', 'data_kelas'));
    }

    public function actionViewSettingKelasSiswa(Request $request)
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
                'path' => 'siswa/setting-kelas-siswa/view-kelas/' . $input->id_kelas
            ];
        }
    }

    public function viewKelasSettingKelas(Request $request, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')
            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('kelas.id_kelas', '!=', $id_kelas)
            ->get();

        $kelas = Kelas::where('id_kelas', '=', $id_kelas)->first();

        return view('pendidikan/siswa/setting-kelas-siswa/view-setting-kelas-siswa', compact('auth_data', 'data_kelas', 'kelas'));
    }

    public function tambahKelasSiswa(Request $request, $id_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')
            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();
        $siswa = Siswa::join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('siswa.id_siswa', '=', $id_siswa)
            ->first();

        return view('pendidikan/siswa/setting-kelas-siswa/tambah-siswa-kelas', compact('auth_data', 'data_kelas', 'siswa'));
    }

    public function datatablesKelasSiswa(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Siswa::join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
            ->where('id_kelas', '=', $id_kelas)
            ->where('status_pengguna.aktif_status_pengguna', '=', 1)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('nis_siswa', 'asc')->get();
        return Datatables::of($list_data)
            ->addColumn('nisn_siswa', function ($item) {
                return $item->nisn_siswa;
            })
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = Siswa::join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
            ->where('id_kelas', '=', NULL)
            ->where('status_pengguna.aktif_status_pengguna', '=', 1)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();

        return Datatables::of($list_data)
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

    public function actionSettingKelasSiswa(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if ($mode == 'tambah-siswa') {
                $siswa                = Siswa::find($id);
                $siswa->id_kelas    = $input->id_kelas;
                $siswa->save();

                $logKelasSiswa                          = new LogKelasSiswa;
                $logKelasSiswa->id_log_kelas_siswa      = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $logKelasSiswa->id_siswa                = $id;
                $logKelasSiswa->id_kelas                = $input->id_kelas;
                $logKelasSiswa->created_by              = $input->auth_data->pengguna->id_pengguna;
                $logKelasSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'siswa/setting-kelas-siswa',
                    'message' => 'Tambah Siswa ke Kelas Berhasil'
                ];
            } elseif ($mode == 'ganti-kelas') {
                DB::beginTransaction();

                try {
                    foreach ($input->id_siswa as $id_siswa) {
                        $siswa                 = Siswa::where('id_siswa', '=', $id_siswa)->first();

                        $kelas_sebelumnya = $siswa->id_kelas;

                        $siswa->id_kelas    = $input->id_kelas;
                        $siswa->save();

                        // ubah tagihan siswa juga ketika status pindah = 1

                        if ($input->status_pindah == 1) {

                            $tagihan_biaya = TagihanBiaya::where([
                                'id_siswa' => $id_siswa,
                                'id_kelas' => $kelas_sebelumnya
                            ])->get();

                            foreach ($tagihan_biaya as $r) {

                                $tagihan                   = TagihanBiaya::find($r->id_tagihan_biaya);
                                $tagihan->id_kelas         = $input->id_kelas;
                                $tagihan->updated_by       = $input->auth_data->pengguna->id_pengguna;
                                $tagihan->save();
                            }
                        }

                        $logKelasSiswa                          = new LogKelasSiswa;
                        $logKelasSiswa->id_log_kelas_siswa      = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $logKelasSiswa->id_siswa                = $id_siswa;
                        $logKelasSiswa->id_kelas                = $input->id_kelas;
                        $logKelasSiswa->created_by              = $input->auth_data->pengguna->id_pengguna;
                        $logKelasSiswa->save();
                    }
                    DB::commit();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Siswa Berhasil Pindah Kelas',
                        'path' => 'siswa/setting-kelas-siswa/view-kelas/' . $id
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => 'Siswa Gagal Pindah Kelas'
                    ];
                }
            }
        }
    }
}
