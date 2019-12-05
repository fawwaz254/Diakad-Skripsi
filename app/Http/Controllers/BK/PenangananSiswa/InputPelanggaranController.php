<?php

namespace App\Http\Controllers\BK\PenangananSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;
use App\Models\Guru as Guru;
use App\Models\Siswa as Siswa;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class InputPelanggaranController extends BaseController{

    public function viewInputPelanggaran(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('bk/penanganan-siswa/input-pelanggaran/view-input-pelanggaran',compact('auth_data'));

    }

    public function addInputPelanggaran(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil data all kelas
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        // ambil data all kategori
        $data_kategori = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        $id_pelanggaran_siswa = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/penanganan-siswa/input-pelanggaran/add-input-pelanggaran',compact('auth_data','data_semester','data_kelas','data_kategori','id_pelanggaran_siswa'));

    }

    public function editInputPelanggaran($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        // ambil data all kelas
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        // ambil data all kategori
        $data_kategori = LibDataPelanggaran::fetchDataKategoriPelanggaran($auth_data);

        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, $id);

        // ambil data siswa pelanggar
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, null, $data_pelanggaran_siswa->id_siswa);

        // ambil data siswa sekelas
        $data_siswa_sekelas = LibSiswa::fetchDataSiswa($auth_data, $data_siswa->id_kelas);

        // ambil data subkategori by kategori
        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $data_pelanggaran_siswa->id_kategori_pelanggaran);

        // convert format date
        $tgl_pelanggaran = strftime( "%d %B %Y %H:%M:%S", strtotime($data_pelanggaran_siswa->tgl_pelanggaran));

        $is_khusus = 0;

        if($auth_data->pengguna->id_pengguna == $data_pelanggaran_siswa->created_by) {
            $is_khusus = 1;
        }

        return view('bk/penanganan-siswa/input-pelanggaran/edit-input-pelanggaran',compact('auth_data','data_semester','data_kelas','data_kategori','data_siswa','data_siswa_sekelas','data_pelanggaran_siswa', 'data_subkategori', 'tgl_pelanggaran', 'is_khusus'));

    }

    public function ajaxGetSiswaByKelas(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $input->kelas);

        return $data_siswa;
    }

    public function ajaxGetSubkategoriByKategori(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data subkategori by kategori
        $data_subkategori = LibDataPelanggaran::fetchDataSubkategoriPelanggaranByKategori($auth_data, $input->kategori);

        return $data_subkategori;
    }

    public function datatablesInputPelanggaran(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, null, "1");

        return Datatables::of($list_data)
                ->addColumn('nm_siswa', function($item){
                    return $item->nm_pengguna;
                })
                ->addColumn('aktor_input_pelanggaran', function($item){
                    if ($item->aktor_input_pelanggaran == 1) {
                        return "Role BK";
                    }
                    elseif ($item->aktor_input_pelanggaran == 2) {
                        return "Kesiswaan";
                    }
                    elseif ($item->aktor_input_pelanggaran == 3) {
                        return "Wali Kelas";
                    }
                })
                ->addColumn('nm_input', function($item){
                    if( ! empty($item->nm_guru_input)) {
                        if( ! empty($item->gelar_depan_guru) && ! empty($item->gelar_belakang_guru)) {
                            return $item->gelar_depan_guru." ".$item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                        }
                        elseif( ! empty($item->gelar_depan_guru)) {
                            return $item->gelar_depan_guru." ".$item->nm_guru_input." (Guru)";
                        }
                        elseif( ! empty($item->gelar_belakang_guru)) {
                            return $item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                        }
                        else {
                            return $item->nm_guru_input." (Guru)"; 
                        }
                    }
                    else {
                        if( ! empty($item->gelar_depan_staff) && ! empty($item->gelar_belakang_staff)) {
                            return $item->gelar_depan_staff." ".$item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                        }
                        elseif( ! empty($item->gelar_depan_staff)) {
                            return $item->gelar_depan_staff." ".$item->nm_staff_input." (Tendik)";
                        }
                        elseif( ! empty($item->gelar_belakang_staff)) {
                            return $item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                        }
                        else {
                            return $item->nm_staff_input." (Tendik)"; 
                        }
                    }
                })
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('tingkat_pelanggaran', function($item){
                    return $item->tingkat_kategori_pelanggaran.".".$item->tingkat_subkategori_pelanggaran;
                })
                ->addColumn('catatan_pelanggaran_khusus', function($item) use($auth_data){
                    if ($item->created_by == $auth_data->pengguna->id_pengguna) {
                        return "Klik Action Untuk Melihat/Mengedit";
                    }
                    else {
                        return "Khusus User Input";
                    }
                })
                ->addColumn('tgl_pelanggaran', function($item){
                    return strftime( "%d %B %Y %H:%M:%S", strtotime($item->tgl_pelanggaran));
                })
                ->addColumn('is_sudah_tindakan', function($item){
                    if ($item->is_sudah_tindakan == 0) {
                        return "Belum";
                    }
                    elseif ($item->is_sudah_tindakan == 1) {
                        return "Sudah";
                    }
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_pelanggaran_siswa,
                        'is_sudah_tindakan' => $item->is_sudah_tindakan
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionInputPelanggaran(Request $request, $mode, $id = null) {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_siswa'                      => 'required',
            'id_subkategori_pelanggaran'    => 'required',
            'id_semester'                   => 'required',
            'catatan_pelanggaran'           => 'required',
            /*'catatan_pelanggaran_khusus'    => 'required',*/
            'tgl_pelanggaran'               => 'required'
        ]);
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add') {
                if ($input->auth_data->pengguna->status_join_table == 2) {
                    // get id_guru
                    $guru = Guru::select('id_guru')
                        ->where('id_pengguna','=',$input->auth_data->pengguna->id_pengguna)
                        ->first();

                    $id_guru_input = $guru->id_guru;
                }
                else {
                    $id_guru_input = null;
                }

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $siswa = Siswa::where('id_siswa','=',$input->id_siswa)->first();

                $pelanggaranSiswa                               = new PelanggaranSiswa;
                $pelanggaranSiswa->id_pelanggaran_siswa         = $id;
                $pelanggaranSiswa->id_siswa                     = $input->id_siswa;
                $pelanggaranSiswa->id_kelas                     = $siswa->id_kelas;
                $pelanggaranSiswa->id_guru_input                = $id_guru_input;
                $pelanggaranSiswa->id_semester                  = $input->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran          = $input->catatan_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran_khusus   = $input->catatan_pelanggaran_khusus;
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tgl_pelanggaran),"Y-m-d H:i:s");
                $pelanggaranSiswa->aktor_input_pelanggaran      = 1;
                $pelanggaranSiswa->is_sudah_tindakan            = 0;
                $pelanggaranSiswa->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/input-pelanggaran',
                    'message' => 'Save Pelanggaran Siswa successfully'
                ];
            }
            elseif($mode == 'edit'){

                $siswa = Siswa::where('id_siswa','=',$input->id_siswa)->first();
                
                // make object to find id
                $pelanggaranSiswa                               = PelanggaranSiswa::find($id);
                $pelanggaranSiswa->id_siswa                     = $input->id_siswa;
                $pelanggaranSiswa->id_kelas                     = $siswa->id_kelas;
                $pelanggaranSiswa->id_semester                  = $input->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran   = $input->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran          = $input->catatan_pelanggaran;
                if(! empty($input->catatan_pelanggaran_khusus)) {
                    $pelanggaranSiswa->catatan_pelanggaran_khusus   = $input->catatan_pelanggaran_khusus;
                }
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tgl_pelanggaran),"Y-m-d H:i:s");
                $pelanggaranSiswa->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->updated_at                   = $now;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/input-pelanggaran',
                    'message' => 'Update Pelanggaran Siswa successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($tindakanPelanggaran = TindakanPelanggaran::where('id_pelanggaran_siswa',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Pelanggaran Siswa'
                    ]; 
                }
                else{
                    // make object to find id
                    $pelanggaranSiswa               = PelanggaranSiswa::find($id);
                    $pelanggaranSiswa->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $pelanggaranSiswa->save();

                    $pelanggaranSiswa->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Pelanggaran Siswa successfully'
                    ];
                }
            }
        }
    }


}