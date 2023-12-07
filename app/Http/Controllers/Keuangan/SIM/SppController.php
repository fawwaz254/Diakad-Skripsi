<?php

namespace App\Http\Controllers\Keuangan\SIM;

use App\Imports\DataImportExcel;
use App\Libraries\Keuangan\LibCetakKeuangan;
use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibKelas;
use App\Models\Biaya;
use App\Models\BiayaSekolah;
use App\Models\Bulan;
use App\Models\DetailBiaya;
use App\Models\Guru;
use App\Models\JenisDetailBiaya;
use App\Models\Kelas;
use App\Models\KelompokBiaya;
use App\Models\KelompokBiayaInternal;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTunggakan;
use App\Models\Rapb;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\Staff;
use App\Models\SubkategoriRapb;
use App\Models\TagihanBiaya;
use App\Models\TunggakanAlumni;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuBulananKas;
use App\Models\TutupBukuTahunanBiaya;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use Yajra\Datatables\Datatables;

class SppController extends BaseController
{
    public function viewMenuSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('keuangan/sim/spp/view-menu-spp', compact('auth_data'));
    }

    public function viewMenuInput(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
        })->get();

        if (!empty($id)) {
            $realisasi = Realisasi::where('id_realisasi', $id)->first();
        } else {
            $realisasi = null;
        }

        return view('keuangan/sim/spp/view-menu-input-penerimaan', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_subkategori', 'realisasi'));
    }

    public function viewMenuEditSetting(Request $request, $thn_akademik_semester, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        $tahun_akademik_semester = $thn_akademik_semester;

        $data_kelompok_biaya = KelompokBiaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('status_kelompok_biaya', 1)
            ->orderBy('status_kelompok_biaya', 'asc')
            ->orderBy('nm_kelompok_biaya', 'asc')
            ->get();

        $data_kelompok_biaya_internal = KelompokBiayaInternal::where('is_aktif', 1)->orderBy('nm_kelompok_biaya_internal', 'asc')
            ->get();

        $kelas = Kelas::find($id);

        return view('keuangan/sim/spp/view-menu-edit-setting', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelompok_biaya', 'data_kelompok_biaya_internal', 'kelas'));
    }

    public function viewMenuEditSettingNonSpp(Request $request, $thn_akademik_semester, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);
        $tahun_akademik_semester = $thn_akademik_semester;

        $data_kelompok_biaya = KelompokBiaya::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('status_kelompok_biaya', 1)
            ->orderBy('status_kelompok_biaya', 'asc')
            ->orderBy('nm_kelompok_biaya', 'asc')
            ->get();

        $data_kelompok_biaya_internal = KelompokBiayaInternal::where('is_aktif', 1)->orderBy('nm_kelompok_biaya_internal', 'asc')
            ->get();

        $kelas = Kelas::find($id);

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->orderBy('nm_biaya', 'asc')->get();

        $data_jenis_detail_biaya = JenisDetailBiaya::get();

        return view('keuangan/sim/spp/view-menu-edit-setting-non-spp', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelompok_biaya', 'data_kelompok_biaya_internal', 'kelas', 'data_biaya', 'data_jenis_detail_biaya'));
    }

    public function viewMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('keuangan/sim/spp/view-menu-cari', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelas'));
    }

    public function viewMenuUpload(Request $request)
    {
        return view('keuangan/sim/spp/view-menu-upload');
    }

    public function downloadContohUploadKeuangan(Request $request)
    {
        $file = public_path() . "/excel/ContohUploadPembayaran.xls";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohUploadPembayaran.xls', $headers);
    }

    public function downloadContohUploadKeuanganNonSpp(Request $request)
    {
        $file = public_path() . "/excel/ContohUploadPembayaranNonSpp.xls";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohUploadPembayaranNonSpp.xls', $headers);
    }

    public function actionMenuUpload(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        if ($request->hasFile('file-excel')) {

            $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
            $data = $data[0]; // Sheet 1
            if (count($data)) {
                if (isset($data[0]['keterangan'])) {
                    DB::beginTransaction();
                    try {
                        foreach ($data as $key => $item) {
                            $item = (object) $item;

                            if (!empty($item->nis)) {
                                $siswa = Siswa::where('nis_siswa', $item->nis)->first();

                                if (!$siswa) {
                                    return [
                                        'status' => 300, // FAILED
                                        'message' => "Nis dengan nomor " . $item->nis . ' tidak ditemukan didalam sistem',
                                    ];
                                } else {
                                    $semester = Semester::where('kode_semester', $item->kode_semester)->first();
                                    $tanggal_bayar =  Carbon::parse($item->tanggal)->format('Y-m-d H:i:s');
                                    $keterangan = $item->keterangan;
                                    $tahun_ajaran = $item->tahun_ajaran;

                                    $tagihan_siswa = TagihanBiaya::where('is_tagih', 1)->where('id_siswa', $siswa->id_siswa)
                                        ->whereHas('detail_biaya', function ($q) use ($keterangan) {
                                            $q->where('keterangan_biaya', $keterangan)->where('id_jenis_detail_biaya', '!=', 4);
                                        })
                                        ->whereHas('detail_biaya.biaya_sekolah.semester', function ($q) use ($tahun_ajaran) {
                                            $q->where('tahun_ajaran', $tahun_ajaran);
                                        })
                                        ->first();

                                    if ($tagihan_siswa) {
                                        $pembayaran_biaya = new PembayaranBiaya;
                                        $pembayaran_biaya->id_pembayaran_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                        $pembayaran_biaya->id_tagihan_biaya = $tagihan_siswa->id_tagihan_biaya;
                                        $pembayaran_biaya->id_staff_bayar = $input->auth_data->pengguna->id_pengguna;
                                        $pembayaran_biaya->id_semester_bayar = $semester->id_semester;
                                        $pembayaran_biaya->besar_pembayaran = $tagihan_siswa->besar_biaya;
                                        $pembayaran_biaya->tgl_pembayaran = $tanggal_bayar;
                                        $pembayaran_biaya->keterangan = "Langsung Lunas";
                                        $pembayaran_biaya->created_by = $input->auth_data->pengguna->id_pengguna;
                                        $pembayaran_biaya->save();

                                        $tagihan_siswa->besar_pembayaran = $tagihan_siswa->besar_pembayaran + $pembayaran_biaya->besar_pembayaran;
                                        $tagihan_siswa->tgl_pelunasan = $pembayaran_biaya->tgl_pembayaran;
                                        $tagihan_siswa->is_tagih = 0;
                                        $tagihan_siswa->updated_by = $input->auth_data->pengguna->id_pengguna;
                                        $tagihan_siswa->save();
                                    }
                                }
                            }
                        }

                        DB::commit();
                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'sim/spp/upload-pembayaran',
                            'message' => 'Upload Pembayaran Successfully',
                        ];
                    } catch (\Exception $e) {
                        DB::rollback();
                        // something went wrong
                        return [
                            'status' => 203, // GAGAL
                            'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                        ];
                    }
                } else {
                    DB::beginTransaction();

                    $listNis = collect($data)->pluck('nis')->unique();
                    $listSiswa = Siswa::whereIn('nis_siswa', $listNis)->get();
                    $listSemester = Semester::get();
                    $listIdSiswa = $listSiswa->pluck('id_siswa');
                    $listTagihanSiswa = TagihanBiaya::whereIn('id_siswa', $listIdSiswa)->whereHas('detail_biaya', function ($q) {
                        $q->where('id_jenis_detail_biaya', 4);
                    })->whereHas('detail_biaya.biaya_sekolah')->get();

                    try {
                        foreach ($data as $key => $item) {
                            $item = (object) $item;

                            if (!empty($item->nis)) {
                                $siswa = $listSiswa->where('nis_siswa', $item->nis)->first();

                                if (!$siswa) {
                                    return [
                                        'status' => 300, // FAILED
                                        'message' => "Nis dengan nomor " . $item->nis . ' tidak ditemukan didalam sistem',
                                    ];
                                } else {
                                    $semester = $listSemester->where('kode_semester', $item->kode_semester)->first();
                                    // $semester = Semester::where('tahun_ajaran', $item->tahun_ajaran)->where('nm_semester', strtoupper($item->nm_semester))->first();

                                    $tanggal_bayar =  Carbon::parse($item->tanggal)->format('Y-m-d H:i:s');
                                    $id_bulan = $item->id_bulan;

                                    $tagihan_siswa = $listTagihanSiswa->where('id_siswa', $siswa->id_siswa)
                                        ->where('detail_biaya.id_bulan', $id_bulan)
                                        ->where('detail_biaya.biaya_sekolah.id_semester', $semester->id_semester)->first();

                                    // $tagihan_siswa = $listTagihanSiswa->where('id_siswa', $siswa->id_siswa)
                                    //     ->whereHas('detail_biaya', function ($q) use ($id_bulan) {
                                    //         $q->where('id_bulan', $id_bulan);
                                    //     })
                                    //     ->whereHas('detail_biaya.biaya_sekolah', function ($q) use ($semester) {
                                    //         $q->where('id_semester', $semester->id_semester);
                                    //     })->first();

                                    if (empty($tagihan_siswa)) {
                                        return [
                                            'status' => 300, // FAILED
                                            'message' => "Tagihan dengan nomor " . $item->nis . 'tidak ditemukan didalam sistem, ',
                                        ];
                                    }

                                    if ($tagihan_siswa->is_tagih == '1') {
                                        $pembayaran_biaya = new PembayaranBiaya;
                                        $pembayaran_biaya->id_pembayaran_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                        $pembayaran_biaya->id_tagihan_biaya = $tagihan_siswa->id_tagihan_biaya;
                                        $pembayaran_biaya->id_staff_bayar = $input->auth_data->pengguna->id_pengguna;
                                        $pembayaran_biaya->id_semester_bayar = $semester->id_semester;
                                        $pembayaran_biaya->besar_pembayaran = $tagihan_siswa->besar_biaya;
                                        $pembayaran_biaya->tgl_pembayaran = $tanggal_bayar;
                                        $pembayaran_biaya->keterangan = "Langsung Lunas";
                                        $pembayaran_biaya->created_by = $input->auth_data->pengguna->id_pengguna;
                                        $pembayaran_biaya->save();

                                        $tagihan_siswa->besar_pembayaran = $tagihan_siswa->besar_pembayaran + $pembayaran_biaya->besar_pembayaran;
                                        $tagihan_siswa->tgl_pelunasan = $pembayaran_biaya->tgl_pembayaran;
                                        $tagihan_siswa->is_tagih = 0;
                                        $tagihan_siswa->updated_by = $input->auth_data->pengguna->id_pengguna;
                                        $tagihan_siswa->save();
                                    }
                                }
                            }
                        }

                        DB::commit();
                        return [
                            'status' => 202, // SUCCESS AND LOAD CONTENT
                            'path' => 'sim/spp/upload-pembayaran',
                            'message' => 'Upload Pembayaran Successfully',
                        ];
                    } catch (\Exception $e) {
                        DB::rollback();
                        // something went wrong
                        return [
                            'status' => 203, // GAGAL
                            'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                        ];
                    }
                }
            } else {
                return [
                    'status' => 300, // FAILED
                    'message' => "File Excel Anda Kosong",
                ];
            }
        } else {
            return [
                'status' => 300, // FAILED
                'message' => "File Excel tidak ditemukan",
            ];
        }
    }

    public function indexDownloadLapBulanan(Request $request, $tahun_akademik_semester = null, $id_bulan = null)
    {
        $input = (object) $request->input();

        $tahun = $tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if ($id_bulan < 7) {
            $id_semester = $id_semester_selesai;
        } else {
            $id_semester = $id_semester_mulai;
        }

        $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
            'id_semester_mulai' => $id_semester_mulai,
            'id_semester_selesai' => $id_semester_selesai,
            'id_bulan' => $id_bulan,
        ])->orderBy('tingkat')->get();

        $subkategori_rapb = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 2);
        })->get()->pluck('id_subkategori_rapb');

        $data_realisasi = Rapb::selectRaw('
                                        nm_kategori_rapb,
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
            ->leftJoin('realisasi', function ($q) {
                $q->on('realisasi.id_rapb', '=', 'rapb.id_rapb')
                    ->whereNull('realisasi.deleted_at');
            })
            ->join('subkategori_rapb', function ($q) {
                $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                    ->whereNull('subkategori_rapb.deleted_at');
            })
            ->join('kategori_rapb', function ($q) {
                $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                    ->whereNull('kategori_rapb.deleted_at');
            })
            ->where('id_semester_mulai', $id_semester_mulai)
            ->where('id_semester_selesai', $id_semester_selesai)
            ->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')
            ->get();

        $bulan = Bulan::find($id_bulan);
        $sekolah = $input->auth_data->sekolah_data;

        $tutup_buku_tahun_ini = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->first();
        $tutup_buku_kas_bulan_ini = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->first();
        if ($tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan - 1])->first()) { } else {
            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => 'Tagihan bulan lalu belum diproses',
            ]);
        }

        return view('keuangan/sim/spp/lap-bulanan-excel', compact('data_tutup_buku_bulanan_biaya', 'data_realisasi', 'bulan', 'tahun', 'sekolah', 'tutup_buku_tahun_ini', 'tutup_buku_kas_bulan_ini', 'tutup_buku_kas_bulan_lalu'));
    }

    public function actionRefreshLapBulanan(Request $request, $tahun_akademik_semester = null, $id_bulan = null)
    {
        $input = (object) $request->input();

        // $tahun      = $input->tahun;
        // $id_bulan   = $input->bulan;

        $tahun = $tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if ($id_bulan < 7) {
            $id_semester = $id_semester_selesai;
        } else {
            $id_semester = $id_semester_mulai;
        }

        $kode_semester_mulai = $semester_mulai->kode_semester;

        $list_data_jml_siswa = DB::select(
            'SELECT kelas.tingkat, COUNT(siswa.id_siswa) AS jml_siswa
                            FROM siswa
                            JOIN kelas ON kelas.id_kelas = siswa.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN admisi ON admisi.id_siswa = siswa.id_siswa
                                AND admisi.id_semester = ?
                                AND admisi.deleted_at IS NULL
                            JOIN status_pengguna ON status_pengguna.id_status_pengguna = admisi.id_status_pengguna
                                AND status_pengguna.aktif_status_pengguna = 1
                                AND status_pengguna.deleted_at IS NULL
                            WHERE siswa.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_semester]
        );

        $list_data_tagihan = DB::select(
            'SELECT kelas.tingkat, SUM(tagihan_biaya.besar_biaya) AS jml_tagihan_biaya
                            FROM tagihan_biaya
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ?
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE tagihan_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $id_semester]
        );

        $list_data_pembayaran = DB::select(
            'SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ?
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $id_semester, $tahun, $id_bulan]
        );

        $list_data_pembayaran_old_month = DB::select(
            'SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya_bulan_lalu
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ? - 1
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester = ?
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $id_semester, $tahun, $id_bulan]
        );

        $list_data_pembayaran_old_years = DB::select(
            'SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya_tahun_lalu
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                AND detail_biaya.id_bulan = ? - 1
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.deleted_at IS NULL
                            JOIN semester ON semester.id_semester = biaya_sekolah.id_semester
                                AND semester.kode_semester < ?
                                AND semester.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $kode_semester_mulai, $tahun, $id_bulan]
        );

        $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

        if ($tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan - 1])->first()) { } else {
            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => 'Tagihan bulan lalu belum diproses',
            ]);
        }

        DB::beginTransaction();
        try {

            foreach ($list_data_jml_siswa as $i => $data) {
                $tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                    'id_semester_mulai' => $id_semester_mulai,
                    'id_semester_selesai' => $id_semester_selesai,
                    'id_bulan' => $id_bulan,
                    'tingkat' => $data->tingkat,
                ])->first();

                if ($tutup_buku_bulanan_biaya) {
                    $tutup_buku_bulanan_biaya->jml_siswa = $data->jml_siswa;

                    $tagihan = collect($list_data_tagihan)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_tagihan_biaya = (!empty($tagihan) ? $tagihan->jml_tagihan_biaya : 0);

                    $pembayaran = collect($list_data_pembayaran)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya = (!empty($pembayaran) ? $pembayaran->jml_pembayaran_biaya : 0);

                    $tutup_buku_bulanan_biaya->jml_tunggakan_biaya = $tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya;

                    if ($id_bulan == 7) {
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = 0;
                    } else {
                        $pembayaran_old_month = collect($list_data_pembayaran_old_month)->firstWhere('tingkat', $data->tingkat);
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = (!empty($pembayaran_old_month) ? $pembayaran_old_month->jml_pembayaran_biaya_bulan_lalu : 0);
                    }

                    $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu = (!empty($pembayaran_old_years) ? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);

                    $tutup_buku_bulanan_biaya->updated_by = $input->auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                } else {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $tutup_buku_bulanan_biaya = new TutupBukuBulananBiaya;
                    $tutup_buku_bulanan_biaya->id_tutup_buku_bulanan_biaya = $id;
                    $tutup_buku_bulanan_biaya->id_semester_mulai = $id_semester_mulai;
                    $tutup_buku_bulanan_biaya->id_semester_selesai = $id_semester_selesai;
                    $tutup_buku_bulanan_biaya->id_bulan = $id_bulan;
                    $tutup_buku_bulanan_biaya->tingkat = $data->tingkat;
                    $tutup_buku_bulanan_biaya->jml_siswa = $data->jml_siswa;

                    $tagihan = collect($list_data_tagihan)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_tagihan_biaya = (!empty($tagihan) ? $tagihan->jml_tagihan_biaya : 0);

                    $pembayaran = collect($list_data_pembayaran)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya = (!empty($pembayaran) ? $pembayaran->jml_pembayaran_biaya : 0);

                    $tutup_buku_bulanan_biaya->jml_tunggakan_biaya = $tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya;

                    if ($id_bulan == 7) {
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = 0;
                    } else {
                        $pembayaran_old_month = collect($list_data_pembayaran_old_month)->firstWhere('tingkat', $data->tingkat);
                        $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = (!empty($pembayaran_old_month) ? $pembayaran_old_month->jml_pembayaran_biaya_bulan_lalu : 0);
                    }

                    $pembayaran_old_years = collect($list_data_pembayaran_old_years)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu = (!empty($pembayaran_old_years) ? $pembayaran_old_years->jml_pembayaran_biaya_tahun_lalu : 0);

                    $tutup_buku_bulanan_biaya->created_by = $input->auth_data->pengguna->id_pengguna;
                    $tutup_buku_bulanan_biaya->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() . '' . $e->getLine() : 'Operation error. Error ' . $e->getLine(),
            ]);
        }

        DB::beginTransaction();
        try {
            $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                'id_semester_mulai' => $id_semester_mulai,
                'id_semester_selesai' => $id_semester_selesai,
                'id_bulan' => $id_bulan,
            ])->get();

            /* INSERT TUTUP BUKU TAHUNAN BIAYA */
            $pembayaran_tunggakan_tahun_lalu = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu');

            $tahun_lalu = $tahun - 1;
            $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '1')->first()->id_semester;
            $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '2')->first()->id_semester;

            $tutup_buku_tahunan_biaya_old = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu])->first();

            if ($tutup_buku_tahunan_biaya_now = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->first()) {
                $tutup_buku_tahunan_biaya_now->updated_by = $input->auth_data->pengguna->id_pengguna;
            } else {
                $tutup_buku_tahunan_biaya_now = new TutupBukuTahunanBiaya;
                $tutup_buku_tahunan_biaya_now->id_tutup_buku_tahunan_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $tutup_buku_tahunan_biaya_now->id_semester_mulai = $id_semester_mulai;
                $tutup_buku_tahunan_biaya_now->id_semester_selesai = $id_semester_selesai;
                $tutup_buku_tahunan_biaya_now->created_by = $input->auth_data->pengguna->id_pengguna;
            }

            $tutup_buku_tahunan_biaya_now->jml_tunggakan_biaya = $tutup_buku_tahunan_biaya_old->jml_tunggakan_biaya - $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_tahunan_biaya_now->save();
            /* END INSERT TUTUP BUKU TAHUNAN BIAYA */

            /* INSERT TUTUP BUKU BULANAN KAS */
            $pembayaran_tunggakan_bulan_ini = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya');
            $pembayaran_tunggakan_bulan_lalu = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu');

            $data_realisasi = Realisasi::selectRaw('
                                        nm_kategori_rapb,
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
                ->join('rapb', function ($q) {
                    $q->on('rapb.id_rapb', '=', 'realisasi.id_rapb')
                        ->whereNull('rapb.deleted_at');
                })
                ->join('subkategori_rapb', function ($q) {
                    $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                        ->whereNull('subkategori_rapb.deleted_at');
                })
                ->join('kategori_rapb', function ($q) {
                    $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                        ->whereNull('kategori_rapb.deleted_at');
                })
                ->whereIn('id_semester_realisasi', [$id_semester_mulai, $id_semester_selesai])
                ->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')
                ->get();

            if ($tutup_buku_bulanan_kas_now = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan])->first()) {
                $tutup_buku_bulanan_kas_now->updated_by = $input->auth_data->pengguna->id_pengguna;
            } else {
                $tutup_buku_bulanan_kas_now = new TutupBukuBulananKas;
                $tutup_buku_bulanan_kas_now->id_tutup_buku_bulanan_kas = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $tutup_buku_bulanan_kas_now->id_semester_mulai = $id_semester_mulai;
                $tutup_buku_bulanan_kas_now->id_semester_selesai = $id_semester_selesai;
                $tutup_buku_bulanan_kas_now->id_bulan = $id_bulan;
                $tutup_buku_bulanan_kas_now->created_by = $input->auth_data->pengguna->id_pengguna;
            }
            $tutup_buku_bulanan_kas_now->kas_spp = $pembayaran_tunggakan_bulan_ini + $pembayaran_tunggakan_bulan_lalu + $pembayaran_tunggakan_tahun_lalu;
            $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan = $data_realisasi->where('tipe_kategori_rapb', 1)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran = $data_realisasi->where('tipe_kategori_rapb', 2)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_akhir_bulan = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan + $tutup_buku_bulanan_kas_old->kas_akhir_bulan;
            $tutup_buku_bulanan_kas_now->save();
            /* END INSERT TUTUP BUKU BULANAN KAS */

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'status_text' => 'Success',
                'message' => 'Success',
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' => 300,
                'status_text' => 'Failed',
                'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() . '' . $e->getLine() : 'Operation error. Error ' . $e->getLine(),
            ]);
        }
    }

    public function datatablesMenuCari(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun = $input->tahun_akademik_semester;
        $kelas = $input->kelas;

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })->where('id_jenis_detail_biaya', 4)->get();

            $list_data = Siswa::with(['tagihan_biaya' => function ($q) use ($data_detail_biaya) {
                $q->where('is_tagih', 1)
                    ->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'));
            }])
                ->with('pengguna', 'kelas', 'last_kelas_siswa.kelas');

            if (!empty($kelas)) {
                $list_data = $list_data->whereHas('kelas', function ($q) use ($kelas) {
                    $q->where('id_kelas', $kelas);
                });
            }
        } else {
            $list_data = array();
        }

        return Datatables::of($list_data)
            ->addColumn('total_tagihan_bulan', function ($item) {
                return 'Rp' . number_format($item->tagihan_biaya->sum('besar_biaya'));
            })
            ->addColumn('tagihan_bulan', function ($item) use ($data_detail_biaya) {
                $array_tagihan_bulan = array();
                foreach ($item->tagihan_biaya as $tagihan) {
                    $tagihan_bulan['id_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->id_bulan;
                    $tagihan_bulan['nm_bulan'] = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya)->bulan->nm_bulan;

                    $array_tagihan_bulan[] = $tagihan_bulan;
                }

                if (!empty($array_tagihan_bulan)) {
                    $array_tagihan_bulan = collect($array_tagihan_bulan)->sortBy('id_bulan')->pluck('nm_bulan');
                }

                return $array_tagihan_bulan;
            })
            ->editColumn('kelas.nm_kelas', function ($item) {  //this example  for edit your columns if colums is empty 
                $nm_kelas = !empty($item->id_kelas) ? $item->kelas->nm_kelas : 'Alumni ( ' .  $item->last_kelas_siswa->kelas->nm_kelas . ' )';
                return $nm_kelas;
            })
            ->make(true);
    }

    public function viewMenuPembayaran(Request $request, $tahun_akademik_semester = null, $id_kelas = null, $waktu = null, $order_by = null)
    {
        if ($waktu == null) {
            $waktu = Carbon::today()->toDateString();
        }
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
            $siswa = clone $query_tagihan->get();
            $data_tagihan = $query_tagihan->where('detail_biaya.id_jenis_detail_biaya', 4)->get();

            $clone_query_tagihan->where('detail_biaya.id_jenis_detail_biaya', '<>', 4);
            $data_tagihan_non_bulanan = $clone_query_tagihan->get();

            $data_bulan_tagihan = $data_tagihan->unique('nm_bulan')->sortBy('id_bulan')->sortBy('kode_semester')->values()->all();

            $total_pembayaran =  'Rp ' . number_format($data_tagihan->where('is_tagih', '0')->sum('besar_pembayaran'));
            $total_tunggakan = 'Rp ' . number_format($data_tagihan->where('is_tagih', '1')->sum('besar_biaya'));
            $jumlah_pembayaran = $data_tagihan->where('is_tagih', '0')->count();
            $jumlah_tunggakan = $data_tagihan->where('is_tagih', '1')->count();


            // $semesterMulai = $semester_mulai->id_semester;
            // $semesterSelesai = $semester_selesai->id_semester;
            // $total_pembayaran = [];

            // $data_pembayaran = PembayaranBiaya::whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($query) use ($semesterMulai, $semesterSelesai) {
            //     $query->whereIn('id_semester', [$semesterMulai, $semesterSelesai]);
            // })->whereHas('tagihan_biaya', function ($query) use ($id_kelas) {
            //     $query->where('id_kelas', $id_kelas);
            // })->get();

            // $total_pembayaran = collect($data_bulan_tagihan)->mapWithKeys(function ($data_bulan) use ($data_pembayaran) {
            //     $data = $data_pembayaran->filter(function ($item) use ($data_bulan) {
            //         return $item->tgl_pembayaran->format('m') == $data_bulan->kode_bulan;
            //     })->count();
            //     return [$data_bulan->id_bulan => $data];
            // })->toArray();

            // $data_tagihan->unique('id_siswa')->pluck('id_siswa')->values()->all()

            $data_siswa = Siswa::with('pengguna', 'pengguna.status_pengguna')
                ->whereIn('siswa.id_siswa', $siswa->unique('id_siswa')->pluck('id_siswa')->values()->all())
                ->get()
                ->sortBy('nis_siswa')
                ->when($order_by, function ($query, $order_by) {
                    return $query->sortBy($order_by == 'nama' ? 'pengguna.nm_pengguna' : 'nis_siswa');
                });


            //semester lain
            // $list_id_semester_lalu = $semester->where('thn_akademik_semester', '<', $tahun_akademik_semester)->pluck('id_semester')->toArray();

            // $data_tagihan_siswa_semester_lalu = TagihanBiaya::with('detail_biaya.bulan', 'kelas')->where('is_tagih', '1')->whereIn('id_siswa', $data_tagihan->unique('id_siswa')->pluck('id_siswa'))
            //     ->whereHas('detail_biaya', function ($query) {
            //         $query->where('id_jenis_detail_biaya', '=', '4')->where('validasi_biaya', '1');
            //     })
            //     ->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($list_id_semester_lalu) {
            //         $query->whereIn('id_semester',  $list_id_semester_lalu);
            //     })->get();

            $data_ket_tagihan = $data_tagihan_non_bulanan->unique('keterangan')->sortByDesc('keterangan');
        } else {
            $total_pembayaran = array();
            $data_siswa = array();
            $data_tagihan = array();
            $data_bulan_tagihan = array();

            $data_tagihan_non_bulanan = array();
            $data_ket_tagihan = array();
            $data_tagihan_siswa_semester_lalu = array();

            $total_pembayaran =  0;
            $total_tunggakan = 0;
            $jumlah_pembayaran = 0;
            $jumlah_tunggakan = 0;
        }
        //'data_tagihan_siswa_semester_lalu'
        //'total_pembayaran'

        $minDate = Carbon::parse($waktu)->subDays(60)->format('Y/m/d');
        $maxDate = Carbon::parse($waktu)->addDays(60)->format('Y/m/d');

        return view('keuangan/sim/spp/view-menu-pembayaran', compact('auth_data', 'data_semester', 'data_kelas', 'tahun_akademik_semester', 'id_kelas', 'data_siswa', 'data_tagihan', 'data_bulan_tagihan', 'waktu', 'data_tagihan_non_bulanan', 'data_ket_tagihan', 'minDate', 'maxDate', 'total_pembayaran', 'total_tunggakan', 'jumlah_pembayaran', 'jumlah_tunggakan'));
    }

    public function viewMenuPemasukan(Request $request, $tahun_akademik_semester = null, $id_bulan = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        if (empty($id_bulan)) {
            $now = Carbon::today();

            $id_bulan = $now->month;
        }

        $start_month = Carbon::create($tahun_akademik_semester, $id_bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun_akademik_semester, $id_bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();

        $start_date = $start_month->format('Y-m-d');
        $end_date = $end_month->format('Y-m-d');

        // $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTingkat($auth_data, $start_date, $end_date);

        $sc_tahun = Carbon::createFromFormat('Y-m-d', $start_date);
        $tahun = $sc_tahun->year;

        $sc_bulan = Carbon::createFromFormat('Y-m-d', $start_date);
        $id_bulan = $sc_bulan->month;

        if ($id_bulan < 7) {
            $tahun_semester = $tahun - 1;
        } else {
            $tahun_semester = $tahun;
        }

        $data_laporan = [
            'semester_aktif' => Semester::where('kode_semester', $tahun_semester . '1')->first(),
            'dates' => CarbonPeriod::create($start_date, $end_date),
            'tingkat' => Kelas::select('tingkat')->distinct()->get()->pluck('tingkat'),
        ];


        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('keuangan/sim/spp/view-menu-pemasukan', compact('auth_data', 'data_laporan', 'start_date', 'end_date', 'data_semester', 'tahun_akademik_semester', 'data_bulan', 'id_bulan'));
    }


    public function getPemasukanData(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_laporan = LibCetakKeuangan::fetchLaporanPembayaranPerTingkat($auth_data, $input->start_date, $input->end_date);
        $data = [];

        if ($input->status == '1') {
            foreach ($data_laporan['dates'] as $date) {
                $id_bulan = $date->format('n');
                $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);
                if ($id_bulan < 7) {
                    $index_splice = $id_bulan + 5;
                    $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                    $index_periode_bulan_ini->all();

                    $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                    $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                } elseif ($id_bulan == 7) {
                    $where_bayar_bulan_ini_dan_kedepannya = [7];
                    $where_bayar_bulan_lalu_dan_belakangnya = [];
                } else {
                    $index_splice = $id_bulan - 7;
                    $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                    $index_periode_bulan_ini->all();

                    $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                    $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                }

                foreach ($data_laporan['tingkat'] as $tingkat) {
                    $data[$tingkat . '-' . '1' . '-' . $date->format('Y-m-d')] = 'Rp ' . number_format(
                        $data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where(
                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                            $data_laporan['semester_aktif']->tahun_ajaran,
                        )->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran'),
                    );
                }

                $data['jumlah-' . $date->format('Y-m-d')] = 'Rp ' .
                    number_format(
                        $data_laporan['data']->where(
                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                            $data_laporan['semester_aktif']->tahun_ajaran,
                        )->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran'),
                    );
            }

            foreach ($data_laporan['tingkat'] as $tingkat) {
                $data['total-' . $tingkat . '-' . '1'] = 'Rp ' .
                    number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_ini_dan_kedepannya)->sum('besar_pembayaran'));
            }

            $data['total-jumlah'] = 'Rp ' .
                number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->sum('besar_pembayaran'));

            return $data;
        } else {
            foreach ($data_laporan['dates'] as $date) {
                $id_bulan = $date->format('n');
                $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);

                if ($id_bulan < 7) {
                    $index_splice = $id_bulan + 5;
                    $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                    $index_periode_bulan_ini->all();

                    $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                    $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                } elseif ($id_bulan == 7) {
                    $where_bayar_bulan_ini_dan_kedepannya = [7];
                    $where_bayar_bulan_lalu_dan_belakangnya = [];
                } else {
                    $index_splice = $id_bulan - 7;
                    $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
                    $index_periode_bulan_ini->all();

                    $where_bayar_bulan_ini_dan_kedepannya = $index_periode_bulan_ini;
                    $where_bayar_bulan_lalu_dan_belakangnya = $periode_bulan_sekolah;
                }

                foreach ($data_laporan['tingkat'] as $tingkat) {
                    $data[$tingkat . '-' . '2' . '-' . $date->format('Y-m-d')] = 'Rp ' . number_format(
                        $data_laporan['data']->where('tagihan_biaya.kelas.tingkat', $tingkat)->where(
                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                            $data_laporan['semester_aktif']->tahun_ajaran,
                        )->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran'),
                    );
                }

                $data['tahun-lalu-masuk-' . $date->format('Y-m-d')] = 'Rp ' .
                    number_format(
                        $data_laporan['data_tunggakan']->filter(function ($item) use ($date) {
                            return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                        })->sum('besar_pembayaran') +
                            $data_laporan['data']->where(
                                'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                                '!=',
                                $data_laporan['semester_aktif']->tahun_ajaran,
                            )->filter(function ($item) use ($date) {
                                return false !== stristr($item->tgl_pembayaran, $date->format('Y-m-d'));
                            })->sum('besar_pembayaran'),
                    );
            }

            foreach ($data_laporan['tingkat'] as $tingkat) {
                $data['total-' . $tingkat . '-' . '2'] = 'Rp ' .
                    number_format($data_laporan['data']->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', $data_laporan['semester_aktif']->tahun_ajaran)->where('tagihan_biaya.kelas.tingkat', $tingkat)->whereIn('tagihan_biaya.detail_biaya.id_bulan', $where_bayar_bulan_lalu_dan_belakangnya)->sum('besar_pembayaran'));
            }


            $data['total-tahun-lalu-masuk'] = 'Rp ' .
                number_format(
                    $data_laporan['data_tunggakan']->sum('besar_pembayaran') +
                        $data_laporan['data']->where(
                            'tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran',
                            '!=',
                            $data_laporan['semester_aktif']->tahun_ajaran,
                        )->sum('besar_pembayaran'),
                );
            return $data;
        }
    }



    public function viewMenuPenerimaan(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $tahun = $tahun_akademik_semester;
        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
        })->get();

        $data_bulan = Bulan::orderBy('id_bulan')->get();

        $data_realisasi = Realisasi::select('*')->addSelect(DB::raw('MONTH(realisasi.tgl_realisasi) month'))
            ->with('rapb', 'rapb.subkategori')->whereHas('rapb.subkategori.kategori', function ($q) {
                $q->where('tipe_kategori_rapb', 1)->where('jenis_kategori_rapb', 0);
            })->whereIn('id_semester_realisasi', [$semester_mulai->id_semester, $semester_selesai->id_semester])->get();

        return view('keuangan/sim/spp/view-menu-penerimaan', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_subkategori', 'data_bulan', 'data_realisasi'));
    }

    public function viewMenuDetailPenerimaan(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        if (empty($tgl_awal)) {
            $tgl_awal = Carbon::now()->firstOfMonth()->format('Y-m-d');
        }

        if (empty($tgl_akhir)) {
            $tgl_akhir = Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        return view('keuangan/sim/spp/view-menu-detail-penerimaan', compact('auth_data', 'data_semester', 'tgl_awal', 'tgl_akhir', 'tahun_akademik_semester'));
    }

    public function datatablesMenuDetailPenerimaan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $tahun = $input->tahun;
        $tgl_awal = $input->tgl_awal;
        $tgl_akhir = $input->tgl_akhir;
        // $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        // $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $list_data = Realisasi::with('rapb', 'rapb.subkategori')->whereHas('rapb.subkategori.kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 1);
        })->
            // whereIn('id_semester_realisasi', [$semester_mulai->id_semester, $semester_selesai->id_semester])
            // ->
            whereDate('tgl_realisasi', '>=', $tgl_awal)
            ->whereDate('tgl_realisasi', '<=', $tgl_akhir)
            ->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();

        // dd($list_data);
        return Datatables::of($list_data)
            ->editColumn('tgl_realisasi', function ($item) {
                return date_format(date_create($item->tgl_realisasi), "d M Y");
            })
            ->editColumn('dana_realisasi', function ($item) {
                return 'Rp' . number_format($item->dana_realisasi);
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_realisasi,
                );
                return $data;
            })
            ->make(true);
    }

    public function viewMenuTunggakan(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

        $tutup_buku_tahunan = TutupBukuTahunanBiaya::where('id_semester_mulai', $semester_mulai->id_semester)
            ->where('id_semester_selesai', $semester_selesai->id_semester)
            ->isInputByPengguna($auth_data->pengguna->id_pengguna)
            ->first();

        $data_pembayaran_tunggakan = PembayaranTunggakan::where('id_semester_mulai', $semester_mulai->id_semester)
            ->where('id_semester_selesai', $semester_selesai->id_semester)
            ->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();

        $bulanAwal = [7, 8, 9, 10, 11, 12];
        $pembayaran_semester_mulai = $pembayaran = PembayaranBiaya::whereIn('tgl_pembayaran', function ($query) use ($bulanAwal) {
            $query->select('tgl_pembayaran')
                ->from('pembayaran_biaya') // Ganti dengan nama tabel yang benar
                ->whereIn(DB::raw('MONTH(tgl_pembayaran)'), $bulanAwal);
        })->whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($q) use ($semester_mulai, $semester_selesai) {
            $q->whereNotIn('id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester]);
        })->whereYear('tgl_pembayaran',  $semester_mulai->thn_akademik_semester)->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();

        $bulanAkhir = [1, 2, 3, 4, 5, 6];
        $pembayaran_semester_akhir = $pembayaran = PembayaranBiaya::whereIn('tgl_pembayaran', function ($query) use ($bulanAkhir) {
            $query->select('tgl_pembayaran')
                ->from('pembayaran_biaya') // Ganti dengan nama tabel yang benar
                ->whereIn(DB::raw('MONTH(tgl_pembayaran)'), $bulanAkhir);
        })->whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($q) use ($semester_mulai, $semester_selesai) {
            $q->whereNotIn('id_semester', [$semester_mulai->id_semester, $semester_selesai->id_semester]);
        })->whereYear('tgl_pembayaran',  $semester_selesai->thn_akademik_semester + 1)->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();


        if ($tutup_buku_tahunan) {
            $sisa_tunggakan = $tutup_buku_tahunan->jml_tunggakan_biaya - $data_pembayaran_tunggakan->sum('besar_pembayaran');
            $sisa_tunggakan -=  $pembayaran_semester_mulai->sum('besar_pembayaran');
            $sisa_tunggakan -= $pembayaran_semester_akhir->sum('besar_pembayaran');
        } else {
            $sisa_tunggakan = null;
        }

        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('keuangan/sim/spp/view-menu-tunggakan', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'tutup_buku_tahunan', 'data_pembayaran_tunggakan', 'sisa_tunggakan', 'data_bulan'));
    }

    public function viewMenuTunggakanAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('keuangan/sim/spp/view-menu-tunggakan-alumni', compact('auth_data'));
    }

    public function datatablesMenuTunggakanAlumni(Request $request)
    {
        $input = (object) $request->input();

        $list_data = TagihanBiaya::select(
            'pengguna.nm_pengguna',
            'tagihan_biaya.id_siswa',
            'siswa.id_siswa',
            'siswa.nis_siswa',
            'siswa.thn_masuk_siswa',
            DB::raw('SUM(tagihan_biaya.besar_biaya) as total_biaya'),
            DB::raw('COUNT(tagihan_biaya.id_tagihan_biaya) as total_tagihan')
        )
            ->join('siswa', 'tagihan_biaya.id_siswa', '=', 'siswa.id_siswa')
            ->join('pengguna', 'siswa.id_pengguna', '=', 'pengguna.id_pengguna')
            ->join('detail_biaya', 'tagihan_biaya.id_detail_biaya', '=', 'detail_biaya.id_detail_biaya')
            ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
            ->groupBy('pengguna.nm_pengguna', 'tagihan_biaya.id_siswa',  'siswa.id_siswa', 'siswa.nis_siswa', 'siswa.thn_masuk_siswa')
            ->where('tagihan_biaya.is_tagih', '1')
            ->where('detail_biaya.id_jenis_detail_biaya', 4)
            ->where('status_pengguna.nm_status_pengguna', 'LULUS')
            ->with('siswa.last_kelas_siswa.kelas', 'siswa.tunggakan_alumni')
            ->get();

        return Datatables::of($list_data)
            ->addColumn('tunggakan', function ($item) {
                if (isset($item->siswa->tunggakan_alumni)) {
                    $data = array(
                        'jumlah_tunggakan' =>  'Rp ' . number_format($item->siswa->tunggakan_alumni->jumlah_tunggakan),
                        'selisih' => ($item->siswa->tunggakan_alumni->jumlah_tunggakan == $item->total_biaya) ? '-' : 'Rp ' . number_format($item->total_biaya - $item->siswa->tunggakan_alumni->jumlah_tunggakan),
                        'action' => $item->id_siswa,
                    );
                } else {
                    $data = array(
                        'jumlah_tunggakan' =>  '-',
                        'selisih' => ($item->total_biaya == '0') ? '-' : 'Rp ' . number_format($item->total_biaya),
                        'action' => $item->id_siswa,
                    );
                }
                return $data;
            })->editColumn('nm_kelas', function ($item) {
                return $item->siswa->last_kelas_siswa ? $item->siswa->last_kelas_siswa->kelas->nm_kelas : '-';
            })
            ->addColumn('total_biaya', function ($item) {
                return 'Rp ' . number_format($item->total_biaya);
            })

            ->make(true);
    }

    public function getDetailDataTungakanAlumni(Request $request)
    {
        $input = (object) $request->input();
        // dd($input->id_siswa);
        // $list_id_semester_lalu = Semester::where('thn_akademik_semester', '<', $input->tahun_akademik_semester)->pluck('id_semester')->toArray();
        // $status =  $input->status;
        $data_tagihan_siswa_semester_lalu = TagihanBiaya::with('kelas', 'detail_biaya.bulan', 'detail_biaya.biaya_sekolah.semester')->where('is_tagih', '1')->where('id_siswa', $input->id_siswa)
            ->whereHas('detail_biaya', function ($query) {
                $query->where('id_jenis_detail_biaya', '=', '4')->where('validasi_biaya', '1');
            })
            ->whereHas('detail_biaya.biaya_sekolah')
            ->get();

        $data['status'] = $input->status;
        $data['data_tagihan_siswa_semester_lalu'] = $data_tagihan_siswa_semester_lalu;

        return $data;
    }

    public function viewMenuSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        return view('keuangan/sim/spp/view-menu-setting', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function datatablesMenuSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun = $input->tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::whereHas('biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })
                ->where('id_jenis_detail_biaya', 4)
                ->where(function ($q) {
                    $q->where('id_bulan', 1)
                        ->orWhere('id_bulan', 7);
                })
                ->get();
        } else {
            $data_detail_biaya = null;
        }

        $list_data = Kelas::with(['tagihan' => function ($q) use ($data_detail_biaya) {
            $q->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'))
                ->with('detail_biaya', 'detail_biaya.bulan');
        }])->where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas');

        return Datatables::of($list_data)
            ->addColumn('nominal_spp_juli', function ($item) {
                $biaya = 'Belum diset';
                if (!empty($item->tagihan)) {
                    if ($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 7)->first()) {
                        $biaya = 'Rp' . number_format($tagihan->besar_biaya);
                    }
                }

                return $biaya;
            })
            ->addColumn('nominal_spp_non_juli', function ($item) {
                $biaya = 'Belum diset';
                if (!empty($item->tagihan)) {
                    if ($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 1)->first()) {
                        $biaya = 'Rp' . number_format($tagihan->besar_biaya);
                    }
                }

                return $biaya;
            })
            ->addColumn('action', function ($item) {
                $status = 0;
                if (!empty($item->tagihan)) {
                    if ($tagihan = $item->tagihan->where('detail_biaya.bulan.id_bulan', 7)->first()) {
                        $status = 1;
                    }
                }

                $data = array(
                    'id' => $item->id_kelas,
                    'status' => $status,
                );
                return $data;
            })
            ->make(true);
    }

    public function viewMenuSettingNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->orderBy('nm_biaya', 'asc')->get();

        return view('keuangan/sim/spp/view-menu-setting-non-spp', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_biaya'));
    }

    public function datatablesMenuSettingNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun = $input->tahun_akademik_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $data_biaya = Biaya::where('nm_biaya', '<>', 'SPP')->get();

        if (!empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_detail_biaya = DetailBiaya::whereHas('biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })
                ->whereIn('id_biaya', $data_biaya->pluck('id_biaya'))
                ->get();
        } else {
            $data_detail_biaya = null;
        }

        $list_data = Kelas::with(['tagihan' => function ($q) use ($data_detail_biaya) {
            $q->whereIn('id_detail_biaya', $data_detail_biaya->pluck('id_detail_biaya'))
                ->with('detail_biaya');
        }])->where('is_aktif', 1)->orderBy('tingkat');

        $dt = Datatables::of($list_data);

        foreach ($data_biaya as $biaya) {
            $dt->addColumn($biaya->id_biaya, function ($item) use ($biaya) {
                $besar_biaya = 'Belum diset';
                if (!empty($item->tagihan)) {
                    if ($tagihan = $item->tagihan->firstWhere('detail_biaya.id_biaya', $biaya->id_biaya)) {
                        $besar_biaya = 'Rp' . number_format($tagihan->besar_biaya);
                    }
                }

                return $besar_biaya;
            });
        }

        return $dt->addColumn('action', function ($item) {
            $data = array(
                'id' => $item->id_kelas,
            );
            return $data;
        })
            ->make(true);
    }

    public function viewMenuSettingTunggakanTahunLalu(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        return view('keuangan/sim/spp/view-menu-setting-tunggakan-tahun-lalu', compact('auth_data', 'semester_aktif'));
    }

    public function datatablesMenuSettingTunggakanTahunLalu(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = TutupBukuTahunanBiaya::with('semester_mulai', 'semester_selesai')->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();

        return Datatables::of($list_data)
            ->addColumn('semester_mulai', function ($item) {
                return $item->semester_mulai->tahun_ajaran . ' ' . $item->semester_mulai->nm_semester;
            })
            ->addColumn('semester_selesai', function ($item) {
                return $item->semester_mulai->tahun_ajaran . ' ' . $item->semester_selesai->nm_semester;
            })
            ->addColumn('tunggakan', function ($item) {
                return number_format($item->jml_tunggakan_biaya, 0, ',', '.');
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_tutup_buku_tahunan_biaya,
                );
                return $data;
            })
            ->make(true);
    }

    public function addeMenuSettingTunggakanTahunLalu(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        // get semester mulai dan selesai
        $tahun = $semester_aktif->thn_akademik_semester;
        $kode_semester_mulai = $tahun . '1';
        $kode_semester_selesai = $tahun . '2';

        $semester_mulai = Semester::where('kode_semester', $kode_semester_mulai)->first();
        $semester_selesai = Semester::where('kode_semester', $kode_semester_selesai)->first();

        return view('keuangan/sim/spp/add-menu-setting-tunggakan-tahun-lalu', compact('auth_data', 'semester_mulai', 'semester_selesai'));
    }

    public function editMenuSettingTunggakanTahunLalu($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = TutupBukuTahunanBiaya::find($id);

        return view('keuangan/sim/spp/edit-menu-setting-tunggakan-tahun-lalu', compact('auth_data', 'data'));
    }

    public function actionMenuSettingTunggakanTahunLalu(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'jml_tunggakan_biaya' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

            // get semester mulai dan selesai
            $tahun = $semester_aktif->thn_akademik_semester;
            $kode_semester_mulai = $tahun . '1';
            $kode_semester_selesai = $tahun . '2';

            $tahun_lalu = $semester_aktif->thn_akademik_semester - 1;

            $semester_mulai = Semester::where('kode_semester', $kode_semester_mulai)->first();
            $semester_selesai = Semester::where('kode_semester', $kode_semester_selesai)->first();

            $semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '1')->first();
            $semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '2')->first();

            if ($mode == 'add') {

                // cek apakah sudah pernah diinput
                $cek = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

                if ($cek) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Data pada semester ini sudah dimasukkan',
                    ];
                }

                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $data = new TutupBukuTahunanBiaya;
                $data->id_tutup_buku_tahunan_biaya = $id;
                $data->id_semester_mulai = $semester_mulai->id_semester;
                $data->id_semester_selesai = $semester_selesai->id_semester;
                $data->jml_tunggakan_biaya = $input->jml_tunggakan_biaya;
                $data->created_by = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                $cek = TutupBukuBulananKas::where(['id_bulan' => 6, 'id_semester_mulai' => $semester_mulai_tahun_lalu->id_semester, 'id_semester_selesai' => $semester_selesai_tahun_lalu->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

                if ($cek) {
                    $cek->sisa_tunggakan_biaya = $data->jml_tunggakan_biaya;
                    $cek->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sim/spp/setting-tunggakan-tahun-lalu',
                    'message' => 'Save Gedung Successfully',
                ];
            } elseif ($mode == 'edit') {
                $data = TutupBukuTahunanBiaya::find($id);
                $data->jml_tunggakan_biaya = $input->jml_tunggakan_biaya;
                $data->updated_by = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                $cek = TutupBukuBulananKas::where(['id_bulan' => 6, 'id_semester_mulai' => $semester_mulai_tahun_lalu->id_semester, 'id_semester_selesai' => $semester_selesai_tahun_lalu->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

                if ($cek) {
                    $cek->sisa_tunggakan_biaya = $data->jml_tunggakan_biaya;
                    $cek->save();
                }

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sim/spp/setting-tunggakan-tahun-lalu',
                    'message' => 'Save Gedung Successfully',
                ];
            }
        }
    }

    public function viewMenuSettingSaldoKasAwalTahun(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        return view('keuangan/sim/spp/view-menu-setting-saldo-kas-awal-tahun', compact('auth_data', 'semester_aktif'));
    }

    public function datatablesMenuSettingSaldoKasAwalTahun(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = TutupBukuBulananKas::where('id_bulan', 6)->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();

        return Datatables::of($list_data)
            ->addColumn('semester_mulai', function ($item) {
                return $item->semester_mulai->tahun_ajaran . ' ' . $item->semester_mulai->nm_semester;
            })
            ->addColumn('semester_selesai', function ($item) {
                return $item->semester_mulai->tahun_ajaran . ' ' . $item->semester_selesai->nm_semester;
            })
            ->addColumn('saldo', function ($item) {
                return number_format($item->kas_akhir_bulan, 0, ',', '.');
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_tutup_buku_bulanan_kas,
                );
                return $data;
            })
            ->make(true);
    }

    public function addeMenuSettingSaldoKasAwalTahun(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        // get semester mulai dan selesai
        $tahun = $semester_aktif->thn_akademik_semester - 1;
        $kode_semester_mulai = $tahun . '1';
        $kode_semester_selesai = $tahun . '2';

        $semester_mulai = Semester::where('kode_semester', $kode_semester_mulai)->first();
        $semester_selesai = Semester::where('kode_semester', $kode_semester_selesai)->first();

        return view('keuangan/sim/spp/add-menu-setting-saldo-kas-awal-tahun', compact('auth_data', 'semester_mulai', 'semester_selesai'));
    }

    public function editMenuSettingSaldoKasAwalTahun($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = TutupBukuBulananKas::find($id);

        return view('keuangan/sim/spp/edit-menu-setting-saldo-kas-awal-tahun', compact('auth_data', 'data'));
    }

    public function actionMenuSettingSaldoKasAwalTahun(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'kas_akhir_bulan' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

            // get semester mulai dan selesai
            $tahun = $semester_aktif->thn_akademik_semester - 1;
            $kode_semester_mulai = $tahun . '1';
            $kode_semester_selesai = $tahun . '2';

            $semester_mulai = Semester::where('kode_semester', $kode_semester_mulai)->first();
            $semester_selesai = Semester::where('kode_semester', $kode_semester_selesai)->first();

            if ($mode == 'add') {

                // cek apakah sudah pernah diinput
                $cek = TutupBukuBulananKas::where(['id_bulan' => 6, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

                if ($cek) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Data pada semester ini sudah dimasukkan',
                    ];
                }

                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $data = new TutupBukuBulananKas;
                $data->id_tutup_buku_bulanan_kas = $id;
                $data->id_semester_mulai = $semester_mulai->id_semester;
                $data->id_semester_selesai = $semester_selesai->id_semester;
                $data->id_bulan = 6;
                $data->kas_akhir_bulan = $input->kas_akhir_bulan;
                $data->created_by = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sim/spp/setting-saldo-kas-awal-tahun',
                    'message' => 'Save Successfully',
                ];
            } elseif ($mode == 'edit') {

                $data = TutupBukuBulananKas::find($id);
                $data->kas_akhir_bulan = $input->kas_akhir_bulan;
                $data->updated_by = $input->auth_data->pengguna->id_pengguna;
                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sim/spp/setting-saldo-kas-awal-tahun',
                    'message' => 'Save Successfully',
                ];
            }
        }
    }

    public function actionMenuSettingSaveSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester' => 'required',
            'id_kelas' => 'required',
            'nominal_spp_juli' => 'required',
            'nominal_spp_non_juli' => 'required',
            'id_kelompok_biaya' => 'required',
            'id_kelompok_biaya_internal_juli' => 'required',
            'id_kelompok_biaya_internal_non_juli' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {
            $now = Carbon::now();

            $tahun = $input->tahun_akademik_semester;
            $data_siswa = Siswa::where('id_kelas', $input->id_kelas)->get();
            $kelas = Kelas::find($input->id_kelas);

            $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

            $biaya = Biaya::where('nm_biaya', 'SPP')->first();
            $kelompok_biaya = KelompokBiaya::where('id_kelompok_biaya', $input->id_kelompok_biaya)->first();

            $data_bulan = Bulan::orderBy('id_bulan', 'asc')->get();

            DB::beginTransaction();
            try {
                Siswa::where('id_kelas', $kelas->id_kelas)->update(['id_kelompok_biaya' => $input->id_kelompok_biaya]);
                foreach ($data_bulan as $bulan) {
                    $detail_biaya = array();
                    $tagihan = array();

                    $id_detail_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    if ($bulan->id_bulan < 7) {
                        $id_semester = $semester_selesai->id_semester;
                    } else {
                        $id_semester = $semester_mulai->id_semester;
                    }

                    if ($biaya_sekolah = BiayaSekolah::where(['id_semester' => $id_semester, 'id_kelompok_biaya' => $input->id_kelompok_biaya])->first()) { } else {
                        $biaya_sekolah = new BiayaSekolah;
                        $biaya_sekolah->id_biaya_sekolah = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $biaya_sekolah->id_kelompok_biaya = $input->id_kelompok_biaya;
                        $biaya_sekolah->id_semester = $id_semester;
                        $biaya_sekolah->besar_biaya_sekolah = (1 * $input->nominal_spp_juli) + (11 * $input->nominal_spp_non_juli);
                        $biaya_sekolah->validasi_biaya_sekolah = 1;
                        $biaya_sekolah->keterangan_biaya_sekolah = 'SPP ' . $kelompok_biaya->nm_kelompok_biaya;
                        $biaya_sekolah->save();
                    }

                    if ($bulan->id_bulan == 7) {
                        $besar_biaya = $input->nominal_spp_juli;
                        $id_kelompok_biaya_internal = $input->id_kelompok_biaya_internal_non_juli;
                    } else {
                        $besar_biaya = $input->nominal_spp_non_juli;
                        $id_kelompok_biaya_internal = $input->id_kelompok_biaya_internal_juli;
                    }

                    if ($item = DetailBiaya::where(
                        [
                            'id_biaya_sekolah' => $biaya_sekolah->id_biaya_sekolah,
                            'id_biaya' => $biaya->id_biaya,
                            'id_kelompok_biaya_internal' => $id_kelompok_biaya_internal,
                            'id_jenis_detail_biaya' => 4,
                            'id_bulan' => $bulan->id_bulan,
                        ]
                    )->first()) {
                        $id_detail_biaya = $item->id_detail_biaya;
                    } else {
                        $detail_biaya[] = array(
                            'id_detail_biaya' => $id_detail_biaya,
                            'id_biaya_sekolah' => $biaya_sekolah->id_biaya_sekolah,
                            'id_biaya' => $biaya->id_biaya,
                            'id_kelompok_biaya_internal' => $id_kelompok_biaya_internal,
                            'validasi_biaya' => 1,
                            'besar_biaya' => $besar_biaya,
                            'id_jenis_detail_biaya' => 4,
                            'id_bulan' => $bulan->id_bulan,
                            'created_at' => $now,
                            'created_by' => $input->auth_data->pengguna->id_pengguna,
                            'updated_at' => $now,
                        );
                    }

                    foreach ($data_siswa as $siswa) {
                        $tagihan[] = array(
                            'id_tagihan_biaya' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                            'id_siswa' => $siswa->id_siswa,
                            'id_kelas' => $siswa->id_kelas,
                            'id_detail_biaya' => $id_detail_biaya,
                            'besar_biaya' => $besar_biaya,
                            'denda_biaya' => 0,
                            'is_tagih' => 1,
                            'created_at' => $now,
                            'created_by' => $input->auth_data->pengguna->id_pengguna,
                            'updated_at' => $now,
                        );
                    }

                    if (!empty($detail_biaya)) {
                        DetailBiaya::insert($detail_biaya);
                    }

                    if (!empty($tagihan)) {
                        TagihanBiaya::insert($tagihan);
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/setting',
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed ' . $e->getMessage() . ' in line ' . $e->getLine(),
                ]);
            }
        }
    }

    public function actionMenuSettingSaveNonSpp(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester' => 'required',
            'semester' => 'required',
            'id_jenis_detail_biaya' => 'required',
            'id_kelas' => 'required',
            'id_biaya' => 'required',
            'besar_biaya' => 'required',
            'id_kelompok_biaya' => 'required',
            'id_kelompok_biaya_internal' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {
            $now = Carbon::now();

            $tahun = $input->tahun_akademik_semester;
            $data_siswa = Siswa::where('id_kelas', $input->id_kelas)->get();
            $kelas = Kelas::find($input->id_kelas);

            $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

            $data_bulan = Bulan::orderBy('id_bulan', 'asc')->get();

            if ($input->semester == 1) {
                $id_semester = $semester_mulai->id_semester;
            } else {
                $id_semester = $semester_selesai->id_semester;
            }

            if ($biaya_sekolah = BiayaSekolah::where(['id_semester' => $id_semester, 'id_kelompok_biaya' => $input->id_kelompok_biaya])->first()) {
                $biaya_sekolah->besar_biaya_sekolah = $biaya_sekolah->besar_biaya_sekolah + $input->besar_biaya;
                $biaya_sekolah->save();
            } else {
                $biaya_sekolah = new BiayaSekolah;
                $biaya_sekolah->id_biaya_sekolah = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $biaya_sekolah->id_kelompok_biaya = $input->id_kelompok_biaya;
                $biaya_sekolah->id_semester = $id_semester;
                $biaya_sekolah->besar_biaya_sekolah = $input->besar_biaya;
                $biaya_sekolah->validasi_biaya_sekolah = 1;
                $biaya_sekolah->keterangan_biaya_sekolah = 'SPP Kelas ' . $kelas->nm_kelas;
                $biaya_sekolah->save();
            }

            DB::beginTransaction();
            try {
                $detail_biaya = array();
                $tagihan = array();

                foreach ($data_siswa as $siswa) {
                    $id_detail_biaya = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $besar_biaya = $input->besar_biaya;

                    $detail_biaya[] = array(
                        'id_detail_biaya' => $id_detail_biaya,
                        'id_biaya_sekolah' => $biaya_sekolah->id_biaya_sekolah,
                        'id_biaya' => $input->id_biaya,
                        'id_kelompok_biaya_internal' => $input->id_kelompok_biaya_internal,
                        'validasi_biaya' => 1,
                        'besar_biaya' => $besar_biaya,
                        'id_jenis_detail_biaya' => $input->id_jenis_detail_biaya,
                        'created_at' => $now,
                        'created_by' => $input->auth_data->pengguna->id_pengguna,
                        'updated_at' => $now,
                    );

                    $tagihan[] = array(
                        'id_tagihan_biaya' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                        'id_siswa' => $siswa->id_siswa,
                        'id_kelas' => $siswa->id_kelas,
                        'id_detail_biaya' => $id_detail_biaya,
                        'besar_biaya' => $besar_biaya,
                        'denda_biaya' => 0,
                        'is_tagih' => 1,
                        'created_at' => $now,
                        'created_by' => $input->auth_data->pengguna->id_pengguna,
                        'updated_at' => $now,
                    );
                }

                DetailBiaya::insert($detail_biaya);
                TagihanBiaya::insert($tagihan);

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/setting-non-spp',
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status_code' => 300,
                    'status_text' => 'Failed',
                    'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                ]);
            }
        }
    }

    public function actionSaveInputPenerimaan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'tgl_realisasi' => 'required',
            'nm_realisasi' => 'required',
            'id_subkategori_rapb' => 'required',
            'dana_realisasi' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {
            $now = Carbon::today();

            $tahun_akademik_semester = $input->tahun;
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

            $tgl_realisasi = Carbon::parse($input->tgl_realisasi);
            $id_bulan = $tgl_realisasi->month;

            if ($id_bulan < 7) {
                $id_semester = $semester_selesai->id_semester;
            } else {
                $id_semester = $semester_mulai->id_semester;
            }

            DB::beginTransaction();
            try {
                $rapb = Rapb::where(['id_subkategori_rapb' => $input->id_subkategori_rapb, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester])->first();

                if ($rapb) { } else {
                    $rapb = new Rapb;
                    $rapb->id_rapb = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $rapb->id_semester_mulai = $semester_mulai->id_semester;
                    $rapb->id_semester_selesai = $semester_selesai->id_semester;
                    $rapb->id_subkategori_rapb = $input->id_subkategori_rapb;
                    $rapb->tgl_rapb = $now->format('Y-m-d');
                    $rapb->prioritas_rapb = 3;
                    $rapb->dana_perkiraan_rapb = 0;
                    $rapb->created_by = $input->auth_data->pengguna->id_pengguna;

                    if ($actor = Staff::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()) {
                        $rapb->id_unit_kerja = $actor->id_unit_kerja;
                    } else if ($actor = Guru::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()) {
                        $rapb->id_unit_kerja = $actor->id_unit_kerja;
                    }

                    if ($kepala_unit_keuangan = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                        ->where('guru.jenis_jabatan', '=', 2)
                        ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                        ->first()
                    ) {
                        $rapb->id_pengguna_kepala_unit = $kepala_unit_keuangan->id_pengguna;
                    } else if ($kepala_unit_keuangan = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                        ->where('staff.jenis_jabatan', '=', 2)
                        ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                        ->first()
                    ) {
                        $rapb->id_pengguna_kepala_keuangan = $kepala_unit_keuangan->id_pengguna;
                    }
                    $rapb->save();
                }

                if (isset($input->id_realisasi)) {
                    $realisasi = Realisasi::find($input->id_realisasi);
                } else {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $realisasi = new Realisasi;
                    $realisasi->id_realisasi = $id;
                    $realisasi->id_semester_realisasi = $id_semester;
                    $realisasi->id_rapb = $rapb->id_rapb;
                    $realisasi->created_by = $input->auth_data->pengguna->id_pengguna;
                    $realisasi->termin_dana_realisasi = 1;
                    $realisasi->is_hutang_realisasi = 0;
                }

                if ($actor = Staff::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()) {
                    $realisasi->id_unit_kerja = $actor->id_unit_kerja;
                } else if ($actor = Guru::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()) {
                    $realisasi->id_unit_kerja = $actor->id_unit_kerja;
                }
                $realisasi->nm_realisasi = $input->nm_realisasi;
                $realisasi->dana_realisasi = $input->dana_realisasi;
                $realisasi->tgl_realisasi = date_format(date_create($input->tgl_realisasi), "Y-m-d");
                $realisasi->save();

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/penerimaan',
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed',
                ]);
            }
        }
    }

    public function actionDeletePenerimaan(Request $request, $id)
    {
        $input = (object) $request->input();

        $pengeluaran = Realisasi::find($id);
        $pengeluaran->deleted_by = $input->auth_data->pengguna->id_pengguna;
        $pengeluaran->save();

        $pengeluaran->delete();

        return [
            'status' => 203,
            'message' => "Berhasil dihapus",
        ];
    }


    public function actionGetKeterangan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun_akademik_semester = $input->tahun_akademik_semester;
        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

        $data_pembayaran_tunggakan = PembayaranTunggakan::where('id_semester_mulai', $semester_mulai->id_semester)
            ->where('id_semester_selesai', $semester_selesai->id_semester)
            ->isInputByPengguna($auth_data->pengguna->id_pengguna)
            ->whereMonth('tgl_pembayaran', $input->id_bulan)
            ->get();


        return  $data_pembayaran_tunggakan;
    }


    public function actionSaveInputTunggakan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun_akademik_semester' => 'required',
            'tgl_pembayaran' => 'required',
            'keterangan' => 'required',
            'besar_pembayaran' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first(),
            ];
        } else {
            $now = Carbon::today();

            $tahun_akademik_semester = $input->tahun_akademik_semester;
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

            DB::beginTransaction();
            try {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $pembayaran_tunggakan = new PembayaranTunggakan;
                $pembayaran_tunggakan->id_pembayaran_tunggakan = $id;
                $pembayaran_tunggakan->id_semester_mulai = $semester_mulai->id_semester;
                $pembayaran_tunggakan->id_semester_selesai = $semester_selesai->id_semester;
                $pembayaran_tunggakan->besar_pembayaran = $input->besar_pembayaran;
                $pembayaran_tunggakan->tgl_pembayaran = date_format(date_create($input->tgl_pembayaran), "Y-m-d");
                $pembayaran_tunggakan->keterangan = $input->keterangan;
                $pembayaran_tunggakan->created_by = $input->auth_data->pengguna->id_pengguna;
                $pembayaran_tunggakan->save();

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/spp/tunggakan/' . $tahun_akademik_semester,
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed',
                ]);
            }
        }
    }

    public function printPembayaran(Request $request, $id)
    {
        $auth_data = $request->auth_data;

        if ($pembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna')->where('id_tagihan_biaya', $id)->first()) { } else {
            return abort(404);
        }

        $terbilang = LibDataKeuangan::getTerbilang($pembayaran->besar_pembayaran);
        return view('keuangan/sim/spp/print-pembayaran-spp', compact('auth_data', 'pembayaran', 'terbilang'));
    }

    public function getJumlahTunggakanPembayaran(Request $request)
    {
        $input = (object) $request->input();
        $semester = Semester::get();
        $id_kelas = $input->id_kelas;
        $tahun_akademik_semester = $input->tahun_akademik_semester;

        $data['data_tagihan_siswa_semester_lalu'] = [];
        $data['total_pembayar'] = [];
        $data['list_siswa'] = [];
        $data['list_bulan'] = [];

        if (!empty($id_kelas) && !empty($tahun_akademik_semester)) {
            $semester_mulai = $semester->firstWhere('kode_semester', $tahun_akademik_semester . '1');
            $semester_selesai = $semester->firstWhere('kode_semester', $tahun_akademik_semester . '2');

            $semesterMulai = $semester_mulai->id_semester;
            $semesterSelesai = $semester_selesai->id_semester;

            $data_pembayaran = PembayaranBiaya::whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($query) use ($semesterMulai, $semesterSelesai) {
                $query->whereIn('id_semester', [$semesterMulai, $semesterSelesai]);
            })->whereHas('tagihan_biaya', function ($query) use ($id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })->get();

            $bulan = Bulan::get();

            $total_pembayaran = collect($bulan)->mapWithKeys(function ($data_bulan) use ($data_pembayaran) {
                $data = $data_pembayaran->filter(function ($item) use ($data_bulan) {
                    return $item->tgl_pembayaran->format('m') == $data_bulan->kode_bulan;
                })->count();
                return [$data_bulan->id_bulan => $data];
            })->toArray();

            $list_id_semester_lalu = $semester->where('thn_akademik_semester', '<', $tahun_akademik_semester)->pluck('id_semester')->toArray();

            $list_siswa = TagihanBiaya::where('id_kelas', $id_kelas)
                ->whereHas('detail_biaya', function ($query) {
                    $query->where('validasi_biaya', 1)->where('id_jenis_detail_biaya', 4);
                })->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($semesterMulai, $semesterSelesai) {
                    $query->whereIn('id_semester', [$semesterMulai, $semesterSelesai]);
                })->distinct('id_siswa')->pluck('id_siswa')->toArray();

            $data_tagihan_siswa_semester_lalu = TagihanBiaya::selectRaw('id_siswa, COUNT(*) as jumlah_tagihan')->where('is_tagih', '1')->whereIn('id_siswa', $list_siswa)
                ->whereHas('detail_biaya', function ($query) {
                    $query->where('id_jenis_detail_biaya', '=', '4')->where('validasi_biaya', '1');
                })
                ->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($list_id_semester_lalu) {
                    $query->whereIn('id_semester',  $list_id_semester_lalu);
                })->groupBy('id_siswa')
                ->get();

            $data['data_tagihan_siswa_semester_lalu'] = $data_tagihan_siswa_semester_lalu;
            $data['total_pembayar'] =  $total_pembayaran;
        }

        return $data;
    }

    public function getDataTungakanTahunLalu(Request $request)
    {
        $input = (object) $request->input();
        $list_id_semester_lalu = Semester::where('thn_akademik_semester', '<', $input->tahun_akademik_semester)->pluck('id_semester')->toArray();
        $data_tagihan_siswa_semester_lalu = TagihanBiaya::with('detail_biaya.bulan', 'kelas')->where('is_tagih', '1')->where('id_siswa', $input->id_siswa)
            ->whereHas('detail_biaya', function ($query) {
                $query->where('id_jenis_detail_biaya', '=', '4')->where('validasi_biaya', '1');
            })
            ->whereHas('detail_biaya.biaya_sekolah', function ($query) use ($list_id_semester_lalu) {
                $query->whereIn('id_semester',  $list_id_semester_lalu);
            })->get();

        return $data_tagihan_siswa_semester_lalu;
    }


    public function deleteDataTungakanTahunLalu(Request $request, $id)
    {
        $input = (object) $request->input();

        $tagihan_biaya = TagihanBiaya::find($id);
        if ($tagihan_biaya) {
            $tagihan_biaya->deleted_by = $input->auth_data->pengguna->id_pengguna;
            $tagihan_biaya->save();
            $tagihan_biaya->delete(); //untuk semestara, jika sudah clear maka akan permanent delete
            return $id;
        } else {
            return false;
        }
    }


    public function viewMenuUploadTunggakanAlumni(Request $request)
    {
        // $input = (object) $request->input();
        return view('keuangan/sim/spp/view-menu-upload-tunggakan-alumni');
    }

    public function actionMenuUploadTunggakanAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($request->hasFile('file-excel')) {

            $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));

            // dd($data);

            $data = $data[0]; // Sheet 1
            if (count($data)) {
                // dd($data);

                DB::beginTransaction();
                try {
                    foreach ($data as $key => $item) {
                        $now = Carbon::now(env('APP_TIMEZONE', ''));
                        if (!empty($item["nis"])) {
                            if ($tunggakan = TunggakanAlumni::where('nis', $item["nis"])->first()) { } else {
                                $tunggakan = new TunggakanAlumni;
                                $tunggakan->id_tunggakan_alumni = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                $tunggakan->nis = $item["nis"];
                            }

                            $tunggakan->nm_siswa = $item["nama_siswa"];
                            $tunggakan->eks = $item["eks"];
                            $tunggakan->tahun_pelajaran = $item["tahun_pelajaran"];
                            $tunggakan->jumlah_tunggakan = $item["jumlah_tunggakan"];
                            $tunggakan->created_by = "upload excel";
                            $tunggakan->save();
                        }
                    }

                    DB::commit();
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'sim/spp/tunggakanAlumni/upload',
                        'message' => 'Upload Pembayaran Successfully',
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    // something went wrong
                    return [
                        'status' => 203, // GAGAL
                        'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                    ];
                }
            }
        }
    }

    public function deleteTunggakanAlumni(Request $request, $id, $selisih)
    {
        $input = (object) $request->input();

        $tagihan_biaya = TagihanBiaya::find($id);
        $id_siswa =  $tagihan_biaya->id_siswa;

        $selisih = str_replace(['Rp', ' ', ','], '', $selisih);
        $selisih = intval($selisih);

        $selisih = $selisih - $tagihan_biaya->besar_biaya;
        if ($selisih > 0) {
            $selisih = 'Rp ' . number_format($selisih);
        } else {
            $selisih = '0';
        }

        $tagihan_biaya->deleted_by = $input->auth_data->pengguna->id_pengguna;
        $tagihan_biaya->save();
        $tagihan_biaya->delete(); //untuk semestara, jika sudah clear maka akan permanent delete

        $data_tagihan_siswa_semester_lalu = TagihanBiaya::with('kelas', 'detail_biaya.bulan', 'detail_biaya.biaya_sekolah.semester')->where('is_tagih', '1')->where('id_siswa', $id_siswa)
            ->whereHas('detail_biaya', function ($query) {
                $query->where('id_jenis_detail_biaya', '=', '4')->where('validasi_biaya', '1');
            })
            ->whereHas('detail_biaya.biaya_sekolah')
            ->get();

        return [
            'status' => 200, // SUCCESS AND LOAD CONTENT
            'message' => 'Delete Tagihan Successfully',
            'data' => $data_tagihan_siswa_semester_lalu,
            'selisih' => $selisih,
        ];
    }


    public function tunggakanSudahDihapus(Request $request)
    {
        $auth_data = $request->auth_data;
        return view('keuangan/sim/spp/view-menu-tunggakan-sudah-dihapus', compact('auth_data'));
    }

    public function datatablesTunggakanSudahDihapus(Request $request)
    {
        $list_data = TagihanBiaya::onlyTrashed()->whereHas('detail_biaya')->with('detail_biaya.bulan', 'detail_biaya.biaya_sekolah.semester', 'detail_biaya.biaya_sekolah.kelompok', 'siswa.pengguna', 'kelas')->orderBy('deleted_at', 'desc');

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return  $item->detail_biaya->biaya_sekolah->semester->tahun_ajaran . ' ' . $item->detail_biaya->biaya_sekolah->semester->nm_semester;
            })
            ->editColumn('deleted_at', function ($item) {
                return Carbon::parse($item->deleted_at)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }
}
