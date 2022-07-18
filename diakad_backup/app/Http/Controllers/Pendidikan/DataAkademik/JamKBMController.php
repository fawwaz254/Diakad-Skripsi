<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\JadwalJam as JadwalJam;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class JamKBMController extends BaseController{

    public function viewJamKBM(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('pendidikan/data-akademik/jam-kbm/view-jam-kbm',compact('auth_data'));

    }

    public function addJamKBM(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_jadwal_jam = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('pendidikan/data-akademik/jam-kbm/add-jam-kbm',compact('auth_data','id_jadwal_jam'));

    }

    public function editJamKBM($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jadwal_jam = $this->fetchDataJamKBM($auth_data, $id);

        return view('pendidikan/data-akademik/jam-kbm/edit-jam-kbm',compact('auth_data','data_jadwal_jam'));

    }

    public function datatablesJamKBM(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataJamKBM($auth_data);

        return Datatables::of($list_data)
                ->addColumn('jam_mulai', function($item){
                    return $item->jam_mulai.":".$item->menit_mulai;
                })
                ->addColumn('jam_selesai', function($item){
                    return $item->jam_selesai.":".$item->menit_selesai;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_jadwal_jam
                    );
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataJamKBM($auth_data, $id = null){

        // get mode view
        if ($id == null){
            $jadwalJam = JadwalJam::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('jam_ke', 'asc')->get();
        }
        // get mode edit
        else{
            $jadwalJam = JadwalJam::where('id_jadwal_jam','=',$id)->first();
        }

        return $jadwalJam;
    }

    // Action POST
    public function actionJamKBM(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_jadwal_jam' => 'required',
            'jam_ke' => 'required',
            'jam_menit_mulai' => 'required',
            'jam_menit_selesai' => 'required',
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $jadwalJam                      = new JadwalJam;
                $jadwalJam->id_jadwal_jam       = $id;
                $jadwalJam->nm_jadwal_jam       = $input->nm_jadwal_jam;
                $jadwalJam->jam_ke              = $input->jam_ke;
                $jadwalJam->jam_mulai           = substr($input->jam_menit_mulai,0,2);
                $jadwalJam->menit_mulai         = substr($input->jam_menit_mulai,3,4);
                $jadwalJam->jam_selesai         = substr($input->jam_menit_selesai,0,2);
                $jadwalJam->menit_selesai       = substr($input->jam_menit_selesai,3,4);
                $jadwalJam->id_sekolah          = $input->auth_data->pengguna->id_sekolah;
                $jadwalJam->created_by          = $input->auth_data->pengguna->id_pengguna;
                $jadwalJam->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jam-kbm',
                    'message' => 'Save Jam KBM successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $jadwalJam                      = JadwalJam::find($id);
                $jadwalJam->nm_jadwal_jam       = $input->nm_jadwal_jam;
                $jadwalJam->jam_ke              = $input->jam_ke;
                $jadwalJam->jam_mulai           = substr($input->jam_menit_mulai,0,2);
                $jadwalJam->menit_mulai         = substr($input->jam_menit_mulai,3,4);
                $jadwalJam->jam_selesai         = substr($input->jam_menit_selesai,0,2);
                $jadwalJam->menit_selesai       = substr($input->jam_menit_selesai,3,4);
                $jadwalJam->updated_by          = $input->auth_data->pengguna->id_pengguna;
                $jadwalJam->updated_at          = $now;
                $jadwalJam->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/jam-kbm',
                    'message' => 'Update Jam KBM successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($jadwalKelasMp = JadwalKelasMp::where('id_jadwal_jam',$id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Gagal mengapus data jam kbm , karena sudah digunakan pada jadwal kelas'
                    ]; 
                }
                else {
                    // make object to find id
                    $jadwalJam               = JadwalJam::find($id);
                    $jadwalJam->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $jadwalJam->save();

                    $jadwalJam->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jam KBM successfully'
                    ];
                }
            }
        }
    }

}