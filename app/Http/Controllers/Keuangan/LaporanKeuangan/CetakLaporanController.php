<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use App\Libraries\Keuangan\LibDataKeuangan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Biaya;
use App\Models\Bulan;
use App\Models\DetailBiaya;
use App\Models\Siswa as Siswa;
use App\Models\Staff as Staff;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;
use App\Models\Realisasi;
use Auth;
use Carbon\Carbon;
use DateTime;
use DB;
use Session;
use Validator;
use Yajra\DataTables\DataTables;

class CetakLaporanController extends BaseController
{
    // Kategori REALISASI didapat dari kategori RAPB => 1: Penerimaan, 2: Pengeluaran
    // Kategori PEMBAYARAN_BIAYA dianggap Penerimaan semua (SPP, uang kegiatan, dll)
    public function viewCetakLaporan(Request $request, $nis_nama_siswa = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $bulan = Bulan::orderBy('id_bulan')->get();

        if(empty(session('setting_print_keuangan'))){
            session(['setting_print_keuangan' => 'all']);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/view-cetak-laporan', compact('auth_data', 'nis_nama_siswa', 'bulan'));
    }

    public function datatablesCetakLaporan(Request $request){
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $startDate = (isset($input->start_date) && !empty($input->start_date)) ? $input->start_date : null;
        $endDate = (isset($input->end_date) && !empty($input->end_date)) ? $input->end_date : null;

        // fetch laporan keuangan
        $dataLaporan = LibDataKeuangan::fetchDataLaporanKeuanganInternal($auth_data, $startDate, $endDate);

        return DataTables::of($dataLaporan['laporan'])
                ->addColumn('tanggal_bayar', function ($item) {
                    return date_format(date_create($item['tanggal']), "d M Y");
                })
                ->addColumn('debit', function ($item) {
                    if($item['tipe'] == 1){
                        return $item['nominal'];
                    } else {
                        return null;
                    }
                })
                ->addColumn('credit', function ($item) {
                    if($item['tipe'] == 2){
                        return $item['nominal'];
                    } else {
                        return null;
                    }
                })
                ->with('total_debit', number_format($dataLaporan['total_debit']))
                ->with('total_kredit', number_format($dataLaporan['total_kredit']))
                ->make(true);
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

    public function printCetakLaporanKas(Request $request, $jenis, $start_date, $end_date)
    {
        // dd(phpinfo());
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
            $data_laporan = LibDataKeuangan::fetchDataLaporanKeuanganInternal($auth_data, $start_date, $end_date);
        } else if($jenis == 'detail-reguler'){
            $judul = 'LAPORAN ARUS KAS - REGULER';
            // fetch laporan keuangan
            $data_laporan = LibDataKeuangan::fetchDataLaporanKeuanganReguler($auth_data, $start_date, $end_date);
        }

        return view('keuangan/laporan-keuangan/cetak-laporan/print-cetak-laporan', compact('auth_data', 'judul', 'data_laporan', 'start_date', 'end_date'));
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
            'jenis' => 'required|in:siswa,tanggal,bulan'
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
            $data_laporan = LibDataKeuangan::fetchDataLaporanPembayaranSiswaPerSiswa($auth_data, $start_date, $end_date);

            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/print-cetak-laporan-pembayaran-siswa-by-siswa', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } 
        elseif($jenis == 'tanggal'){
            $data_laporan = LibDataKeuangan::fetchDataLaporanPembayaranSiswaPerTanggal($auth_data, $start_date, $end_date);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/print-cetak-laporan-pembayaran-siswa-by-tanggal', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
        } 
        elseif ($jenis == 'bulan'){
            $start_year = Carbon::parse($start_date)->format('Y');
            $end_year = Carbon::parse($end_date)->format('Y');
            $data_laporan = LibDataKeuangan::fetchDataLaporanPembayaranSiswaPerBulan($auth_data, $start_year, $end_year);
            
            return view('keuangan/laporan-keuangan/cetak-laporan/pembayaran-siswa/print-cetak-laporan-pembayaran-siswa-by-bulan', compact('auth_data', 'data_laporan', 'start_year', 'end_year'));
        }
    }
    
}
