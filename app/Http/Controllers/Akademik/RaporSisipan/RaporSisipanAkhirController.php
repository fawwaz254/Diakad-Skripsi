<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\RaporSisipan;
use App\Models\Setting;
use Yajra\Datatables\Datatables;
use App\Models\Siswa;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanAkhirController extends Controller
{

    public function viewDaftarNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);
        return view('akademik/rapor-sisipan/daftar-nilai-sas/view-daftar-nilai-sas', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesDaftarNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = RaporSisipan::where('id_semester', $id_semester)
            ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->withCount(['nilai_rapor_sisipan' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])
            ->orderBy('created_at', 'desc');

        $komponen = KomponenNilaiRaporSisipan::where('status', '1')->count();

        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen) {
                $nilaiLengkap =  $item->kelas->siswa->count() * $komponen;
                $nilaiTerisi = $item->nilai_rapor_sisipan_count;
                if ($nilaiLengkap == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2) . '%';
                }

                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan
                );
                return $data;
            })
            ->make(true);
    }
    public function pdfDaftarNilaiSAS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan')->get();
        // $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->orderBy('nis_siswa')
            ->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
            })
            ->get();
        if ($setting == '0') {
            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_uts = $list_data->firstWhere('type', '=', 'uts');
                        $nilai_uas = $list_data->firstWhere('type', '=', 'uas');
                        //for average
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_uts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'uts'] =  $nilaiRapor['nilai'];
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'kkm'] =  $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_uas->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'uas'] =  $nilaiRapor['nilai'];
                        }
                        // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                        // }
                        // $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        // $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas-with-kkm', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } elseif ($setting == '1') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                        $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                        $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                        $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                        $nilai_sumatif3 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 3');
                        $nilai_sumatif4 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 4');
                        $sas = $list_data->firstWhere('nm_nilai', '=', 'SAS');

                        //for average
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } elseif ($setting == '3') {
            $list_kd_aktif = [];
            foreach ($list_data as $key => $data) {
                $data1 = $list_nilai->where('id_komponen_nilai', $data->id_komponen_nilai)->where('nilai', '!=', 0)->first();
                if (!empty($data1)) {
                    $list_kd_aktif[$key]['id_komponen_nilai'] =   $data->id_komponen_nilai;
                    $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
                }
            }

            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-kd', compact('auth_data', 'id_rapor_sisipan', 'list_kd_aktif', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        }
    }
}
