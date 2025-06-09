<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use App\Exports\RaporSisipanSTS;
use App\Exports\RekapRaporSisipanSTS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
// use App\Models\Rapor;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
// use App\Models\KomponenNilaiRapor;
use App\Models\NilaiRapor;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use App\Imports\UploadRaporSisipanSTS;
use App\Jobs\CreateNilaiRapor;
use App\Jobs\CreateRapor;
use App\Models\JenisMataPelajaran;
use App\Models\Jurusan;
use App\Models\KelasRapor;
use App\Models\KelasSisipan;
use App\Models\KomponenJenisRapor;
use App\Models\Rapor;
use App\Models\Semester;
// use App\Models\Rapor;
use App\Models\Setting;
use Auth;
use DB;
use Session;
use Validator;
use Barryvdh\Debugbar\Facades\Debugbar;
use Barryvdh\Debugbar\Twig\Extension\Debug;

class RaporSisipanController extends Controller
{


    // public function updateData(Request $request)
    // {
    //     set_time_limit(-1);


    //     // $list_siswa = Siswa::with('calon_siswa')->get();

    //     // foreach ($list_siswa as $siswa) {

    //     //     if (empty($siswa->nisn)) {
    //     //         $siswa->nisn_siswa = $siswa->calon_siswa->nisn_siswa;
    //     //         $siswa->updated_by = 'updatenis';
    //     //         $siswa->save();
    //     //     }
    //     // }



    //     $kelas = Kelas::whereIn('tingkat', [1, 2])->get();
    //     $siswa = Siswa::whereIn('id_kelas', $kelas->pluck('id_kelas'))->get();
    //     $komponen_jenis_rapor_rapor = KomponenNilaiRapor::whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->get();
    //     // $komponen_jenis_rapor_rapor = KomponenNilaiRapor::whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->get();

    //     $nilai_rapor = NilaiRapor::with('komponen_jenis_rapor')->whereIn('id_siswa', $siswa->pluck('id_siswa'))->whereIn('id_komponen_jenis_rapor', $komponen_jenis_rapor_rapor->pluck('id_komponen_jenis_rapor'))->get();


    //     foreach ($nilai_rapor as $nilai_rapor) {

    //         if ($nilai_rapor->komponen_jenis_rapor->urutan == '1') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh12169811641765373342382fd";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '2') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh1216981164176537334238302";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '3') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh1216981164176537334238304";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '4') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh1216981164176537334238307";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '5') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh1216981164176537334238309";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '6') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh121698116417653733423830b";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '7') {
    //             $nilai_rapor->deleted_by = "syahrul";
    //             $nilai_rapor->save();
    //             $nilai_rapor->delete();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '8') {
    //             $nilai_rapor->deleted_by = "syahrul";
    //             $nilai_rapor->save();
    //             $nilai_rapor->delete();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '9') {
    //             $nilai_rapor->id_komponen_jenis_rapor = "Qjh121698116417653733423830d";
    //             $nilai_rapor->save();
    //         } elseif ($nilai_rapor->komponen_jenis_rapor->urutan == '10') {
    //             $nilai_rapor->deleted_by = "syahrul";
    //             $nilai_rapor->save();
    //             $nilai_rapor->delete();
    //         }
    //     }

    //     return 'done';
    //     // $nilai_rapor = NilaiRapor::get();
    // }

    public function viewDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataSemester($auth_data);
        return view('guru/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function addDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        // $data['list_mapel'] = MataPelajaran::with('jenis_mata_pelajaran')->get();
        $data['list_kelas'] = Kelas::where('is_aktif', 1)->get();
        // $data['list_jurusan'] = Jurusan::all();
        // $data['jenis_mapel'] = JenisMataPelajaran::all();
        $data['semester_aktif'] = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data['data_semester'] = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-sisipan/daftar-nilai-sts/add-daftar-nilai-sts', compact('auth_data'), $data);
    }

