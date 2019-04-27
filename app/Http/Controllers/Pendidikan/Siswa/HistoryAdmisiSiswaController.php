<?php

namespace App\Http\Controllers\Pendidikan\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;

use App\Models\Kota as Kota;
use App\Models\Provinsi as Provinsi;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\Admisi as Admisi;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Semester as Semester;

use Auth;
use DB;
use Session;
use Validator;

class HistoryAdmisiSiswaController extends BaseController
{
	public function viewHistoryAdmisiSiswa(Request $request){
	    # code..
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		return view('pendidikan/siswa/history-admisi-siswa/view-history-admisi-siswa',compact('auth_data'));
	}

	public function actionViewHistoryAdmisiSiswa(Request $request){
      # code...
		$input = (object) $request->input();
		$auth_data = $input->auth_data;

		$validator = Validator::make($request->all(), [
			'nis_nama_siswa' =>'required'
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
                    'path' => 'siswa/histori-admisi-siswa/view-detail/'.$input->nis_nama_siswa
                ];
            }
        }
        public function viewDetailHistoryAdmisiSiswa(Request $request, $nis_siswa){
        # code...
        	$input = (object) $request->input();
        	$auth_data = $input->auth_data;

            $siswa = LibSiswa::fetchAdmisiSiswa($auth_data, $nis_siswa);
        	$status = StatusPengguna::where('status_join_table','=','3')->where('kode_status_pengguna','!=','LULUS')->where('kode_status_pengguna','!=','CALON_LULUS')->get();
        	$semester = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();

        	return view('pendidikan/siswa/history-admisi-siswa/view-detail-history-admisi-siswa',compact('auth_data','nis_siswa','siswa','status','semester'));
        }
        public function datatablesHistoryAdmisiSiswa(Request $request, $nis_nama_siswa){
        	$input = (object) $request->input();
        	$auth_data = $input->auth_data;

            $siswa_data = LibSiswa::fetchAdmisiSiswa($auth_data, $nis_nama_siswa);

            $nis_siswa = $siswa_data->nis_siswa;

        	$siswa = Admisi::select('admisi.tgl_keluar','status_pengguna.nm_status_pengguna','jalur.nm_jalur','pengajuan_wisuda.nomor_sk_kelulusan','pengajuan_wisuda.tgl_sk_kelulusan','pengajuan_wisuda.nomor_ijasah','semester.tahun_ajaran','semester.nm_semester','admisi.keterangan_admisi','admisi.id_admisi','status_pengguna.kode_status_pengguna')
        	->join('status_pengguna','status_pengguna.id_status_pengguna','=','admisi.id_status_pengguna','')
        	->join('semester','semester.id_semester','=','admisi.id_semester')
        	->leftJoin('jalur','jalur.id_jalur','=','admisi.id_jalur')
        	->join('siswa','siswa.id_siswa','=','admisi.id_siswa')
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
        	->leftJoin('pengajuan_wisuda','pengajuan_wisuda.id_pengajuan_wisuda','=','admisi.id_pengajuan_wisuda')
        	->where('siswa.nis_siswa','=',$nis_siswa)
            ->orderBy('semester.thn_akademik_semester', 'asc')
            ->orderBy('semester.nm_semester', 'asc')
        	->get();

        	return Datatables::of($siswa)
        	->addColumn('tgl_keluar', function($item){
        		return $item->tgl_keluar;
        	})
        	->addColumn('nm_status_pengguna', function($item){
        		return $item->nm_status_pengguna;
        	})
        	->addColumn('nm_semester', function($item){
        		return $item->tahun_ajaran." ".$item->nm_semester;
        	})
        	->addColumn('action', function($item){
        		$data = array(
        			'id' => $item->id_admisi,
        			'status_pengguna'	=> $item->kode_status_pengguna
        		);
        		return $data;
        	})
        	->make(true);
        }
        public function actionHistoryAdmisiSiswa(Request $request, $mode, $id = null){

        	$input = (object) $request->input();

        	$validator = Validator::make($request->all(), [
        		
        	]);

        	if($validator->fails() && $mode != 'delete') {
        		return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
        	if($mode == 'delete'){
        		// mengambil waktu sekarang
        		$now = Carbon::now(env('APP_TIMEZONE', ''));
	            // make object to find id
        		$admisi             		  = Admisi::find($id);
        		$admisi->deleted_by   		  = $input->auth_data->pengguna->id_pengguna;
        		$admisi->save();

        		$admisi->delete();

        		return [
	                        'status' => 203, // SUCCESS AND LOAD TABLE
	                        'message' => 'BERHASIL MENGHAPUS'
	                    ];
	                }
	            }
	        }
	    }
