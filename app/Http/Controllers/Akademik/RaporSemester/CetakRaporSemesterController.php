<?php

namespace App\Http\Controllers\Akademik\RaporSemester;

use App\Exports\TambahanRapor;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\KelompokMapelRapor;
use App\Models\KelompokPribadiSisipan;
use App\Models\KelompokTambahanRapor;
use App\Models\KomponenJenisRapor;
use App\Models\NilaiPribadiSisipan;
use App\Models\PribadiSisipan;
use App\Models\Rapor;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;

class CetakRaporSemesterController extends Controller
{
    public function viewCetakRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/rapor-semester/cetak-rapor/view-cetak-rapor', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesCetakRaporSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = Kelas::where('is_aktif', 1)->with('jurusan')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $list_rapor = Rapor::whereHas('semester', function ($query) use ($id_semester) {
            $query->where('id_semester', '=', $id_semester);
        })->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->get();
        $semester = Semester::where('id_semester', $id_semester)->first();

        $kelas_rapor = KelasRapor::whereHas('mata_pelajaran_rapor', function ($q) {
            $q->where('jenis', '1');
        })->get();

        return Datatables::of($list_data)
            ->addColumn('kelas', function ($item) use ($kelas_rapor) {
                return $kelas_rapor->where('id_kelas', $item->id_kelas)->count();
            })
            ->addColumn('wali_kelas', function ($item) use ($wali_kelas) {
                $k = $wali_kelas->firstWhere('id_kelas', $item->id_kelas);
                return $k->guru->pengguna->nm_pengguna ?? '';
            })
            ->addColumn('rapor', function ($item) use ($list_rapor) {
                return $list_rapor->where('id_kelas', $item->id_kelas)->count();
            })->addColumn('semester', function () use ($semester) {
                return  $semester->tahun_ajaran . ' ' . $semester->nm_semester;
            })
            ->addColumn('action', function ($item)  use ($list_rapor, $id_semester) {
                $data = array(
                    'id_kelas'                  => $item->id_kelas,
                    'jumlah'                    => $list_rapor->where('id_kelas', $item->id_kelas)->count(),
                    'id_semester'               => $id_semester
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakRaporSemester(Request $request, $id_semester, $id_kelas)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        $semester = Semester::find($id_semester);
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
        $list_komponen = KomponenJenisRapor::where('id_jenis_rapor', $kelas->id_jenis_rapor)->get();
        $list_siswa = Siswa::with(['nilai_pribadi_sisipan' => function ($q) use ($id_semester) {
            $q->where('id_semester', $id_semester)->where('nilai', '!=', 0);
        }, 'nilai_pribadi_sisipan.pribadi_sisipan'])->where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();
        $nilai_siswa = [];
        $data = [];

        $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
            $q->where('nilai', '>', 0);
        }])->get();

        foreach ($rapors as $rapor) {
            foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                if ($nilai_rapor['nilai'] != '0') {
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'nilai'] = $nilai_rapor['nilai'];
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $nilai_rapor['keterangan'];
                }
            }
        }

        $kelas_rapor = KelasRapor::where('id_kelas', $id_kelas)->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::with([
            'mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
                $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            }
            // , 'sub_kelompok_mapel_rapor.mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
            //     $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            // }
        ])->orderBy('urutan')->get();
        // $kelompok_mapel_rapor = KelompokMapelRapor::with(['mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
        //     $q->whereIn('id_kelompok_mapel_rapor', $kelas_rapor->pluck('id_kelompok_mata_pelajaran_rapor'))->with('mata_pelajaran');
        // }, 'sub_kelompok_mapel_rapor.mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
        //     $q->whereIn('id_kelompok_mapel_rapor', $kelas_rapor->pluck('id_kelompok_mata_pelajaran_rapor'))->with('mata_pelajaran');
        // }])->get();

        // dd($kelompok_mapel_rapor);

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smksitiaminah') {
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

                // foreach ($k->sub_kelompok_mapel_rapor as $s) {
                //     $data[$k->urutan + $s->urutan]['nama'] = $s->nm_sub_kelompok_rapor;
                //     foreach ($s->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                //         if ($mata_pelajaran_rapor->jenis == '0') {
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                //         } else {
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = $mata_pelajaran_rapor->mata_pelajaran->nilai_kkm;
                //             $data[$k->urutan + $s->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
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

            return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-sitiaminah', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'pribadi_sisipan_kehadiran', 'nilai_pengembangan_diri', 'nilai_ekskul', 'wali_kelas'));
        }
    }

    public function viewDataTambahan(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelompok_tambahan_rapor = KelompokTambahanRapor::with('tambahan_rapor')->get();
        return view('akademik/rapor-semester/cetak-rapor/view-data-tambahan', compact('auth_data', 'id_semester', 'id_kelas', 'kelompok_tambahan_rapor'));
    }
    public function datatablesDataTambahan(Request $request, $id_semester, $id_kelas)
    {
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with(
            [
                'nilai_tambahan_rapor' => function ($q) use ($id_semester) {
                    $q->where('id_semester', $id_semester);
                },
                'pengguna'
            ]
        );

        $kelompok_tambahan_rapor = KelompokTambahanRapor::with('tambahan_rapor')->get();

        return Datatables::of($list_siswa)
            ->addColumn('tambahan_rapor', function ($item) use ($kelompok_tambahan_rapor) {
                $nilai = [];
                foreach ($kelompok_tambahan_rapor as $k) {
                    foreach ($k->tambahan_rapor as $tambahan_rapor) {
                        $cek = $item->nilai_tambahan_rapor->firstWhere('id_tambahan_rapor', $tambahan_rapor->id_tambahan_rapor);
                        if ($cek) {
                            if ($k->nm_kelompok_tambahan_rapor == 'Catatan Untuk Orang Tua') {
                                $nilai[$k->urutan][] =  $cek->nilai;
                            } else {
                                $nilai[$k->urutan][] = $tambahan_rapor->nm_tambahan_rapor . ' : ' . $cek->nilai;
                            }
                        }
                    }
                }
                $data = array(
                    'n1' => isset($nilai[1]) ? $nilai[1] : null,
                    'n2' => isset($nilai[2]) ? $nilai[2] : null,
                    'n3' => isset($nilai[3]) ? $nilai[3] : null,
                    'n4' => isset($nilai[4]) ? $nilai[4] : null,
                );
                return $data;
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                );
                return $data;
            })
            ->make(true);
    }
    public function templateExcelDataTambahan(Request $request, $id_kelas)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelompok_tambahan_rapor = KelompokTambahanRapor::with('tambahan_rapor')->get();

        $list_siswa = Siswa::where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $kelas = Kelas::find($id_kelas);

        $data['kelompok_tambahan_rapor'] = $kelompok_tambahan_rapor;
        $data['list_siswa'] = $list_siswa;
        $data['kelas'] = $kelas;
        return Excel::download(new TambahanRapor($data), ' Template Tambahan Rapor (' . $kelas->nm_kelas . ').xlsx');
    }
}
