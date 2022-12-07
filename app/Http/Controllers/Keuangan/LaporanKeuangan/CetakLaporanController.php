<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use App\Libraries\Keuangan\LibCetakKeuangan;
use App\Models\Bulan;
use App\Models\Semester;
use App\Models\TutupBukuBulananKas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class CetakLaporanController extends BaseController
{
    // Kategori REALISASI didapat dari kategori RAPB => 1: Penerimaan, 2: Pengeluaran
    // Kategori PEMBAYARAN_BIAYA dianggap Penerimaan semua (SPP, uang kegiatan, dll)
    public function viewCetakLaporan(Request $request, $nis_nama_siswa = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $bulan = Bulan::orderBy('id_bulan')->get();

        if (empty(session('setting_print_keuangan'))) {
            session(['setting_print_keuangan' => 'all']);
        }

        if (empty(session('setting_print_keuangan2'))) {
            session(['setting_print_keuangan2' => 'semua']);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/view-cetak-laporan', compact('auth_data', 'nis_nama_siswa', 'bulan'));
    }

    public function actionSetSettingCetak(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'print_setting' => 'required|in:all,self',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        if (empty(session('setting_print_keuangan'))) {
            session(['setting_print_keuangan' => 'all']);
        } else {
            session(['setting_print_keuangan' => $input->print_setting]);
        }
        return $input->print_setting;
    }

    public function actionSetSettingCetak2(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'print_setting' => 'required|in:semua,spp,lain',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        if (empty(session('setting_print_keuangan2'))) {
            session(['setting_print_keuangan2' => 'semua']);
        } else {
            session(['setting_print_keuangan2' => $input->print_setting]);
        }
        return $input->print_setting;
    }

    public function printCetakLaporanPengeluaran(Request $request, $jenis, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis,
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:kategori',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        }

        if ($jenis == 'kategori') {
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanPengeluaranByKategori($auth_data, $start_date, $end_date);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/pengeluaran/rekap-by-kategori', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
    }

    public function printCetakLaporanKas(Request $request, $jenis, $start_date, $end_date)
    {
        set_time_limit(1800);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $sekolah = $auth_data->sekolah_data->nm_sekolah;

        $month_before = Carbon::parse($start_date)->subMonth()->format('m');

        $sc_bulan = Carbon::createFromFormat('Y-m-d', $start_date);
        $id_bulan = $sc_bulan->month;
        $id_bulan_lalu = $sc_bulan->subMonth()->month;

        $sc_tahun = Carbon::createFromFormat('Y-m-d', $start_date);
        $tahun = $sc_tahun->year;
        $tahun_lalu = $sc_tahun->subYear()->year;

        if ($id_bulan < 7) {
            $tahun_semester = $tahun - 1;
        } else {
            $tahun_semester = $tahun;
        }

        $semester_mulai = Semester::where('kode_semester', $tahun_semester . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_semester . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $saldo_before = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu])->first();

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis,
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:detail-reguler,detail-internal',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        }

        if ($jenis == 'detail-internal') {
            $judul = 'LAPORAN ARUS KAS - INTERNAL';
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanKasInternal($auth_data, $start_date, $end_date);
        } else if ($jenis == 'detail-reguler') {
            $judul = 'LAPORAN ARUS KAS - REGULER';
            // fetch laporan keuangan

            $data_laporan = LibCetakKeuangan::fetchLaporanKasReguler($auth_data, $start_date, $end_date);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/pemasukan-pengeluaran/rekap-detail', compact('auth_data', 'judul', 'data_laporan', 'start_date', 'end_date', 'sekolah', 'saldo_before'));
    }

    public function printCetakLaporanPembayaranSiswa(Request $request, $jenis, $start_date, $end_date)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis,
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:siswa,siswa-online,tanggal,bulan,tingkat,kelas,kategori',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        }

        // fetch laporan keuangan
        if ($jenis == 'siswa') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerSiswa($auth_data, $start_date, $end_date);
            // dd($data_laporan);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-siswa', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'siswa-online') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerSiswaOnline($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-siswa-online', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'kelas') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerKelas($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-kelas', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'tingkat') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTingkat($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-tingkat', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'tanggal') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTanggal($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-tanggal', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'bulan') {
            $start_year = Carbon::parse($start_date)->format('Y');
            $end_year = Carbon::parse($end_date)->format('Y');
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerBulan($auth_data, $start_year, $end_year);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-bulan', compact('auth_data', 'data_laporan', 'start_year', 'end_year'));
        } elseif ($jenis == 'kategori') {
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerKategori($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-kategori', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        }
    }

    public function printCetakLaporanBulanan(Request $request, $jenis, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis,
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:full,harian',
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        }

        if ($jenis == 'full') {
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanBulananFull($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pemasukan-pengeluaran/laporan-bulanan-full', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } else if ($jenis == 'harian') {
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanBulananPerKategori($auth_data, $start_date, $end_date);
            return view('keuangan/laporan-keuangan/cetak-laporan/pemasukan-pengeluaran/laporan-bulanan-per-kategori', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        }
    }
}
