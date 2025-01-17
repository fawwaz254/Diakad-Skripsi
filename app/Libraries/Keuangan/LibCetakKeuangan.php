<?php

namespace App\Libraries\Keuangan;

use App\Models\Biaya;
use App\Models\Bulan as Bulan;
use App\Models\Kelas;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTunggakan;
use App\Models\Rapb;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\SubkategoriRapb;
use App\Models\TutupBukuBulananBiaya;
use App\Models\TutupBukuBulananKas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Support\Facades\DB;

class LibCetakKeuangan
{

    public static function fetchLaporanBulananFull($auth_data, $start_date, $end_date)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

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

        if ($id_bulan < 7) {
            $id_semester = $id_semester_selesai;
        } else {
            $id_semester = $id_semester_mulai;
        }

        $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '1')->first()->id_semester;
        $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '2')->first()->id_semester;

        $kode_semester_mulai = $semester_mulai->kode_semester;

        if ($print_setting == 'self') {
            $where_personal = " AND kelas.created_by = '" . $auth_data->pengguna->id_pengguna . "'";
        } else {
            $where_personal = "";
        }

        // REVISI JML SISWA
        $list_data_jml_siswa = DB::select(
            'SELECT kelas.tingkat, COUNT(tagihan_biaya.id_tagihan_biaya) AS jml_siswa
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
                            ' . $where_personal . '
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $id_semester]
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
                            ' . $where_personal . '
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_bulan, $id_semester]
        );

        $periode_bulan_sekolah = collect([7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6]);

        if ($id_bulan < 7) {
            $index_splice = $id_bulan + 5;
            $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
            $index_periode_bulan_ini->all();
            $where_bayar_bulan_ini_dan_kedepannya = 'AND detail_biaya.id_bulan IN (' . $index_periode_bulan_ini->implode(',') . ')';
            $where_bayar_bulan_lalu_dan_belakangnya = 'AND detail_biaya.id_bulan IN (' . $periode_bulan_sekolah->implode(',') . ')';
        } else if ($id_bulan == 7) {
            $where_bayar_bulan_ini_dan_kedepannya = 'AND detail_biaya.id_bulan IN (7)';
            $where_bayar_bulan_lalu_dan_belakangnya = '';
        } else {
            $index_splice = $id_bulan - 7;
            $index_periode_bulan_ini = $periode_bulan_sekolah->splice($index_splice);
            $index_periode_bulan_ini->all();
            $where_bayar_bulan_ini_dan_kedepannya = 'AND detail_biaya.id_bulan IN (' . $index_periode_bulan_ini->implode(',') . ')';
            $where_bayar_bulan_lalu_dan_belakangnya = 'AND detail_biaya.id_bulan IN (' . $periode_bulan_sekolah->implode(',') . ')';
        }

        $list_data_pembayaran = DB::select(
            'SELECT kelas.tingkat, SUM(pembayaran_biaya.besar_pembayaran) AS jml_pembayaran_biaya
                            FROM pembayaran_biaya
                            JOIN tagihan_biaya ON tagihan_biaya.id_tagihan_biaya = pembayaran_biaya.id_tagihan_biaya
                                AND tagihan_biaya.deleted_at IS NULL
                            JOIN kelas ON kelas.id_kelas = tagihan_biaya.id_kelas
                                AND kelas.deleted_at IS NULL
                            JOIN detail_biaya ON detail_biaya.id_detail_biaya = tagihan_biaya.id_detail_biaya
                                AND detail_biaya.id_jenis_detail_biaya = 4
                                ' . $where_bayar_bulan_ini_dan_kedepannya . '
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester IN (?, ?)
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                                ' . $where_personal . '
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_semester_mulai, $id_semester_selesai, $tahun, $id_bulan]
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
                                ' . $where_bayar_bulan_lalu_dan_belakangnya . '
                                AND detail_biaya.deleted_at IS NULL
                            JOIN biaya_sekolah ON biaya_sekolah.id_biaya_sekolah = detail_biaya.id_biaya_sekolah
                                AND biaya_sekolah.id_semester IN (?, ?)
                                AND biaya_sekolah.deleted_at IS NULL
                            WHERE YEAR(pembayaran_biaya.tgl_pembayaran) = ?
                                AND MONTH(pembayaran_biaya.tgl_pembayaran) = ?
                                AND pembayaran_biaya.deleted_at IS NULL
                                ' . $where_personal . '
                            GROUP BY kelas.tingkat
                            ORDER BY kelas.tingkat',
            [$id_semester_mulai, $id_semester_selesai, $tahun, $id_bulan]
        );

        $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

        DB::beginTransaction();
        try {
            $tutup_buku_bulanan_kas_now = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

            if ($id_bulan_lalu < 7) {
                // Semester lama kurang dari bulan 7
                $tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
            } else {
                // Semester ini mulai bulan 7
                $tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
            }

            foreach ($list_data_jml_siswa as $i => $data) {
                if ($i == '0') {
                    // List data tunggakan
                    $pembayaran_tunggakan_bulan_ini = PembayaranTunggakan::where('id_semester_mulai', $semester_mulai->id_semester)
                        ->where('id_semester_selesai', $semester_selesai->id_semester)
                        ->whereMonth('tgl_pembayaran', $id_bulan)
                        ->isInputByPengguna($auth_data->pengguna->id_pengguna)->sum('besar_pembayaran') + PembayaranBiaya::whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                            $q->whereNotIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
                        })->whereMonth('tgl_pembayaran', $id_bulan)->whereYear('tgl_pembayaran', $tahun)->isInputByPengguna($auth_data->pengguna->id_pengguna)->sum('besar_pembayaran');
                }
                $tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                    'id_semester_mulai' => $id_semester_mulai,
                    'id_semester_selesai' => $id_semester_selesai,
                    'id_bulan' => $id_bulan,
                    'tingkat' => $data->tingkat,
                    'created_by' => $auth_data->pengguna->id_pengguna,
                ])->first();

                if ($id_bulan_lalu < 7) {
                    // Semester lama kurang dari bulan 7
                    $tutup_buku_bulanan_biaya_old = TutupBukuBulananBiaya::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu, 'id_bulan' => $id_bulan_lalu, 'tingkat' => $data->tingkat, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
                } else {
                    // Semester ini mulai bulan 7
                    $tutup_buku_bulanan_biaya_old = TutupBukuBulananBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu, 'tingkat' => $data->tingkat, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
                }

                if ($tutup_buku_bulanan_biaya) {
                } else {
                    $id = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                    $tutup_buku_bulanan_biaya = new TutupBukuBulananBiaya;
                    $tutup_buku_bulanan_biaya->id_tutup_buku_bulanan_biaya = $id;
                    $tutup_buku_bulanan_biaya->id_semester_mulai = $id_semester_mulai;
                    $tutup_buku_bulanan_biaya->id_semester_selesai = $id_semester_selesai;
                    $tutup_buku_bulanan_biaya->id_bulan = $id_bulan;
                    $tutup_buku_bulanan_biaya->tingkat = $data->tingkat;
                    $tutup_buku_bulanan_biaya->created_by = $auth_data->pengguna->id_pengguna;
                }

                $tutup_buku_bulanan_biaya->jml_siswa = $data->jml_siswa;

                $tagihan = collect($list_data_tagihan)->firstWhere('tingkat', $data->tingkat);
                $tutup_buku_bulanan_biaya->jml_tagihan_biaya = (!empty($tagihan) ? $tagihan->jml_tagihan_biaya : 0);

                $pembayaran = collect($list_data_pembayaran)->firstWhere('tingkat', $data->tingkat);
                $tutup_buku_bulanan_biaya->jml_pembayaran_biaya = (!empty($pembayaran) ? $pembayaran->jml_pembayaran_biaya : 0);

                // $tutup_buku_bulanan_biaya->jml_tunggakan_biaya    = $tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya;

                if ($id_bulan == 7) {
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = 0;
                    $tutup_buku_bulanan_biaya->jml_tunggakan_biaya = 0;
                } else {
                    $pembayaran_old_month = collect($list_data_pembayaran_old_month)->firstWhere('tingkat', $data->tingkat);
                    $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu = (!empty($pembayaran_old_month) ? $pembayaran_old_month->jml_pembayaran_biaya_bulan_lalu : 0);

                    if (!empty($tutup_buku_bulanan_biaya_old)) {
                        $tutup_buku_bulanan_biaya->jml_tunggakan_biaya = $tutup_buku_bulanan_biaya_old->jml_tunggakan_biaya + $tutup_buku_bulanan_biaya_old->jml_tagihan_biaya
                            - $tutup_buku_bulanan_biaya_old->jml_pembayaran_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu;
                    } else {
                        $tutup_buku_bulanan_biaya->jml_tunggakan_biaya = 0;
                    }
                }

                $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu = $pembayaran_tunggakan_bulan_ini;
                $pembayaran_tunggakan_bulan_ini = 0;

                $tutup_buku_bulanan_biaya->updated_by = $auth_data->pengguna->id_pengguna;

                if ($tutup_buku_bulanan_kas_now && $tutup_buku_bulanan_kas_now->is_fix == 1) {
                } else {
                    $tutup_buku_bulanan_biaya->save();
                }
            }

            $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where([
                'id_semester_mulai' => $id_semester_mulai,
                'id_semester_selesai' => $id_semester_selesai,
                'id_bulan' => $id_bulan,
                'created_by' => $auth_data->pengguna->id_pengguna,
            ])->get();

            $pembayaran_tunggakan_tahun_lalu_masuk_bulan_ini = $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu');

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
                ->whereMonth('tgl_realisasi', $id_bulan)
                ->whereYear('tgl_realisasi', $tahun);

            if ($print_setting == 'self') {
                $data_realisasi = $data_realisasi->isInputByPengguna($auth_data->pengguna->id_pengguna);
            }

            $data_realisasi = $data_realisasi->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')->get();

            if ($tutup_buku_bulanan_kas_now) {
                $tutup_buku_bulanan_kas_now->updated_by = $auth_data->pengguna->id_pengguna;
            } else {
                $tutup_buku_bulanan_kas_now = new TutupBukuBulananKas;
                $tutup_buku_bulanan_kas_now->id_tutup_buku_bulanan_kas = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $tutup_buku_bulanan_kas_now->id_semester_mulai = $id_semester_mulai;
                $tutup_buku_bulanan_kas_now->id_semester_selesai = $id_semester_selesai;
                $tutup_buku_bulanan_kas_now->id_bulan = $id_bulan;
                $tutup_buku_bulanan_kas_now->is_fix = 0;
                $tutup_buku_bulanan_kas_now->created_by = $auth_data->pengguna->id_pengguna;
            }
            $tutup_buku_bulanan_kas_now->kas_spp = $pembayaran_tunggakan_bulan_ini + $pembayaran_tunggakan_bulan_lalu + $pembayaran_tunggakan_tahun_lalu_masuk_bulan_ini;
            $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan = $data_realisasi->where('tipe_kategori_rapb', 1)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran = $data_realisasi->where('tipe_kategori_rapb', 2)->sum('total_realisasi');
            $tutup_buku_bulanan_kas_now->kas_akhir_bulan = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan - $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran + ($tutup_buku_bulanan_kas_old->kas_akhir_bulan ?? 0);
            $tutup_buku_bulanan_kas_now->sisa_tunggakan_biaya = ($tutup_buku_bulanan_kas_old->sisa_tunggakan_biaya ?? 0) - $pembayaran_tunggakan_tahun_lalu_masuk_bulan_ini;
            if ($tutup_buku_bulanan_kas_now->is_fix == 0) {
                $tutup_buku_bulanan_kas_now->save();
            }
            /* END INSERT TUTUP BUKU BULANAN KAS */

            // START SHOW Beban Non-KBM
            $subkategori_non_kbm = SubkategoriRapb::where('kode_subkategori_rapb', 'K.5.3')->where('nm_subkategori_rapb', 'Beban Pembelajaran Non KBM')->first();
            $subkategori_pengembangan_pendidikan = SubkategoriRapb::where('kode_subkategori_rapb', 'K.5.4')->where('nm_subkategori_rapb', 'Beban Pengembangan Pendidikan')->first();

            if ($subkategori_non_kbm && $subkategori_pengembangan_pendidikan) {
                $pembayaran_non_kbm = PembayaranBiaya::with('tagihan_biaya.kelas', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
                    ->whereMonth('tgl_pembayaran', $id_bulan)
                    ->whereYear('tgl_pembayaran', $tahun)
                    ->whereHas('tagihan_biaya.detail_biaya', function ($q) {
                        $q->where('id_jenis_detail_biaya', 4);
                    });
                if ($print_setting == 'self') {
                    $pembayaran_non_kbm = $pembayaran_non_kbm->where('pembayaran_biaya.created_by', $auth_data->pengguna->id_pengguna)->get();
                } else {
                    $pembayaran_non_kbm = $pembayaran_non_kbm->get();
                }
                $temp_data_bayar_non_kbm = [];
                $total_bayar_non_kbm = 0;

                //--
                $temp_data_pengembangan_pendidikan = [];
                $total_bayar_pengembangan_pendidikan = 0;


                foreach ($pembayaran_non_kbm as $data) {
                    // Get Biaya Internal (kelompok_biaya_internal)
                    $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;

                    if (!empty($biayaInternal)) {
                        $tingkat = $data->tagihan_biaya->kelas->tingkat;

                        $detailBiayaInternal = $biayaInternal->detail_biaya_internal;

                        foreach ($detailBiayaInternal as $x) {
                            if ($x->subkategori_rapb) {
                                if (!isset($temp_data_pengembangan_pendidikan[$x->nm_detail_biaya_internal][$tingkat])) {
                                    $temp_data_pengembangan_pendidikan[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                                } else {
                                    $temp_data_pengembangan_pendidikan[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                                }

                                if ($x->subkategori_rapb->kode_subkategori_rapb == 'K.5.4') {
                                    $total_bayar_pengembangan_pendidikan += $x->besar_biaya;
                                }
                            } else if ($x->nm_detail_biaya_internal == 'SPP MURNI' || $x->nm_detail_biaya_internal == 'LAIN-LAIN') {
                            } else {
                                if (!isset($temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat])) {
                                    $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                                } else {
                                    $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                                }

                                $total_bayar_non_kbm += $x->besar_biaya;
                            }
                        }
                    }
                }

                $subkategori_non_kbm = [
                    'status' => true,
                    'total_bayar' => $total_bayar_non_kbm,
                    'data' => $temp_data_bayar_non_kbm,
                ];

                $subkategori_pengembangan_pendidikan = [
                    'status' => true,
                    'total_bayar' => $total_bayar_pengembangan_pendidikan,
                    'data' => $temp_data_pengembangan_pendidikan,
                ];
                // Penambahan Pengeluaran post Beban non-KBM
                $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran += $total_bayar_non_kbm;
                $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran += $total_bayar_pengembangan_pendidikan;
                $tutup_buku_bulanan_kas_now->kas_akhir_bulan = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan - $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran + $tutup_buku_bulanan_kas_old->kas_akhir_bulan;
                if ($tutup_buku_bulanan_kas_now->is_fix == 0) {
                    $tutup_buku_bulanan_kas_now->save();
                }
            } else {
                $subkategori_non_kbm = [
                    'status' => false,
                ];
                $subkategori_pengembangan_pendidikan = [
                    'status' => false,
                ];
            }
            // END SHOW BEBAN NON-KBM

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            dd((env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() . '. In Line: ' . $e->getLine() : 'There is something wrong. Error Code ' . $e->getLine());
        }

        $data_tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan, 'created_by' => $auth_data->pengguna->id_pengguna])->orderBy('tingkat')->get();

        $data_realisasi = Rapb::selectRaw('
                                        nm_kategori_rapb,
                                        kode_subkategori_rapb,
                                        nm_subkategori_rapb,
                                        tipe_kategori_rapb,
                                        SUM(dana_realisasi) as total_realisasi,
                                        dana_perkiraan_rapb')
            ->leftJoin('realisasi', function ($q) use ($id_bulan, $tahun, $print_setting, $auth_data) {
                $q->on('realisasi.id_rapb', '=', 'rapb.id_rapb')
                    ->whereNull('realisasi.deleted_at')
                    ->whereMonth('realisasi.tgl_realisasi', $id_bulan)
                    ->whereYear('realisasi.tgl_realisasi', $tahun);

                if ($print_setting == 'self') {
                    $q->where('realisasi.created_by', $auth_data->pengguna->id_pengguna);
                }
            })
            ->join('subkategori_rapb', function ($q) {
                $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                    ->whereNull('subkategori_rapb.deleted_at');
            })
            ->join('kategori_rapb', function ($q) {
                $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                    ->whereNull('kategori_rapb.deleted_at');
            })
            ->where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])
            ->orderBy('subkategori_rapb.kode_subkategori_rapb');

        if ($print_setting == 'self') {
            $data_realisasi = $data_realisasi->isInputByPengguna($auth_data->pengguna->id_pengguna);
        }

        $data_realisasi = $data_realisasi->groupBy('realisasi.id_rapb', 'nm_kategori_rapb', 'kode_subkategori_rapb', 'nm_subkategori_rapb', 'tipe_kategori_rapb', 'dana_perkiraan_rapb')->get();
        $bulan = Bulan::find($id_bulan);
        $sekolah = $auth_data->sekolah_data;

        // $tutup_buku_tahun_ini = TutupBukuTahunanBiaya::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai])->firstOrFail();
        $tutup_buku_kas_bulan_ini = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan, 'created_by' => $auth_data->pengguna->id_pengguna])->firstOrFail();
        if ($id_bulan_lalu < 7) {
            // Semester lama kurang dari bulan 7
            $tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
        } else {
            // Semester ini mulai bulan 7
            $tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
        }

        $data = [
            'data_tutup_buku_bulanan_biaya' => $data_tutup_buku_bulanan_biaya,
            'data_realisasi' => $data_realisasi,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'sekolah' => $sekolah,
            // 'tutup_buku_tahun_ini' => $tutup_buku_tahun_ini,
            'tutup_buku_kas_bulan_ini' => $tutup_buku_kas_bulan_ini,
            'tutup_buku_kas_bulan_lalu' => $tutup_buku_kas_bulan_lalu,
            'subkategori_non_kbm' => $subkategori_non_kbm,
            'subkategori_pengembangan_pendidikan' => $subkategori_pengembangan_pendidikan,
        ];

        return $data;
    }

    public static function fetchLaporanBulananPerKategori($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $semester_pair = getIdSemesterByTanggal($start_date, 'Y-m-d');
        $id_bulan = $semester_pair->date_carbon->month;
        $tahun = $semester_pair->date_carbon->year;

        $dates = CarbonPeriod::create($start_date, $end_date);
        $tutup_buku_bulanan_biaya = TutupBukuBulananBiaya::query()
            ->where('id_semester_mulai', $semester_pair->ganjil->id_semester)
            ->where('id_semester_selesai', $semester_pair->genap->id_semester)
            ->where('id_bulan', $id_bulan)
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->first();

        $old_semester_pair = getIdSemesterByTanggal(Carbon::createFromFormat('Y-m-d', $start_date)->subMonth()->format('Y-m-d'), 'Y-m-d');
        $id_bulan_lalu = $old_semester_pair->date_carbon->month;

        $tutup_buku_kas_bulan_lalu = TutupBukuBulananKas::query()
            ->where('id_semester_mulai', $old_semester_pair->ganjil->id_semester)
            ->where('id_semester_selesai', $old_semester_pair->genap->id_semester)
            ->where('id_bulan', $id_bulan_lalu)
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->first();

        $pembayaran_spp = PembayaranBiaya::query()
            ->with('tagihan_biaya.detail_biaya.biaya_sekolah.semester')
            ->whereHas('tagihan_biaya.detail_biaya.biaya_sekolah', function ($q) use ($semester_pair) {
                $q->whereIn('id_semester', [$semester_pair->ganjil->id_semester, $semester_pair->genap->id_semester]);
            })
            ->whereMonth('tgl_pembayaran', $id_bulan)
            ->whereYear('tgl_pembayaran', $tahun)
            ->whereHas('tagihan_biaya.detail_biaya', function ($q) {
                $q->where('id_jenis_detail_biaya', 4);
            })
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->get();

        $semester_aktif = Semester::where('is_aktif_semester', '1')->first();
        $spp_tahun_lalu = $pembayaran_spp->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.tahun_ajaran', '!=', $semester_aktif->tahun_ajaran)->sum('besar_pembayaran');

        $realisasi_all = Realisasi::query()
            ->with('rapb', 'rapb.subkategori', 'rapb.subkategori.kategori')
            ->whereBetween('tgl_realisasi', [$start_date, $end_date])
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->get();

        $pembayaran_tunggakan = PembayaranTunggakan::query()
            ->whereMonth('tgl_pembayaran', $id_bulan)
            ->whereYear('tgl_pembayaran', $tahun)
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->orderBy('tgl_pembayaran', 'asc')
            ->get();

        $data_laporan = array();
        $no = 0;
        $master_saldo_masuk = 0;

        foreach ($dates as $dddd) {
            $data = array();
            $skip_this_date = true;
            $data['date_format'] = $dddd->format('Y-m-d');
            $data['day'] = $dddd->format('d');

            $data_in = array();
            // IF ONLY FIRST DATE
            if ($no == 0) {
                $data_temp['category'] = 'Lain-Lain';
                $data_temp['text'] = 'Saldo awal BULAN';
                $data_temp['value'] = $tutup_buku_kas_bulan_lalu->kas_akhir_bulan;
                $data_temp['id_subkategori_rapb'] = null;

                $data_in[] = $data_temp;
                $master_saldo_masuk += $data_temp['value'];
                $skip_this_date = false;
            } else {
                $data_temp['category'] = 'Lain-Lain';
                $data_temp['text'] = 'Saldo';
                $data_temp['value'] = $master_saldo_masuk;
                $data_temp['id_subkategori_rapb'] = null;

                $data_in[] = $data_temp;
            }

            // FOREACH FOR SPP
            $bayar_as_date = $pembayaran_spp->where('tgl_pembayaran', $data['date_format'] . ' 00:00:00')->all();
            if (count($bayar_as_date) > 0) {
                $bayar_as_date_as_nominal = collect($bayar_as_date)->groupBy(function ($item, $key) {
                    return 'SPP ' . number_format($item->besar_pembayaran);
                });
            } else {
                $bayar_as_date_as_nominal = array();
            }
            foreach ($bayar_as_date_as_nominal as $spp_category => $bayar_as_date) {
                $data_temp['category'] = 'SPP';
                $data_temp['text'] = $spp_category . ' x' . $bayar_as_date->count();
                $data_temp['value'] = $bayar_as_date->sum('besar_pembayaran');

                $data_in[] = $data_temp;
                $master_saldo_masuk += $data_temp['value'];
                $skip_this_date = false;
            }

            // FOR TUNGGAKAN TAHUN LALU
            // $bayar_as_date = $pembayaran_tunggakan->where('tgl_pembayaran', $data['date_format'] . ' 00:00:00')->all();
            // if (count($bayar_as_date) > 0) {
            //     $data_temp['category'] = 'SPP';
            //     $data_temp['text'] = 'Tunggakan SPP Tahun Lalu';
            //     $data_temp['value'] = collect($bayar_as_date)->sum('besar_pembayaran');

            //     $data_in[] = $data_temp;
            //     $master_saldo_masuk += $data_temp['value'];
            //     $skip_this_date = false;
            // }

            // FOREACH FOR REALISASI INCOME NON-SPP
            $realisasi_as_date = $realisasi_all->where('tgl_realisasi', $data['date_format'])->where('rapb.subkategori.kategori.tipe_kategori_rapb', 1)->all();
            foreach ($realisasi_as_date as $realisasi) {
                $data_temp['category'] = $realisasi->rapb->subkategori->deskripsi_subkategori_rapb;
                $data_temp['text'] = $realisasi->nm_realisasi;
                $data_temp['id_subkategori_rapb'] = $realisasi->rapb->subkategori->id_subkategori_rapb;
                $data_temp['value'] = $realisasi->dana_realisasi;

                $data_in[] = $data_temp;
                $master_saldo_masuk += $data_temp['value'];
                $skip_this_date = false;
            }

            $data['in'] = $data_in;

            $data_out = array();
            // FOREACH FOR REALISASI OUTCOME NON-SPP
            $realisasi_as_date = $realisasi_all->where('tgl_realisasi', $data['date_format'])->where('rapb.subkategori.kategori.tipe_kategori_rapb', 2)->all();
            foreach ($realisasi_as_date as $realisasi) {
                $data_temp['category'] = $realisasi->rapb->subkategori->deskripsi_subkategori_rapb;
                $data_temp['text'] = $realisasi->nm_realisasi;
                $data_temp['id_subkategori_rapb'] = $realisasi->rapb->subkategori->id_subkategori_rapb;
                $data_temp['value'] = $realisasi->dana_realisasi;

                $data_out[] = $data_temp;
                $skip_this_date = false;
            }

            $data['out'] = $data_out;
            // $data['spp_tahun_lalu'] = $spp_tahun_lalu;

            $no++;

            if (!$skip_this_date) {
                $data_laporan[] = $data;
            }
        }

        $pembayaran_non_kbm = PembayaranBiaya::query()
            ->with('tagihan_biaya.kelas', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
            ->whereBetween('tgl_pembayaran', [$start_date, $end_date])
            ->whereHas('tagihan_biaya.detail_biaya', function ($q) {
                $q->where('id_jenis_detail_biaya', 4);
            })
            ->when($print_setting == 'self', function ($q) use ($auth_data) {
                $q->where('created_by', $auth_data->pengguna->id_pengguna);
            })
            ->get();

        $temp_data_bayar_non_kbm = [];
        $total_bayar_non_kbm = 0;
        $temp_data_subkategori_rapb = [];
        $total_bayar_pengembangan_pendidikan = 0;

        foreach ($pembayaran_non_kbm as $data) {
            // Get Biaya Internal (kelompok_biaya_internal)
            $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;

            if (!empty($biayaInternal)) {
                $tingkat = $data->tagihan_biaya->kelas->tingkat;

                $detailBiayaInternal = $biayaInternal->detail_biaya_internal;

                foreach ($detailBiayaInternal as $x) {
                    if ($x->subkategori_rapb) {
                        if (!isset($temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal])) {
                            $temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal] = $x->besar_biaya;
                        } else {
                            $temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal] += $x->besar_biaya;
                        }

                        if ($x->subkategori_rapb->kode_subkategori_rapb == 'K.5.4') {
                            $total_bayar_pengembangan_pendidikan += $x->besar_biaya;
                        }
                    } else if ($x->nm_detail_biaya_internal == 'SPP MURNI' || $x->nm_detail_biaya_internal == 'LAIN-LAIN') {
                    } else {
                        if (!isset($temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat])) {
                            $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                        } else {
                            $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                        }

                        $total_bayar_non_kbm += $x->besar_biaya;
                    }
                }
            }
        }

        return [
            'bulan' => Bulan::find($id_bulan),
            'tahun' => $tahun,
            'report' => $data_laporan,
            'subkategori' => SubkategoriRapb::with('kategori')->orderBy('kode_subkategori_rapb')->get(),
            'tutup_buku_bulanan_biaya' => $tutup_buku_bulanan_biaya,
            'spp_tahun_lalu' => $spp_tahun_lalu,
            'total_bayar_non_kbm' => $total_bayar_non_kbm,
            'total_bayar_pengembangan_pendidikan' => $total_bayar_pengembangan_pendidikan,
        ];
    }

    /** Get Laporan Keuangan */
    public static function fetchLaporanKeuangan($auth_data, $start_date = null, $end_date = null)
    {
        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $dataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.siswa', 'tagihan_biaya.siswa.pengguna', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')->with(['tagihan_biaya.detail_biaya' => function ($q) {
            $q->with('biaya');
        }]);

        if (!empty($start_date) && !empty($end_date)) {
            $dataPembayaran = $dataPembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }
        $allDataPembayaran = $dataPembayaran->get();

        foreach ($allDataPembayaran as $x) {
            $date = new DateTime($x->tgl_pembayaran);

            if ($x->tagihan_biaya->detail_biaya->id_bulan === null) { // untuk non-SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . $x->tagihan_biaya->keterangan;

                $tempDataLaporan[] = [
                    'tanggal' => $date->format('Y-m-d'),
                    'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna . ' - ' . $keterangan,
                    'nominal' => $x->besar_pembayaran,
                    // 'tahun_ajaran' => ,
                    'nm_tipe' => 'debit',
                    'tipe' => 1, //penerimaan
                ];
            } else { // untuk SPP
                $keterangan = $x->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' - ' . Carbon::createFromFormat('m', $x->tagihan_biaya->detail_biaya->id_bulan)->format('F');

                foreach ($x->tagihan_biaya->detail_biaya->kelompok_biaya_internal->detail_biaya_internal as $spp) {
                    $tempDataLaporan[] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'keterangan' => $x->tagihan_biaya->siswa->pengguna->nm_pengguna . ' - ' . $keterangan . ' (' . $spp->nm_detail_biaya_internal . ')',
                        'nominal' => $spp->besar_biaya,
                        // 'tahun_ajaran' => ,
                        'nm_tipe' => 'debit',
                        'tipe' => 1, //penerimaan
                    ];
                }
            }
        }

        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }
        $allDataRealisasi = $dataRealisasi->get();

        foreach ($allDataRealisasi as $x) {
            $keterangan = $x->rapb->subkategori->kategori->nm_kategori_rapb . ' - ' . $x->nm_realisasi;
            $tipe = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'keterangan' => $keterangan,
                'nominal' => $x->dana_realisasi,
                // 'tahun_ajaran' => ,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'tipe' => $tipe,
            ];
        }

        // reordering by date descending
        foreach (collect($tempDataLaporan)->sortByDesc('tanggal') as $data) {
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');

        $data = [
            'laporan' => collect($dataLaporan)->sortByDesc('tanggal'),
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit,
        ];

        return $data;
    }

    public static function fetchLaporanPengeluaranByKategori($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];
        // $danaPembangunan['4%'] = null;
        // $danaPembangunan['7%'] = null;

        $allBiaya = Biaya::get();

        $dataRealisasi = Realisasi::query()
            ->with('rapb.subkategori.kategori')
            ->whereHas('rapb.subkategori.kategori', function ($q) {
                $q->where('tipe_kategori_rapb', 2);
            });

        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }

        if ($print_setting == 'self') {
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->sortBy('tgl_realisasi')->sortBy('rapb.subkategori.kode_subkategori_rapb');
        } else {
            $allDataRealisasi = $dataRealisasi->get()->sortBy('tgl_realisasi')->sortBy('rapb.subkategori.kode_subkategori_rapb');
        }

        $totalLaporan = $allDataRealisasi->sum('dana_realisasi');

        // START SHOW Beban Non-KBM
        $subkategori_non_kbm = SubkategoriRapb::where('kode_subkategori_rapb', 'K.5.3')->where('nm_subkategori_rapb', 'Beban Pembelajaran Non KBM')->first();

        if ($subkategori_non_kbm) {
            $pembayaran_non_kbm = PembayaranBiaya::with('tagihan_biaya.kelas', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal.subkategori_rapb')
                ->whereBetween('tgl_pembayaran', [$start_date, $end_date])
                ->whereHas('tagihan_biaya.detail_biaya', function ($q) {
                    $q->where('id_jenis_detail_biaya', 4);
                });


            if ($print_setting == 'self') {
                $pembayaran_non_kbm = $pembayaran_non_kbm->where('pembayaran_biaya.created_by', $auth_data->pengguna->id_pengguna)->get();
            } else {
                $pembayaran_non_kbm = $pembayaran_non_kbm->get();
            }
            $temp_data_bayar_non_kbm = [];
            $temp_data_subkategori_rapb = [];
            $total_bayar_non_kbm = 0;

            foreach ($pembayaran_non_kbm as $data) {
                // Get Biaya Internal (kelompok_biaya_internal)
                $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;

                if (!empty($biayaInternal)) {
                    $tingkat = $data->tagihan_biaya->kelas->tingkat;

                    $detailBiayaInternal = $biayaInternal->detail_biaya_internal;

                    foreach ($detailBiayaInternal as $x) {
                        if ($x->subkategori_rapb) {
                            if (!isset($temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal])) {
                                $temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal] = $x->besar_biaya;
                            } else {
                                $temp_data_subkategori_rapb[$x->subkategori_rapb->kode_subkategori_rapb . ' ' . $x->subkategori_rapb->nm_subkategori_rapb][$x->nm_detail_biaya_internal] += $x->besar_biaya;
                            }
                        } elseif ($x->nm_detail_biaya_internal == 'SPP MURNI' || $x->nm_detail_biaya_internal == 'LAIN-LAIN') {
                        } else {
                            if (!isset($temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat])) {
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] = $x->besar_biaya;
                            } else {
                                $temp_data_bayar_non_kbm[$x->nm_detail_biaya_internal][$tingkat] += $x->besar_biaya;
                            }

                            $total_bayar_non_kbm += $x->besar_biaya;
                        }
                    }
                }
            }

            $subkategori_non_kbm = [
                'status' => true,
                'total_bayar' => $total_bayar_non_kbm,
                'data' => $temp_data_bayar_non_kbm,
                'data_with_subkategori_rapb' => $temp_data_subkategori_rapb,
            ];
        } else {
            $subkategori_non_kbm = [
                'status' => false,
            ];
        }


        // END SHOW BEBAN NON-KBM
        $data = [
            'data' => $allDataRealisasi->groupBy(function ($item, $key) {
                return $item->rapb->subkategori->kode_subkategori_rapb . ' ' . $item->rapb->subkategori->nm_subkategori_rapb;
            }),
            'total_data' => $totalLaporan,
            'subkategori_non_kbm' => $subkategori_non_kbm,
            'tingkat' => Kelas::select('tingkat')->orderBy('tingkat')->distinct()->get()->pluck('tingkat'),
            // 'danaPembangunan' => $danaPembangunan,
        ];

        return $data;
    }

    public static function fetchLaporanKasInternal($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $allBiaya = Biaya::get();

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.potongan.detail_potongan', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $allDataPembayaran = $pembayaran->get();
        }

        // find Pembayaran in each Biaya
        foreach ($allBiaya as $kategori) {
            // pembayaran for this Biaya per TA
            $pembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.biaya.nm_biaya', '=', $kategori->nm_biaya)->groupBy('tagihan_biaya.detail_biaya.biaya_sekolah.semester.thn_akademik_semester');

            foreach ($pembayaran as $ta => $value) {
                $stringNmBiaya = $value->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya;

                // loop each pembayaran
                $val = $value;
                foreach ($value as $data) {
                    // Get Biaya Internal (kelompok_biaya_internal)
                    $biayaInternal = $data->tagihan_biaya->detail_biaya->kelompok_biaya_internal;
                    // === var for $tempDataLaporan
                    $date = new DateTime($data->tgl_pembayaran);
                    $ket_biaya = $data->tagihan_biaya->detail_biaya->keterangan_biaya;
                    $count = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                        ->filter(function ($q) use ($date) {
                            $qDate = new DateTime($q->tgl_pembayaran);
                            return $qDate->format('Y-m-d') == $date->format('Y-m-d');
                        })->count();
                    $keyTempData = $kategori->nm_biaya . '-' . $ket_biaya . '-' . $ta;
                    $tahun_ajaran = $data->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                    // ===

                    if (!empty($biayaInternal)) {
                        $detailBiayaInternal = $biayaInternal->detail_biaya_internal;
                        $nominal = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)->filter(function ($q) use ($date) {
                            $qDate = new DateTime($q->tgl_pembayaran);
                            return $qDate->format('Y-m-d') == $date->format('Y-m-d');
                        });
                        $nominalPembayaran = $nominal->sum('besar_pembayaran');
                        // dd($nominal);
                        foreach ($detailBiayaInternal as $x) {
                            // dd($x, $data);
                            $potongan = $data->tagihan_biaya->potongan;

                            if (!empty($potongan)) {
                                $potongan_biaya = $potongan->detail_potongan
                                    ->where('id_detail_biaya_internal', $x->id_detail_biaya_internal)
                                    ->sum('potongan_biaya');
                            } else {
                                $potongan_biaya = 0;
                            }
                            $sisa = $nominalPembayaran -= ($x->besar_biaya - $potongan_biaya);
                            $tempDataLaporan[$keyTempData . $x->nm_detail_biaya_internal] = [
                                'tanggal' => $date->format('Y-m-d'),
                                'nominal' => ($sisa > ($x->besar_biaya - $potongan_biaya))
                                    ? ($x->besar_biaya - $potongan_biaya)
                                    : $x->besar_biaya + $sisa,
                                'potongan' => $potongan_biaya,
                                'frekuensi' => $count,
                                'tipe' => 1,
                                'nm_tipe' => 'debit',
                                'kategori' => $stringNmBiaya,
                                'keterangan' => $x->nm_detail_biaya_internal . ' ' . $count . 'x ' . number_format($x->besar_biaya) . ' (' . $tahun_ajaran . ')',
                                'tahun_ajaran' => $tahun_ajaran,
                            ];
                        }
                        // dd($tempDataLaporan);
                    } else {
                        $nominal = $val->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)->filter(function ($q) use ($date) {
                            $qDate = new DateTime($q->tgl_pembayaran);
                            return $qDate->format('Y-m-d') == $date->format('Y-m-d');
                        });

                        $tempDataLaporan[$keyTempData . $date->format('Y-m-d')] = [
                            'tanggal' => $date->format('Y-m-d'),
                            'nominal' => $nominal->sum('besar_pembayaran'),
                            'potongan' => $val->where('tagihan_biaya.detail_biaya.biaya.nm_biaya', $kategori->nm_biaya)
                                ->where('tagihan_biaya.detail_biaya.keterangan_biaya', '=', $ket_biaya)
                                ->where('tagihan_biaya.detail_biaya.biaya_sekolah.semester.thn_akademik_semester', '=', $ta)
                                ->sum('tagihan_biaya.potongan.total_potongan'),
                            'frekuensi' => $count,
                            'tipe' => 1,
                            'nm_tipe' => 'debit',
                            'kategori' => $stringNmBiaya,
                            'keterangan' => $ket_biaya . ' ' . $count . 'x (' . $tahun_ajaran . ')',
                            'tahun_ajaran' => $tahun_ajaran,
                        ];
                    }
                }
            }
        }

        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }

        if ($print_setting == 'self') {
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $allDataRealisasi = $dataRealisasi->get();
        }

        foreach ($allDataRealisasi as $x) {
            $kategori = $x->rapb->subkategori->kategori->nm_kategori_rapb;
            $keterangan = $x->nm_realisasi;
            $tipe = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'nominal' => $x->dana_realisasi,
                'potongan' => null,
                'frekuensi' => null,
                'tipe' => $tipe,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'kategori' => $kategori,
                'keterangan' => ($keterangan == '-' || $keterangan == null) ? $kategori : $keterangan,
                'tahun_ajaran' => null,
            ];
        }

        // reordering by date descending
        foreach (collect($tempDataLaporan)->sortBy('tanggal') as $data) {
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');

        $data = [
            'laporan' => $dataLaporan,
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit,
        ];

        return $data;
    }

    public static function fetchLaporanKasReguler($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $dataLaporan = []; // tgl, keterangan, tipe (debit/kredit), nominal
        $tempDataLaporan = [];

        $dates = CarbonPeriod::create($start_date, $end_date);

        $data_pembayaran = PembayaranBiaya::with(
            'tagihan_biaya',
            'tagihan_biaya.potongan',
            'tagihan_biaya.detail_biaya',
            'tagihan_biaya.detail_biaya.biaya',
            'tagihan_biaya.detail_biaya.biaya_sekolah.semester'
        );
        if (!empty($start_date) && !empty($end_date)) {
            $data_pembayaran = $data_pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $data_pembayaran = $data_pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $data_pembayaran = $data_pembayaran->get();
        }

        foreach ($dates as $date) {
            $filter_pembayaran = $data_pembayaran->where('tgl_pembayaran', '>=', $date->format('Y-m-d') . ' 00:00:00')->where('tgl_pembayaran', '<=', $date->format('Y-m-d') . ' 23:59:00')->values();

            $frekuensi = array();
            $nominal = array();
            $potongan = array();

            $kategori_biaya = array();
            $keterangan = array();
            $tahun_ajaran = array();

            foreach ($filter_pembayaran as $pembayaran) {
                if ($pembayaran->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4) { // SPP
                    $keyTempData = $pembayaran->tagihan_biaya->detail_biaya->biaya->nm_biaya . '-' . $pembayaran->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                } else { // Non-SPP
                    $keyTempData = $pembayaran->tagihan_biaya->detail_biaya->biaya->nm_biaya . '-' . $pembayaran->tagihan_biaya->detail_biaya->keterangan_biaya . '-' . $pembayaran->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                }

                $key_id = $keyTempData . $date->format('Y-m-d');

                if (isset($frekuensi[$key_id])) {
                    $frekuensi[$key_id] += 1;
                    $nominal[$key_id] += $pembayaran->besar_pembayaran;
                    if (!empty($pembayaran->tagihan_biaya->id_potongan_biaya)) {
                        $potongan[$key_id] += $pembayaran->tagihan_biaya->potongan->total_potongan;
                    }
                } else {
                    $frekuensi[$key_id] = 1;
                    $nominal[$key_id] = $pembayaran->besar_pembayaran;
                    if (!empty($pembayaran->tagihan_biaya->id_potongan_biaya)) {
                        $potongan[$key_id] = $pembayaran->tagihan_biaya->potongan->total_potongan;
                    } else {
                        $potongan[$key_id] = 0;
                    }
                }

                $keterangan[$key_id] = $keyTempData;
                $kategori_biaya[$key_id] = $pembayaran->tagihan_biaya->detail_biaya->biaya->nm_biaya;
                $tahun_ajaran[$key_id] = $pembayaran->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
            }

            foreach ($frekuensi as $key => $count) {
                $tempDataLaporan[$key] = [
                    'tanggal' => $date->format('Y-m-d'),
                    'nominal' => $nominal[$key],
                    'frekuensi' => $count,
                    'potongan' => $potongan[$key],
                    'tipe' => 1,
                    'nm_tipe' => 'debit',
                    'kategori' => $kategori_biaya[$key],
                    'keterangan' => $keterangan[$key] . ' ' . $count . 'x (' . $tahun_ajaran[$key] . ')',
                    'tahun_ajaran' => $tahun_ajaran[$key],
                ];
            }
        }

        $kas_spp = collect($tempDataLaporan)->sum('nominal');

        $dataRealisasi = Realisasi::with('rapb.subkategori.kategori');
        if (!empty($start_date) && !empty($end_date)) {
            $dataRealisasi = $dataRealisasi->whereBetween('tgl_realisasi', [$start_date, $end_date]);
        }

        if ($print_setting == 'self') {
            $allDataRealisasi = $dataRealisasi->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $allDataRealisasi = $dataRealisasi->get();
        }

        $kas_rapb_penerimaan = $allDataRealisasi->where('rapb.subkategori.kategori.tipe_kategori_rapb', 1)->sum('dana_realisasi');
        $kas_rapb_pengeluaran = $allDataRealisasi->where('rapb.subkategori.kategori.tipe_kategori_rapb', 2)->sum('dana_realisasi');

        foreach ($allDataRealisasi as $x) {
            $kategori = $x->rapb->subkategori->kategori->nm_kategori_rapb;
            $keterangan = $x->nm_realisasi;
            $tipe = $x->rapb->subkategori->kategori->tipe_kategori_rapb;
            $date = new DateTime($x->tgl_realisasi);
            $tempDataLaporan[] = [
                'tanggal' => $date->format('Y-m-d'),
                'nominal' => $x->dana_realisasi,
                'potongan' => null,
                'frekuensi' => null,
                'tipe' => $tipe,
                'nm_tipe' => $tipe == 1 ? 'debit' : 'kredit',
                'kategori' => $kategori,
                'keterangan' => ($keterangan == '-' || $keterangan == null) ? $kategori : $keterangan,
                'tahun_ajaran' => null,
            ];
        }

        // reordering by date descending
        foreach (collect($tempDataLaporan)->sortBy('tanggal') as $data) {
            $dataLaporan[] = $data;
        }

        $totalDebit = collect($dataLaporan)->where('tipe', 1)->sum('nominal');
        $totalKredit = collect($dataLaporan)->where('tipe', 2)->sum('nominal');

        $data = [
            'laporan' => $dataLaporan,
            'total_debit' => $totalDebit,
            'total_kredit' => $totalKredit,
        ];

        $now = Carbon::now(env('APP_TIMEZONE', 'Asia/Jakarta'));

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

        $id_semester_mulai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '1')->first()->id_semester;
        $id_semester_selesai_tahun_lalu = Semester::where('kode_semester', $tahun_lalu . '2')->first()->id_semester;

        $semester_mulai = Semester::where('kode_semester', $tahun_semester . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_semester . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        if ($id_bulan_lalu < 7) {
            // Semester lama kurang dari bulan 7
            $tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai_tahun_lalu, 'id_semester_selesai' => $id_semester_selesai_tahun_lalu, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
        } else {
            // Semester ini mulai bulan 7
            $tutup_buku_bulanan_kas_old = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan_lalu, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
        }

        if ($tutup_buku_bulanan_kas_now = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'id_bulan' => $id_bulan, 'created_by' => $auth_data->pengguna->id_pengguna])->first()) {
            $tutup_buku_bulanan_kas_now->updated_by = $auth_data->pengguna->id_pengguna;
        } else {
            $tutup_buku_bulanan_kas_now = new TutupBukuBulananKas;
            $tutup_buku_bulanan_kas_now->id_tutup_buku_bulanan_kas = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $tutup_buku_bulanan_kas_now->id_semester_mulai = $id_semester_mulai;
            $tutup_buku_bulanan_kas_now->id_semester_selesai = $id_semester_selesai;
            $tutup_buku_bulanan_kas_now->id_bulan = $id_bulan;
            $tutup_buku_bulanan_kas_now->created_by = $auth_data->pengguna->id_pengguna;
        }
        $tutup_buku_bulanan_kas_now->kas_spp = $kas_spp;
        $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan = $kas_rapb_penerimaan;
        $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran = $kas_rapb_pengeluaran;
        $tutup_buku_bulanan_kas_now->kas_akhir_bulan = $tutup_buku_bulanan_kas_now->kas_spp + $tutup_buku_bulanan_kas_now->kas_rapb_penerimaan - $tutup_buku_bulanan_kas_now->kas_rapb_pengeluaran + ($tutup_buku_bulanan_kas_old->kas_akhir_bulan ?? 0);
        $tutup_buku_bulanan_kas_now->save();

        return $data;
    }

    public static function fetchLaporanPembayaranPerSiswaOnline($auth_data, $start_date = null, $end_date = null)
    {

        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')->whereNotNull('nomor_transaksi');

        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        }

        $listData = [];
        $ketTagihan = '';
        $idBiaya = null;

        foreach ($allDataPembayaran as $idSiswa => $siswa) {

            $tagihanBiayaSiswa = $siswa->first()->tagihan_biaya;

            $summ = [];

            foreach ($siswa->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya') as $idBiaya => $rwytBayar) {

                $item = $rwytBayar->first();

                if ($idBiaya != $item->tagihan_biaya->detail_biaya->biaya->id_biaya) {
                    $ketTagihan = ''; // reset
                }

                $idBiaya = $item->tagihan_biaya->detail_biaya->biaya->id_biaya;
                $ketTagihan = $ketTagihan . (empty($ketTagihan) ? '' : ', ') . $item->tagihan_biaya->keterangan;

                if ($item->tagihan_biaya->siswa->kelas) {
                    $nm_kelas = $item->tagihan_biaya->siswa->kelas->nm_kelas;
                } else {
                    $nm_kelas = '-';
                }

                $summ[] = [
                    'id_siswa' => $idSiswa,
                    'id_biaya' => $idBiaya,
                    'nis_siswa' => $item->tagihan_biaya->siswa->nis_siswa,
                    'nm_siswa' => $item->tagihan_biaya->siswa->pengguna->nm_pengguna,
                    'kelas_siswa' => $nm_kelas,
                    'total_nominal_pembayaran' => $rwytBayar->sum('besar_pembayaran'),
                    'total_potongan_biaya' => $siswa->where('tagihan_biaya.detail_biaya.biaya.id_biaya', $idBiaya)->sum('tagihan_biaya.potongan.total_potongan'),
                    'frekuensi_pembayaran' => $rwytBayar->count(),
                    'kategori_biaya' => $item->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'keterangan_tagihan' => $item->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4 ? $item->tagihan_biaya->keterangan : $ketTagihan,
                    'keterangan_biaya' => $item->tagihan_biaya->detail_biaya->biaya->keterangan_biaya,
                    'tahun_ajaran_tagihan' => $item->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran,
                ];
            }

            if ($tagihanBiayaSiswa->siswa->kelas) {
                $nm_kelas2 = $tagihanBiayaSiswa->siswa->kelas->nm_kelas;
            } else {
                $nm_kelas2 = '-';
            }

            $listData[] = [
                'id_siswa' => $idSiswa,
                'nis_siswa' => $tagihanBiayaSiswa->siswa->nis_siswa,
                'nm_siswa' => $tagihanBiayaSiswa->siswa->pengguna->nm_pengguna,
                'kelas_siswa' => $nm_kelas2,
                'potongan_biaya' => $siswa->sum('tagihan_biaya.potongan.total_potongan'),
                'summary' => $summ,
            ];
        }

        $allPembayaranBiaya = $pembayaran->get()->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya');
        $summaryData = [];
        foreach ($allPembayaranBiaya as $idBiaya => $biaya) {
            $summaryData[] = [
                'id_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                'nm_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                'frekuensi' => $biaya->count(),
                'id_jenis_detail_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->id_jenis_detail_biaya,
                'total_pembayaran' => $biaya->sum('besar_pembayaran'),
                'total_potongan_biaya' => $biaya->where('tagihan_biaya.detail_biaya.biaya.id_biaya', $idBiaya)->sum('tagihan_biaya.potongan.total_potongan'),
            ];
        }

        $result = [
            'data' => $listData,
            'summary' => $summaryData,
            'kategori_biaya' => collect($summaryData)->pluck('nm_biaya', 'id_biaya'),
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerKategori($auth_data, $start_date = null, $end_date = null)
    {

        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.detail_biaya.biaya');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy('tagihan_biaya.detail_biaya.keterangan_biaya', 'tagihan_biaya.detail_biaya.id_biaya');
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy('tagihan_biaya.detail_biaya.keterangan_biaya', 'tagihan_biaya.detail_biaya.id_biaya');
        }

        $listData = [];

        foreach ($allDataPembayaran as $key => $value) {

            $listData[$key]['nama'] = $key;
            $listData[$key]['total'] = $value->sum('besar_pembayaran');
        }

        return $listData;
    }

    public static function fetchLaporanPembayaranPerSiswa($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        if (empty(session('setting_print_keuangan2'))) {
            $print_setting2 = 'semua';
        } else {
            $print_setting2 = session('setting_print_keuangan2');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.bulan', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
            ->whereHas('tagihan_biaya.detail_biaya', function ($query) use ($print_setting2) {
                if ($print_setting2 == 'spp') {
                    return $query->where('id_jenis_detail_biaya', '=', 4);
                } elseif ($print_setting2 == 'lain') {
                    return $query->where('id_jenis_detail_biaya', '!=', 4);
                }
            });

        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy('tagihan_biaya.siswa.id_siswa');
        }

        $listData = [];
        $ketTagihan = '';
        $idBiaya = null;
        foreach ($allDataPembayaran as $idSiswa => $siswa) {
            // dd($siswa);
            $tagihanBiayaSiswa = $siswa->first()->tagihan_biaya;
            // dd($tagihanBiayaSiswa);

            $summ = [];
            foreach ($siswa->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya') as $idBiaya => $rwytBayar) {

                $item = $rwytBayar->first();

                if ($idBiaya != $item->tagihan_biaya->detail_biaya->biaya->id_biaya) {
                    $ketTagihan = ''; // reset
                }
                $idBiaya = $item->tagihan_biaya->detail_biaya->biaya->id_biaya;
                $ketTagihan = $ketTagihan . (empty($ketTagihan) ? '' : ', ') . $item->tagihan_biaya->keterangan;

                if ($item->tagihan_biaya->siswa->kelas) {
                    $nm_kelas = $item->tagihan_biaya->siswa->kelas->nm_kelas;
                } else {
                    $nm_kelas = '-';
                }

                $summ[] = [
                    'id_siswa' => $idSiswa,
                    'id_biaya' => $idBiaya,
                    'nis_siswa' => $item->tagihan_biaya->siswa->nis_siswa,
                    'nm_siswa' => $item->tagihan_biaya->siswa->pengguna->nm_pengguna,
                    'kelas_siswa' => $nm_kelas,
                    'detail_biaya' => $rwytBayar,
                    'total_nominal_pembayaran' => $rwytBayar->sum('besar_pembayaran'),
                    'total_potongan_biaya' => $siswa->where('tagihan_biaya.detail_biaya.biaya.id_biaya', $idBiaya)->sum('tagihan_biaya.potongan.total_potongan'),
                    'frekuensi_pembayaran' => $rwytBayar->count(),
                    'kategori_biaya' => $item->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'keterangan_tagihan' => $item->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4 ? $item->tagihan_biaya->keterangan : $ketTagihan,
                    'keterangan_biaya' => $item->tagihan_biaya->detail_biaya->biaya->keterangan_biaya,
                    'tahun_ajaran_tagihan' => $item->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran,
                    'test' => $ketTagihan,

                ];
            }

            if ($tagihanBiayaSiswa->siswa->kelas) {
                $nm_kelas2 = $tagihanBiayaSiswa->siswa->kelas->nm_kelas;
            } else {
                $nm_kelas2 = '-';
            }

            $listData[] = [
                'id_siswa' => $idSiswa,
                'nis_siswa' => $tagihanBiayaSiswa->siswa->nis_siswa,
                'nm_siswa' => $tagihanBiayaSiswa->siswa->pengguna->nm_pengguna,
                'kelas_siswa' => $nm_kelas2,
                'potongan_biaya' => $siswa->sum('tagihan_biaya.potongan.total_potongan'),
                'summary' => $summ,
            ];
        }

        $allPembayaranBiaya = $pembayaran->get()->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya');
        $summaryData = [];
        foreach ($allPembayaranBiaya as $idBiaya => $biaya) {
            $summaryData[] = [
                'id_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                'nm_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                'frekuensi' => $biaya->count(),
                'id_jenis_detail_biaya' => $biaya->first()->tagihan_biaya->detail_biaya->id_jenis_detail_biaya,
                'total_pembayaran' => $biaya->sum('besar_pembayaran'),
                // 'test'=> $biaya->where('tagihan_biaya.detail_biaya.biaya.id_biaya', $idBiaya)->first(),
                'total_potongan_biaya' => $biaya->where('tagihan_biaya.detail_biaya.biaya.id_biaya', $idBiaya)->sum('tagihan_biaya.potongan.total_potongan'),
            ];

            // $test = $pembayaran->get()->groupBy('tagihan_biaya.keterangan')->map(function ($item) {
            //     return $item->count();
            // });;

        }

        // $test = $pembayaran->get()->groupBy('tagihan_biaya.keterangan');
        // $test1 = $pembayaran->get()->groupBy('tagihan_biaya.keterangan')->map(function ($item){
        //     return $item->count();
        // });
        $total = [];
        foreach ($pembayaran->get()->groupBy('tagihan_biaya.keterangan') as $idBiaya => $biaya) {

            $total[] = [
                'nama' => $biaya->first()->tagihan_biaya->detail_biaya->id_jenis_detail_biaya != 4 ? $biaya->first()->tagihan_biaya->keterangan : "SPP",
                'total' => $biaya->count(),
                'nominal' => $biaya->where('tagihan_biaya.keterangan', $idBiaya)->sum('besar_pembayaran'),
                // 'nominal' => $biaya->first()->tagihan_biaya->besar_biaya,
            ];
        }

        $result = [
            'data' => $listData,
            'summary' => $summaryData,
            'kategori_biaya' => collect($summaryData)->pluck('nm_biaya', 'id_biaya'),
            'jumlah' => $total,
            // 'frekuensi' => collect($summaryData)->pluck('total'),
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerKelas($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        if (empty(session('setting_print_keuangan2'))) {
            $print_setting2 = 'semua';
        } else {
            $print_setting2 = session('setting_print_keuangan2');
        }

        $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya.kelas', 'tagihan_biaya.potongan', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.detail_biaya.bulan', 'tagihan_biaya.detail_biaya.biaya')
            ->whereHas('tagihan_biaya.detail_biaya', function ($query) use ($print_setting2) {
                if ($print_setting2 == 'spp') {
                    return $query->where('id_jenis_detail_biaya', '=', 4);
                } elseif ($print_setting2 == 'lain') {
                    return $query->where('id_jenis_detail_biaya', '!=', 4);
                }
            });

        if (!empty($start_date) && !empty($end_date)) {
            $allDataPembayaran = $allDataPembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $allDataPembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $allDataPembayaran = $allDataPembayaran->get();
        }

        $result = [
            'data' => $allDataPembayaran,
            'jenis_bayar' => $allDataPembayaran->sortBy('tagihan_biaya.detail_biaya.id_bulan')->values()->groupBy(function ($item, $key) {
                if (!empty($item->tagihan_biaya->detail_biaya->id_bulan)) {
                    return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya . ' ' . $item->tagihan_biaya->detail_biaya->bulan->nm_bulan;
                } else {
                    return $item->tagihan_biaya->detail_biaya->biaya->nm_biaya;
                }
            }),
        ];
        // dd($result);
        return $result;
    }

    public static function fetchLaporanPembayaranPerTingkat($auth_data, $start_date = null, $end_date = null)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        if (empty(session('setting_print_keuangan2'))) {
            $print_setting2 = 'semua';
        } else {
            $print_setting2 = session('setting_print_keuangan2');
        }

        if ($print_setting2 == 'spp') {
            $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.kelas')->whereHas('tagihan_biaya.detail_biaya.biaya', function ($query) {
                $query->where('nm_biaya', 'SPP');
            });
        } elseif ($print_setting2 == 'lain') {
            $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.kelas')->whereHas('tagihan_biaya.detail_biaya.biaya', function ($query) {
                $query->where('nm_biaya', '!=', 'SPP');
            });
        } else {
            $allDataPembayaran = PembayaranBiaya::with('tagihan_biaya', 'tagihan_biaya.detail_biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.kelas');
        }

        $allDataPembayaranTunggakan = PembayaranTunggakan::query();

        if (!empty($start_date) && !empty($end_date)) {
            $allDataPembayaran = $allDataPembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
            $allDataPembayaranTunggakan = $allDataPembayaranTunggakan->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        // if($print_setting2 == 'spp'){
        //     $allDataPembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.id_jenis_detail_biaya', '=', 4)->get();
        //     // $allDataPembayaran = $allDataPembayaran->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //     //     $query->where('id_jenis_detail_biaya', 4);
        //     // });
        //     // $allDataPembayaranTunggakan = $allDataPembayaranTunggakan->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //     //     $query->where('id_jenis_detail_biaya', 4);
        //     // });
        // }elseif($print_setting2== 'lain'){
        //     $allDataPembayaran = $allDataPembayaran->where('tagihan_biaya.detail_biaya.id_jenis_detail_biaya','!=' ,4)->get();
        //     // $allDataPembayaranTunggakan =  $allDataPembayaranTunggakan->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //     //     $query->where('id_jenis_detail_biaya', '!=' , 4);
        //     // });
        //     // $allDataPembayaranTunggakan =  $allDataPembayaranTunggakan->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //     //     $query->where('id_jenis_detail_biaya', '!=' , 4);
        //     // });
        // }

        if ($print_setting == 'self') {
            $allDataPembayaran = $allDataPembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
            $allDataPembayaranTunggakan = $allDataPembayaranTunggakan->isInputByPengguna($auth_data->pengguna->id_pengguna)->get();
        } else {
            $allDataPembayaran = $allDataPembayaran->get();
            $allDataPembayaranTunggakan = $allDataPembayaranTunggakan->get();
        }

        $sc_bulan = Carbon::createFromFormat('Y-m-d', $start_date);
        $id_bulan = $sc_bulan->month;

        $sc_tahun = Carbon::createFromFormat('Y-m-d', $start_date);
        $tahun = $sc_tahun->year;

        if ($id_bulan < 7) {
            $tahun_semester = $tahun - 1;
        } else {
            $tahun_semester = $tahun;
        }

        $result = [
            'data' => $allDataPembayaran,
            'semester_aktif' => Semester::where('kode_semester', $tahun_semester . '1')->first(),
            'dates' => CarbonPeriod::create($start_date, $end_date),
            'tingkat' => Kelas::select('tingkat')->orderBy('tingkat')->distinct()->get()->pluck('tingkat'),
            'data_tunggakan' => $allDataPembayaranTunggakan,
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerTanggal($auth_data, $start_date, $end_date)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        if (empty(session('setting_print_keuangan2'))) {
            $print_setting2 = 'semua';
        } else {
            $print_setting2 = session('setting_print_keuangan2');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.bulan', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
            ->whereHas('tagihan_biaya.detail_biaya', function ($query) use ($print_setting2) {
                if ($print_setting2 == 'spp') {
                    return $query->where('id_jenis_detail_biaya', '=', 4);
                } elseif ($print_setting2 == 'lain') {
                    return $query->where('id_jenis_detail_biaya', '!=', 4);
                }
            });

        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        }

        $listData = [];
        foreach ($allDataPembayaran as $tanggal => $rwytBayar) {
            $detail = [];
            foreach ($rwytBayar->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya') as $x) {
                $detail[] = [
                    'id_biaya' => isset(collect($x)->first()->tagihan_biaya->detail_biaya->biaya->id_biaya) ? collect($x)->first()->tagihan_biaya->detail_biaya->biaya->id_biaya : '',
                    'nama_biaya' => isset(collect($x)->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya) ? collect($x)->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya : '',
                    'frekuensi' => collect($x)->count(),
                    'nominal_pembayaran' => collect($x)->sum('besar_pembayaran'),
                    'potongan_biaya' => collect($x)->sum('tagihan_biaya.potongan.total_potongan'),
                ];
            }

            $listData[] = [
                'tanggal_pembayaran' => $tanggal,
                'total_pembayaran' => collect($detail)->sum('nominal_pembayaran'),
                'total_potongan' => collect($detail)->sum('potongan_biaya'),
                'detail' => $detail,
            ];
        }

        $result = [
            'data' => $listData,
            'kategori_biaya' => $pembayaran->get()->pluck('tagihan_biaya.detail_biaya.biaya.nm_biaya', 'tagihan_biaya.detail_biaya.biaya.id_biaya'),
        ];

        return $result;
    }

    public static function fetchLaporanPembayaranPerBulan($auth_data, $start_year, $end_year)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        if (empty(session('setting_print_keuangan2'))) {
            $print_setting2 = 'semua';
        } else {
            $print_setting2 = session('setting_print_keuangan2');
        }

        if ($print_setting2 == 'spp') {
            $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
                ->whereHas('tagihan_biaya.detail_biaya', function ($query) {
                    $query->where('id_jenis_detail_biaya', 4);
                });
        } elseif ($print_setting2 == 'lain') {
            $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal')
                ->whereHas('tagihan_biaya.detail_biaya', function ($query) {
                    $query->where('id_jenis_detail_biaya', '!=', 4);
                });
        } else {
            $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal');
        }

        if (!empty($start_year) && !empty($end_year)) {
            $pembayaran = $pembayaran->where(function ($month) use ($start_year, $end_year) {
                $month->whereYear('tgl_pembayaran', '>=', $start_year);
                $month->whereYear('tgl_pembayaran', '<=', $end_year);
            });
        }

        // if($print_setting2 == 'spp'){
        //     $pembayaran = $pembayaran->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //         $query->where('id_jenis_detail_biaya', 4);
        //     });
        // }elseif($print_setting2== 'lain'){
        //     $pembayaran =  $pembayaran->whereHas('tagihan_biaya.detail_biaya', function ($query){
        //         $query->where('id_jenis_detail_biaya', '!=' , 4);
        //     });
        // }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m');
            });
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m');
            });
        }

        $listData = [];
        foreach ($allDataPembayaran as $rwytBayarPerTgl) {
            $details = [];

            $rwytDetail = $rwytBayarPerTgl->groupBy('tagihan_biaya.detail_biaya.biaya.id_biaya');

            foreach ($rwytDetail as $dtl) {
                $details[] = [
                    'id_biaya' => $dtl->first()->tagihan_biaya->detail_biaya->biaya->id_biaya,
                    'nm_biaya' => $dtl->first()->tagihan_biaya->detail_biaya->biaya->nm_biaya,
                    'frekuensi' => $dtl->count(),
                    'total_pembayaran' => $dtl->sum('besar_pembayaran'),
                    'potongan_biaya' => $dtl->sum('tagihan_biaya.potongan.total_potongan'),
                ];
            }

            $tglPembayaran = Carbon::parse($rwytBayarPerTgl->first()->tgl_pembayaran);
            $listData[] = [
                'kode_bulan' => $tglPembayaran->format('m'),
                'tahun' => $tglPembayaran->format('Y'),
                'frekuensi' => $rwytBayarPerTgl->count(),
                'total_pembayaran' => $rwytBayarPerTgl->sum('besar_pembayaran'),
                'potongan_biaya' => $rwytBayarPerTgl->sum('tagihan_biaya.potongan.total_potongan'),
                'details' => $details,
            ];
        }
        $tempResult = [];
        $bulan = Bulan::orderBy('id_bulan')->get();
        $diffYear = $end_year - $start_year;
        for ($i = 0; $i <= $diffYear; $i++) {
            foreach ($bulan as $b) {
                $dataPerMonth = collect($listData)->where('kode_bulan', $b->kode_bulan)->where('tahun', $i + $start_year)->first();
                $tempResult[] = [
                    'kode_bulan' => $b->kode_bulan,
                    'bulan' => $b->nm_bulan,
                    'tahun' => $dataPerMonth['tahun'] ?? $i + $start_year,
                    'frekuensi' => $dataPerMonth['frekuensi'] ?? 0,
                    'total_pembayaran' => $dataPerMonth['total_pembayaran'] ?? 0,
                    'total_potongan' => collect($listData)->where('kode_bulan', $b->kode_bulan)->where('tahun', $i + $start_year)->sum('potongan_biaya'),
                    'details' => $dataPerMonth['details'] ?? [],
                ];
            }
        }

        $result = [
            'data' => $tempResult,
            'kategori_biaya' => $pembayaran->get()->pluck('tagihan_biaya.detail_biaya.biaya.nm_biaya', 'tagihan_biaya.detail_biaya.biaya.id_biaya'),
        ];

        return $result;
    }
    /** ========== */

    public static function fetchLaporanPembayaranDetail($auth_data, $start_date, $end_date)
    {
        if (empty(session('setting_print_keuangan'))) {
            $print_setting = 'all';
        } else {
            $print_setting = session('setting_print_keuangan');
        }

        $pembayaran = PembayaranBiaya::with('tagihan_biaya.siswa.pengguna', 'tagihan_biaya.potongan', 'tagihan_biaya.siswa.kelas', 'tagihan_biaya.detail_biaya.biaya', 'tagihan_biaya.detail_biaya.biaya_sekolah.semester', 'tagihan_biaya.detail_biaya.kelompok_biaya_internal.detail_biaya_internal', 'tagihan_biaya.detail_biaya.bulan');
        if (!empty($start_date) && !empty($end_date)) {
            $pembayaran = $pembayaran->whereBetween('tgl_pembayaran', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        if ($print_setting == 'self') {
            $allDataPembayaran = $pembayaran->isInputByPengguna($auth_data->pengguna->id_pengguna)->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        } else {
            $allDataPembayaran = $pembayaran->get()->groupBy(function ($pay) {
                return Carbon::parse($pay->tgl_pembayaran)->format('Y-m-d');
            });
        }

        $allDataPembayaran = $allDataPembayaran->sortKeys();
        $listData = [];
        foreach ($allDataPembayaran->sortBy('tgl_pembayaran') as $tanggal => $rwytBayar) {
            $detail = [];
            foreach ($rwytBayar as $x) {
                $detail[] = [
                    'nama_biaya' => $x->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == '4' ? $x->tagihan_biaya->detail_biaya->keterangan_biaya . ' Tahun ' . $x->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran . ' (' .  $x->tagihan_biaya->detail_biaya->bulan->nm_bulan . ')' : $x->tagihan_biaya->detail_biaya->keterangan_biaya . ' Tahun ' . $x->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran,
                    'siswa' => $x->tagihan_biaya->siswa,
                    'besar_pembayaran' => $x->besar_pembayaran,
                ];
            }

            foreach ($rwytBayar->groupBy('keterangan') as $x) {
                foreach ($x as $y) {
                    $detailKeterangan[$y->tagihan_biaya->detail_biaya->id_jenis_detail_biaya == '4' ? $y->tagihan_biaya->detail_biaya->keterangan_biaya . ' Tahun ' . $y->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran . ' (' .  $y->tagihan_biaya->detail_biaya->bulan->nm_bulan . ')' : $y->tagihan_biaya->detail_biaya->keterangan_biaya . ' Tahun ' . $y->tagihan_biaya->detail_biaya->biaya_sekolah->semester->tahun_ajaran][] = [
                        'siswa' => $y->tagihan_biaya->siswa,
                        'besar_pembayaran' => $y->besar_pembayaran,
                    ];
                }
            }


            $listData[] = [
                'tanggal_pembayaran' => $tanggal,
                'total_pembayaran' => collect($detail)->sum('besar_pembayaran'),
                'detail' => $detail,
                'detailKeterangan' => $detailKeterangan,
            ];
        }

        return $listData;
    }
}
