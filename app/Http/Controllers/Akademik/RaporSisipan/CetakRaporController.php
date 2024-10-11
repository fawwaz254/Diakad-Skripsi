<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Exports\PengembanganDiri;
use App\Http\Controllers\Controller;
use App\Imports\UploadPengembanganDiri;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Rapor;
use App\Models\KelasRapor;
use App\Models\MataPelajaran;
use App\Models\RaporSisipan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use Maatwebsite\Excel\Facades\Excel;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\KelasSisipan;
use App\Models\KelompokMapelRapor;
use App\Models\KelompokPribadiSisipan;
use App\Models\KelompokSisipan;
use App\Models\KomponenJenisRapor;
use App\Models\Kurikulum;
use App\Models\MataPelajaranSisipan;
use App\Models\NilaiPribadiSisipan;
use App\Models\NilaiRapor;
use App\Models\PribadiSisipan;
use App\Models\RaporSisipanDeskripsi;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SubRaporSisipan;
use App\Models\UrutanRaporSisipan;
use App\Models\WaliKelas;
use Auth;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use DB;
use Session;
use Validator;


class CetakRaporController extends Controller
{

    // public function updateCetakRapor(Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;
    //     $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    //     $kelas = Kelas::where('is_aktif', '1')->get();


    //     foreach ($kelas as $k) {

    //         $kelas_sisipan = KelasSisipan::with('mata_pelajaran_sisipan')->where('id_kelas', $k->id_kelas)->get();

    //         $rapor_sisipan = RaporSisipan::where('id_semester', $semester_aktif->id_semester)->where('id_kelas', $k->id_kelas)->get();

    //         $mata_pelajaran_sisipan = MataPelajaranSisipan::whereIn('id_mata_pelajaran_sisipan', $kelas_sisipan->pluck('id_mata_pelajaran_sisipan'))->get();

    //         foreach ($rapor_sisipan as $r) {
    //             $cek = $kelas_sisipan->where('id_kelas', $r->id_kelas)->where('mata_pelajaran_sisipan.id_mata_pelajaran', $r->id_mata_pelajaran)->first();
    //             if ($cek) { } else {
    //                 $mata_pelajaran = MataPelajaran::where('id_mata_pelajaran', $r->id_mata_pelajaran)->first();
    //                 if ($mata_pelajaran) {
    //                     $mapel =  MataPelajaran::whereIn('id_mata_pelajaran', $mata_pelajaran_sisipan->pluck('id_mata_pelajaran'))->where('nm_mata_pelajaran',  $mata_pelajaran->nm_mata_pelajaran)->first();
    //                     if ($mapel) {
    //                         $r->id_mata_pelajaran = $mapel->id_mata_pelajaran;
    //                         $r->updated_by = 'syahrul update';
    //                         $r->save();
    //                     } else {
    //                         // return $r->id_rapor_sisipan;
    //                     }
    //                 }
    //                 // return $r->id_rapor_sisipan;
    //             }
    //         }
    //     }


    //     return 'sukses';
    // }
    public function viewCetakRapor(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
        if (isset($tanggal)) {
            $tanggal_cetak = $tanggal->value;
        } else {
            $tanggal_cetak = date('Y-m-d');
        }

        return view('akademik/rapor-sisipan/cetak-rapor/view-cetak-rapor', compact('auth_data', 'semester_aktif', 'data_semester', 'tanggal_cetak'));
    }

    public function viewCetakRaporWaliKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $wali_kelas = WaliKelas::where('is_aktif', 1)->where('id_guru', $guru->id_guru)->first();
        $data_wali_kelas = LibGuru::fetchDataWaliKelas($auth_data, $wali_kelas->id_kelas)->where('is_aktif', 1)->first();
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();

