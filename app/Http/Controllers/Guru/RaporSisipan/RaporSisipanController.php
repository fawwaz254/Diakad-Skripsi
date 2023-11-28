<?php

namespace App\Http\Controllers\Guru\RaporSisipan;

use App\Exports\RaporSisipanSTS;
use App\Exports\RekapRaporSisipanSTS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use App\Imports\UploadRaporSisipanSTS;
use App\Jobs\CreateRaporSisipan;
use App\Models\JenisMataPelajaran;
use App\Models\Jurusan;
use App\Models\KelasSisipan;
use App\Models\Setting;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanController extends Controller
{


    public function updateData(Request $request)
    {
        set_time_limit(-1);


        // $list_siswa = Siswa::with('calon_siswa')->get();

        // foreach ($list_siswa as $siswa) {

        //     if (empty($siswa->nisn)) {
        //         $siswa->nisn_siswa = $siswa->calon_siswa->nisn_siswa;
        //         $siswa->updated_by = 'updatenis';
        //         $siswa->save();
        //     }
        // }



        $kelas = Kelas::whereIn('tingkat', [1, 2])->get();
        $siswa = Siswa::whereIn('id_kelas', $kelas->pluck('id_kelas'))->get();
        $komponen_nilai_rapor_sisipan = KomponenNilaiRaporSisipan::whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->get();
        // $komponen_nilai_rapor_sisipan = KomponenNilaiRaporSisipan::whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->get();

        $nilai_rapor_sisipan = NilaiRaporSisipan::with('komponen_nilai')->whereIn('id_siswa', $siswa->pluck('id_siswa'))->whereIn('id_komponen_nilai', $komponen_nilai_rapor_sisipan->pluck('id_komponen_nilai'))->get();


        foreach ($nilai_rapor_sisipan as $nilai_rapor) {

            if ($nilai_rapor->komponen_nilai->urutan == '1') {
                $nilai_rapor->id_komponen_nilai = "Qjh12169811641765373342382fd";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '2') {
                $nilai_rapor->id_komponen_nilai = "Qjh1216981164176537334238302";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '3') {
                $nilai_rapor->id_komponen_nilai = "Qjh1216981164176537334238304";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '4') {
                $nilai_rapor->id_komponen_nilai = "Qjh1216981164176537334238307";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '5') {
                $nilai_rapor->id_komponen_nilai = "Qjh1216981164176537334238309";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '6') {
                $nilai_rapor->id_komponen_nilai = "Qjh121698116417653733423830b";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '7') {
                $nilai_rapor->deleted_by = "syahrul";
                $nilai_rapor->save();
                $nilai_rapor->delete();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '8') {
                $nilai_rapor->deleted_by = "syahrul";
                $nilai_rapor->save();
                $nilai_rapor->delete();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '9') {
                $nilai_rapor->id_komponen_nilai = "Qjh121698116417653733423830d";
                $nilai_rapor->save();
            } elseif ($nilai_rapor->komponen_nilai->urutan == '10') {
                $nilai_rapor->deleted_by = "syahrul";
                $nilai_rapor->save();
                $nilai_rapor->delete();
            }
        }

        return 'done';
        // $nilai_rapor_sisipan = NilaiRaporSisipan::get();
    }

    public function viewDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function addDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

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
        // $auth_data = $input->auth_data;
        // $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        if ($mode == 'delete') {
            DB::beginTransaction();
            try {
                DB::table('nilai_rapor_sisipan')->where('id_rapor_sisipan', $id)->delete();
                $raporSisipan = RaporSisipan::where('id_rapor_sisipan', $id)->first();
                if ($raporSisipan) {
                    $raporSisipan->delete();
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
            $cekDuplicate = RaporSisipan::where('id_kelas', $input->id_kelas)->where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_semester', $input->id_semester)
                ->with('pengguna')->first();
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
        }

        if ($cekDuplicate) {
            return [
                'status' => 300, // FAILED
                'message' => 'Kelas dan Mapel Sudah digunakan oleh ' . $cekDuplicate->pengguna->nm_pengguna,
            ];
        }

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($mode == 'add') {
                DB::beginTransaction();

                try {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $rapor_sisipan                      = new RaporSisipan;
                    $rapor_sisipan->id_rapor_sisipan    = $id;
                    $rapor_sisipan->id_semester         = $input->id_semester;
                    $rapor_sisipan->id_mata_pelajaran   = $input->id_mata_pelajaran;
                    $rapor_sisipan->id_kelas            = $input->id_kelas;
                    $rapor_sisipan->id_pengguna         = $input->auth_data->pengguna->id_pengguna;
                    $rapor_sisipan->created_by          = $input->auth_data->pengguna->id_pengguna;
                    $rapor_sisipan->save();

                    $siswa = Siswa::where('id_kelas', $input->id_kelas)->with('pengguna.status_pengguna')
                        ->whereHas('pengguna.status_pengguna', function ($query) {
                            $query->where('aktif_status_pengguna', '=', '1');
                        })
                        ->get();

                    $komponen_nilai = KomponenNilaiRaporSisipan::where('status', 1)->get();
                    foreach ($siswa as $s) {
                        foreach ($komponen_nilai as $komponen) {
                            $id = $input->auth_data->sekolah_data->prefix . strtotime(Carbon::now(env('APP_TIMEZONE', ''))) . uniqid();
                            $list_data[] = [
                                'id_nilai_rapor_sisipan' =>  $id,
                                'id_rapor_sisipan' => $rapor_sisipan->id_rapor_sisipan,
                                'id_komponen_nilai' => $komponen->id_komponen_nilai,
                                'id_siswa' => $s->id_siswa,
                                'nilai' => 0,
                                'created_by' => $input->auth_data->pengguna->id_pengguna,
                            ];
                        }
                    }
                    CreateRaporSisipan::dispatch($list_data);

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
            }
        }
    }

    public function printDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan)->where('nilai', '!=', '0');
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();

        $nilai_siswa = [];
        $list_kd_aktif = [];
        // $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                    // $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                    // $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                    // $sts = $list_data->firstWhere('urutan', '=', '9');
                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi1'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'nilai_sumasi2'] =  $nilaiRapor['nilai'];
                    // }
                    // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                    //     $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                    // }
                }
            }
        }
        foreach ($list_data as $key => $data) {
            $data1 = $list_nilai->where('id_komponen_nilai', $data->id_komponen_nilai)->where('nilai', '!=', 0)->first();
            if (!empty($data1)) {
                $list_kd_aktif[$key]['id_komponen_nilai'] =   $data->id_komponen_nilai;
                $list_kd_aktif[$key]['nm_nilai'] =   $data->nm_nilai;
            }
        }



        $data['nilai_siswa'] = $nilai_siswa;
        // $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor_sisipan'] = $rapor_sisipan;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_kd_aktif;
        $data['id_rapor_sisipan'] = $id_rapor_sisipan;

        return Excel::download(new RekapRaporSisipanSTS($data), 'Rekap Rapor Sisipan STS (' . $rapor_sisipan->kelas->nm_kelas . ' - ' . $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
    }

    public function datatablesDaftarNilaiSTS(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $status = $input->status;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = RaporSisipan::where('id_semester', $id_semester)
            // ->with(['nilai_rapor_sisipan' => function ($q) {
            //     $q->where('nilai', '!=', '0');
            // }, 'pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester'])
            ->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas.siswa', 'semester')
            ->withCount(['nilai_rapor_sisipan' => function ($q) {
                $q->where('nilai', '!=', 0);
            }])
            ->orderBy('created_at', 'desc');

        // $siswa = Siswa::where('id_kelas', $list_data->pluck('id_kelas'))->whereHas('pengguna.status_pengguna', function ($query) {
        //     $query->where('aktif_status_pengguna', '=', '1');
        // })->get();

        $komponen = KomponenNilaiRaporSisipan::where('status', '1')->where('type', '!=', 'uas')->count();

        if ($status == '0') {
            $list_data = $list_data->where('id_pengguna', $auth_data->pengguna->id_pengguna);
        }


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
            ->addColumn('action', function ($item) use ($status) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan,
                    'status' => $status,
                );
                return $data;
            })
            ->make(true);
    }

    public function excelDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            if ($rapor_sisipan->kelas->tingkat == '3') {
                $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
            } else {
                $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')
                    ->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();
            }
        }


        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })
            ->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
            })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
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
        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS (' . $rapor_sisipan->kelas->nm_kelas . ' - ' . $nm_mata_pelajaran . ').xlsx');
    }


    public function imporExcelSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
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
        $kelas_sisipan = KelasSisipan::where('id_kelas', $input->id_kelas)->with('mata_pelajaran_sisipan.mata_pelajaran')->get();
        return $kelas_sisipan;
    }


    public function pdfDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })->orderBy('nis_siswa')->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            if ($rapor_sisipan->kelas->tingkat == '3') {
                $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->whereIn('urutan', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->orderBy('urutan', 'asc')->get();
                $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->where('nilai', '!=', '0')->get();
                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    $nilai = $list_nilai->toArray();
                    foreach ($nilai as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }
                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-maryam', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
            } else {
                $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')
                    ->whereIn('urutan', [11, 12, 13, 14, 15, 16, 17])->orderBy('urutan', 'asc')->get();

                $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->where('nilai', '!=', '0')->get();
                if (empty($list_nilai)) {

                    return 'Harap Hapus Rapor sisipan ini, dan buat ulang';
                }

                $nilai_siswa = [];
                $nilai_siswa['kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                if ($list_siswa) {
                    foreach ($list_nilai->toArray() as $nilaiRapor) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                    }
                }

                return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-maryam-merdeka', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
            }
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smksitiaminah') {
            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_siswa['kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];

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
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . 'predikat'] = $hasil;
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-sitiamina', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'smktanada') {
            $nilai_siswa = [];

            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type',  'uts');
                })->get();

            $list_nilai2 = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->whereIn('urutan', [1, 2, 3, 4]);
                })->get();


            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa['uts' . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
                }
                $nilai = $list_nilai2->toArray();
                foreach ($nilai as $nilaiRapor) {
                    $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa']] = $nilaiRapor['nilai'];
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

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-tanada', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'list_nilai2', 'rapor_sisipan'));
        } elseif ($auth_data->sekolah_data->nm_singkat_sekolah == 'manu') {
            $nilai_siswa = [];

            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type',  '!=', 'uas');
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

            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts-manu', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa',  'rapor_sisipan'));
        }



        if ($setting == '0') {
            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type', '!=', 'uas');
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
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-with-kkm', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        } elseif ($setting == '1') {
            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                        $nilai_sumatif1 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 1');
                        $nilai_sumatif2 = $list_data->firstWhere('nm_nilai', '=', 'NILAI SUMATIF 2');
                        $sts = $list_data->firstWhere('nm_nilai', '=', 'STS');
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'NILAISUMATIF2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'STS'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } elseif ($setting == '2') {
            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type', '!=', 'uas');
                })->get();
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                        $nilai_tugas1 = $list_data->firstWhere('urutan', '=', '1');
                        $nilai_tugas2 = $list_data->firstWhere('urutan', '=', '2');
                        $nilai_tugas3 = $list_data->firstWhere('urutan', '=', '3');
                        $nilai_tugas4 = $list_data->firstWhere('urutan', '=', '4');
                        $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                        $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                        $nilai_sumatif3 = $list_data->firstWhere('urutan', '=', '7');
                        $nilai_sumatif4 = $list_data->firstWhere('urutan', '=', '8');
                        $sts = $list_data->firstWhere('urutan', '=', '9');

                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '2'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas3->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '3'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas4->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '4'] =  $nilaiRapor['nilai'];
                        }

                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '5'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '6'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '7'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '8'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . 'sts'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts2', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } elseif ($setting == '3') {
            $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
                ->whereHas('komponen_nilai', function ($query) {
                    $query->where('status', 1)->where('type', '!=', 'uas');
                })->get();
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
        } else { }
    }
}
