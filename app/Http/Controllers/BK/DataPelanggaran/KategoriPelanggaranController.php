<?php

namespace App\Http\Controllers\BK\DataPelanggaran;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KategoriPelanggaran as KategoriPelanggaran;
use App\Models\SubkategoriPelanggaran as SubkategoriPelanggaran;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class KategoriPelanggaranController extends BaseController
{

    public function viewKategoriPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('bk/data-pelanggaran/kategori-pelanggaran/view-kategori-pelanggaran', compact('auth_data'));
    }

    public function addKategoriPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_kategori_pelanggaran = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('bk/data-pelanggaran/kategori-pelanggaran/add-kategori-pelanggaran', compact('auth_data', 'id_kategori_pelanggaran'));
    }

    public function editKategoriPelanggaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_kategori_pelanggaran = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data, $id);

        return view('bk/data-pelanggaran/kategori-pelanggaran/edit-kategori-pelanggaran', compact('auth_data', 'data_kategori_pelanggaran'));
    }

    public function datatablesKategoriPelanggaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kategori_pelanggaran
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionKategoriPelanggaran(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kategori_pelanggaran' => 'required',
            'tingkat_kategori_pelanggaran' => 'required',
            'keterangan_kategori_pelanggaran' => 'required'
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
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $kategoriPelanggaran                                        = new KategoriPelanggaran;
                $kategoriPelanggaran->id_kategori_pelanggaran               = $id;
                $kategoriPelanggaran->nm_kategori_pelanggaran               = $input->nm_kategori_pelanggaran;
                $kategoriPelanggaran->tingkat_kategori_pelanggaran          = $input->tingkat_kategori_pelanggaran;
                $kategoriPelanggaran->keterangan_kategori_pelanggaran       = $input->keterangan_kategori_pelanggaran;
                $kategoriPelanggaran->id_sekolah                            = auth_data()->pengguna->id_sekolah;
                $kategoriPelanggaran->created_by                            = auth_data()->pengguna->id_pengguna;
                $kategoriPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/kategori-pelanggaran',
                    'message' => 'Save Kategori Pelanggaran Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $kategoriPelanggaran                                        = KategoriPelanggaran::find($id);
                $kategoriPelanggaran->nm_kategori_pelanggaran               = $input->nm_kategori_pelanggaran;
                $kategoriPelanggaran->tingkat_kategori_pelanggaran          = $input->tingkat_kategori_pelanggaran;
                $kategoriPelanggaran->keterangan_kategori_pelanggaran       = $input->keterangan_kategori_pelanggaran;
                $kategoriPelanggaran->updated_by                            = auth_data()->pengguna->id_pengguna;
                $kategoriPelanggaran->updated_at                            = $now;
                $kategoriPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-pelanggaran/kategori-pelanggaran',
                    'message' => 'Save Kategori Pelanggaran Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($subkategoriPelanggaran = SubkategoriPelanggaran::where('id_subkategori_pelanggaran', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kategori Pelanggaran'
                    ];
                } else {
                    // make object to find id
                    $kategoriPelanggaran               = KategoriPelanggaran::find($id);
                    $kategoriPelanggaran->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $kategoriPelanggaran->save();

                    $kategoriPelanggaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kategori Pelanggaran Successfully'
                    ];
                }
            }
        }
    }
}
