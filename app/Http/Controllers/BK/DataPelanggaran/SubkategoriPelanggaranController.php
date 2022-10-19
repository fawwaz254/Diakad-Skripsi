<?php

namespace App\Http\Controllers\BK\DataPelanggaran;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\SubkategoriPelanggaran as SubkategoriPelanggaran;
use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class SubkategoriPelanggaranController extends BaseController
{
    public function viewSubkategoriPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('bk/data-pelanggaran/subkategori-pelanggaran/view-subkategori-pelanggaran', compact('auth_data'));
    }

    public function addSubkategoriPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_kategori_pelanggaran = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        $id_subkategori_pelanggaran = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/data-pelanggaran/subkategori-pelanggaran/add-subkategori-pelanggaran', compact('auth_data', 'data_kategori_pelanggaran', 'id_subkategori_pelanggaran'));
    }

    public function editSubkategoriPelanggaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kategori_pelanggaran = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        $data_subkategori_pelanggaran = LibDataPelanggaran::fetchDataSubkategoriPelanggaran($auth_data, $id);

        return view('bk/data-pelanggaran/subkategori-pelanggaran/edit-subkategori-pelanggaran', compact('auth_data', 'data_kategori_pelanggaran', 'data_subkategori_pelanggaran'));
    }

    public function datatablesSubkategoriPelanggaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataPelanggaran::fetchDataSubkategoriPelanggaran($auth_data);

        return Datatables::of($list_data)
                ->editColumn('nm_subkategori_pelanggaran', function ($item) {
                    return strip_tags($item->nm_subkategori_pelanggaran);
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_subkategori_pelanggaran
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionSubkategoriPelanggaran(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kategori_pelanggaran' => 'required',
            'nm_subkategori_pelanggaran' => 'required',
            'tingkat_subkategori_pelanggaran' => 'required',
            'poin_subkategori_pelanggaran' => 'required',
            'keterangan_subkategori_pelanggaran' => 'required'
        ]);
        
        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $subKategoriPelanggaran                                       = new SubkategoriPelanggaran;
                $subKategoriPelanggaran->id_subkategori_pelanggaran           = $id;
                $subKategoriPelanggaran->id_kategori_pelanggaran              = $input->id_kategori_pelanggaran;
                $subKategoriPelanggaran->nm_subkategori_pelanggaran           = $input->nm_subkategori_pelanggaran;
                $subKategoriPelanggaran->tingkat_subkategori_pelanggaran      = $input->tingkat_subkategori_pelanggaran;
                $subKategoriPelanggaran->poin_subkategori_pelanggaran         = $input->poin_subkategori_pelanggaran;
                $subKategoriPelanggaran->keterangan_subkategori_pelanggaran   = $input->keterangan_subkategori_pelanggaran;
                $subKategoriPelanggaran->created_by                           = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/subkategori-pelanggaran',
                    'message' => 'Save Sub-Kategori Pelanggaran Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $subKategoriPelanggaran                                       = SubkategoriPelanggaran::find($id);
                $subKategoriPelanggaran->id_kategori_pelanggaran              = $input->id_kategori_pelanggaran;
                $subKategoriPelanggaran->nm_subkategori_pelanggaran           = $input->nm_subkategori_pelanggaran;
                $subKategoriPelanggaran->tingkat_subkategori_pelanggaran      = $input->tingkat_subkategori_pelanggaran;
                $subKategoriPelanggaran->poin_subkategori_pelanggaran         = $input->poin_subkategori_pelanggaran;
                $subKategoriPelanggaran->keterangan_subkategori_pelanggaran   = $input->keterangan_subkategori_pelanggaran;
                $subKategoriPelanggaran->updated_by                           = $input->auth_data->pengguna->id_pengguna;
                $subKategoriPelanggaran->updated_at                           = $now;
                $subKategoriPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/subkategori-pelanggaran',
                    'message' => 'Update Sub-Kategori Pelanggaran Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($pelanggaranSiswa = PelanggaranSiswa::where('id_subkategori_pelanggaran', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Sub-Kategori Pelanggaran'
                    ];
                } else {
                    // make object to find id
                    $subKategoriPelanggaran               = SubkategoriPelanggaran::find($id);
                    $subKategoriPelanggaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $subKategoriPelanggaran->save();

                    $subKategoriPelanggaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Sub-Kategori Pelanggaran Successfully'
                    ];
                }
            }
        }
    }
}
