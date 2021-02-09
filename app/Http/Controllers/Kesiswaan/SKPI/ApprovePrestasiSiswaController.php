<?php

namespace App\Http\Controllers\Kesiswaan\SKPI;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanSiswa;
use App\Models\PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class ApprovePrestasiSiswaController extends BaseController{

    public function viewApprovePrestasiSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('kesiswaan/skpi/approve-prestasi-siswa/view-approve-prestasi-siswa',compact('auth_data'));

    }

    public function viewDetailPrestasiSiswa(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('kesiswaan/skpi/approve-prestasi-siswa/view-detail-prestasi-siswa',compact('auth_data'));

    }

    public function printSkpi(Request $request,$id){

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = Siswa::findOrFail($id);
        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $data->nis_siswa);

        $prestasi = PrestasiSiswa::where('prestasi_siswa.id_siswa',$id)
                    ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
                    ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
                    ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
                    ->where('prestasi_siswa.status',1)
                    ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->get();

        $kegiatan = KegiatanSiswa::where('kegiatan_siswa.id_siswa',$id)
                    ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_siswa.id_tingkat_prestasi_siswa')
                    ->join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')
                    ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
                    ->where('kegiatan_siswa.status',1)
                    ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->get();

        return view('kesiswaan/skpi/approve-prestasi-siswa/print-skpi',compact('auth_data','siswa','prestasi','kegiatan'));

    }

    public function datatablesKegiatanApprovePrestasiSiswa(Request $request,$id){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa =Siswa::findOrFail($id);

    	$list_data = KegiatanSiswa::Select(
        			'kegiatan_siswa.id_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_siswa',
        			'kegiatan_siswa.status',
        			'kegiatan_siswa.tgl_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_scan_sertif',
        			'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
        			)
        ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->where('p1.id_pengguna', '=', $siswa->id_pengguna)
        ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
        				->addColumn('keterangan', function ($item) {
		                    if ($item->status == 0) {
		                        $status = 'Belum Diapprove';
		                        $color = 'pink';
		                    } elseif ($item->status== 1) {
		                        $status = 'Sudah Diapprove';
		                        $color = 'teal';
		                    }
		                    $data = array(
		                        'status' => $status,
		                        'color'	 => $color
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

    public function datatablesPrestasiApprovePrestasiSiswa(Request $request,$id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'prestasi_siswa.status',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nisn_siswa',
            'siswa.nis_siswa',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'kelas.nm_kelas',
            'prestasi_siswa.lokasi_prestasi_siswa',
            'prestasi_siswa.penyelenggara_prestasi_siswa',
            'prestasi_siswa.tgl_prestasi_siswa',
            'ekskul.nm_ekskul',
            'prestasi_siswa.id_prestasi_siswa',
            'prestasi_siswa.id_guru_pendamping',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
        ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->join('semester', 'semester.id_semester', '=', 'prestasi_siswa.id_semester')
        ->join('kelas', 'kelas.id_kelas', '=', 'prestasi_siswa.id_kelas')
        ->leftJoin('ekskul', 'ekskul.id_ekskul', '=', 'prestasi_siswa.id_ekskul')
        ->leftJoin('guru', 'guru.id_guru', '=', 'prestasi_siswa.id_guru_pendamping')
        ->leftJoin('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
        ->orderBy('prestasi_siswa.created_at', 'desc')
        ->orderBy('semester.thn_akademik_semester', 'desc')
        ->orderBy('semester.nm_semester', 'desc')
        ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('prestasi_siswa.id_siswa',$id)
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->get();

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->nm_semester.' ('.$item->tahun_ajaran.')';
                })
                ->addColumn('jenis_prestasi', function ($item) {
                    if ($item->jenis_prestasi_siswa == 1) {
                        return "Sains";
                    } elseif ($item->jenis_prestasi_siswa == 2) {
                        return "Seni";
                    } elseif ($item->jenis_prestasi_siswa == 3) {
                        return "Olahraga";
                    } elseif ($item->jenis_prestasi_siswa == 99) {
                        return "Lain-Lain";
                    }
                })
                ->addColumn('keterangan', function ($item) {
                    if ($item->status == 0) {
                        $status = 'Belum Diapprove';
                        $color = 'pink';
                    } elseif ($item->status== 1) {
                        $status = 'Sudah Diapprove';
                        $color = 'teal';
                    }
                    $data = array(
                        'status' => $status,
                        'color'	 => $color
                    );
                    return $data;
                })
              ->addColumn('tgl_prestasi_siswa', function ($item) {
                  return strftime("%d %B %Y", strtotime($item->tgl_prestasi_siswa));
              })
              ->addColumn('nm_guru_pendamping', function ($item) {
                  if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } elseif (! empty($item->gelar_depan)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping;
                  } elseif (! empty($item->gelar_belakang)) {
                      return $item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } else {
                      return $item->nm_guru_pendamping;
                  }
              })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_prestasi_siswa,
                        'status' => $item->status
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionApprovePrestasiSiswa(Request $request,$data,$id){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$now = Carbon::now(env('APP_TIMEZONE', ''));

    	if($data=='prestasi'){

    		$from = 'prestasi';

    		$prestasi = PrestasiSiswa::findOrFail($id);
    		$prestasi->status = 1;
    		$prestasi->approved_by = $input->auth_data->pengguna->id_pengguna;
            $prestasi->approved_at = $now;

            $prestasi->save();

    	}

    	elseif($data=='kegiatan'){

    		$from = 'kegiatan';

    		$kegiatan = KegiatanSiswa::findOrFail($id);
    		$kegiatan->status = 1;
    		$kegiatan->approved_by = $input->auth_data->pengguna->id_pengguna;
            $kegiatan->approved_at = $now;

            $kegiatan->save();

    	}

    	 return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Approved Succesfully',
                'from' 	  => $from
                ];

    }

    public function datatablesApprovePrestasiSiswa(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = Siswa::select('siswa.id_siswa','calon_siswa_baru.nm_c_siswa')
				->whereHas('kegiatan_siswa',function($q) use($auth_data){
                    $q->where(['pengguna.id_sekolah'=>$auth_data->pengguna->id_sekolah]);
                })
				->orWhereHas('prestasi_siswa',function($q) use($auth_data){
                    $q->where(['pengguna.id_sekolah'=>$auth_data->pengguna->id_sekolah]);
                })
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
				->join('calon_siswa_baru', 'siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
				->withCount([
					'kegiatan_siswa as kegiatan_siswa_not_approved' => function($q){ $q->where('status',0); },
					'kegiatan_siswa as kegiatan_siswa_approved' => function($q){ $q->where('status',1); },
					'prestasi_siswa as prestasi_siswa_not_approved' => function($q){ $q->where('status',0); },
					'prestasi_siswa as prestasi_siswa_approved' => function($q){ $q->where('status',1); }
				])
				->get();

        return Datatables::of($data)
        					->addColumn('prestasi', function ($item) {
			                    $data = array(
			                        'prestasi_siswa_not_approved' => $item->prestasi_siswa_not_approved,
			                        'prestasi_siswa_approved' => $item->prestasi_siswa_approved
			                    );
			                    return $data;
			                })
			                ->addColumn('kegiatan', function ($item) {
			                    $data = array(
			                        'kegiatan_siswa_not_approved' => $item->kegiatan_siswa_not_approved,
			                        'kegiatan_siswa_approved' => $item->kegiatan_siswa_approved
			                    );
			                    return $data;
			                })
							->addColumn('action', function ($item) {
			                    $data = array(
			                        'id' => $item->id_siswa
			                    );
			                    return $data;
			                })
		                	->make(true);


    }

}