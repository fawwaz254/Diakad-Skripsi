<?php

namespace App\Http\Controllers\Akademik\MGMP;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JenisMGMP;
use Validator;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
class JenisMGMPcontroller extends Controller
{
    
    
    public function viewDataJenis(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_pengguna;

        return view('akademik/mgmp/data-jenis/view-data-jenis', compact('auth_data'));
    }

    public function datatablesjenis(Request $request)
    {
        
        $list_data = '';
        $list_data = JenisMGMP::all();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jenis_MGMP
                );
                return $data;
            })->make(true);
    }
    public function addDataJenis(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/mgmp/data-jenis/add-data-jenis', compact('auth_data'));
    }

    public function actionDataJenis(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'jenis_mgmp' => 'required',
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            //mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $data_jenis                               = new JenisMGMP();
                    $data_jenis->id_jenis_MGMP                = $id;
                    $data_jenis->jenis_MGMP                   = $input->jenis_mgmp;
                    $data_jenis->created_by                   = $input->auth_data->pengguna->id_pengguna;
                    $data_jenis->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'mpmp/jenis-mgmp',
                        'message' => 'Save Data Jenis Succesfully'
                    ];
                } elseif ($mode == 'delete') {
                    // make object to find id 
                    $data_jenis                       = JenisMGMP::where('id_jenis_MGMP', $id)->first();
                    $data_jenis->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                    $data_jenis->save();
                    $data_jenis->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Jenis succesfully'

                    ];
                }
        }
    }
}


