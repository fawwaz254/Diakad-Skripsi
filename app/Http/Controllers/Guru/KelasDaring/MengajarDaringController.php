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
use App\Models\PresensiMpSiswa;
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

class MengajarDaringController extends BaseController
{
    public function viewMengajarDaring(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/kelas-daring/mengajar-daring/view-mengajar-daring', compact('auth_data'));
    }

    public function datatablesMengajarDaring(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

        $kelas_mp = KelasMp::whereIn('id_kelas_mp_grup', KelasMpGrup::where('id_guru',$guru->id_guru)->pluck('id_kelas_mp_grup'))->pluck('id_kelas_mp');
       

        if($input->status==0){
             $list_data = PresensiMp::with('kelas_mp.kelas_mp_grup')
                            ->whereHas('kelas_mp',function($q) use ($kelas_mp){
                                $q->whereIn('id_kelas_mp',$kelas_mp);
                            })
                            ->where(function($q){
                                $q->where('tgl_presensi','>',Carbon::now()->format('Y-m-d'));
                                $q->orWhere(function($q2){
                                    $q2->where('tgl_presensi',Carbon::now()->format('Y-m-d'))
                                    ->where('waktu_selesai','>=',Carbon::now()->format('H:i'));
                                });
                            })
                            ->get();    
        }
        else{
             $list_data = PresensiMp::with('kelas_mp.kelas_mp_grup')
                            ->whereHas('kelas_mp',function($q) use ($kelas_mp){
                                $q->whereIn('id_kelas_mp',$kelas_mp);
                            })
                            ->where(function($q){
                                $q->where('tgl_presensi','<',Carbon::now()->format('Y-m-d'));
                                $q->orWhere(function($q2){
                                    $q2->where('tgl_presensi',Carbon::now()->format('Y-m-d'))
                                    ->where('waktu_selesai','<=',Carbon::now()->format('H:i'));
                                });
                            })
                            ->whereNotNull('tgl_entry')
                            ->get();    
        }

        return Datatables::of($list_data)
            ->addColumn('nama_kelas_daring',function($item){
                return $item->kelas_mp->kelas_mp_grup->nm_kelas_mp_grup;
            })
            ->addColumn('kelas',function($item){
                    
                $data = '';
                $id_kelas_mp_grup = $item->kelas_mp->id_kelas_mp_grup;
                $list = KelasMp::with('kelas')->where('id_kelas_mp_grup',$id_kelas_mp_grup)->get();
                foreach($list as $key => $r){
                    if($key != $list->count()-1){
                         $data = $data . $r->kelas->nm_kelas . ',';
                    }
                    else{
                         $data = $data . $r->kelas->nm_kelas;
                    }
                }

                return $data;

            })
            ->editColumn('tgl_presensi', function ($item) {
                return date_format(date_create($item->tgl_presensi.' '.$item->waktu_mulai), "d M Y H:i");
            })
            ->editColumn('jenis_materi', function ($item) {
                return $item->jenis_materi_to_text();
            })
            ->addColumn('status_materi',function($item){
                if($item->materi->count()>0){
                    return 'Sudah Upload Materi';
                }
                else{
                    return 'Belum Upload Materi';
                }
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
                    'id' => $item->id_presensi_mp,
                    'is_task' => $item->is_task
                );
                return $data;
            })
            ->make(true);

    }

    public function viewDetailMengajarDaring(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = PresensiMp::with('kelas_mp.kelas_mp_grup')->find($id);
        $data_materi = PresensiMpMateri::where('id_presensi_mp',$id)->get();
        $peserta = PresensiMpSiswa::with('siswa.pengguna','siswa.kelas')->where('id_presensi_mp',$id)->get();

        return view('guru/kelas-daring/mengajar-daring/view-detail-mengajar-daring', compact('auth_data','data','data_materi','peserta'));

    }

    public function changeStatusMengajarDaring(Request $request,$id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = PresensiMp::find($id);
        $data->tgl_entry = Carbon::now()->format('Y-m-d');
        $data->updated_by = $auth_data->pengguna->id_pengguna;
        $data->save();

         return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'kelas-daring/mengajar-daring/'.$id,
                'message' => 'Update Kelas Mengajar Daring Success'
            ];

    }

}