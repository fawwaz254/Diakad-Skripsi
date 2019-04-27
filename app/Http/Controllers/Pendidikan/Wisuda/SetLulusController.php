<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PengajuanWisuda as PengajuanWisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;

use App\Models\Admisi as Admisi;

use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\RolePengguna as RolePengguna;
use App\Models\StatusPengguna as StatusPengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use Auth;
use DB;
use Session;
use Validator;

class SetLulusController extends BaseController{

    public function viewSetLulus(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('pendidikan/wisuda/set-lulus/view-set-lulus',compact('auth_data'));

    }

    public function datatablesSetLulus(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataSetLulus($auth_data);

        return Datatables::of($list_data)
                ->addColumn('nm_periode_wisuda', function($item){
                    if(! empty($item->id_pengajuan_wisuda) && empty($item->id_periode_wisuda)) {
                        return "Diajukan Di Periode Lain";
                    }
                    else {
                        return $item->nm_periode_wisuda;
                    }
                })
                ->addColumn('semester', function($item){
                    if(! empty($item->id_pengajuan_wisuda) && empty($item->id_periode_wisuda)) {
                        return "Diajukan Di Periode Lain";
                    }
                    else {
                        return $item->tahun_ajaran." ".$item->nm_semester;
                    }
                })
                ->addColumn('tgl_pengajuan_wisuda', function($item){
                    return strftime( "%d %B %Y %T", strtotime($item->tgl_pengajuan_wisuda));
                })
                ->addColumn('status_biodata', function($item){
                    if($item->status_biodata == 0) {
                        return "Belum Lengkap";
                    }
                    else {
                        return "Lengkap";
                    }
                })
                ->addColumn('status_lab', function($item){
                    if($item->status_lab == 0) {
                        return "Ada Tanggungan";
                    }
                    else {
                        return "Bebas Tanggungan";
                    }
                })
                ->addColumn('status_perpus', function($item){
                    if($item->status_perpus == 0) {
                        return "Ada Tanggungan";
                    }
                    else {
                        return "Bebas Tanggungan";
                    }
                })
                ->addColumn('status_ijasah', function($item){
                    if($item->status_ijasah == 0) {
                        return "Belum Cetak";
                    }
                    else {
                        return "Sudah Cetak";
                    }
                })
                ->addColumn('nomor_sk_kelulusan', function($item){
                    if(! empty($item->nomor_sk_kelulusan)) {
                        return $item->nomor_sk_kelulusan;
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('tgl_sk_kelulusan', function($item){
                    if(! empty($item->tgl_sk_kelulusan)) {
                        return strftime( "%d %B %Y", strtotime($item->tgl_sk_kelulusan));
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('nomor_ijasah', function($item){
                    if(! empty($item->nomor_ijasah)) {
                        return $item->nomor_ijasah;
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('tgl_kelulusan', function($item){
                    if(! empty($item->tgl_kelulusan)) {
                        return strftime( "%d %B %Y", strtotime($item->tgl_kelulusan));                    
                    }
                    else {
                        return "-";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                            'id' => $item->id_pengajuan_wisuda
                        );
                    
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataSetLulus($auth_data){

        $siswa = Siswa::select('pengajuan_wisuda.id_pengajuan_wisuda','siswa.id_siswa','periode_wisuda.id_periode_wisuda','periode_wisuda.nm_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengajuan_wisuda.status_biodata', 'pengajuan_wisuda.status_lab', 'pengajuan_wisuda.status_perpus', 'pengajuan_wisuda.status_ijasah', 'pengajuan_wisuda.nomor_sk_kelulusan', 'pengajuan_wisuda.tgl_sk_kelulusan', 'pengajuan_wisuda.nomor_ijasah', 'pengajuan_wisuda.tgl_kelulusan', 'pengajuan_wisuda.tgl_pengajuan_wisuda', 'pengajuan_wisuda.status_wisuda')
                    ->join('pengajuan_wisuda','pengajuan_wisuda.id_siswa','=','siswa.id_siswa')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->join('periode_wisuda','periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda')
                    ->join('semester','semester.id_semester','=','periode_wisuda.id_semester')
                    ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                    ->where('status_pengguna.aktif_status_pengguna','=',1)
                    ->where('pengajuan_wisuda.status_biodata','=',1)
                    ->where('pengajuan_wisuda.status_lab','=',1)
                    ->where('pengajuan_wisuda.status_perpus','=',1)
                    ->where('pengajuan_wisuda.status_ijasah','=',1)
                    ->whereNotNull('pengajuan_wisuda.nomor_sk_kelulusan')
                    ->whereNotNull('pengajuan_wisuda.tgl_sk_kelulusan')
                    ->whereNotNull('pengajuan_wisuda.nomor_ijasah')
                    ->whereNotNull('pengajuan_wisuda.tgl_kelulusan')
                    ->where('pengajuan_wisuda.status_wisuda','=',1)
                    ->orderBy('kelas.tingkat', 'asc')
                    ->orderBy('kelas.nm_kelas', 'asc')
                    ->orderBy('siswa.nis_siswa', 'asc')
                    ->get();

        return $siswa;
    }


    // Action POST
    public function actionSetLulus(Request $request, $mode, $id){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            // keperluan get url
            /*'id_pengajuan_wisuda' => 'required'*/
        ]);

        if($validator->fails() && $mode != 'set-lulus') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'set-lulus') {

                DB::beginTransaction();

                try {
                    // get info pengajuan_wisuda kode CALON_LULUS
                    $pengajuanWisudaSet = PengajuanWisuda::where('id_pengajuan_wisuda','=',$id)
                                        ->first();

                    // get status_pengguna kode CALON_LULUS
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna','=',"LULUS")
                                        ->where('status_join_table','=',3)
                                        ->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                        ->first();

                    // -- UPDATE id_kelas = null tabel siswa --
                    $siswa                      = Siswa::find($pengajuanWisudaSet->id_siswa);
                    $siswa->id_kelas            = null;
                    $siswa->updated_by          = $input->auth_data->pengguna->id_pengguna;
                    $siswa->updated_at          = $now;
                    $siswa->save();

                    // -- UPDATE status_pengguna tabel pengguna --
                    $pengguna                       = Pengguna::find($siswa->id_pengguna);
                    $pengguna->id_status_pengguna   = $statusPengguna->id_status_pengguna;
                    $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                    $pengguna->updated_at           = $now;
                    $pengguna->save();

                    // get role_pengguna
                    $rolePenggunaSet = RolePengguna::where('id_pengguna','=',$pengguna->id_pengguna)
                                    ->where('id_role','=',3)
                                    ->first();

                    // -- UPDATE role tabel role_pengguna --
                    $rolePengguna                   = RolePengguna::find($rolePenggunaSet->id_role_pengguna);
                    $rolePengguna->id_role          = 12;
                    $rolePengguna->updated_by       = $input->auth_data->pengguna->id_pengguna;
                    $rolePengguna->updated_at       = $now;
                    $rolePengguna->save();

                    // -- UPDATE tabel pengajuan_wisuda --
                    $pengajuanWisuda                        = PengajuanWisuda::find($id);
                    $pengajuanWisuda->status_wisuda         = 2;
                    $pengajuanWisuda->updated_by            = $input->auth_data->pengguna->id_pengguna;
                    $pengajuanWisuda->updated_at            = $now;
                    $pengajuanWisuda->save();

                    // -- UPDATE tabel admisi --
                    // get info admisi
                    $admisi = Admisi::join('status_pengguna','status_pengguna.id_status_pengguna','=','admisi.id_status_pengguna')
                                    ->where('admisi.id_siswa','=',$siswa->id_siswa)
                                    ->where('status_pengguna.kode_status_pengguna','=',"CALON_LULUS")
                                    ->where('admisi.id_pengajuan_wisuda','=',$id)
                                    ->first();

                    $admisi                         = Admisi::find($admisi->id_admisi);
                    $admisi->id_status_pengguna     = $statusPengguna->id_status_pengguna;
                    $admisi->updated_by             = $input->auth_data->pengguna->id_pengguna;
                    $admisi->updated_at             = $now;
                    $admisi->save();

                    DB::commit();
                    // all good

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Set Lulus successfully'
                    ];

                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                            'status' => 203, // GAGAL
                            'message' => 'Set Lulus Gagal!'
                        ];
                }
                
            }
        }
    }

}