        // $jumlah_kelas_sisipan = KelasSisipan::where('id_kelas', $wali_kelas->id_kelas)->count();
        $jumlah_kelas_sisipan = KelasRapor::with('mata_pelajaran_rapor.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->where('id_kelas', $wali_kelas->id_kelas)
            ->whereHas('mata_pelajaran_rapor', function ($q) {
                $q->where('jenis', '1');
            })->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
                $q->where('nm_rapor', 'sisipan');
            })->count();

        return view('guru/wali-kelas/cetak-rapor/view-cetak-rapor', compact('wali_kelas', 'data_wali_kelas', 'semester_aktif', 'jumlah_kelas_sisipan'));
    }

    public function datatablesCetakRapor(Request $request)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = Kelas::where('is_aktif', 1)->with('jurusan')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        // $list_rapor_sisipan = RaporSisipan::whereHas('semester', function ($query) use ($id_semester) {
        //     $query->where('id_semester', '=', $id_semester);
        // })->get();
        $list_rapor_sisipan = Rapor::whereHas('semester', function ($query) use ($id_semester) {
            $query->where('id_semester', '=', $id_semester);
        })->where('nm_rapor', 'sisipan')->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->get();
        $semester = Semester::where('id_semester', $id_semester)->first();

        // $kelas_sisipan = KelasSisipan::whereHas('mata_pelajaran_sisipan', function ($q) {
        //     $q->where('jenis', '1');
        // })->get();
        $kelas_rapor = KelasRapor::whereHas('mata_pelajaran_rapor', function ($q) {
            $q->where('jenis', '1');
        })->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'sisipan');
        })->get();



        return Datatables::of($list_data)
            ->addColumn('kelas_sisipan', function ($item) use ($kelas_rapor) {
                return $kelas_rapor->where('id_kelas', $item->id_kelas)->count();
            })
            ->addColumn('wali_kelas', function ($item) use ($wali_kelas) {
                $k = $wali_kelas->firstWhere('id_kelas', $item->id_kelas);
                return $k->guru->pengguna->nm_pengguna ?? '';
            })
            ->addColumn('rapor_sisipan', function ($item) use ($list_rapor_sisipan) {
                return $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count();
            })->addColumn('semester', function () use ($semester) {
                return  $semester->tahun_ajaran . ' ' . $semester->nm_semester;
            })
            ->addColumn('action', function ($item)  use ($list_rapor_sisipan, $id_semester) {
                $data = array(
                    'id_kelas'                  => $item->id_kelas,
                    'jumlah'                    => $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count(),
                    'id_semester'               => $id_semester
                );
                return $data;
            })
            ->make(true);
    }


    public function viewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/cetak-rapor/view-setting-cetak-rapor', compact('auth_data'));
    }

    public function viewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-deskripsi-cetak-rapor', compact('auth_data'));
    }

    public function addSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $mapel = MataPelajaran::where('id_mata_pelajaran', $mata_pelajaran)->with('urutan_rapor_sisipan')->first();

        return view('akademik/rapor-sisipan/cetak-rapor/add-setting-cetak-rapor', compact('auth_data', 'mapel'));
    }

    public function  editDeskripsi(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $rapor_sisipan_deskripsi = RaporSisipanDeskripsi::find($id);
        $mata_pelajaran = MataPelajaran::select('nm_mata_pelajaran')->groupBy('nm_mata_pelajaran')->get();
        return view('akademik/rapor-sisipan/cetak-rapor/edit-deskripsi-rapor-sisipan', compact('auth_data', 'rapor_sisipan_deskripsi', 'mata_pelajaran'));
    }

    public function actionDeskripsi(Request $request, $mode, $id)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();
        $validator = Validator::make($request->all(), [
            'nm_mata_pelajaran' => 'required',
            'tingkat' => 'required',
            'kd_deskripsi' => 'required',
            'deskripsi1' => 'required',
            'deskripsi2' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $rapor_sisipan_deskripsi = new RaporSisipanDeskripsi;
                $rapor_sisipan_deskripsi->id_rapor_sisipan_deskripsi = $id;
                $rapor_sisipan_deskripsi->nm_mata_pelajaran = $input->nm_mata_pelajaran;
                $rapor_sisipan_deskripsi->tingkat = $input->tingkat;
                $rapor_sisipan_deskripsi->kd_deskripsi = $input->kd_deskripsi;
                $rapor_sisipan_deskripsi->deskripsi1 = $input->deskripsi1;
                $rapor_sisipan_deskripsi->deskripsi2 = $input->deskripsi2;
                $rapor_sisipan_deskripsi->updated_at = $now;
                $rapor_sisipan_deskripsi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/cetak-rapor/viewDeskripsi',
                    'message' => 'Add Deskripsi Successfully'
                ];
            } elseif ($mode == 'edit') {

                $rapor_sisipan_deskripsi = RaporSisipanDeskripsi::find($id);
                $rapor_sisipan_deskripsi->nm_mata_pelajaran = $input->nm_mata_pelajaran;
                $rapor_sisipan_deskripsi->tingkat = $input->tingkat;
                $rapor_sisipan_deskripsi->kd_deskripsi = $input->kd_deskripsi;
                $rapor_sisipan_deskripsi->deskripsi1 = $input->deskripsi1;
                $rapor_sisipan_deskripsi->deskripsi2 = $input->deskripsi2;
                $rapor_sisipan_deskripsi->updated_at = $now;
                $rapor_sisipan_deskripsi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/cetak-rapor/viewDeskripsi',
                    'message' => 'Edit Deskripsi Successfully'
                ];
            } elseif ($mode == 'delete') {

                // $kegiatan = KegiatanGuru::find($id);
                // $kegiatan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                // $kegiatan->deleted_at  = $now;
                // $kegiatan->save();

                // $kegiatan->delete();

                // return [
                //     'status' => 203, // SUCCESS AND LOAD TABLE
                //     'message' => 'Delete Kegiatan Successfully'
                // ];
            }
        }
    }

    public function postSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($urutan_rapor_sisipan = UrutanRaporSisipan::find($mata_pelajaran)) {
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->updated_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        } else {
            $now = Carbon::now();
            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $urutan_rapor_sisipan               = new UrutanRaporSisipan;
            $urutan_rapor_sisipan->id_urutan_rapor_sisipan    = $id;
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->id_mata_pelajaran   = $mata_pelajaran;
            $urutan_rapor_sisipan->created_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        }

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'rapor-sisipan/cetak-rapor/viewSetting/',
            'message' => 'Save Successfully'
        ];
    }

    public function datatablesViewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $mapel = MataPelajaran::with('urutan_rapor_sisipan', 'jurusan', 'jenis_mata_pelajaran')->isAktif()->get()->sortBy('urutan_rapor_sisipan.urutan');

        return Datatables::of($mapel)
            ->addColumn('urutan', function ($item) {
                return $item->urutan_rapor_sisipan->urutan ?? 'Urutan belum di Set';
            })
            ->addColumn('action', function ($item) {
                // $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                $data = array(
                    'id_mata_pelajaran'     => $item->id_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesViewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $deskripsi = RaporSisipanDeskripsi::get()->sortBy(['nm_mata_pelajaran', 'kd_deskripsi']);
        // ->sortBy('kd_deskripsi')->sortBy('nm_mata_pelajaran');
        return Datatables::of($deskripsi)
            ->editColumn('deskripsi1', function ($item) {
                if (strlen($item->deskripsi1) < 75) {
                    return $item->deskripsi1;
                } else {
                    return substr($item->deskripsi1, 0, 75) . '...';
                }
            })->editColumn('deskripsi2', function ($item) {

                if (strlen($item->deskripsi2) < 75) {
                    return $item->deskripsi2;
                } else {
                    return substr($item->deskripsi2, 0, 75) . '...';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_rapor_sisipan_deskripsi'     => $item->id_rapor_sisipan_deskripsi
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakRapor(Request $request, $id_semester, $id_kelas)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        $semester = Semester::find($id_semester);
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
        $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->where('nm_komponen_jenis_rapor', '!=', 'UAS')->orderByRaw('CAST(urutan AS SIGNED)')->get();

        $list_siswa = Siswa::with(['nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
            $q->where('id_semester', $id_semester)->where('nilai', '!=', 0);
        }, 'nilai_pribadi_sisipan.pribadi_sisipan'])->where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();
        $nilai_siswa = [];
        $data = [];

        if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smpypm1') {
            $komponen_sikap = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'SIKAP')
                ->whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->first();

            $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', '0');
            }])->where('nm_rapor', 'sisipan')->get();

            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor['nilai'] != '0') {

                        if ($komponen_sikap && $komponen_sikap->id_komponen_jenis_rapor == $nilai_rapor['id_komponen_jenis_rapor']) {
                            if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                                $hasil = 'A';
                            } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                                $hasil = 'B';
                            } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                                $hasil = 'C';
                            } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                                $hasil = 'D';
                            } else {
                                $hasil = 'Nilai tidak valid';
                            }

                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $hasil;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                        }
                    }
                }
            }
        } else {
            $nilai_siswa = [];
            $typeuts = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'STS')->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->first();
            $sumatif = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'like', '%SUMATIF%')->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->get();

            $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', '0');
            }])->where('nm_rapor', 'sisipan')->get();


            foreach ($rapors as $rapor) {
                //karna server tidak kuat terpaksa menggunakan cara ini
                $nilai_rapors = NilaiRapor::where('id_rapor', $rapor->id_rapor)->where('nilai', '!=', '0')->get();
                foreach ($nilai_rapors as  $nilai_rapor) {
                    if ($nilai_rapor->id_komponen_jenis_rapor == $typeuts?->id_komponen_jenis_rapor) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'sts'] = $nilai_rapor['nilai'];
                    } elseif (in_array($nilai_rapor->id_komponen_jenis_rapor, $sumatif->pluck('id_komponen_jenis_rapor')->toArray())) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                        if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'])) {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] = (int) $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] + (int) $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah']  + 1;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] = $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = 1;
                        }
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                    }
                }
            }
        }

        $kelas_rapor = KelasRapor::where('id_kelas', $id_kelas)->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'sisipan');
        })->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::where('nm_rapor', 'sisipan')->with([
            'mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
                $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            }
        ])->orderBy('urutan')->get();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            if ($kelas->tingkat == '3') {
                $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
                // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
            } else {
                // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
                $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
            }


            foreach ($kelompok_mapel_rapor as $k) {

                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        if ($kelas->tingkat == '3') {
                            $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '78';
                        } else {

                            $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '75';
                        }
                        // $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($k->sub_mata_pelajaran_rapor as $sub_mata_pelajaran_rapor) {
                //     $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['nama'] = $sub_mata_pelajaran_rapor->nm_sub_mata_pelajaran_rapor;
                //     foreach ($sub_mata_pelajaran_rapor->mata_pelajaran_sisipan as $mata_pelajaran_rapor) {
                //         if ($mata_pelajaran_rapor->jenis == '0') {
                //             $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                //             $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                //             $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                //             if ($kelas->tingkat == '3') {
                //                 $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '78';
                //             } else {

                //                 $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '75';
                //             }
                //             // $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                //             $data[$k->urutan + $sub_mata_pelajaran_rapor->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->where('nilai', '!=', '0')->get();
            $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ekstra Kurikuler');
            })->get();

            $pribadi_sisipan2 = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Kepribadian');
            })->get();

            $nilai_pengembangan_diri = [];
            $nilai_ekskul = [];
            foreach ($nilai_pribadi_siswa as $n) {
                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_ekskul[$n->id_siswa . 'nm_ekskul'][] = $n->pribadi_sisipan->nm_pribadi_sisipan;
                    if ($n->nilai >= 90 && $n->nilai <= 100) {
                        $hasil = 'A';
                    } elseif ($n->nilai >= 80 && $n->nilai < 90) {
                        $hasil = 'B';
                    } elseif ($n->nilai >= 70 && $n->nilai < 80) {
                        $hasil = 'C';
                    } elseif ($n->nilai >= 0 && $n->nilai < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }

                    $nilai_ekskul[$n->id_siswa . 'nilai_ekskul'][] = $hasil;
                } elseif (in_array($n->id_pribadi_sisipan, $pribadi_sisipan2->pluck('id_pribadi_sisipan')->toArray())) {
                    if ($n->nilai >= 90 && $n->nilai <= 100) {
                        $hasil = 'A';
                    } elseif ($n->nilai >= 80 && $n->nilai < 90) {
                        $hasil = 'B';
                    } elseif ($n->nilai >= 70 && $n->nilai < 80) {
                        $hasil = 'C';
                    } elseif ($n->nilai >= 0 && $n->nilai < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }

                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $hasil;
                } else {

                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                }
            }

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            if ($kelas->tingkat == '3') {
                return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-maryam', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri', 'kelompok_pribadi_sisipan', 'nilai_ekskul', 'semester', 'wali_kelas', 'tanggal_cetak'));
            } else {
                return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-maryam-merdeka', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri', 'kelompok_pribadi_sisipan', 'nilai_ekskul', 'semester', 'wali_kelas', 'tanggal_cetak'));
            }
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smksitiaminah') {
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($k_sisipan->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                //         if ($mata_pelajaran_sisipan->jenis == '0') {
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         } else {
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                        $hasil = 'A';
                    } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                        $hasil = 'B';
                    } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                        $hasil = 'C';
                    } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = $hasil;
                }
            }



            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $pribadi_sisipan_ekskul = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ekstra Kurikuler');
            })->get();

            $nilai_pengembangan_diri = [];
            $nilai_ekskul = [];
            foreach ($nilai_pribadi_siswa as $n) {

                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kehadiran->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                    // $nilai_pengembangan_diri[$n->id_siswa . 'ketidak_hadiran'][] = $n->pribadi_sisipan->nm_pribadi_sisipan;
                    // $nilai_pengembangan_diri[$n->id_siswa . 'nilai_ketidak_hadiran'][] = $n->nilai;
                } elseif (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_ekskul->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_ekskul[$n->id_siswa .  'ekskul'][] = $n->pribadi_sisipan->nm_pribadi_sisipan;
                    if ($n->nilai >= 90 && $n->nilai <= 100) {
                        $hasil = 'A';
                        $keterangan = 'Sangat Aktif Mengikuti Extra Tersebut';
                    } elseif ($n->nilai >= 80 && $n->nilai < 90) {
                        $hasil = 'B';
                        $keterangan = 'Aktif Mengikuti Extra Tersebut';
                    } elseif ($n->nilai >= 70 && $n->nilai < 80) {
                        $hasil = 'C';
                        $keterangan = 'Cukup Aktif Mengikuti Extra Tersebut';
                    } elseif ($n->nilai >= 0 && $n->nilai < 70) {
                        $hasil = 'D';
                        $keterangan = 'Kurang Aktif Mengikuti Extra Tersebut';
                    } else {
                        $hasil = '';
                        $keterangan = '';
                    }
                    $nilai_ekskul[$n->id_siswa . 'nilai_ekskul'][] = $hasil;
                    $nilai_ekskul[$n->id_siswa . 'keterangan_ekskul'][] = $keterangan;
                } else {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                }
            }

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-sitiaminah', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'pribadi_sisipan_kehadiran', 'nilai_pengembangan_diri', 'nilai_ekskul', 'wali_kelas', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', '!=', 'SAS')->orderByRaw('CAST(urutan AS SIGNED)')->get();
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($kelompok_mapel_rapor->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                //         if ($mata_pelajaran_sisipan->jenis == '0') {
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         } else {
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                //             $data[$kelompok_mapel_rapor->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smpypm2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'wali_kelas', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smktanada') {
            $nilai_siswa = [];
            $typeuts = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'uts')
                ->orWhere('nm_komponen_jenis_rapor', 'uas')
                ->whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->first();
            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor->id_komponen_jenis_rapor == $typeuts->id_komponen_jenis_rapor) { // if jenis rapor = uts
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'uts'] = $nilai_rapor['nilai']; // input nilai uts
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                        if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'])) {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] = (int)$nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] + (int)$nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah']  += 1;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] = $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = 1;
                        }
                    }
                }
            }

            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }

                    foreach ($list_siswa as $siswa) {
                        // hitung rata-rata nilai tugas
                        if (isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas'])) {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = (float) $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas'] / $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'jumlah'];
                        } else {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                        }

                        // hitung rata-rata dan kriteria
                        if (isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts'])) {
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = ($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] + (int) $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts']) / 2; // nilai rata-rata = (nilai rata-rata tugas + nilai uts) / 2

                            if ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 90 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] <= 100) {
                                $hasil = 'A';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 80 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 90) {
                                $hasil = 'B';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 70 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 80) {
                                $hasil = 'C';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 0 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 70) {
                                $hasil = 'D';
                            } else {
                                $hasil = 'Nilai tidak valid';
                            }

                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = $hasil;
                        } else {
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                        }
                    }
                }

                $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();

                $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();

                $pribadi_sisipan_kepribadian = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                    $query->where('nm_kelompok_pribadi_sisipan', 'Kepribadian');
                })->get();

                $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                    $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
                })->get();

                $pribadi_sisipan_catatan_orang_tua = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                    $query->where('nm_kelompok_pribadi_sisipan', 'Catatan Untuk Orang Tua');
                })->get();

                $nilai_pengembangan_diri = [];

                foreach ($nilai_pribadi_siswa as $n) {
                    if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kepribadian->pluck('id_pribadi_sisipan')->toArray())) {
                        $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                    } else {
                        $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                    }
                }

                // foreach ($k->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$k->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                //         if ($mata_pelajaran_sisipan->jenis == '0') {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uts')->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
            $list_komponen = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->orderBy('urutan', 'asc')->get();

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-tanada', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri', 'pribadi_sisipan_kepribadian', 'pribadi_sisipan_kehadiran', 'pribadi_sisipan_catatan_orang_tua', 'semester', 'wali_kelas', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1') {
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($k->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$k->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                //         if ($mata_pelajaran_rapor->jenis == '0') {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $nilai_pengembangan_diri = [];

            foreach ($nilai_pribadi_siswa as $n) {
                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->pribadi_sisipan->urutan] = $n->nilai;
                }
            }

            // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8])->orderBy('urutan', 'asc')->get();


            $list_komponen = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan'); //ini
            })->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8])->orderBy('urutan', 'asc')->get();

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smpypm1', compact('auth_data', 'pribadi_sisipan', 'nilai_pengembangan_diri', 'kelas', 'list_siswa', 'data', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'semester', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            if ($kelas->tingkat == 1) {
                $urutan = [1, 2, 5]; // urutan komponen untuk kelas 10
            } else {
                $urutan = [3, 4, 6]; // urutan komponen untuk kelas 11 dan 12
            }

            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', "!=", 'UAS')->whereIn('urutan', $urutan)->orderByRaw('CAST(urutan AS SIGNED)')->get();

            $nilai_siswa = [];
            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if (in_array($nilai_rapor['id_komponen_jenis_rapor'], $list_komponen->pluck('id_komponen_jenis_rapor')->toArray())) {
                        $id_siswa = $nilai_rapor['id_siswa'];
                        $id_mapel = $rapor['id_mata_pelajaran'];
                        $id_komponen = $nilai_rapor['id_komponen_jenis_rapor'];

                        if (!isset($nilai_siswa[$id_siswa])) {
                            $nilai_siswa[$id_siswa] = [];
                        }

                        if (!isset($nilai_siswa[$id_siswa][$id_mapel])) {
                            $nilai_siswa[$id_siswa][$id_mapel] = [];
                        }

                        if (!isset($nilai_siswa[$id_siswa][$id_mapel][$id_komponen])) {
                            $nilai_siswa[$id_siswa][$id_mapel][$id_komponen] = 0;
                        }

                        $nilai_siswa[$id_siswa][$id_mapel][$id_komponen] += (int) $nilai_rapor['nilai'];
                    }
                }
            }

            // dd($nilai_siswa);

            // hitung rata-rata nilai siswa berdasarkan mata pelajaran (nilai seluruh komponen / jumlah komponen)
            $rata_rata_nilai = [];
            foreach ($nilai_siswa as $id_siswa => $nilai_mapel) {
                $rata_rata_nilai[$id_siswa] = [];
                foreach ($nilai_mapel as $id_mapel => $nilai_komponen) {
                    $rata_rata_nilai[$id_siswa][$id_mapel] = [];
                    $total_nilai_mapel = 0;
                    $jumlah_komponen = 0;
                    foreach ($nilai_komponen as $id_komponen => $nilai) {
                        $total_nilai_mapel += $nilai;
                        $jumlah_komponen++;
                    }
                    $rata_rata_nilai[$id_siswa][$id_mapel] = round($total_nilai_mapel / $jumlah_komponen, 1);
                }
            }

            // dd($rata_rata_nilai);

            // hitung total nilai siswa dari seluruh rata-rata nilai
            $total_nilai = [];
            foreach ($rata_rata_nilai as $id_siswa => $nilai_mapel) {
                $total_nilai[$id_siswa] = 0;
                foreach ($nilai_mapel as $id_mapel => $nilai) {
                    $total_nilai[$id_siswa] += (int) $nilai;
                }
            }

            // dd($total_nilai);

            // nama kelompok mapel khusus kelas 10 MANU
            $custom_nm_kelompok_mapel = [
                1 => 'Kelompok A (Wajib)',
                2 => 'Kelompok B (Peminatan)',
                3 => 'Kelompok C (Muatan Lokal)',
            ];

            foreach ($kelompok_mapel_rapor as $k) {
                // jika kelas 10, maka ganti nama kelompok mapel
                if ($kelas->tingkat == 1) {
                    if ($k->urutan == 1) {
                        // jika kelompok mapel urutan 1, maka ganti nama kelompok mapel dengan Kelompok A (Wajib)
                        $k->nm_kelompok_mapel_rapor = $custom_nm_kelompok_mapel[1];
                    } elseif ($k->urutan == 2) {
                        // jika kelompok mapel urutan 2, maka ganti nama kelompok mapel dengan Kelompok B (Peminatan)
                        $k->nm_kelompok_mapel_rapor = $custom_nm_kelompok_mapel[2];
                    } elseif ($k->urutan == 3) {
                        // jika kelompok mapel urutan 3, maka ganti nama kelompok mapel dengan Kelompok C (Muatan Lokal)
                        $k->nm_kelompok_mapel_rapor = $custom_nm_kelompok_mapel[3];
                    }
                }

                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor; // assign nama kelompok mapel ke data
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $pribadi_sisipan_ekskul = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ekstra Kurikuler');
            })->get();

            $pribadi_sisipan_perminatan = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Perminatan Khusus');
            })->get();

            $nilai_pengembangan_diri = [];
            $nilai_ekskul = [];
            $nilai_perminatan = [];
            foreach ($nilai_pribadi_siswa as $n) {
                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kehadiran->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                } elseif (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_ekskul->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_ekskul[$n->id_siswa .  $n->id_pribadi_sisipan] = $n->nilai;
                    // predikat dan keterangan
                    if ($n->nilai == 'A') {
                        $n->keterangan = 'Sangat aktif mengikuti kegiatan ekstrakurikuler';
                    } elseif ($n->nilai == 'B') {
                        $n->keterangan = 'Aktif mengikuti kegiatan ekstrakurikuler';
                    } elseif ($n->nilai == 'C') {
                        $n->keterangan = 'Cukup aktif mengikuti kegiatan ekstrakurikuler';
                    } elseif ($n->nilai == 'D') {
                        $n->keterangan = 'Kurang aktif mengikuti kegiatan ekstrakurikuler';
                    } else {
                        $n->keterangan = '';
                    }
                    $nilai_ekskul[$n->id_siswa . $n->id_pribadi_sisipan . 'keterangan'] = $n->keterangan;
                } else if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_perminatan->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_perminatan[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                } else {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                }
            }

            arsort($total_nilai);

            $jumlah_komponen = count($list_komponen);

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-manu', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'wali_kelas', 'total_nilai', 'nilai_ekskul', 'pribadi_sisipan_kehadiran', 'pribadi_sisipan_perminatan', 'pribadi_sisipan_ekskul', 'nilai_pengembangan_diri', 'nilai_perminatan', 'jumlah_komponen', 'rata_rata_nilai', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smknu') {

            if ($kelas->tingkat == '1') {

                $list_komponen = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();
                // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();
            } else {
                // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();

                $list_komponen = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();
            }

            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        // if ($kelas->tingkat == '3') {
                        //     $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '78';
                        // } else {

                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = '70';
                        // }
                        // $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($k->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$k->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                //         if ($mata_pelajaran_sisipan->jenis == '0') {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                //             if ($kelas->tingkat == '3') {
                //                 $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '78';
                //             } else {

                //                 $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '75';
                //             }
                //             // $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $nilai_pengembangan_diri = [];
            $nilai_ekskul = [];
            foreach ($nilai_pribadi_siswa as $n) {

                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kehadiran->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                } else {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                }
            }

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            if ($kelas->tingkat == '1') {
                return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smknu1', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri',  'nilai_ekskul', 'semester', 'wali_kelas', 'pribadi_sisipan_kehadiran', 'tanggal_cetak'));
            } else {
                return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smknu2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri',  'nilai_ekskul', 'semester', 'wali_kelas', 'pribadi_sisipan_kehadiran', 'tanggal_cetak'));
            }
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'mtsnu') {


            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor['nilai'] != '0') {

                        if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                            $hasil = 'A';
                        } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                            $hasil = 'B';
                        } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                            $hasil = 'C';
                        } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                            $hasil = 'D';
                        } else {
                            $hasil = 'Nilai tidak valid';
                        }

                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = $hasil;
                    }
                }
            }


            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->orderBy('urutan', 'asc')->get();

            // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan', 'asc')->get();

            foreach ($kelompok_mapel_rapor as $k_sisipan) {
                $data[$k_sisipan->urutan]['nama'] = $k_sisipan->nm_kelompok_sisipan;
                foreach ($k_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                    if ($mata_pelajaran_sisipan->jenis == '0') {
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                        // $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                    } else {
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                        // if ($kelas->tingkat == '3') {
                        //     $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '78';
                        // } else {

                        // $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '70';
                        // }
                        // $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                    }
                }

                foreach ($k_sisipan->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                    $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                    foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                        if ($mata_pelajaran_sisipan->jenis == '0') {
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                            // $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                        } else {
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                            // if ($kelas->tingkat == '3') {
                            //     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '78';
                            // } else {

                            //     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = '75';
                            // }
                            // $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                        }
                    }
                }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $nilai_pengembangan_diri = [];
            $nilai_ekskul = [];
            foreach ($nilai_pribadi_siswa as $n) {

                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kehadiran->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                } else {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
                }
            }

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            // if ($kelas->tingkat == '1') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-mtsnu', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri',  'nilai_ekskul', 'semester', 'wali_kelas', 'pribadi_sisipan_kehadiran', 'tanggal_cetak'));
            // } else {
            //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smknu2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'nilai_pengembangan_diri',  'nilai_ekskul', 'semester', 'wali_kelas', 'pribadi_sisipan_kehadiran'));
            // }
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman') {
            $nilai_siswa = [];
            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                    if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'])) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] += $nilai_rapor['nilai'];
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] = $nilai_rapor['nilai'];
                    }
                }
            }

            foreach ($kelompok_mapel_rapor as $k) {
                if ($kelas->tingkat == '1' || $kelas->tingkat === '2') {
                    if ($k->urutan == '1') {
                        $data[$k->urutan]['nama'] = "A. KELOMPOK UMUM";
                    } elseif ($k->urutan == '2') {
                        $data[$k->urutan]['nama'] = "B. KELOMPOK KEJURUAN";
                    } elseif ($k->urutan == '3') {
                        $data[$k->urutan]['nama'] = "C. MAPEL PILIHAN";
                    } else {
                        continue;
                    }
                } else {
                    if ($k->urutan == '1') {
                        $data[$k->urutan]['nama'] = "A. MUATAN NASIONAL";
                    } elseif ($k->urutan == '2') {
                        $data[$k->urutan]['nama'] = "B. MUATAN KEWILAYAAN";
                    } elseif ($k->urutan == '3') {
                        $data[$k->urutan]['nama'] = "C. MUATAN PEMINATAN KEHURUAN";
                    } elseif ($k->urutan == '4') {
                        $data[$k->urutan]['nama'] = "D. MUATAN LOKAL";
                    } else {
                        continue;
                    }
                }

                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }

                    foreach ($list_siswa as $siswa) {
                        if (isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas']) && isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts'])) {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas'] / 4;
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = ($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] + $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts']) / 2;

                            if ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 90 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] <= 100) {
                                $hasil = 'A';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 80 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 90) {
                                $hasil = 'B';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 70 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 80) {
                                $hasil = 'C';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 0 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 70) {
                                $hasil = 'D';
                            } else {
                                $hasil = 'Nilai tidak valid';
                            }

                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = $hasil;
                        } else {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                        }
                    }
                }
            }

            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', '!=', 'UAS')->orderBy('urutan', 'asc')->get();

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smkypm3', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'tanggal_cetak'));
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2') {
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }

                // foreach ($k->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                //     $data[$k->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                //     foreach ($sub_kelompok_sisipan->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                //         if ($mata_pelajaran_rapor->jenis == '0') {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$k->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         }
                //     }
                // }
            }

            $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
            $pribadi_sisipan = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
                $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
            })->get();

            $nilai_pengembangan_diri = [];

            foreach ($nilai_pribadi_siswa as $n) {
                if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan->pluck('id_pribadi_sisipan')->toArray())) {
                    $nilai_pengembangan_diri[$n->id_siswa . $n->pribadi_sisipan->urutan] = $n->nilai;
                }
            }

            // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8])->orderBy('urutan', 'asc')->get();


            $list_komponen = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan'); //ini
            })
                ->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8])
                ->orderBy('urutan', 'asc')
                ->get();

                // dd($list_komponen);

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-smawh2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'wali_kelas', 'tanggal_cetak'));
        }
    }



    public function printCetakRapor2(Request $request, $id_semester, $id_kelas)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        $semester = Semester::find($id_semester);
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
        $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->orderBy('urutan', 'asc')->get();

        $list_siswa = Siswa::with(['nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
            $q->where('id_semester', $id_semester)->where('nilai', '!=', 0);
        }, 'nilai_pribadi_sisipan.pribadi_sisipan'])->where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();
        $nilai_siswa = [];
        $data = [];

        if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smpypm1') {
            $komponen_sikap = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'SIKAP')
                ->whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->first();

            $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', '0');
            }])->where('nm_rapor', 'sisipan')->get();

            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor['nilai'] != '0') {

                        if ($komponen_sikap && $komponen_sikap->id_komponen_jenis_rapor == $nilai_rapor['id_komponen_jenis_rapor']) {
                            if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                                $hasil = 'A';
                            } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                                $hasil = 'B';
                            } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                                $hasil = 'C';
                            } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                                $hasil = 'D';
                            } else {
                                $hasil = 'Nilai tidak valid';
                            }

                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $hasil;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                        }
                    }
                }
            }
        } else {
            $nilai_siswa = [];
            $typeuts = KomponenJenisRapor::where('nm_komponen_jenis_rapor', 'uts')->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->first();
            $sumatif = KomponenJenisRapor::whereNotIn('nm_komponen_jenis_rapor', ['uts', 'uas'])->whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->get();

            $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', '0');
            }])->where('nm_rapor', 'sisipan')->get();


            foreach ($rapors as $rapor) {
                //karna server tidak kuat terpaksa menggunakan cara ini
                $nilai_rapors = NilaiRapor::where('id_rapor', $rapor->id_rapor)->where('nilai', '!=', '0')->get();
                foreach ($nilai_rapors as  $nilai_rapor) {
                    if ($nilai_rapor->id_komponen_jenis_rapor == $typeuts->id_komponen_jenis_rapor) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'sts'] = $nilai_rapor['nilai'];
                    } elseif (in_array($nilai_rapor->id_komponen_jenis_rapor, $sumatif->pluck('id_komponen_jenis_rapor')->toArray())) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                        if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'])) {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] + $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah']  + 1;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_sumatif'] = $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'jumlah'] = 1;
                        }
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                    }
                }
            }
        }

        $kelas_rapor = KelasRapor::where('id_kelas', $id_kelas)->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'sisipan');
        })->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::where('nm_rapor', 'sisipan')->with([
            'mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
                $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            }
        ])->orderBy('urutan')->get();


        foreach ($rapors as $rapor) {
            foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                if ($nilai_rapor['nilai'] != '0') {

                    if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                        $hasil = 'A';
                    } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                        $hasil = 'B';
                    } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                        $hasil = 'C';
                    } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }

                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = $hasil;
                }
            }
        }


        $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->orderBy('urutan', 'asc')->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman') {
            $nilai_siswa = [];
            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor']] = $nilai_rapor['nilai'];
                    if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'])) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] += $nilai_rapor['nilai'];
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'total_nilai_tugas'] = $nilai_rapor['nilai'];
                    }
                }
            }

            foreach ($kelompok_mapel_rapor as $k) {
                if ($kelas->tingkat == '1' || $kelas->tingkat === '2') {
                    if ($k->urutan == '1') {
                        $data[$k->urutan]['nama'] = "A. KELOMPOK UMUM";
                    } elseif ($k->urutan == '2') {
                        $data[$k->urutan]['nama'] = "B. KELOMPOK KEJURUAN";
                    } elseif ($k->urutan == '3') {
                        $data[$k->urutan]['nama'] = "C. MAPEL PILIHAN";
                    } else {
                        continue;
                    }
                } else {
                    if ($k->urutan == '1') {
                        $data[$k->urutan]['nama'] = "A. MUATAN NASIONAL";
                    } elseif ($k->urutan == '2') {
                        $data[$k->urutan]['nama'] = "B. MUATAN KEWILAYAAN";
                    } elseif ($k->urutan == '3') {
                        $data[$k->urutan]['nama'] = "C. MUATAN PEMINATAN KEHURUAN";
                    } elseif ($k->urutan == '4') {
                        $data[$k->urutan]['nama'] = "D. MUATAN LOKAL";
                    } else {
                        continue;
                    }
                }

                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }

                    foreach ($list_siswa as $siswa) {
                        if (isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas']) && isset($nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts'])) {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'total_nilai_tugas'] / 4;
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = ($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] + $nilai_siswa[$siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran . 'uts']) / 2;

                            if ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 90 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] <= 100) {
                                $hasil = 'A';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 80 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 90) {
                                $hasil = 'B';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 70 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 80) {
                                $hasil = 'C';
                            } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] >= 0 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] < 70) {
                                $hasil = 'D';
                            } else {
                                $hasil = 'Nilai tidak valid';
                            }

                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = $hasil;
                        } else {
                            $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                            $nilai_siswa['rata_rata' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                            $nilai_siswa['kriteria' . $siswa->id_siswa . $mata_pelajaran_rapor->id_mata_pelajaran] = null;
                        }
                    }
                }
            }

            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->orderBy('urutan', 'asc')->get();

            $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_sisipan')->first();
            if (isset($tanggal)) {
                $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
            } else {
                $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-akhir-smkypm3', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'tanggal_cetak'));
        }
    }


    // public function printCetakRapor2(Request $request, $id_semester, $id_kelas)
    // {
    //     set_time_limit(-1);
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;
    //     $siswa = Siswa::where('id_siswa', $id_siswa)->with('pengguna')->first();
    //     $kelas = Kelas::where('id_kelas', $siswa->id_kelas)->with('jurusan')->first();
    //     $sekolah = Sekolah::first();
    //     //untuk sub
    //     $k = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->has('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

    //     $raporSisipanA = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
    //         ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
    //             $query->where('kode_jenis_mata_pelajaran', '=', 'A');
    //         })->whereHas('semester', function ($query) use ($id_semester) {
    //             $query->where('id_semester', '=', $id_semester);
    //         })
    //         ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

    //     $raporSisipanB = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
    //         ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
    //             $query->where('kode_jenis_mata_pelajaran', '=', 'B');
    //         })->whereHas('semester', function ($query) use ($id_semester) {
    //             $query->where('id_semester', '=', $id_semester);
    //         })
    //         ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

    //     $raporSisipanC = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
    //         ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
    //             $query->where('kode_jenis_mata_pelajaran', '=', 'C')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.1')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.2')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.3');
    //         })->whereHas('semester', function ($query) use ($id_semester) {
    //             $query->where('id_semester', '=', $id_semester);
    //         })
    //         ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

    //     $raporSisipanD = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
    //         ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
    //             $query->where('kode_jenis_mata_pelajaran', '=', 'D');
    //         })->whereHas('semester', function ($query) use ($id_semester) {
    //             $query->where('id_semester', '=', $id_semester);
    //         })
    //         ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
    //         ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

    //     $sub = SubRaporSisipan::with('sub_rapor_sisipan_mp', 'jenis_mata_pelajaran')->get();


    //     $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $siswa->id_kelas)->first();
    //     $semester = Semester::where('id_semester', $id_semester)->first();


    //     $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
    //     $list_nilai = NilaiRaporSisipan::where('id_siswa', $id_siswa)->with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')->get();
    //     $list_deskripsi = RaporSisipanDeskripsi::get();

    //     $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
    //     if ($setting == '0') {
    //         return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-akhir', compact('auth_data', 'kelas', 'siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas', 'sub', 'sekolah', 'id_semester', 'semester', 'list_deskripsi'));
    //     } elseif ($setting == '1') {
    //         echo "Maintane";
    //         //     $nilai_siswa = [];
    //         //     $nilai_komponen = [];
    //         //     if ($list_siswa) {
    //         //         $nilai = $list_nilai->toArray();
    //         //         foreach ($nilai as $nilaiRapor) {
    //         //             foreach ($nilaiRapor as $a) {
    //         //                 if (isset($nilaiRapor['id_komponen_jenis_rapor']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
    //         //                     $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
    //         //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
    //         //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
    //         //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                 }
    //         //             }
    //         //         }
    //         //     }

    //         //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
    //     } elseif ($setting == '2') {
    //         echo "Maintane";
    //         //     $nilai_siswa = [];
    //         //     $nilai_komponen = [];
    //         //     if ($list_siswa) {
    //         //         $nilai = $list_nilai->toArray();
    //         //         foreach ($nilai as $nilaiRapor) {
    //         //             foreach ($nilaiRapor as $a) {
    //         //                 if (isset($nilaiRapor['id_komponen_jenis_rapor']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
    //         //                     $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
    //         //                     $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
    //         //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
    //         //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
    //         //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
    //         //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
    //         //                     }
    //         //                 }
    //         //             }
    //         //         }
    //         //     }

    //         //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
    //     } else { }
    //     // $input = (object) $request->input();
    //     // $auth_data = $input->auth_data;
    //     // $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
    //     // $semester = Semester::find($id_semester);
    //     // $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
    //     // $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan', 'asc')->get();
    //     // $list_siswa = Siswa::with(['nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
    //     //     $q->where('id_semester', $id_semester)->where('nilai', '!=', 0);
    //     // }, 'nilai_pribadi_sisipan.pribadi_sisipan'])->where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
    //     //     $query->where('aktif_status_pengguna', '=', '1');
    //     // })->orderBy('nis_siswa')->get();
    //     // $nilai_siswa = [];
    //     // $data = [];

    //     // $nilai_siswa = [];
    //     // $typeuts = KomponenNilaiRaporSisipan::where('type', 'uts')->first();
    //     // $sumatif = KomponenNilaiRaporSisipan::where('type', 'sumatif')->get();

    //     // $rapor_sisipans = RaporSisipan::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)
    //     //     // ->with(['nilai_rapor_sisipan' => function ($q) {
    //     //     //     $q->where('nilai', '>', 0);
    //     //     // }])
    //     //     ->get();

    //     // // foreach ($rapor_sisipans as $rapor_sisipan) {
    //     // //     //karna server tidak kuat terpaksa menggunakan cara ini
    //     // //     $nilai_rapor_sisipans = NilaiRaporSisipan::where('id_rapor_sisipan', $rapor_sisipan->id_rapor_sisipan)->where('nilai', '!=', '0')->get();
    //     // //     foreach ($nilai_rapor_sisipans as  $nilai_rapor_sisipan) {
    //     // //         if (!empty($typeuts) && $nilai_rapor_sisipan->id_komponen_jenis_rapor == $typeuts->id_komponen_jenis_rapor) {
    //     // //             $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'sts'] = $nilai_rapor_sisipan['nilai'];
    //     // //         } elseif (in_array($nilai_rapor_sisipan->id_komponen_jenis_rapor, $sumatif->pluck('id_komponen_jenis_rapor')->toArray())) {
    //     // //             $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . $nilai_rapor_sisipan['id_komponen_jenis_rapor']] = $nilai_rapor_sisipan['nilai'];
    //     // //             if (isset($nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'total_nilai_sumatif'])) {
    //     // //                 $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'total_nilai_sumatif'] = $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'total_nilai_sumatif'] + $nilai_rapor_sisipan['nilai'];
    //     // //                 $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'jumlah'] = $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'jumlah']  + 1;
    //     // //             } else {
    //     // //                 $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'total_nilai_sumatif'] = $nilai_rapor_sisipan['nilai'];
    //     // //                 $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . 'jumlah'] = 1;
    //     // //             }
    //     // //         } else {
    //     // //             $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . $nilai_rapor_sisipan['id_komponen_jenis_rapor']] = $nilai_rapor_sisipan['nilai'];
    //     // //         }
    //     // //     }
    //     // // }

    //     // $kelas_sisipan = KelasSisipan::where('id_kelas', $id_kelas)->get();
    //     // $kelompok_sisipan = KelompokSisipan::with(['mata_pelajaran_sisipan' => function ($q) use ($kelas_sisipan) {
    //     //     $q->whereIn('id_mata_pelajaran_sisipan', $kelas_sisipan->pluck('id_mata_pelajaran_sisipan'))->with('mata_pelajaran');
    //     // }, 'sub_kelompok_sisipan.mata_pelajaran_sisipan' => function ($q) use ($kelas_sisipan) {
    //     //     $q->whereIn('id_mata_pelajaran_sisipan', $kelas_sisipan->pluck('id_mata_pelajaran_sisipan'))->with('mata_pelajaran');
    //     // }])->get();

    //     // if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
    //     //     $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan', 'asc')->get();
    //     //     $nilai_siswa = [];
    //     //     $total_nilai = [];

    //     //     foreach ($rapor_sisipans as $rapor_sisipan) {

    //     //         foreach ($rapor_sisipan->nilai_rapor_sisipan as  $nilai_rapor_sisipan) {
    //     //             if (in_array($nilai_rapor_sisipan['id_komponen_jenis_rapor'], $list_komponen->pluck('id_komponen_jenis_rapor')->toArray())) {
    //     //                 $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . $nilai_rapor_sisipan['id_komponen_jenis_rapor']] = $nilai_rapor_sisipan['nilai'];
    //     //                 if (isset($nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran']])) {
    //     //                     $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran']] += $nilai_rapor_sisipan['nilai'];
    //     //                 } else {
    //     //                     $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran']] = $nilai_rapor_sisipan['nilai'];
    //     //                 }

    //     //                 if (isset($total_nilai[$nilai_rapor_sisipan['id_siswa']])) {
    //     //                     $total_nilai[$nilai_rapor_sisipan['id_siswa']] += $nilai_rapor_sisipan['nilai'];
    //     //                 } else {
    //     //                     $total_nilai[$nilai_rapor_sisipan['id_siswa']] = $nilai_rapor_sisipan['nilai'];
    //     //                 }
    //     //             }
    //     //         }
    //     //     }

    //     //     foreach ($kelompok_sisipan as $k_sisipan) {
    //     //         $data[$k_sisipan->urutan]['nama'] = $k_sisipan->nm_kelompok_sisipan;
    //     //         foreach ($k_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
    //     //             if ($mata_pelajaran_sisipan->jenis == '0') {
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
    //     //             } else {
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
    //     //                 $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
    //     //             }
    //     //         }

    //     //         foreach ($k_sisipan->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
    //     //             $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
    //     //             foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
    //     //                 if ($mata_pelajaran_sisipan->jenis == '0') {
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
    //     //                 } else {
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
    //     //                     $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
    //     //                 }
    //     //             }
    //     //         }
    //     //     }


    //     //     $nilai_pribadi_siswa = NilaiPribadiSisipan::with('pribadi_sisipan')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
    //     //     $kelompok_sisipan = KelompokPribadiSisipan::where('nm_kelompok_pribadi_sisipan')->first();
    //     //     $pribadi_sisipan_kehadiran = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
    //     //         $query->where('nm_kelompok_pribadi_sisipan', 'Ketidak Hadiran');
    //     //     })->get();

    //     //     $pribadi_sisipan_ekskul = PribadiSisipan::whereHas('kelompok_pribadi_sisipan', function ($query) {
    //     //         $query->where('nm_kelompok_pribadi_sisipan', 'Ekstra Kurikuler');
    //     //     })->get();

    //     //     $nilai_pengembangan_diri = [];
    //     //     $nilai_ekskul = [];
    //     //     foreach ($nilai_pribadi_siswa as $n) {

    //     //         if (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_kehadiran->pluck('id_pribadi_sisipan')->toArray())) {
    //     //             $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
    //     //             // $nilai_pengembangan_diri[$n->id_siswa . 'ketidak_hadiran'][] = $n->pribadi_sisipan->nm_pribadi_sisipan;
    //     //             // $nilai_pengembangan_diri[$n->id_siswa . 'nilai_ketidak_hadiran'][] = $n->nilai;
    //     //         } elseif (in_array($n->id_pribadi_sisipan, $pribadi_sisipan_ekskul->pluck('id_pribadi_sisipan')->toArray())) {
    //     //             $nilai_ekskul[$n->id_siswa .  'ekskul'][] = $n->pribadi_sisipan->nm_pribadi_sisipan;
    //     //             if ($n->nilai >= 90 && $n->nilai <= 100) {
    //     //                 $hasil = 'A';
    //     //                 $keterangan = 'Sangat Aktif Mengikuti Extra Tersebut';
    //     //             } elseif ($n->nilai >= 80 && $n->nilai < 90) {
    //     //                 $hasil = 'B';
    //     //                 $keterangan = 'Aktif Mengikuti Extra Tersebut';
    //     //             } elseif ($n->nilai >= 70 && $n->nilai < 80) {
    //     //                 $hasil = 'C';
    //     //                 $keterangan = 'Cukup Aktif Mengikuti Extra Tersebut';
    //     //             } elseif ($n->nilai >= 0 && $n->nilai < 70) {
    //     //                 $hasil = 'D';
    //     //                 $keterangan = 'Kurang Aktif Mengikuti Extra Tersebut';
    //     //             } else {
    //     //                 $hasil = '';
    //     //                 $keterangan = '';
    //     //             }
    //     //             $nilai_ekskul[$n->id_siswa . 'nilai_ekskul'][] = $hasil;
    //     //             $nilai_ekskul[$n->id_siswa . 'keterangan_ekskul'][] = $keterangan;
    //     //         } else {
    //     //             $nilai_pengembangan_diri[$n->id_siswa . $n->id_pribadi_sisipan] = $n->nilai;
    //     //         }
    //     //     }


    //     //     arsort($total_nilai);

    //     //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-manu-akhir', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'wali_kelas', 'total_nilai', 'nilai_ekskul', 'pribadi_sisipan_kehadiran', 'nilai_pengembangan_diri'));
    //     // } else {
    //     //     return 'Sekolah anda belum menyetor Format rapor sisipan Akhir semester';
    //     // }
    // }


    public function viewSiswaUas(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-siswa-cetak-uas', compact('auth_data', 'id_semester', 'id_kelas'));
    }

    public function datatablesSiswaUas(Request $request, $id_semester, $id_kelas)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna')->get();

        return Datatables::of($list_siswa)
            // ->editColumn('deskripsi1', function ($item) {
            //     if (strlen($item->deskripsi1) < 75) {
            //         return $item->deskripsi1;
            //     } else {
            //         return substr($item->deskripsi1, 0, 75) . '...';
            //     }
            // })->editColumn('deskripsi2', function ($item) {

            //     if (strlen($item->deskripsi2) < 75) {
            //         return $item->deskripsi2;
            //     } else {
            //         return substr($item->deskripsi2, 0, 75) . '...';
            //     }
            // })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_siswa'     => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakRaporAkhir(Request $request, $id_semester, $id_siswa)
    {
        set_time_limit(1800);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_siswa', $id_siswa)->with('pengguna')->first();
        $kelas = Kelas::where('id_kelas', $siswa->id_kelas)->with('jurusan')->first();
        $sekolah = Sekolah::first();
        //untuk sub
        $k = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp')
            ->has('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanA = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'A');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanB = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'B');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanC = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'C')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.1')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.2')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.3');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanD = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'D');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $sub = SubRaporSisipan::with('sub_rapor_sisipan_mp', 'jenis_mata_pelajaran')->get();


        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $siswa->id_kelas)->first();
        $semester = Semester::where('id_semester', $id_semester)->first();


        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_nilai = NilaiRaporSisipan::where('id_siswa', $id_siswa)->with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')->get();
        $list_deskripsi = RaporSisipanDeskripsi::get();

        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
        if ($setting == '0') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-akhir', compact('auth_data', 'kelas', 'siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas', 'sub', 'sekolah', 'id_semester', 'semester', 'list_deskripsi'));
        } elseif ($setting == '1') {
            echo "Maintane";
            //     $nilai_siswa = [];
            //     $nilai_komponen = [];
            //     if ($list_siswa) {
            //         $nilai = $list_nilai->toArray();
            //         foreach ($nilai as $nilaiRapor) {
            //             foreach ($nilaiRapor as $a) {
            //                 if (isset($nilaiRapor['id_komponen_jenis_rapor']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
            //                     $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
            //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
            //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
            //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
            //                     }
            //                 }
            //             }
            //         }
            //     }

            //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } elseif ($setting == '2') {
            echo "Maintane";
            //     $nilai_siswa = [];
            //     $nilai_komponen = [];
            //     if ($list_siswa) {
            //         $nilai = $list_nilai->toArray();
            //         foreach ($nilai as $nilaiRapor) {
            //             foreach ($nilaiRapor as $a) {
            //                 if (isset($nilaiRapor['id_komponen_jenis_rapor']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
            //                     $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
            //                     $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
            //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
            //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
            //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
            //                     }
            //                 }
            //             }
            //         }
            //     }

            //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        }
    }

    public function addDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $mata_pelajaran = MataPelajaran::select('nm_mata_pelajaran')->isAktif()->groupBy('nm_mata_pelajaran')->get();
        return view('akademik/rapor-sisipan/cetak-rapor/add-deskripsi-rapor-sisipan', compact('auth_data', 'mata_pelajaran'));
    }

    public function viewPengembanganDiri(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();
        return view('akademik/rapor-sisipan/cetak-rapor/view-pengembangan-diri', compact('auth_data', 'id_semester', 'id_kelas', 'kelompok_pribadi_sisipan'));
    }

    public function datatablesPengembanganDiri(Request $request, $id_semester, $id_kelas)
    {
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with(
            [
                'nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
                    $q->where('id_semester', $id_semester);
                },
                'pengguna'
            ]
        );

        $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();

        return Datatables::of($list_siswa)
            ->addColumn('pribadi_sisipan', function ($item) use ($kelompok_pribadi_sisipan) {
                $nilai = [];
                foreach ($kelompok_pribadi_sisipan as $k) {
                    foreach ($k->pribadi_sisipan as $pribadi_sisipan) {
                        $cek = $item->nilai_pribadi_sisipan->firstWhere('id_pribadi_sisipan', $pribadi_sisipan->id_pribadi_sisipan);
                        if ($cek) {
                            if ($k->nm_kelompok_pribadi_sisipan == 'Catatan Untuk Orang Tua') {
                                $nilai[$k->urutan][] =  $cek->nilai;
                            } else {
                                $nilai[$k->urutan][] = $pribadi_sisipan->nm_pribadi_sisipan . ' : ' . $cek->nilai;
                            }
                        }
                    }
                }
                $jumlah_kelompok = count($kelompok_pribadi_sisipan);
                $data = [];
                for ($i = 1; $i <= $jumlah_kelompok; $i++) {
                    if (isset($nilai[$i])) {
                        $data[$i] = $nilai[$i];
                    } else {
                        $data[$i] = '';
                    }
                }
                return $data;
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                );
                return $data;
            })
            ->make(true);
    }

    public function templateExcelPengembanganDiri(Request $request, $id_kelas)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelompok_pribadi_sisipan = KelompokPribadiSisipan::with('pribadi_sisipan')->get();
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();
        $kelas = Kelas::find($id_kelas);

        $data['kelompok_pribadi_sisipan'] = $kelompok_pribadi_sisipan;
        $data['list_siswa'] = $list_siswa;
        $data['kelas'] = $kelas;
        return Excel::download(new PengembanganDiri($data), ' Template Pengembangan Diri (' . $kelas->nm_kelas . ').xlsx');
    }

    public function imporExcelPengembanganDiri(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-import-excel-pengembangan-diri', compact('auth_data'));
    }

    public function uploadExcelPengembanganDiri(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadPengembanganDiri, $request->file('file-excel'));
            } catch (\Exception $e) {
                return [
                    'status'     => 200, // FAILED
                    'message'     => "Gagal, Cek kembali apakah ada data nilai yang melebihi batas"
                ];
            }
            return [
                'status'     => 200, // FAILED
                'message'     => "Upload Sukses"
            ];
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }

    public function actionPengembanganDiri(Request $request, $mode, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($mode == 'delete') {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $nilai_pengembangan_diri = NilaiPribadiSisipan::where('id_siswa', $id_siswa)->where('id_semester', $semester_aktif->id_semester)->get();
            foreach ($nilai_pengembangan_diri as $pengembangan_diri) {
                $pengembangan_diri->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                $pengembangan_diri->save();
                $pengembangan_diri->delete();
            }

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Data Pengembangan Diri succesfully'

            ];
        }
    }
}
