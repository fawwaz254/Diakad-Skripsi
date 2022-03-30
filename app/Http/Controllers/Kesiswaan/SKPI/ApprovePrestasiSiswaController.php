<?php

namespace App\Http\Controllers\Kesiswaan\SKPI;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\KegiatanSiswa;
use App\Models\Ekskul;
use App\Models\Semester;
use App\Models\Guru;
use App\Models\PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\InformasiTambahan;
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

    public function viewDetailPrestasiSiswa(Request $request,$id,$param){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('kesiswaan/skpi/approve-prestasi-siswa/view-detail-prestasi-siswa',compact('auth_data','param'));

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

    public function datatablesKegiatanApprovePrestasiSiswa(Request $request,$id,$param){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa =Siswa::findOrFail($id);

    	$list_data = KegiatanSiswa::Select(
        			'kegiatan_siswa.id_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_siswa',
        			'kegiatan_siswa.status',
        			'kegiatan_siswa.tgl_kegiatan_siswa',
                    'kegiatan_siswa.lokasi_kegiatan_siswa',
                    'kegiatan_siswa.penyelenggara_kegiatan_siswa',
        			'kegiatan_siswa.nm_kegiatan_scan_sertif',
        			'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa'
        			)
        ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'kegiatan_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->where('p1.id_pengguna', '=', $siswa->id_pengguna)
        ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        if($param == 0) $list_data = $list_data->where('status','!=',0)->get();
        else $list_data = $list_data->where('status',0)->get();

        return Datatables::of($list_data)
        				->addColumn('keterangan', function ($item) {
		                    if ($item->status == 0) {
		                        $status = 'Belum Diapprove';
		                        $color = 'pink';
		                    } elseif ($item->status== 1) {
		                        $status = 'Sudah Diapprove';
		                        $color = 'teal';
		                    }
                            elseif ($item->status== 10) {
                                $status = 'Reject';
                                $color = 'red';
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

    public function datatablesInformasiTambahan(Request $request, $id,$param){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa =Siswa::findOrFail($id);

    	$list_data = InformasiTambahan::Select(
        			'informasi_tambahan.id_informasi_tambahan',
                    'informasi_tambahan.jenis_informasi_tambahan',
        			'informasi_tambahan.nm_informasi_tambahan',
                    'informasi_tambahan.nm_informasi_tambahan_eng',
        			'informasi_tambahan.status'
        			);

        if($param == 0) $list_data = $list_data->where('status','!=',0)->get();
        else $list_data = $list_data->where('status',0)->get();

        return Datatables::of($list_data)
        				->addColumn('keterangan', function ($item) {
		                    if ($item->status == 0) {
		                        $status = 'Belum Diapprove';
		                        $color = 'pink';
		                    } elseif ($item->status== 1) {
		                        $status = 'Sudah Diapprove';
		                        $color = 'teal';
		                    }
                            elseif ($item->status== 10) {
                                $status = 'Reject';
                                $color = 'red';
                            }
		                    $data = array(
		                        'status' => $status,
		                        'color'	 => $color
		                    );
		                    return $data;
		                })
        				->addColumn('action', function($item){
		                    $data = array(
		                        'id' => $item->id_informasi_tambahan,
		                        'status'=>$item->status
		                    );
		                    return $data;
		                })
        ->make(true);

    }




    public function datatablesPrestasiApprovePrestasiSiswa(Request $request,$id,$param)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'prestasi_siswa.status',
            'prestasi_siswa.jenis_lomba_siswa',
            'prestasi_siswa.link_sertif_prestasi_siswa',
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
        ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        if($param == 0) $list_data = $list_data->where('status','!=',0)->get();
        else $list_data = $list_data->where('status',0)->get();
        

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
                    elseif ($item->status== 10) {
                        $status = 'Reject';
                        $color = 'red';
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
                        'link_sertifikat' => $item->link_sertif_prestasi_siswa,
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
        elseif($data=='informasi-tambahan'){

            $from = 'informasi-tambahan';

    		$kegiatan = InformasiTambahan::findOrFail($id);
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

    public function actionRejectPrestasiSiswa(Request $request,$data,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if($data=='prestasi'){

            $from = 'prestasi';

            $prestasi = PrestasiSiswa::findOrFail($id);
            $prestasi->status = 10;
            $prestasi->keterangan = $input->keterangan;
            $prestasi->approved_by = $input->auth_data->pengguna->id_pengguna;
            $prestasi->approved_at = $now;

            $prestasi->save();

        }

        elseif($data=='kegiatan'){

            $from = 'kegiatan';

            $kegiatan = KegiatanSiswa::findOrFail($id);
            $kegiatan->status = 10;
            $kegiatan->keterangan = $input->keterangan;
            $kegiatan->approved_by = $input->auth_data->pengguna->id_pengguna;
            $kegiatan->approved_at = $now;

            $kegiatan->save();

        }
        elseif($data=='informasi-tambahan'){
            $from = 'informasi-tambahan';

            $kegiatan = InformasiTambahan::findOrFail($id);
            $kegiatan->status = 10;
            $kegiatan->keterangan = $input->keterangan;
            $kegiatan->approved_by = $input->auth_data->pengguna->id_pengguna;
            $kegiatan->approved_at = $now;

            $kegiatan->save();
        }

         return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Reject Succesfully',
                'from'    => $from
                ];

    }


    public function datatablesApprovePrestasiSiswa(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;
        $param = $input->param;
        $param_semua_siswa = $input->param_semua_siswa;

        if($input->role=='guru'){

            $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

            if($param_semua_siswa==0){

                $data = Siswa::select('siswa.id_siswa','calon_siswa_baru.nm_c_siswa','nm_pengguna','nm_kelas')
                ->whereHas('kegiatan_siswa',function($q) use($auth_data,$param,$wali_kelas){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                    $q->where(['siswa.id_kelas'=>$wali_kelas->id_kelas]);
                  
                })
                ->orWhereHas('prestasi_siswa',function($q) use($auth_data,$param,$wali_kelas){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                    $q->where(['siswa.id_kelas'=>$wali_kelas->id_kelas]);
                    
                })
                ->orWhereHas('informasi_tambahan',function($q) use($auth_data,$param,$wali_kelas){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                    $q->where(['siswa.id_kelas'=>$wali_kelas->id_kelas]);
                    
                })
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('kelas','siswa.id_kelas','kelas.id_kelas')
                ->join('calon_siswa_baru', 'siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                ->withCount([
                    'kegiatan_siswa as kegiatan_siswa_reject' => function($q){ $q->where('status',10); },
                    'kegiatan_siswa as kegiatan_siswa_approved' => function($q){ $q->where('status',1); },
                    'kegiatan_siswa as kegiatan_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_reject' => function($q){ $q->where('status',10); },
                    'prestasi_siswa as prestasi_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_approved' => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_reject' => function($q){ $q->where('status',10); },
                    'informasi_tambahan as informasi_tambahan_approved'  => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_not_approved' => function($q){ $q->where('status',0); },
                   
        
                ]);

            }

            else{

                $data = Siswa::select('siswa.id_siswa','calon_siswa_baru.nm_c_siswa','nm_pengguna','nm_kelas')
                            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                            ->join('kelas','siswa.id_kelas','kelas.id_kelas')
                            ->join('calon_siswa_baru', 'siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                            ->withCount([
                                'kegiatan_siswa as kegiatan_siswa_reject' => function($q){ $q->where('status',10); },
                                'kegiatan_siswa as kegiatan_siswa_approved' => function($q){ $q->where('status',1); },
                                'kegiatan_siswa as kegiatan_siswa_not_approved' => function($q){ $q->where('status',0); },
                                'prestasi_siswa as prestasi_siswa_reject' => function($q){ $q->where('status',10); },
                                'prestasi_siswa as prestasi_siswa_not_approved' => function($q){ $q->where('status',0); },
                                'prestasi_siswa as prestasi_siswa_approved' => function($q){ $q->where('status',1); },
                                'informasi_tambahan as informasi_tambahan_reject' => function($q){ $q->where('status',10); },
                                'informasi_tambahan as informasi_tambahan_approved'  => function($q){ $q->where('status',1); },
                                'informasi_tambahan as informasi_tambahan_not_approved' => function($q){ $q->where('status',0); },
                                
                    
                            ]);

            }

        }

        else{

            if($param_semua_siswa==0){

                $data = Siswa::select('siswa.id_siswa','calon_siswa_baru.nm_c_siswa','nm_pengguna','nm_kelas')
                ->whereHas('kegiatan_siswa',function($q) use($auth_data,$param){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                  
                })
                ->orWhereHas('prestasi_siswa',function($q) use($auth_data,$param){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                })
                ->orWhereHas('informasi_tambahan',function($q) use($auth_data,$param){
                    if($param == 0){
                        $q->where('status','!=',0);
                    }
                    else{
                        $q->where('status',0);
                    }
                })
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('calon_siswa_baru', 'siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                ->join('kelas','siswa.id_kelas','kelas.id_kelas')
                ->withCount([
                    'kegiatan_siswa as kegiatan_siswa_reject' => function($q){ $q->where('status',10); },
                    'kegiatan_siswa as kegiatan_siswa_approved' => function($q){ $q->where('status',1); },
                    'kegiatan_siswa as kegiatan_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_reject' => function($q){ $q->where('status',10); },
                    'prestasi_siswa as prestasi_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_approved' => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_reject' => function($q){ $q->where('status',10); },
                    'informasi_tambahan as informasi_tambahan_approved'  => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_not_approved' => function($q){ $q->where('status',0); },
                   
                ]);

            }

            else{

                $data = Siswa::select('siswa.id_siswa','calon_siswa_baru.nm_c_siswa','nm_pengguna','nm_kelas')
                ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('calon_siswa_baru', 'siswa.id_c_siswa', '=', 'calon_siswa_baru.id_c_siswa')
                ->join('kelas','siswa.id_kelas','kelas.id_kelas')
                ->withCount([
                    'kegiatan_siswa as kegiatan_siswa_reject' => function($q){ $q->where('status',10); },
                    'kegiatan_siswa as kegiatan_siswa_approved' => function($q){ $q->where('status',1); },
                    'kegiatan_siswa as kegiatan_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_reject' => function($q){ $q->where('status',10); },
                    'prestasi_siswa as prestasi_siswa_not_approved' => function($q){ $q->where('status',0); },
                    'prestasi_siswa as prestasi_siswa_approved' => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_reject' => function($q){ $q->where('status',10); },
                    'informasi_tambahan as informasi_tambahan_approved'  => function($q){ $q->where('status',1); },
                    'informasi_tambahan as informasi_tambahan_not_approved' => function($q){ $q->where('status',0); },
                   
                ]);

            }

        }

        if($param == 0){
             return Datatables::of($data)
                            ->addColumn('prestasi',function($item){
                                return '<button class="btn bg-pink">'.$item->prestasi_siswa_reject.' reject</button>
                                <button class="btn bg-teal">'.$item->prestasi_siswa_approved.' approved</button>';
                            })
                            ->addColumn('kegiatan',function($item){
                                 return '<button class="btn bg-pink">'.$item->kegiatan_siswa_reject.' reject</button>
                                <button class="btn bg-teal">'.$item->kegiatan_siswa_approved.' approved</button>';
                            })
                            ->addColumn('informasi_tambahan',function($item){
                                return '<button class="btn bg-pink">'.$item->informasi_tambahan_reject.' reject</button>
                               <button class="btn bg-teal">'.$item->informasi_tambahan_approved.' approved</button>';
                           })
                            ->addColumn('action', function ($item) {
                                $data = array(
                                    'id' => $item->id_siswa,
                                    'id2' => 1
                                );
                                return $data;
                            })
                             ->rawColumns(['prestasi','kegiatan','informasi_tambahan'])
                            ->make(true);
        }

        else{
             return Datatables::of($data)
                            ->addColumn('prestasi',function($item){
                                return '<button class="btn bg-pink">'.$item->prestasi_siswa_not_approved.' belum di approve</button>';
                            })
                            ->addColumn('kegiatan',function($item){
                                 return '<button class="btn bg-pink">'.$item->kegiatan_siswa_not_approved.' belum di approve</button>';
                            })
                            ->addColumn('informasi_tambahan',function($item){
                                return '<button class="btn bg-pink">'.$item->informasi_tambahan_not_approved.' belum di approve</button>';
                           })
                            ->addColumn('action', function ($item) {
                                $data = array(
                                    'id' => $item->id_siswa
                                );
                                return $data;
                            })
                             ->rawColumns(['prestasi','kegiatan','informasi_tambahan'])
                            ->make(true);
        }


    }

    public function editPrestasiSiswa(Request $request,$id){

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_ekskul = Ekskul::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $data_tingkat_prestasi = TingkatPrestasiSiswa::where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $prestasi = PrestasiSiswa::join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')->where('id_prestasi_siswa', '=', $id)->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/skpi/approve-prestasi-siswa/edit-prestasi-siswa', compact('auth_data', 'data_kelas', 'data_semester', 'data_tingkat_prestasi', 'data_ekskul', 'data_guru', 'prestasi'));

    }

    public function actionEditPrestasiSiswa(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_prestasi_siswa' => 'required',
            'peringkat_prestasi_siswa' => 'required',
            'lokasi_prestasi_siswa' => 'required',
            'penyelenggara_prestasi_siswa' => 'required',
            'jenis_prestasi_siswa' => 'required',
            'jenis_lomba_siswa'=>'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_prestasi_siswa' => 'required',
            'link_sertifikat' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

            $prestasi                                 = PrestasiSiswa::find($id);
            $prestasi->id_semester                    = $input->id_semester;
            $prestasi->id_tingkat_prestasi_siswa      = $input->id_tingkat_prestasi_siswa;
            $prestasi->id_guru_pendamping             = $input->id_guru_pendamping;
            $prestasi->id_ekskul                      = $input->id_ekskul;
            $prestasi->jenis_prestasi_siswa           = $input->jenis_prestasi_siswa;
            $prestasi->jenis_lomba_siswa              = $input->jenis_lomba_siswa;
            $prestasi->nm_prestasi_siswa              = $input->nm_prestasi_siswa;
            $prestasi->lokasi_prestasi_siswa          = $input->lokasi_prestasi_siswa;
            $prestasi->penyelenggara_prestasi_siswa   = $input->penyelenggara_prestasi_siswa;
            $prestasi->peringkat_prestasi_siswa       = $input->peringkat_prestasi_siswa;
            $prestasi->tgl_prestasi_siswa             = date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
            $prestasi->link_sertif_prestasi_siswa     = $input->link_sertifikat;
            $prestasi->updated_by                     = $input->auth_data->pengguna->id_pengguna;
            $prestasi->updated_at                     = $now;
            $prestasi->save();

            $prestasi->save();

            if($auth_data->pengguna->status_join_table==2){
                $path = 'wali-kelas/edit-prestasi-siswa/'.$id;
            }

            else{
                $path = 'skpi/edit-prestasi-siswa/'.$id;
            }

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => $path,
                'message' => 'Edit Prestasi successfully'
            ];


        }


    }

    public function editKegiatanSiswa(Request $request,$id){

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        $data_semester = Semester::where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
        $tingkat = TingkatPrestasiSiswa::where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        $kegiatan = KegiatanSiswa::join('siswa', 'siswa.id_siswa', '=', 'kegiatan_siswa.id_siswa')->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')->where('id_kegiatan_siswa', '=', $id)->first();

        // $id_jenis_mata_pelajaran = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('kesiswaan/skpi/approve-prestasi-siswa/edit-kegiatan-siswa', compact('auth_data', 'data_kelas', 'data_semester', 'tingkat', 'kegiatan'));

    }

    public function actionEditKegiatanSiswa(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'nm_kegiatan_siswa' => 'required',
            'id_siswa' => 'required',
            'id_semester' => 'required',
            'lokasi_kegiatan_siswa' => 'required',
            'penyelenggara_kegiatan_siswa' => 'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_kegiatan_siswa' => 'required',
            'link_sertifikat' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

            $kegiatan = KegiatanSiswa::find($id);

            $kegiatan->id_semester = $input->id_semester;
            $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
            $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
            $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
            $kegiatan->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
            $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
            $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
            $kegiatan->updated_at = $now;
            $kegiatan->updated_by = $input->auth_data->pengguna->id_pengguna;
            $kegiatan->save();

            if($auth_data->pengguna->status_join_table==2){
                $path = 'wali-kelas/edit-kegiatan-siswa/'.$id;
            }

            else{
                $path = 'skpi/edit-kegiatan-siswa/'.$id;
            }

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => $path,
                'message' => 'Edit Prestasi successfully'
            ];

        }

    }

}