<?php

namespace App\Http\Controllers\Akademik\Presensi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Kurikulum;
use App\Models\MataPelajaran;
use App\Models\Semester;
use App\Models\KelasMp;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\PengampuMp;
use App\Models\JadwalHari;
use App\Models\Ruangan;
use App\Models\JadwalJam;
use App\Models\JadwalKelasMp;
use App\Models\PengambilanMp;
use App\Models\UjianMp;
use App\Models\Siswa;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\SumberDaya\LibGuru;

use Auth;
use DB;
use PDF;
use Session;
use Validator;

class CetakPresensiUTSController extends BaseController
{
    //
    public function viewCetakPresensiUTS(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/presensi/cetak-presensi-uts/view-cetak-presensi-uts', compact('auth_data', 'data_semester'));
    }

    public function actionViewCetakPresensiUTS(Request $request)
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
                'path' => 'presensi/cetak-presensi-uts/view-semester-cetak-presensi-uts/'.$input->id_semester
            ];
        }
    }

    public function viewSemesterCetakPresensiUTS(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester   = Semester::where('id_semester', '=', $id)->first();

        $kelas_mp = LibAkademik::FetchDataUsulanMataAjar($auth_data, $semester->id_semester);
       
        return view('akademik/presensi/cetak-presensi-uts/view-semester-cetak-presensi-uts', compact('auth_data', 'semester', 'id', 'kelas_mp'));
    }

    public function datatablesCetakPresensiUTS(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        //kode kegiatan diganti sesuai jenis ujiannya
        $list_data = UjianMp::select('ujian_mp.nm_ujian_mp', 'kegiatan.nm_kegiatan', 'ujian_mp.is_online', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'ujian_mp.keterangan', 'ruangan.nm_ruangan', 'ruangan.kapasitas_ujian', 'gedung.kode_gedung', 'ujian_mp.id_ujian_mp', 'ujian_mp.id_kelas_mp', 'kelas_mp.nm_kelas_mp', 'kelas_mp.id_semester', 'semester.nm_semester', 'semester.tahun_ajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'kegiatan.id_kegiatan', 'pengampu_mp.id_guru')
                        ->join('kegiatan', function ($q) {
                            $q->on('kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
                                ->whereNull('kegiatan.deleted_at');
                        })
                        ->leftJoin('ujian_mp_ruangan', function ($q) {
                            $q->on('ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
                                ->whereNull('ujian_mp_ruangan.deleted_at');
                        })
                        ->leftJoin('ruangan', function ($q) {
                            $q->on('ujian_mp_ruangan.id_ruangan', '=', 'ruangan.id_ruangan')
                                ->whereNull('ruangan.deleted_at');
                        })
                        ->leftJoin('gedung', function ($q) {
                            $q->on('gedung.id_gedung', '=', 'ruangan.id_gedung')
                                ->whereNull('gedung.deleted_at');
                        })
                        ->join('kelas_mp', function ($q) {
                            $q->on('kelas_mp.id_kelas_mp', '=', 'ujian_mp.id_kelas_mp')
                                ->whereNull('kelas_mp.deleted_at');
                        })
                        ->join('semester', function ($q) {
                            $q->on('semester.id_semester', '=', 'kelas_mp.id_semester')
                                ->whereNull('semester.deleted_at');
                        })
                        ->join('mata_pelajaran', function ($q) {
                            $q->on('mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
                                ->whereNull('mata_pelajaran.deleted_at');
                        })
                        ->leftJoin('pengampu_mp', function ($q) {
                            $q->on('pengampu_mp.id_kelas_mp', '=', 'kelas_mp.id_kelas_mp')
                                ->whereNull('pengampu_mp.deleted_at');
                        })
                        ->where('ujian_mp.is_online', '=', 0)
                        ->where('kelas_mp.id_semester', '=', $id)
                        ->where('kegiatan.kode_kegiatan', '=', 'UTS')
                        ->orderBy('ujian_mp.created_at', 'desc')
                        ->get();

        return Datatables::of($list_data)
            ->addColumn('ruangan_ujian', function ($item) {
                if ($item->nm_gedung == null) {
                    return $item->nm_ruangan;
                } elseif ($item->nm_ruangan == null && $item->nm_gedung == null) {
                    return "-";
                } else {
                    return $item->nm_ruangan." (".$item->nm_gedung.")";
                }
            })
            ->addColumn('jenis_ujian', function ($item) {
                if ($item->is_online == 1) {
                    return $item->nm_kegiatan." Online";
                } elseif ($item->is_online == 0) {
                    return $item->nm_kegiatan." Reguler";
                }
            })
            ->addColumn('semester', function ($item) {
                return $item->nm_semester.' ('.$item->tahun_ajaran.')';
            })
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->nm_mata_pelajaran.' ('.$item->kd_mata_pelajaran.')';
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_ujian_mp,
                    'pengampu' => $item->id_guru
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakPresensiUTS(Request $request, $id_ujian_mp, $id_guru)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $guru = Guru::find($id_guru);

        $data_kelas = LibGuru::fetchDataJadwalUTS($auth_data, $guru->id_pengguna, $semester_aktif->id_semester, 0, $id_ujian_mp);

        $kelas = UjianMp::where('id_ujian_mp', '=', $id_ujian_mp)->first();

        $data_siswa = Siswa::select('siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'siswa.id_kelas', 'kelas.nm_kelas', 'kelas_mp.nm_kelas_mp', 'siswa.id_siswa')
                ->join('kelas', function ($q) {
                    $q->on('kelas.id_kelas', '=', 'siswa.id_kelas')
                        ->whereNull('kelas.deleted_at');
                })
                ->join('kelas_mp', function ($q) {
                    $q->on('kelas_mp.id_kelas', '=', 'kelas.id_kelas')
                        ->whereNull('kelas_mp.deleted_at');
                })
                ->join('pengguna', function ($q) {
                    $q->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->whereNull('pengguna.deleted_at');
                })
                ->where('kelas_mp.id_kelas_mp', '=', $kelas->id_kelas_mp)
                ->get();

        $pdf = PDF::loadView('akademik/presensi/cetak-presensi-uts/download-cetak-presensi-uts', compact('data_siswa', 'auth_data', 'semester_aktif', 'data_kelas'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
}
