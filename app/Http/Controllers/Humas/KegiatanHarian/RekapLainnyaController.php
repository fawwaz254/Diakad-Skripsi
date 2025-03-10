<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use DB;
use Auth;

use Session;
use Validator;
use Carbon\Carbon;
use App\Models\Bulan;
use App\Models\Kelas;
use App\Models\Pengguna;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

use App\Models\KegiatanHarian;
use App\Models\PengisianJawaban;
use Yajra\Datatables\Datatables;

use App\Http\Controllers\Controller;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;
use App\Models\PengisianKegiatanHarian;
use App\Models\KegiatanHarianPertanyaan;


class RekapLainnyaController extends Controller
{
    public function viewListKegiatan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('humas/kegiatan-harian/rekap-lainnya/view-list-rekap-lainnya', compact('auth_data'));
    }

    public function datatablesListKegiatan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = KegiatanHarian::with('pengisisan_kegiatan_harian')->where('nm_kegiatan_harian', '!=', 'Monitoring Kesehatan COV-19');
        return Datatables::of($list_data)
            ->editColumn('is_aktif', function ($item) {
                return $item->is_aktif_to_text();
            })
            ->addColumn('jumlah', function ($item) {
                return $item->pengisisan_kegiatan_harian->count();
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_harian
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesDetail(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = PengisianKegiatanHarian::with('pengguna_pengisi');

        if (!empty($input->date)) {
            $list_data = $list_data->whereDate('pengisian_kegiatan_harian.tgl_pengisian', $input->date);
        }
        if (!empty($input->status)) {
            $list_data = $list_data->where('pengisian_kegiatan_harian.status_pengisian', $input->status);
        }

        if (!empty($input->id_kelas)) {
            $list_data = $list_data->select(
                'pengisian_kegiatan_harian.id_pengisian_kegiatan_harian',
                'pengisian_kegiatan_harian.id_pengguna_pengisi',
                'pengisian_kegiatan_harian.id_kegiatan_harian',
                'pengisian_kegiatan_harian.status_join_table',
                'pengisian_kegiatan_harian.tgl_pengisian',
                'pengisian_kegiatan_harian.status_pengisian',
                'pengisian_kegiatan_harian.warna_keadaan',
                'pengisian_kegiatan_harian.created_at',
                'pengisian_kegiatan_harian.updated_at'
            )->leftJoin('siswa', function ($q) {
                $q->on('siswa.id_pengguna', '=', 'pengisian_kegiatan_harian.id_pengguna_pengisi')
                    ->whereNull('siswa.deleted_at');
            })
                ->where('pengisian_kegiatan_harian.status_join_table', 3)
                ->whereNull('pengisian_kegiatan_harian.id_kegiatan_harian')
                ->where('id_kelas', $input->id_kelas);
        } else if (!empty($input->is_tendik_guru)) {
            $list_data = $list_data->whereIn('status_join_table', [1, 2])->whereNull('id_kegiatan_harian');
        } else if (!empty($input->pengguna)) {
            $list_data = $list_data->where('id_pengguna_pengisi', $input->pengguna);
        } else {
            $list_data = $list_data->where('id_pengguna_pengisi', $auth_data->pengguna->id_pengguna)->whereNull('id_kegiatan_harian');
        }

        return Datatables::of($list_data)
            ->editColumn('pengguna_pengisi.nm_pengguna', function ($item) {
                return $item->pengguna_pengisi->fullname();
            })
            ->editColumn('tgl_pengisian', function ($item) {
                return date_format(date_create($item->tgl_pengisian), 'd M Y');
            })
            ->editColumn('created_at', function ($item) {
                return date_format(date_create($item->created_at), 'd M Y H:i') . ' WIB';
            })
            ->editColumn('status', function ($item) {
                $data = [
                    'status' => $item->status_to_text(),
                    'warna_keadaan' => $item->warna_keadaan
                ];
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pengisian_kegiatan_harian
                );
                return $data;
            })
            ->make(true);
    }

    public function actionFormLainnya(Request $request, $mode)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($mode == 'add') {
            // 
        } elseif ($mode == 'delete') {
            $pengisian_kegiatan_harian  = PengisianKegiatanHarian::where('id_pengisian_kegiatan_harian', $input->id_pengisian_kegiatan_harian)->first();
            $pengisian_jawaban          = PengisianJawaban::where('id_pengisian_kegiatan_harian', $input->id_pengisian_kegiatan_harian)->first();

            if ($pengisian_kegiatan_harian && $pengisian_jawaban) {
                try {
                    $pengisian_kegiatan_harian->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $pengisian_kegiatan_harian->save();

                    $pengisian_jawaban->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $pengisian_jawaban->save();

                    $pengisian_kegiatan_harian->delete();
                    $pengisian_jawaban->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Successfully'
                    ];
                } catch (\Throwable $th) {
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Failed'
                    ];
                }
            } else {
                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Data Tidak Ditemukan'
                ];
            }
        }
    }


    public function viewRekapKegiatanGuruTendik(Request $request, $id_kegiatan_harian, $id_bulan = null, $tahun = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::today();
        if (empty($id_bulan)) {
            $id_bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        $start_month = Carbon::create($tahun, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($id_bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereIn('status_join_table', [1, 2])->orderBy('nm_pengguna')->get();
        $data_pengisian = PengisianKegiatanHarian::where('id_kegiatan_harian', $id_kegiatan_harian)->whereMonth('tgl_pengisian', $id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();
        return view('humas/kegiatan-harian/rekap-lainnya/view-rekap-lainnya-guru-tendik', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun', 'id_kegiatan_harian'));
    }

    public function viewRekapKegiatanSiswa(Request $request, $id_kegiatan_harian, $bulan = null, $tahun = null, $kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $now = Carbon::today();
        if (empty($bulan)) {
            $bulan = $now->month;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }

        if (empty($kelas)) {
            $getKelas = Kelas::first();
            $kelas = $getKelas->id_kelas;
        }

        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $allKelas = Kelas::where('is_aktif', 1)->get();
        // dd($kelas);

        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereHas('siswa', function ($q) use ($kelas) {
            $q->where('id_kelas', $kelas);
        })->orderBy('nm_pengguna')->get();
        // dd($data_pengguna);
        $data_pengisian = PengisianKegiatanHarian::where('id_kegiatan_harian', $id_kegiatan_harian)->whereMonth('tgl_pengisian', $bulan->id_bulan)->whereYear('tgl_pengisian', $tahun)->whereIn('id_pengguna_pengisi', $data_pengguna->pluck('id_pengguna'))->get();
        return view('humas/kegiatan-harian/rekap-lainnya/view-rekap-lainnya-siswa', compact('auth_data', 'dates', 'data_bulan', 'bulan', 'data_pengguna', 'data_pengisian', 'tahun', 'allKelas', 'kelas', 'id_kegiatan_harian'));
    }

    public function viewRekapDetail(Request $request, $id_pengguna = '-', $date)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $pengguna = Pengguna::find($id_pengguna);

        return view('humas/kegiatan-harian/rekap-lainnya/view-detail-rekap-lainnya', compact('auth_data', 'pengguna', 'date'));
    }

    public function viewFormDetail(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $pengisian_kegiatan_harian = PengisianKegiatanHarian::with('pengguna_pengisi')->where('id_pengisian_kegiatan_harian', $id)->first();

        $data_pengisian_jawaban = PengisianJawaban::with('pertanyaan', 'jawaban')->where('id_pengisian_kegiatan_harian', $id)->get();

        return view('tendik/kegiatan-harian/form-lainnya/view-detail-form-lainnya', compact('auth_data', 'pengisian_kegiatan_harian', 'data_pengisian_jawaban'));
    }
}
