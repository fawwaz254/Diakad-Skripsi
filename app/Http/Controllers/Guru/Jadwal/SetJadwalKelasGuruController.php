<?php

namespace App\Http\Controllers\Guru\Jadwal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Guru;
use App\Models\JadwalHari;
use App\Models\JadwalJam;
use App\Models\JadwalKelasMp;
use App\Models\Kelas;
use App\Models\KelasMp;
use App\Models\MataPelajaran;
use App\Models\PengambilanMp;
use App\Models\PengampuMp;
use App\Models\Ruangan;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;
use App\Libraries\Akademik\LibAkademik;

class SetJadwalKelasGuruController extends Controller
{
    public function viewSetJadwalKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('guru/jadwal/set-jadwal-kelas/view-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas'));
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
                'path' => 'jadwal/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
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

        $kelas_mp   = KelasMp::select('mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', 'jadwal_jam.jam_mulai', 'jadwal_jam.menit_mulai', 'jadwal_jam.jam_selesai', 'jadwal_jam.menit_selesai', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.path_foto_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester', 'jadwal_kelas_mp.id_jadwal_kelas_mp', 'jadwal_kelas_mp.id_jadwal_hari', 'jadwal_kelas_mp.id_jadwal_jam', 'jadwal_kelas_mp.id_jadwal_jam_selesai', 'kelas_mp.id_mata_pelajaran', 'pengampu_mp.id_guru', 'pengampu_mp.id_pengampu_mp')
            // ->join('kelas_mp','kelas_mp.id_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->join('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
            ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
            ->leftJoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->join('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
            ->where('kelas.id_kelas', '=', $id_kelas)
            ->where('semester.id_semester', '=', $id_semester)
            ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
            ->whereNull('pengampu_mp.pjmp_uts')
            ->whereNull('pengampu_mp.pjmp_uas')
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
                    $data_kelas_mp[$i . $j['id_jadwal_hari']] =  $kelas_mp->firstWhere('id_kelas_mp',  $j['id_kelas_mp']);
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'color'] =  $rand;
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'primary'] = $i == $mulai->jam_ke ? '1' : '0';
                }
            }
        }

        $list_guru       = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        // $ruangan    = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_ruangan', 'asc')->get();
        $allruangan    = Ruangan::orderBy('nm_ruangan', 'asc')->get();
        $mapel      = MataPelajaran::all();
        // $ruangan    = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_ruangan', 'asc')->get();
        // $ruangan    = Ruangan::where('id_kelas', $id_kelas)->first();
        return view('guru/jadwal/set-jadwal-kelas/tambah-set-jadwal-kelas', compact('auth_data', 'allruangan', 'data_semester', 'data_kelas', 'jadwal_jam', 'jadwal_hari', 'kelas', 'data_kelas_mp', 'semester', 'list_guru', 'mapel', 'jam'));
    }

    public function actionTambahJadwalKelas(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        //validasi waktu
        if ($mode != 'delete') {
            $validator = Validator::make($request->all(), [
                'jamMasuk'          => 'required',
                'jamSelesai'        => 'required',
                'ruangan'           => 'required',
                'guru'               => 'required',
                'id_hari'           => 'required',
                'id_semester'       => 'required',
                'id_kelas'          => 'required'
            ]);
        }

        if ($mode != 'delete' && $validator->fails()) {
            return [
                'status_code' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if ($mode != 'delete') {
            $jam_masuk = JadwalJam::find($input->jamMasuk);
            $jam_keluar = JadwalJam::find($input->jamSelesai);
            if ($jam_keluar->jam_ke < $jam_masuk->jam_ke) {
                return [
                    'status_code' => 300, // FAILED
                    'message' => 'Jam Keluar tidak boleh lebih kecil dari Jam Masuk'
                ];
            }
            $cek_jadwal = LibAkademik::cekJadwalKelas($auth_data, $input->guru, '-', $input->id_hari, $input->jamMasuk, $input->jamSelesai);
        }

        if ($mode == 'add') {
            if ($cek_jadwal['guru'] == 0) {
                return [
                    'status_code' => 300, // FAILED
                    'message' => 'Guru yang bersangkutan sudah mengambil waktu ini di kelas lain '
                ];
            } elseif ($cek_jadwal['ruangan'] == 0) {
                return [
                    'status_code' => 300, // FAILED
                    'message' => 'Sudah Ada Jadwal yang Sama di Waktu dan Tempat yang sama'
                ];
            }
        }

        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if ($mode == 'add') {
            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $mapel = MataPelajaran::find($input->mapel);
            $kelas = Kelas::find($input->id_kelas);

            $kelas_mp                           = new KelasMp;
            $kelas_mp->id_kelas_mp              = $id;
            $kelas_mp->id_semester              = $input->id_semester;
            $kelas_mp->id_kelas                 = $input->id_kelas;
            $kelas_mp->id_mata_pelajaran        = $input->mapel;
            $kelas_mp->nm_kelas_mp              = $mapel->nm_mata_pelajaran . '-' . $kelas->nm_kelas;
            $kelas_mp->jml_pertemuan_kelas_mp   = '0';
            $kelas_mp->created_by               = $input->auth_data->pengguna->id_pengguna;
            $kelas_mp->created_at               = $now;
            $kelas_mp->save();


            // $ruang = Ruangan::where('id_kelas', $input->id_kelas)->first();

            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp                        = new JadwalKelasMp;
            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp->id_kelas_mp           = $kelas_mp->id_kelas_mp;
            $jadwal_kelas_mp->id_jadwal_hari        = $input->id_hari;
            $jadwal_kelas_mp->id_jadwal_jam         = $input->jamMasuk;
            $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jamSelesai;
            $jadwal_kelas_mp->id_ruangan            = $input->ruangan;
            $jadwal_kelas_mp->created_at            = $now;
            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp                    = new PengampuMp();
            $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp->id_kelas_mp       = $kelas_mp->id_kelas_mp;
            $pengampu_mp->id_guru           = $input->guru;
            $pengampu_mp->pjmp_pengampu_mp  = 1;
            // $pengampu_mp->pjmp_uts          = 1;
            // $pengampu_mp->pjmp_uas          = 1;
            $pengampu_mp->created_at        = $now;
            $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status_code' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'jadwal/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        } elseif ($mode == 'delete') {
            if ($kelas_mp = PengambilanMp::where('id_kelas_mp', $id)->first()) {
                return [
                    'status_code' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Terdapat siswa yang telah mengambil kelas ini, hapus ploting mapel siswa terlebih dahulu'
                ];
            } else {
                DB::beginTransaction();
                try {
                    JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                    PengampuMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    PengampuMp::where('id_kelas_mp', $id)->delete();

                    KelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    KelasMp::where('id_kelas_mp', $id)->delete();

                    return [
                        'status_code' => 202, // SUCCESS AND LOAD TABLE
                        'path' => 'jadwal/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                        'message' => 'Delete Jadwal Mata Ajar Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    return [
                        'status_code' => 202, // SUCCESS AND LOAD TABLE
                        'path' => 'jadwal/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                        'message' => 'Delete Jadwal Mata Ajar Gagal'
                    ];
                }
            }
        } elseif ($mode = 'edit') {
            $jadwal_kelas_mp                        = JadwalKelasMp::find($id);
            // $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            // $jadwal_kelas_mp->id_kelas_mp           = $kelas_mp->id_kelas_mp;
            $jadwal_kelas_mp->id_jadwal_hari        = $input->id_hari;
            $jadwal_kelas_mp->id_jadwal_jam         = $input->jamMasuk;
            $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jamSelesai;
            $jadwal_kelas_mp->id_ruangan            = $input->ruangan;
            $jadwal_kelas_mp->updated_at            = $now;
            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            // $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $pengampu_mp                    = PengampuMp::find($input->id_pengampu_mp);
            // $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            // $pengampu_mp->id_kelas_mp       = $kelas_mp->id_kelas_mp;
            $pengampu_mp->id_guru           = $input->guru;
            // $pengampu_mp->pjmp_pengampu_mp  = 1;
            // $pengampu_mp->pjmp_uts          = 1;
            // $pengampu_mp->pjmp_uas          = 1;
            $jadwal_kelas_mp->updated_at            = $now;
            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status_code' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'jadwal/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        }
    }
}
