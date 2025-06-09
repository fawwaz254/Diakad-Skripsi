<?php

namespace App\Http\Controllers\SumberDaya\DataSumberDaya;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Pengguna as Pengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class StatusAktifGuruController extends BaseController
{

    public function viewStatusAktifGuru(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('sumber-daya/data-sumber-daya/status-aktif-guru/view-status-aktif-guru', compact('auth_data'));
    }

    public function addStatusAktifGuru(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_status_pengguna = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sumber-daya/data-sumber-daya/status-aktif-guru/add-status-aktif-guru', compact('auth_data', 'id_status_pengguna'));
    }

    public function editStatusAktifGuru($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_status_pengguna = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data, $id);

        return view('sumber-daya/data-sumber-daya/status-aktif-guru/edit-status-aktif-guru', compact('auth_data', 'data_status_pengguna'));
    }

    public function datatablesStatusAktifGuru(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataSumberDaya::fetchDataStatusAktifGuru($auth_data);

        return Datatables::of($list_data)
            ->addColumn('aktif_status_pengguna', function ($item) {
                if ($item->aktif_status_pengguna == 0) {
                    return "Keluar/Non-Aktif";
                } elseif ($item->aktif_status_pengguna == 1) {
                    return "Aktif";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_status_pengguna
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionStatusAktifGuru(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_status_pengguna'     => 'required',
            'aktif_status_pengguna'  => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $statusPengguna                         = new StatusPengguna;
                $statusPengguna->id_status_pengguna     = $id;
                $statusPengguna->status_join_table      = 2;
                $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                $statusPengguna->id_sekolah             = auth_data()->pengguna->id_sekolah;
                $statusPengguna->created_by             = auth_data()->pengguna->id_pengguna;
                $statusPengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/status-aktif-guru',
                    'message' => 'Save Status Aktif Guru Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $statusPengguna                         = StatusPengguna::find($id);
                $statusPengguna->nm_status_pengguna     = $input->nm_status_pengguna;
                $statusPengguna->aktif_status_pengguna  = $input->aktif_status_pengguna;
                $statusPengguna->updated_by             = auth_data()->pengguna->id_pengguna;
                $statusPengguna->updated_at             = $now;
                $statusPengguna->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/status-aktif-guru',
                    'message' => 'Update Status Aktif Guru Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($pengguna = Pengguna::where('id_status_pengguna', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Status Aktif Guru'
                    ];
                } else {
                    // make object to find id
                    $statusPengguna               = StatusPengguna::find($id);
                    $statusPengguna->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $statusPengguna->save();

                    $statusPengguna->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Status Aktif Guru Successfully'
                    ];
                }
            }
        }
    }
}
