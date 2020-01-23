<?php

namespace App\Http\Controllers\Keuangan\Rapb;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Rapb as Rapb;
use App\Models\Realisasi as Realisasi;
use App\Models\RealisasiPembayaran as RealisasiPembayaran;
use App\Models\Guru as Guru;
use App\Models\Staff as Staff;
use App\Models\UnitKerja as UnitKerja;
use App\Models\SubkategoriRapb as SubkategoriRapb;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\SumberDaya\LibDataSumberDaya;

use Auth;
use DB;
use Session;
use Validator;

class RealisasiRapbController extends BaseController
{
    public function viewRealisasi(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

    	return view('keuangan/rapb/realisasi-rapb/view-realisasi',compact('auth_data','data_semester'));
  	}
  	
    public function actionViewRapb(Request $request){
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
                    'path' => 'rapb/realisasi-rapb/view-detail-rapb/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai
                ];
     	}
  	}

  	public function viewDetailRapb(Request $request, $id_semester_mulai, $id_semester_selesai){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        
        return view('keuangan/rapb/realisasi-rapb/view-detail-rapb',compact('auth_data','id_semester_mulai','id_semester_selesai','data_semester'));
    }

    public function datatablesRapbRendah(Request $request, $id_semester_mulai, $id_semester_selesai){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, null, "1", "1");

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran_mulai." (".$item->nm_semester_mulai.") - ".$item->tahun_ajaran_selesai." (".$item->nm_semester_selesai.")";
                })
                ->addColumn('tipe_kategori_rapb', function($item){
                    if($item->tipe_kategori_rapb == 1) {
                      return "Penerimaan";
                    }
                    elseif($item->tipe_kategori_rapb == 2) {
                      return "Pengeluaran";
                    }
                })
                ->addColumn('dana_perkiraan_rapb', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb);
                })
                ->addColumn('jml_realisasi', function($item){
                    return "Rp".number_format($item->jml_realisasi);
                })
                ->addColumn('sisa_realisasi', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb - $item->jml_realisasi);
                })
                ->addColumn('tgl_rapb', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_rapb));
                })
                ->addColumn('nm_kepala_unit', function($item){
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rapb'        => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_keuangan', function($item){
                    $data = array(
                        'nm_kepala_keuangan'  => $item->nm_kepala_keuangan,
                        'id_rapb'             => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('detail', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesRapbSedang(Request $request, $id_semester_mulai, $id_semester_selesai){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, null, "1", "2");

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran_mulai." (".$item->nm_semester_mulai.") - ".$item->tahun_ajaran_selesai." (".$item->nm_semester_selesai.")";
                })
                ->addColumn('tipe_kategori_rapb', function($item){
                    if($item->tipe_kategori_rapb == 1) {
                      return "Penerimaan";
                    }
                    elseif($item->tipe_kategori_rapb == 2) {
                      return "Pengeluaran";
                    }
                })
                ->addColumn('dana_perkiraan_rapb', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb);
                })
                ->addColumn('jml_realisasi', function($item){
                    return "Rp".number_format($item->jml_realisasi);
                })
                ->addColumn('sisa_realisasi', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb - $item->jml_realisasi);
                })
                ->addColumn('tgl_rapb', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_rapb));
                })
                ->addColumn('nm_kepala_unit', function($item){
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rapb'        => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_keuangan', function($item){
                    $data = array(
                        'nm_kepala_keuangan'  => $item->nm_kepala_keuangan,
                        'id_rapb'             => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('detail', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesRapbTinggi(Request $request, $id_semester_mulai, $id_semester_selesai){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, null, "1", "3");

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran_mulai." (".$item->nm_semester_mulai.") - ".$item->tahun_ajaran_selesai." (".$item->nm_semester_selesai.")";
                })
                ->addColumn('tipe_kategori_rapb', function($item){
                    if($item->tipe_kategori_rapb == 1) {
                      return "Penerimaan";
                    }
                    elseif($item->tipe_kategori_rapb == 2) {
                      return "Pengeluaran";
                    }
                })
                ->addColumn('dana_perkiraan_rapb', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb);
                })
                ->addColumn('jml_realisasi', function($item){
                    return "Rp".number_format($item->jml_realisasi);
                })
                ->addColumn('sisa_realisasi', function($item){
                    return "Rp".number_format($item->dana_perkiraan_rapb - $item->jml_realisasi);
                })
                ->addColumn('tgl_rapb', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_rapb));
                })
                ->addColumn('nm_kepala_unit', function($item){
                    $data = array(
                        'nm_kepala_unit' => $item->nm_kepala_unit,
                        'id_unit_kerja'  => $item->id_unit_kerja,
                        'id_rapb'        => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_keuangan', function($item){
                    $data = array(
                        'nm_kepala_keuangan'  => $item->nm_kepala_keuangan,
                        'id_rapb'             => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('detail', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_rapb
                    );
                    return $data;
                })
                ->make(true);
    }

    // Realisasi
    public function viewDetailRealisasi(Request $request, $id_semester_mulai, $id_semester_selesai, $id_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_rapb = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id_rapb); 

        return view('keuangan/rapb/realisasi-rapb/view-detail-realisasi',compact('auth_data','id_rapb', 'semester_mulai', 'semester_selesai', 'data_rapb'));

    }

    public function datatablesRealisasi(Request $request, $id_rapb){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRealisasi($auth_data, $id_rapb, null, "1");

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." (".$item->nm_semester.")";
                })
                ->addColumn('pengadaan', function($item){
                    if( ! empty($item->id_rpb_sarpras)) {
                      return "Ya";
                    }
                    else {
                      return "Tidak";
                    }
                })
                ->addColumn('keterangan', function($item){
                    return $item->kode_ket_subkategori_rapb." - ".$item->nm_ket_subkategori_rapb;
                })
                ->addColumn('is_hutang', function($item){
                    if($item->is_hutang_realisasi == 0) {
                      return "Tidak";
                    }
                    elseif($item->is_hutang_realisasi == 1) {
                      return "Ya";
                    }
                })
                ->addColumn('dana_realisasi', function($item){
                    return "Rp".number_format($item->dana_realisasi);
                })
                ->addColumn('tgl_realisasi', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_realisasi));
                })
                ->addColumn('nm_cek_keuangan', function($item){
                    $data = array(
                        'nm_cek_keuangan'   => $item->nm_cek_keuangan,
                        'id_realisasi'      => $item->id_realisasi
                    );
                    return $data;
                })
                ->addColumn('nm_kepala_keuangan', function($item){
                    $data = array(
                        'nm_kepala_keuangan'  => $item->nm_kepala_keuangan,
                        'id_realisasi'        => $item->id_realisasi
                    );
                    return $data;
                })
                ->addColumn('cicilan', function($item) {
                    $data = array(
                        'id' => $item->id_realisasi
                    );
                    return $data;
                })
                ->make(true);
    }

    public function addRealisasi(Request $request, $id_semester_mulai, $id_semester_selesai, $id_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $id_realisasi = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_rapb = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id_rapb);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $data_semester = LibDataAkademik::fetchDataRentangSemester($auth_data, $id_semester_mulai, $id_semester_selesai);

        $data_ket_subkategori_rapb = LibDataKeuangan::fetchDataKetSubkategoriRapb($auth_data, $data_rapb->id_kategori_rapb, $data_rapb->id_subkategori_rapb);

        return view('keuangan/rapb/realisasi-rapb/add-realisasi',compact('auth_data','id_realisasi', 'semester_mulai', 'semester_selesai', 'data_rapb', 'data_unit_kerja', 'data_semester', 'data_ket_subkategori_rapb'));

    }

    // Realisasi Pembayaran
    public function viewDetailRealisasiTermin(Request $request, $id_semester_mulai, $id_semester_selesai, $id_rapb, $id_realisasi){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_rapb = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id_rapb); 

        $data_realisasi = LibDataKeuangan::fetchDataRealisasi($auth_data, $id_rapb, $id_realisasi); 

        return view('keuangan/rapb/realisasi-rapb/view-detail-realisasi-termin',compact('auth_data','id_rapb', 'semester_mulai', 'semester_selesai', 'data_rapb', 'data_realisasi'));

    }

    public function datatablesRealisasiTermin(Request $request, $id_realisasi){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibDataKeuangan::fetchDataRealisasiPembayaran($auth_data, $id_realisasi, null, "1");

        return Datatables::of($list_data)
                ->addColumn('tgl_pembayaran', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_pembayaran));
                })
                ->addColumn('dana_realisasi_pembayaran', function($item){
                    return "Rp".number_format($item->dana_realisasi_pembayaran);
                })
                ->addColumn('nm_kepala_keuangan', function($item){
                    $data = array(
                        'nm_kepala_keuangan'            => $item->nm_pengguna,
                        'id_realisasi_pembayaran'       => $item->id_realisasi_pembayaran
                    );
                    return $data;
                })
                ->make(true);
    }


    // Action POST
    public function actionApvRealisasi(Request $request, $mode, $id = null){
        $input = (object) $request->input();
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if($mode == 'approve-cek-keuangan') {
            $staff = Staff::join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                          ->whereIn('staff.jenis_jabatan', [2, 4, 98])
                          ->where('pengguna.id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                          ->first();

            if( ! empty($staff->id_pengguna)) {
              // make object to find id
              $realisasi                           = Realisasi::find($id);
              $realisasi->id_pengguna_cek_keuangan = $staff->id_pengguna;
              $realisasi->updated_by               = $input->auth_data->pengguna->id_pengguna;
              $realisasi->updated_at               = $now;
              $realisasi->save();

              return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Cek Keuangan successfully'
              ]; 
            }  
            else {

                $guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                              ->whereIn('guru.jenis_jabatan', [2, 4, 98])
                              ->where('pengguna.id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                              ->first();

                if( ! empty($guru->id_pengguna)) {
                  // make object to find id
                  $realisasi                           = Realisasi::find($id);
                  $realisasi->id_pengguna_cek_keuangan = $guru->id_pengguna;
                  $realisasi->updated_by               = $input->auth_data->pengguna->id_pengguna;
                  $realisasi->updated_at               = $now;
                  $realisasi->save();

                  return [
                      'status' => 203, // SUCCESS AND LOAD CONTENT
                      'message' => 'Cek Keuangan successfully'
                  ]; 
                }
                else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Anda Tidak Mempunyai Izin Untuk Cek Keuangan!'
                    ];
                }               
            }                      
        }
        elseif($mode == 'approve-kepala-keuangan') {
            $guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                          ->where('guru.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            $staff = Staff::join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                          ->where('staff.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            if( ! empty($guru->id_pengguna)) {
              // make object to find id
              $realisasi                               = Realisasi::find($id);
              $realisasi->id_pengguna_kepala_keuangan  = $guru->id_pengguna;
              $realisasi->updated_by                   = $input->auth_data->pengguna->id_pengguna;
              $realisasi->updated_at                   = $now;
              $realisasi->save();

              return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ]; 
            }    
            elseif(! empty($staff->id_pengguna)) {
              // make object to find id
              $realisasi                               = Realisasi::find($id);
              $realisasi->id_pengguna_kepala_keuangan  = $staff->id_pengguna;
              $realisasi->updated_by                   = $input->auth_data->pengguna->id_pengguna;
              $realisasi->updated_at                   = $now;
              $realisasi->save();

              return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ]; 
            }    
            else {
              return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit Keuangan Belum Dilakukan Setting!'
                ];
            } 
        }
        elseif($mode == 'approve-kepala-keuangan-termin') {
            $guru = Guru::join('pengguna','pengguna.id_pengguna','=','guru.id_pengguna')
                          ->where('guru.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            $staff = Staff::join('pengguna','pengguna.id_pengguna','=','staff.id_pengguna')
                          ->where('staff.jenis_jabatan', '=', 2)
                          ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                          ->first();

            if( ! empty($guru->id_pengguna)) {
              // make object to find id
              $RealisasiPembayaran                               = RealisasiPembayaran::find($id);
              $RealisasiPembayaran->id_pengguna_kepala_keuangan  = $guru->id_pengguna;
              $RealisasiPembayaran->updated_by                   = $input->auth_data->pengguna->id_pengguna;
              $RealisasiPembayaran->updated_at                   = $now;
              $RealisasiPembayaran->save();

              return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ]; 
            }    
            elseif(! empty($staff->id_pengguna)) {
              // make object to find id
              $RealisasiPembayaran                               = RealisasiPembayaran::find($id);
              $RealisasiPembayaran->id_pengguna_kepala_keuangan  = $staff->id_pengguna;
              $RealisasiPembayaran->updated_by                   = $input->auth_data->pengguna->id_pengguna;
              $RealisasiPembayaran->updated_at                   = $now;
              $RealisasiPembayaran->save();

              return [
                  'status' => 203, // SUCCESS AND LOAD CONTENT
                  'message' => 'Approve Kepala Keuangan successfully'
              ]; 
            }    
            else {
              return [
                    'status' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Kepala Unit Keuangan Belum Dilakukan Setting!'
                ];
            } 
        }
    }

    // Action POST
    public function actionRealisasi(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        if ($mode == 'add') {
            $validator = Validator::make($request->all(), [
                'id_semester_realisasi'     => 'required',
                'id_rapb'                   => 'required',
                'id_unit_kerja'             => 'required',
                'nm_realisasi'              => 'required',
                //'id_ket_subkategori_rapb' => 'required',
                'termin_dana_realisasi'     => 'required',
                'is_cicilan'                => 'required',
                'dana_realisasi'            => 'required',
                'tgl_realisasi'             => 'required'
            ]);
        }
        
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

                $realisasi                               = new Realisasi;
                $realisasi->id_realisasi                 = $id;
                $realisasi->id_semester_realisasi        = $input->id_semester_realisasi;
                $realisasi->id_rapb                      = $input->id_rapb;
                $realisasi->id_unit_kerja                = $input->id_unit_kerja;
                $realisasi->nm_realisasi                 = $input->nm_realisasi;
                if (! empty($input->id_ket_subkategori_rapb)) {
                    $realisasi->id_ket_subkategori_rapb  = $input->id_ket_subkategori_rapb;
                }
                $realisasi->termin_dana_realisasi        = $input->termin_dana_realisasi;
                if ($input->is_cicilan == 1) {
                    $realisasi->is_hutang_realisasi          = 1;
                }
                elseif ($input->is_cicilan == 2) {
                    $realisasi->is_hutang_realisasi          = 0;
                }
                $realisasi->dana_realisasi               = $input->dana_realisasi;
                $realisasi->tgl_realisasi                = date_format(date_create($input->tgl_realisasi),"Y-m-d");
                $realisasi->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $realisasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapb/realisasi-rapb/view-detail-realisasi/'.$input->id_semester_mulai.'/'.$input->id_semester_selesai.'/'.$input->id_rapb,
                    'message' => 'Input Realisasi RAPB successfully'
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
                $rapb->tgl_rapb               = date_format(date_create($input->tgl_rapb),"Y-m-d");
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
                    $rapb->save();

                    $rapb->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete RAPB successfully'
                    ];
                }
            }

        }
    }







    

    public function editRealisasiRapb(Request $request, $id_semester_mulai, $id_semester_selesai, $id_rapb){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $semester_mulai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_mulai);

        $semester_selesai = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester_selesai);

        $data_unit_kerja = LibDataSumberDaya::fetchDataUnitKerja($auth_data);

        $data_rapb = LibDataKeuangan::fetchDataRapb($auth_data, $id_semester_mulai, $id_semester_selesai, $id_rapb);

        $kategori = SubkategoriRapb::select('kategori_rapb.id_kategori_rapb', 'kategori_rapb.tipe_kategori_rapb')
                            ->join('kategori_rapb','kategori_rapb.id_kategori_rapb','=','subkategori_rapb.id_kategori_rapb')
                            ->where('subkategori_rapb.id_subkategori_rapb', '=', $data_rapb->id_subkategori_rapb)
                            ->first();

        $jenis_kategori = $kategori->tipe_kategori_rapb;

        $id_kategori_rapb = $kategori->id_kategori_rapb;

        $data_kategori = LibDataKeuangan::fetchDataKategoriRapb($auth_data, $jenis_kategori);

        $data_subkategori = LibDataKeuangan::fetchDataSubkategoriRapb($auth_data, $id_kategori_rapb);

        // convert format date
        $tgl_rapb = strftime( "%A, %d %B %Y", strtotime($data_rapb->tgl_rapb));

        return view('keuangan/rapb/input-rapb/edit-input-rapb',compact('auth_data', 'semester_mulai', 'semester_selesai', 'data_unit_kerja', 'data_rapb', 'jenis_kategori', 'id_kategori_rapb', 'data_kategori', 'data_subkategori', 'tgl_rapb'));

    }

    
}
