<?php

namespace App\Http\Controllers\SumberDaya\DataSumberDaya;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\UnitKerja as UnitKerja;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class UnitKerjaController extends BaseController{

    public function viewUnitKerja(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sumber-daya/data-sumber-daya/unit-kerja/view-unit-kerja',compact('auth_data'));

    }

    public function addUnitKerja(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_unit_kerja_induk = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_unit_kerja = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sumber-daya/data-sumber-daya/unit-kerja/add-unit-kerja',compact('auth_data','data_unit_kerja_induk','id_unit_kerja'));

    }

    public function editUnitKerja($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_unit_kerja_induk = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data, $id);

        return view('sumber-daya/data-sumber-daya/unit-kerja/edit-unit-kerja',compact('auth_data','data_unit_kerja_induk','data_unit_kerja'));

    }

    public function datatablesUnitKerja(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_unit_kerja
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionUnitKerja(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_unit_kerja'         => 'required',
            'deskripsi_unit_kerja'  => 'required',
            //'tipe_unit_kerja'       => 'required',
            //'id_unit_kerja_induk'   => 'required',
            'nm_singkatan_unit'     => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $unitKerja                          = new UnitKerja;
                $unitKerja->id_unit_kerja           = $id;
                $unitKerja->nm_unit_kerja           = $input->nm_unit_kerja;
                $unitKerja->deskripsi_unit_kerja    = $input->deskripsi_unit_kerja;
                $unitKerja->tipe_unit_kerja         = $input->tipe_unit_kerja;
                $unitKerja->id_unit_kerja_induk     = $input->id_unit_kerja_induk;
                $unitKerja->nm_singkatan_unit       = $input->nm_singkatan_unit;
                $unitKerja->id_sekolah              = $input->auth_data->pengguna->id_sekolah;
                $unitKerja->created_by              = $input->auth_data->pengguna->id_pengguna;
                $unitKerja->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/unit-kerja',
                    'message' => 'Save Unit Kerja successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                $unitKerja                          = UnitKerja::find($id);
                $unitKerja->nm_unit_kerja           = $input->nm_unit_kerja;
                $unitKerja->deskripsi_unit_kerja    = $input->deskripsi_unit_kerja;
                $unitKerja->tipe_unit_kerja         = $input->tipe_unit_kerja;
                $unitKerja->id_unit_kerja_induk     = $input->id_unit_kerja_induk;
                $unitKerja->nm_singkatan_unit       = $input->nm_singkatan_unit;
                $unitKerja->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $unitKerja->updated_at              = $now;
                $unitKerja->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/unit-kerja',
                    'message' => 'Update Unit Kerja successfully'
                ];
            }
            elseif($mode == 'delete') {
                if($guru = Guru::where('id_unit_kerja',$id)->first() or $staff = Staff::where('id_unit_kerja',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Unit Kerja'
                    ]; 
                }
                else {
                    // make object to find id
                    $unitKerja               = UnitKerja::find($id);
                    $unitKerja->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $unitKerja->save();

                    $unitKerja->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Unit Kerja successfully'
                    ];
                }
            }
        }
    }

}