<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;
use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use Session;
use Validator;

class InputPelanggaranController extends BaseController{

    public function viewInputPelanggaran(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        return view('guru/wali-kelas/input-pelanggaran/view-input-pelanggaran',compact('auth_data', 'wali_kelas'));

    }

    public function addInputPelanggaran(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        $id_pelanggaran_siswa = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('guru/wali-kelas/input-pelanggaran/add-input-pelanggaran',compact('auth_data','wali_kelas','data_siswa','id_pelanggaran_siswa'));

    }

    public function editInputPelanggaran($id, Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        // ambil data all siswa
        $data_siswa = LibSiswa::fetchDataSiswa($auth_data, $wali_kelas->id_kelas);

        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, $id);

        // convert format date
        $tgl_pelanggaran = strftime( "%d %B %Y %H:%M:%S", strtotime($data_pelanggaran_siswa->tgl_pelanggaran));

        return view('guru/wali-kelas/input-pelanggaran/edit-input-pelanggaran',compact('auth_data','wali_kelas','data_siswa','data_pelanggaran_siswa','tgl_pelanggaran'));

    }

    public function datatablesInputPelanggaran(Request $request) {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // get id_guru
        $guru = Guru::select('id_guru')
            ->where('id_pengguna','=',$auth_data->pengguna->id_pengguna)
            ->first();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_data = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, $wali_kelas->id_kelas, null, "1");

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
                    if( ! empty($item->nm_guru_input)){
                        return $item->nm_guru_input." (Guru)";
                    }
                    else{
                        return $item->nm_staff_input." (Tendik)";
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
            'id_siswa'              => 'required',
            'catatan_pelanggaran'   => 'required',
            'tgl_pelanggaran'       => 'required'
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

                $pelanggaranSiswa                               = new PelanggaranSiswa;
                $pelanggaranSiswa->id_pelanggaran_siswa         = $id;
                $pelanggaranSiswa->id_siswa                     = $input->id_siswa;
                $pelanggaranSiswa->id_guru_input                = $id_guru_input;
                $pelanggaranSiswa->catatan_pelanggaran          = $input->catatan_pelanggaran;
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tgl_pelanggaran),"Y-m-d H:i:s");
                $pelanggaranSiswa->aktor_input_pelanggaran      = 3;
                $pelanggaranSiswa->is_sudah_tindakan            = 0;
                $pelanggaranSiswa->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-pelanggaran',
                    'message' => 'Save Pelanggaran Siswa successfully'
                ];
            }
            elseif($mode == 'edit'){
                // make object to find id
                $pelanggaranSiswa                               = PelanggaranSiswa::find($id);
                $pelanggaranSiswa->id_siswa                     = $input->id_siswa;
                $pelanggaranSiswa->catatan_pelanggaran          = $input->catatan_pelanggaran;
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tgl_pelanggaran),"Y-m-d H:i:s");
                $pelanggaranSiswa->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->updated_at                   = $now;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-pelanggaran',
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