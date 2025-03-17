<?php

namespace App\Http\Controllers\Sekretariat\DataSekretariat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipKategori as ArsipKategori;
use App\Models\ArsipSubkategori as ArsipSubkategori;

use Auth;
use DB;
use Session;
use Validator;

class DataKategoriController extends BaseController
{
    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sekretariat/data-sekretariat/data-kategori/view-data-kategori', compact('auth_data'));
    }

    public function addDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $unit = UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-sekretariat/data-kategori/add-data-kategori', compact('auth_data'));
    }

    public function editDataKategori(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $unit 	= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $arsip     = ArsipKategori::where('id_arsip_kategori', '=', $id)->first();

        return view('sekretariat/data-sekretariat/data-kategori/edit-data-kategori', compact('auth_data', 'arsip'));
    }

    public function datatablesDataKategori(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = ArsipKategori::where('arsip_kategori.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_arsip_kategori
                );
                return $data;
            })
            ->make(true);
    }

    public function actionDataKategori(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'nm_arsip_kategori' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // ACTION ADD
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $arsip                         = new ArsipKategori;
                $arsip->id_arsip_kategori    = $id;
                $arsip->nm_arsip_kategori    = $input->nm_arsip_kategori;
                $arsip->id_sekolah             = $auth_data->pengguna->id_sekolah;
                $arsip->created_at             = $now;
                $arsip->created_by            = $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-kategori',
                    'message' => 'Save Data Arsip Kategori Successfully'
                ];
            } elseif ($mode == 'edit') {
                $arsip                         = ArsipKategori::find($id);
                $arsip->nm_arsip_kategori    = $input->nm_arsip_kategori;
                $arsip->updated_at             = $now;
                $arsip->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sekretariat/data-kategori',
                    'message' => 'Save Data Arsip Kategori Successfully'
                ];
            } elseif ($mode == 'delete') {
                if (ArsipSubkategori::where('id_arsip_kategori', '=', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Arsip Kategori gagal'
                    ];
                } else {
                    $arsip     = ArsipKategori::find($id);
                    $arsip->deleted_by    = $input->auth_data->pengguna->id_pengguna;
                    $arsip->deleted_at     = $now;
                    $arsip->save();

                    $arsip->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Arsip Kategori Successfully'
                    ];
                }
            }
        }
    }
}
