<?php

namespace App\Http\Controllers\Keuangan\Rapb;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Rapb as Rapb;
use App\Models\Realisasi as Realisasi;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use App\Models\UnitKerja as UnitKerja;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class InputRapbController extends BaseController
{
    public function viewInputRapb(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

    	return view('keuangan/rapb/input-rapb/view-input-rapb',compact('auth_data','data_semester'));
  	}
  	
    public function actionViewInputRapb(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
            'id_semester_mulai'   => 'required',
            'id_semester_selesai'  => 'required'
        ]);

      if($validator->fails()) {
          return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
      }
      else {
          return [
                	'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai
                ];
     	}
  	}

  	public function viewDetailInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        
        return view('keuangan/rapb/input-rapb/view-detail-input-rapb',compact('auth_data','id_semester_mulai','id_semester_selesai','data_semester'));
    }

    public function datatablesInputRapb(Request $request, $id_semester_mulai, $id_semester_selesai){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, null, "1");

        return Datatables::of($list_data)
                ->addColumn('semester_mulai', function($item){
                    return $item->tahun_ajaran_mulai." (".$item->nm_semester_mulai.")";
                })
                ->addColumn('semester_selesai', function($item){
                    return $item->tahun_ajaran_selesai." (".$item->nm_semester_selesai.")";
                })
                ->addColumn('prioritas_rapb', function($item){
                    if($item->prioritas_rapb == 1) {
                      return "Rendah";
                    }
                    elseif($item->prioritas_rapb == 2) {
                      return "Sedang";
                    }
                    elseif($item->prioritas_rapb == 3) {
                      return "Tinggi";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionInputRapb(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester_mulai' => 'required',
            'id_semester_selesai' => 'required',
            'id_subkategori_rapb' => 'required',
            'id_unit_kerja' => 'required',
            'dana_perkiraan_rapb' => 'required',
            'tgl_rapb' => 'required',
            'prioritas_rapb' => 'required'
        ]);
        
        if($validator->fails() && in_array($mode, ['add','edit'])) {
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

                $rapb                         = new Rapb;
                $rapb->id_rapb                = $id;
                $rapb->id_semester_mulai      = $input->id_semester_mulai;
                $rapb->id_semester_selesai    = $input->id_semester_selesai;
                $rapb->id_subkategori_rapb    = $input->id_subkategori_rapb;
                $rapb->id_unit_kerja          = $input->id_unit_kerja;
                $rapb->dana_perkiraan_rapb    = $input->dana_perkiraan_rapb;
                $rapb->tgl_rapb               = $input->tgl_rapb;
                $rapb->prioritas_rapb         = $input->prioritas_rapb;
                $rapb->created_by             = $input->auth_data->pengguna->id_pengguna;
                $rapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai,
                    'message' => 'Input RAPB successfully'
                ];  
            }
            elseif($mode == 'edit') {
                // make object to find id
                $rapb                         = Rapb::find($id);
                $rapb->id_semester_mulai      = $input->id_semester_mulai;
                $rapb->id_semester_selesai    = $input->id_semester_selesai;
                $rapb->id_subkategori_rapb    = $input->id_subkategori_rapb;
                $rapb->id_unit_kerja          = $input->id_unit_kerja;
                $rapb->dana_perkiraan_rapb    = $input->dana_perkiraan_rapb;
                $rapb->tgl_rapb               = $input->tgl_rapb;
                $rapb->prioritas_rapb         = $input->prioritas_rapb;
                $rapb->updated_by             = $input->auth_data->pengguna->id_pengguna;
                $rapb->updated_at             = $now;
                $rapb->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/input-rapb/view-detail-input-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai,
                    'message' => 'Input RAPB successfully'
                ];  
            }
            elseif($mode == 'approve-kepala-unit') {
                $guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                              ->where('guru.id_unit_kerja', '=', $input->id_unit_kerja)
                              ->where('guru.jenis_jabatan', '=', 98)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                              ->where('guru.id_unit_kerja', '=', $input->id_unit_kerja)
                              ->where('guru.jenis_jabatan', '=', 98)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                if( ! empty($guru->id_pengguna)) {
                  // make object to find id
                  $rapb                           = Rapb::find($id);
                  $rapb->id_pengguna_kepala_unit  = $guru->id_pengguna;
                  $rapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                  $rapb->updated_at               = $now;
                  $rapb->save();

                  return [
                      'status' => 203, // SUCCESS AND LOAD CONTENT
                      'message' => 'Approve Kepala Unit successfully'
                  ]; 
                }    
                elseif(! empty($staff->id_pengguna)) {
                  // make object to find id
                  $rapb                           = Rapb::find($id);
                  $rapb->id_pengguna_kepala_unit  = $staff->id_pengguna;
                  $rapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                  $rapb->updated_at               = $now;
                  $rapb->save();

                  return [
                      'status' => 203, // SUCCESS AND LOAD CONTENT
                      'message' => 'Approve Kepala Unit successfully'
                  ]; 
                }    
                else {
                  $unit_kerja = UnitKerja::where('id_unit_kerja', '=', $input->id_unit_kerja)->first();

                  return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Kepala Unit '.$unit_kerja->nm_unit_kerja.' Belum Dilakukan Setting!'
                    ];
                }                      
            }
            elseif($mode == 'approve-kepala-keuangan') {
                $guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                              ->where('guru.jenis_jabatan', '=', 2)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                $staff = Staff::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                              ->where('guru.jenis_jabatan', '=', 2)
                              ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                              ->first();

                if( ! empty($guru->id_pengguna)) {
                  // make object to find id
                  $rapb                               = Rapb::find($id);
                  $rapb->id_pengguna_kepala_keuangan  = $guru->id_pengguna;
                  $rapb->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                  $rapb->updated_at                   = $now;
                  $rapb->save();

                  return [
                      'status' => 203, // SUCCESS AND LOAD CONTENT
                      'message' => 'Approve Kepala Keuangan successfully'
                  ]; 
                }    
                elseif(! empty($staff->id_pengguna)) {
                  // make object to find id
                  $rapb                           = Rapb::find($id);
                  $rapb->id_pengguna_kepala_unit  = $staff->id_pengguna;
                  $rapb->updated_by               = $input->auth_data->pengguna->id_pengguna;
                  $rapb->updated_at               = $now;
                  $rapb->save();

                  return [
                      'status' => 203, // SUCCESS AND LOAD CONTENT
                      'message' => 'Approve Kepala Keuangan successfully'
                  ]; 
                }    
                else {
                  return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Kepala Unit Keuangan Belum Dilakukan Setting!'
                    ];
                } 
            }
            elseif($mode == 'delete'){
                if($realisasi = Realisasi::where('id_rapb',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'RAPB Sudah Terpakai Pada Realisasi!'
                    ]; 
                }
                else{
                    // make object to find id
                    $rapb               = Rapb::find($id);
                    $rapb->deleted_by   = $input->auth_data->pengguna->id_pengguna;

                    $rapb->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete RAPB successfully'
                    ];
                }
            }

        }
    }
}
