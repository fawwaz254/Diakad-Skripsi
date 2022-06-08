<?php

namespace App\Http\Controllers\Siswa\Alumni;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Yajra\Datatables\Datatables;
use App\Libraries\Humas\LibAlumni;

class TracerAlumniSiswaController extends Controller
{

    const PATH = 'alumni/tracer-alumni';
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat_instansi', 'kontak_instansi', 'bidang_usaha_instansi', 'tahun_masuk_instansi', 'kapan_mulai_bekerja', 'lama_bekerja'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat_perguruan', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk_perguruan'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat_usaha', 'kontak_usaha', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['status_menunggu'];
    const FETCH_SMP = ['nm_sekolah','alamat_sekolah','jurusan','jenis_sekolah','tahun_masuk_sekolah'];

    public function viewTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if( $auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1'){
            return view('siswa.alumni.view-tracer-alumni-smp');
        }else{
            // return view('humas.alumni.tracer-alumni.view-tracer-alumni');
        }
    }


    public function addTracerAlumni(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('tingkat', 3)->orWhere('tingkat',9)->get();
        $alumni = null;
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->with('calon_siswa','pengguna')->first();
        return view('siswa.alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas','siswa'));
    }
    public function datatablesTracerAlumni(Request $request)
    {
    $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        if( $auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' ){
        $alumnis    = LibAlumni::getAlumnisSmpWithId( $siswa->id_c_siswa);
         }else{
        $alumnis    = LibAlumni::getAlumnisWithId($siswa->id_c_siswa);
    }

        return Datatables::of($alumnis)
            ->addColumn('status_verifikasi', function ($item) {
                if ($item->status_verifikasi == 0) {
                    $data['status'] = 'Belum Diverikasi';
                    $data['color'] = 'pink';
                } else {
                    $data['status'] = 'Sudah Diverikasi';
                    $data['color'] = 'teal';
                }
                return $data;
            })
            ->editColumn('status', function ($item) {
                return ucfirst($item->status);
            })
            ->addColumn('action', function ($item) {
                return ['id' => $item->id_alumni];
            })
            ->make(true);

        }
}
