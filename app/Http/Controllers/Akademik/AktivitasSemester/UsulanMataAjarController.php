<?php

namespace App\Http\Controllers\Akademik\AktivitasSemester;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum as Kurikulum;
use App\Models\MataPelajaran as MataPelajaran;
use App\Models\Semester as Semester;
use App\Models\KelasMp as KelasMp;
use App\Models\Guru as Guru;
use App\Models\Kelas as Kelas;
use App\Models\PengampuMp as PengampuMp;
use App\Models\JadwalHari as JadwalHari;
use App\Models\Ruangan as Ruangan;
use App\Models\JadwalJam as JadwalJam;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\PengambilanMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class UsulanMataAjarController extends BaseController
{
    //
    public function viewUsulanMataAjar(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/aktivitas-semester/usulan-mata-ajar/view-usulan-mata-ajar', compact('auth_data', 'data_semester'));
    }

    public function actionViewUsulanMataAjar(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester' =>'required'
        ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                        'status' => 204, // SUCCESS AND LOAD CONTENT
                        'path' => 'aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$input->id_semester
                ];
        }
    }

    public function viewSemesterUsulanMataAjar(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = Semester::where('id_semester', '=', $id)->first();

        $kelas_mp = KelasMp::where('kelas_mp.id_semester', '=', $semester->id_semester)->first();
       
        return view('akademik/aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar', compact('auth_data', 'semester', 'id', 'kelas_mp'));
    }

    public function viewTambahMataAjar(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester   = Semester::where('id_semester', '=', $id)->first();
        return view('akademik/aktivitas-semester/usulan-mata-ajar/view-kelas-tambah-usulan-mata-ajar', compact('auth_data', 'id', 'semester'));
    }

    public function addUsulanMataAjar(Request $request, $id_semester, $id_mata_pelajaran)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $mapel      = MataPelajaran::leftjoin('jurusan', 'jurusan.id_jurusan', '=', 'mata_pelajaran.id_jurusan')
                            ->join('jenis_mata_pelajaran', function ($join) {
                                $join->on('jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
                                     ->whereNull('jenis_mata_pelajaran.deleted_at');
                            })
                            ->where('mata_pelajaran.id_mata_pelajaran', '=', $id_mata_pelajaran)
                            ->first();
        $kelas      = Kelas::join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        $semester   = Semester::where('id_semester', '=', $id_semester)->first();

        return view('akademik/aktivitas-semester/usulan-mata-ajar/add-usulan-mata-ajar', compact('auth_data', 'kelas', 'id_semester', 'id_mata_pelajaran', 'mapel', 'semester'));
    }

    public function copyUsulanMataAjar(Request $request, $id_kelas_mp)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $kelas_mp   = KelasMp::with('kelas')->where('id_kelas_mp', '=', $id_kelas_mp)->first();

        $mapel      = MataPelajaran::leftjoin('jurusan', 'jurusan.id_jurusan', '=', 'mata_pelajaran.id_jurusan')
                            ->join('jenis_mata_pelajaran', function ($join) {
                                $join->on('jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
                                    ->whereNull('jenis_mata_pelajaran.deleted_at');
                            })
                            ->where('mata_pelajaran.id_mata_pelajaran', '=', $kelas_mp->id_mata_pelajaran)
                            ->first();

        $id_semester = $kelas_mp->id_semester;
        $id_mata_pelajaran = $kelas_mp->id_mata_pelajaran;

        $kelas      = Kelas::join('jurusan', function ($q) {
            $q->on('jurusan.id_jurusan', '=', 'kelas.id_jurusan')
                ->whereNull('jurusan.deleted_at');
        })
                            ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                            ->whereNotExists(function ($query) use ($id_semester, $id_mata_pelajaran) {
                                $query->select(DB::raw(1))
                                      ->from('kelas_mp')
                                      ->whereNull('kelas_mp.deleted_at')
                                      ->whereRaw('kelas_mp.id_kelas = kelas.id_kelas')
                                      ->whereRaw('kelas_mp.id_semester = "'.$id_semester.'"')
                                      ->whereRaw('kelas_mp.id_mata_pelajaran = "'.$id_mata_pelajaran.'"');
                            })
                            ->orderBy('kelas.tingkat')
                            ->orderBy('kelas.nm_kelas')
                            ->get();

        $semester   = Semester::where('id_semester', '=', $kelas_mp->id_semester)->first();

        return view('akademik/aktivitas-semester/usulan-mata-ajar/copy-kelas-usulan-mata-ajar', compact('auth_data', 'kelas', 'id_semester', 'id_mata_pelajaran', 'mapel', 'kelas_mp', 'semester'));
    }

    public function editUsulanMataAjar(Request $request, $id)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $kelas_mp   = KelasMp::select('mata_pelajaran.id_mata_pelajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"), 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester')
            ->leftJoin('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftJoin('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->leftJoin('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->leftJoin('ruangan', 'ruangan.id_ruangan', '=', 'jadwal_kelas_mp.id_ruangan')
            ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
            ->leftJoin('pengampu_mp', 'pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftJoin('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->leftJoin('pengguna', 'guru.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
            ->where('kelas_mp.id_kelas_mp', '=', $id)
            ->first();

        $jadwal     = JadwalKelasMp::where('id_kelas_mp', '=', $id)
                        ->orderBy('id_jadwal_hari', 'asc')
                        ->get();
        $jml_jadwal = count($jadwal);

        $pengampu_mp_pj   = PengampuMp::where('id_kelas_mp', '=', $id)->where('pjmp_pengampu_mp', '=', 1)->first();
        $anggota          = PengampuMp::where('id_kelas_mp', '=', $id)->where('pjmp_pengampu_mp', '=', 2)
                                ->orderBy('id_guru', 'asc')
                                ->get();
        $jml_anggota        = count($anggota);

        $hari       = JadwalHari::get();
        $jam        = JadwalJam::orderBy('jam_ke', 'asc')->get();
        $pjma       = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_pengguna', 'asc')->get();
        $ruangan    = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('nm_ruangan', 'asc')->get();

        return view('akademik/aktivitas-semester/usulan-mata-ajar/edit-usulan-mata-ajar', compact('auth_data', 'id', 'pjma', 'hari', 'ruangan', 'jam', 'kelas_mp', 'jadwal', 'jml_jadwal', 'pengampu_mp_pj', 'anggota', 'jml_anggota'));
    }

    public function datatablesMataPelajaran(Request $request, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = MataPelajaran::join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->leftjoin('jurusan', 'mata_pelajaran.id_jurusan', '=', 'jurusan.id_jurusan')
            ->whereNotExists(function ($query) use ($id_semester) {
                $query->select(DB::raw(1))
                      ->from('kelas_mp')
                      ->whereRaw('kelas_mp.id_mata_pelajaran = mata_pelajaran.id_mata_pelajaran')
                      ->whereRaw('kelas_mp.id_semester = "'.$id_semester.'"')
                      ->whereNull('kelas_mp.deleted_at');
            })
            ->where('jenis_mata_pelajaran.id_sekolah', '=', $auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_mata_pelajaran
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesUsulanMataAjar(Request $request, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = KelasMp::with(
            'mata_pelajaran',
            'mata_pelajaran.jenis_mata_pelajaran',
            'kelas',
            'jadwal_kelas_mp',
            'jadwal_kelas_mp.jadwal_jam_mulai',
            'jadwal_kelas_mp.jadwal_jam_selesai',
            'pengampu_mp'
        )
            ->with(['pengambilan_mp' => function ($q) {
                $q->where('status_apv_pengambilan_mp', 1);
            }])
            ->where('id_semester', '=', $id_semester);

        return Datatables::of($list_data)
                ->addColumn('jml_jadwal_jam', function ($item) {
                    $jml_jadwal_jam = 0;
                    foreach ($item->jadwal_kelas_mp as $jadwal) {
                        $jml_jadwal_jam += $jadwal->jadwal_jam_selesai->jam_ke - $jadwal->jadwal_jam_mulai->jam_ke + 1;
                    }
                    return $jml_jadwal_jam;
                })
                ->addColumn('jml_jadwal', function ($item) {
                    return $item->jadwal_kelas_mp->count();
                })
                ->addColumn('jml_siswa', function ($item) {
                    return $item->pengambilan_mp->count();
                })
                ->addColumn('jml_pengampu', function ($item) {
                    return $item->pengampu_mp->count();
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_kelas_mp
                    );
                    return $data;
                })
                ->make(true);
    }

    public function copyJadwalSemesterLain(Request $request, $id_semester)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $now = (int) $semester->thn_akademik_semester + 1;

        /*$tahun_sebelum = Carbon::now()->subYears(3)->year;*/

        $tahun_sebelum = (int) $semester->thn_akademik_semester - 2;

        $data_semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                            ->where('id_semester', '<>', $semester->id_semester)
                            ->whereBetween('thn_akademik_semester', [$tahun_sebelum, $now])
                            ->orderBy('thn_akademik_semester', 'asc')
                            ->orderBy('nm_semester', 'asc')
                            ->get();

        return view('akademik/aktivitas-semester/usulan-mata-ajar/copy-semester-usulan-mata-ajar', compact('auth_data', 'id_semester', 'semester', 'data_semester'));
    }

    public function actionUsulanMataAjar(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // ACTION ADD
            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                // $semester    = Semester::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->where('is_aktif_semester','=','1')->first();
                $kelas                              = Kelas::where('id_kelas', '=', $input->id_kelas)->first();
                $mapel                              = MataPelajaran::where('id_mata_pelajaran', '=', $input->id_mata_pelajaran)->first();
                $nm_kelas_mp                        = $mapel->nm_mata_pelajaran.'-'.$kelas->nm_kelas;
                // ADA BUG DI NAMA_KELAS_MP entah add/copy
                
                $kelas_mp                           = new KelasMp;
                $kelas_mp->id_kelas_mp              = $id;
                $kelas_mp->id_semester              = $input->id_semester;
                $kelas_mp->id_kelas                 = $input->id_kelas;
                $kelas_mp->id_mata_pelajaran        = $input->id_mata_pelajaran;
                $kelas_mp->nm_kelas_mp              = $nm_kelas_mp;
                $kelas_mp->jml_pertemuan_kelas_mp   = $input->jml_pertemuan_kelas_mp;
                $kelas_mp->created_by               = $input->auth_data->pengguna->id_pengguna;
                $kelas_mp->created_at               = $now;
                $kelas_mp->save();

                return [
                    'status'    =>  202, // SUCCESS AND LOAD CONTENT
                    'message'   =>  'Save Usulan Mata Ajar successfully',
                    'path'      =>  'aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$input->id_semester
                ];
            } elseif ($mode == 'copy') {
                $batch_insert_kelas_mp = [];
                foreach ($input->id_kelas as $id_kelas) {
                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    
                    $kelas                              = Kelas::where('id_kelas', '=', $input->id_kelas)->first();
                    $mapel                              = MataPelajaran::where('id_mata_pelajaran', '=', $input->id_mata_pelajaran)->first();
                    $nm_kelas_mp                        = $mapel->nm_mata_pelajaran.'-'.$kelas->nm_kelas;
                    
                    $batch_insert_kelas_mp[] = array(
                        'id_kelas_mp'              => $id,
                        'id_semester'              => $input->id_semester,
                        'id_kelas'                 => $id_kelas,
                        'id_mata_pelajaran'        => $input->id_mata_pelajaran,
                        'nm_kelas_mp'              => $nm_kelas_mp,
                        'jml_pertemuan_kelas_mp'   => $input->jml_pertemuan_kelas_mp,
                        'created_by'               => $input->auth_data->pengguna->id_pengguna,
                        'created_at'               => $now,
                        'updated_by'               => $input->auth_data->pengguna->id_pengguna,
                        'updated_at'               => $now,
                    );
                }

                \App\Jobs\CopyUsulanMataAjar::dispatch($batch_insert_kelas_mp);

                return [
                    'status'    =>  202, // SUCCESS AND LOAD CONTENT
                    'message'   =>  'Save Usulan Mata Ajar successfully',
                    'path'      =>  'aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$input->id_semester
                ];
            } elseif ($mode == 'copy-jadwal-semester') {
                // semester paste
                $id_semester = $id;
                
                // semester copy
                $id_semester_copy = $input->id_semester_copy;

                DB::beginTransaction();

                try {

                    // proses tabel kelas_mp
                    $kelas_mp_set = KelasMp::where('id_semester', '=', $id_semester_copy)->get();

                    $batch_insert_kelas_mp = [];
                    $batch_insert_jadwal_kelas_mp = [];
                    $batch_insert_pengampu_mp = [];
                    foreach ($kelas_mp_set as $kelas_mp) {
                        $id_kelas_mp                = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $id_kelas                   = $kelas_mp->id_kelas;
                        $id_mata_pelajaran          = $kelas_mp->id_mata_pelajaran;
                        $nm_kelas_mp                = $kelas_mp->nm_kelas_mp;
                        $jml_pertemuan_kelas_mp     = $kelas_mp->jml_pertemuan_kelas_mp;

                        $batch_insert_kelas_mp[] = array(
                            'id_kelas_mp'               => $id_kelas_mp,
                            'id_semester'               => $id_semester,
                            'id_kelas'                  => $id_kelas,
                            'id_mata_pelajaran'         => $id_mata_pelajaran,
                            'nm_kelas_mp'               => $nm_kelas_mp,
                            'jml_pertemuan_kelas_mp'    => $jml_pertemuan_kelas_mp,
                            'created_by'                => $input->auth_data->pengguna->id_pengguna,
                            'created_at'                => $now
                        );

                        // proses tabel jadwal_kelas_mp
                        $jadwal_kelas_mp_set = JadwalKelasMp::where('id_kelas_mp', '=', $kelas_mp->id_kelas_mp)
                                                ->get();

                        foreach ($jadwal_kelas_mp_set as $jadwal_kelas_mp) {
                            $id_jadwal_kelas_mp         = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_ruangan                 = $jadwal_kelas_mp->id_ruangan;
                            $id_jadwal_hari             = $jadwal_kelas_mp->id_jadwal_hari;
                            $id_jadwal_jam              = $jadwal_kelas_mp->id_jadwal_jam;
                            $id_jadwal_jam_selesai      = $jadwal_kelas_mp->id_jadwal_jam_selesai;

                            $batch_insert_jadwal_kelas_mp[] = array(
                                'id_jadwal_kelas_mp'        => $id_jadwal_kelas_mp,
                                'id_kelas_mp'               => $id_kelas_mp,
                                'id_ruangan'                => $id_ruangan,
                                'id_jadwal_hari'            => $id_jadwal_hari,
                                'id_jadwal_jam'             => $id_jadwal_jam,
                                'id_jadwal_jam_selesai'     => $id_jadwal_jam_selesai,
                                'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                'created_at'                => $now
                            );
                        }

                        // proses tabel pengampu_mp
                        $pengampu_mp_set = PengampuMp::where('id_kelas_mp', '=', $kelas_mp->id_kelas_mp)
                                                ->where('pjmp_pengampu_mp', '=', 1)
                                                ->get();

                        foreach ($pengampu_mp_set as $pengampu_mp) {
                            $id_pengampu_mp             = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $id_guru                    = $pengampu_mp->id_guru;
                            $pjmp_pengampu_mp           = $pengampu_mp->pjmp_pengampu_mp;
                            $pjmp_uts                   = $pengampu_mp->pjmp_uts;
                            $pjmp_uas                   = $pengampu_mp->pjmp_uas;
                            $nomor_sk_mengajar          = $pengampu_mp->nomor_sk_mengajar;
                            $tgl_sk_mengajar            = $pengampu_mp->tgl_sk_mengajar;

                            $batch_insert_pengampu_mp[] = array(
                                'id_pengampu_mp'            => $id_pengampu_mp,
                                'id_kelas_mp'               => $id_kelas_mp,
                                'id_guru'                   => $id_guru,
                                'pjmp_pengampu_mp'          => $pjmp_pengampu_mp,
                                'pjmp_uts'                  => $pjmp_uts,
                                'pjmp_uas'                  => $pjmp_uas,
                                'nomor_sk_mengajar'         => $nomor_sk_mengajar,
                                'tgl_sk_mengajar'           => $tgl_sk_mengajar,
                                'created_by'                => $input->auth_data->pengguna->id_pengguna,
                                'created_at'                => $now
                            );
                        }
                    }

                    if (sizeof($batch_insert_kelas_mp) > 0) {
                        KelasMp::insert($batch_insert_kelas_mp);
                    }

                    if (!empty($input->jadwal)) {
                        if (sizeof($batch_insert_jadwal_kelas_mp) > 0) {
                            JadwalKelasMp::insert($batch_insert_jadwal_kelas_mp);
                        }
                        
                        if (sizeof($batch_insert_pengampu_mp) > 0) {
                            PengampuMp::insert($batch_insert_pengampu_mp);
                        }
                    }
                


                    DB::commit();
                    // all good

                    return [
                        'status'    =>  202, // SUCCESS AND LOAD CONTENT
                        'message'   =>  'Save Copy Usulan Mata Ajar successfully',
                        'path'      =>  'aktivitas-semester/usulan-mata-ajar/view-semester-usulan-mata-ajar/'.$id_semester
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 300, // GAGAL
                                'message' => 'Edit Usulan Mata Ajar Gagal! '
                            ];
                }
            } elseif ($mode == 'edit') {
                if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1)) {
                    $cek_jadwal = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1);
                    
                    if ($cek_jadwal['guru'] == 0) {
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 1'
                        ];
                    } elseif ($cek_jadwal['ruangan'] == 0) {
                        return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 1 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                    }
                }

                if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan2, $input->hari_jadwal2, $input->jam_jadwal2, $input->jam_jadwal_selesai2)) {
                        $cek_jadwal2 = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan2, $input->hari_jadwal2, $input->jam_jadwal2, $input->jam_jadwal_selesai2);

                        if ($cek_jadwal2['guru'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 2'
                        ];
                        } elseif ($cek_jadwal2['ruangan'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 2 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                        }
                    }
                }

                if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan3, $input->hari_jadwal3, $input->jam_jadwal3, $input->jam_jadwal_selesai3)) {
                        $cek_jadwal3 = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan3, $input->hari_jadwal3, $input->jam_jadwal3, $input->jam_jadwal_selesai3);

                        if ($cek_jadwal3['guru'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 3'
                        ];
                        } elseif ($cek_jadwal3['ruangan'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 3 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                        }
                    }
                }

                if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan4, $input->hari_jadwal4, $input->jam_jadwal4, $input->jam_jadwal_selesai4)) {
                        $cek_jadwal4 = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan4, $input->hari_jadwal4, $input->jam_jadwal4, $input->jam_jadwal_selesai4);

                        if ($cek_jadwal4['guru'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 4'
                        ];
                        } elseif ($cek_jadwal4['ruangan'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 4 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                        }
                    }
                }

                if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan5, $input->hari_jadwal5, $input->jam_jadwal5, $input->jam_jadwal_selesai5)) {
                        $cek_jadwal5 = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan5, $input->hari_jadwal5, $input->jam_jadwal5, $input->jam_jadwal_selesai5);

                        if ($cek_jadwal5['guru'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 5'
                        ];
                        } elseif ($cek_jadwal5['ruangan'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 5 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                        }
                    }
                }

                if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                    if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan6, $input->hari_jadwal6, $input->jam_jadwal6, $input->jam_jadwal_selesai6)) {
                        $cek_jadwal6 = LibAkademik::cekJadwalKelas($auth_data, $input->pjma, $input->ruangan6, $input->hari_jadwal6, $input->jam_jadwal6, $input->jam_jadwal_selesai6);

                        if ($cek_jadwal6['guru'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Guru PJMA Sudah Mempunyai Jadwal di Hari dan Jam yang Sama Pada Jadwal 6'
                        ];
                        } elseif ($cek_jadwal6['ruangan'] == 0) {
                            return [
                            'status' => 300, // FAILED
                            'message' => 'Ruangan Pada Jadwal 6 Sudah Mempunyai Jadwal di Hari dan Jam yang Sama'
                        ];
                        }
                    }
                }

                DB::beginTransaction();

                try {
                    $kelasMp = KelasMp::where('id_kelas_mp', '=', $input->id_kelas_mp)->first();
                    
                    $kelas                              = Kelas::where('id_kelas', '=', $kelasMp->id_kelas)->first();
                    $mapel                              = MataPelajaran::where('id_mata_pelajaran', '=', $input->id_mata_pelajaran)->first();
                    $nm_kelas_mp                        = $mapel->nm_mata_pelajaran.'-'.$kelas->nm_kelas;

                    $kelasMp->nm_kelas_mp = $nm_kelas_mp;
                    $kelasMp->save();

                    //input jadwal 1
                    if (! empty($input->id_jadwal_kelas_mp_1)) {
                        $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_1);
                        $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jam_jadwal_selesai1;
                        $jadwal_kelas_mp->id_ruangan            = $input->ruangan1;
                        $jadwal_kelas_mp->updated_at            = $now;
                        $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                        $jadwal_kelas_mp->save();
                    } else {
                        $jadwal_kelas_mp                        = new JadwalKelasMp;
                        $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $jadwal_kelas_mp->id_kelas_mp           = $id;
                        $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal1;
                        $jadwal_kelas_mp->id_jadwal_jam_selesai = $input->jam_jadwal_selesai1;
                        $jadwal_kelas_mp->id_ruangan            = $input->ruangan1;
                        $jadwal_kelas_mp->created_at            = $now;
                        $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                        $jadwal_kelas_mp->save();
                    }
                    

                    //input jadwal 2
                    if (! empty($input->id_jadwal_kelas_mp_2)) {
                        if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_2);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai2)? $input->jam_jadwal_selesai2 : $input->jam_jadwal2);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan2;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal2 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal2 != null or $input->ruangan2 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_2);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal2 != null && $input->jam_jadwal_selesai2 != null && $input->hari_jadwal2 != null && $input->ruangan2 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal2;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai2)? $input->jam_jadwal_selesai2 : $input->jam_jadwal2);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan2;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 3
                    if (! empty($input->id_jadwal_kelas_mp_3)) {
                        if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_3);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai3)? $input->jam_jadwal_selesai3 : $input->jam_jadwal3);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan3;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal3 != null or $input->jam_jadwal_selesai3 != null or $input->hari_jadwal3 != null or $input->ruangan3 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_3);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal3 != null && $input->jam_jadwal_selesai3 != null && $input->hari_jadwal3 != null && $input->ruangan3 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal3;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai3)? $input->jam_jadwal_selesai3 : $input->jam_jadwal3);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan3;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 4
                    if (! empty($input->id_jadwal_kelas_mp_4)) {
                        if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_4);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai4)? $input->jam_jadwal_selesai4 : $input->jam_jadwal4);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan4;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal4 != null or $input->jam_jadwal_selesai4 != null or $input->hari_jadwal4 != null or $input->ruangan4 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_4);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal4 != null && $input->jam_jadwal_selesai4 != null && $input->hari_jadwal4 != null && $input->ruangan4 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal4;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai4)? $input->jam_jadwal_selesai4 : $input->jam_jadwal4);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan4;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }
                    
                    //input jadwal 5
                    if (! empty($input->id_jadwal_kelas_mp_5)) {
                        if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_5);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai5)? $input->jam_jadwal_selesai5 : $input->jam_jadwal5);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan5;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal5 != null or $input->jam_jadwal_selesai5 != null or $input->hari_jadwal5 != null or $input->ruangan5 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_5);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal5 != null && $input->jam_jadwal_selesai5 != null && $input->hari_jadwal5 != null && $input->ruangan5 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal5;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai5)? $input->jam_jadwal_selesai5 : $input->jam_jadwal5);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan5;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input jadwal 6
                    if (! empty($input->id_jadwal_kelas_mp_6)) {
                        if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                            $jadwal_kelas_mp                        = JadwalKelasMp::find($input->id_jadwal_kelas_mp_6);
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai6)? $input->jam_jadwal_selesai6 : $input->jam_jadwal6);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan6;
                            $jadwal_kelas_mp->updated_at            = $now;
                            $jadwal_kelas_mp->updated_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        } elseif ($input->jam_jadwal6 != null or $input->jam_jadwal_selesai6 != null or $input->hari_jadwal6 != null or $input->ruangan6 != null) {
                        } else {
                            // make object to find id
                            $jadwal_kelas_mp               = JadwalKelasMp::find($input->id_jadwal_kelas_mp_6);
                            $jadwal_kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();

                            $jadwal_kelas_mp->delete();
                        }
                    } else {
                        if ($input->jam_jadwal6 != null && $input->jam_jadwal_selesai6 != null && $input->hari_jadwal6 != null && $input->ruangan6 != null) {
                            $jadwal_kelas_mp                        = new JadwalKelasMp;
                            $jadwal_kelas_mp->id_jadwal_kelas_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $jadwal_kelas_mp->id_kelas_mp           = $id;
                            $jadwal_kelas_mp->id_jadwal_hari        = $input->hari_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam         = $input->jam_jadwal6;
                            $jadwal_kelas_mp->id_jadwal_jam_selesai = (!empty($input->jam_jadwal_selesai6)? $input->jam_jadwal_selesai6 : $input->jam_jadwal6);
                            $jadwal_kelas_mp->id_ruangan            = $input->ruangan6;
                            $jadwal_kelas_mp->created_at            = $now;
                            $jadwal_kelas_mp->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $jadwal_kelas_mp->save();
                        }
                    }

                    //input pjma
                    if (! empty($input->id_pengampu_mp_pj)) {
                        $pengampu_mp                    = PengampuMp::find($input->id_pengampu_mp_pj);
                        $pengampu_mp->id_guru           = $input->pjma;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->pjmp_uts          = 1;
                        $pengampu_mp->pjmp_uas          = 1;
                        $pengampu_mp->updated_at        = $now;
                        $pengampu_mp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    } else {
                        $pengampu_mp                    = new PengampuMp;
                        $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $pengampu_mp->id_kelas_mp       = $id;
                        $pengampu_mp->id_guru           = $input->pjma;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->pjmp_uts          = 1;
                        $pengampu_mp->pjmp_uas          = 1;
                        $pengampu_mp->created_at        = $now;
                        $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    }
                    

                    // tim pjma 1
                    if (! empty($input->id_pengampu_mp_1)) {
                        if ($input->pjma_tim1 != null) {
                            $pengampu_mp                    = PengampuMp::find($input->id_pengampu_mp_1);
                            $pengampu_mp->id_guru           = $input->pjma_tim1;
                            $pengampu_mp->pjmp_pengampu_mp  = 2;
                            $pengampu_mp->pjmp_uts          = 0;
                            $pengampu_mp->pjmp_uas          = 0;
                            $pengampu_mp->updated_at        = $now;
                            $pengampu_mp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();
                        } else {
                            // make object to find id
                            $pengampu_mp               = PengampuMp::find($input->id_pengampu_mp_1);
                            $pengampu_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();

                            $pengampu_mp->delete();
                        }
                    } else {
                        if ($input->pjma_tim1 != null) {
                            $pengampu_mp                    = new PengampuMp;
                            $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $pengampu_mp->id_kelas_mp       = $id;
                            $pengampu_mp->id_guru           = $input->pjma_tim1;
                            $pengampu_mp->pjmp_pengampu_mp  = 2;
                            $pengampu_mp->pjmp_uts          = 0;
                            $pengampu_mp->pjmp_uas          = 0;
                            $pengampu_mp->created_at        = $now;
                            $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();
                        }
                    }

                    // tim pjma 2
                    if (! empty($input->id_pengampu_mp_2)) {
                        if ($input->pjma_tim2 != null) {
                            $pengampu_mp                    = PengampuMp::find($input->id_pengampu_mp_2);
                            $pengampu_mp->id_guru           = $input->pjma_tim2;
                            $pengampu_mp->pjmp_pengampu_mp  = 2;
                            $pengampu_mp->pjmp_uts          = 0;
                            $pengampu_mp->pjmp_uas          = 0;
                            $pengampu_mp->updated_at        = $now;
                            $pengampu_mp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();
                        } else {
                            // make object to find id
                            $pengampu_mp               = PengampuMp::find($input->id_pengampu_mp_2);
                            $pengampu_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();

                            $pengampu_mp->delete();
                        }
                    } else {
                        if ($input->pjma_tim2 != null) {
                            $pengampu_mp                    = new PengampuMp;
                            $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $pengampu_mp->id_kelas_mp       = $id;
                            $pengampu_mp->id_guru           = $input->pjma_tim2;
                            $pengampu_mp->pjmp_pengampu_mp  = 2;
                            $pengampu_mp->pjmp_uts          = 0;
                            $pengampu_mp->pjmp_uas          = 0;
                            $pengampu_mp->created_at        = $now;
                            $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
                            $pengampu_mp->save();
                        }
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Update Usulan Mata Ajar successfully',
                        'path'      =>  'aktivitas-semester/usulan-mata-ajar/edit/'.$id
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 300, // GAGAL
                                'message' => 'Edit Usulan Mata Ajar Gagal!'
                            ];
                }
            } elseif ($mode == 'delete') {
                if ($kelas_mp = PengambilanMp::where('id_kelas_mp', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Terdapat siswa yang telah mengambil kelas ini'
                    ];
                } else {
                    // make object to find id
                    $kelas_mp               = KelasMp::find($id);
                    $kelas_mp->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kelas_mp->save();
                    $kelas_mp->delete();

                    JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                    PengampuMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    PengampuMp::where('id_kelas_mp', $id)->delete();


                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Mata Ajar successfully'
                    ];
                }
            }
        }
    }
}
