<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use App\Exports\RaporSisipanSAS;
use App\Exports\RaporSisipanSTS;
use App\Exports\RekapRaporSisipanSTS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\UploadRaporSisipanSAS;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Rapor;
use App\Models\KomponenJenisRapor;
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
use App\Jobs\CreateRaporSisipan;
use App\Models\NilaiRapor;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanAkhirController extends Controller
{


    // public function generate(Request $request)
    // {
    //     set_time_limit(-1);
    //     $input = (object) $request->input();
    //     $komponen_nilais = KomponenNilaiRaporSisipan::whereIn('nm_nilai', ['NILAI SUMATIF 5', 'NILAI SUMATIF 6'])->get();
    //     // $id_kelass = RaporSisipan::groupBy('id_kelas')->pluck('id_kelas')->toArray();
    //     $rapor_sisipans = RaporSisipan::with(['nilai_rapor_sisipan' => function ($query) {
    //         $query->where('id_komponen_nilai', '=', 'B9hY71663719810632a5982c79e1');
    //     }])->get();
    //     // dd($rapor_sisipans[1]);
    //     // $siswas = Siswa::whereIn('id_kelas', $id_kelass)
    //     //     ->whereHas('pengguna.status_pengguna', function ($query) {
    //     //         $query->where('aktif_status_pengguna', '=', '1');
    //     //     });
    //     // dd($komponen_nilais);
    //     // foreach ($komponen_nilais as $komponen_nilai) {
    //     // $list_data = [];
    //     foreach ($rapor_sisipans as $rapor_sisipan) {
    //         // $siswa = $siswas->where('id_kelas', $rapor_sisipan->id_kelas)->get();
    //         // foreach ($siswa as $s) {
    //         foreach ($rapor_sisipan->nilai_rapor_sisipan as $s) {
    //             foreach ($komponen_nilais as $komponen) {
    //                 // dd($komponen);
    //                 $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
    //                 $list_data[] = [
    //                     'id_nilai_rapor_sisipan' =>  $id,
    //                     'id_rapor_sisipan' => $rapor_sisipan->id_rapor_sisipan,
    //                     'id_komponen_nilai' => $komponen->id_komponen_nilai,
    //                     'id_siswa' => $s->id_siswa,
    //                     'nilai' => 0,
    //                     'created_by' => 'batch syahrul',
    //                 ];
    //                 // dd($list_data);
    //             }
    //         }
    //         // }
    //         // $siswa = null;
    //         // dd($list_data);
    //         if (!empty($list_data)) {
    //             CreateRaporSisipan::dispatch($list_data);
    //             unset($list_data);
    //         }
    //     }


    //     echo " sukses";
    //     // }
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

        if (empty($input->thn_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $thn_akademik_semester = $semester_aktif->thn_akademik_semester;
        } else {
            $thn_akademik_semester = $input->thn_akademik_semester;
        }

        // $list_data = RaporSisipan::where('id_semester', $id_semester)
        //     ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
        //     ->withCount(['nilai_rapor_sisipan' => function ($q) {
        //         $q->where('nilai', '!=', 0);
        //     }])
        //     ->orderBy('created_at', 'desc');


        $list_data = Rapor::where('nm_rapor', 'sisipan')
            // ->with(['nilai_rapor' => function ($q) {
            //     $q->where('nilai', '!=', '0');
            // }, 'pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester'])
            ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->whereHas('semester', function ($query) use ($thn_akademik_semester) {
                $query->where('thn_akademik_semester', '=', $thn_akademik_semester);
            })
            ->withCount(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])

            ->orderBy('created_at', 'desc');



        $komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->count();

        if ($status == '0') {
            $list_data = $list_data->where('created_by', $auth_data->pengguna->id_pengguna);
        }

        // $komponen = KomponenNilaiRaporSisipan::where('status', '1')->count();

        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen) {
                $nilaiLengkap =  $item->kelas->siswa->count() * $komponen;
                $nilaiTerisi = $item->nilai_rapor_count;
                if ($nilaiLengkap == '0' || $nilaiTerisi == '0') {
                    $hasil = '0%';
                } else {
                    $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2) . '%';
                }

                return $hasil;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran;
            })
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor,
                    'status' => $status,
                    'id_kelas' => $item->id_kelas,
                    'id_semester' => $item->id_semester,
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
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })
            ->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
            })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
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
        // $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->orderBy('urutan', 'asc')->get();

        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })->orderBy('nis_siswa')->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            $nilai_siswa = [];

            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1);
                })->get();


            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                $rata = 0;
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];

                    if (isset($nilai_siswa[$nilaiRapor['id_siswa']])) {
                        $nilai_siswa[$nilaiRapor['id_siswa']] += $nilaiRapor['nilai'];
                    } else {
                        $nilai_siswa[$nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas-manu', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa',  'rapor_sisipan'));
        }




        // if ($setting == '0') {
        //     $nilai_siswa = [];
        //     if ($list_siswa) {
        //         $nilai = $list_nilai->toArray();
        //         foreach ($nilai as $nilaiRapor) {
        //             foreach ($nilaiRapor as $a) {
        //                 $nilai_uts = $list_data->firstWhere('type', '=', 'uts');
        //                 $nilai_uas = $list_data->firstWhere('type', '=', 'uas');
        //                 //for average
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_uts->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'uts'] =  $nilaiRapor['nilai'];
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'kkm'] =  $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_uas->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'uas'] =  $nilaiRapor['nilai'];
        //                 }
        //                 // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
        //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
        //                 // }
        //                 // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
        //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
        //                 // }
        //                 // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
        //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
        //                 // }
        //                 // if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
        //                 //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
        //                 // }
        //                 // $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
        //                 // $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
        //             }
        //         }
        //     }
        //     return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas-with-kkm', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_komponen', 'rapor_sisipan'));
        // } elseif ($setting == '1') {
        //     $nilai_siswa = [];
        //     $nilai_komponen = [];
        //     if ($list_siswa) {
        //         $nilai = $list_nilai->toArray();
        //         foreach ($nilai as $nilaiRapor) {
        //             foreach ($nilaiRapor as $a) {
        //                 $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

        //                 $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
        //                 $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
        //                 $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
        //                 $nilai_sumatif3 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 3');
        //                 $nilai_sumatif4 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 4');
        //                 $sas = $list_data->firstWhere('nm_nilai', '=', 'SAS');

        //                 //for average
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi3'] =  $nilaiRapor['nilai'];
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi4'] =  $nilaiRapor['nilai'];
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
        //                 }
        //                 if ($nilaiRapor['id_komponen_nilai']  == $sas->id_komponen_nilai) {
        //                     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sas'] =  $nilaiRapor['nilai'];
        //                 }
        //             }
        //         }
        //     }
        //     return view('guru/rapor-sisipan/daftar-nilai-sas/cetak-nilai-sas', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        // } elseif ($setting == '3') {
        //     foreach ($list_data as $key => $data) {
        //         $data1 = $list_nilai->where('id_komponen_nilai', $data->id_komponen_nilai)->where('nilai', '!=', 0)->first();
        //         if (!empty($data1)) {
        //             $list_kd_aktif[$key]['id_komponen_nilai'] =   $data->id_komponen_nilai;
        //             $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
        //         }
        //     }

        //     $nilai_siswa = [];
        //     if ($list_siswa) {
        //         $nilai = $list_nilai->toArray();
        //         foreach ($nilai as $nilaiRapor) {
        //             foreach ($nilaiRapor as $a) {
        //                 $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
        //                 $nilai_siswa[$nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
        //             }
        //         }
        //     }

        //     return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-kd', compact('auth_data', 'id_rapor_sisipan', 'list_kd_aktif', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        // }
    }
    public function inputNilai(Request $request, $id_rapor)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();
        // $list_data = KomponenJenisRapor::where('id_jenis_rapor', $rapor->kelas->id_jenis_rapor)->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->get();

        $dynamicColumns = [
            [
                'title' => 'NIS',
                'width' => 150,
            ], [

                'title' => 'Nama',
                'width' => 300,
            ]
        ];
        foreach ($list_data as $data) {
            array_push($dynamicColumns, [
                'title' => $data->nm_komponen_jenis_rapor,
            ]);
        }
        return view('guru/rapor-sisipan/daftar-nilai-sas/view-edit-nilai-sas', compact('auth_data', 'id_rapor',  'dynamicColumns'));
    }

    public function getNilai(Request $request, $id_rapor)
    {
        $rapor = Rapor::find($id_rapor);
        $siswa = Siswa::with('pengguna')->where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        $list_data = $siswa->map(function ($item) use ($id_rapor) {
            $data = array();
            $data['nis_siswa'] = $item->nis_siswa;
            $data['nm_pengguna'] = $item->pengguna->nm_pengguna;
            $nilai_rapor = NilaiRapor::where('id_siswa', $item->id_siswa)->where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    // $query->where('nm_komponen_jenis_rapor', "!=", 'UAS');
                })
                ->get();
            foreach ($nilai_rapor as $n) {
                $data[$n->id_komponen_jenis_rapor] = $n->nilai;
            }
            return $data;
        })->toArray();
        return response()->json($list_data);
    }

    public function actionDaftarNilaiSAS(Request $request, $mode, $id = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if ('editNilai') {
            $rapor = Rapor::with('kelas')->find($id);
            $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })->orderBy('nis_siswa')->get();
            $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->orderBy('urutan', 'asc')->get();
            $data = json_decode($input->data);

            foreach ($data as $d) {
                $siswa = $list_siswa->where('nis_siswa', $d[0])->first();
                if ($siswa) {
                    $no = 2;
                    $nilai_rapors = NilaiRapor::where('id_siswa', $siswa->id_siswa)->where('id_rapor', $id)
                        // ->whereHas('komponen_jenis_rapor', function ($query) {
                        // $query->where('nm_komponen_jenis_rapor', "!=", 'UAS');
                        // })
                        ->get();
                    foreach ($list_komponen as $komponen) {
                        $nilai_rapor = $nilai_rapors->where('id_komponen_jenis_rapor', $komponen->id_komponen_jenis_rapor)->first();
                        if ($nilai_rapor->nilai != $d[$no]) {
                            $nilai_rapor->nilai = $d[$no];
                            $nilai_rapor->save();
                        }
                        $no++;
                    }
                }
            }
            return [
                'status' => 300, // FAILED
                'message' => 'Update Sukses',
            ];
        }
    }
    public function printDaftarNilaiSAS(Request $request, $id_rapor)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();

        // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })
            // ->where('nm_komponen_jenis_rapor', '!=', 'uas')
            ->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor', function ($query) use ($id_rapor) {
            $query->where('id_rapor', '=', $id_rapor)->where('nilai', '!=', '0');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
            ->whereHas('komponen_jenis_rapor', function ($query) {
                // $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
            })->get();

        $nilai_siswa = [];
        $list_kd_aktif = [];
        // $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];
                    // $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                    // $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                    // $sts = $list_data->firstWhere('urutan', '=', '9');
                    // if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                    // }
                }
            }
        }
        foreach ($list_data as $key => $data) {
            $data1 = $list_nilai->where('id_komponen_jenis_rapor', $data->id_komponen_jenis_rapor)->where('nilai', '!=', 0)->first();
            if (!empty($data1)) {
                $list_kd_aktif[$key]['id_komponen_jenis_rapor'] =   $data->id_komponen_jenis_rapor;
                $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
            }
        }



        $data['nilai_siswa'] = $nilai_siswa;
        // $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor'] = $rapor;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_kd_aktif;
        $data['id_rapor'] = $id_rapor;

        return Excel::download(new RekapRaporSisipanSTS($data), 'Rekap Rapor Sisipan SAS (' . $rapor->kelas->nm_kelas . ' - ' . $rapor->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
    }
}