    public function actionDaftarNilaiSTS(Request $request, $mode, $id = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        // $auth_data = auth_data();
        // $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        if ($mode == 'delete') {
            DB::beginTransaction();
            try {
                DB::table('nilai_rapor')->where('id_rapor', $id)->delete();
                $rapor = Rapor::where('id_rapor', $id)->first();
                if ($rapor) {
                    $rapor->delete();
                }

                DB::Commit();
                return [
                    'status' => 202,
                    'path' => 'rapor-sisipan/daftar-nilai-sts',
                    'message' => 'Delete Rapor Sisipan Successfully'
                ];
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                DB::rollback();
                return [
                    'status' => 202,
                    'path' => 'rapor-sisipan/daftar-nilai-sts',
                    'message' => 'Delete Rapor Sisipan Gagal, Silahkan coba lagi'
                ];
            }
        }

        if ($mode == 'add') {
            $s = Semester::find($input->id_semester);
            $cekDuplicate = Rapor::where('id_kelas', $input->id_kelas)->where('id_mata_pelajaran', $input->id_mata_pelajaran)
                ->where('nm_rapor', 'sisipan')
                ->whereHas('semester', function ($query) use ($s) {
                    $query->where('thn_akademik_semester', '=', $s->thn_akademik_semester)->where('kode_semester', $s->kode_semester);
                })
                // ->where('id_semester', $input->id_semester)
                ->with('pengguna')->first();


            if ($cekDuplicate) {
                return [
                    'status' => 300, // FAILED
                    'message' => 'Kelas dan Mapel Sudah digunakan oleh ' . $cekDuplicate->pengguna->nm_pengguna,
                ];
            }
        }

        $validator = Validator::make($request->all(), [
            'id_mata_pelajaran' => 'required',
            'id_kelas'              => 'required'
        ]);

        if ($validator->fails() && $mode == 'add') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            $now = Carbon::now();
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                    $rapor                      = new Rapor;
                    $rapor->id_rapor            = $id;
                    $rapor->id_semester         = $input->id_semester;
                    $rapor->id_mata_pelajaran   = $input->id_mata_pelajaran;
                    $rapor->id_kelas            = $input->id_kelas;
                    $rapor->nm_rapor            = 'sisipan';
                    // $rapor->id_pengguna         = auth_data()->pengguna->id_pengguna;
                    $rapor->created_by          = auth_data()->pengguna->id_pengguna;
                    $rapor->save();

                    $siswa = Siswa::where('id_kelas', $input->id_kelas)
                        ->whereHas('pengguna.status_pengguna', function ($query) {
                            $query->where('aktif_status_pengguna', '=', '1');
                        })
                        ->get();

