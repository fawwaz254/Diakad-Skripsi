<?php

namespace App\Http\Controllers\Keuangan\SIM;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Guru;
use App\Models\Rapb;
use App\Models\Realisasi;
use App\Models\Semester;
use App\Models\Staff;
use App\Models\SubkategoriRapb;
use App\Models\TutupBukuBulananKas;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use Yajra\Datatables\Datatables;

class PengeluaranController extends BaseController
{
    public function viewMenuPengeluaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('keuangan/sim/pengeluaran/view-menu-pengeluaran', compact('auth_data'));
    }

    public function viewMenuInput(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 2);
        })->get();

        $maxDate = Carbon::now()->addMonth()->format('Y/m/d');
        $minDate = Carbon::now()->subMonths(6)->format('Y/m/d');

        $semester_aktif = Semester::where('is_aktif_semester', 1)->first();
        $semester_ganjil = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '1')->first();
        $semester_genap = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '2')->first();

        $id_semester_mulai = $semester_ganjil->id_semester;
        $id_semester_selesai = $semester_genap->id_semester;
        if ($tutup_buku_bulanan_kas_last = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'is_fix' => 1, 'created_by' => $auth_data->pengguna->id_pengguna])->orderBy('updated_at', 'desc')->orderBy('id_bulan', 'desc')->first()) {
            if ($tutup_buku_bulanan_kas_last->id_bulan < 7) {
                $tahun_semester = $semester_aktif->thn_akademik_semester + 1;
            } else {
                $tahun_semester = $semester_aktif->thn_akademik_semester;
            }
            $minDate = Carbon::parse($tahun_semester . '-' . $tutup_buku_bulanan_kas_last->id_bulan . '-01')->addMonth()->format('Y/m/d');
        }

        return view('keuangan/sim/pengeluaran/view-menu-input', compact('auth_data', 'data_semester', 'tahun_akademik_semester', 'maxDate', 'minDate', 'data_subkategori'));
    }

    public function viewMenuTarget(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if (empty($tahun_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        return view('keuangan/sim/pengeluaran/view-menu-target', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function datatablesMenuTarget(Request $request)
    {
        $input = (object) $request->input();

        $tahun = $input->tahun;
        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $list_data = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 2);
        });

        $list_rapb = Rapb::selectRaw('
                            nm_kategori_rapb,
                            kode_subkategori_rapb,
                            rapb.id_subkategori_rapb,
                            nm_subkategori_rapb,
                            tipe_kategori_rapb,
                            dana_perkiraan_rapb')
            ->join('subkategori_rapb', function ($q) {
                $q->on('subkategori_rapb.id_subkategori_rapb', '=', 'rapb.id_subkategori_rapb')
                    ->whereNull('subkategori_rapb.deleted_at');
            })
            ->join('kategori_rapb', function ($q) {
                $q->on('kategori_rapb.id_kategori_rapb', '=', 'subkategori_rapb.id_kategori_rapb')
                    ->whereNull('kategori_rapb.deleted_at');
            })
            ->where('id_semester_mulai', $semester_mulai->id_semester)
            ->where('id_semester_selesai', $semester_selesai->id_semester)
            ->isInputByPengguna(auth_data()->pengguna->id_pengguna)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('target_rapb', function ($item) use ($list_rapb) {
                if ($rapb = $list_rapb->firstWhere('id_subkategori_rapb', $item->id_subkategori_rapb)) {
                    return 'Rp' . number_format($rapb->dana_perkiraan_rapb);
                } else {
                    return 'Rp0';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_subkategori_rapb,
                );
                return $data;
            })
            ->make(true);
    }

    public function viewMenuEditTarget(Request $request, $tahun_akademik_semester, $id_subkategori_rapb)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester . '2')->first();

        if ($item = Rapb::where(['id_subkategori_rapb' => $id_subkategori_rapb, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first()) {
        } else {
            $item = null;
        }

        $subkategori_rapb = SubkategoriRapb::find($id_subkategori_rapb);

        $maxDate = Carbon::now()->addMonth()->format('Y/m/d');
        $minDate = Carbon::now()->subMonths(6)->format('Y/m/d');

        $semester_aktif = Semester::where('is_aktif_semester', 1)->first();
        $semester_ganjil = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '1')->first();
        $semester_genap = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '2')->first();

        $id_semester_mulai = $semester_ganjil->id_semester;
        $id_semester_selesai = $semester_genap->id_semester;
        if ($tutup_buku_bulanan_kas_last = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'is_fix' => 1, 'created_by' => $auth_data->pengguna->id_pengguna])->orderBy('updated_at', 'desc')->orderBy('id_bulan', 'desc')->first()) {
            if ($tutup_buku_bulanan_kas_last->id_bulan < 7) {
                $tahun_semester = $semester_aktif->thn_akademik_semester + 1;
            } else {
                $tahun_semester = $semester_aktif->thn_akademik_semester;
            }
            $minDate = Carbon::parse($tahun_semester . '-' . $tutup_buku_bulanan_kas_last->id_bulan . '-01')->addMonth()->format('Y/m/d');
        }

        return view('keuangan/sim/pengeluaran/view-menu-input-target', compact('auth_data', 'item', 'semester_mulai', 'tahun_akademik_semester', 'minDate', 'maxDate', 'subkategori_rapb'));
    }

    public function actionSaveEditTarget(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'subkategori' => 'required',
            'dana_perkiraan_rapb' => 'required',
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
            DB::beginTransaction();
            try {

                if (!empty($input->id)) {
                    $rapb = Rapb::find($id);
                } else {
                    $rapb = Rapb::where(['id_subkategori_rapb' => $input->subkategori, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();
                }

                if ($rapb) {
                    $rapb->updated_by = auth_data()->pengguna->id_pengguna;
                } else {
                    $rapb = new Rapb;
                    $rapb->id_rapb = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                    $rapb->id_semester_mulai = $semester_mulai->id_semester;
                    $rapb->id_semester_selesai = $semester_selesai->id_semester;
                    $rapb->id_subkategori_rapb = $input->subkategori;
                    $rapb->tgl_rapb = $now->format('Y-m-d');
                    $rapb->prioritas_rapb = 3;
                    $rapb->created_by = auth_data()->pengguna->id_pengguna;

                    if ($actor = Staff::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                        $rapb->id_unit_kerja = $actor->id_unit_kerja;
                    } else if ($actor = Guru::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                        $rapb->id_unit_kerja = $actor->id_unit_kerja;
                    }

                    if ($kepala_unit_keuangan = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                        ->where('guru.jenis_jabatan', '=', 2)
                        ->where('pengguna.id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                        ->first()
                    ) {
                        $rapb->id_pengguna_kepala_unit = $kepala_unit_keuangan->id_pengguna;
                    } else if ($kepala_unit_keuangan = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                        ->where('staff.jenis_jabatan', '=', 2)
                        ->where('pengguna.id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                        ->first()
                    ) {
                        $rapb->id_pengguna_kepala_keuangan = $kepala_unit_keuangan->id_pengguna;
                    }
                }

                $rapb->dana_perkiraan_rapb = $input->dana_perkiraan_rapb;
                $rapb->save();

                DB::commit();
                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/pengeluaran/target',
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                ]);
            }
        }
    }

    public function viewMenuTampilkan(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

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

        return view('keuangan/sim/pengeluaran/view-menu-tampilkan', compact('auth_data', 'data_semester', 'tgl_awal', 'tgl_akhir', 'tahun_akademik_semester'));
    }

    public function datatablesMenuTampilkan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $tahun = $input->tahun;
        $tgl_awal = $input->tgl_awal;
        $tgl_akhir = $input->tgl_akhir;
        $semester_mulai = Semester::where('kode_semester', $tahun . '1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun . '2')->first();

        $list_data = Realisasi::with('rapb', 'rapb.subkategori')->whereHas('rapb.subkategori.kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 2);
        })->whereIn('id_semester_realisasi', [$semester_mulai->id_semester, $semester_selesai->id_semester])
            ->whereDate('tgl_realisasi', '>=', $tgl_awal)
            ->whereDate('tgl_realisasi', '<=', $tgl_akhir)
            ->isInputByPengguna($auth_data->pengguna->id_pengguna);

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

    public function actionSaveInputPengeluaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

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

            $rapb = Rapb::where(['id_subkategori_rapb' => $input->id_subkategori_rapb, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester, 'created_by' => $auth_data->pengguna->id_pengguna])->first();

            if ($rapb) {
            } else {
                $rapb = new Rapb;
                $rapb->id_rapb = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $rapb->id_semester_mulai = $semester_mulai->id_semester;
                $rapb->id_semester_selesai = $semester_selesai->id_semester;
                $rapb->id_subkategori_rapb = $input->id_subkategori_rapb;
                $rapb->tgl_rapb = $now->format('Y-m-d');
                $rapb->prioritas_rapb = 3;
                $rapb->dana_perkiraan_rapb = 0;
                $rapb->created_by = auth_data()->pengguna->id_pengguna;

                if ($actor = Staff::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                    $rapb->id_unit_kerja = $actor->id_unit_kerja;
                } else if ($actor = Guru::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                    $rapb->id_unit_kerja = $actor->id_unit_kerja;
                }

                if ($kepala_unit_keuangan = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                    ->where('guru.jenis_jabatan', '=', 2)
                    ->where('pengguna.id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                    ->first()
                ) {
                    $rapb->id_pengguna_kepala_unit = $kepala_unit_keuangan->id_pengguna;
                } else if ($kepala_unit_keuangan = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                    ->where('staff.jenis_jabatan', '=', 2)
                    ->where('pengguna.id_sekolah', '=', auth_data()->pengguna->id_sekolah)
                    ->first()
                ) {
                    $rapb->id_pengguna_kepala_keuangan = $kepala_unit_keuangan->id_pengguna;
                }
                $rapb->save();
            }

            $tgl_realisasi = Carbon::parse($input->tgl_realisasi);
            $id_bulan = $tgl_realisasi->month;

            if ($id_bulan < 7) {
                $id_semester = $semester_selesai->id_semester;
            } else {
                $id_semester = $semester_mulai->id_semester;
            }

            DB::beginTransaction();
            try {

                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                if ($input->id_realisasi) {
                    $realisasi = Realisasi::find($input->id_realisasi);
                } else {
                    $realisasi = new Realisasi;
                    $realisasi->id_realisasi = $id;
                }
                $realisasi->id_semester_realisasi = $id_semester;
                $realisasi->id_rapb = $rapb->id_rapb;
                if ($actor = Staff::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                    $realisasi->id_unit_kerja = $actor->id_unit_kerja;
                } else if ($actor = Guru::where('id_pengguna', auth_data()->pengguna->id_pengguna)->first()) {
                    $realisasi->id_unit_kerja = $actor->id_unit_kerja;
                }
                $realisasi->nm_realisasi = $input->nm_realisasi;
                $realisasi->termin_dana_realisasi = 1;
                $realisasi->is_hutang_realisasi = 0;
                $realisasi->dana_realisasi = $input->dana_realisasi;
                $realisasi->tgl_realisasi = date_format(date_create($input->tgl_realisasi), "Y-m-d");
                $realisasi->created_by = auth_data()->pengguna->id_pengguna;
                if ($input->id_realisasi) {
                    $realisasi->updated_by = auth_data()->pengguna->id_pengguna;
                }

                $realisasi->save();

                DB::commit();

                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/pengeluaran/tampilkan',
                    'message' => 'Success',
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine(),
                ]);
            }
        }
    }

    public function editPengeluaran(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $pengeluaran = Realisasi::findOrFail($id);
        $tahun_akademik_semester = Semester::find($pengeluaran->id_semester_realisasi)->thn_akademik_semester;
        $rapb = Rapb::find($pengeluaran->id_rapb);

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function ($q) {
            $q->where('tipe_kategori_rapb', 2);
        })->get();

        $maxDate = Carbon::now()->addMonth()->format('Y/m/d');
        $minDate = Carbon::now()->subMonths(6)->format('Y/m/d');

        $semester_aktif = Semester::where('is_aktif_semester', 1)->first();
        $semester_ganjil = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '1')->first();
        $semester_genap = Semester::where('kode_semester', $semester_aktif->thn_akademik_semester . '2')->first();

        $id_semester_mulai = $semester_ganjil->id_semester;
        $id_semester_selesai = $semester_genap->id_semester;
        if ($tutup_buku_bulanan_kas_last = TutupBukuBulananKas::where(['id_semester_mulai' => $id_semester_mulai, 'id_semester_selesai' => $id_semester_selesai, 'is_fix' => 1, 'created_by' => $auth_data->pengguna->id_pengguna])->orderBy('updated_at', 'desc')->orderBy('id_bulan', 'desc')->first()) {
            if ($tutup_buku_bulanan_kas_last->id_bulan < 7) {
                $tahun_semester = $semester_aktif->thn_akademik_semester + 1;
            } else {
                $tahun_semester = $semester_aktif->thn_akademik_semester;
            }
            $minDate = Carbon::parse($tahun_semester . '-' . $tutup_buku_bulanan_kas_last->id_bulan . '-01')->addMonth()->format('Y/m/d');
        }

        return view('keuangan/sim/pengeluaran/view-menu-input', compact('auth_data', 'pengeluaran', 'data_semester', 'tahun_akademik_semester', 'minDate', 'maxDate', 'data_subkategori', 'rapb'));
    }

    public function deletePengeluaran(Request $request, $id)
    {
        $input = (object) $request->input();

        $pengeluaran = Realisasi::find($id);
        $pengeluaran->deleted_by = auth_data()->pengguna->id_pengguna;
        $pengeluaran->save();

        $pengeluaran->delete();

        return [
            'status' => 203,
            'message' => "Berhasil dihapus",
        ];
    }

    public function printKuitansiPengeluaran(Request $request, $id)
    {
        $auth_data = $request->auth_data;

        $pengeluaran = Realisasi::find($id);

        $terbilang = LibDataKeuangan::getTerbilang($pengeluaran->dana_realisasi);
        // dd($terbilang);

        return view('keuangan/sim/pengeluaran/print-kuitansi-pengeluaran', compact('auth_data', 'pengeluaran', 'terbilang'));
    }
}
