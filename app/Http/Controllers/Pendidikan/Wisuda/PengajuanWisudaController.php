<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PengajuanWisuda as PengajuanWisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;

use App\Models\Admisi as Admisi;
use App\Models\Kelas;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\StatusPengguna as StatusPengguna;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibWisuda;

use Auth;
use DB;
use Session;
use Validator;

class PengajuanWisudaController extends BaseController{

    public function viewPengajuanWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data);

        $kelas_calon_lulus = Kelas::orderBy('tingkat', 'desc')->first();
        $data_kelas = Kelas::where('tingkat', $kelas_calon_lulus->tingkat)->orderBy('nm_kelas')->get();

    	return view('pendidikan/wisuda/pengajuan-wisuda/view-pengajuan-wisuda',compact('auth_data','data_periode_wisuda', 'data_kelas'));

    }

    public function actionViewDetailPengajuanWisuda(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_periode_wisuda' => 'required',
            'id_kelas' => 'required',
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
                        'path' => 'wisuda/pengajuan-wisuda/view-detail/'.$input->id_periode_wisuda.'/'.$input->id_kelas
                    ];
        }
    }

    public function viewDetailPengajuanWisuda(Request $request, $id_periode_wisuda, $id_kelas){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data, $id_periode_wisuda);

        return view('pendidikan/wisuda/pengajuan-wisuda/view-detail-pengajuan-wisuda',compact('auth_data','id_periode_wisuda','data_periode_wisuda', 'id_kelas'));

    }

    public function cancelPengajuanWisuda(Request $request, $id, $id_periode_wisuda, $id_kelas){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_pengajuan_wisuda = $this->fetchDataPengajuanWisudaDetail($auth_data, $id);

        // convert format date
        $tgl_pengajuan_wisuda = strftime( "%d %B %Y %T", strtotime($data_pengajuan_wisuda->tgl_pengajuan_wisuda));

        return view('pendidikan/wisuda/pengajuan-wisuda/cancel-pengajuan-wisuda',compact('auth_data','data_pengajuan_wisuda','id_periode_wisuda','id_kelas','tgl_pengajuan_wisuda'));

    }

    public function datatablesPengajuanWisuda(Request $request, $id_periode_wisuda, $id_kelas){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = $this->fetchDataPengajuanWisuda($auth_data, $id_periode_wisuda, $id_kelas);

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
                ->addColumn('action', function($item){
                    if(! empty($item->id_pengajuan_wisuda)) {
                        $data = array(
                            'id' => $item->id_pengajuan_wisuda,
                            'id_siswa' => $item->id_siswa,
                            'id_periode_wisuda' => $item->id_periode_wisuda,
                            'status_wisuda' => $item->status_wisuda
                        );
                    }
                    else {
                        $data = array(
                            'id' => null,
                            'id_siswa' => $item->id_siswa,
                            'id_periode_wisuda' => $item->id_periode_wisuda,
                            'status_wisuda' => $item->status_wisuda
                        );
                    }
                    
                    return $data;
                })
                ->make(true);
    }

    public function fetchDataPengajuanWisudaDetail($auth_data, $id_pengajuan_wisuda){
        $pengajuanWisuda = PengajuanWisuda::select('pengajuan_wisuda.id_pengajuan_wisuda', 'pengajuan_wisuda.id_periode_wisuda', 'periode_wisuda.nm_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengajuan_wisuda.tgl_pengajuan_wisuda')
                    ->join('periode_wisuda','periode_wisuda.id_periode_wisuda','=','pengajuan_wisuda.id_periode_wisuda')
                    ->join('semester','semester.id_semester','=','periode_wisuda.id_semester')
                    ->join('siswa','siswa.id_siswa','=','pengajuan_wisuda.id_siswa')
                    ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
                    ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
                    ->where('pengajuan_wisuda.id_pengajuan_wisuda','=',$id_pengajuan_wisuda)
                    ->first();

        return $pengajuanWisuda;
    }

    public function fetchDataPengajuanWisuda($auth_data, $id_periode_wisuda, $id_kelas){
        $kelas_calon_lulus = Kelas::orderBy('tingkat', 'desc')->first();
        $siswa = Siswa::select('pengajuan_wisuda.id_pengajuan_wisuda','siswa.id_siswa','periode_wisuda.id_periode_wisuda','periode_wisuda.nm_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengajuan_wisuda.status_wisuda')
            ->leftJoin('pengajuan_wisuda', function ($join) {
                    $join->on('pengajuan_wisuda.id_siswa','=','siswa.id_siswa')
                            ->where('pengajuan_wisuda.status_wisuda', '<>', 3)
                            ->whereNull('pengajuan_wisuda.deleted_at');
                })
            ->join('pengguna','pengguna.id_pengguna','=','siswa.id_pengguna')
            ->join('status_pengguna','status_pengguna.id_status_pengguna','=','pengguna.id_status_pengguna')
            ->join('kelas','kelas.id_kelas','=','siswa.id_kelas')
            ->leftJoin('periode_wisuda', function ($join) use ($id_periode_wisuda) {
                if($id_periode_wisuda != "0") {
                    $join->on('periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda')
                    ->where('periode_wisuda.id_periode_wisuda', '=', $id_periode_wisuda);
                }else{
                    $join->on('periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda');
                }
            })
            ->leftJoin('semester','semester.id_semester','=','periode_wisuda.id_semester')
            ->where('pengguna.id_sekolah','=',$auth_data->pengguna->id_sekolah)
            ->where('status_pengguna.aktif_status_pengguna','=',1)
            ->where('kelas.tingkat','=',$kelas_calon_lulus->tingkat)
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('kelas.nm_kelas', 'asc')
            ->orderBy('siswa.nis_siswa', 'asc');
        
            if(!empty($id_kelas)){
                $siswa = $siswa->where('kelas.id_kelas', $id_kelas);
            }
            $siswa = $siswa->get();

        return $siswa;
    }


    // Action POST
    public function actionPengajuanWisuda(Request $request, $mode, $id = null, $id_siswa = null, $id_periode_wisuda = null){

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            // keperluan get url
            'id_kelas' => 'required',
            'id_periode_wisuda' => 'required',
            // ------------------
            'keterangan_batal' => 'required'
        ]);

        if($validator->fails() && $mode != 'pengajuan') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            // ACTION ADD
            if($mode == 'cancel'){
                $pengajuanWisudaCek = PengajuanWisuda::where('id_pengajuan_wisuda','=',$id)->where('status_wisuda','=',2)->first();

                if($pengajuanWisudaCek){
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Cancel Pengajuan Wisuda! Siswa Sudah Lulus!'
                    ];
                }
                else{
                    $pengajuanWisuda        = PengajuanWisuda::find($id);

                    // get status_pengguna kode AKTIF
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna','=',"AKTIF")
                                        ->where('status_join_table','=',3)
                                        ->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                        ->first();

                    // get siswa->id_pengguna
                    $siswa  = Siswa::find($pengajuanWisuda->id_siswa);

                    // -- UPDATE status_pengguna tabel pengguna --
                    $pengguna                       = Pengguna::find($siswa->id_pengguna);
                    $pengguna->id_status_pengguna   = $statusPengguna->id_status_pengguna;
                    $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                    $pengguna->updated_at           = $now;
                    $pengguna->save();

                    // -- UPDATE tabel pengajuan_wisuda --
                    $pengajuanWisuda->keterangan_batal      = $input->keterangan_batal;
                    $pengajuanWisuda->status_wisuda         = 3;
                    $pengajuanWisuda->updated_by            = $input->auth_data->pengguna->id_pengguna;
                    $pengajuanWisuda->updated_at            = $now;
                    $pengajuanWisuda->save();

                    // -- DELETE tabel admisi --
                    // get admisi sesuai id_pengajuan_wisuda
                    $admisi = Admisi::where('id_pengajuan_wisuda','=',$pengajuanWisuda->id_pengajuan_wisuda)
                                        ->first();
                    $admisi->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $admisi->save();

                    $admisi->delete();
                    

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'wisuda/pengajuan-wisuda/view-detail/'.$input->id_periode_wisuda.'/'.$input->id_kelas,
                        'message' => 'Cancel Pengajuan Wisuda successfully'
                    ];
                }
            }
            elseif($mode == 'pengajuan') {
                DB::beginTransaction();

                try {
                    // make id
                    
                    // get status_pengguna kode CALON_LULUS
                    $statusPengguna = StatusPengguna::where('kode_status_pengguna','=',"CALON_LULUS")
                                            ->where('status_join_table','=',3)
                                            ->where('id_sekolah','=',$input->auth_data->pengguna->id_sekolah)
                                            ->first();
                    
                    foreach($input->id_siswa as $id_siswa){
                        $id_pengajuan_wisuda = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        // get siswa->id_pengguna
                        $siswa  = Siswa::find($id_siswa);

                        // make id
                        $id_admisi = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                        if($id_periode_wisuda != "0") {

                            // -- UPDATE status_pengguna tabel pengguna --
                            $pengguna                       = Pengguna::find($siswa->id_pengguna);
                            $pengguna->id_status_pengguna   = $statusPengguna->id_status_pengguna;
                            $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                            $pengguna->updated_at           = $now;
                            $pengguna->save();

                            // -- INSERT tabel pengajuan_wisuda --
                            $pengajuanWisuda                        = new PengajuanWisuda;
                            $pengajuanWisuda->id_pengajuan_wisuda   = $id_pengajuan_wisuda;
                            $pengajuanWisuda->id_siswa              = $id_siswa;
                            $pengajuanWisuda->id_kelas              = $siswa->id_kelas;
                            $pengajuanWisuda->id_periode_wisuda     = $id_periode_wisuda;
                            $pengajuanWisuda->status_wisuda         = 1;
                            $pengajuanWisuda->tgl_pengajuan_wisuda  = $now;
                            $pengajuanWisuda->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $pengajuanWisuda->save();

                            // -- INSERT tabel admisi --
                            // get periodeWisuda->id_semester
                            $periodeWisuda = PeriodeWisuda::find($id_periode_wisuda);

                            $admisi                         = new Admisi;
                            $admisi->id_admisi              = $id_admisi;
                            $admisi->id_siswa               = $id_siswa;
                            $admisi->id_semester            = $periodeWisuda->id_semester;
                            $admisi->id_status_pengguna     = $statusPengguna->id_status_pengguna;
                            $admisi->id_pengajuan_wisuda    = $id_pengajuan_wisuda;
                            $admisi->created_by             = $input->auth_data->pengguna->id_pengguna;
                            $admisi->save();
                        }
                        else {
                            // cek periode wisuda aktif sesuai semester aktif
                            $periodeWisuda = PeriodeWisuda::join('semester','semester.id_semester','=','periode_wisuda.id_semester')
                                                ->where('periode_wisuda.is_aktif','=',1)
                                                ->where('semester.is_aktif_semester','=',1)
                                                ->first();
                            // -- UPDATE status_pengguna tabel pengguna --
                            $pengguna                       = Pengguna::find($siswa->id_pengguna);
                            $pengguna->id_status_pengguna   = $statusPengguna->id_status_pengguna;
                            $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                            $pengguna->updated_at           = $now;
                            $pengguna->save();

                            // -- INSERT tabel pengajuan_wisuda --
                            $pengajuanWisuda                        = new PengajuanWisuda;
                            $pengajuanWisuda->id_pengajuan_wisuda   = $id_pengajuan_wisuda;
                            $pengajuanWisuda->id_siswa              = $id_siswa;
                            $pengajuanWisuda->id_kelas              = $siswa->id_kelas;
                            $pengajuanWisuda->id_periode_wisuda     = $periodeWisuda->id_periode_wisuda;
                            $pengajuanWisuda->status_wisuda         = 1;
                            $pengajuanWisuda->tgl_pengajuan_wisuda  = $now;
                            $pengajuanWisuda->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $pengajuanWisuda->save();

                            // -- INSERT tabel admisi --
                            $admisi                         = new Admisi;
                            $admisi->id_admisi              = $id_admisi;
                            $admisi->id_siswa               = $id_siswa;
                            $admisi->id_semester            = $periodeWisuda->id_semester;
                            $admisi->id_status_pengguna     = $statusPengguna->id_status_pengguna;
                            $admisi->id_pengajuan_wisuda    = $id_pengajuan_wisuda;
                            $admisi->created_by             = $input->auth_data->pengguna->id_pengguna;
                            $admisi->save();
                        }
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Pengajuan Wisuda successfully'
                    ];
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                        'status' => 203, // GAGAL
                        'message' => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error. Error '.$e->getLine()
                    ];
                }   
                
            }
        }
    }

}