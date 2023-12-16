<?php

namespace App\Http\Controllers\Akademik\RaporSemester;

use App\Exports\TambahanRapor as ExcelTambahanRApor;
use App\Http\Controllers\Controller;
use App\Imports\UploadTambahanRapor;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\KelompokMapelRapor;
use App\Models\KelompokPribadiSisipan;
use App\Models\KelompokTambahanRapor;
use App\Models\KeteranganRapor;
use App\Models\KomponenJenisRapor;
use App\Models\NilaiPribadiSisipan;
use App\Models\NilaiRapor;
use App\Models\NilaiTambahanRapor;
use App\Models\PribadiSisipan;
use App\Models\Rapor;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TambahanRapor;
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

    public function printCetakRaporSemester(Request $request, $id_semester, $id_kelas = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->orderBy('nis_siswa')->get();
        $siswa = Siswa::find($id_semester);
        if (!empty($siswa)) {
            $list_siswa = Siswa::where('id_siswa', $id_semester)->orderBy('nis_siswa')->get();
            $semester = Semester::where('is_aktif_semester', '1')->first();
            $id_kelas = $siswa->id_kelas;
            $id_semester = $semester->id_semester;
        }

        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan', 'jenis_rapor')->first();
        $semester = Semester::find($id_semester);
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
        $list_komponen = KomponenJenisRapor::where('id_jenis_rapor', $kelas->id_jenis_rapor)->get();

        $nilai_siswa = [];
        $data = [];

        $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
            $q->where('nilai', '>', 0);
        }])->get();

        $keterangan_rapors = KeteranganRapor::whereIn('id_rapor', $rapors->pluck('id_rapor'))->get();

        foreach ($rapors as $rapor) {
            foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                if ($nilai_rapor['nilai'] != '0') {
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'nilai'] = $nilai_rapor['nilai'];

                    if ($kelas->type_rapor == '1') {
                        $keterangan_rapor = $keterangan_rapors->where('id_rapor', $rapor->id_rapor)->where('id_komponen_jenis_rapor', $nilai_rapor['id_komponen_jenis_rapor'])->first();

                        if ($keterangan_rapor) {
                            if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $keterangan_rapor->keterangan_a;
                            } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $keterangan_rapor->keterangan_b;
                            } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $keterangan_rapor->keterangan_c;
                            } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $keterangan_rapor->keterangan_d;
                            } else {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = '';
                            }
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan2'] = $keterangan_rapor->keterangan_d;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = '';
                        }
                    } elseif ($kelas->type_rapor == '2') {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan'] = $rapor->keterangan;
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'keterangan2'] = $rapor->keterangan2;
                    }
                }
            }
        }

        $kelas_rapor = KelasRapor::where('id_kelas', $id_kelas)->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::with([
            'mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
                $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            }
        ])->orderBy('urutan')->get();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smksitiaminah') {

            $kkm = 0;
            if ($kelas->tingkat == 1) {
                $kkm =  78;
            } elseif ($kelas->tingkat == 2) {
                $kkm =  79;
            } else {
                $kkm = 80;
            }

            foreach ($kelompok_mapel_rapor as $k) {
                if ($kelas->tingkat == '1') {
                    if ($k->nm_kelompok_mapel_rapor == 'Muatan Nasional') {
                        $data[$k->urutan]['nama'] = 'A. Mata Pelajaran Umum';
                    } elseif ($k->nm_kelompok_mapel_rapor == 'Muatan Kewilayahan') {
                        $data[$k->urutan]['nama'] = 'B. Mata Pelajaran Kejuruan';
                    } else {
                        continue;
                    }
                } else {
                    $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                }


                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] = $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = null;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] =  $kkm;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }
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

            $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
            })->get();
            $sikap_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Sikap')->first();
            $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
            $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

            $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
            })->get();

            $tambahan = array();

            $tambahan['ketidakhadiran'] = null;
            $tambahan['sikap'] = null;
            $tambahan['ekskul'] = null;

            $tambahan['catatan_wali_kelas'] = null;
            $tambahan['kelulusan'] = null;

            foreach ($nilai_tambahan_rapor as $n) {
                if (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                    $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                } elseif ($sikap_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                    $tambahan['sikap'][$n->id_siswa] = $n->nilai;
                } elseif ($catatan_wali_kelas_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                    $tambahan['catatan_wali_kelas'][$n->id_siswa] = $n->nilai;
                } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                    $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                } elseif ($kelulusan_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                    $tambahan['kelulusan'][$n->id_siswa] = $n->nilai;
                }
            }

            return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-sitiaminah', compact('auth_data', 'ekskul_tambahan_rapor', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester', 'kehadiran_tambahan_rapor', 'tambahan',  'wali_kelas'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {

            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                }
            }

            if ($kelas->jenis_rapor->nm_jenis_rapor == 'Merdeka') {

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
                return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-maryam', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas'));
            } else {

                $nilaiRapors = NilaiRapor::whereIn('id_rapor', $rapors->pluck('id_rapor'))
                    ->whereHas('komponen_jenis_rapor', function ($query) {
                        $query->where('nm_komponen_jenis_rapor', '!=', 'UAS');
                    })->get();


                foreach ($rapors as $rapor) {
                    foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                        if ($nilai_rapor['nilai'] != '0') {

                            if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'])) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] += $nilai_rapor['nilai'];
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'] += 1;
                            } else {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] = $nilai_rapor['nilai'];
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'] = 1;
                            }
                            if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai']) && isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'])) {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'rata-rata'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] / $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'];
                            }


                            $nilaiRaporMax = $nilaiRapors->where('id_rapor', $rapor['id_rapor'])->where('id_siswa', $nilai_rapor['id_siswa'])->sortByDesc('nilai');
                            $nilaiMax = $nilaiRaporMax->first();
                            if ($nilaiMax) {
                                $keterangan_rapor = $keterangan_rapors->where('id_rapor', $rapor['id_rapor'])->where('id_komponen_jenis_rapor', $nilaiMax->id_komponen_jenis_rapor)->first();
                                if ($keterangan_rapor) {
                                    if ($nilaiMax->nilai >= 90 && $nilaiMax->nilai <= 100) {
                                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_a;
                                    } elseif ($nilaiMax->nilai >= 80 && $nilaiMax->nilai < 90) {
                                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_b;
                                    } elseif ($nilaiMax->nilai >= 70 && $nilaiMax->nilai < 80) {
                                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_c;
                                    } elseif ($nilaiMax->nilai >= 0 && $nilaiMax->nilai < 70) {
                                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_d;
                                    } else {
                                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = '';
                                    }
                                }
                            }

                            $nilaiRaporMin = $nilaiRapors->where('id_rapor', $rapor['id_rapor'])->where('id_siswa', $nilai_rapor['id_siswa'])->sortBy('nilai');
                            $nilaiMin = $nilaiRaporMin->first();
                            if ($nilaiMin) {
                                $keterangan_rapor = $keterangan_rapors->where('id_rapor', $rapor->id_rapor)->where('id_komponen_jenis_rapor', $nilaiMin->id_komponen_jenis_rapor)->first();
                                if ($keterangan_rapor) {
                                    // if ($nilaiMin->nilai >= 90 && $nilaiMin->nilai <= 100) {
                                    //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_a;
                                    // } elseif ($nilaiMin->nilai >= 80 && $nilaiMin->nilai < 90) {
                                    //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_b;
                                    // } elseif ($nilaiMin->nilai >= 70 && $nilaiMin->nilai < 80) {
                                    //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_c;
                                    // } elseif ($nilaiMin->nilai >= 0 && $nilaiMin->nilai < 70) {
                                    //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_d;
                                    // } else {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan2;
                                    // }
                                }
                            }
                        }
                    }
                }

                $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
                $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
                })->get();
                $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
                })->get();
                $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
                $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

                $tambahan = array();

                $tambahan['ketidakhadiran'] = null;
                $tambahan['ekskul'] = null;
                $tambahan['catatan_wali_kelas'] = null;

                foreach ($nilai_tambahan_rapor as $n) {
                    if (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif ($catatan_wali_kelas_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                        $tambahan['catatan_wali_kelas'][$n->id_siswa] = $n->nilai;
                    }
                }

                return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-maryam2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas', 'tambahan', 'ekskul_tambahan_rapor', 'kehadiran_tambahan_rapor'));
            }
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            $nilaiRapors = NilaiRapor::whereIn('id_rapor', $rapors->pluck('id_rapor'))
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'UAS');
                })->get();


            foreach ($rapors as $rapor) {
                foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                    if ($nilai_rapor['nilai'] != '0') {

                        if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'])) {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] += $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'] += 1;
                        } else {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] = $nilai_rapor['nilai'];
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'] = 1;
                        }
                        if (isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai']) && isset($nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'])) {
                            $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'rata-rata'] = $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'nilai'] / $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran']  . 'jumlah'];
                        }


                        $nilaiRaporMax = $nilaiRapors->where('id_rapor', $rapor['id_rapor'])->where('id_siswa', $nilai_rapor['id_siswa'])->sortByDesc('nilai');
                        $nilaiMax = $nilaiRaporMax->first();
                        if ($nilaiMax) {
                            $keterangan_rapor = $keterangan_rapors->where('id_rapor', $rapor['id_rapor'])->where('id_komponen_jenis_rapor', $nilaiMax->id_komponen_jenis_rapor)->first();
                            if ($keterangan_rapor) {
                                if ($nilaiMax->nilai >= 90 && $nilaiMax->nilai <= 100) {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_a;
                                } elseif ($nilaiMax->nilai >= 80 && $nilaiMax->nilai < 90) {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_b;
                                } elseif ($nilaiMax->nilai >= 70 && $nilaiMax->nilai < 80) {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_c;
                                } elseif ($nilaiMax->nilai >= 0 && $nilaiMax->nilai < 70) {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = $keterangan_rapor->keterangan_d;
                                } else {
                                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan'] = '';
                                }
                            }
                        }

                        $nilaiRaporMin = $nilaiRapors->where('id_rapor', $rapor['id_rapor'])->where('id_siswa', $nilai_rapor['id_siswa'])->sortBy('nilai');
                        $nilaiMin = $nilaiRaporMin->first();
                        if ($nilaiMin) {
                            $keterangan_rapor = $keterangan_rapors->where('id_rapor', $rapor->id_rapor)->where('id_komponen_jenis_rapor', $nilaiMin->id_komponen_jenis_rapor)->first();
                            if ($keterangan_rapor) {
                                // if ($nilaiMin->nilai >= 90 && $nilaiMin->nilai <= 100) {
                                //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_a;
                                // } elseif ($nilaiMin->nilai >= 80 && $nilaiMin->nilai < 90) {
                                //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_b;
                                // } elseif ($nilaiMin->nilai >= 70 && $nilaiMin->nilai < 80) {
                                //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_c;
                                // } elseif ($nilaiMin->nilai >= 0 && $nilaiMin->nilai < 70) {
                                //     $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan_d;
                                // } else {
                                $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . 'keterangan2'] = $keterangan_rapor->keterangan2;
                                // }
                            }
                        }
                    }
                }
            }
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    if ($mata_pelajaran_rapor->jenis == '0') {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->keterangan;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    } else {
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    }
                }
            }


            $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
            $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
            })->get();
            $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
            })->get();
            $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
            $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

            $tambahan = array();

            $tambahan['ketidakhadiran'] = null;
            $tambahan['ekskul'] = null;
            $tambahan['catatan_wali_kelas'] = null;

            foreach ($nilai_tambahan_rapor as $n) {
                if (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                    $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                    $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                } elseif ($catatan_wali_kelas_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                    $tambahan['catatan_wali_kelas'][$n->id_siswa] = $n->nilai;
                }
            }


            return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-manu', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas', 'tambahan', 'ekskul_tambahan_rapor', 'kehadiran_tambahan_rapor'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smktanada') {
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                }
            }
            if ($kelas->jenis_rapor->nm_jenis_rapor == 'Merdeka') {

                $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
                $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
                })->get();
                $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
                })->get();

                $pengembangan_karakter_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Pengembangan Karakter');
                })->get();


                // $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
                $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

                $tambahan = array();

                $tambahan['pengembangan_karakter'] = null;
                $tambahan['ketidakhadiran'] = null;
                $tambahan['ekskul'] = null;
                $tambahan['Kelulusan'] = null;

                foreach ($nilai_tambahan_rapor as $n) {
                    if (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif (in_array($n->id_tambahan_rapor, $pengembangan_karakter_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['pengembangan_karakter'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif ($kelulusan_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                        $tambahan['Kelulusan'][$n->id_siswa] = $n->nilai;
                    }
                }

                return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-tanada', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas', 'tambahan', 'ekskul_tambahan_rapor', 'kehadiran_tambahan_rapor', 'pengembangan_karakter_tambahan_rapor'));
            } else {

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


                $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();
                $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
                })->get();
                $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
                })->get();

                $pengembangan_karakter_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
                    $query->where('nm_kelompok_tambahan_rapor', 'Pengembangan Karakter');
                })->get();


                // $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
                $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

                $tambahan = array();

                $tambahan['pengembangan_karakter'] = null;
                $tambahan['ketidakhadiran'] = null;
                $tambahan['ekskul'] = null;
                $tambahan['Kelulusan'] = null;

                foreach ($nilai_tambahan_rapor as $n) {
                    if (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif (in_array($n->id_tambahan_rapor, $pengembangan_karakter_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
                        $tambahan['pengembangan_karakter'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
                    } elseif ($kelulusan_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
                        $tambahan['Kelulusan'][$n->id_siswa] = $n->nilai;
                    }
                }

                return view('akademik/rapor-semester/cetak-rapor/cetak-rapor-tanada2', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas', 'tambahan', 'ekskul_tambahan_rapor', 'kehadiran_tambahan_rapor', 'pengembangan_karakter_tambahan_rapor'));
            }
        }

        return 'Sekolah anda tidak menggunakan Rapor';
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
        )->orderBy('nis_siswa');

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
        return Excel::download(new ExcelTambahanRApor($data), ' Template Tambahan Rapor (' . $kelas->nm_kelas . ').xlsx');
    }

    public function actionDataTambahan(Request $request, $mode, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($mode == 'delete') {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $nilai_tambahan_rapor = NilaiTambahanRapor::where('id_siswa', $id_siswa)->where('id_semester', $semester_aktif->id_semester)->get();
            foreach ($nilai_tambahan_rapor as $n) {
                $n->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                $n->save();
                $n->delete();
            }

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Data Tambahan RApor succesfully'

            ];
        }
    }

    public function  imporExcelDataTambahan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-semester/cetak-rapor/view-import-excel-tambahan-data', compact('auth_data'));
    }

    public function uploadExcelDataTambahan(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadTambahanRapor, $request->file('file-excel'));
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
}
