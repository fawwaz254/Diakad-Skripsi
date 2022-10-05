<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Guru;
use App\Models\JadwalHari;
use App\Models\JadwalJam;
use App\Models\JadwalKelasMp;
use App\Models\KelasMp;
use App\Models\MataPelajaran;
use App\Models\Ruangan;
use Auth;
use DB;
use Session;
use Validator;

class SetJadwalKelasController extends Controller
{
    public function viewSetJadwalKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('akademik/aktivitas-semester/set-jadwal-kelas/view-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas'));
    }

    public function actionSetJadwalKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
            ];
        }
    }

    public function viewTambahJadwalKelas(Request $request, $id_kelas, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $jadwal_jam = JadwalJam::orderBy('jam_ke', 'asc')->orderBy('created_at', 'asc')->get();
        $jam = JadwalJam::select('jam_ke')->distinct()->orderBy('jam_ke', 'asc')->get();
        // dd($jadwal_jam);
        $jadwal_hari = JadwalHari::all();
        $kelas =  LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $jadwal_kelas_mp = JadwalKelasMp::with('kelas_mp')->whereHas('kelas_mp', function ($query) use ($id_kelas, $id_semester) {
            $query->where('id_kelas', '=', $id_kelas)->where('id_semester', '=', $id_semester);
        })->get();

        $kelas_mp   = KelasMp::select('mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', 'jadwal_jam.jam_mulai', 'jadwal_jam.menit_mulai', 'jadwal_jam.jam_selesai', 'jadwal_jam.menit_selesai', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.path_foto_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester')
            // ->join('kelas_mp','kelas_mp.id_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->join('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
            ->join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
            ->join('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->join('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
            ->where('kelas.id_kelas', '=', $id_kelas)
            ->where('semester.id_semester', '=', $id_semester)
            // ->where('kelas_mp.id_kelas_mp', '=', 'D4Ka21611379707600bb3fb69ca0')
            ->get();

        // dd($kelas_mp);


        $data_kelas_mp = [];
        $jadwal = $jadwal_kelas_mp->toArray();
        // dd($jadwal);
        foreach ($jadwal as $j) {
            foreach ($j as $a) {
                // if($j['id_jadwal_jam'] == $j['id_jadwal_jam_selesai'])
                // $data_kelas_mp[$j['id_jadwal_jam'] . $j['id_jadwal_hari']] =  $kelas_mp->firstWhere('id_kelas_mp',  $j['id_kelas_mp']);
                $data_kelas_mp[$j['id_jadwal_jam_selesai'] . $j['id_jadwal_hari']] =  $kelas_mp->firstWhere('id_kelas_mp',  $j['id_kelas_mp']);
                // else
                $mulai = $jadwal_jam->firstWhere('id_jadwal_jam',  $j['id_jadwal_jam']);
                $selesai =  $jadwal_jam->firstWhere('id_jadwal_jam',  $j['id_jadwal_jam_selesai']);

                $i = $mulai->jam_ke;
                // dd($mulai);
                $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                for ($i; $i <= $selesai->jam_ke; $i++) {
                    // $id_jam =  $jadwal_jam->firstWhere('jam_ke', $i);
                    //  dd($id_jam->id_jadwal_jam);
                    $data_kelas_mp[$i . $j['id_jadwal_hari']] =  $kelas_mp->firstWhere('id_kelas_mp',  $j['id_kelas_mp']);
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'color'] =  $rand;
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'primary'] = $i == $mulai->jam_ke ? '1' : '0';

                    // dd(  $data_kelas_mp[$id_jam->id_jadwal_jam . $j['id_jadwal_hari'].'color']);
                    // dd($data_kelas_mp[$id_jam->id_jadwal_jam . $j['id_jadwal_hari']]);

                }

                // $data_kelas_mp[$j['id_jadwal_jam_selesai'] . $j['id_jadwal_hari'].'size'] = $selesai->jam_ke -  $mulai->jam_ke + 1;



                // $data_kelas_mp[$j['id_jadwal_jam_selesai'] . $j['id_jadwal_hari']] =  $kelas_mp->firstWhere('id_kelas_mp',  $j['id_kelas_mp']);


            }
        }

        $list_guru       = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        $ruangan    = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_ruangan', 'asc')->get();

        $mapel      = MataPelajaran::all();

        return view('akademik/aktivitas-semester/set-jadwal-kelas/tambah-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas', 'jadwal_jam', 'jadwal_hari', 'kelas', 'data_kelas_mp', 'semester', 'list_guru', 'mapel','jam'));
    }
}
