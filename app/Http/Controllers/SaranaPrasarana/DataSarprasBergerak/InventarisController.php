<?php

namespace App\Http\Controllers\SaranaPrasarana\DataSarprasBergerak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InventarisBergerak;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use Auth;
use DB;
use Session;
use Validator;
use Excel;


class InventarisController extends Controller
{
    public function viewInventaris(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/data-sarpras-bergerak/view-inventaris', compact('auth_data'));
    }

    public function addInventaris(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        // $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $id_inventaris_ruangan = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/data-sarpras-bergerak/add-inventaris', compact('auth_data', 'id_inventaris_ruangan'));
    }

    public function editInventaris($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_inventaris_ruangan = InventarisBergerak::find($id);
        return view('sarana-prasarana/data-sarpras-bergerak/edit-inventaris', compact('auth_data', 'data_inventaris_ruangan'));
    }



    public function datatablesInventaris(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = InventarisBergerak::all();

        return Datatables::of($list_data)

            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_inventaris_bergerak
                );
                return $data;
            })
            ->make(true);
    }




    public function actionInventaris(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_inventaris_ruangan'             => 'required',
            // 'kode_inventaris_ruangan'           => 'required',
            // 'tgl_pembelian'                     => 'required',
            'jumlah_inventaris_ruangan'         => 'required',
            'jumlah_kondisi_baik'               => 'required',
            'jumlah_kondisi_rusak'              => 'required',
            'spesifikasi_inventaris_ruangan'    => 'required',
            'keterangan_inventaris_ruangan'     => 'required'
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
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $inventarisRuangan                                  = new InventarisBergerak;
                $inventarisRuangan->id_inventaris_bergerak           = $id;
                $inventarisRuangan->nm_inventaris_ruangan           = $input->nm_inventaris_ruangan;
                // $inventarisRuangan->kode_inventaris_ruangan         = $input->kode_inventaris_ruangan;
                // convert format date
                // $inventarisRuangan->tgl_pembelian                   = date_format(date_create($input->tgl_pembelian), "Y-m-d H:i:s");
                $inventarisRuangan->jumlah_inventaris_ruangan       = $input->jumlah_inventaris_ruangan;
                $inventarisRuangan->jumlah_kondisi_baik             = $input->jumlah_kondisi_baik;
                $inventarisRuangan->jumlah_kondisi_rusak            = $input->jumlah_kondisi_rusak;
                $inventarisRuangan->spesifikasi_inventaris_ruangan  = $input->spesifikasi_inventaris_ruangan;
                $inventarisRuangan->keterangan_inventaris_ruangan   = $input->keterangan_inventaris_ruangan;
                $inventarisRuangan->created_by                      = $input->auth_data->pengguna->id_pengguna;
                $inventarisRuangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-inventaris-bergerak/data-inventaris',
                    'message' => 'Save Inventaris Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $inventarisRuangan                                  = InventarisBergerak::find($id);
                // $inventarisRuangan->id_ruangan                      = $input->id_ruangan;
                $inventarisRuangan->nm_inventaris_ruangan           = $input->nm_inventaris_ruangan;
                // $inventarisRuangan->kode_inventaris_ruangan         = $input->kode_inventaris_ruangan;
                // // convert format date
                // $inventarisRuangan->tgl_pembelian                   = date_format(date_create($input->tgl_pembelian), "Y-m-d H:i:s");
                $inventarisRuangan->jumlah_inventaris_ruangan       = $input->jumlah_inventaris_ruangan;
                $inventarisRuangan->jumlah_kondisi_baik             = $input->jumlah_kondisi_baik;
                $inventarisRuangan->jumlah_kondisi_rusak            = $input->jumlah_kondisi_rusak;
                $inventarisRuangan->spesifikasi_inventaris_ruangan  = $input->spesifikasi_inventaris_ruangan;
                $inventarisRuangan->keterangan_inventaris_ruangan   = $input->keterangan_inventaris_ruangan;
                $inventarisRuangan->updated_by                      = $input->auth_data->pengguna->id_pengguna;
                $inventarisRuangan->updated_at                      = $now;
                $inventarisRuangan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-inventaris-bergerak/data-inventaris',
                    'message' => 'Update Inventaris Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $inventarisRuangan               = InventarisBergerak::find($id);
                $inventarisRuangan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $inventarisRuangan->save();

                $inventarisRuangan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Inventaris Successfully'
                ];
            }
        }
    }
}
