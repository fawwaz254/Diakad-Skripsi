<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\PengambilanMp;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;

class LihatNilaiWaliController extends BaseController
{
    public function index(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $walimurid= WaliMurid::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $siswa = Siswa::where('id_wali_murid',$walimurid->id_wali_murid)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);


        $nilaiKBM = Siswa::select('guru.id_guru', 'guru.id_pengguna', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.id_kelas_mp', 'semester.tahun_ajaran', 'semester.nm_semester', 'mata_pelajaran.kd_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'kelas.nm_kelas', 'pengampu_mp.pjmp_pengampu_mp','pengambilan_mp.nilai_angka','pengambilan_mp.nilai_huruf')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            
            ->join('kelas_mp', 'kelas_mp.id_kelas', '=', 'kelas.id_kelas')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('pengambilan_mp', 'pengambilan_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->where('siswa.id_siswa', '=', $siswa->id_siswa)
            ->where('pengambilan_mp.id_siswa', '=',$siswa->id_siswa)
            ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
            ->where('semester.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('semester.id_semester', '=', $semester_aktif->id_semester)->get();
        // dd($nilaiKBM);
        


        
    	return view('siswa/akademik/lihat-nilai/view-lihat-nilai',compact('auth_data','siswa','nilaiKBM'));

    }
}
