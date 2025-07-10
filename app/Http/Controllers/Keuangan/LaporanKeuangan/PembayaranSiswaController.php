<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PembayaranBiaya;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;

use Auth;
use Illuminate\Support\Facades\DB;

class PembayaranSiswaController extends BaseController
{
    public function viewPembayaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('keuangan/laporan-keuangan/pembayaran-siswa/view-pembayaran-siswa', compact('auth_data'));
    }

    public function datatablesPembayaranSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna')->with(['tagihan_biaya.detail_biaya' => function ($q) {
            $q->with('biaya');
        }]);

        if (!empty($input->start_date) && !empty($input->end_date)) {
            $list_data = $list_data->whereBetween('tgl_pembayaran', [$input->start_date . ' 00:00:00', $input->end_date . ' 23:59:59']);
        }

        $temp_list_data = $list_data->get();
        $total = $temp_list_data->sum('besar_pembayaran');

        return Datatables::of($list_data)
            ->addColumn('tanggal_bayar', function ($item) {
                return date_format(date_create($item->tgl_pembayaran), "d M Y H:i") . ' WIB';
            })
            ->addColumn('keterangan_bayar', function ($item) {
                if (!empty($item->tagihan_biaya->detail_biaya->id_bulan)) {
                    return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' bulan ' . Carbon::createFromFormat('m', $item->tagihan_biaya->detail_biaya->id_bulan)->format('F');
                } else {
                    return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' ' . $item->tagihan_biaya->keterangan;
                }
            })
            ->with('total', number_format($total))
            ->make(true);
    }

    public function printSimplePembayaranSiswa(Request $request, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = PembayaranBiaya::select(
            'siswa.id_siswa',
            'siswa.nis_siswa',
            'siswa.nisn_siswa',
            'kelas.nm_kelas',
            'pengguna.nm_pengguna',
            DB::raw('DATE_FORMAT(pembayaran_biaya.tgl_pembayaran, "%d %b %Y") as tgl_pembayaran'),
            DB::raw('SUM(pembayaran_biaya.besar_pembayaran) as total_pembayaran')
        )
            ->join('tagihan_biaya', function ($join) {
                $join->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya');
                $join->whereNull('tagihan_biaya.deleted_at');
            })
            ->join('siswa', function ($join) {
                $join->on('siswa.id_siswa', '=', 'tagihan_biaya.id_siswa');
                $join->whereNull('siswa.deleted_at');
            })
            ->join('kelas', function ($join) {
                $join->on('kelas.id_kelas', '=', 'siswa.id_kelas');
                $join->whereNull('kelas.deleted_at');
            })
            ->join('pengguna', function ($join) {
                $join->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna');
                $join->whereNull('pengguna.deleted_at');
            })
            ->join('detail_biaya', function ($join) {
                $join->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya');
                $join->whereNull('detail_biaya.deleted_at');
            })
            ->join('biaya', function ($join) {
                $join->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya');
                $join->whereNull('biaya.deleted_at');
            });

        if (!empty($start_date) && !empty($end_date)) {
            $list_data = $list_data->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }
        $data_pembayaran = $list_data->groupBy('id_siswa', 'nis_siswa', 'nisn_siswa', 'nm_pengguna', 'kelas.nm_kelas', DB::raw('DATE_FORMAT(pembayaran_biaya.tgl_pembayaran, "%d %b %Y")'))->get();

        return view('keuangan/laporan-keuangan/pembayaran-siswa/print-simple-pembayaran-siswa', compact('auth_data', 'data_pembayaran', 'start_date', 'end_date'));
    }

    public function printDetailPembayaranSiswa(Request $request, $start_date, $end_date)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = PembayaranBiaya::select(
            'siswa.id_siswa',
            'siswa.nis_siswa',
            'siswa.nisn_siswa',
            'kelas.nm_kelas',
            'pengguna.nm_pengguna',
            'pembayaran_biaya.tgl_pembayaran',
            'pembayaran_biaya.besar_pembayaran',
            'tagihan_biaya.keterangan',
            'detail_biaya.id_jenis_detail_biaya',
            'detail_biaya.id_bulan',
            'biaya.nm_biaya'
        )
            ->join('tagihan_biaya', function ($join) {
                $join->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya');
                $join->whereNull('tagihan_biaya.deleted_at');
            })
            ->join('siswa', function ($join) {
                $join->on('siswa.id_siswa', '=', 'tagihan_biaya.id_siswa');
                $join->whereNull('siswa.deleted_at');
            })
            ->join('kelas', function ($join) {
                $join->on('kelas.id_kelas', '=', 'siswa.id_kelas');
                $join->whereNull('kelas.deleted_at');
            })
            ->join('pengguna', function ($join) {
                $join->on('pengguna.id_pengguna', '=', 'siswa.id_pengguna');
                $join->whereNull('pengguna.deleted_at');
            })
            ->join('detail_biaya', function ($join) {
                $join->on('detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya');
                $join->whereNull('detail_biaya.deleted_at');
            })
            ->join('biaya', function ($join) {
                $join->on('biaya.id_biaya', '=', 'detail_biaya.id_biaya');
                $join->whereNull('biaya.deleted_at');
            });

        if (!empty($start_date) && !empty($end_date)) {
            $list_data = $list_data->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        $data_pembayaran = $list_data->get()->groupBy('id_siswa');

        try {
            $total_pembayaran = $list_data->sum('besar_pembayaran');
        } catch (\Throwable $th) {
            $total_pembayaran = null;
        }


        return view('keuangan/laporan-keuangan/pembayaran-siswa/print-detail-pembayaran-siswa', compact('auth_data', 'data_pembayaran', 'start_date', 'end_date', 'total_pembayaran'));
    }
}
