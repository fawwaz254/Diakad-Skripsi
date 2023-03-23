<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use App\Exports\RaporSisipanSAS;
use App\Exports\RaporSisipanSTS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\UploadRaporSisipanSAS;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\RaporSisipan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\Setting;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanAkhirController extends Controller
{
    // public function viewSemesterNilaiSAS(Request $request)
    // {
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $data_semester = LibDataAkademik::fetchDataSemester($auth_data);
    //     $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    //     return view('guru/rapor-sisipan/daftar-nilai-sas/view-semester-nilai-sas', compact('auth_data', 'data_semester', 'semester_aktif'));
    // }

    // public function actionSemesterNilaiSAS(Request $request)
    // {
    //     # code...
    //     $input = (object) $request->input();
    //     // $auth_data = $input->auth_data;

    //     $validator = Validator::make($request->all(), [
    //         'thn_akademik_semester' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return [
    //             'status' => 300, // FAILED
    //             'message' => $validator->errors()->first()
    //         ];
    //     } else {
    //         return [
    //             'status' => 204, // SUCCESS AND LOAD CONTENT
    //             'path' => 'rapor-sisipan/daftar-nilai-sas/' . $input->thn_akademik_semester
    //         ];
    //     }
    // }


    public function viewDaftarNilaiSAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);

        return view('guru/rapor-sisipan/daftar-nilai-sas/view-daftar-nilai-sas', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function datatablesDaftarNilaiSAS(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $status = $input->status;

        if (empty($input->tahun_ajaran)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $thn_akademik_semester = $semester_aktif->thn_akademik_semester;
        } else {
            $thn_akademik_semester = $input->tahun_ajaran;
        }

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = RaporSisipan::with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas', 'semester')->whereHas('semester', function ($query) use ($thn_akademik_semester) {
            $query->where('thn_akademik_semester', '=', $thn_akademik_semester);
        })->orderBy('created_at', 'desc');

        if ($status == '0') {
            $list_data = $list_data->where('id_pengguna', $auth_data->pengguna->id_pengguna);
        }

        $siswa = Siswa::with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })->get();
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first();
        if ($setting->value == '3') {
            $komponen1 = KomponenNilaiRaporSisipan::where('urutan', '1')->first()->id_komponen_nilai;
            $komponen2 = null;
        } else {
            $komponen1 = KomponenNilaiRaporSisipan::where('type', 'uts')->first()->id_komponen_nilai;
            $komponen2 = KomponenNilaiRaporSisipan::where('type', 'uas')->first()->id_komponen_nilai;
        }

        $nilaiRaporSisipans = NilaiRaporSisipan::whereIn('id_komponen_nilai', [$komponen1, $komponen2])->get();


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen1, $komponen2, $siswa, $setting, $nilaiRaporSisipans) {
                $allSiswa =  $siswa->where('id_kelas', $item->kelas->id_kelas)->count();
                if ($setting->value == '3') {
                    $nilaiRaporSisipan =    $nilaiRaporSisipans->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')->where('id_komponen_nilai', $komponen1);

                    $nilaiSiswa = $nilaiRaporSisipan->where('id_komponen_nilai', $komponen1)->count();

                    $data = array(
                        'jumlah_siswa' => $allSiswa,
                        'terisi_siswa' => $nilaiSiswa,
                        'setting'           => $setting->value,
                    );
                } else {
                    // $nilaiRaporSisipan =  $nilaiRaporSisipans->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')
                    // ->whereHas('siswa', function ($query) use ($item) {
                    //     $query->where('id_kelas', '=', $item->kelas->id_kelas);
                    // })
                    // ->get();

                    $nilaiUTSSiswa = $nilaiRaporSisipans->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')->where('id_komponen_nilai', $komponen1)->count();
                    $nilaiUASSiswa = $nilaiRaporSisipans->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')->where('id_komponen_nilai', $komponen2)->count();

                    $data = array(
                        'jumlah_siswa' => $allSiswa,
                        'terisi_siswa_sts' => $nilaiUTSSiswa,
                        'terisi_siswa_sas' => $nilaiUASSiswa,
                        'setting'           => $setting->value,
                    );
                }
                return $data;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran;
            })
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan,
                    'status' => $status,
                );
                return $data;
            })
            ->make(true);
    }


    public function imporExcelSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('guru/rapor-sisipan/daftar-nilai-sas/view-upload-nilai-sas', compact('auth_data'));
    }

    public function uploadRaporSisipanSAS(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadRaporSisipanSAS, $request->file('file-excel'));
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

    public function excelDaftarNilaiSAS(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })
            ->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
            })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1);
            })->get();

        $nilai_siswa = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                }
            }
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['rapor_sisipan'] = $rapor_sisipan;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor_sisipan'] = $id_rapor_sisipan;
        $nm_mata_pelajaran = str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran);
        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan SAS (' . $rapor_sisipan->kelas->nm_kelas . ' - ' . $nm_mata_pelajaran . ').xlsx');
    }

    // public function printDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    // {

    //     set_time_limit(1800);
    //     $input = (object) $request->input();
    //     $auth_data = $input->auth_data;

    //     $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

    //     $list_data = KomponenNilaiRaporSisipan::whereIn('urutan', [1, 2, 5, 6, 9])->get();
    //     $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();

    //     $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
    //         ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
    //             $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
    //         })
    //         ->whereHas('komponen_nilai', function ($query) {
    //             $query->whereIn('urutan', [1, 2, 5, 6, 9]);
    //         })->get();

    //     $nilai_siswa = [];
    //     $nilai_komponen = [];
    //     if ($list_siswa) {
    //         $nilai = $list_nilai->toArray();
    //         foreach ($nilai as $nilaiRapor) {
    //             // dd($nilaiRapor);
    //             foreach ($nilaiRapor as $a) {
    //                 $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

    //                 $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
    //                 $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
    //                 $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
    //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
    //                 }
    //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
    //                 }
    //                 if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
    //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
    //                 }
    //             }
    //         }
    //     }

    //     $data['nilai_siswa'] = $nilai_siswa;
    //     $data['nilai_komponen'] = $nilai_komponen;
    //     $data['rapor_sisipan'] = $rapor_sisipan;
    //     $data['list_siswa'] = $list_siswa;;
    //     $data['list_data'] = $list_data;
    //     $data['id_rapor_sisipan'] = $id_rapor_sisipan;

    //     return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS.xlsx');
    // }

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

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })->get();
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
