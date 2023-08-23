<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Siswa as Siswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class BiayaSiswaController extends BaseController
{
    public function viewBiayaSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/utility/biaya-siswa/view-biaya-siswa', compact('auth_data'));
    }

    public function viewBiayaSiswaByKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        return view('keuangan/utility/biaya-siswa-by-kelas/view-biaya-siswa-by-kelas', compact('auth_data', 'data_kelas', 'data_kelompok_biaya'));
    }

    public function setBiayaSiswa($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // ambil data kelompok biaya
        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_biaya_siswa = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, null, $id);

        return view('keuangan/utility/biaya-siswa/set-biaya-siswa', compact('auth_data', 'data_kelompok_biaya', 'data_biaya_siswa'));
    }

    public function editBiayaSiswa($id, Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data kelompok biaya
        $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

        $data_biaya_siswa = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, null, $id);

        return view('keuangan/utility/biaya-siswa/edit-biaya-siswa', compact('auth_data', 'data_kelompok_biaya', 'data_biaya_siswa'));
    }

    public function datatablesBiayaSiswaBelum(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (!empty($input->id_kelas)) {
            if ($input->id_kelas == 'notset') {
                $list_data = array();
            } else {
                $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 0, null, $input->id_kelas, "1");
            }
        } else {
            $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 0, null, null, "1");
        }

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->editColumn('jenis_kelamin', function ($item) {
                if ($item->jenis_kelamin == 1) {
                    return 'Laki-Laki';
                } else if ($item->jenis_kelamin == 2) {
                    return 'Perempuan';
                } else {
                    return 'Belum diset';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesBiayaSiswaSudah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (!empty($input->id_kelas)) {
            if ($input->id_kelas == 'notset') {
                $list_data = array();
            } else {
                $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 1, null, $input->id_kelas, "1");
            }
        } else {
            $list_data = LibDataKeuangan::fetchDataBiayaSiswa($auth_data, 1, null, null, "1");
        }

        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->editColumn('jenis_kelamin', function ($item) {
                if ($item->jenis_kelamin == 1) {
                    return 'Laki-Laki';
                } else if ($item->jenis_kelamin == 2) {
                    return 'Perempuan';
                } else {
                    return 'Belum diset';
                }
            })
            ->addColumn('kelompok_biaya', function ($item) {
                if ($item->status_kelompok_biaya == 1) {
                    return $item->nm_kelompok_biaya;
                } else {
                    return $item->nm_kelompok_biaya;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionBatchBiayaSiswa(Request $request, $mode)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya'     => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'set') {
                if (!isset($input->id_siswa)) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Pilih Siswa Terlebih Dahulu'
                    ];
                }

                $id_siswa_collection = collect($input->id_siswa);

                foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                    foreach ($chunk_id_siswa as $id_siswa) {
                        $siswa                          = Siswa::find($id_siswa);
                        $siswa->id_kelompok_biaya       = $input->id_kelompok_biaya;
                        $siswa->save();
                    }
                }

                return [
                    'status' => 200, // SUCCESS AND LOAD CONTENT
                    'message' => 'Save Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {

                if (isset($input->id_siswa)) {
                    $id_siswa_collection = collect($input->id_siswa);

                    foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                        foreach ($chunk_id_siswa as $id_siswa) {
                            $siswa                          = Siswa::find($id_siswa);
                            $siswa->id_kelompok_biaya       = $input->id_kelompok_biaya;
                            $siswa->save();
                        }
                    }

                    return [
                        'status' => 200, // SUCCESS AND LOAD CONTENT
                        'message' => 'Update Biaya Siswa Successfully'
                    ];
                }
                return [
                    'status' => 300, // FAILED
                    'message' => 'Pilih Siswa Terlebih Dahulu'
                ];
            } elseif ($mode == 'delete') {
                $id_siswa_collection = collect($input->id_siswa);

                foreach ($id_siswa_collection->chunk(25) as $chunk_id_siswa) {
                    foreach ($chunk_id_siswa as $id_siswa) {
                        $siswa                          = Siswa::find($id_siswa);
                        $siswa->id_kelompok_biaya       = null;
                        $siswa->save();
                    }
                }

                return [
                    'status' => 200, // SUCCESS AND LOAD CONTENT
                    'message' => 'Delete Biaya Siswa Successfully'
                ];
            }
        }
    }

    // Action POST
    public function actionBiayaSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelompok_biaya'     => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'set') {
                // make object to find id
                $siswa                          = Siswa::find($id);
                $siswa->id_kelompok_biaya       = $input->id_kelompok_biaya;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/biaya-siswa',
                    'message' => 'Save Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $siswa                          = Siswa::find($id);
                $siswa->id_kelompok_biaya       = $input->id_kelompok_biaya;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/biaya-siswa',
                    'message' => 'Update Biaya Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $siswa                          = Siswa::find($id);
                $siswa->id_kelompok_biaya       = null;
                $siswa->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Biaya Siswa Successfully'
                ];
            }
        }
    }
}
