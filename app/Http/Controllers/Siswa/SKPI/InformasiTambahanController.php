<?php

namespace App\Http\Controllers\Siswa\SKPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InformasiTambahan;
use App\Models\Siswa;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;
use App\Libraries\Pendidikan\LibDataAkademik;


class InformasiTambahanController extends Controller
{
    public function viewInformasiTambahan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('siswa/skpi/informasi-tambahan/view-informasi-tambahan',compact('auth_data'));
    }

    public function viewAddInformasiTambahan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

      
        
    	return view('siswa/skpi/informasi-tambahan/add-informasi-tambahan',compact('auth_data','tingkat'));

    }

    public function viewEditInformasiTambahan(Request $request,$id){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $tingkat = TingkatPrestasiSiswa::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        // $jenis_prestasi = [[1,'Sains'],[2,'Seni'],[3,'Olahraga'],[4,'Lain-lain']];
        // $ekskul = Ekskul::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        // $guru = Guru::select(
        //         'id_guru',
        //         'p2.nm_pengguna as nm_guru_pendamping',
        //         'p2.gelar_depan',
        //         'p2.gelar_belakang')
        //         ->Join('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
        //         ->get();

        $informasi_tambahan = InformasiTambahan::findOrFail($id);
        
        return view('siswa/skpi/informasi-tambahan/edit-informasi-tambahan',compact('auth_data','informasi_tambahan'));
    }

    public function actionInformasiTambahan(Request $request, $mode , $id=null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $validator = Validator::make($request->all(), [
            'jenis_informasi_tambahan' => 'required',
            'nm_informasi_tambahan' => 'required',
            'nm_informasi_tambahan_eng' => 'required'
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

        		$informasi = new InformasiTambahan;
        		$informasi->id_informasi_tambahan = $id;
        		$informasi->id_siswa = $siswa->id_siswa;
        		$informasi->id_kelas = $siswa->id_kelas;
        		$informasi->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
        		$informasi->jenis_informasi_tambahan = $input->jenis_informasi_tambahan;
                $informasi->nm_informasi_tambahan = $input->nm_informasi_tambahan;
                $informasi->nm_informasi_tambahan_eng = $input->nm_informasi_tambahan_eng;
                $informasi->status = 0;
            	$informasi->created_by = $input->auth_data->pengguna->id_pengguna;
        		$informasi->save();

        		return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'skpi/informasi_tambahan',
                    'message' => 'Save Kegiatan successfully'
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
        		$kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
        		$kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
        		$kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
        		$kegiatan->updated_at = $now;
                $kegiatan->updated_by = $input->auth_data->pengguna->id_pengguna;
        		$kegiatan->save();

	    		return [
	                    'status' => 202, // SUCCESS AND LOAD CONTENT
	                    'path' => 'skpi/data-kegiatan-siswa/edit/'.$id,
	                    'message' => 'Edit Kegiatan successfully'
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
                    'message' => 'Delete Kegiatan successfully'
                ];

        	}

        }  
    }

    public function datatablesInformasiTambahan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = InformasiTambahan::Select(
        			'informasi_tambahan.id_informasi_tambahan',
        			'informasi_tambahan.jenis_informasi_tambahan',
                    'informasi_tambahan.nm_informasi_tambahan',
                    'informasi_tambahan.nm_informasi_tambahan_eng',
                    'informasi_tambahan.status',
        			'informasi_tambahan.keterangan'
                   )->get();

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
                        })->addColumn('action', function($item){
		                    $data = array(
		                        'id' => $item->id_informasi_tambahan,
                                'status'=>$item->status);
		                    return $data;
		                })->make(true);
    }
}
