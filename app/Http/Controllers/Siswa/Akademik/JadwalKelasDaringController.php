<?php

namespace App\Http\Controllers\Siswa\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Siswa;
use App\Models\JadwalKelasMp;
use App\Models\KelasMp;
use App\Models\KelasMpGrup;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\PresensiMpMateri;
use App\Models\PengampuMapel;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class JadwalKelasDaringController extends BaseController{

    public function viewJadwalKelasDaring(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    	return view('siswa/akademik/jadwal-kelas-daring/view-jadwal-kelas-daring',compact('auth_data', 'semester_aktif'));

    }

    public function datatablesJadwalKelasDaring(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $siswa = Siswa::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $kelas_mp = KelasMp::where(['id_semester'=>$semester_aktif->id_semester,'id_kelas'=>$siswa->id_kelas])->whereNotNull('id_kelas_mp_grup')->pluck('id_kelas_mp');

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
                
                $open_class = 0;

                if(Carbon::now()->format('Y-m-d') == $item->tgl_presensi){

                    $start = strtotime($item->waktu_mulai);
                    $end = strtotime(Carbon::now()->format('H:i'));
                    $mins = ($start - $end) / 60;

                    if($mins <= 60) $open_class = 1;
                
                }

                $data = array(
                    'id' => $item->id_presensi_mp,
                    'open_class' => $open_class
                );
                return $data;
            })
            ->make(true);

    }

    public function downloadMateri(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = PresensiMpSiswa::find($id);
        $data->kehadiran = 1;
        $data->updated_by = $auth_data->pengguna->id_pengguna;
        $data->save();

        return response()->json('success');

    }

    public function viewDetailJadwalKelasDaring(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $data = PresensiMp::with('kelas_mp.kelas_mp_grup')->find($id);
        $data_materi = PresensiMpMateri::where('id_presensi_mp',$id)->get();

        $presensi_mp_siswa = PresensiMpSiswa::where(['id_presensi_mp'=>$id,'id_siswa'=>$siswa->id_siswa])->first();

        return view('siswa/akademik/jadwal-kelas-daring/view-detail-jadwal-kelas-daring', compact('auth_data','data','data_materi','presensi_mp_siswa'));

    }

}