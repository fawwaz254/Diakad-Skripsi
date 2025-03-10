<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use Auth;
use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\KelasMp;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\JadwalJam;
use App\Models\Kurikulum;
use App\Models\JadwalHari;
use App\Models\PengampuMp;
use App\Models\PresensiMp;
use Illuminate\Http\Request;
use App\Models\JadwalKelasMp;
use App\Models\MataPelajaran;
use App\Models\PengambilanMp;
use Yajra\Datatables\Datatables;
use App\Models\JenisMataPelajaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\WaliKelas;

class SetJadwalKelasController extends Controller
{
    public function viewSetJadwalKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('akademik/aktivitas-semester/set-jadwal-kelas/view-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas'));
    }

    public function actionSetJadwalKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required',
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Harap untuk memilih kelas terlebih dahulu'
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
        $auth_data = auth_data();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $jadwal_jam = JadwalJam::orderBy('jam_ke', 'asc')->orderBy('created_at', 'asc')->get();
        $jam = JadwalJam::select('jam_ke')->distinct()->orderBy('jam_ke', 'asc')->get();
        // dd($jadwal_jam);
        $jadwal_hari = JadwalHari::all();
        $kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $jadwal_kelas_mp = JadwalKelasMp::with('kelas_mp')->whereHas('kelas_mp', function ($query) use ($id_kelas, $id_semester) {
            $query->where('id_kelas', '=', $id_kelas)->where('id_semester', '=', $id_semester)
                ->where('id_jadwal_hari', '!=', '0')
                ->where('id_jadwal_jam', '!=', '0')
                ->where('id_jadwal_jam_selesai', '!=', '0');
        })->get();

        $kelas_mp = KelasMp::select('mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', 'jadwal_jam.jam_mulai', 'jadwal_jam.menit_mulai', 'jadwal_jam.jam_selesai', 'jadwal_jam.menit_selesai', 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.path_foto_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester', 'jadwal_kelas_mp.id_jadwal_kelas_mp', 'jadwal_kelas_mp.id_jadwal_hari', 'jadwal_kelas_mp.id_jadwal_jam', 'jadwal_kelas_mp.id_jadwal_jam_selesai', 'kelas_mp.id_mata_pelajaran', 'pengampu_mp.id_guru', 'pengampu_mp.id_pengampu_mp', 'presensi_mp.id_presensi_mp')
            // ->join('kelas_mp','kelas_mp.id_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->join('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
            ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
            ->leftJoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftJoin('presensi_mp', 'presensi_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
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
            ->where('jadwal_kelas_mp.id_jadwal_hari', '!=', '0')
            ->where('jadwal_kelas_mp.id_jadwal_jam', '!=', '0')
            ->where('jadwal_kelas_mp.id_jadwal_jam_selesai', '!=', '0')
            // ->where('kelas_mp.id_kelas_mp', '=', 'Fh2L4167264532763b28ad02fda7')
            ->get();

        $data_kelas_mp = [];
        $jadwal = $jadwal_kelas_mp->toArray();
        // dd($jadwal);
        foreach ($jadwal as $j) {
            foreach ($j as $a) {
                $data_kelas_mp[$j['id_jadwal_jam_selesai'] . $j['id_jadwal_hari']] = $kelas_mp->firstWhere('id_kelas_mp', $j['id_kelas_mp']);
                $mulai = $jadwal_jam->firstWhere('id_jadwal_jam', $j['id_jadwal_jam']);
                $selesai = $jadwal_jam->firstWhere('id_jadwal_jam', $j['id_jadwal_jam_selesai']);

                $i = $mulai->jam_ke;
                $rand = str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
                for ($i; $i <= $selesai->jam_ke; $i++) {
                    $data_kelas_mp[$i . $j['id_jadwal_hari']] = $kelas_mp->firstWhere('id_kelas_mp', $j['id_kelas_mp']);
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'color'] = $rand;
                    $data_kelas_mp[$i . $j['id_jadwal_hari'] . 'primary'] = $i == $mulai->jam_ke ? '1' : '0';
                }
            }
        }

        $list_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        $allruangan = Ruangan::orderBy('nm_ruangan', 'asc')->get();
        // $mapel       = MataPelajaran::all();

        //get kurikulum aktif di sekolah tersebut
        // $data_kurikulum = Kurikulum::with('jurusan', 'mapel', 'mapel.mata_pelajaran')
        //     ->where('is_aktif', '=', 1)
        //     ->whereHas('jurusan', function ($q) use ($auth_data) {
        //         $q->where('id_sekolah', $auth_data->pengguna->id_sekolah);
        //     })
        //     ->get();

        $id_jurusan = $kelas->id_jurusan;
        $data_jenis_mata_pelajaran = JenisMataPelajaran::with('mapel')->whereHas('mapel', function ($query) use ($id_jurusan) {
            $query->where('id_jurusan', '=', $id_jurusan);
        })->get();

        if (empty($data_jenis_mata_pelajaran)) {
            $data_jenis_mata_pelajaran = JenisMataPelajaran::with('mapel')->get();
        }


        $list_jurusan = Jurusan::all();
        $list_jenis_mata_pelajaran = JenisMataPelajaran::all();

        if (MataPelajaran::where('id_jurusan', $id_jurusan)->first()) {
        } else {
            $id_jurusan = null;
        }

        return view('akademik/aktivitas-semester/set-jadwal-kelas/tambah-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas', 'jadwal_jam', 'jadwal_hari', 'kelas', 'data_kelas_mp', 'semester', 'list_guru', 'jam', 'allruangan', 'data_jenis_mata_pelajaran', 'list_jenis_mata_pelajaran', 'list_jurusan', 'id_jurusan'));
    }

    public function viewCopyJadwalKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $now = (int) $semester->thn_akademik_semester + 1;

        $tahun_sebelum = (int) $semester->thn_akademik_semester - 2;

        $data_semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->whereBetween('thn_akademik_semester', [$tahun_sebelum, $now])
            ->orderBy('thn_akademik_semester', 'asc')
            ->orderBy('nm_semester', 'asc')
            ->get();
        $data_kelas = LibKelas::fetchDataKelas($auth_data);


        return view('akademik/aktivitas-semester/set-jadwal-kelas/copy-set-jadwal-kelas', compact('auth_data', 'data_semester', 'data_kelas'));
    }

    public function copyTambahJadwalKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validasi = KelasMp::where('id_semester', $input->id_semester_paste)->whereIn('id_kelas', $input->kelas)->whereHas('jadwal_kelas_mp')
            ->whereHas('pengampu_mp')->first();

        if ($validasi) {
            return [
                'status' => 300, // FAILED
                'message' => 'Harap hapus data terlebih dahulu agar data tidak tertumpuk'
            ];
        }

        $select_kelas_mp = KelasMp::where('id_semester', $input->id_semester_copy)->whereIn('id_kelas', $input->kelas)
            ->with('jadwal_kelas_mp', 'pengampu_mp')
            ->whereHas('jadwal_kelas_mp')
            ->whereHas('pengampu_mp')
            ->get();

        //insert kelas mp
        $batch_insert_kelas_mp = [];
        $batch_insert_jadwal_kelas_mp = [];
        $batch_insert_pengampu_mp = [];
        foreach ($select_kelas_mp as $kelas_mp) {
            $id_kelas_mp = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $batch_insert_kelas_mp[] = array(
                'id_kelas_mp' => $id_kelas_mp,
                'id_semester' => $input->id_semester_paste,
                'id_kelas' => $kelas_mp->id_kelas,
                'id_mata_pelajaran' => $kelas_mp->id_mata_pelajaran,
                'nm_kelas_mp' => $kelas_mp->nm_kelas_mp,
                'jml_pertemuan_kelas_mp' => $kelas_mp->jml_pertemuan_kelas_mp,
                'created_by' => auth_data()->pengguna->id_pengguna,
                'created_at' => $now,
                'updated_by' => auth_data()->pengguna->id_pengguna,
                'updated_at' => $now,
            );

            foreach ($kelas_mp->jadwal_kelas_mp as $jadwal_kelas) {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                $batch_insert_jadwal_kelas_mp[] = array(
                    'id_jadwal_kelas_mp' => $id,
                    'id_kelas_mp' => $id_kelas_mp,
                    'id_ruangan' => $jadwal_kelas->id_ruangan,
                    'id_jadwal_hari' => $jadwal_kelas->id_jadwal_hari,
                    'id_jadwal_jam' => $jadwal_kelas->id_jadwal_jam,
                    'id_jadwal_jam_selesai' => $jadwal_kelas->id_jadwal_jam_selesai,
                    'created_by' => auth_data()->pengguna->id_pengguna,
                    'created_at' => $now,
                    'updated_by' => auth_data()->pengguna->id_pengguna,
                    'updated_at' => $now,
                );
            }

            foreach ($kelas_mp->pengampu_mp as $pengampu_mp) {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                $batch_insert_pengampu_mp[] = array(
                    'id_pengampu_mp' => $id,
                    'id_kelas_mp' => $id_kelas_mp,
                    'id_guru' => $pengampu_mp->id_guru,
                    'pjmp_pengampu_mp' => $pengampu_mp->pjmp_pengampu_mp,
                    'pjmp_uts' => $pengampu_mp->pjmp_uts,
                    'pjmp_uas' => $pengampu_mp->pjmp_uas,
                    'nomor_sk_mengajar' => $pengampu_mp->nomor_sk_mengajar,
                    'tgl_sk_mengajar' => $pengampu_mp->tgl_sk_mengajar,
                    'created_by' => auth_data()->pengguna->id_pengguna,
                    'created_at' => $now,
                    'updated_by' => auth_data()->pengguna->id_pengguna,
                    'updated_at' => $now,
                );
            }
        }


        \App\Jobs\CopySetJadwalKelas::dispatch($batch_insert_kelas_mp, $batch_insert_jadwal_kelas_mp, $batch_insert_pengampu_mp);
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'message' => 'Copy Set Jadwal Kelas Successfully',
            'path' => 'aktivitas-semester/copy-jadwal-kelas/view-copy-jadwal-kelas'
        ];
    }

    public function actionTambahJadwalKelas(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        //validasi waktu
        if ($mode != 'delete') {
            $validator = Validator::make($request->all(), [
                'jamMasuk' => 'required',
                'jamSelesai' => 'required',
                'ruangan' => 'required',
                'guru' => 'required',
                'id_hari' => 'required',
                'id_semester' => 'required',
                'id_kelas' => 'required'
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

        // if ($mode == 'add') {
        //     if ($cek_jadwal['guru'] == 0) {
        //         return [
        //             'status_code' => 300, // FAILED
        //             'message' => 'Guru yang bersangkutan sudah mengambil waktu ini di kelas ' . $cek_jadwal['alasan']
        //         ];
        //     } elseif ($cek_jadwal['ruangan'] == 0) {
        //         return [
        //             'status_code' => 300, // FAILED
        //             'message' => 'Sudah Ada Jadwal yang Sama di Waktu dan Tempat yang sama'
        //         ];
        //     }
        // }

        // mengambil waktu sekarang
        $now = Carbon::now();


        if ($mode == 'add') {


            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

            $mapel = MataPelajaran::find($input->mapel);

            if (empty($mapel)) {
                return [
                    'status_code' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Mapel tidak ditemukan, Coba Refresh'
                ];
            }
            $kelas = Kelas::find($input->id_kelas);

            $kelas_mp = new KelasMp;
            $kelas_mp->id_kelas_mp = $id;
            $kelas_mp->id_semester = $input->id_semester;
            $kelas_mp->id_kelas = $input->id_kelas;
            $kelas_mp->id_mata_pelajaran = $input->mapel;
            $kelas_mp->nm_kelas_mp = $mapel->nm_mata_pelajaran . '-' . $kelas->nm_kelas;
            $kelas_mp->jml_pertemuan_kelas_mp = '0';
            $kelas_mp->created_by = auth_data()->pengguna->id_pengguna;
            $kelas_mp->created_at = $now;
            $kelas_mp->save();


            // $ruang = Ruangan::where('id_kelas', $input->id_kelas)->first();

            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp = new JadwalKelasMp;
            $jadwal_kelas_mp->id_jadwal_kelas_mp = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp->id_kelas_mp = $kelas_mp->id_kelas_mp;
            $jadwal_kelas_mp->id_jadwal_hari = $input->id_hari;
            $jadwal_kelas_mp->id_jadwal_jam = $input->jamMasuk;
            $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jamSelesai;
            $jadwal_kelas_mp->id_ruangan = $input->ruangan;
            $jadwal_kelas_mp->created_at = $now;
            $jadwal_kelas_mp->created_by = auth_data()->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp = new PengampuMp();
            $pengampu_mp->id_pengampu_mp = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp->id_kelas_mp = $kelas_mp->id_kelas_mp;
            $pengampu_mp->id_guru = $input->guru;
            $pengampu_mp->pjmp_pengampu_mp = 1;
            // $pengampu_mp->pjmp_uts          = 1;
            // $pengampu_mp->pjmp_uas          = 1;
            $pengampu_mp->created_at = $now;
            $pengampu_mp->created_by = auth_data()->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status_code' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        } elseif ($mode == 'delete') {
            try {
                if (PresensiMp::where('id_kelas_mp', $id)->first()) { // jika jadwal sudah diisi presensi, maka tidak boleh dihapus
                    return [
                        'status_code' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Tidak boleh dihapus karena Sudah dilakukan penilaian'
                    ];
                }
                DB::beginTransaction();

                JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_at' => $now, 'deleted_by' => auth_data()->pengguna->id_pengguna]);
                JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                PengampuMp::where('id_kelas_mp', $id)->update(['deleted_at' => $now, 'deleted_by' => auth_data()->pengguna->id_pengguna]);
                PengampuMp::where('id_kelas_mp', $id)->delete();

                KelasMp::where('id_kelas_mp', $id)->update(['deleted_at' => $now, 'deleted_by' => auth_data()->pengguna->id_pengguna]);
                KelasMp::where('id_kelas_mp', $id)->delete();

                DB::commit();

                return [
                    'status_code' => 202, // SUCCESS AND LOAD TABLE
                    'path' => 'aktivitas-semester/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                    'message' => 'Delete Jadwal Mata Ajar Successfully'
                ];
            } catch (\Exception $e) {
                DB::rollback();

                Log::error('Error Delete Jadwal Mata Ajar: ' . $e->getMessage());

                return [
                    'status_code' => 202, // SUCCESS AND LOAD TABLE
                    'path' => 'aktivitas-semester/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                    'message' => 'Delete Jadwal Mata Ajar Gagal'
                ];
            }
        } elseif ($mode = 'edit') {
            $jadwal_kelas_mp = JadwalKelasMp::find($id);
            // $jadwal_kelas_mp->id_jadwal_kelas_mp    = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();
            // $jadwal_kelas_mp->id_kelas_mp           = $kelas_mp->id_kelas_mp;
            $jadwal_kelas_mp->id_jadwal_hari = $input->id_hari;
            $jadwal_kelas_mp->id_jadwal_jam = $input->jamMasuk;
            $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jamSelesai;
            $jadwal_kelas_mp->id_ruangan = $input->ruangan;
            $jadwal_kelas_mp->updated_at = $now;
            $jadwal_kelas_mp->updated_by = auth_data()->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            // $id = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();
            $pengampu_mp = PengampuMp::find($input->id_pengampu_mp);
            // $pengampu_mp->id_pengampu_mp    = auth_data()->sekolah_data->prefix.strtotime($now).uniqid();
            // $pengampu_mp->id_kelas_mp       = $kelas_mp->id_kelas_mp;
            $pengampu_mp->id_guru = $input->guru;
            // $pengampu_mp->pjmp_pengampu_mp  = 1;
            // $pengampu_mp->pjmp_uts          = 1;
            // $pengampu_mp->pjmp_uas          = 1;
            $jadwal_kelas_mp->updated_at = $now;
            $jadwal_kelas_mp->updated_by = auth_data()->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status_code' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/set-jadwal-kelas/view-tambah-jadwal-kelas/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        }
    }

    public function getMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        if (!empty($input->jurusan) && !empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::isAktif()->where('id_jurusan', $input->jurusan)->where('id_jenis_mata_pelajaran', $input->jenismapel)->get()->sortBy('kd_mata_pelajaran');
        } elseif (!empty($input->jurusan) && empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::isAktif()->where('id_jurusan', $input->jurusan)->get()->sortBy('kd_mata_pelajaran');
        } elseif (empty($input->jurusan) && !empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::isAktif()->where('id_jenis_mata_pelajaran', $input->jenismapel)->get()->sortBy('kd_mata_pelajaran');
        } else {
            $data['mapel'] = null;
        }
        return $data;
    }
    public function removeKelasKosong(Request $request)
    {
        $kelas_mp = KelasMp::whereDoesntHave('kelas')->get();
        foreach ($kelas_mp as $k) {
            $k->deleted_by = 'remove kelas kosong';
            $k->save();
            $k->delete();
        }
    }


    //////////////////////////////////// KBM TANPA JADWAL ///////////////////////////////////////////

    public function viewSetKBMTanpaJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('akademik/aktivitas-semester/set-jadwal-kelas/kbm-tanpa-jadwal/view-kbm-tanpa-jadwal', compact('auth_data', 'data_kelas', 'data_semester'));
    }

    public function actionSetKBMTanpaJadwal(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
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
                'path' => 'aktivitas-semester/set-kbm-tanpa-jadwal/view-detail/' . $input->id_kelas . '/' . $input->id_semester,
            ];
        }
    }

    public function viewKBMTanpaJadwal(Request $request, $id_kelas, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        $kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        // $kelas_mp = KelasMp::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->get();
        return view('akademik/aktivitas-semester/set-jadwal-kelas/kbm-tanpa-jadwal/detail-kbm-tanpa-jadwal', compact('auth_data', 'data_semester', 'data_kelas', 'kelas', 'semester', 'id_kelas', 'id_semester'));
    }

    public function datatablesKBMTanpaJadwal(Request $request, $id_kelas, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = KelasMp::select('kelas_mp.id_kelas_mp', 'kelas_mp.nm_kelas_mp', 'kelas.id_kelas', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jurusan.nm_jurusan', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'semester.nm_semester', 'semester.tahun_ajaran', 'pengguna.nm_pengguna', 'pengguna.path_foto_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'jadwal_kelas_mp.id_jadwal_kelas_mp', 'jadwal_kelas_mp.id_jadwal_jam', 'jadwal_kelas_mp.id_jadwal_jam_selesai', 'pengampu_mp.id_guru', 'pengampu_mp.id_pengampu_mp')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->join('jurusan', 'jurusan.id_jurusan', '=', 'mata_pelajaran.id_jurusan')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
            ->join('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftJoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->join('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->join('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
            ->where('kelas_mp.id_kelas', $id_kelas)
            ->where('kelas_mp.id_semester', $id_semester)
            ->where('jadwal_kelas_mp.id_jadwal_hari', '0')
            ->where('jadwal_kelas_mp.id_jadwal_jam', '0')
            ->where('id_jadwal_jam_selesai', '0')
            ->get();

        return Datatables::of($list_data)
            ->addColumn('nm_pengguna', function ($item) {
                if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna . ", " . $item->gelar_belakang;
                } elseif (!empty($item->gelar_depan)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna;
                } elseif (!empty($item->gelar_belakang)) {
                    return $item->nm_pengguna . ", " . $item->gelar_belakang;
                } else {
                    return $item->nm_pengguna;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kelas_mp,
                    'id_kelas' => $item->id_kelas,
                );
                return $data;
            })
            ->make(true);
    }

    public function addKBMTanpaJadwal(Request $request, $id_kelas, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $semester = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $id_semester = $semester->id_semester;
        $list_guru = Guru::with('pengguna')->join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        $id_guru = $list_guru->where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();

        $guru = $list_guru->where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $id_guru =  $guru->id_guru ?? null;
        if (!$id_guru) {
            $id_guru = WaliKelas::select('id_guru')->where('id_kelas', $id_kelas)->where('is_aktif', 1)->first()->id_guru;
        }

        $id_jurusan = $kelas->id_jurusan;
        $data_jenis_mata_pelajaran = JenisMataPelajaran::with([
            'mapel' => function ($query) {
                $query->orderBy('kd_mata_pelajaran');
            }
        ])->whereHas('mapel', function ($query) use ($id_jurusan) {
            $query->where('id_jurusan', '=', $id_jurusan);
        })->get();

        if (empty($data_jenis_mata_pelajaran)) {
            $data_jenis_mata_pelajaran = JenisMataPelajaran::with([
                'mapel' => function ($query) {
                    $query->orderBy('kd_mata_pelajaran');
                }
            ])->get();
        }
        $list_jurusan = Jurusan::all();
        $list_jenis_mata_pelajaran = JenisMataPelajaran::with([
            'mapel' => function ($query) {
                $query->orderBy('kd_mata_pelajaran');
            }
        ])->get();

        if (MataPelajaran::where('id_jurusan', $id_jurusan)->first()) {
        } else {
            $id_jurusan = null;
        }

        return view('akademik/aktivitas-semester/set-jadwal-kelas/kbm-tanpa-jadwal/input-kbm-tanpa-jadwal', compact('auth_data', 'id_kelas', 'kelas', 'list_guru', 'id_guru', 'semester', 'id_jurusan', 'data_jenis_mata_pelajaran', 'list_jurusan', 'list_jenis_mata_pelajaran', 'id_semester'));
    }

    public function editKBMTanpaJadwal(Request $request, $id_kelas, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        $semester = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $id_semester = $semester->id_semester;
        $list_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();

        $id_guru = PengampuMp::where('id_kelas_mp', $id)->first()->id_guru;
        $kelas_mp = KelasMp::find($id);

        $jadwal_kelas_mp = JadwalKelasMp::where('id_kelas_mp', $id)->first();
        $pengampu_mp = PengampuMp::where('id_kelas_mp', $id)->first();

        $id_jurusan = $kelas->id_jurusan;
        $data_jenis_mata_pelajaran = JenisMataPelajaran::with([
            'mapel' => function ($query) {
                $query->orderBy('kd_mata_pelajaran');
            }
        ])->whereHas('mapel', function ($query) use ($id_jurusan) {
            $query->where('id_jurusan', '=', $id_jurusan);
        })->get();

        if (empty($data_jenis_mata_pelajaran)) {
            $data_jenis_mata_pelajaran = JenisMataPelajaran::with([
                'mapel' => function ($query) {
                    $query->orderBy('kd_mata_pelajaran');
                }
            ])->get();
        }

        $list_jurusan = Jurusan::all();
        $list_jenis_mata_pelajaran = JenisMataPelajaran::with([
            'mapel' => function ($query) {
                $query->orderBy('kd_mata_pelajaran');
            }
        ])->get();

        if (MataPelajaran::where('id_jurusan', $id_jurusan)->first()) {
        }
        return view('akademik/aktivitas-semester/set-jadwal-kelas/kbm-tanpa-jadwal/edit-kbm-tanpa-jadwal', compact('auth_data', 'id_kelas', 'kelas', 'list_guru', 'id_guru', 'semester', 'id_jurusan', 'data_jenis_mata_pelajaran', 'list_jurusan', 'list_jenis_mata_pelajaran', 'kelas_mp', 'jadwal_kelas_mp', 'pengampu_mp', 'id_semester'));
    }

    public function actionInputKBMTanpaJadwal(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        if ($mode == 'add') {
            $validator = Validator::make($request->all(), [
                // 'jamMasuk' => 'required',
                // 'jamSelesai' => 'required',
                // 'ruangan' => 'required',
                // 'id_hari' => 'required',
                'id_guru' => 'required',
                'id_semester' => 'required',
                'id_kelas' => 'required',
                'id_mata_pelajaran' => 'required',
            ]);
        } elseif ($mode == 'edit') {
            $validator = Validator::make($request->all(), [
                // 'jamMasuk' => 'required',
                // 'jamSelesai' => 'required',
                // 'ruangan' => 'required',
                'id_guru' => 'required',
                'id_semester' => 'required',
                'id_kelas' => 'required',
                'id_mata_pelajaran' => 'required',

            ]);
        }

        if ($mode != 'delete' && $validator->fails()) {
            return [
                'status_code' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if ($mode == 'add') {
            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $mapel = MataPelajaran::find($input->id_mata_pelajaran);
            $kelas = Kelas::find($input->id_kelas);

            $kelas_mp = new KelasMp;
            $kelas_mp->id_kelas_mp = $id;
            $kelas_mp->id_semester = $input->id_semester;
            $kelas_mp->id_kelas = $input->id_kelas;
            $kelas_mp->id_mata_pelajaran = $input->id_mata_pelajaran;
            $kelas_mp->nm_kelas_mp = $mapel->nm_mata_pelajaran . '-' . $kelas->nm_kelas;
            $kelas_mp->jml_pertemuan_kelas_mp = '0';
            $kelas_mp->created_by = auth_data()->pengguna->id_pengguna;
            $kelas_mp->created_at = $now;
            $kelas_mp->save();

            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp = new JadwalKelasMp;
            $jadwal_kelas_mp->id_jadwal_kelas_mp = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $jadwal_kelas_mp->id_kelas_mp = $kelas_mp->id_kelas_mp;
            $jadwal_kelas_mp->id_jadwal_hari = '0';
            $jadwal_kelas_mp->id_jadwal_jam = '0';
            $jadwal_kelas_mp->id_jadwal_jam_selesai = '0';
            $jadwal_kelas_mp->id_ruangan = '0';
            $jadwal_kelas_mp->created_at = $now;
            $jadwal_kelas_mp->created_by = auth_data()->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp = new PengampuMp();
            $pengampu_mp->id_pengampu_mp = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengampu_mp->id_kelas_mp = $kelas_mp->id_kelas_mp;
            $pengampu_mp->id_guru = $input->id_guru;
            $pengampu_mp->pjmp_pengampu_mp = 1;
            $pengampu_mp->created_at = $now;
            $pengampu_mp->created_by = auth_data()->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/set-kbm-tanpa-jadwal/view-detail/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        } elseif ($mode == 'delete') {
            // dd("delete");
            if ($kelas_mp = PengambilanMp::where('id_kelas_mp', $id)->first()) {
                return [
                    'status_code' => 300, // SUCCESS AND LOAD TABLE
                    'message' => 'Terdapat siswa yang telah mengambil kelas ini, hapus ploting mapel siswa terlebih dahulu'
                ];
            } else {
                DB::beginTransaction();
                try {
                    JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => auth_data()->pengguna->id_pengguna]);
                    JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                    PengampuMp::where('id_kelas_mp', $id)->update(['deleted_by' => auth_data()->pengguna->id_pengguna]);
                    PengampuMp::where('id_kelas_mp', $id)->delete();

                    KelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => auth_data()->pengguna->id_pengguna]);
                    KelasMp::where('id_kelas_mp', $id)->delete();
                    DB::commit();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jadwal Mata Ajar Successfully'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    return [
                        'status_code' => 300,
                        'message' => 'Delete Jadwal Mata Ajar Gagal'
                    ];
                }
            }
        } elseif ($mode = 'edit') {
            $mapel = MataPelajaran::find($input->id_mata_pelajaran);
            $kelas = Kelas::find($input->id_kelas);

            $kelas_mp = KelasMp::find($id);
            $kelas_mp->id_mata_pelajaran = $input->id_mata_pelajaran;
            $kelas_mp->nm_kelas_mp = $mapel->nm_mata_pelajaran . '-' . $kelas->nm_kelas;
            $kelas_mp->updated_by = auth_data()->pengguna->id_pengguna;
            $kelas_mp->updated_at = $now;
            $kelas_mp->save();

            $jadwal_kelas_mp = JadwalKelasMp::where('id_kelas_mp', $id)->first();
            $jadwal_kelas_mp->updated_at = $now;
            $jadwal_kelas_mp->updated_by = auth_data()->pengguna->id_pengguna;
            $jadwal_kelas_mp->save();

            $pengampu_mp = PengampuMp::where('id_kelas_mp', $id)->first();
            $pengampu_mp->id_guru = $input->id_guru;
            $jadwal_kelas_mp->updated_at = $now;
            $jadwal_kelas_mp->updated_by = auth_data()->pengguna->id_pengguna;
            $pengampu_mp->save();
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'aktivitas-semester/set-kbm-tanpa-jadwal/view-detail/' . $input->id_kelas . '/' . $input->id_semester,
                'message' => 'Save Successfully'
            ];
        }
    }

    public function getMapelKbmTanpaJadwal(Request $request)
    {
        $input = (object) $request->input();
        if (!empty($input->jurusan) && !empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->where('id_jenis_mata_pelajaran', $input->jenismapel)->isAktif()->get()->sortBy('kd_mata_pelajaran');
        } elseif (!empty($input->jurusan) && empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->isAktif()->get()->sortBy('kd_mata_pelajaran');
        } elseif (empty($input->jurusan) && !empty($input->jenismapel)) {
            $data['mapel'] = MataPelajaran::where('id_jenis_mata_pelajaran', $input->jenismapel)->isAktif()->get()->sortBy('kd_mata_pelajaran');
        } else {
            $data['mapel'] = null;
        }
        return $data;
    }
}
