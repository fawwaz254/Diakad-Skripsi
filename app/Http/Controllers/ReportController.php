<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Jalur as Jalur;
use App\Models\JalurSiswa as JalurSiswa;
use App\Models\PelanggaranSiswa;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class ReportController extends BaseController{

    public function viewAllDiakad(){

        $role = ['Akademik','Bimbingan Konseling','Humas','Kesiswaan','Keuangan','Pendidikan','PPDB','Rapor & Buku Induk','Sarana Prasarana','Sumber Daya','Tenaga Pendidik'];

        $data = array();

        foreach($role as $key => $r){

            $data[$key]['role'] = $r;
            $data[$key]['index'] = $key;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            if($key == 1){
                $temp = $this->check_progress_bk();
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
            }

        }

    	return view('reporting-dashboard.all-diakad',compact('data'));

    }

    public function count_multidimension($param){
        $jumlah = 0;
        foreach ($param as $key => $value) {
            if($value['status'] == 1){
                $jumlah++;
            }
        }

        return $jumlah;
    }

    public function check_progress_bk(){

        $pelanggaran_siswa = PelanggaranSiswa::count();
        $tindakan_pelanggaran = PelanggaranSiswa::where('is_sudah_tindakan', 1)->count();

        $param[0]['catatan'] = 'Belum ada pelanggaran yang diinputkan';
        $param[0]['status'] = 0;

        $param[1]['catatan'] = 'Belum melakukan tindakan peda pelanggaran';
        $param[1]['status'] = 0;

        if($pelanggaran_siswa){
            $param[0]['status'] = 1;
        }

        if($tindakan_pelanggaran){
            $param[1]['status'] = 1;
        }

        $jumlah_diisi = $this->count_multidimension($param);

        if($jumlah_diisi == 0){
            $status = 'Belum Digunakan';
        }

        elseif($jumlah_diisi < count($param)){
            $status = 'Sudah digunakan namun belum maksimal';
        }

        else{
            $status = 'Sudah digunakan dengan maksimal';
        }

        $data['status'] = $status;
        $data['catatan'] = $param;

        return $data;

    }

}