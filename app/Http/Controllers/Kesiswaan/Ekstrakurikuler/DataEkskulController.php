<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul as Ekskul;
use App\Models\PelatihEkskulSet as PelatihEkskulSet;
use App\Models\EkskulWajib as EkskulWajib;

use Auth;
use DB;
use Session;
use Validator;

class DataEkskulController extends BaseController
{
    public function viewDataEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('kesiswaan/ekstrakurikuler/data-ekskul/view-data-ekskul', compact('auth_data'));
    }

    public function addDataEkskul(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/ekstrakurikuler/data-ekskul/add-data-ekskul', compact('auth_data'));
    }

    public function editDataEkskul(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $ekskul = Ekskul::where('id_ekskul', '=', $id)->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/ekstrakurikuler/data-ekskul/edit-data-ekskul', compact('auth_data', 'ekskul'));
    }

    public function datatablesDataEkskul(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
            ->addColumn('tgl_sk_ekskul', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_sk_ekskul));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_ekskul
                );
                return $data;
            })
            ->make(true);
    }
    public function actionDataEkskul(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_ekskul'         => 'required',
            'nomor_sk_ekskul'    => 'required',
            'tgl_sk_ekskul'        => 'required'
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

                $ekskul                     = new Ekskul;
                $ekskul->id_ekskul        = $id;
                $ekskul->nm_ekskul         = $input->nm_ekskul;
                $ekskul->nomor_sk_ekskul = $input->nomor_sk_ekskul;
                $ekskul->tgl_sk_ekskul     = date_format(date_create($input->tgl_sk_ekskul), "Y-m-d");
                $ekskul->created_by        = $input->auth_data->pengguna->id_pengguna;
                $ekskul->id_sekolah      = $auth_data->pengguna->id_sekolah;
                $ekskul->created_at        = $now;
                $ekskul->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/data-ekskul',
                    'message' => 'Save Data Ekskul Successfully'
                ];
            } elseif ($mode == 'edit') {
                $ekskul                     = Ekskul::find($id);
                $ekskul->nm_ekskul         = $input->nm_ekskul;
                $ekskul->nomor_sk_ekskul = $input->nomor_sk_ekskul;
                $ekskul->tgl_sk_ekskul     = date_format(date_create($input->tgl_sk_ekskul), "Y-m-d");
                $ekskul->updated_by        = $input->auth_data->pengguna->id_pengguna;
                $ekskul->updated_at        = $now;
                $ekskul->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/data-ekskul',
                    'message' => 'Save Data Ekskul Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($pelatih = PelatihEkskulSet::where('id_ekskul', '=', $id)->first() && $wajib = EkskulWajib::where('id_ekskul', '=', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Data Ekskul'
                    ];
                } else {
                    // make object to find id
                    $ekskul                 = Ekskul::find($id);
                    $ekskul->deleted_by     = $input->auth_data->pengguna->id_pengguna;
                    $ekskul->save();

                    $ekskul->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Ekskul Successfully'
                    ];
                }
            }
        }
    }
}
