<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenJenisRapor;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRapor;
use App\Models\NilaiRaporSisipan;
use App\Models\Rapor;
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
        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);
        return view('akademik/rapor-sisipan/daftar-nilai-sas/view-daftar-nilai-sas', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesDaftarNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($input->thn_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $thn_akademik_semester = $semester_aktif->thn_akademik_semester;
        } else {
            $thn_akademik_semester = $input->thn_akademik_semester;
        }

        $list_data = Rapor::
            // where('id_semester', $id_semester)->
            with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->withCount(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])->whereHas('semester', function ($query) use ($thn_akademik_semester) {
                $query->where('thn_akademik_semester', '=', $thn_akademik_semester);
            })
            ->where('nm_rapor', 'sisipan')
            ->orderBy('created_at', 'desc');


        // $komponen = KomponenNilaiRaporSisipan::where('status', '1')->count();
        $komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->count();


        return Datatables::of($list_data)
            // ->addColumn('jumlah', function ($item) use ($komponen) {
            //     $nilaiLengkap =  $item->kelas->siswa->count() * $komponen;
            //     $nilaiTerisi = $item->nilai_rapor_count;
            //     if ($nilaiLengkap == '0' || $nilaiTerisi == '0') {
            //         $hasil = '0%';
            //     } else {
            //         $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2) . '%';
            //     }

            //     return $hasil;
            // })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan
                );
                return $data;
            })
            ->make(true);
    }
    public function pdfDaftarNilaiSAS(Request $request, $id_rapor)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->orderBy('urutan')->get();

        // $list_data = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan')->get();
        // $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })
            ->orderBy('nis_siswa')
            ->get();

        $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
            ->whereHas('rapor', function ($query) use ($id_rapor) {
                $query->where('id_rapor', $id_rapor);
            })
            ->get();
        if ($setting == '0') {
            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_uts = $list_data->firstWhere('nm_jenis_rapor', '=', 'uts');
                        $nilai_uas = $list_data->firstWhere('nm_jenis_rapor', '=', 'uas');
                        //for average
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_uts->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'uts'] =  $nilaiRapor['nilai'];
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'kkm'] =  $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_uas->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'uas'] =  $nilaiRapor['nilai'];
                        }
                        // if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif3->id_komponen_jenis_rapor) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif4->id_komponen_jenis_rapor) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        // }
                        // if ($nilaiRapor['id_komponen_jenis_rapor']  == $sas->id_komponen_jenis_rapor) {
                        //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                        // }
                        // $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        // $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas-with-kkm', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_komponen', 'rapor'));
        } elseif ($setting == '1') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];

                        $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                        $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                        $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                        $nilai_sumatif3 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 3');
                        $nilai_sumatif4 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 4');
                        $sas = $list_data->firstWhere('nm_nilai', '=', 'SAS');

                        //for average
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif3->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif4->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $sas->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor'));
        } elseif ($setting == '3') {
            $list_kd_aktif = [];
            foreach ($list_data as $key => $data) {
                $data1 = $list_nilai->where('id_komponen_jenis_rapor', $data->id_komponen_jenis_rapor)->where('nilai', '!=', 0)->first();
                if (!empty($data1)) {
                    $list_kd_aktif[$key]['id_komponen_jenis_rapor'] =   $data->id_komponen_jenis_rapor;
                    $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
                }
            }

            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor'] . 'kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-kd', compact('auth_data', 'id_rapor', 'list_kd_aktif', 'list_siswa', 'nilai_siswa', 'rapor'));
        }
    }
}
