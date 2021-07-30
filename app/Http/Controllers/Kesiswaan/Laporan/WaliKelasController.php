<?php

namespace App\Http\Controllers\Kesiswaan\Laporan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\LaporanWaliKelas;
use App\Models\LaporanWaliKelasDetail;
use App\Models\Bulan;
use App\Models\WaliKelas;

use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;

class WaliKelasController extends BaseController{

    public function viewWaliKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('kesiswaan/laporan/wali-kelas/view-wali-kelas',compact('auth_data'));
    }

    public function addWaliKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::today();
        $id_bulan = $now->month;

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('kesiswaan/laporan/wali-kelas/add-wali-kelas',compact('auth_data','data_semester','data_bulan', 'bulan'));

    }

    public function editWaliKelas($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $wali_kelas = LaporanWaliKelas::findOrFail($id);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('kesiswaan/laporan/wali-kelas/edit-wali-kelas',compact('auth_data','data_semester','data_bulan', 'wali_kelas'));

    }

    public function detailWaliKelas($id, Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_wali_kelas = LaporanWaliKelas::with('semester','bulan')->findOrFail($id);

        return view('kesiswaan/laporan/wali-kelas/detail-wali-kelas',compact('auth_data','laporan_wali_kelas'));

    }

    public function detailAjaxWaliKelas($id){

        $data = LaporanWaliKelasDetail::with('guru.pengguna','kelas')->find($id);
        return response()->json($data);

    }

    public function datatablesWaliKelas(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanWaliKelas::with('bulan','semester')
                                    ->where('created_by',$input->auth_data->pengguna->id_pengguna)
                                    ->where('role_id',$input->auth_data->role_aktif->id_role)
                                    ->get();

        return Datatables::of($list_data)
                ->addColumn('semester',function($item){
                    return $item->semester->tahun_ajaran.' '.$item->semester->nm_semester;
                })
                ->addColumn('bulan',function($item){
                    return $item->bulan->nm_bulan;
                })
                ->addColumn('status',function($item){
                    $pembilang = LaporanWaliKelasDetail::where('id_laporan_wali_kelas',$item->id_laporan_wali_kelas)->whereNotNull('status')->count();
                    $penyebut = LaporanWaliKelasDetail::where('id_laporan_wali_kelas',$item->id_laporan_wali_kelas)->count();
                    return $pembilang.'/'.$penyebut;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_laporan_wali_kelas
                    );
                    return $data;
                })
                ->make(true);
    }

    public function detailDataTable(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data =  LaporanWaliKelasDetail::with('guru','kelas')->where('id_laporan_wali_kelas',$id)->get();

        return Datatables::of($list_data)
                ->addColumn('guru',function($item){
                    return $item->guru->pengguna->gelar_depan.' '.$item->guru->pengguna->nm_pengguna.' '.$item->guru->pengguna->gelar_belakang;
                })
                ->addColumn('jabatan',function($item){
                    return 'Wali Kelas '.$item->kelas->nm_kelas;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id'     => $item->id_laporan_wali_kelas_detail,
                        'status' => $item->status
                    );
                    return $data;
                })
                ->make(true);

    }

    public function actionWaliKelas(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_bulan' => 'required'
        ]);
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // validasi data

            if($mode == 'add' || $mode =='edit'){

                $wali_kelas = WaliKelas::where(['id_semester'=>$input->id_semester,'is_aktif'=>1])->get();

                if($wali_kelas->count() == 0){

                    return [
                        'status' => 300, // FAILED
                        'message' => 'Mohon maaf data wali kelas pada semester yang anda pilih belum ada'
                    ];

                }

                $cek = LaporanWaliKelas::where(['id_semester'=>$input->id_semester,'id_bulan'=>$input->id_bulan,'role_id'=>$input->auth_data->role_aktif->id_role])->count();

                if($cek > 0){

                    return [
                        'status' => 300, // FAILED
                        'message' => 'Mohon maaf laporan wali kelas untuk semester dan bulan yang anda pilih sudah ada'
                    ];

                }

            }


            if($mode == 'add') {

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $data                        = new LaporanWaliKelas;
                $data->id_laporan_wali_kelas = $id;
                $data->role_id               = $input->auth_data->role_aktif->id_role;
                $data->id_semester           = $input->id_semester;
                $data->id_bulan              = $input->id_bulan;
                $data->created_by            = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                foreach($wali_kelas as $r){

                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $data2                                  = new LaporanWaliKelasDetail;
                    $data2->id_laporan_wali_kelas_detail    = $id;
                    $data2->id_laporan_wali_kelas           = $data->id_laporan_wali_kelas;
                    $data2->id_guru                         = $r->id_guru;
                    $data2->id_kelas                        = $r->id_kelas;
                    $data2->created_by                      = $input->auth_data->pengguna->id_pengguna;
                    $data2->save();

                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'laporan/wali-kelas',
                    'message' => 'Save Laporan Wali Kelas successfully'
                ];

            }

            elseif($mode == 'edit'){
 
                $data                        = LaporanWaliKelas::find($id);

                $semester_seblumnya = $data->id_semester;

                $data->id_laporan_wali_kelas = $id;
                $data->id_semester           = $input->id_semester;
                $data->id_bulan              = $input->id_bulan;
                $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                if($input->id_semester != $semester_seblumnya){

                    LaporanWaliKelasDetail::where('id_laporan_wali_kelas', $id)
                                    ->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);

                    LaporanWaliKelasDetail::where('id_laporan_wali_kelas', $id)->delete();

                    foreach($wali_kelas as $r){

                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                        $data2                                  = new LaporanWaliKelasDetail;
                        $data2->id_laporan_wali_kelas_detail    = $id;
                        $data2->id_laporan_wali_kelas           = $data->id_laporan_wali_kelas;
                        $data2->id_guru                         = $r->id_guru;
                        $data2->id_kelas                        = $r->id_kelas;
                        $data2->created_by                      = $input->auth_data->pengguna->id_pengguna;
                        $data2->save();

                    }

                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'laporan/wali-kelas',
                    'message' => 'Update Laporan Wali Kelas  successfully'
                ];

            }

            elseif($mode == 'delete'){
                    
                $data               = LaporanWaliKelas::find($id);
                $data->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $data->save();
                $data->delete();

                LaporanWaliKelasDetail::where('id_laporan_wali_kelas', $id)
                                    ->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);

                LaporanWaliKelasDetail::where('id_laporan_wali_kelas', $id)->delete();


                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Laporan Wali Kelas successfully'
                ];

            }

        }
    }

    public function actionDetailWaliKelas(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id = $input->id_laporan_wali_kelas_detail;

        $data = LaporanWaliKelasDetail::find($id);
        $data->status = $input->status;
        $data->catatan = $input->catatan;
        $data->updated_by = $input->auth_data->pengguna->id_pengguna;
        $data->save();

        return [
            'status' => 205,
            'message' => 'Update Status Laporan Wali Kelas Successfully'
        ];


    }

}