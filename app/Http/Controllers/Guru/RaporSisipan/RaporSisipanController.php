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
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('guru/rapor-sisipan/daftar-nilai-sts/view-daftar-nilai-sts', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function addDaftarNilaiSTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $data['list_mapel'] = MataPelajaran::with('jenis_mata_pelajaran')->get();
        $data['list_kelas'] = Kelas::all();
        $data['list_jurusan'] = Jurusan::all();
        $data['jenis_mapel'] = JenisMataPelajaran::all();

        return view('guru/rapor-sisipan/daftar-nilai-sts/add-daftar-nilai-sts', compact('auth_data'), $data);
    }

    public function actionDaftarNilaiSTS(Request $request, $mode, $id = null)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
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
            $cekDuplicate = RaporSisipan::where('id_kelas', $input->id_kelas)->where('id_mata_pelajaran', $input->id_mata_pelajaran)->where('id_semester', $semester_aktif->id_semester)
                ->first();
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
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })
            ->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
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
        set_time_limit(9800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $status = $input->status;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = RaporSisipan::where('id_semester', $id_semester)->with('pengguna', 'mata_pelajaran.jenis_mata_pelajaran', 'kelas', 'semester')->orderBy('created_at', 'desc');

        if ($status == '0') {
            $list_data = $list_data->where('id_pengguna', $auth_data->pengguna->id_pengguna);
        }

        // $jurusan = Jurusan::all();
        $siswa = Siswa::with('pengguna.status_pengguna')
            ->whereHas('pengguna.status_pengguna', function ($query) {
                $query->where('aktif_status_pengguna', '=', '1');
            })->get();
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first();
        if ($setting->value == '3') {
            $komponen = KomponenNilaiRaporSisipan::where('urutan', '1')->first()->id_komponen_nilai;
        } else {
            $komponen = KomponenNilaiRaporSisipan::where('type', 'uts')->first();
            if (!empty($komponen)) {
                $komponen = $komponen->id_komponen_nilai;
            }
        }
        // $komponenUTS = KomponenNilaiRaporSisipan::where('type', 'uts')->first()->id_komponen_nilai;
        $allnilaiSiswaKosong = NilaiRaporSisipan::where('id_komponen_nilai', $komponen)->whereHas('rapor_sisipan', function ($query) use ($id_semester) {
            $query->where('id_semester', $id_semester);
        })->get();
        // dd($allnilaiSiswaKosong->where('id_rapor_sisipan', 'Hz7M716705600326392b92106bff')->get());

        return Datatables::of($list_data)
            ->addColumn('jumlah', function ($item) use ($siswa, $allnilaiSiswaKosong, $setting) {
                //semua siswa
                $allSiswa =  $siswa->where('id_kelas', $item->kelas->id_kelas)->count();
                if ($setting->value == '3') {
                    $nilaiSiswaKosong = $allnilaiSiswaKosong->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')->count();
                } else { }
                $nilaiSiswaKosong = $allnilaiSiswaKosong->where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', '!=', '0')
                    // ->whereHas('siswa', function ($query) use ($item) {
                    //     $query->where('id_kelas', '=', $item->kelas->id_kelas);
                    // })
                    ->count();

                $data = array(
                    'jumlah_siswa' => $allSiswa,
                    'terisi_siswa' => $nilaiSiswaKosong,
                );
                return $data;
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
            })->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
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

        // $auth_data = $input->auth_data;
        // $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->where('id_jenis_mata_pelajaran', $input->jenis_mata_pelajaran)->get()->sortBy('kd_mata_pelajaran');
        $data['kelas'] = Kelas::where('id_jurusan', $input->jurusan)->get();

        if (!empty($input->jurusan) && !empty($input->jenis_mata_pelajaran)) {
            $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->where('id_jenis_mata_pelajaran', $input->jenis_mata_pelajaran)->get()->sortBy('kd_mata_pelajaran');
        } elseif (!empty($input->jurusan) && empty($input->jenis_mata_pelajaran)) {
            $data['mapel'] = MataPelajaran::where('id_jurusan', $input->jurusan)->get()->sortBy('kd_mata_pelajaran');
        } elseif (empty($input->jurusan) && !empty($input->jenis_mata_pelajaran)) {
            $data['mapel'] = MataPelajaran::where('id_jenis_mata_pelajaran', $input->jenis_mata_pelajaran)->get()->sortBy('kd_mata_pelajaran');
        } else {
            $data['mapel'] = null;
        }

        return $data;
    }


    public function pdfDaftarNilaiSTS(Request $request, $id_rapor_sisipan)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester', 'pengguna')->first();
        $list_data = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna.status_pengguna')->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->whereHas('nilai_rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
            $query->where('id_rapor_sisipan', '=', $id_rapor_sisipan);
        })->orderBy('nis_siswa')->get();


        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)
            ->whereHas('komponen_nilai', function ($query) {
                $query->where('status', 1)->where('type', '!=', 'uas');
            })->whereHas('rapor_sisipan', function ($query) use ($id_rapor_sisipan) {
                $query->where('id_rapor_sisipan', $id_rapor_sisipan);
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
