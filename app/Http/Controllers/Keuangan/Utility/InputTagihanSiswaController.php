<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\BiayaSekolah;
use App\Models\DetailBiaya;
use App\Models\KelompokBiaya;
use App\Models\TagihanBiaya;
use App\Models\Semester;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Models\Siswa as Siswa;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InputTagihanSiswaController extends BaseController
{
    public function viewInputTagihanSiswa(Request $request){
	    # code..
	    $input = (object) $request->input();
	    $auth_data = $input->auth_data;

      $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
      $data_biaya = LibDataKeuangan::fetchDataNamaBiaya($auth_data);
      $data_jenis_detail_biaya = LibDataKeuangan::fetchDataJenisDetailBiaya($auth_data);
      $data_kelompok_biaya = KelompokBiaya::all();
      $data_siswa = LibSiswa::fetchDataSiswa($auth_data);

    	return view('keuangan/utility/input-tagihan-siswa/view-input-tagihan-siswa',compact('auth_data','data_semester','data_biaya','data_jenis_detail_biaya','data_siswa','data_kelompok_biaya'));
  	}

    public function addTagihan(Request $request){

      $input = (object) $request->input();
      $auth_data = $input->auth_data;

      $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'nama_biaya' => 'required',
            'besar_biaya' => 'required',
            'keterangan' => 'required'
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

          DB::beginTransaction();

          try {

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // get biaya sekolah

            $list_siswa = $input->id_siswa;
            $siswa = Siswa::find($list_siswa[0]);

            $biaya_sekolah = BiayaSekolah::where(['id_semester'=>$input->semester,'id_kelompok_biaya'=>$siswa->id_kelompok_biaya])->first();

            $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

            $detailBiaya                                = new DetailBiaya;
            $detailBiaya->id_detail_biaya               = $id;
            $detailBiaya->id_biaya_sekolah              = $biaya_sekolah->id_biaya_sekolah;
            $detailBiaya->id_biaya                      = $input->nama_biaya;
            $detailBiaya->besar_biaya                   = $input->besar_biaya;
            $detailBiaya->validasi_biaya                = 1;
            $detailBiaya->is_general                    = 0;
            $detailBiaya->keterangan_biaya              = $input->keterangan;
            $detailBiaya->id_jenis_detail_biaya         = 3;
            $detailBiaya->created_by                    = $input->auth_data->pengguna->id_pengguna;
            $detailBiaya->save();

            foreach($list_siswa as $r){

              $detail_siswa = Siswa::find($r);

              $tagihanBiaya                       = new TagihanBiaya;
              $tagihanBiaya->id_tagihan_biaya     = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
              $tagihanBiaya->id_siswa             = $r;
              $tagihanBiaya->id_kelas             = $detail_siswa->id_kelas;
              $tagihanBiaya->id_detail_biaya      = $detailBiaya->id_detail_biaya;
              $tagihanBiaya->besar_biaya          = $input->besar_biaya;
              $tagihanBiaya->denda_biaya          = 0;
              $tagihanBiaya->is_tagih             = 1;
              $tagihanBiaya->keterangan           = $input->keterangan;
              $tagihanBiaya->created_by           = $input->auth_data->pengguna->id_pengguna;
              $tagihanBiaya->save();
            }

            DB::commit();

             return [
                      'status' => 202, // SUCCESS AND LOAD CONTENT
                      'path' => 'utility/input-tagihan-siswa',
                      'message' => 'Insert Tagihan Siswa successfully'
                  ];

          }

          catch (\Exception $e) {

            DB::rollback();

            return [
                'status' => 203, // GAGAL
                'message' => 'Generate Tagihan Siswa Gagal! ' . $e->getMessage()
            ];

          }

        }

    }

    public function filterSiswa(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = LibSiswa::fetchDataSiswaByKelompokBiaya($auth_data,$id);
        return response()->json($data);

    }

}