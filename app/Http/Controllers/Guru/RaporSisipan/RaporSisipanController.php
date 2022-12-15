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
use App\Models\Jurusan;
use App\Models\Setting;
use Auth;
use DB;
use Session;
use Validator;

class RaporSisipanController extends Controller
{
    public function viewDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data'));
    }

    public function addDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data['list_mapel'] = MataPelajaran::all();
        $data['list_kelas'] = Kelas::all();
        $data['list_jurusan'] = Jurusan::all();

        return view('guru/rapor-sisipan/daftar-nilai-sts/add-daftar-nilai-sts', compact('auth_data'), $data);
    }

    public function actionDaftarNilaiSTS(Request $request, $mode, $id = null)
    {
        set_time_limit(9800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        if ($mode == 'delete') {
            try {
                $nilaiRaporSisipan = NilaiRaporSisipan::where('id_rapor_sisipan', $id)->get();
                foreach ($nilaiRaporSisipan as $id) {
                    $nilai = NilaiRaporSisipan::where('id_nilai_rapor_sisipan', $id->id_nilai_rapor_sisipan)->first();
                    $nilai->delete();
                }

                $raporSisipan = RaporSisipan::where('id_rapor_sisipan', $id)->first();
                $raporSisipan->delete();
            } catch (\GuzzleHttp\Exception\GuzzleException $e) {
                return [
                    'status' => 202,
                    'path' => 'rapor-sisipan/daftar-nilai-sts',
                    'message' => 'Delete Rapor Sisipan Gagal, Silahkan coba lagi'
                ];
            }

            return [
                'status' => 202,
                'path' => 'rapor-sisipan/daftar-nilai-sts',
                'message' => 'Delete Rapor Sisipan Successfully'
            ];
        }

        if ($mode == 'add') {
            $cekDuplicate = RaporSisipan::where('id_kelas', $input->id_kelas)->where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_semester', $semester_aktif->id_semester)->first();
            $validator = Validator::make($request->all(), [
                'id_mata_pelajaran' => 'required',
                'id_kelas'              => 'required'
            ]);
        }

        if ($cekDuplicate) {
            return [
                'status' => 300, // FAILED
                'message' => 'Kelas dan Mapel Sudah ada Guru Lain yang Menggunakan'
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
                    $rapor_sisipan->id_semester         = $semester_aktif->id_semester;
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
                        'status' => 203, // GAGAL
                        'message' => $e->getMessage()
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

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();

        $nilai_siswa = [];
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

        $data['nilai_siswa'] = $nilai_siswa;
        // $data['nilai_komponen'] = $nilai_komponen;
        $data['rapor_sisipan'] = $rapor_sisipan;
        $data['list_siswa'] = $list_siswa;;
        $data['list_data'] = $list_data;
        $data['id_rapor_sisipan'] = $id_rapor_sisipan;

        return Excel::download(new RekapRaporSisipanSTS($data), 'Rekap Rapor Sisipan STS (' . $rapor_sisipan->kelas->nm_kelas . ' - ' . $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
    }

    public function datatablesDaftarNilaiSTS(Request $request)
    {
        set_time_limit(9800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = RaporSisipan::with('pengguna', 'mata_pelajaran', 'kelas', 'semester')->where('id_pengguna', $auth_data->pengguna->id_pengguna)->orderBy('created_at', 'desc');
        // $jurusan = Jurusan::all();
        $siswa = Siswa::with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })->get();

        $komponenUTS = KomponenNilaiRaporSisipan::where('type', 'uts')->first()->id_komponen_nilai;

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->mata_pelajaran->nm_mata_pelajaran;
            })
            ->addColumn('jumlah', function ($item) use ($siswa, $komponenUTS) {
                //semua siswa
                $allSiswa =  $siswa->where('id_kelas', $item->kelas->id_kelas)->count();
                $nilaiSiswaKosong = NilaiRaporSisipan::where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('id_komponen_nilai', $komponenUTS)->where('nilai', '!=', '0')->whereHas('siswa', function ($query) use ($item) {
                    $query->where('id_kelas', '=', $item->kelas->id_kelas);
                })->count();
                $data = array(
                    'jumlah_siswa' => $allSiswa,
                    'terisi_siswa' => $nilaiSiswaKosong,
                );
                return $data;
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

    public function excelDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas')->first();

        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
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

        return Excel::download(new RaporSisipanSTS($data), 'Rapor Sisipan STS (' . $rapor_sisipan->kelas->nm_kelas . ' - ' . $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran . ').xlsx');
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
            Excel::import(new UploadRaporSisipanSTS, $request->file('file-excel'));
            return [
                'status'     => 200, // FAILED
                'message'     => "Upload Sukses"
            ];;
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }

    public function getDataFromJurusan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->get();
        $data['kelas'] = Kelas::where('id_jurusan', $input->jurusan)->get();

        return $data;
    }

    public function pdfDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa', 'komponen_nilai')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->get();

        if ($setting == '0') {
            $nilai_siswa = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan'] . 'kkm'] = $rapor_sisipan->mata_pelajaran->nilai_kkm ?? 'kkm belum di set';
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-with-kkm', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
        } elseif ($setting == '1') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                        $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                        $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                        $sts = $list_data->firstWhere('urutan', '=', '9');
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '5'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '6'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '9'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } elseif ($setting == '2') {
            $nilai_siswa = [];
            $nilai_komponen = [];
            if ($list_siswa) {
                $nilai = $list_nilai->toArray();
                foreach ($nilai as $nilaiRapor) {
                    foreach ($nilaiRapor as $a) {
                        $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];

                        $nilai_tugas1 = $list_data->firstWhere('urutan', '=', '1');
                        $nilai_sumatif1 = $list_data->firstWhere('urutan', '=', '5');
                        $nilai_sumatif2 = $list_data->firstWhere('urutan', '=', '6');
                        $sts = $list_data->firstWhere('urutan', '=', '9');
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '1'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '5'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '6'] =  $nilaiRapor['nilai'];
                        }
                        if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
                            $nilai_komponen[$nilaiRapor['id_siswa'] . '9'] =  $nilaiRapor['nilai'];
                        }
                    }
                }
            }
            return view('guru/rapor-sisipan/daftar-nilai-sts/cetak-nilai-sts2', compact('auth_data', 'id_rapor_sisipan', 'list_data', 'list_siswa', 'nilai_siswa', 'nilai_komponen', 'rapor_sisipan'));
        } else { }
    }
}
