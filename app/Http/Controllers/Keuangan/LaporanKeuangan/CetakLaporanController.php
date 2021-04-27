<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Libraries\Keuangan\LibCetakKeuangan;
use App\Models\Bulan;

use Carbon\Carbon;
use Validator;

class CetakLaporanController extends BaseController
{
    // Kategori REALISASI didapat dari kategori RAPB => 1: Penerimaan, 2: Pengeluaran
    // Kategori PEMBAYARAN_BIAYA dianggap Penerimaan semua (SPP, uang kegiatan, dll)
    public function viewCetakLaporan(Request $request, $nis_nama_siswa = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $bulan = Bulan::orderBy('id_bulan')->get();

        if(empty(session('setting_print_keuangan'))){
            session(['setting_print_keuangan' => 'all']);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/view-cetak-laporan', compact('auth_data', 'nis_nama_siswa', 'bulan'));
    }

    public function actionSetSettingCetak(Request $request){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'print_setting' => 'required|in:all,self',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->first();
        }

        if(empty(session('setting_print_keuangan'))){
            session(['setting_print_keuangan' => 'all']);
        }else{
            session(['setting_print_keuangan' => $input->print_setting]);
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
            'jenis' => $jenis
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:kategori'
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if($jenis == 'kategori'){
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanPengeluaranByKategori($auth_data, $start_date, $end_date);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/pengeluaran/rekap-by-kategori', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
    }

    public function printCetakLaporanKas(Request $request, $jenis, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:detail-reguler,detail-internal'
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        if($jenis == 'detail-internal'){
            $judul = 'LAPORAN ARUS KAS - INTERNAL';
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanKasInternal($auth_data, $start_date, $end_date);
        } else if($jenis == 'detail-reguler'){
            $judul = 'LAPORAN ARUS KAS - REGULER';
            // fetch laporan keuangan
            $data_laporan = LibCetakKeuangan::fetchLaporanKasReguler($auth_data, $start_date, $end_date);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/pemasukan-pengeluaran/rekap-detail', compact('auth_data', 'judul', 'data_laporan', 'start_date', 'end_date'));
    }
    
    public function printCetakLaporanPembayaranSiswa(Request $request, $jenis, $start_date, $end_date)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make([
            'start_date' => $start_date,
            'end_date' => $end_date,
            'jenis' => $jenis
        ], [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'jenis' => 'required|in:siswa,tanggal,bulan,tingkat,kelas'
        ], [
            'end_date.after_or_equal' => 'Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal',
            'jenis.in' => 'Tidak valid'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        // fetch laporan keuangan
        if($jenis == 'siswa'){            
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerSiswa($auth_data, $start_date, $end_date);

            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-siswa', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif($jenis == 'kelas'){
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerKelas($auth_data, $start_date, $end_date);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-kelas', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif($jenis == 'tingkat'){
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTingkat($auth_data, $start_date, $end_date);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-tingkat', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif($jenis == 'tanggal'){
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTanggal($auth_data, $start_date, $end_date);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-tanggal', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } elseif ($jenis == 'bulan'){
            $start_year = Carbon::parse($start_date)->format('Y');
            $end_year = Carbon::parse($end_date)->format('Y');
            $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerBulan($auth_data, $start_year, $end_year);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/rekap-by-bulan', compact('auth_data', 'data_laporan', 'start_year', 'end_year'));
        }
    }
    
}
