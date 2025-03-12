<?php

namespace App\Http\Controllers\WaliMurid\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\RaporSisipan;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SubRaporSisipan;
use App\Models\WaliKelas;
use App\Models\WaliMurid;

class RaporSisipanSTSController extends Controller
{
    public function viewRaporSisipanSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();


        return view('wali-murid/rapor-sisipan/view-cetak-sts', compact('auth_data'));
    }

    public function cetakRaporSisipanSTS(Request $request, $id_semester)
    {
        // $id_semester, $id_kelas
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($id_semester == '0') {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        }

        $wali_murid = WaliMurid::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('siswa')->first();

        $id_kelas = $wali_murid->siswa->id_kelas;



        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        // $list_siswa = Siswa::where('id_kelas', $id_kelas)->get();
        // $kurikulum = Kurikulum::where('is_aktif',1)->orderBy('tahun_kurikulum', 'DESC')->with('mapel.mata_pelajaran.jenis_mata_pelajaran')->get();
        // $k = $kurikulum->firstWhere('id_jurusan', $kelas->jurusan->id_jurusan);
        //untuk sub
        $k = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp')
            ->has('mata_pelajaran.sub_rapor_sisipan_mp')
            ->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanA = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'A');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanB = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'B');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanC = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'C')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.1')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.2')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.3');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanD = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'D');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $sub = SubRaporSisipan::with('sub_rapor_sisipan_mp', 'jenis_mata_pelajaran')->get();


        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();

        // $rapor_sisipan = RaporSisipan::where('id_kelas', $id_kelas)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->get();

        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $siswa = Siswa::where('id_siswa', $wali_murid->siswa->id_siswa)->first();

        $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')
            ->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            })->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();

        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
        if ($setting == '0') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas', 'sub'));
        } elseif ($setting == '1') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
                            $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
                            $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
                            $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
                            $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
                            }
                        }
                    }
                }
            }

            return view('wali-murid/rapor-sisipan/cetak-sts', compact('auth_data', 'kelas', 'siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } elseif ($setting == '2') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
                            $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
                            // $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
                            // $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
                            // $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
                            // $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();

                            // if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas->id_komponen_nilai) {
                            //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
                            // }
                            // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
                            // }
                            // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
                            // }
                            // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
                            // }


                            $nilai_tugas1 = $list_komponen->firstWhere('urutan', '=', '1');
                            $nilai_tugas2 = $list_komponen->firstWhere('urutan', '=', '2');
                            $nilai_tugas3 = $list_komponen->firstWhere('urutan', '=', '3');
                            $nilai_tugas4 = $list_komponen->firstWhere('urutan', '=', '4');
                            $nilai_sumatif1 = $list_komponen->firstWhere('urutan', '=', '5');
                            $nilai_sumatif2 = $list_komponen->firstWhere('urutan', '=', '6');
                            $nilai_sumatif3 = $list_komponen->firstWhere('urutan', '=', '7');
                            $nilai_sumatif4 = $list_komponen->firstWhere('urutan', '=', '8');
                            $sts = $list_komponen->firstWhere('urutan', '=', '9');

                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '2'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas3->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '3'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas4->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '4'] =  $nilaiRapor['nilai'];
                            }

                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
                                // dd($nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5']);
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '7'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '8'] =  $nilaiRapor['nilai'];
                            }
                            if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                                $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . 'sts'] =  $nilaiRapor['nilai'];
                            }
                        }
                    }
                }
            }

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } elseif ($setting == '3') {
            echo 'maintane';
        } else { }


        return view('wali-murid/rapor-sisipan/view-cetak-sts', compact('auth_data'));
    }
}
