<?php

namespace App\Http\Controllers\Keuangan\Utility;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Siswa as Siswa;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\WaliKelas;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class PembayaranByKelasController extends BaseController
{
    public function viewPembayaranByKelas(Request $request, $tahun_akademik_semester = null, $id_kelas = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('keuangan/utility/pembayaran-by-kelas/view-pembayaran-by-kelas', compact('auth_data', 'data_semester', 'data_kelas', 'tahun_akademik_semester', 'id_kelas'));
    }

    public function actionViewPembayaranByKelas(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester' => 'required',
            'id_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'utility/pembayaran-by-kelas/view-detail/' . $input->tahun_akademik_semester . '/' . $input->id_kelas,
            ];
        }
    }

    public function viewDetailPembayaranByKelas(Request $request, $tahun_akademik_semester, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester = Semester::orderBy('tahun_ajaran', 'asc')->get();

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = $semester->firstWhere('is_aktif_semester', 1);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = $semester->unique('thn_akademik_semester');

        $data_kelas = Kelas::select('id_kelas', 'nm_kelas')->where('is_aktif', 1)->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();

        if (!empty($id_kelas) && !empty($tahun_akademik_semester)) {
            $semester_mulai = $semester->firstWhere('kode_semester', $tahun_akademik_semester . '1');
            $semester_selesai = $semester->firstWhere('kode_semester', $tahun_akademik_semester . '2');

            $query_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'biaya.nm_biaya', 'semester.kode_semester', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'tagihan_biaya.is_request', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'bulan.kode_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', 'tagihan_biaya.besar_pembayaran', 'tagihan_biaya.tgl_pelunasan', 'tagihan_biaya.updated_at')
                ->join('siswa', function ($q) {
                    $q->on('tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
                        ->whereNull('siswa.deleted_at');
                })
                ->leftJoin('detail_biaya', function ($q) {
                    $q->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                        ->whereNull('detail_biaya.deleted_at');
                })
                ->leftJoin('biaya', function ($q) {
                    $q->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                        ->whereNull('biaya.deleted_at');
                })
                ->leftJoin('biaya_sekolah', function ($q) {
                    $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                        ->whereNull('biaya_sekolah.deleted_at');
                })
                ->leftJoin('semester', function ($q) {
                    $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
                        ->whereNull('semester.deleted_at');
                })
                ->leftJoin('bulan', function ($q) {
                    $q->on('detail_biaya.id_bulan', '=', 'bulan.id_bulan')
                        ->whereNull('bulan.deleted_at');
                })
                ->where('detail_biaya.validasi_biaya', 1)
                ->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester])
                ->where('tagihan_biaya.id_kelas', $id_kelas);

            $clone_query_tagihan = clone $query_tagihan;
            $data_tagihan = $query_tagihan->where('detail_biaya.id_jenis_detail_biaya', 4)->get();

            $clone_query_tagihan->where('detail_biaya.id_jenis_detail_biaya', '<>', 4);
            $data_tagihan_non_bulanan = $clone_query_tagihan->get();

            $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->sortBy('kode_semester')->values()->all();

            $data_siswa = Siswa::with('pengguna', 'pengguna.status_pengguna')->whereIn('siswa.id_siswa', $data_tagihan->unique('id_siswa')->pluck('id_siswa')->values()->all())
                ->orderBy('nis_siswa')->get();

            $data_ket_tagihan = $data_tagihan_non_bulanan->unique('keterangan')->sortByDesc('keterangan');
        } else {
            $total_pembayaran = array();
            $data_siswa = array();
            $data_tagihan = array();
            $data_bulan_tagihan = array();

            $data_tagihan_non_bulanan = array();
            $data_ket_tagihan = array();
            $data_tagihan_siswa_semester_lalu = array();
        }

        return view('keuangan/utility/pembayaran-by-kelas/view-pembayaran-by-kelas', compact('auth_data', 'data_semester', 'data_kelas', 'tahun_akademik_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan', 'data_tagihan_non_bulanan', 'data_ket_tagihan'));
    }

    public function printPembayaranByKelas(Request $request, $tahun_akademik_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data, $id_kelas);
        // $wali_kelas = WaliKelas::with('guru.pengguna')->where('id_kelas', $data_kelas->id_kelas)->first();

        if (!empty($id_kelas) && !empty($tahun_akademik_semester)) {
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

            // $data_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'biaya.nm_biaya', 'semester.kode_semester', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'tagihan_biaya.is_request', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"), 'pembayaran_biaya.tgl_pembayaran', 'pembayaran_biaya.id_pembayaran_biaya')
            //     ->join('siswa', function ($q) {
            //         $q->on('tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
            //             ->whereNull('siswa.deleted_at');
            //     })
            //     ->leftJoin('detail_biaya', function ($q) {
            //         $q->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
            //             ->whereNull('detail_biaya.deleted_at');
            //     })
            //     ->leftJoin('biaya', function ($q) {
            //         $q->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya')
            //             ->whereNull('biaya.deleted_at');
            //     })
            //     ->leftJoin('biaya_sekolah', function ($q) {
            //         $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
            //             ->whereNull('biaya_sekolah.deleted_at');
            //     })
            //     ->leftJoin('semester', function ($q) {
            //         $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
            //             ->whereNull('semester.deleted_at');
            //     })
            //     ->leftJoin('bulan', function ($q) {
            //         $q->on('detail_biaya.id_bulan', '=', 'bulan.id_bulan')
            //             ->whereNull('bulan.deleted_at');
            //     })
            //     ->leftJoin('pembayaran_biaya', function ($q) {
            //         $q->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya')
            //             ->whereNull('pembayaran_biaya.deleted_at');
            //     })
            //     ->where('detail_biaya.validasi_biaya', 1)
            //     ->where('detail_biaya.id_jenis_detail_biaya', 4)
            //     ->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester])
            //     ->where('tagihan_biaya.id_kelas', $id_kelas)
            //     ->get();

            // $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->sortBy('kode_semester')->values()->all();

            // $data_tagihan_non_bulanan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'biaya.nm_biaya', 'semester.kode_semester', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', 'pembayaran_biaya.tgl_pembayaran', 'pembayaran_biaya.id_pembayaran_biaya', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"), DB::raw('detail_biaya.keterangan_biaya AS title_biaya'))
            //     ->join('siswa', function ($q) {
            //         $q->on('tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
            //             ->whereNull('siswa.deleted_at');
            //     })
            //     ->leftJoin('detail_biaya', function ($q) {
            //         $q->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
            //             ->whereNull('detail_biaya.deleted_at');
            //     })
            //     ->leftJoin('biaya', function ($q) {
            //         $q->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya')
            //             ->whereNull('biaya.deleted_at');
            //     })
            //     ->leftJoin('biaya_sekolah', function ($q) {
            //         $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
            //             ->whereNull('biaya_sekolah.deleted_at');
            //     })
            //     ->leftJoin('semester', function ($q) {
            //         $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
            //             ->whereNull('semester.deleted_at');
            //     })
            //     ->leftJoin('pembayaran_biaya', function ($q) {
            //         $q->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya')
            //             ->whereNull('pembayaran_biaya.deleted_at');
            //     })
            //     ->where('detail_biaya.validasi_biaya', 1)
            //     ->where('detail_biaya.id_jenis_detail_biaya', '<>', 4)
            //     ->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester])
            //     ->where('tagihan_biaya.id_kelas', $id_kelas)
            //     ->get();

            // $data_siswa = Siswa::with('pengguna', 'pengguna.status_pengguna')->whereIn('siswa.id_siswa', $data_tagihan->unique('id_siswa')->pluck('id_siswa')->values()->all())
            //     ->orderBy('nis_siswa')->get();

            // $data_ket_tagihan = $data_tagihan_non_bulanan->unique('title_biaya')->values()->all();









            $query_tagihan = TagihanBiaya::select('tagihan_biaya.id_siswa', 'biaya.nm_biaya', 'semester.kode_semester', 'siswa.nis_siswa', 'tagihan_biaya.id_tagihan_biaya', 'tagihan_biaya.is_tagih', 'tagihan_biaya.is_request', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'bulan.kode_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', 'tagihan_biaya.besar_pembayaran', 'tagihan_biaya.tgl_pelunasan', 'tagihan_biaya.updated_at')
                ->join('siswa', function ($q) {
                    $q->on('tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
                        ->whereNull('siswa.deleted_at');
                })
                ->leftJoin('detail_biaya', function ($q) {
                    $q->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                        ->whereNull('detail_biaya.deleted_at');
                })
                ->leftJoin('biaya', function ($q) {
                    $q->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                        ->whereNull('biaya.deleted_at');
                })
                ->leftJoin('biaya_sekolah', function ($q) {
                    $q->on('biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                        ->whereNull('biaya_sekolah.deleted_at');
                })
                ->leftJoin('semester', function ($q) {
                    $q->on('semester.id_semester', '=', 'biaya_sekolah.id_semester')
                        ->whereNull('semester.deleted_at');
                })
                ->leftJoin('bulan', function ($q) {
                    $q->on('detail_biaya.id_bulan', '=', 'bulan.id_bulan')
                        ->whereNull('bulan.deleted_at');
                })
                ->where('detail_biaya.validasi_biaya', 1)
                ->whereIn('biaya_sekolah.id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester])
                ->where('tagihan_biaya.id_kelas', $id_kelas);

            $clone_query_tagihan = clone $query_tagihan;
            $data_tagihan = $query_tagihan->where('detail_biaya.id_jenis_detail_biaya', 4)->get();

            $clone_query_tagihan->where('detail_biaya.id_jenis_detail_biaya', '<>', 4);
            $data_tagihan_non_bulanan = $clone_query_tagihan->get();

            $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->sortBy('kode_semester')->values()->all();

            $data_siswa = Siswa::with('pengguna', 'pengguna.status_pengguna')->whereIn('siswa.id_siswa', $data_tagihan->unique('id_siswa')->pluck('id_siswa')->values()->all())
                ->orderBy('nis_siswa')->get();

            $data_ket_tagihan = $data_tagihan_non_bulanan->unique('keterangan')->sortByDesc('keterangan');
        } else {
            $data_siswa = array();
            $data_tagihan = array();
            $data_bulan_tagihan = array();

            $data_tagihan_non_bulanan = array();
            $data_ket_tagihan = array();
        }

        return view('keuangan/utility/pembayaran-by-kelas/print-pembayaran-by-kelas', compact('auth_data', 'data_semester', 'data_kelas', 'tahun_akademik_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan', 'data_tagihan_non_bulanan', 'data_ket_tagihan'));
    }
}
