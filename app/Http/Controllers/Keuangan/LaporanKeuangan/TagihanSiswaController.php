<?php

namespace App\Http\Controllers\Keuangan\LaporanKeuangan;

use App\Models\Biaya;
use App\Models\Bulan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Semester;
use App\Models\DetailBiaya;
use App\Models\BiayaSekolah;
use App\Models\TagihanBiaya;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\PembayaranBiaya;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class TagihanSiswaController extends BaseController
{
    public function viewTagihanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_kelas = LibKelas::fetchDataKelas($auth_data);

        return view('keuangan/laporan-keuangan/tagihan-siswa/view-tagihan-siswa', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'data_kelas'));
    }

    public function datatablesTagihanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tahun = $input->tahun_akademik_semester;
        $kelas = $input->kelas;
        $jenis_tagihan = $input->jenis_tagihan;
        $status = $request->status;
        // dd($jenis_tagihan);
        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $list_data = array();

        if (!empty($kelas) && !empty($id_semester_mulai) && !empty($id_semester_selesai)) {
            $data_id_biaya_sekolah = BiayaSekolah::select('id_biaya_sekolah')->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai])->get()->pluck('id_biaya_sekolah');

            $list_data = Siswa::with(['tagihan_tertagih' => function ($q) use ($data_id_biaya_sekolah, $jenis_tagihan) {
                $q->with('pembayaran', 'potongan')
                    ->whereHas('detail_biaya', function ($query) use ($data_id_biaya_sekolah, $jenis_tagihan) {
                        $query->whereIn('id_biaya_sekolah', $data_id_biaya_sekolah);

                        if ($jenis_tagihan) {
                            if (!in_array("0", $jenis_tagihan)) {
                                $query->whereIn('id_detail_biaya', $jenis_tagihan);
                            }
                        }
                    });
            }, 'pengguna', 'kelas']);

            if ($status == 1) {
                if (!empty($kelas)) {
                    if ($kelas == 'all') {
                        $list_data = $list_data->whereNotNull('id_kelas')->orderBy("id_kelas", "asc");
                    } else {
                        $list_data = $list_data->where('id_kelas', $kelas);
                    }
                }
            } else if ($status == 2) {
                if (!empty($kelas)) {
                    if ($kelas === 'all') {
                    } else {
                        $list_data = $list_data->whereHas('last_kelas_siswa', function ($q) use ($kelas) {
                            $q->where('id_kelas', $kelas)->with('kelas');
                        });
                    }
                }
            }
        }

        return Datatables::of($list_data)
            ->editColumn('kelas.nm_kelas', function ($item) use ($status) {
                if ($status == 1) {
                    return $item->kelas->nm_kelas;
                } else if ($status == 2) {
                    return $item->last_kelas_siswa->kelas->nm_kelas . ' (Alumni)';
                } else {
                    return '';
                }
            })
            ->addColumn('total_tagihan_bulan', function ($item) {
                $sumPembayaran = $item->tagihan_tertagih->sum(function ($sum) {
                    return $sum->pembayaran->sum('besar_pembayaran');
                });
                $diskonTagihan = $item->tagihan_tertagih->sum(function ($sum) {
                    return $sum->potongan->total_potongan ?? 0;
                });
                return 'Rp' . number_format($item->tagihan_tertagih->sum('besar_biaya') - $sumPembayaran - $diskonTagihan);
            })
            ->addColumn('tagihan_bulan', function ($item) use ($tahun) {
                $array_tagihan_bulan = array();
                foreach ($item->tagihan_tertagih as $tagihan) {

                    // $x =   $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan->id_detail_biaya);
                    $x = $tagihan->detail_biaya;

                    $nominal = $x->besar_biaya - $tagihan->pembayaran->sum('besar_pembayaran') - ($tagihan->potongan->total_potongan ?? 0);

                    $tagihan_bulan['id_bulan'] = 13;
                    $tagihan_bulan['jenis_tagihan'] = $x->biaya->nm_biaya;
                    $tagihan_bulan['judul'] = $tagihan_bulan['jenis_tagihan'];
                    $tagihan_bulan['biaya'] = 'Rp' . number_format($nominal);

                    if ($x->id_jenis_detail_biaya == 4) {
                        $tagihan_bulan['id_bulan'] = $x->bulan->id_bulan;
                        $tagihan_bulan['nm_bulan'] = $x->bulan->nm_bulan;
                        if ($x->bulan->id_bulan < 7) {
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul'] . ' ' . $tagihan_bulan['nm_bulan'] . ' ' . ($tahun + 1);
                        } else {
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul'] . ' ' . $tagihan_bulan['nm_bulan'] . ' ' . $tahun;
                        }
                        $array_tagihan_bulan[] = $tagihan_bulan;
                    }

                    if (!empty($array_tagihan_bulan)) {
                        $array_tagihan_bulan = collect($array_tagihan_bulan)->sortBy('id_bulan')->toArray();
                    }
                };
                return $array_tagihan_bulan;
            })
            ->make(true);
    }

    public function printTagihanSiswa(Request $request, $tahun, $id_kelas, $jenis_tagihan)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $status = $request->status;

        if ($id_kelas == 'all') {
            $kelas_data = Kelas::where('is_aktif', 1)->get();
        } else {
            $kelas_data = Kelas::find($id_kelas);
        }


        $jenis_tagihan_explode = explode(",", $jenis_tagihan);

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;

        $data_detail_biaya = DetailBiaya::with('bulan')->whereHas('biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
            $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
        })->get();

        $data_id_biaya_sekolah = BiayaSekolah::select('id_biaya_sekolah')->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai])->get()->pluck('id_biaya_sekolah');

        $list_data = Siswa::with(['tagihan_tertagih' => function ($q) use ($data_id_biaya_sekolah, $jenis_tagihan_explode) {
            $q->with('pembayaran', 'potongan')
                ->whereHas('detail_biaya', function ($query) use ($data_id_biaya_sekolah, $jenis_tagihan_explode) {
                    $query->whereIn('id_biaya_sekolah', $data_id_biaya_sekolah);

                    if ($jenis_tagihan_explode) {
                        if (!in_array("0", $jenis_tagihan_explode)) {
                            $query->whereIn('id_detail_biaya', $jenis_tagihan_explode);
                        }
                    }
                });
        }, 'pengguna', 'kelas']);

        if ($status == 1) {
            if ($id_kelas == 'all') {
                $list_data = $list_data->whereNotNull('id_kelas');
            } else {
                $list_data = $list_data->whereHas('kelas', function ($q) use ($id_kelas) {
                    $q->where('id_kelas', $id_kelas);
                });
            }
        } else if ($status == 2) {
            $list_data = $list_data->whereHas('last_kelas_siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas)->with('kelas');
            });
        }

        $all_data = $list_data->get();

        $all_data = $all_data->map(function ($data) use ($data_detail_biaya, $tahun) {
            $tagihan = [];

            foreach ($data->tagihan_tertagih as $tagihan_siswa) {


                if ($x = $data_detail_biaya->firstWhere('id_detail_biaya', $tagihan_siswa->id_detail_biaya)) {

                    $nominal = $tagihan_siswa->besar_biaya - $tagihan_siswa->pembayaran->sum('besar_pembayaran') - ($tagihan_siswa->potongan->total_potongan ?? 0);

                    $tagihan_bulan['id_bulan'] = 13;
                    $tagihan_bulan['jenis_tagihan'] = $x->biaya->nm_biaya;
                    $tagihan_bulan['judul'] = $tagihan_bulan['jenis_tagihan'];
                    $tagihan_bulan['belum_bayar'] = $nominal;
                    $tagihan_bulan['total_potongan'] = ($tagihan_siswa->potongan->total_potongan ?? 0);
                    $tagihan_bulan['sudah_bayar'] = $tagihan_siswa->pembayaran->sum('besar_pembayaran');
                    $tagihan_bulan['tagihan'] = $x->besar_biaya;
                    $tagihan_bulan['id_jenis_detail_biaya'] = $x->id_jenis_detail_biaya;

                    if ($x->id_jenis_detail_biaya == 4) {
                        $tagihan_bulan['id_bulan'] = $x->bulan->id_bulan;
                        $tagihan_bulan['nm_bulan'] = $x->bulan->nm_bulan;
                        if ($x->bulan->id_bulan < 7) {
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul'] . ' ' . $tagihan_bulan['nm_bulan'] . ' ' . ($tahun + 1);
                        } else {
                            $tagihan_bulan['judul'] = $tagihan_bulan['judul'] . ' ' . $tagihan_bulan['nm_bulan'] . ' ' . $tahun;
                        }
                    } else {
                        $tagihan_bulan['judul'] = $tagihan_bulan['judul'] . ' ' . $x->keterangan_biaya;
                    }
                    $tagihan[] = $tagihan_bulan;
                }
            }

            if (!empty($tagihan)) {
                $tagihan = collect($tagihan)->sortBy('id_bulan')->toArray();
            }

            $data['tagihan'] = $tagihan;
            return $data;
        });

        return view('keuangan/laporan-keuangan/tagihan-siswa/print-tagihan-siswa', compact('auth_data', 'semester_mulai', 'semester_selesai', 'status', 'all_data', 'kelas_data', 'id_kelas'));
    }

    public function showTotalTagihan(Request $request, $tahun, $id_kelas, $status)
    {

        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $id_semester_mulai = $semester_mulai->id_semester;
        $id_semester_selesai = $semester_selesai->id_semester;
        $data_id_biaya_sekolah = BiayaSekolah::select('id_biaya_sekolah')->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai])->get()->pluck('id_biaya_sekolah');

        $list_data = Siswa::with(['tagihan_tertagih' => function ($q) use ($data_id_biaya_sekolah) {
            $q->whereHas('detail_biaya', function ($query) use ($data_id_biaya_sekolah) {
                $query->whereIn('id_biaya_sekolah', $data_id_biaya_sekolah)->where('id_jenis_detail_biaya', '4');
            });
        }]);


        if ($status == '1') {
            if (!empty($id_kelas)) {
                if ($id_kelas == 'all') {
                    $list_data = $list_data->whereNotNull('id_kelas');
                } else {
                    $list_data = $list_data->where('id_kelas', $id_kelas);
                }
            }
        } else if ($status == '2') {
            if (!empty($id_kelas)) {
                if ($id_kelas === 'all') {
                } else {
                    $list_data = $list_data->whereHas('last_kelas_siswa', function ($q) use ($id_kelas) {
                        $q->where('id_kelas', $id_kelas);
                    });
                }
            }
        }

        $data = $list_data->get();
        $nominal = 0;
        foreach ($data as $d) {
            foreach ($d->tagihan_tertagih as $tagihan_biaya)
                $nominal  +=   $tagihan_biaya->detail_biaya->besar_biaya;
        }

        return response()->json('Rp' . number_format($nominal));
    }

    public function showListTagihan(Request $request, $tahun, $id_kelas)
    {
        // $kelas_data = Kelas::find($id_kelas);

        $id_semester_mulai = Semester::where('kode_semester', $tahun . '1')->first()->id_semester;
        $id_semester_selesai = Semester::where('kode_semester', $tahun . '2')->first()->id_semester;
        if ($id_kelas == 'all') {
            $tagihan_biaya = TagihanBiaya::whereHas('detail_biaya.biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
            })
                ->groupBy('id_detail_biaya')
                ->pluck('id_detail_biaya');
        } else {
            $tagihan_biaya = TagihanBiaya::where('id_kelas', $id_kelas)
                ->whereHas('detail_biaya.biaya_sekolah', function ($q) use ($id_semester_mulai, $id_semester_selesai) {
                    $q->whereIn('id_semester', [$id_semester_mulai, $id_semester_selesai]);
                })
                ->groupBy('id_detail_biaya')
                ->pluck('id_detail_biaya');
        }


        $data_detail_biaya = DetailBiaya::with('bulan', 'biaya')->whereIn('id_detail_biaya', $tagihan_biaya)->get();

        $data_detail_biaya_modified = $data_detail_biaya->map(function ($item, $key) use ($tahun) {

            if ($item->id_jenis_detail_biaya == 4) {
                $bulan = $item->bulan->id_bulan;
                $nm_bulan = $item->bulan->nm_bulan;

                if ($bulan < 7) {
                    $judul = $nm_bulan . ' ' . ($tahun + 1);
                } else {
                    $judul = $nm_bulan . ' ' . $tahun;
                }
            } else {

                $judul = $item->keterangan_biaya;
            }

            $item->judul = $item->biaya->nm_biaya . ' ' . $judul;
            return $item;
        });

        return response()->json($data_detail_biaya_modified);
    }

    public function bayarBulanJuli(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $id_semester =  $semester_aktif->id_semester;

        $tagihan = TagihanBiaya::where('is_tagih', 1)->whereHas('detail_biaya', function ($query) use ($id_semester) {
            $query->where('id_bulan', '=', '7')->whereHas('biaya_sekolah', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            });
        })->get();

        foreach ($tagihan as $t) {
            $besar = $t->besar_biaya;
            $t->is_tagih = 0;
            $t->besar_pembayaran =  $besar;
            $t->tgl_pelunasan = '2023-07-31 00:00:00';
            $t->keterangan = 'pembayaran awal tahun';

            $now = Carbon::now();
            $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $pembayaranBiaya = new PembayaranBiaya;
            $pembayaranBiaya->id_pembayaran_biaya = $uuid;
            $pembayaranBiaya->id_tagihan_biaya = $t->id_tagihan_biaya;
            $pembayaranBiaya->id_staff_bayar = $input->auth_data->pengguna->id_pengguna;
            $pembayaranBiaya->id_semester_bayar = $id_semester;
            $pembayaranBiaya->besar_pembayaran =  $besar;
            $pembayaranBiaya->tgl_pembayaran = '2023-07-31 00:00:00';
            $pembayaranBiaya->keterangan = 'pembayaran awal tahun';
            $pembayaranBiaya->created_by = $input->auth_data->pengguna->id_pengguna;
            $pembayaranBiaya->save();
            $t->save();
        }
    }
}
