<?php

namespace App\Http\Controllers\Guru\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\KelasMp as KelasMp;
use App\Models\Guru as Guru;
use App\Models\PengampuMp as PengampuMp;
use App\Models\JadwalHari as JadwalHari;
use App\Models\Ruangan as Ruangan;
use App\Models\JadwalJam as JadwalJam;
use App\Models\JadwalKelasMp as JadwalKelasMp;
use App\Models\PengambilanMp as PengambilanMp;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class InputJadwalController extends BaseController
{
    public function viewInputJadwal(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $id = $semester->id_semester;

        $id_pengguna = $auth_data->pengguna->id_pengguna;

        $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
        $id_guru = $guru->id_guru;
       
        return view('guru/jadwal/input-jadwal/view-input-jadwal', compact('auth_data', 'semester', 'id', 'id_guru'));
    }

    public function datatablesInputJadwal(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = KelasMp::select(
            'mata_pelajaran.nm_mata_pelajaran',
            'mata_pelajaran.kd_mata_pelajaran',
            'kelas.nm_kelas',
            'kelas_mp.id_kelas_mp',
            'mata_pelajaran.kredit_semester',
            'mata_pelajaran.tingkat_semester',
            'kelas_mp.nm_kelas_mp',
            'jenis_mata_pelajaran.nm_jenis_mata_pelajaran',
            'pengampu_mp.id_guru',
            'pengguna.nm_pengguna'
        )
            ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
            ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
            ->join('jenis_mata_pelajaran', 'jenis_mata_pelajaran.id_jenis_mata_pelajaran', '=', 'mata_pelajaran.id_jenis_mata_pelajaran')
            ->leftJoin('pengampu_mp', function ($join) {
                $join->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                 ->where('pengampu_mp.pjmp_pengampu_mp', '=', 1)
                                 ->whereNull('pengampu_mp.deleted_at');
            })
            ->leftJoin('guru', 'guru.id_guru', '=', 'pengampu_mp.id_guru')
            ->leftJoin('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
            ->where('kelas_mp.id_semester', '=', $id)
            ->orderBy('mata_pelajaran.nm_mata_pelajaran', 'asc')
            ->orderBy('kelas.nm_kelas', 'asc')
            ->orderBy('mata_pelajaran.tingkat_semester', 'asc');

        return Datatables::of($list_data)
                ->editColumn('jml_jadwal_jam', function ($item) {
                    if (!empty($item->jml_jadwal_jam)) {
                        return $item->jml_jadwal_jam;
                    } else {
                        return '0';
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_kelas_mp,
                        'id_guru' => $item->id_guru,
                        'nm_pengguna' => $item->nm_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }

    public function editInputJadwal(Request $request, $id)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $kelas_mp   = KelasMp::select('mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran', 'kelas.nm_kelas', 'kelas.id_kelas' ,'jadwal_hari.nm_jadwal_hari', 'jadwal_jam.nm_jadwal_jam', DB::raw("(SELECT COUNT(*) FROM pengambilan_mp WHERE pengambilan_mp.id_kelas_mp = kelas_mp.id_kelas_mp AND pengambilan_mp.status_apv_pengambilan_mp = 1 AND pengambilan_mp.deleted_at IS NULL) AS jml_siswa"), 'kelas_mp.id_kelas_mp', 'mata_pelajaran.kredit_semester', 'mata_pelajaran.tingkat_semester', 'ruangan.nm_ruangan', 'gedung.nm_gedung', 'ruangan.kapasitas_ruangan', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'kelas_mp.nm_kelas_mp', 'kelas_mp.jml_pertemuan_kelas_mp', 'semester.nm_semester', 'semester.tahun_ajaran', 'semester.id_semester')
            ->leftJoin('jadwal_kelas_mp', 'jadwal_kelas_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
            ->leftJoin('jadwal_hari', 'jadwal_hari.id_jadwal_hari', '=', 'jadwal_kelas_mp.id_jadwal_hari')
            ->leftJoin('jadwal_jam', 'jadwal_jam.id_jadwal_jam', '=', 'jadwal_kelas_mp.id_jadwal_jam')
            ->leftJoin('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
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
        return view('guru/jadwal/input-jadwal/edit-input-jadwal', compact('auth_data', 'id', 'pjma', 'hari', 'ruangan', 'jam', 'kelas_mp', 'jadwal', 'jml_jadwal', 'pengampu_mp_pj', 'anggota', 'jml_anggota'));
    }

    public function actionInputJadwal(Request $request, $mode, $id = null)
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
            if ($mode == 'edit') {
                $id_pengguna = $auth_data->pengguna->id_pengguna;

                $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
                $id_guru = $guru->id_guru;
                if (LibAkademik::cekJadwalKelasMpBerubah($auth_data, $id, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1)) {
                    $cek_jadwal = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan1, $input->hari_jadwal1, $input->jam_jadwal1, $input->jam_jadwal_selesai1);

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
                        $cek_jadwal2 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan2, $input->hari_jadwal2, $input->jam_jadwal2, $input->jam_jadwal_selesai2);

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
                        $cek_jadwal3 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan3, $input->hari_jadwal3, $input->jam_jadwal3, $input->jam_jadwal_selesai3);

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
                        $cek_jadwal4 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan4, $input->hari_jadwal4, $input->jam_jadwal4, $input->jam_jadwal_selesai4);

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
                        $cek_jadwal5 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan5, $input->hari_jadwal5, $input->jam_jadwal5, $input->jam_jadwal_selesai5);

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
                        $cek_jadwal6 = LibAkademik::cekJadwalKelas($auth_data, $id_guru, $input->ruangan6, $input->hari_jadwal6, $input->jam_jadwal6, $input->jam_jadwal_selesai6);

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
                        } elseif ($input->jam_jadwal4 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal4 != null or $input->ruangan4 != null) {
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
                        } elseif ($input->jam_jadwal5 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal5 != null or $input->ruangan5 != null) {
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
                        } elseif ($input->jam_jadwal6 != null or $input->jam_jadwal_selesai2 != null or $input->hari_jadwal6 != null or $input->ruangan6 != null) {
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
                        $pengampu_mp->id_guru           = $id_guru;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->updated_at        = $now;
                        $pengampu_mp->updated_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    } else {
                        $pengampu_mp                    = new PengampuMp;
                        $pengampu_mp->id_pengampu_mp    = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $pengampu_mp->id_kelas_mp       = $id;
                        $pengampu_mp->id_guru           = $id_guru;
                        $pengampu_mp->pjmp_pengampu_mp  = 1;
                        $pengampu_mp->created_at        = $now;
                        $pengampu_mp->created_by        = $input->auth_data->pengguna->id_pengguna;
                        $pengampu_mp->save();
                    }

                    DB::commit();
                    // all good

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Update Usulan Mata Ajar Successfully',
                        'path'      =>  'jadwal/input-jadwal'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong

                    return [
                                'status' => 203, // GAGAL
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
                    JadwalKelasMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    JadwalKelasMp::where('id_kelas_mp', $id)->delete();

                    PengampuMp::where('id_kelas_mp', $id)->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    PengampuMp::where('id_kelas_mp', $id)->delete();


                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Jadwal Mata Ajar Successfully'
                    ];
                }
            }
        }
    }
}
