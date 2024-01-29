<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Kegiatan as Kegiatan;
use App\Models\KodeKegiatan as KodeKegiatan;
use App\Models\JadwalKegiatan as JadwalKegiatan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class KegiatanController extends BaseController
{

    public function viewKegiatan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('pendidikan/data-akademik/kegiatan/view-kegiatan', compact('auth_data'));
    }

    public function addKegiatan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $data_kode_kegiatan = KodeKegiatan::orderBy('kode_kegiatan', 'asc')->get();

        $id_kegiatan = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('pendidikan/data-akademik/kegiatan/add-kegiatan', compact('auth_data', 'data_kode_kegiatan', 'id_kegiatan'));
    }

    public function editKegiatan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_kode_kegiatan = KodeKegiatan::orderBy('kode_kegiatan', 'asc')->get();

        $data_kegiatan = LibDataAkademik::fetchDataKegiatan($auth_data, $id);

        return view('pendidikan/data-akademik/kegiatan/edit-kegiatan', compact('auth_data', 'data_kode_kegiatan', 'data_kegiatan'));
    }

    public function datatablesKegiatan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataAkademik::fetchDataKegiatan($auth_data);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan
                );
                return $data;
            })
            ->make(true);
    }

    public function fetchDataKegiatan($auth_data, $id = null)
    {

        // get mode view
        if ($id == null) {
            $kegiatan = Kegiatan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_kegiatan', 'asc')->get();
        }
        // get mode edit
        else {
            $kegiatan = Kegiatan::where('id_kegiatan', '=', $id)->first();
        }

        return $kegiatan;
    }

    // Action POST
    public function actionKegiatan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kegiatan' => 'required',
            'deskripsi_kegiatan' => 'required',
            'kode_kegiatan' => 'required'
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

                $kegiatan                       = new Kegiatan;
                $kegiatan->id_kegiatan          = $id;
                $kegiatan->nm_kegiatan          = $input->nm_kegiatan;
                $kegiatan->deskripsi_kegiatan   = $input->deskripsi_kegiatan;
                $kegiatan->kode_kegiatan        = $input->kode_kegiatan;
                $kegiatan->id_sekolah           = $input->auth_data->pengguna->id_sekolah;
                $kegiatan->created_by           = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/kegiatan',
                    'message' => 'Save Kegiatan Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $kegiatan                       = Kegiatan::find($id);
                $kegiatan->nm_kegiatan          = $input->nm_kegiatan;
                $kegiatan->deskripsi_kegiatan   = $input->deskripsi_kegiatan;
                $kegiatan->kode_kegiatan        = $input->kode_kegiatan;
                $kegiatan->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->updated_at           = $now;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/kegiatan',
                    'message' => 'Update Kegiatan Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($jadwalKegiatan = JadwalKegiatan::where('id_kegiatan', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kegiatan'
                    ];
                } else {
                    // make object to find id
                    $kegiatan               = Kegiatan::find($id);
                    $kegiatan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kegiatan->save();

                    $kegiatan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kegiatan Successfully'
                    ];
                }
            }
        }
    }
}
