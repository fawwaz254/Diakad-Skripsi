<?php

namespace App\Http\Controllers\Pendidikan\LaporanAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class JurnalKelasController extends BaseController{

    protected $modul_url = 'laporan-akademik';
    protected $menu_url = 'jurnal-kelas';

    public function viewJurnalKelas(Request $request,$id_kelas = null ,$id_semester = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $auth_data->modul_url = $this->modul_url;
        $auth_data->menu_url = $this->menu_url;

        $selected_kelas = null;
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        if(!empty($id_kelas)) {
            $selected_kelas = LibKelas::fetchDataKelas($auth_data,$id_kelas);
        }

        $selected_semester = null;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        if(!empty($id_semester)) {
            $selected_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);
        }

    	return view('pendidikan/laporan-akademik/jurnal-kelas/view-jurnal-kelas',compact('auth_data','selected_semester','data_semester','selected_kelas','data_kelas'));

    }

    public function datatablesJurnalKelas(Request $request,$id_kelas,$id_semester){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibGuru::fetchDataJadwalKBMByKelas($auth_data,$id_semester, $id_kelas);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function($item){
                    return $item->kelas_mp->mata_pelajaran->kd_mata_pelajaran." - ".$item->kelas_mp->mata_pelajaran->nm_mata_pelajaran;
                })
                ->addColumn('jadwal_hari', function($item){
                    return $item->jadwal_hari->nm_jadwal_hari;
                })
                ->addColumn('ruangan', function($item){
                    return $item->ruangan->nm_ruangan;
                })
                ->addColumn('jadwal_jam', function($item){
                    return $item->jadwal_jam_mulai->jam_mulai.":".$item->jadwal_jam_mulai->menit_mulai." - ".$item->jadwal_jam_mulai->jam_selesai.":".$item->jadwal_jam_mulai->menit_selesai;
                })
                ->make(true);


    }


}