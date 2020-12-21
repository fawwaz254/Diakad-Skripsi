<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Siswa as Siswa;
use App\Models\DetailBiaya as DetailBiaya;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use DB;
use Session;
use Validator;

class TagihanSiswaController extends BaseController
{
    public function viewTagihanSiswa(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

      $data_thn_masuk_siswa = Siswa::select('siswa.thn_masuk_siswa')
                                  ->distinct()
                                  ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                  ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                                  ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                  ->where('status_pengguna.aktif_status_pengguna','=',1)
                                  ->orderBy('thn_masuk_siswa', 'ASC')
                                  ->get();

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

      $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

      $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);

    	return view('keuangan/utility/tagihan-siswa/view-tagihan-siswa',compact('auth_data','data_thn_masuk_siswa','data_semester','data_kelompok_biaya','data_jalur'));
  	}
  	
    public function actionViewTagihanSiswa(Request $request){
      # code...
      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
            'thn_masuk_siswa'   => 'required',
            'id_semester'       => 'required',
            /*'id_kelompok_biaya' => 'required',
            'id_jalur'          => 'required',*/
            'is_insert_replace' => 'required'
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
                    'path' => 'utility/tagihan-siswa/view-detail-tagihan-siswa/'.$input->thn_masuk_siswa.'/'.$input->id_semester.'/'.$input->id_kelompok_biaya.'/'.$input->id_jalur.'/'.$input->is_insert_replace
                ];
     	}
  	}

  	public function viewDetailTagihanSiswa(Request $request, $thn_masuk_siswa, $id_semester, $id_kelompok_biaya, $id_jalur, $is_insert_replace){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_thn_masuk_siswa = Siswa::select('siswa.thn_masuk_siswa')
                                  ->distinct()
                                  ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                                  ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                                  ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                                  ->where('status_pengguna.aktif_status_pengguna','=',1)
                                  ->orderBy('thn_masuk_siswa', 'ASC')
                                  ->get();

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

      $data_kelompok_biaya = LibDataKeuangan::fetchDataKelompokBiaya($auth_data);

      $data_jalur = LibDataAkademik::fetchDataJalur($auth_data);
        
        return view('keuangan/utility/tagihan-siswa/view-detail-tagihan-siswa',compact('auth_data','thn_masuk_siswa','id_semester','id_kelompok_biaya','id_jalur','is_insert_replace','data_thn_masuk_siswa','data_semester','data_kelompok_biaya','data_jalur'));
    }

    public function datatablesTagihanSiswa(Request $request, $thn_masuk_siswa, $id_semester, $id_kelompok_biaya, $id_jalur, $is_insert_replace){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibDataKeuangan::fetchDataSiswaTagihan($auth_data, $thn_masuk_siswa, $id_semester, $id_kelompok_biaya, $id_jalur, "1");

        return Datatables::of($siswa)
                ->addColumn('checkbox', function($item){
                    $data = array(
                        'id_siswa' => $item->id_siswa
                    );
                    return $data;
                })
                ->addColumn('kelompok_biaya', function($item){
                    if($item->status_kelompok_biaya == 1){
                        return $item->nm_kelompok_biaya." (Reguler)";
                    }
                    elseif($item->status_kelompok_biaya == 2){
                        return $item->nm_kelompok_biaya." (Khusus)";
                    }
                    else {
                        return "Belum Di Set";
                    }
                })
                ->make(true);
    }


    // Action POST
    public function actionTagihanSiswa(Request $request, $mode){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_siswa' => 'required',
            'id_semester' => 'required',
            'is_insert_replace' => 'required'
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
                DB::beginTransaction();

                try {
                  foreach ($input->id_siswa as $id_siswa) {
                    $kelompok_biaya = Siswa::select('id_kelompok_biaya')->where('id_siswa','=',$id_siswa)->first();

                    $detail_biaya_set = DetailBiaya::select('detail_biaya.id_detail_biaya', 'detail_biaya.besar_biaya')
                                          ->join('biaya_sekolah','biaya_sekolah.id_biaya_sekolah','=','detail_biaya.id_biaya_sekolah')
                                          ->where('biaya_sekolah.id_kelompok_biaya','=',$kelompok_biaya->id_kelompok_biaya)
                                          ->where('biaya_sekolah.id_semester','=',$input->id_semester)
                                          ->get();

                    foreach ($detail_biaya_set as $detail_biaya) {
                      $tagihan_set = TagihanBiaya::select('id_tagihan_biaya')
                                          ->where('id_siswa','=',$id_siswa)
                                          ->where('id_detail_biaya','=',$detail_biaya->id_detail_biaya)
                                          ->first();

                      if ($input->is_insert_replace == "1") {
                        if ($tagihan_set) {
                            continue;
                        //   return [
                        //           'status' => 203, // GAGAL
                        //           'message' => 'Generate Tagihan Siswa Gagal, Detail Biaya Sudah Ada!'
                        //       ];
                        }
                      }
                      // delete tagihan lama
                      elseif ($input->is_insert_replace == "2") {

                        $pembayaranBiaya            = PembayaranBiaya::find($tagihan_set->id_tagihan_biaya);

                        if($pembayaranBiaya) {
                          return [
                                  'status' => 203, // GAGAL
                                  'message' => 'Generate Tagihan Siswa Gagal, Tagihan Pernah Dibayarkan!'
                              ];
                        }
                        else {
                          $tagihanBiaya               = TagihanBiaya::find($tagihan_set->id_tagihan_biaya);
                          $tagihanBiaya->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                          $tagihanBiaya->save();

                          $tagihanBiaya->delete();
                        }

                      }

                        $siswa = Siswa::find($id_siswa);
                        
                        $tagihanBiaya                       = new TagihanBiaya;
                        $tagihanBiaya->id_tagihan_biaya     = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $tagihanBiaya->id_siswa             = $id_siswa;
                        $tagihanBiaya->id_kelas             = $siswa->id_kelas;
                        $tagihanBiaya->id_detail_biaya      = $detail_biaya->id_detail_biaya;
                        $tagihanBiaya->besar_biaya          = $detail_biaya->besar_biaya;
                        $tagihanBiaya->denda_biaya          = 0;
                        $tagihanBiaya->is_tagih             = 1;
                        $tagihanBiaya->keterangan           = $detail_biaya->keterangan_biaya;
                        $tagihanBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
                        $tagihanBiaya->save();
                    }                                                              
                  }

                  DB::commit();
                  // all good

                  return [
                      'status' => 202, // SUCCESS AND LOAD CONTENT
                      'path' => 'utility/tagihan-siswa/view-detail-tagihan-siswa/'.$input->thn_masuk_siswa.'/'.$input->id_semester.'/'.$input->id_kelompok_biaya.'/'.$input->id_jalur.'/'.$input->is_insert_replace,
                      'message' => 'Generate Tagihan Siswa successfully'
                  ];

                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
                                'message' => 'Generate Tagihan Siswa Gagal!'
                            ];
                }  
            }
        }
    }
}
