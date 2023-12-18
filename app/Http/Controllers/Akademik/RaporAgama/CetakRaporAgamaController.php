<?php

namespace App\Http\Controllers\Akademik\RaporAgama;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KelasRapor;
use App\Models\KelompokMapelRapor;
use App\Models\KomponenJenisRapor;
use App\Models\Rapor;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class CetakRaporAgamaController extends Controller
{
    public function viewCetakRaporAgama(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('akademik/rapor-agama/cetak-rapor/view-cetak-rapor', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesCetakRaporAgama(Request $request)
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
        })->where('nm_rapor', 'agama')->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->get();
        $semester = Semester::where('id_semester', $id_semester)->first();

        $kelas_rapor = KelasRapor::whereHas('mata_pelajaran_rapor', function ($q) {
            $q->where('jenis', '1');
        })->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'agama');
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

    public function printCetakRaporAgama(Request $request, $id_semester, $id_kelas = null)
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
        $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'agama');
        })->get();

        $nilai_siswa = [];
        $data = [];

        $rapors = Rapor::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with(['nilai_rapor' => function ($q) {
            $q->where('nilai', '!=', '0');
        }])->where('nm_rapor', 'agama')->get();

        // $keterangan_rapors = KeteranganRapor::whereIn('id_rapor', $rapors->pluck('id_rapor'))->get();

        foreach ($rapors as $rapor) {
            foreach ($rapor->nilai_rapor as  $nilai_rapor) {
                if ($nilai_rapor['nilai'] != '0') {
                    $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'nilai'] = $nilai_rapor['nilai'];
                    if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = 'A';
                    } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = 'B';
                    } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = 'C';
                    } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = 'D';
                    } else {
                        $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = '';
                    }
                }
            }
        }

        $kelas_rapor = KelasRapor::where('id_kelas', $id_kelas)->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
            $q->where('nm_rapor', 'agama');
        })->get();
        $kelompok_mapel_rapor = KelompokMapelRapor::where('nm_rapor', 'agama')->with([
            'mata_pelajaran_rapor' => function ($q) use ($kelas_rapor) {
                $q->whereIn('id_mata_pelajaran_rapor', $kelas_rapor->pluck('id_mata_pelajaran_rapor'))->with('mata_pelajaran');
            }
        ])->orderBy('urutan')->get();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {

            $kkm = 0;
            if ($kelas->tingkat == 1) {
                $kkm =  75;
            } elseif ($kelas->tingkat == 2) {
                $kkm =  75;
            } else {
                $kkm = 78;
            }
            foreach ($kelompok_mapel_rapor as $k) {
                $data[$k->urutan]['nama'] = $k->nm_kelompok_mapel_rapor;
                foreach ($k->mata_pelajaran_rapor as $mata_pelajaran_rapor) {
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['nm_point'][] =  $mata_pelajaran_rapor->mata_pelajaran->nm_mata_pelajaran;
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_rapor->id_mata_pelajaran;
                    $data[$k->urutan]['data'][$mata_pelajaran_rapor->urutan]['kkm'][] = $kkm;
                }
            }

            // foreach ($rapors as $rapor) {
            //     foreach ($rapor->nilai_rapor as  $nilai_rapor) {
            //         if ($nilai_rapor['nilai'] >= 90 && $nilai_rapor['nilai'] <= 100) {
            //             $hasil = 'A';
            //         } elseif ($nilai_rapor['nilai'] >= 80 && $nilai_rapor['nilai'] < 90) {
            //             $hasil = 'B';
            //         } elseif ($nilai_rapor['nilai'] >= 70 && $nilai_rapor['nilai'] < 80) {
            //             $hasil = 'C';
            //         } elseif ($nilai_rapor['nilai'] >= 0 && $nilai_rapor['nilai'] < 70) {
            //             $hasil = 'D';
            //         } else {
            //             $hasil = 'Nilai tidak valid';
            //         }
            //         $nilai_siswa[$nilai_rapor['id_siswa'] . $rapor['id_mata_pelajaran'] . $nilai_rapor['id_komponen_jenis_rapor'] . 'predikat'] = $hasil;
            //     }
            // }

            // $nilai_tambahan_rapor = NilaiTambahanRapor::with('tambahan_rapor')->whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->where('id_semester', $id_semester)->get();


            // $predikat_sikap_spiritual = TambahanRapor::where('nm_tambahan_rapor', 'Predikat Sikap Spiritual')->first();
            // $deskripsi_sikap_spiritual = TambahanRapor::where('nm_tambahan_rapor', 'Deskripsi Sikap Spiritual')->first();
            // $predikat_sikap_sosial = TambahanRapor::where('nm_tambahan_rapor', 'Predikat Sikap Sosial')->first();
            // $deskripsi_sikap_sosial = TambahanRapor::where('nm_tambahan_rapor', 'Deskripsi Sikap Sosial')->first();
            // $kehadiran_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
            //     $query->where('nm_kelompok_tambahan_rapor', 'Ketidak Hadiran');
            // })->get();
            // $ekskul_tambahan_rapor = TambahanRapor::whereHas('kelompok_tambahan_rapor', function ($query) {
            //     $query->where('nm_kelompok_tambahan_rapor', 'Ekstrakurikuler');
            // })->get();
            // $catatan_wali_kelas_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Catatan Wali Kelas')->first();
            // $kelulusan_tambahan_rapor = TambahanRapor::where('nm_tambahan_rapor', 'Kelulusan')->first();

            // $tambahan = array();



            $tambahan = array();

            // $tambahan['predikat_sikap_spiritual'] = null;
            // $tambahan['deskripsi_sikap_spiritual'] = null;
            // $tambahan['predikat_sikap_sosial'] = null;
            // $tambahan['deskripsi_sikap_sosial'] = null;
            // $tambahan['ketidakhadiran'] = null;
            // $tambahan['ekskul'] = null;
            // $tambahan['catatan_wali_kelas'] = null;


            // foreach ($nilai_tambahan_rapor as $n) {
            //     if ($predikat_sikap_spiritual->id_tambahan_rapor == $n->id_tambahan_rapor) {
            //         $tambahan['predikat_sikap_spiritual'][$n->id_siswa] = $n->nilai;
            //     } elseif ($deskripsi_sikap_spiritual->id_tambahan_rapor == $n->id_tambahan_rapor) {
            //         $tambahan['deskripsi_sikap_spiritual'][$n->id_siswa] = $n->nilai;
            //     } elseif ($predikat_sikap_sosial->id_tambahan_rapor == $n->id_tambahan_rapor) {
            //         $tambahan['predikat_sikap_sosial'][$n->id_siswa] = $n->nilai;
            //     } elseif ($deskripsi_sikap_sosial->id_tambahan_rapor == $n->id_tambahan_rapor) {
            //         $tambahan['deskripsi_sikap_sosial'][$n->id_siswa] = $n->nilai;
            //     } elseif (in_array($n->id_tambahan_rapor, $kehadiran_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
            //         $tambahan['ketidakhadiran'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
            //     } elseif (in_array($n->id_tambahan_rapor, $ekskul_tambahan_rapor->pluck('id_tambahan_rapor')->toArray())) {
            //         $tambahan['ekskul'][$n->id_siswa][$n->id_tambahan_rapor] = $n->nilai;
            //     } elseif ($catatan_wali_kelas_tambahan_rapor->id_tambahan_rapor == $n->id_tambahan_rapor) {
            //         $tambahan['catatan_wali_kelas'][$n->id_siswa] = $n->nilai;
            //     }
            // }

            return view('akademik/rapor-agama/cetak-rapor/cetak-rapor-maryam', compact('auth_data', 'list_siswa', 'nilai_siswa', 'data', 'kelas', 'list_komponen', 'semester',  'wali_kelas'));
        }

        return 'Sekolah anda tidak menggunakan Rapor';
    }
}
