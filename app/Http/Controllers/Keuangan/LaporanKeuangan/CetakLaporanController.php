<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use App\Libraries\Keuangan\LibDataKeuangan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

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

        return view('keuangan/laporan-keuangan/cetak-laporan/view-cetak-laporan', compact('auth_data', 'nis_nama_siswa'));
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

    public function printCetakLaporan(Request $request, $start_date, $end_date)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // fetch laporan keuangan
        $data_laporan = LibDataKeuangan::fetchDataLaporanKeuanganInternal($auth_data, $start_date, $end_date);

        return view('keuangan/laporan-keuangan/cetak-laporan/print-cetak-laporan', compact('auth_data', 'data_laporan', 'start_date', 'end_date'));
    }
    
}
