<?php

namespace App\Http\Controllers\Guru\KelasDaring;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\JadwalKelasMp;
use App\Models\KelasMp;
use App\Models\KelasMpGrup;
use App\Models\PresensiMp;
use App\Models\PresensiMpMateri;
use App\Models\PengampuMapel;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class SettingKelasDaringController extends BaseController
{
    public function viewKelasDaring(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        return view('guru/kelas-daring/setting-kelas-daring/view-setting-kelas-daring', compact('auth_data', 'semester_aktif'));
    }

    public function viewAddKelasDaring(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return view('guru/kelas-daring/setting-kelas-daring/view-add-setting-kelas-daring', compact('auth_data','data_guru'));
    }

    public function viewEditKelasDaring(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = KelasMpGrup::find($id);

        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return view('guru/kelas-daring/setting-kelas-daring/view-edit-setting-kelas-daring', compact('auth_data', 'item','data_guru'));
    }

    public function viewAddPresensiMpKelasDaring(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = KelasMpGrup::find($id);

        return view('guru/kelas-daring/setting-kelas-daring/jadwal-daring/view-add-jadwal-daring', compact('auth_data', 'item'));
    }

    public function viewEditMateriKelasDaring(Request $request, $id_kelas_mp_grup = '-', $id_presensi_mp = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = PresensiMp::find($id_presensi_mp);
        $data_materi = PresensiMpMateri::where('id_presensi_mp', $id_presensi_mp)->get();

        return view('guru/kelas-daring/setting-kelas-daring/jadwal-daring/view-add-edit-materi', compact('auth_data', 'id_kelas_mp_grup', 'item', 'data_materi'));
    }

    public function viewKelasMpKelasDaring(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $item = KelasMpGrup::find($id);

        return view('guru/kelas-daring/setting-kelas-daring/kelas-daring/view-kelas-daring', compact('auth_data', 'semester_aktif', 'item'));
    }

    public function viewPresensiMpKelasDaring(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $item = KelasMpGrup::find($id);

        // cek apakah sudah mengisi kelas

        $cek_kelas_mp = KelasMp::where('id_kelas_mp_grup',$id)->count();

        return view('guru/kelas-daring/setting-kelas-daring/jadwal-daring/view-jadwal-daring', compact('auth_data', 'semester_aktif', 'item','cek_kelas_mp'));
    }

    public function datatablesKelasDaring(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = KelasMpGrup::with('kelas_mp', 'kelas_mp.kelas', 'check_kelas_mp.presensi_mp','guru.pengguna')->where('id_semester', $semester_aktif->id_semester);

        if($auth_data->pengguna->status_join_table ==2){
            $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
            $list_data = $list_data->where('id_guru', $guru->id_guru);
        }

        return Datatables::of($list_data)
            ->addColumn('nm_guru',function($item){
                return $item->guru->pengguna->nm_pengguna;
            })
            ->addColumn('kelas', function ($item) {
                $data_nm_kelas = array();
                foreach($item->kelas_mp as $kelas_mp){
                    $data_nm_kelas[] = $kelas_mp->kelas->nm_kelas;
                }

                $data = array(
                    'id' => $item->id_kelas_mp_grup,
                    'data_nm_kelas' => $data_nm_kelas
                );
                return $data;
            })
            ->addColumn('jadwal', function ($item) {
                $data_jadwal = array();
                if(!empty($item->check_kelas_mp)){
                    foreach($item->check_kelas_mp->presensi_mp as $presensi_mp){
                        $data_jadwal[] = $presensi_mp->tgl_presensi;
                    }
                }

                $data = array(
                    'id' => $item->id_kelas_mp_grup,
                    'data_jadwal' => $data_jadwal
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_mp_grup
                );
                return $data;
            })
            ->rawColumns(['kelas'])
            ->make(true);
    }

    public function datatablesKelasMpKelasDaring(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $guru = Guru::find($input->id_guru);
        $pengampu_mapel = PengampuMapel::where('id_guru',$guru->id_guru)->pluck('id_mata_pelajaran');

        $list_data = KelasMp::with('kelas', 'mata_pelajaran', 'mata_pelajaran.jenis_mata_pelajaran')
                            ->where('id_semester', $semester_aktif->id_semester)
                            ->whereIn('id_mata_pelajaran',$pengampu_mapel);

        if(!empty($input->status) && $input->status == 1){
            $list_data = $list_data->where('id_kelas_mp_grup', $input->id);
        }else{
            $list_data = $list_data->whereNull('id_kelas_mp_grup');
        }

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_mp
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesPresensiMpKelasDaring(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $item = KelasMp::with('presensi_mp')->where('id_semester', $semester_aktif->id_semester)->where('id_kelas_mp_grup', $input->id)->first();

        $list_data = $item->presensi_mp;

        return Datatables::of($list_data)
            ->editColumn('tgl_presensi', function ($item) {
                return date_format(date_create($item->tgl_presensi.' '.$item->waktu_mulai), "d M Y H:i");
            })
            ->editColumn('jenis_materi', function ($item) {
                return $item->jenis_materi_to_text();
            })
            ->addColumn('status_jadwal', function ($item) {
                if(!empty($item->tgl_entry)){
                    return 'Sudah diadakan';
                }else{
                    return 'Belum diadakan';
                }
            })
            ->addColumn('action', function ($item) use ($input) {
                $data = array(
                    'grup' => $input->id,
                    'id' => $item->id_presensi_mp
                );
                return $data;
            })
            ->make(true);
    }

    public function actionAddKelasDaring(Request $request) {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if($auth_data->pengguna->status_join_table != 2){
            $validator = Validator::make($request->all(), [
                'nm_kelas_mp_grup'  => 'required',
                'id_guru'           => 'required',
            ]);
        }

        else{
            $validator = Validator::make($request->all(), [
                'nm_kelas_mp_grup'  => 'required',
            ]);
        }
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $auth_data = $input->auth_data;

            if($auth_data->pengguna->status_join_table != 2){
                $guru = Guru::where('id_guru', '=', $input->id_guru)->first();
            }

            else{
                $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
            }

            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
    
            if(empty($input->id)){
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $kelas_mp_grup                      = new KelasMpGrup;
                $kelas_mp_grup->id_kelas_mp_grup    = $id;
                $kelas_mp_grup->id_semester         = $semester_aktif->id_semester;
                $kelas_mp_grup->id_guru             = $guru->id_guru;
                $kelas_mp_grup->created_by          = $auth_data->pengguna->id_pengguna;
            }else{
                $kelas_mp_grup                      = KelasMpGrup::find($input->id);
                $kelas_mp_grup->id_guru             = $guru->id_guru;
                $kelas_mp_grup->updated_by          = $auth_data->pengguna->id_pengguna;
            }

            $kelas_mp_grup->nm_kelas_mp_grup                  = $input->nm_kelas_mp_grup;
            $kelas_mp_grup->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'kelas-daring/jadwal-kelas',
                'message' => 'Save Jadwal Kelas'
            ];
        }
    }

    public function actionKelasMpKelasDaring(Request $request, $mode = '-') {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp'       => 'required',
            'id'  => 'required',
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            if($mode == 'set'){
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $kelas_mp                               = KelasMp::find($input->id_kelas_mp);
                $kelas_mp->id_kelas_mp_grup             = $input->id;
                $kelas_mp->updated_by                   = $auth_data->pengguna->id_pengguna;
                $kelas_mp->save();
        
                return [
                    'status' => 200, // SUCCESS
                    'message' => 'Save Jadwal Kelas'
                ];
            }else{
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $kelas_mp                               = KelasMp::find($input->id_kelas_mp);
                $kelas_mp->id_kelas_mp_grup             = null;
                $kelas_mp->updated_by                   = $auth_data->pengguna->id_pengguna;
                $kelas_mp->save();
        
                return [
                    'status' => 200, // SUCCESS
                    'message' => 'Delete Jadwal Kelas'
                ];
            }
        }
    }

    public function actionPresensiMpKelasDaring(Request $request, $mode = '-') {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp_grup'  => 'required',
            'pertemuan_ke'  => 'required',
            'jenis_materi'  => 'required',
            'is_daring'  => 'required',
            'torelansi_terlambat'  => 'required',
            'tgl_presensi'  => 'required',
            'waktu_mulai'  => 'required',
            'waktu_selesai'  => 'required',
        ]);
        
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            if($mode == 'add-jadwal'){
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $data_kelas_mp = KelasMp::where('id_kelas_mp_grup', $input->id_kelas_mp_grup)->get();

                foreach($data_kelas_mp as $kelas_mp){
                    if($check_presensi = PresensiMp::where('tgl_presensi', $input->tgl_presensi)->where('id_kelas_mp', $kelas_mp->id_kelas_mp)->first()){

                    }else{
                        $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $presensi_mp = new PresensiMp;
                        $presensi_mp->id_presensi_mp = $id;
                        $presensi_mp->id_kelas_mp = $kelas_mp->id_kelas_mp;
                        $presensi_mp->pertemuan_ke = $input->pertemuan_ke;
                        $presensi_mp->jenis_materi = $input->is_daring;
                        $presensi_mp->is_daring = $input->is_daring;
                        $presensi_mp->torelansi_terlambat = $input->torelansi_terlambat;
                        $presensi_mp->waktu_mulai = $input->waktu_mulai;
                        $presensi_mp->waktu_selesai = $input->waktu_selesai;
                        $presensi_mp->tgl_presensi = $input->tgl_presensi;
                        $presensi_mp->created_by = $auth_data->pengguna->id_pengguna;
                        $presensi_mp->save();
                    }
                }
        
                return [
                    'status' => 202, // SUCCESS
                    'path' => 'kelas-daring/jadwal-kelas/data-jadwal/'.$input->id_kelas_mp_grup,
                    'message' => 'Save Jadwal Kelas'
                ];
            }else{
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $auth_data = $input->auth_data;
        
                $kelas_mp                               = KelasMp::find($input->id_kelas_mp);
                $kelas_mp->id_kelas_mp_grup             = null;
                $kelas_mp->updated_by                   = $auth_data->pengguna->id_pengguna;
                $kelas_mp->save();
        
                return [
                    'status' => 200, // SUCCESS
                    'message' => 'Delete Jadwal Kelas'
                ];
            }
        }
    }

    public function actionEditMateriKelasDaring(Request $request, $mode = '-') {
        $input = (object) $request->input();
        
        if($mode == 'add-materi'){
            $validator = Validator::make($request->all(), [
                'id_kelas_mp_grup'  => 'required',
                'id_presensi_mp'  => 'required',
                'uraian_materi'  => 'required',
            ]);
            
            if($validator->fails()) {
                return [
                    'status' => 300, // FAILED
                    'message' => $validator->errors()->first()
                ];
            };

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $auth_data = $input->auth_data;

            $presensi_mp = PresensiMp::find($input->id_presensi_mp);

            $data_kelas_mp = KelasMp::where('id_kelas_mp_grup', $input->id_kelas_mp_grup)->get();

            foreach($data_kelas_mp as $kelas_mp){
                if($check_presensi = PresensiMp::where('tgl_presensi', $presensi_mp->tgl_presensi)->where('id_kelas_mp', $kelas_mp->id_kelas_mp)->first()){
                    $check_presensi->uraian_materi = $input->uraian_materi;
                    $check_presensi->updated_by = $auth_data->pengguna->id_pengguna;
                    $check_presensi->save();
                }
            }
    
            return [
                'status' => 202, // SUCCESS
                'path' => 'kelas-daring/jadwal-kelas/data-jadwal/'.$input->id_kelas_mp_grup,
                'message' => 'Success simpan Materi'
            ];
        }else if($mode == 'add-file'){
            $validator = Validator::make($request->all(), [
                'id_kelas_mp_grup'  => 'required',
                'id_presensi_mp'  => 'required',
                'nm_materi' => 'required',
                'file' => 'file|required|max:10240' // 10 MB
            ]);

            if($validator->fails()) {
                return [
                    'status' => 300, // FAILED
                    'message' => $validator->errors()->first()
                ];
            }

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $auth_data = $input->auth_data;
            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

            $kelas_mp_grup2 = KelasMpGrup::find($input->id_kelas_mp_grup);
            $guru = Guru::where('id_guru', '=', $kelas_mp_grup2->id_guru)->first();
            $presensi_mp = PresensiMp::find($input->id_presensi_mp);

            $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru/'.$guru->id_guru.'/materi/'.$input->id_kelas_mp_grup, request()->file, 'public');
            
            $data_kelas_mp = KelasMp::where('id_kelas_mp_grup', $input->id_kelas_mp_grup)->get();

            foreach($data_kelas_mp as $kelas_mp){
                if($check_presensi = PresensiMp::where('tgl_presensi', $presensi_mp->tgl_presensi)->where('id_kelas_mp', $kelas_mp->id_kelas_mp)->first()){
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $presensi_mp_materi = new PresensiMpMateri;
                    $presensi_mp_materi->id_presensi_mp_materi = $id;
                    $presensi_mp_materi->id_presensi_mp = $check_presensi->id_presensi_mp;
                    $presensi_mp_materi->nm_materi = $input->nm_materi;
                    $presensi_mp_materi->link_materi = Storage::disk('spaces')->url($file);
                    $presensi_mp_materi->created_by = $auth_data->pengguna->id_pengguna;
                    $presensi_mp_materi->save();
                }
            }
    
            return [
                'status' => 204, // SUCCESS
                'path' => 'kelas-daring/jadwal-kelas/materi/edit/'.$input->id_kelas_mp_grup.'/'.$input->id_presensi_mp,
                'message' => 'Success upload File Materi'
            ];
        }
    }
}
