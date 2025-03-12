<?php

namespace App\Http\Controllers\Kesiswaan\Ekstrakurikuler;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Ekskul as Ekskul;
use App\Models\PelatihEkskulSet as PelatihEkskulSet;
use App\Models\Kelas as Kelas;
use App\Models\EkskulWajib as EkskulWajib;

use Auth;
use DB;
use Session;
use Validator;

class EkskulWajibController extends BaseController
{
    public function viewEkskulWajib(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('kesiswaan/ekstrakurikuler/ekskul-wajib/view-ekskul-wajib', compact('auth_data'));
    }

    public function addEkskulWajib(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now    = Carbon::now();
        $ekskul   = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $tingkat  = Kelas::select('tingkat')->distinct()->get();
        // $id_jenis_mata_pelajaran = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/ekstrakurikuler/ekskul-wajib/add-ekskul-wajib', compact('auth_data', 'ekskul', 'tingkat'));
    }

    public function editEkskulWajib(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now    = Carbon::now();
        $ekskulWajib  = EkskulWajib::join('ekskul', 'ekskul.id_ekskul', '=', 'ekskul_wajib.id_ekskul')->where('id_ekskul_wajib', '=', $id)->first();
        $ekskul   = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $tingkat  = Kelas::select('tingkat')->distinct()->get();
        // $id_jenis_mata_pelajaran = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/ekstrakurikuler/ekskul-wajib/edit-ekskul-wajib', compact('auth_data', 'ekskul', 'tingkat', 'ekskulWajib'));
    }

    public function datatablesEkskulWajib(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = EkskulWajib::join('ekskul', 'ekskul.id_ekskul', '=', 'ekskul_wajib.id_ekskul')->where('ekskul.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
            ->addColumn('is_aktif', function ($item) {
                if ($item->is_aktif == 0) {
                    return "Tidak Aktif";
                } else {
                    return "Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_ekskul_wajib
                );
                return $data;
            })
            ->make(true);
    }

    public function actionEkskulWajib(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'id_ekskul'         => 'required',
            'tingkat_kelas'            => 'required',
            'is_aktif'        => 'required'
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

                $ekskul                             = new EkskulWajib;
                $ekskul->id_ekskul_wajib            = $id;
                $ekskul->id_ekskul                = $input->id_ekskul;
                $ekskul->tingkat_kelas             = $input->tingkat_kelas;
                $ekskul->is_aktif                 = $input->is_aktif;
                $ekskul->created_by                = auth_data()->pengguna->id_pengguna;
                $ekskul->created_at                = $now;
                $ekskul->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/ekskul-wajib',
                    'message' => 'Save Data Ekskul wajib Successfully'
                ];
            } elseif ($mode == 'edit') {
                $ekskul                             = EkskulWajib::find($id);
                $ekskul->id_ekskul           = $input->id_ekskul;
                $ekskul->tingkat_kelas   = $input->tingkat_kelas;
                $ekskul->is_aktif        = $input->is_aktif;
                $ekskul->updated_by          = auth_data()->pengguna->id_pengguna;
                $ekskul->updated_at          = $now;
                $ekskul->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'ekstrakurikuler/ekskul-wajib',
                    'message' => 'Save Data Ekskul Wajib Successfully'
                ];
            } elseif ($mode == 'delete') {
                $ekskul                       = EkskulWajib::find($id);
                $ekskul->deleted_by     = auth_data()->pengguna->id_pengguna;
                $ekskul->save();

                $ekskul->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Ekskul wajib Successfully'
                ];
            }
        }
    }
}
