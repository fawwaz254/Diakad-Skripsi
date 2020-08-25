<?php

namespace App\Http\Controllers\Tendik\KegiatanHarian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KegiatanHarian;
use App\Models\PengisianKegiatanHarian;
use App\Models\PengisianJawaban;
use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class FormKesehatanController extends BaseController{

    public function viewFormKesehatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('tendik/kegiatan-harian/form-kesehatan/view-form-kesehatan',compact('auth_data'));
    }

    public function viewAddFormKesehatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kegiatan_harian = KegiatanHarian::with('kategori_pertanyaan', 'kategori_pertanyaan.pertanyaan', 'kategori_pertanyaan.pertanyaan.jawaban')->where('is_aktif', 1)->first();

        $data_kegiatan_harian_kategori = $kegiatan_harian->kategori_pertanyaan;

        return view('tendik/kegiatan-harian/form-kesehatan/view-add-form-kesehatan',compact('auth_data', 'kegiatan_harian', 'data_kegiatan_harian_kategori'));

    }

    public function viewDetailFormKesehatan(Request $request, $id = '-'){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $pengisian_kegiatan_harian = PengisianKegiatanHarian::with('pengguna_pengisi')->where('id_pengisian_kegiatan_harian', $id)->first();

        $data_pengisian_jawaban = PengisianJawaban::with('pertanyaan', 'jawaban')->where('id_pengisian_kegiatan_harian', $id)->get();

        return view('tendik/kegiatan-harian/form-kesehatan/view-detail-form-kesehatan',compact('auth_data', 'pengisian_kegiatan_harian', 'data_pengisian_jawaban'));

    }

    public function showDatatablesFormKesehatan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PengisianKegiatanHarian::with('pengguna_pengisi')->where('id_pengguna_pengisi', $auth_data->pengguna->id_pengguna);
        
        if(!empty($input->id_kelas)){
            $list_data = $list_data->where('status_join_table', 3);
        }else if(!empty($input->is_tendik_guru)){
            $list_data = $list_data->whereIn('status_join_table', [1,2]);
        }

        return Datatables::of($list_data)
                ->editColumn('pengguna_pengisi.nm_pengguna', function($item){
                    return $item->pengguna_pengisi->fullname();
                })
                ->editColumn('status', function($item){
                    $data = [
                        'status' => $item->status_to_text(),
                        'warna_keadaan' => $item->warna_keadaan
                    ];
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pengisian_kegiatan_harian
                    );
                    return $data;
                })
                ->make(true);
    }
    public function actionFormKesehatan(Request $request, $mode){
        $input = (object) $request->input();

        switch($mode){
            case 'add':
                $syarat = []; break;
            case 'delete':
                $syarat = [
                    'id_pengisian_kegiatan_harian' => 'required',
                ]; break;
            default:
                return ;
        }

        $validator = Validator::make($request->all(), $syarat);
        
        if($validator->fails()) {
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
                switch($request->segment(1)){
                    case 'tendik':
                        $status_join = 1; break;
                    case 'guru':
                        $status_join = 2; break;
                    case 'siswa':
                        $status_join = 3; break;
                    default:
                        $status_join = 0; break;
                }
                
                DB::beginTransaction();
                
                try {
                    $pengisian_kegiatan_harian                                 = new PengisianKegiatanHarian;
                    $pengisian_kegiatan_harian->id_pengisian_kegiatan_harian   = $id;
                    $pengisian_kegiatan_harian->id_pengguna_pengisi            = $input->auth_data->pengguna->id_pengguna;
                    $pengisian_kegiatan_harian->status_join_table              = $status_join;
                    $pengisian_kegiatan_harian->created_by                     = $input->auth_data->pengguna->id_pengguna;
                    $pengisian_kegiatan_harian->save();
                    
                    foreach($input->jawaban_pertanyaan as $id_pertanyaan => $id_jawaban){
                        $kegiatan_harian_jawaban = KegiatanHarianJawaban::find($id_jawaban);
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                        $pengisian_jawaban                                  = new PengisianJawaban;
                        $pengisian_jawaban->id_pengisian_jawaban            = $id;
                        $pengisian_jawaban->id_pengisian_kegiatan_harian    = $pengisian_kegiatan_harian->id_pengisian_kegiatan_harian;
                        $pengisian_jawaban->id_kegiatan_harian_pertanyaan   = $id_pertanyaan;
                        $pengisian_jawaban->id_kegiatan_harian_jawaban      = $id_jawaban;
                        $pengisian_jawaban->isi_jawaban_text                = !empty($input->jawaban_text[$id_jawaban])? $input->jawaban_text[$id_jawaban] : null;
                        $pengisian_jawaban->bobot_jawaban                   = $kegiatan_harian_jawaban->bobot_jawaban;
                        $pengisian_jawaban->warna_keadaan                   = $kegiatan_harian_jawaban->warna_keadaan;
                        $pengisian_jawaban->save();
                    }

                    $pengisian_jawaban_terbobot = PengisianJawaban::where('id_pengisian_kegiatan_harian', $pengisian_kegiatan_harian->id_pengisian_kegiatan_harian)->orderBy('bobot_jawaban', 'desc')->first();

                    $pengisian_kegiatan_harian->warna_keadaan = $pengisian_jawaban_terbobot->warna_keadaan;
                    $pengisian_kegiatan_harian->status_pengisian = ($pengisian_jawaban_terbobot->bobot_jawaban != 0)? 2 : 1;
                    $pengisian_kegiatan_harian->save();

                    DB::commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'kegiatan-harian/mengisi-form-kesehatan',
                        'message' => 'Save successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();

                    return [
                        'status' => 300, // FAILED
                        'message' => 'Oopss'
                    ];
                }
            }
            elseif($mode == 'delete'){
                $pengisian_kegiatan_harian  = PengisianKegiatanHarian::where('id_pengisian_kegiatan_harian', $input->id_pengisian_kegiatan_harian)->first();
                $pengisian_jawaban          = PengisianJawaban::where('id_pengisian_kegiatan_harian', $input->id_pengisian_kegiatan_harian)->delete();

                $pengisian_kegiatan_harian->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $pengisian_kegiatan_harian->save();
                
                $pengisian_kegiatan_harian->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete successfully'
                ];
            }
        }
    }
}