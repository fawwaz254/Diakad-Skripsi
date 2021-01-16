<?php

namespace App\Http\Controllers\Keuangan\Utility;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

use App\Models\Siswa as Siswa;
use App\Models\Staff as Staff;
use App\Models\Bank as Bank;
use App\Models\BankVia as BankVia;
use App\Models\TagihanBiaya as TagihanBiaya;
use App\Models\PembayaranBiaya as PembayaranBiaya;

use Auth;
use DB;
use Session;
use Validator;

class PembayaranSiswaController extends BaseController
{
    public function viewPembayaranSiswa(Request $request, $nis_nama_siswa = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/utility/pembayaran-siswa/view-pembayaran-siswa', compact('auth_data', 'nis_nama_siswa'));
    }

    public function printPembayaranSiswa(Request $request, $id_pengguna = null, $tgl_pembayaran = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $id_pengguna);
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_pembayaran_siswa = PembayaranBiaya::select('siswa.id_siswa', 'tagihan_biaya.id_tagihan_biaya', 'pembayaran_biaya.id_pembayaran_biaya', 'kelompok_biaya.nm_kelompok_biaya', 's_biaya.tahun_ajaran as tahun_ajaran_biaya', 's_biaya.nm_semester as nm_semester_biaya', 'jalur.nm_jalur', 'biaya.nm_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 's_bayar.tahun_ajaran as tahun_ajaran_bayar', 's_bayar.nm_semester as nm_semester_bayar', 'pengguna.nm_pengguna', 'pengguna.gelar_depan', 'pengguna.gelar_belakang', 'pembayaran_biaya.besar_pembayaran', 'pembayaran_biaya.tgl_pembayaran', 'bank.nm_bank', 'bank_via.nm_bank_via', 'pembayaran_biaya.nomor_transaksi', 'tagihan_biaya.keterangan')
                    ->join('tagihan_biaya', 'tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya')
                    ->join('siswa', 'siswa.id_siswa', '=', 'tagihan_biaya.id_siswa')
                    ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                    ->leftJoin('jenis_detail_biaya', 'jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
                    ->leftJoin('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
                    ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                    ->join('kelompok_biaya', 'kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                    ->join('biaya', 'biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                    ->join('semester AS s_biaya', 's_biaya.id_semester', '=', 'biaya_sekolah.id_semester')
                    ->leftJoin('jalur', 'jalur.id_jalur', '=', 'biaya_sekolah.id_jalur')
                    ->join('semester AS s_bayar', 's_bayar.id_semester', '=', 'pembayaran_biaya.id_semester_bayar')
                    ->leftjoin('staff', 'staff.id_staff', '=', 'pembayaran_biaya.id_staff_bayar')
                    ->leftjoin('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                    ->leftJoin('bank', 'bank.id_bank', '=', 'pembayaran_biaya.id_bank')
                    ->leftJoin('bank_via', 'bank_via.id_bank_via', '=', 'pembayaran_biaya.id_bank_via')
                    ->where('siswa.id_siswa', '=', $siswa->id_siswa)
                    ->where('biaya.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                    ->whereDate('pembayaran_biaya.tgl_pembayaran', $tgl_pembayaran)
                    ->get();

        return view('keuangan/utility/pembayaran-siswa/print-pembayaran-siswa', compact('auth_data', 'siswa', 'semester_aktif', 'tgl_pembayaran', 'data_pembayaran_siswa'));
    }
    
    public function printBelumTerbayarPembayaranSiswa(Request $request, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $id_pengguna);
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_terbayar = PembayaranBiaya::select()
                                ->join('tagihan_biaya', function($join){
                                    $join->on('tagihan_biaya.id_tagihan_biaya', '=', 'pembayaran_biaya.id_tagihan_biaya');
                                    $join->whereNull('tagihan_biaya.deleted_at');
                                })->where('tagihan_biaya.id_siswa', $siswa->id_siswa)->pluck('id_tagihan_biaya')->toArray();
                                // dd($data_terbayar);

        $list_data = TagihanBiaya::select('tagihan_biaya.id_tagihan_biaya', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', 'tagihan_biaya.besar_biaya as besar_pembayaran')
            ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
            ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
            ->join('kelompok_biaya', 'kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
            ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
            ->join('biaya', 'biaya.id_biaya', '=', 'detail_biaya.id_biaya')
            ->leftJoin('jenis_detail_biaya', 'jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
            ->leftJoin('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
            // ->where('tagihan_biaya.is_tagih', '=', 1)
            ->where('tagihan_biaya.is_request', '=', 0)
            ->where('tagihan_biaya.id_siswa', '=', $siswa->id_siswa)
            ->whereNotIn('tagihan_biaya.id_tagihan_biaya', $data_terbayar)
            ->orderBy('bulan.id_bulan', 'asc')
            ->orderBy('detail_biaya.id_jenis_detail_biaya', 'asc')
            // ->orderBy('semester.kode_semester', 'asc')
            ->get();
        // dd($data_terbayar, $list_data);

        return view('keuangan/utility/pembayaran-siswa/print-belum-terbayar-pembayaran-siswa', compact('auth_data', 'siswa', 'semester_aktif', 'list_data'));
    }

    public function actionViewPembayaranSiswa(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
          'nis_nama_siswa' =>'required'
      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/pembayaran-siswa/view-detail/'.$input->nis_nama_siswa
                ];
        }
    }
    public function viewDetailPembayaranSiswa(Request $request, $nis_nama_siswa)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchCariSiswaDetail($auth_data, $nis_nama_siswa);
  
        return view('keuangan/utility/pembayaran-siswa/view-pembayaran-siswa', compact('auth_data', 'nis_nama_siswa'));
    }

    public function datatablesPembayaranSiswa(Request $request, $nis_nama_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::select('siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'status_pengguna.nm_status_pengguna', 'jalur.nm_jalur', DB::raw("(SELECT SUM(besar_biaya) FROM tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL) AS total_tagihan"), DB::raw("(SELECT SUM(denda_biaya) FROM tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL) AS total_denda"), DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya WHERE tagihan_biaya.id_siswa = siswa.id_siswa AND tagihan_biaya.is_tagih = 1 AND tagihan_biaya.deleted_at IS NULL AND pembayaran_biaya.deleted_at IS NULL) AS total_pembayaran"))
          ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
          ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
          ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
          ->join('jalur_siswa', function ($join) {
              $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                                 ->where('jalur_siswa.is_jalur_aktif', '=', 1);
          })
          ->join('jalur', 'jalur_siswa.id_jalur', '=', 'jalur.id_jalur')
          ->where(function ($query) use ($nis_nama_siswa) {
              $query->where('siswa.nis_siswa', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%'.$nis_nama_siswa.'%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%'.$nis_nama_siswa.'%');
          })
          ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
          ->get();
        return Datatables::of($siswa)
                ->addColumn('total_tagihan', function ($item) {
                    return "Rp".number_format($item->total_tagihan + $item->total_denda - $item->total_pembayaran);
                })
                ->addColumn('action', function ($item) use ($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->nis_siswa,
                        'id_asli' => $nis_nama_siswa
                    );
                    return $data;
                })
                ->make(true);
    }

    public function viewDetailSiswaPembayaranSiswa(Request $request, $nis_siswa, $nis_nama_siswa_asli)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);

        return view('keuangan/utility/pembayaran-siswa/view-detail-pembayaran-siswa', compact('auth_data', 'nis_siswa', 'nis_nama_siswa_asli', 'siswa'));
    }

    public function datatablesTagihanPembayaranSiswa(Request $request, $id_pengguna, $nis_nama_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_pengguna', '=', $id_pengguna)->first();
        $id_siswa = $siswa->id_siswa;

        $list_data = TagihanBiaya::select('tagihan_biaya.id_tagihan_biaya', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"))
                                ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                                ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                                ->join('kelompok_biaya', 'kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                                ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
                                ->join('biaya', 'biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                                ->leftJoin('jenis_detail_biaya', 'jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
                                ->leftJoin('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
                                // ->where('tagihan_biaya.is_tagih', '=', 1)
                                ->where('tagihan_biaya.is_request', '=', 0)
                                ->where('tagihan_biaya.id_siswa', '=', $id_siswa)
                                ->orderBy('bulan.id_bulan', 'asc')
                                ->orderBy('detail_biaya.id_jenis_detail_biaya', 'asc')
                                // ->orderBy('semester.kode_semester', 'asc')
                                ->get();

        return Datatables::of($list_data)
                ->editColumn('nm_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_biaya." ".$item->keterangan;
                    }
                })
                ->addColumn('biaya_sekolah', function ($item) {
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran." ".$item->nm_semester.")";
                })
                ->addColumn('jenis_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function ($item) {
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function ($item) {
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function ($item) {
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('sisa_tagihan', function ($item) {
                    return "Rp".number_format($item->besar_biaya + $item->denda_biaya - $item->besar_pembayaran);
                })
                ->addColumn('checkbox', function ($item) {
                    $data = array(
                        'id_tagihan' => $item->id_tagihan_biaya,
                        'sisa_tagihan' => $item->besar_biaya + $item->denda_biaya - $item->besar_pembayaran
                    );
                    return $data;
                })
                ->addColumn('action', function ($item) use ($nis_nama_siswa) {
                    $data = array(
                        'id' => $item->id_tagihan_biaya,
                        'id_asli' => $nis_nama_siswa,
                        'sisa_tagihan' => $item->besar_biaya + $item->denda_biaya - $item->besar_pembayaran
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesRiwayatBayarSiswa(Request $request, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LibSiswa::fetchPembayaranSiswa($auth_data, $id_pengguna);

        return Datatables::of($list_data)
                ->editColumn('nm_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_biaya." ".$item->keterangan;
                    }
                })
                ->addColumn('biaya_sekolah', function ($item) {
                    return $item->nm_kelompok_biaya." (".$item->tahun_ajaran_biaya." ".$item->nm_semester_biaya.")";
                })
                ->addColumn('jenis_biaya', function ($item) {
                    if ($item->id_jenis_detail_biaya == 4) {
                        return $item->nm_jenis_detail_biaya." (".$item->nm_bulan.")";
                    } else {
                        return $item->nm_jenis_detail_biaya;
                    }
                })
                ->addColumn('besar_biaya', function ($item) {
                    return "Rp".number_format($item->besar_biaya);
                })
                ->addColumn('denda_biaya', function ($item) {
                    return "Rp".number_format($item->denda_biaya);
                })
                ->addColumn('besar_pembayaran', function ($item) {
                    return "Rp".number_format($item->besar_pembayaran);
                })
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->addColumn('tgl_pembayaran', function ($item) {
                    return strftime("%d %B %Y", strtotime($item->tgl_pembayaran));
                })
                ->addColumn('semester_bayar', function ($item) {
                    return $item->tahun_ajaran_bayar." ".$item->nm_semester_bayar;
                })
                ->addColumn('nm_bank', function ($item) {
                    if (! empty($item->nm_bank)) {
                        return $item->nm_bank_via." ".$item->nm_bank;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_pembayaran_biaya
                    );
                    return $data;
                })
                ->make(true);
    }

    public function viewDetailTagihanPembayaranSiswa(Request $request, $id_tagihan, $nis_nama_siswa_asli)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tagihan = TagihanBiaya::select('tagihan_biaya.id_tagihan_biaya', 'siswa.nis_siswa', 'detail_biaya.id_detail_biaya', 'biaya_sekolah.id_biaya_sekolah', 'biaya_sekolah.id_kelompok_biaya', 'biaya_sekolah.id_semester', 'kelompok_biaya.nm_kelompok_biaya', 'semester.tahun_ajaran', 'semester.nm_semester', 'biaya.nm_biaya', 'detail_biaya.validasi_biaya', 'detail_biaya.id_jenis_detail_biaya', 'jenis_detail_biaya.nm_jenis_detail_biaya', 'detail_biaya.id_bulan', 'bulan.nm_bulan', 'jalur.nm_jalur', 'tagihan_biaya.besar_biaya', 'tagihan_biaya.denda_biaya', 'tagihan_biaya.keterangan', DB::raw("(SELECT SUM(besar_pembayaran) FROM pembayaran_biaya WHERE pembayaran_biaya.id_tagihan_biaya = tagihan_biaya.id_tagihan_biaya AND pembayaran_biaya.deleted_at IS NULL) AS besar_pembayaran"))
                        ->join('siswa', 'siswa.id_siswa', '=', 'tagihan_biaya.id_siswa')
                        ->join('detail_biaya', 'detail_biaya.id_detail_biaya', '=', 'tagihan_biaya.id_detail_biaya')
                        ->join('biaya_sekolah', 'biaya_sekolah.id_biaya_sekolah', '=', 'detail_biaya.id_biaya_sekolah')
                        ->leftJoin('jalur', 'jalur.id_jalur', '=', 'biaya_sekolah.id_jalur')
                        ->join('kelompok_biaya', 'kelompok_biaya.id_kelompok_biaya', '=', 'biaya_sekolah.id_kelompok_biaya')
                        ->join('semester', 'semester.id_semester', '=', 'biaya_sekolah.id_semester')
                        ->join('biaya', 'biaya.id_biaya', '=', 'detail_biaya.id_biaya')
                        ->leftJoin('jenis_detail_biaya', 'jenis_detail_biaya.id_jenis_detail_biaya', '=', 'detail_biaya.id_jenis_detail_biaya')
                        ->leftJoin('bulan', 'bulan.id_bulan', '=', 'detail_biaya.id_bulan')
                        ->where('tagihan_biaya.id_tagihan_biaya', '=', $id_tagihan)
                        ->first();

        $nis_siswa = $tagihan->nis_siswa;

        if ($tagihan->id_jenis_detail_biaya == 4) {
            $jenis_biaya = $tagihan->nm_jenis_detail_biaya." (".$tagihan->nm_bulan.")";
        } else {
            $jenis_biaya = $tagihan->nm_jenis_detail_biaya;
        }

        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $bank       = Bank::all();
        $bank_via   = BankVia::all();

        return view('keuangan/utility/pembayaran-siswa/view-detail-tagihan-pembayaran-siswa', compact('auth_data', 'nis_siswa', 'nis_nama_siswa_asli', 'tagihan', 'jenis_biaya', 'siswa', 'data_semester', 'bank', 'bank_via'));
    }

    // Action POST
    public function actionPembayaranSiswa(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_tagihan_biaya'      => 'required',
            'id_semester_bayar'     => 'required',
            'besar_pembayaran'      => 'required',
            'besar_pembayaran_lama' => 'required',
            'tgl_pembayaran'        => 'required',
            /*'id_bank'             => 'required',
            'id_bank_via'           => 'required',
            'nomor_transaksi'       => 'required',
            'is_tarik'              => 'required',*/
            'keterangan'            => 'required'

            /* kebutuhan return success
            'nis_siswa'             => 'required',
            'nis_nama_siswa_asli'   => 'required'*/
        ]);
        
        if ($validator->fails() && $mode != 'delete' && $mode != 'lunas') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $tagihanBiaya       = TagihanBiaya::find($input->id_tagihan_biaya);

                $besar_biaya = $tagihanBiaya->besar_biaya + $tagihanBiaya->denda_biaya;

                $besar_pembayaran = $input->besar_pembayaran + $input->besar_pembayaran_lama;

                // cek besar pembayaran yg diinput
                if ($besar_pembayaran > $besar_biaya) {
                    return [
                        'status' => 300,
                        'message' => 'Besar Pembayaran Lebih Besar Dari Tagihan!'
                    ];
                }

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                if ($input->auth_data->pengguna->status_join_table == 1) {
                    // get id_guru
                    $staff = Staff::select('id_staff')
                        ->where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                        ->first();

                    $id_staff_bayar = $staff->id_staff;
                } else {
                    $id_staff_bayar = null;
                }

                $pembayaranBiaya                            = new PembayaranBiaya;
                $pembayaranBiaya->id_pembayaran_biaya       = $id;
                $pembayaranBiaya->id_tagihan_biaya          = $input->id_tagihan_biaya;
                $pembayaranBiaya->id_staff_bayar            = $id_staff_bayar;
                $pembayaranBiaya->id_semester_bayar         = $input->id_semester_bayar;
                $pembayaranBiaya->besar_pembayaran          = $input->besar_pembayaran;
                // convert format date
                $pembayaranBiaya->tgl_pembayaran            = date_format(date_create($input->tgl_pembayaran), "Y-m-d H:i:s");
                if (! empty($input->id_bank)) {
                    $pembayaranBiaya->id_bank                   = $input->id_bank;
                }
                if (! empty($input->id_bank_via)) {
                    $pembayaranBiaya->id_bank_via               = $input->id_bank_via;
                }
                if (! empty($input->nomor_transaksi)) {
                    $pembayaranBiaya->nomor_transaksi           = $input->nomor_transaksi;
                }
                $pembayaranBiaya->keterangan                = $input->keterangan;
                if (! empty($input->is_tarik)) {
                    $pembayaranBiaya->is_tarik                  = $input->is_tarik;
                }
                $pembayaranBiaya->created_by                = $input->auth_data->pengguna->id_pengguna;
                $pembayaranBiaya->save();

                // update is_tagih di tabel tagihan_biaya
                if ($besar_pembayaran == $besar_biaya) {
                    $tagihanBiaya->is_tagih     = 0;
                    $tagihanBiaya->updated_by   = $input->auth_data->pengguna->id_pengguna;
                    $tagihanBiaya->updated_at   = $now;
                    $tagihanBiaya->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'utility/pembayaran-siswa/view-detail-siswa/'.$input->nis_siswa.'/'.$input->nis_nama_siswa_asli,
                    'message' => 'Save Pembayaran successfully'
                ];
            } elseif ($mode == 'lunas') {
                $tagihanBiaya   = TagihanBiaya::find($id);

                $besar_biaya    = $tagihanBiaya->besar_biaya + $tagihanBiaya->denda_biaya;

                $besar_pembayaran_lama = PembayaranBiaya::where('id_tagihan_biaya', '=', $id)
                                            ->sum('besar_pembayaran');

                $besar_pelunasan = $besar_biaya - $besar_pembayaran_lama;

                if ($input->auth_data->pengguna->status_join_table == 1) {
                    // get id_guru
                    $staff = Staff::select('id_staff')
                        ->where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                        ->first();

                    $id_staff_bayar = $staff->id_staff;
                } else {
                    $id_staff_bayar = null;
                }

                $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($input->auth_data);

                $pembayaranBiaya                        = new PembayaranBiaya;
                $pembayaranBiaya->id_pembayaran_biaya   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pembayaranBiaya->id_tagihan_biaya      = $id;
                $pembayaranBiaya->id_staff_bayar        = $id_staff_bayar;
                $pembayaranBiaya->id_semester_bayar     = $semester_aktif->id_semester;
                $pembayaranBiaya->besar_pembayaran      = $besar_pelunasan;
                // convert format date
                $pembayaranBiaya->tgl_pembayaran        = (!empty($input->tgl_pembayaran))? $input->tgl_pembayaran : $now;
                $pembayaranBiaya->keterangan            = "Langsung Lunas";
                $pembayaranBiaya->created_by            = $input->auth_data->pengguna->id_pengguna;
                $pembayaranBiaya->save();

                $tagihanBiaya->is_tagih     = 0;
                $tagihanBiaya->updated_by   = $input->auth_data->pengguna->id_pengguna;
                $tagihanBiaya->updated_at   = $now;
                $tagihanBiaya->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'message' => 'Save Pelunasan successfully',
                    'data' => [
                        'id' => $pembayaranBiaya->id_pembayaran_biaya,
                        'date' => date_format(date_create($pembayaranBiaya->tgl_pembayaran), 'd/m'),
                        'month' => date_format(date_create($pembayaranBiaya->tgl_pembayaran), 'n')
                    ]
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $pembayaranBiaya                = PembayaranBiaya::find($id);

                $tagihanBiaya                   = TagihanBiaya::find($pembayaranBiaya->id_tagihan_biaya);
                $tagihanBiaya->is_tagih         = 1;
                $tagihanBiaya->updated_by       = $input->auth_data->pengguna->id_pengguna;
                $tagihanBiaya->updated_at       = $now;
                $tagihanBiaya->save();

                $pembayaranBiaya->forceDelete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Pembayaran Siswa successfully'
                ];
            }
        }
    }

    public function actionDeleteTagihanSiswa(Request $request, $id){
        // make object to find id
        if($tagihanBiaya                   = TagihanBiaya::find($id)){
            $tagihanBiaya->is_tagih         = 0;
            $tagihanBiaya->deleted_by       = $input->auth_data->pengguna->id_pengguna;
            $tagihanBiaya->save();
    
            $tagihanBiaya->delete();
        }

        return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Delete Tagihan Siswa successfully'
        ];
    }

    public function actionPembayaranSiswaMassal(Request $request){
        $input = (object) $request->input();

        $totalNilai = collect($request->data_pembayaran)->sum('nilai');
        $request['total_pembayaran'] = $totalNilai;
        $request['min_total_pembayaran'] = 0;
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'data_pembayaran' => 'required',
            'data_pembayaran.*.id'      => 'required',
            'data_pembayaran.*.nilai'      => 'required',
        ]);
        
        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => "Harap pilih tagihan yang ingin dibayar"
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $dataPembayaran = $input->data_pembayaran;

            // looping save
            foreach($dataPembayaran as $bayar){
                $tagihanBiaya   = TagihanBiaya::find($bayar['id']);
    
                $besar_biaya    = $tagihanBiaya->besar_biaya + $tagihanBiaya->denda_biaya;
    
                $besar_pembayaran_lama = PembayaranBiaya::where('id_tagihan_biaya', '=', $bayar['id'])
                                            ->sum('besar_pembayaran');
    
                $besar_pelunasan = $besar_biaya - $besar_pembayaran_lama;
    
                if ($input->auth_data->pengguna->status_join_table == 1) {
                    // get id_guru
                    $staff = Staff::select('id_staff')
                        ->where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                        ->first();
    
                    $id_staff_bayar = $staff->id_staff;
                } else {
                    $id_staff_bayar = null;
                }
    
                $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($input->auth_data);
    
                $pembayaranBiaya                        = new PembayaranBiaya;
                $pembayaranBiaya->id_pembayaran_biaya   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pembayaranBiaya->id_tagihan_biaya      = $bayar['id'];
                $pembayaranBiaya->id_staff_bayar        = $id_staff_bayar;
                $pembayaranBiaya->id_semester_bayar     = $semester_aktif->id_semester;
                $pembayaranBiaya->besar_pembayaran      = $besar_pelunasan;
                // convert format date
                $pembayaranBiaya->tgl_pembayaran        = (!empty($input->tgl_pembayaran))? $input->tgl_pembayaran : $now;
                $pembayaranBiaya->keterangan            = "Langsung Lunas (Pembayaran Massal)";
                $pembayaranBiaya->created_by            = $input->auth_data->pengguna->id_pengguna;
                $pembayaranBiaya->save();
    
                $tagihanBiaya->is_tagih     = 0;
                $tagihanBiaya->updated_by   = $input->auth_data->pengguna->id_pengguna;
                $tagihanBiaya->updated_at   = $now;
                $tagihanBiaya->save();
            }

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Pembayaran Siswa successfully'
            ];
        }
    }
}
