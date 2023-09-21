<?php

namespace App\Http\Controllers\Siswa\SKPI;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanSiswa;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;


use Auth;
use DB;
use Session;
use Validator;

class DataKegiatanSiswaController extends BaseController{

    public function viewDataKegiatanSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('siswa/skpi/data-kegiatan-siswa/view-data-kegiatan-siswa',compact('auth_data'));

    }

    public function viewAddDataKegiatanSiswa(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $tingkat = TingkatPrestasiSiswa::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
    	return view('siswa/skpi/data-kegiatan-siswa/add-data-kegiatan-siswa',compact('auth_data'));

    }

    public function viewEditDataKegiatanSiswa(Request $request,$id){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $tingkat = TingkatPrestasiSiswa::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        $kegiatan = KegiatanSiswa::find($id);
    	return view('siswa/skpi/data-kegiatan-siswa/edit-data-kegiatan-siswa',compact('auth_data','kegiatan'));

    }

    public function actionDataKegiatanSiswa(Request $request, $mode , $id=null){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $validator = Validator::make($request->all(), [
            'nm_kegiatan_siswa' => 'required',
            'lokasi_kegiatan_siswa' => 'required',
            'penyelenggara_kegiatan_siswa' => 'required',
            // 'id_tingkat_prestasi_siswa' => 'required',
            'tgl_kegiatan_siswa' => 'required',
            'link_sertifikat' => 'required|url'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

        	if($mode == 'add'){

        		$id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        		$kegiatan = new KegiatanSiswa;
        		$kegiatan->id_kegiatan_siswa = $id;
        		$kegiatan->id_siswa = $siswa->id_siswa;
        		$kegiatan->id_kelas = $siswa->id_kelas ?? 'SISWA LULUS';
        		$kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
        		$kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
        		$kegiatan->id_tingkat_prestasi_siswa = 0;
        		$kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
        		$kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
        		$kegiatan->created_by = $input->auth_data->pengguna->id_pengguna;
        		$kegiatan->save();

        		return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'skpi/data-kegiatan-siswa',
                    'message' => 'Save Kegiatan Successfully'
                ];

        	}

        	elseif ($mode == 'edit') {
        		$kegiatan = KegiatanSiswa::find($id);
        		$kegiatan->id_siswa = $siswa->id_siswa;
        		$kegiatan->id_kelas = $siswa->id_kelas;
        		$kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
        		$kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
        		$kegiatan->id_tingkat_prestasi_siswa = 0;
        		$kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
        		$kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
        		$kegiatan->updated_at = $now;
                $kegiatan->updated_by = $input->auth_data->pengguna->id_pengguna;
        		$kegiatan->save();

	    		return [
	                    'status' => 202, // SUCCESS AND LOAD CONTENT
	                    'path' => 'skpi/data-kegiatan-siswa',
	                    'message' => 'Edit Kegiatan Successfully'
	                ];

        	}

        	elseif ($mode == 'delete'){

        		$kegiatan = KegiatanSiswa::find($id);
                $kegiatan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->deleted_at  = $now;
                $kegiatan->save();

                $kegiatan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kegiatan Successfully'
                ];

        	}

        }

    }

    public function datatablesDataKegiatanSiswa(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KegiatanSiswa::Select(
        			'kegiatan_siswa.id_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_siswa',
                    'kegiatan_siswa.status',
        			'kegiatan_siswa.tgl_kegiatan_siswa',
                    'kegiatan_siswa.lokasi_kegiatan_siswa',
                    'kegiatan_siswa.penyelenggara_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_scan_sertif',
        			// 'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
                    'kegiatan_siswa.keterangan'
        			)
        // ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->where('p1.id_pengguna', '=', $auth_data->pengguna->id_pengguna)
        // ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
                        ->addColumn('keterangan_status', function ($item) {
                            if ($item->status == 0) {
                                $status = 'Belum Diapprove';
                                $color = 'pink';
                            } elseif ($item->status== 1) {
                                $status = 'Sudah Diapprove';
                                $color = 'teal';
                            }
                            elseif ($item->status== 10) {
                                $status = 'Ditolak';
                                $color = 'red';
                            }
                            $data = array(
                                'status' => $status,
                                'color'  => $color
                            );
                            return $data;
                        })
        				->addColumn('action', function($item){
		                    $data = array(
		                        'id' => $item->id_kegiatan_siswa,
		                        'link_sertifikat'=>$item->nm_kegiatan_scan_sertif,
                                'status'=>$item->status
		                    );
		                    return $data;
		                })
        				->make(true);

    }

}