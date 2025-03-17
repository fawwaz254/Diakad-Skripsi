<?php

namespace App\Http\Controllers\Kesiswaan\Ijazah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Kesiswaan\LibIjazah;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Ijazah;
use App\Models\PengajuanWisuda;
use App\Models\Siswa;
use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;

class PengambilanIjazahController extends BaseController
{

    public function viewPengambilanIjazah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/ijazah/pengambilan-ijazah/view-pengambilan-ijazah', compact('auth_data'));
    }

    public function addPengambilanIjazah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaLulus($auth_data);

        return view('kesiswaan/ijazah/pengambilan-ijazah/detail-pengambilan-ijazah', compact('auth_data', 'siswa'));
    }

    public function editPengambilanIjazah($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaLulus($auth_data);
        $ijazah = LibIjazah::fetchDataPengambilanIjazah($auth_data, $id);
        // dd($ijazah);

        return view('kesiswaan/ijazah/pengambilan-ijazah/detail-pengambilan-ijazah', compact('auth_data', 'siswa', 'ijazah'));
    }

    public function datatablesPengambilanIjazah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibIjazah::fetchDataPengambilanIjazah($auth_data);

        return Datatables::of($list_data)
            ->addColumn('penerima_ijazah', function ($item) {
                if ($item->penerima_ijazah == null) {
                    return $item->nm_siswa;
                } else {
                    return $item->penerima_ijazah;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_ijazah
                );
                return $data;
            })
            ->make(true);
    }
    public function datatablesPengambilanIjazahSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $ijazah = PengajuanWisuda::pluck('id_siswa')->toArray();
        // dd($ijazah);
        $list_data = Siswa::join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->whereIn('id_siswa', $ijazah);
        // dd($list_data);
        return Datatables::of($list_data)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'nis_siswa' => $item->nis_siswa
                );
                return $data;
            })
            ->addColumn('nm_siswa', function ($item) {
                return $item->nis_siswa . '-' . $item->nm_pengguna;
            })
            ->make(true);
    }

    public function printPengambilanIjazah(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $ijazah = LibIjazah::fetchDataPengambilanIjazah($auth_data, $id);

        return view('kesiswaan/ijazah/pengambilan-ijazah/print-pengambilan-ijazah', compact('auth_data', 'ijazah'));
    }

    // Action POST
    public function actionPengambilanIjazah(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        // formating date
        if ($mode != 'delete') {
            $input->tgl_pengambilan_ijazah = Carbon::createFromFormat('H:i - d F Y', $input->tgl_pengambilan_ijazah);
        }

        $idSiswaSudahAmbil = [];
        // if ($mode == 'add') {
        //     $idSiswaSudahAmbil = Ijazah::pluck('id_siswa')->toArray();
        //     $array = preg_split("/\r\n|\n|\r/", $input->id_siswa);
        //     foreach ($array as $ar) {
        //         dd($ar);
        //     }
        // }

        $validator = Validator::make($request->all(), [
            // 'id_siswa' => [
            //     'required',
            //     'exists:siswa,id_siswa',
            //     Rule::notIn($idSiswaSudahAmbil)
            // ],
            'tgl_pengambilan_ijazah' => 'required|date_format:H:i - d F Y',
            'penerima_ijazah' => 'required_if:is_diwakilkan,1',
            'catatan_ijazah' => 'nullable'
        ], [
            'id_siswa.not_in' => 'Siswa sudah pernah mengambil Ijazah'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                // $data_nis_siswa = preg_split("/\r\n|\n|\r/", $input->id_siswa);

                $list_siswa = Siswa::get();
                $list_ijazah = Ijazah::get();
                foreach ($input->nis_siswa as $nis_siswa) {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                    $id_siswa = $list_siswa->where('nis_siswa', $nis_siswa)->first()->id_siswa;
                    if ($list_ijazah->where('id_siswa', $id_siswa)->first()) {
                    } else {
                        $ijazah                             = new Ijazah;
                        $ijazah->id_ijazah                  = $id;
                        $ijazah->id_siswa                   = $id_siswa;
                        $ijazah->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                        $ijazah->tgl_pengambilan_ijazah     = $input->tgl_pengambilan_ijazah;
                        $ijazah->penerima_ijazah            = $input->penerima_ijazah; // null jika tidak diwakilkan
                        $ijazah->id_pemberi_ijazah          = $input->auth_data->pengguna->id_pengguna;
                        $ijazah->catatan_ijazah             = $input->catatan_ijazah;
                        $ijazah->created_by                 = $input->auth_data->pengguna->id_pengguna;
                        $ijazah->save();
                    }
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ijazah/pengambilan-ijazah',
                    'message' => 'Save Pengambilan Ijazah Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $ijazah                             = Ijazah::find($id);
                $ijazah->id_siswa                   = $input->id_siswa;
                $ijazah->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                $ijazah->tgl_pengambilan_ijazah     = $input->tgl_pengambilan_ijazah;
                if (isset($input->is_diwakilkan) && $input->is_diwakilkan == 1) {
                    $ijazah->penerima_ijazah            = $input->penerima_ijazah; // null jika tidak diwakilkan
                } else {
                    $ijazah->penerima_ijazah            = null; // null jika tidak diwakilkan
                }
                $ijazah->id_pemberi_ijazah          = $input->auth_data->pengguna->id_pengguna;
                $ijazah->catatan_ijazah             = $input->catatan_ijazah;
                $ijazah->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $ijazah->updated_at                 = $now;
                $ijazah->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ijazah/pengambilan-ijazah',
                    'message' => 'Update Pengambilan Ijazah Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $ijazah               = Ijazah::find($id);
                $ijazah->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $ijazah->save();

                $ijazah->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Pengambilan Ijazah Successfully'
                ];
            }
        }
    }
}
