<?php

namespace App\Http\Controllers\Sekretariat\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\LaporanWaliKelas;
use App\Models\LaporanWaliKelasDetail;

use Auth;
use DB;
use Session;
use Validator;

class WaliKelasController extends BaseController
{
    public function viewWaliKelas(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
       
        return view('sekretariat/laporan/wali-kelas/view-wali-kelas',compact('auth_data'));
    }

    public function datatablesWaliKelas(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanWaliKelas::with('bulan','semester','role')->get();

        return Datatables::of($list_data)
                ->addColumn('role',function($item){
                    return $item->role->nm_role;
                })
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

    public function detailWaliKelas($id, Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_wali_kelas = LaporanWaliKelas::with('semester','bulan','role')->findOrFail($id);

        return view('sekretariat/laporan/wali-kelas/detail-wali-kelas',compact('auth_data','laporan_wali_kelas'));

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

}