<?php

namespace App\Http\Controllers\Akademik\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\JenisMataPelajaran as JenisMataPelajaran;
use App\Models\MataPelajaran as MataPelajaran;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class DataJenisMataPelajaranController extends BaseController
{
    public function viewDataJenisMataPelajaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/data-akademik/data-jenis-mata-pelajaran/view-data-jenis-mata-pelajaran', compact('auth_data'));
    }

    public function addJenisMataPelajaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('akademik/data-akademik/data-jenis-mata-pelajaran/add-jenis-mata-pelajaran', compact('auth_data', 'id_jenis_mata_pelajaran'));
    }

    public function editJenisMataPelajaran(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $jenis_mata_pelajaran = JenisMataPelajaran::where('id_jenis_mata_pelajaran', '=', $id)->first();

        return view('akademik/data-akademik/data-jenis-mata-pelajaran/edit-jenis-mata-pelajaran', compact('auth_data', 'jenis_mata_pelajaran'));
    }

    public function datatablesJenisMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = JenisMataPelajaran::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jenis_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function actionJenisMataPelajaran(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'kode_jenis_mata_pelajaran'         => 'required',
            'nm_jenis_mata_pelajaran'            => 'required'
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

                $jenis_mata_pelajaran                                 = new JenisMataPelajaran;
                $jenis_mata_pelajaran->id_jenis_mata_pelajaran        = $id;
                $jenis_mata_pelajaran->id_sekolah                    = $auth_data->pengguna->id_sekolah;
                $jenis_mata_pelajaran->kode_jenis_mata_pelajaran    = $input->kode_jenis_mata_pelajaran;
                $jenis_mata_pelajaran->nm_jenis_mata_pelajaran        = $input->nm_jenis_mata_pelajaran;
                $jenis_mata_pelajaran->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $jenis_mata_pelajaran->created_at                    = $now;
                $jenis_mata_pelajaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jenis-mata-pelajaran',
                    'message' => 'Save Jenis Mata Pelajaran Successfully'
                ];
            } elseif ($mode == 'edit') {
                $jenis_mata_pelajaran                                 = JenisMataPelajaran::find($id);
                $jenis_mata_pelajaran->id_sekolah                    = $auth_data->pengguna->id_sekolah;
                $jenis_mata_pelajaran->kode_jenis_mata_pelajaran    = $input->kode_jenis_mata_pelajaran;
                $jenis_mata_pelajaran->nm_jenis_mata_pelajaran        = $input->nm_jenis_mata_pelajaran;
                $jenis_mata_pelajaran->updated_by                    = $input->auth_data->pengguna->id_pengguna;
                $jenis_mata_pelajaran->updated_at                    = $now;
                $jenis_mata_pelajaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jenis-mata-pelajaran',
                    'message' => 'Update Jenis Mata Pelajaran Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($mataPelajaran = MataPelajaran::where('id_jenis_mata_pelajaran', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jenis Mata Pelajaran'
                    ];
                } else {
                    // make object to find id
                    $jenis_mata_pelajaran                 = JenisMataPelajaran::find($id);
                    $jenis_mata_pelajaran->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                    $jenis_mata_pelajaran->save();

                    $jenis_mata_pelajaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jenis Mata Pelajaran Successfully'
                    ];
                }
            }
        }
    }
}
