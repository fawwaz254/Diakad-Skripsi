<?php

namespace App\Http\Controllers\Keuangan\PemasukanSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PemasukanBiayaKategori as PemasukanBiayaKategori;
use App\Models\PemasukanBiayaSubkategori as PemasukanBiayaSubkategori;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class KategoriPemasukanController extends BaseController
{

    public function viewKategoriPemasukan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('keuangan/pemasukan-sekolah/kategori-pemasukan/view-kategori-pemasukan', compact('auth_data'));
    }

    public function addKategoriPemasukan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_pemasukan_biaya_kategori = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('keuangan/pemasukan-sekolah/kategori-pemasukan/add-kategori-pemasukan', compact('auth_data', 'id_pemasukan_biaya_kategori'));
    }

    public function editKategoriPemasukan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_kategori_pemasukan = LibDataKeuangan::fetchDataKategoriPemasukan($auth_data, $id);

        return view('keuangan/pemasukan-sekolah/kategori-pemasukan/edit-kategori-pemasukan', compact('auth_data', 'data_kategori_pemasukan'));
    }

    public function datatablesKategoriPemasukan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataKeuangan::fetchDataKategoriPemasukan($auth_data);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pemasukan_biaya_kategori
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionKategoriPemasukan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_pemasukan_biaya_kategori' => 'required',
            'keterangan_pemasukan_biaya_kategori' => 'required'
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

                $kategoriPemasukan                                            = new PemasukanBiayaKategori;
                $kategoriPemasukan->id_pemasukan_biaya_kategori             = $id;
                $kategoriPemasukan->nm_pemasukan_biaya_kategori             = $input->nm_pemasukan_biaya_kategori;
                $kategoriPemasukan->keterangan_pemasukan_biaya_kategori     = $input->keterangan_pemasukan_biaya_kategori;
                $kategoriPemasukan->id_sekolah                                = auth_data()->pengguna->id_sekolah;
                $kategoriPemasukan->created_by                                = auth_data()->pengguna->id_pengguna;
                $kategoriPemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/kategori-pemasukan',
                    'message' => 'Save Kategori Pemasukan Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $kategoriPemasukan                                            = PemasukanBiayaKategori::find($id);
                $kategoriPemasukan->nm_pemasukan_biaya_kategori             = $input->nm_pemasukan_biaya_kategori;
                $kategoriPemasukan->keterangan_pemasukan_biaya_kategori     = $input->keterangan_pemasukan_biaya_kategori;
                $kategoriPemasukan->updated_by                                = auth_data()->pengguna->id_pengguna;
                $kategoriPemasukan->updated_at                                = $now;
                $kategoriPemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/kategori-pemasukan',
                    'message' => 'Update Kategori Pemasukan Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($subkategoriPemasukan = PemasukanBiayaSubkategori::where('id_pemasukan_biaya_kategori', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kategori Pemasukan'
                    ];
                } else {
                    // make object to find id
                    $kategoriPemasukan               = PemasukanBiayaKategori::find($id);
                    $kategoriPemasukan->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $kategoriPemasukan->save();

                    $kategoriPemasukan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kategori Pemasukan Successfully'
                    ];
                }
            }
        }
    }
}
