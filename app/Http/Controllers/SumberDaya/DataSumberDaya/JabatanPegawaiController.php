<?php

namespace App\Http\Controllers\SumberDaya\DataSumberDaya;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\JabatanPegawai as JabatanPegawai;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use Auth;
use DB;
use Session;
use Validator;

class JabatanPegawaiController extends BaseController{

    public function viewJabatanPegawai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('sumber-daya/data-sumber-daya/jabatan-pegawai/view-jabatan-pegawai',compact('auth_data'));

    }

    public function addJabatanPegawai(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jabatan_pegawai = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('sumber-daya/data-sumber-daya/jabatan-pegawai/add-jabatan-pegawai',compact('auth_data','id_jabatan_pegawai'));

    }

    public function editJabatanPegawai($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jabatan_pegawai = $this->fetchDataJabatanPegawai($auth_data, $id);

        return view('sumber-daya/data-sumber-daya/jabatan-pegawai/edit-jabatan-pegawai',compact('auth_data','data_jabatan_pegawai'));

    }

    public function datatablesJabatanPegawai(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataJabatanPegawai($auth_data);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jabatan_pegawai
                    );
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataJabatanPegawai($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jabatanPegawai = JabatanPegawai::select('jabatan_pegawai.id_jabatan_pegawai', 'jabatan_pegawai.nm_jabatan_pegawai', 'jabatan_pegawai.deskripsi_jabatan_pegawai', 'jabatan_pegawai.tipe_jabatan_pegawai', 'jabatan_pegawai.kode_jabatan_pegawai')
                    ->where('jabatan_pegawai.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->orderBy('jabatan_pegawai.nm_jabatan_pegawai', 'asc')
                    ->get();
        }
        // get mode edit
        else{
            $jabatanPegawai = JabatanPegawai::where('jabatan_pegawai.id_jabatan_pegawai','=',$id)->first();
        }

        return $jabatanPegawai;
    }


    // Action POST
    public function actionJabatanPegawai(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jabatan_pegawai'         => 'required',
            'deskripsi_jabatan_pegawai'  => 'required',
            'tipe_jabatan_pegawai'       => 'required',
            'kode_jabatan_pegawai'          => 'required'
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
                
                $jabatanPegawai                             = new JabatanPegawai;
                $jabatanPegawai->id_jabatan_pegawai         = $id;
                $jabatanPegawai->nm_jabatan_pegawai         = $input->nm_jabatan_pegawai;
                $jabatanPegawai->deskripsi_jabatan_pegawai  = $input->deskripsi_jabatan_pegawai;
                $jabatanPegawai->tipe_jabatan_pegawai       = $input->tipe_jabatan_pegawai;
                $jabatanPegawai->kode_jabatan_pegawai       = $input->kode_jabatan_pegawai;
                $jabatanPegawai->id_sekolah                 = $input->auth_data->pengguna->id_sekolah;
                $jabatanPegawai->created_by                 = $input->auth_data->pengguna->id_pengguna;
                $jabatanPegawai->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/jabatan-pegawai',
                    'message' => 'Save Jabatan Pegawai successfully'
                ];
            }
            elseif($mode == 'edit') {
                // make object to find id
                $jabatanPegawai                             = JabatanPegawai::find($id);
                $jabatanPegawai->nm_jabatan_pegawai         = $input->nm_jabatan_pegawai;
                $jabatanPegawai->deskripsi_jabatan_pegawai  = $input->deskripsi_jabatan_pegawai;
                $jabatanPegawai->tipe_jabatan_pegawai       = $input->tipe_jabatan_pegawai;
                $jabatanPegawai->kode_jabatan_pegawai       = $input->kode_jabatan_pegawai;
                $jabatanPegawai->updated_by                 = $input->auth_data->pengguna->id_pengguna;
                $jabatanPegawai->updated_at                 = $now;
                $jabatanPegawai->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/jabatan-pegawai',
                    'message' => 'Update Jabatan Pegawai successfully'
                ];
            }
            elseif($mode == 'delete') {
                if($guru = Guru::where('id_jabatan_pegawai',$id)->first() or $staff = Staff::where('id_jabatan_pegawai',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Jabatan Pegawai'
                    ]; 
                }
                else {
                    // make object to find id
                    $jabatanPegawai               = JabatanPegawai::find($id);
                    $jabatanPegawai->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jabatanPegawai->save();

                    $jabatanPegawai->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jabatan Pegawai successfully'
                    ];
                }
            }
        }
    }

}