                    $komponen_jenis_rapor = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                        $query->where('nm_jenis_rapor', 'sisipan');
                    })->get();

                    // $komponen_jenis_rapor = KomponenNilaiRapor::where('status', 1)->get();
                    foreach ($siswa as $s) {
                        foreach ($komponen_jenis_rapor as $komponen) {
                            $id = auth_data()->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $list_data[] = [
                                'id_nilai_rapor' =>  $id,
                                'id_rapor' => $rapor->id_rapor,
                                'id_komponen_jenis_rapor' => $komponen->id_komponen_jenis_rapor,
                                'id_siswa' => $s->id_siswa,
                                'nilai' => 0,
                                'created_at' => Carbon::now(),
                                'created_by' => auth_data()->pengguna->id_pengguna,
                            ];
                        }
                    }
                    // dd($list_data);
                    CreateNilaiRapor::dispatch($list_data);
                    // CreateRapor::dispatch($list_data);

                    DB::Commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'rapor-sisipan/daftar-nilai-sts',
                        'message' => 'Save Tambah Nilai Successfully'
                    ];
                } catch (\Exception $e) {

                    DB::rollback();

                    return [
                        'status' => 300, // GAGAL
                        'message' => 'Siswa di kelas ini kosong'
                    ];
                }
            } elseif ('editNilai') {
                try {
                    DB::beginTransaction();

                    $rapor = Rapor::with('kelas')->find($id);
                    $auth_data = auth_data();
                    $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
                        $query->where('aktif_status_pengguna', '=', '1');
                    })->orderBy('nis_siswa')->get();
                    $list_komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                        $query->where('nm_jenis_rapor', 'sisipan');
                    })
                        ->where('nm_komponen_jenis_rapor', '!=', 'uas')
                        ->orderByRaw('CAST(urutan AS UNSIGNED) ASC')
                        ->get();
                    $data = json_decode($input->data);

                    if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
                        if ($rapor->kelas->tingkat == '1') {
                            $urutan = [1, 2, 5]; // urutan komponen yang di include untuk kelas 10

                            $list_komponen = $list_komponen->whereIn('urutan', $urutan);
                        } else if ($rapor->kelas->tingkat == '2') {
                            $urutan = [3, 4, 6]; // urutan komponen yang di include untuk kelas 11

                            $list_komponen = $list_komponen->whereIn('urutan', $urutan);
                        }

                        foreach ($data as $d) {
                            $siswa = $list_siswa->where('nis_siswa', $d[0])->first();
                            if ($siswa) {
                                $no = 2;
                                if ($rapor->kelas->tingkat == '1') {
                                    $urutan = [1, 2, 5]; // urutan komponen yang di include untuk kelas 10
                                } else if ($rapor->kelas->tingkat == '2') {
                                    $urutan = [3, 4, 6]; // urutan komponen yang di include untuk kelas 11
                                }

                                $nilai_rapors = NilaiRapor::where('id_siswa', $siswa->id_siswa)->where('id_rapor', $id)
                                    ->whereHas('komponen_jenis_rapor', function ($query) use ($urutan) {
                                        $query->where('nm_komponen_jenis_rapor', "!=", 'uas')->whereIn('urutan', $urutan);
                                    })->get();

                                foreach ($list_komponen as $komponen) {
                                    $nilai_rapor = $nilai_rapors->where('id_komponen_jenis_rapor', $komponen->id_komponen_jenis_rapor)->first();
                                    if ($nilai_rapor) {
                                        $nilai_rapor->nilai = $nilai_rapor->nilai ?? 0; // jika nilai kosong maka di isi 0
                                        if ($nilai_rapor->nilai != $d[$no]) {
                                            $nilai_rapor->nilai = $d[$no];
                                            $nilai_rapor->save();
                                        }
                                    } else {
                                        if ($d[$no] != "") {
                                            $nilai_rapor = new NilaiRapor;
                                            $nilai_rapor->id_nilai_rapor = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                                            $nilai_rapor->id_rapor = $id;
                                            $nilai_rapor->id_komponen_jenis_rapor = $komponen->id_komponen_jenis_rapor;
                                            $nilai_rapor->id_siswa = $siswa->id_siswa;
                                            $nilai_rapor->nilai = $d[$no];
                                            $nilai_rapor->created_at = Carbon::now();
                                            $nilai_rapor->created_by = $auth_data->pengguna->id_pengguna;
                                            $nilai_rapor->save();
                                        } else {
                                            $nilai_rapor = new NilaiRapor;
                                            $nilai_rapor->id_nilai_rapor = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                                            $nilai_rapor->id_rapor = $id;
                                            $nilai_rapor->id_komponen_jenis_rapor = $komponen->id_komponen_jenis_rapor;
                                            $nilai_rapor->id_siswa = $siswa->id_siswa;
                                            $nilai_rapor->nilai = 0;
                                            $nilai_rapor->created_at = Carbon::now();
                                            $nilai_rapor->created_by = $auth_data->pengguna->id_pengguna;
                                            $nilai_rapor->save();
                                        }
                                    }

                                    $no++;
                                }
                            }
                        }
                    } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
                        // if ($rapor->kelas->tingkat == '1' || $rapor->kelas->tingkat == '2') {
                        //     $urutan = [11, 12, 13, 14, 15, 16, 17]; // urutan komponen yang di include untuk kelas 10 & 11
                        // } else {
                        //     $urutan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // urutan komponen yang di include untuk kelas 12
                        // }
                        $urutan = [11, 12, 13, 14, 15, 16, 17];

                        $list_komponen = $list_komponen->whereIn('urutan', $urutan);

                        foreach ($data as $d) {
                            $siswa = $list_siswa->where('nis_siswa', $d[0])->first();
                            if ($siswa) {
                                $no = 2;
                                if ($rapor->kelas->tingkat == '1' || $rapor->kelas->tingkat == '2') {
                                    $urutan = [11, 12, 13, 14, 15, 16, 17]; // urutan komponen yang di include untuk kelas 10 & 11
                                } else {
                                    $urutan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // urutan komponen yang di include untuk kelas 12
                                }

                                $nilai_rapors = NilaiRapor::where('id_siswa', $siswa->id_siswa)->where('id_rapor', $id)
                                    ->whereHas('komponen_jenis_rapor', function ($query) use ($urutan) {
                                        $query->where('nm_komponen_jenis_rapor', "!=", 'uas')->whereIn('urutan', $urutan);
                                    })->get();

                                foreach ($list_komponen as $komponen) {
                                    $nilai_rapor = $nilai_rapors->where('id_komponen_jenis_rapor', $komponen->id_komponen_jenis_rapor)->first();
                                    if ($nilai_rapor) {
                                        $nilai_rapor->nilai = $nilai_rapor->nilai ?? 0; // jika nilai kosong maka di isi 0
                                        if ($nilai_rapor->nilai != $d[$no]) {
                                            $nilai_rapor->nilai = $d[$no];
                                            $nilai_rapor->save();
                                        }
                                    } else {
                                        if ($d[$no] != "") {
                                            $nilai_rapor = new NilaiRapor;
                                            $nilai_rapor->id_nilai_rapor = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                                            $nilai_rapor->id_rapor = $id;
                                            $nilai_rapor->id_komponen_jenis_rapor = $komponen->id_komponen_jenis_rapor;
                                            $nilai_rapor->id_siswa = $siswa->id_siswa;
                                            $nilai_rapor->nilai = $d[$no];
                                            $nilai_rapor->created_at = Carbon::now();
                                            $nilai_rapor->created_by = $auth_data->pengguna->id_pengguna;
                                            $nilai_rapor->save();
                                        } else {
                                            $nilai_rapor = new NilaiRapor;
                                            $nilai_rapor->id_nilai_rapor = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                                            $nilai_rapor->id_rapor = $id;
                                            $nilai_rapor->id_komponen_jenis_rapor = $komponen->id_komponen_jenis_rapor;
                                            $nilai_rapor->id_siswa = $siswa->id_siswa;
                                            $nilai_rapor->nilai = 0;
                                            $nilai_rapor->created_at = Carbon::now();
                                            $nilai_rapor->created_by = $auth_data->pengguna->id_pengguna;
                                            $nilai_rapor->save();
                                        }
                                    }

                                    $no++;
                                }
                            }
                        }
                    }

                    foreach ($data as $d) {
                        $siswa = $list_siswa->where('nis_siswa', $d[0])->first();
                        if ($siswa) {
                            $no = 2;
                            $nilai_rapors = NilaiRapor::where('id_siswa', $siswa->id_siswa)->where('id_rapor', $id)
                                ->whereHas('komponen_jenis_rapor', function ($query) {
                                    $query->where('nm_komponen_jenis_rapor', "!=", 'UAS');
                                })->get();
                            foreach ($list_komponen as $komponen) {
                                $nilai_rapor = $nilai_rapors->where('id_komponen_jenis_rapor', $komponen->id_komponen_jenis_rapor)->first();
                                if ($nilai_rapor) {
                                    $nilai_rapor->nilai = $nilai_rapor->nilai ?? 0; // jika nilai kosong maka di isi 0
                                    if ($nilai_rapor->nilai != $d[$no]) {
                                        $nilai_rapor->nilai = $d[$no];
                                        $nilai_rapor->save();
                                    }
                                }

                                $no++;
                            }
                        }
                    }

                    DB::Commit();

                    return [
                        'status' => 300, // SUCCESS
                        'message' => 'Update Sukses'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Update Gagal',
                        'error' => $e->getMessage()
                    ];
                }
            }
        }
    }

    public function printDaftarNilaiSTS(Request $request, $id_rapor)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = auth_data();

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();

        // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor', function ($query) use ($id_rapor) {
            $query->where('id_rapor', '=', $id_rapor)->where('nilai', '!=', '0');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
            ->whereHas('komponen_jenis_rapor', function ($query) {
                $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
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

        return Excel::download(new RekapRaporSisipanSTS($data), 'Rekap Rapor Sisipan STS (' . $rapor->kelas->nm_kelas . ' - ' . $rapor->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
    }

    public function datatablesDaftarNilaiSTS(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = auth_data();
        $status = $input->status;

        if (empty($input->thn_akademik_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $thn_akademik_semester = $semester_aktif->thn_akademik_semester;
        } else {
            $thn_akademik_semester = $input->thn_akademik_semester;
        }

        $list_data = Rapor::where('nm_rapor', 'sisipan')
            // ->with(['nilai_rapor' => function ($q) {
            //     $q->where('nilai', '!=', '0');
            // }, 'pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester'])
            ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->withCount(['nilai_rapor' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])
            ->whereHas('semester', function ($query) use ($thn_akademik_semester) {
                $query->where('thn_akademik_semester', '=', $thn_akademik_semester);
            })->orderBy('created_at', 'desc');

        // $siswa = Siswa::where('id_kelas', $list_data->pluck('id_kelas'))->whereHas('pengguna.status_pengguna', function ($query) {
        //     $query->where('aktif_status_pengguna', '=', '1');
        // })->get();

        // $komponen = KomponenNilaiRapor::where('status', '1')->where('nm_komponen_jenis_rapor', '!=', 'uas')->count();
        $komponen = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->where('nm_komponen_jenis_rapor', '!=', 'uas')->count();

        if ($status == '0') {
            $list_data = $list_data->where('created_by', $auth_data->pengguna->id_pengguna);
        }


        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($komponen) {
                $nilaiLengkap = $item->kelas->siswa->count() * $komponen;
                $nilaiTerisi = $item->nilai_rapor_count;

                if ($nilaiTerisi === 0 || $nilaiLengkap === 0) {
                    return '0%';
                }

                $hasil = number_format(($nilaiTerisi / $nilaiLengkap) * 100, 2);
                return min($hasil, 100) . '%';
            })
            ->editColumn('semester', function ($item) {
                return '(' . $item->semester->nm_semester . ') ' . $item->semester->tahun_ajaran;
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

    public function editNilaiRaporSisipan(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $rapor = Rapor::with('keterangan_rapor.komponen_jenis_rapor', 'kelas', 'semester', 'mata_pelajaran')->find($id);

        return view('guru/rapor-sisipan/edit-nilai-rapor-sisipan', compact('auth_data', 'rapor'));
    }


    public function inputNilai(Request $request, $id_rapor)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();
        // $list_data = KomponenJenisRapor::where('id_jenis_rapor', $rapor->kelas->id_jenis_rapor)->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->where('nm_komponen_jenis_rapor', '!=', 'uas')->get();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            if ($rapor->kelas->tingkat == '1') {
                $urutan = [1, 2, 5]; // urutan komponen yang di include untuk kelas 10
            } else if ($rapor->kelas->tingkat == '2') {
                $urutan = [3, 4, 6]; // urutan komponen yang di include untuk kelas 11
            }

            $list_data = $list_data->whereIn('urutan', $urutan);
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            // if ($rapor->kelas->tingkat == '1' || $rapor->kelas->tingkat == '2') {
            //     $urutan = [11, 12, 13, 14, 15, 16, 17]; // urutan komponen yang di include untuk kelas 10 & 11
            // } else {
            //     $urutan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // urutan komponen yang di include untuk kelas 12
            // }

            $urutan = [11, 12, 13, 14, 15, 16, 17];

            $list_data = $list_data->whereIn('urutan', $urutan);
        }

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

        return view('guru/rapor-sisipan/daftar-nilai-sts/view-edit-nilai-sts', compact('auth_data', 'id_rapor',  'dynamicColumns'));
    }

    public function getNilai(Request $request, $id_rapor)
    {
        $rapor = Rapor::find($id_rapor);
        $input = (object) $request->input();
        $auth_data = auth_data();
        $siswa = Siswa::with('pengguna')->where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            $list_data = $siswa->map(function ($item) use ($id_rapor) {
                $data = array();
                $data['nis_siswa'] = $item->nis_siswa;
                $data['nm_pengguna'] = $item->pengguna->nm_pengguna;
                if ($item->kelas->tingkat == '1') {
                    $urutan = [1, 2, 5]; // urutan komponen yang di include untuk kelas 10
                } else if ($item->kelas->tingkat == '2') {
                    $urutan = [3, 4, 6]; // urutan komponen yang di include untuk kelas 11
                }
                $nilai_rapors = NilaiRapor::where('id_siswa', $item->id_siswa)->where('id_rapor', $id_rapor)
                    ->whereHas('komponen_jenis_rapor', function ($query) use ($urutan) {
                        $query->where('nm_komponen_jenis_rapor', "!=", 'uas')->whereIn('urutan', $urutan);
                    })->get();
                foreach ($nilai_rapors as $n) {
                    $data[$n->id_komponen_jenis_rapor] = $n->nilai;
                }
                return $data;
            })->toArray();

            return response()->json($list_data);
        } else if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            $list_data = $siswa->map(function ($item) use ($id_rapor) {
                $data = array();
                $data['nis_siswa'] = $item->nis_siswa;
                $data['nm_pengguna'] = $item->pengguna->nm_pengguna;
                // if ($item->kelas->tingkat == '1' || $item->kelas->tingkat == '2') {
                //     $urutan = [11, 12, 13, 14, 15, 16, 17]; // urutan komponen yang di include untuk kelas 10 & 11
                // } else {
                //     $urutan = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]; // urutan komponen yang di include untuk kelas 12
                // }
                $urutan = [11, 12, 13, 14, 15, 16, 17];
                $nilai_rapors = NilaiRapor::where('id_siswa', $item->id_siswa)->where('id_rapor', $id_rapor)
                    ->whereHas('komponen_jenis_rapor', function ($query) use ($urutan) {
                        $query->where('nm_komponen_jenis_rapor', "!=", 'uas')->whereIn('urutan', $urutan);
                    })->get();
                foreach ($nilai_rapors as $n) {
                    $data[$n->id_komponen_jenis_rapor] = $n->nilai;
                }
                return $data;
            })->toArray();

            return response()->json($list_data);
        }

        $list_data = $siswa->map(function ($item) use ($id_rapor) {
            $data = array();
            $data['nis_siswa'] = $item->nis_siswa;
            $data['nm_pengguna'] = $item->pengguna->nm_pengguna;
            $nilai_rapor = NilaiRapor::where('id_siswa', $item->id_siswa)->where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', "!=", 'UAS');
                })
                ->get();
            foreach ($nilai_rapor as $n) {
                $data[$n->id_komponen_jenis_rapor] = $n->nilai;
            }
            return $data;
        })->toArray();

        return response()->json($list_data);
    }

    public function excelDaftarNilaiSTS(Request $request, $id_rapor)
    {

        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = auth_data();

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas')->first();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            // if ($rapor->kelas->tingkat == '3') {
            //     // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
            //     $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            //         $query->where('nm_jenis_rapor', 'sisipan');
            //     })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
            // } else {
            //     $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            //         $query->where('nm_jenis_rapor', 'sisipan');
            //     })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
            //     // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')
            //     //     ->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
            // }
            $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smknu') {
            if ($rapor->kelas->tingkat == '1') {
                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();
            } else {
                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();
            }
        } else {
            $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();

            // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        }


        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })
            ->whereHas('nilai_rapor', function ($query) use ($id_rapor) {
                $query->where('id_rapor', '=', $id_rapor);
            })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
            ->whereHas('komponen_jenis_rapor', function ($query) {
                $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
            })->get();

        $nilai_siswa = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                // foreach ($nilaiRapor as $a) {
                $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];
                // }
            }
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['rapor'] = $rapor;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor'] = $id_rapor;
        $nm_mata_pelajaran = str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $rapor->mata_pelajaran->nm_mata_pelajaran);
        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS (' . $rapor->kelas->nm_kelas . ' - ' . $nm_mata_pelajaran . ').xlsx');
    }


    public function imporExcelSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        return view('guru/rapor-sisipan/daftar-nilai-sts/view-upload-nilai-sts', compact('auth_data'));
    }


    public function uploadRaporSisipanSTS(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            try {
                Excel::import(new UploadRaporSisipanSTS, $request->file('file-excel'));
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

    public function getMataPelajaran(Request $request)
    {
        $input = (object) $request->input();
        $kelas = KelasRapor::with('mata_pelajaran_rapor.mata_pelajaran.jenis_mata_pelajaran', 'kelas')->where('id_kelas', $input->id_kelas)
            ->whereHas('mata_pelajaran_rapor', function ($q) {
                $q->where('jenis', '1');
            })->whereHas('mata_pelajaran_rapor.kelompok_mapel_rapor', function ($q) {
                $q->where('nm_rapor', 'sisipan');
            })->get();
        // $kelas = KelasSisipan::where('id_kelas', $input->id_kelas)->with('mata_pelajaran.mata_pelajaran')->get();
        return $kelas;
    }


    public function pdfDaftarNilaiSTS(Request $request, $id_rapor)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = auth_data();
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $rapor = Rapor::where('id_rapor', $id_rapor)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor', 'sisipan');
        })->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor', function ($query) use ($id_rapor) {
            $query->where('id_rapor', '=', $id_rapor);
        })->orderBy('nis_siswa')->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            if ($rapor->kelas->tingkat == '3') {
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();

                $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->where('nilai', '!=', '0')->get();
                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    $nilai = $list_nilai->toArray();
                    foreach ($nilai as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }
                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-maryam', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
            } else {
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')
                //     ->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();

                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();

                $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->where('nilai', '!=', '0')->get();
                if (empty($list_nilai)) {

                    return 'Harap Hapus Rapor sisipan ini, dan buat ulang';
                }

                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    foreach ($list_nilai->toArray() as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }

                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-maryam-merdeka', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
            }
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smksitiaminah') {
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];

                    if ($nilaiRapor['nilai'] >= 90 && $nilaiRapor['nilai'] <= 100) {
                        $hasil = 'A';
                    } elseif ($nilaiRapor['nilai'] >= 80 && $nilaiRapor['nilai'] < 90) {
                        $hasil = 'B';
                    } elseif ($nilaiRapor['nilai'] >= 70 && $nilaiRapor['nilai'] < 80) {
                        $hasil = 'C';
                    } elseif ($nilaiRapor['nilai'] >= 0 && $nilaiRapor['nilai'] < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . 'predikat'] = $hasil;
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-sitiamina', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smktanada') {
            $nilai_siswa = [];

            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor',  'uts');
                })->get();

            $list_nilai2 = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->whereIn('urutan', [1, 2, 3, 4]);
                })->get();


            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa['uts' . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                }
                $nilai = $list_nilai2->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    if (isset($nilai_siswa['total_nilai_tugas' . $nilaiRapor['id_siswa']])) {
                        $nilai_siswa['total_nilai_tugas' . $nilaiRapor['id_siswa']] += $nilaiRapor['nilai'];
                    } else {
                        $nilai_siswa['total_nilai_tugas' . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }

                foreach ($list_siswa as $siswa) {
                    $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa] = $nilai_siswa['total_nilai_tugas' . $siswa->id_siswa] / 4;
                    $nilai_siswa['rata_rata' . $siswa->id_siswa] = ($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa] + $nilai_siswa['uts' . $siswa->id_siswa]) / 2;

                    if ($nilai_siswa['rata_rata' . $siswa->id_siswa] >= 90 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa] <= 100) {
                        $hasil = 'A';
                    } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa] >= 80 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa] < 90) {
                        $hasil = 'B';
                    } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa] >= 70 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa] < 80) {
                        $hasil = 'C';
                    } elseif ($nilai_siswa['rata_rata' . $siswa->id_siswa] >= 0 &&  $nilai_siswa['rata_rata' . $siswa->id_siswa] < 70) {
                        $hasil = 'D';
                    } else {
                        $hasil = 'Nilai tidak valid';
                    }

                    $nilai_siswa['kriteria' . $siswa->id_siswa] = $hasil;
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-tanada', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'list_nilai2', 'rapor'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            $nilai_siswa = [];

            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor',  '!=', 'uas');
                })->get();


            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                $rata = 0;
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];

                    if (isset($nilai_siswa[$nilaiRapor['id_siswa']])) {
                        $nilai_siswa[$nilaiRapor['id_siswa']] += $nilaiRapor['nilai'];
                    } else {
                        $nilai_siswa[$nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-manu', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa',  'rapor'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smknu') {
            if ($rapor->kelas->tingkat == '1') {
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();

                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 11])->orderBy('urutan', 'asc')->get();

                $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->where('nilai', '!=', '0')->get();
                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    $nilai = $list_nilai->toArray();
                    foreach ($nilai as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }
                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-smknu1', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
            } else {
                $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                    $query->where('nm_jenis_rapor', 'sisipan');
                })->where('nm_komponen_jenis_rapor', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();
                // $list_data = KomponenNilaiRapor::where('status', 1)->where('nm_komponen_jenis_rapor', '!=', 'uas')
                //     ->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11])->orderBy('urutan', 'asc')->get();

                $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->where('nilai', '!=', '0')->get();
                if (empty($list_nilai)) {

                    return 'Harap Hapus Rapor sisipan ini, dan buat ulang';
                }

                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    foreach ($list_nilai->toArray() as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }

                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-smknu2', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
            }
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'mtsnu') {
            // $list_data = KomponenNilaiRapor::where('status', 1)->orderBy('urutan', 'asc')->get();
            $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
                $query->where('nm_jenis_rapor', 'sisipan');
            })->where('nm_komponen_jenis_rapor', '!=', 'uas')->orderBy('urutan', 'asc')->get();
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)->where('nilai', '!=', '0')->get();
            $nilai_siswa = [];
            $nilai_siswa['kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-mtsnu', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
        }



        if ($setting == '0') {
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-with-kkm', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor'));
        } elseif ($setting == '1') {
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor'] . 'kkm'] = $rapor->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                        $nilai_sumatif1 = $list_data->firstWhere('nm_komponen_jenis_rapor', '=', 'NILAI SUMATIF 1');
                        $nilai_sumatif2 = $list_data->firstWhere('nm_komponen_jenis_rapor', '=', 'NILAI SUMATIF 2');
                        $nilai_sumatif3 = $list_data->firstWhere('nm_komponen_jenis_rapor', '=', 'NILAI SUMATIF 3');
                        $nilai_sumatif4 = $list_data->firstWhere('nm_komponen_jenis_rapor', '=', 'NILAI SUMATIF 4');
                        $sts = $list_data->firstWhere('nm_komponen_jenis_rapor', '=', 'STS');
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1?->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2?->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif3?->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF3'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif4?->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF4'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts?->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'STS'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor'));
        } elseif ($setting == '2') {
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_jenis_rapor'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor']] = $nilaiRapor['nilai'];

                        $nilai_tugas1 = $list_data->firstWhere('urutan', '=', '1');
                        $nilai_tugas2 = $list_data->firstWhere('urutan', '=', '2');
                        $nilai_tugas3 = $list_data->firstWhere('urutan', '=', '3');
                        $nilai_tugas4 = $list_data->firstWhere('urutan', '=', '4');
                        $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                        $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                        $nilai_sumatif3 = $list_data->firstWhere('urutan', '=', '7');
                        $nilai_sumatif4 = $list_data->firstWhere('urutan', '=', '8');
                        $sts = $list_data->firstWhere('urutan', '=', '9');

                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas1->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas2->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas3->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '3'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_tugas4->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '4'] =  $nilaiRapor['nilai'];
                        }

                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif1->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '5'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif2->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '6'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif3->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '7'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $nilai_sumatif4->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '8'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_jenis_rapor']  == $sts->id_komponen_jenis_rapor) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts2', compact('auth_data', 'id_rapor', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor'));
        } elseif ($setting == '3') {
            $list_nilai = NilaiRapor::where('id_rapor', $id_rapor)
                ->whereHas('komponen_jenis_rapor', function ($query) {
                    $query->where('nm_komponen_jenis_rapor', '!=', 'uas');
                })->get();
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
        } else {
        }
    }
}
