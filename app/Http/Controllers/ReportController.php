<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PelanggaranSiswa;
use App\Models\Role;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\BukuAlat;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class ReportController extends BaseController{

    public function viewAllDiakad(){

        $role = Role::all();

        $data = array();

        foreach($role as $key => $r){

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            if($r->nm_role == 'Bimbingan Konseling'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
            }

            elseif($r->nm_role == 'Sarana Prasarana'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
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

    public function checkProgress($id_role){

        $role = Role::find($id_role);

        if($id_role == 5){ // Bimbingan & Konseling

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

        }

        elseif($id_role == 10){ // Sarana Prasarana

            $gedung = Gedung::count();
            $ruangan = ruangan::count();
            $bukualat = BukuAlat::count();

            $param[0]['catatan'] = 'Belum melakukuan input data gedung';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Belum melakukuan input data ruangan';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Belum melakukuan input data buku/alat';
            $param[2]['status'] = 0;

            if($gedung){
                $param[0]['status'] = 1;
            }

            if($ruangan){
                $param[1]['status'] = 1;
            }

            if($bukualat){
                $param[2]['status'] = 1;
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

        }

        $data['nm_role'] = $role->nm_role;
        $data['status'] = $status;
        $data['catatan'] = $param;

        return response()->json($data);

    }

}