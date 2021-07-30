<?php

namespace App\Http\Controllers\Pendidikan\LaporanAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class JurnalGuruController extends BaseController{

    protected $modul_url = 'laporan-akademik';
    protected $menu_url = 'jurnal-guru';

    public function viewJurnalGuru(Request $request,$id_guru = null ,$id_semester = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if (!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                        ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                        ->get();
        $selected_guru = null;
        if(!empty($id_guru)) {
            $selected_guru = LibGuru::fetchDataAllGuru($auth_data,$id_guru);
        }

    	return view('pendidikan/laporan-akademik/jurnal-guru/view-jurnal-guru',compact('auth_data','selected_guru','selected_semester','data_semester','data_guru'));

    }

    public function datatablesJurnalGuru(Request $request,$id_guru,$id_semester){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::find($id_guru);
        $list_data = LibGuru::fetchDataJadwalKBM($auth_data, $guru->id_pengguna, $id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jam_mulai.":".$item->menit_mulai." - ".$item->jam_selesai.":".$item->menit_selesai;
                })
                ->addColumn('status_pjmp', function($item){
                    if($item->pjmp_pengampu_mp == 1) {
                        return "PJMP";
                    }
                    else {
                        return "Anggota";
                    }
                })
                ->make(true);

    }

}