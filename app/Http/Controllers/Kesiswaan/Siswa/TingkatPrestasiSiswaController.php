<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;
use App\Models\PrestasiSiswa as PrestasiSiswa;

use Auth;
use DB;
use Session;
use Validator;

class TingkatPrestasiSiswaController extends BaseController
{
    public function viewTingkatPrestasiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('kesiswaan/siswa/tingkat-prestasi-siswa/view-tingkat-prestasi-siswa', compact('auth_data'));
    }

    public function addTingkatPrestasiSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        // $id_jenis_mata_pelajaran = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/tingkat-prestasi-siswa/add-tingkat-prestasi-siswa', compact('auth_data'));
    }

    public function editTingkatPrestasiSiswa(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();
        $tingkat_prestasi_siswa = TingkatPrestasiSiswa::where('id_tingkat_prestasi_siswa', '=', $id)->first();

        // $id_jenis_mata_pelajaran = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/siswa/tingkat-prestasi-siswa/edit-tingkat-prestasi-siswa', compact('auth_data', 'tingkat_prestasi_siswa'));
    }

    public function datatablesTingkatPrestasiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = TingkatPrestasiSiswa::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_tingkat_prestasi_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionTingkatPrestasiSiswa(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'nm_tingkat_prestasi_siswa'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // ACTION ADD
            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $tingkat                                 = new TingkatPrestasiSiswa;
                $tingkat->id_tingkat_prestasi_siswa        = $id;
                $tingkat->nm_tingkat_prestasi_siswa        = $input->nm_tingkat_prestasi_siswa;
                $tingkat->created_by                        = auth_data()->pengguna->id_pengguna;
                $tingkat->id_sekolah                      = $auth_data->pengguna->id_sekolah;
                $tingkat->created_at                        = $now;
                $tingkat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/tingkat-prestasi-siswa',
                    'message' => 'Save Data Tingkat Prestasi Siswa Successfully'
                ];
            } elseif ($mode == 'edit') {
                $tingkat                                 = TingkatPrestasiSiswa::find($id);
                $tingkat->nm_tingkat_prestasi_siswa        = $input->nm_tingkat_prestasi_siswa;
                $tingkat->updated_by                        = auth_data()->pengguna->id_pengguna;
                $tingkat->updated_at                        = $now;
                $tingkat->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/tingkat-prestasi-siswa',
                    'message' => 'Save Data Tingkat Prestasi Siswa Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($pelatih = PrestasiSiswa::where('id_tingkat_prestasi_siswa', '=', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Data Tingkat Prestasi Siswa'
                    ];
                } else {
                    // make object to find id
                    $tingkat                 = TingkatPrestasiSiswa::find($id);
                    $tingkat->deleted_by     = auth_data()->pengguna->id_pengguna;
                    $tingkat->save();

                    $tingkat->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Tingkat Prestasi Siswa Successfully'
                    ];
                }
            }
        }
    }
}
