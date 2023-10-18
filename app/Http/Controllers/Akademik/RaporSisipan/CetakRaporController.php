<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\KelasSisipan;
use App\Models\KelompokSisipan;
use App\Models\Kurikulum;
use App\Models\RaporSisipanDeskripsi;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Setting;
use App\Models\Siswa;
use App\Models\SubRaporSisipan;
use App\Models\UrutanRaporSisipan;
use App\Models\WaliKelas;
use Auth;
use DB;
use Session;
use Validator;


class CetakRaporController extends Controller
{

    public function viewCetakRapor(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);


        return view('akademik/rapor-sisipan/cetak-rapor/view-cetak-rapor', compact('auth_data', 'semester_aktif', 'data_semester'));
    }

    public function viewCetakRaporWaliKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $wali_kelas = WaliKelas::where('is_aktif', 1)->where('id_guru', $guru->id_guru)->first();
        $data_wali_kelas = LibGuru::fetchDataWaliKelas($auth_data, $wali_kelas->id_kelas)->where('is_aktif', 1)->first();
        $semester_aktif = Semester::where('is_aktif_semester', '=', 1)->first();
        return view('guru/wali-kelas/cetak-rapor/view-cetak-rapor', compact('wali_kelas', 'data_wali_kelas', 'semester_aktif'));
    }

    public function datatablesCetakRapor(Request $request)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if (empty($input->id_semester)) {
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $id_semester = $semester_aktif->id_semester;
        } else {
            $id_semester = $input->id_semester;
        }

        $list_data = Kelas::where('is_aktif', 1)->with('jurusan')->orderBy('tingkat', 'asc')->orderBy('nm_kelas', 'asc')->get();
        $list_rapor_sisipan = RaporSisipan::whereHas('semester', function ($query) use ($id_semester) {
            $query->where('id_semester', '=', $id_semester);
        })->get();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->get();
        $semester = Semester::where('id_semester', $id_semester)->first();


        $kelas_sisipan = KelasSisipan::whereHas('mata_pelajaran_sisipan', function ($q) {
            $q->where('jenis', '1');
        })->get();


        return Datatables::of($list_data)
            ->addColumn('kelas_sisipan', function ($item) use ($kelas_sisipan) {
                return $kelas_sisipan->where('id_kelas', $item->id_kelas)->count();
            })
            ->addColumn('wali_kelas', function ($item) use ($wali_kelas) {
                $k = $wali_kelas->firstWhere('id_kelas', $item->id_kelas);
                return $k->guru->pengguna->nm_pengguna ?? '';
            })
            ->addColumn('rapor_sisipan', function ($item) use ($list_rapor_sisipan) {
                return $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count();
            })->addColumn('semester', function () use ($semester) {
                return  $semester->tahun_ajaran . ' ' . $semester->nm_semester;
            })
            ->addColumn('action', function ($item)  use ($list_rapor_sisipan, $id_semester) {
                $data = array(
                    'id_kelas'                  => $item->id_kelas,
                    'jumlah'                    => $list_rapor_sisipan->where('id_kelas', $item->id_kelas)->count(),
                    'id_semester'               => $id_semester
                );
                return $data;
            })
            ->make(true);
    }


    public function viewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('akademik/rapor-sisipan/cetak-rapor/view-setting-cetak-rapor', compact('auth_data'));
    }

    public function viewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-deskripsi-cetak-rapor', compact('auth_data'));
    }

    public function addSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $mapel = MataPelajaran::where('id_mata_pelajaran', $mata_pelajaran)->with('urutan_rapor_sisipan')->first();

        return view('akademik/rapor-sisipan/cetak-rapor/add-setting-cetak-rapor', compact('auth_data', 'mapel'));
    }

    public function  editDeskripsi(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $rapor_sisipan_deskripsi = RaporSisipanDeskripsi::find($id);
        $mata_pelajaran = MataPelajaran::select('nm_mata_pelajaran')->groupBy('nm_mata_pelajaran')->get();
        return view('akademik/rapor-sisipan/cetak-rapor/edit-deskripsi-rapor-sisipan', compact('auth_data', 'rapor_sisipan_deskripsi', 'mata_pelajaran'));
    }

    public function actionDeskripsi(Request $request, $mode, $id)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $validator = Validator::make($request->all(), [
            'nm_mata_pelajaran' => 'required',
            'tingkat' => 'required',
            'kd_deskripsi' => 'required',
            'deskripsi1' => 'required',
            'deskripsi2' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $rapor_sisipan_deskripsi = new RaporSisipanDeskripsi;
                $rapor_sisipan_deskripsi->id_rapor_sisipan_deskripsi = $id;
                $rapor_sisipan_deskripsi->nm_mata_pelajaran = $input->nm_mata_pelajaran;
                $rapor_sisipan_deskripsi->tingkat = $input->tingkat;
                $rapor_sisipan_deskripsi->kd_deskripsi = $input->kd_deskripsi;
                $rapor_sisipan_deskripsi->deskripsi1 = $input->deskripsi1;
                $rapor_sisipan_deskripsi->deskripsi2 = $input->deskripsi2;
                $rapor_sisipan_deskripsi->updated_at = $now;
                $rapor_sisipan_deskripsi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/cetak-rapor/viewDeskripsi',
                    'message' => 'Add Deskripsi Successfully'
                ];
            } elseif ($mode == 'edit') {

                $rapor_sisipan_deskripsi = RaporSisipanDeskripsi::find($id);
                $rapor_sisipan_deskripsi->nm_mata_pelajaran = $input->nm_mata_pelajaran;
                $rapor_sisipan_deskripsi->tingkat = $input->tingkat;
                $rapor_sisipan_deskripsi->kd_deskripsi = $input->kd_deskripsi;
                $rapor_sisipan_deskripsi->deskripsi1 = $input->deskripsi1;
                $rapor_sisipan_deskripsi->deskripsi2 = $input->deskripsi2;
                $rapor_sisipan_deskripsi->updated_at = $now;
                $rapor_sisipan_deskripsi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'rapor-sisipan/cetak-rapor/viewDeskripsi',
                    'message' => 'Edit Deskripsi Successfully'
                ];
            } elseif ($mode == 'delete') {

                // $kegiatan = KegiatanGuru::find($id);
                // $kegiatan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                // $kegiatan->deleted_at  = $now;
                // $kegiatan->save();

                // $kegiatan->delete();

                // return [
                //     'status' => 203, // SUCCESS AND LOAD TABLE
                //     'message' => 'Delete Kegiatan Successfully'
                // ];
            }
        }
    }

    public function postSetting(Request $request, $mata_pelajaran)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($urutan_rapor_sisipan = UrutanRaporSisipan::find($mata_pelajaran)) {
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->updated_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $urutan_rapor_sisipan               = new UrutanRaporSisipan;
            $urutan_rapor_sisipan->id_urutan_rapor_sisipan    = $id;
            $urutan_rapor_sisipan->urutan               = $input->urutan;
            $urutan_rapor_sisipan->id_mata_pelajaran   = $mata_pelajaran;
            $urutan_rapor_sisipan->created_by          = $input->auth_data->pengguna->id_pengguna;
            $urutan_rapor_sisipan->save();
        }

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'rapor-sisipan/cetak-rapor/viewSetting/',
            'message' => 'Save Successfully'
        ];
    }

    public function datatablesViewSetting(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $mapel = MataPelajaran::with('urutan_rapor_sisipan', 'jurusan', 'jenis_mata_pelajaran')->get()->sortBy('urutan_rapor_sisipan.urutan');

        return Datatables::of($mapel)
            ->addColumn('urutan', function ($item) {
                return $item->urutan_rapor_sisipan->urutan ?? 'Urutan belum di Set';
            })
            ->addColumn('action', function ($item) {
                // $k = $kurikulum->firstWhere('id_jurusan', $item->id_jurusan );
                $data = array(
                    'id_mata_pelajaran'     => $item->id_mata_pelajaran
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesViewDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $deskripsi = RaporSisipanDeskripsi::get()->sortBy(['nm_mata_pelajaran', 'kd_deskripsi']);
        // ->sortBy('kd_deskripsi')->sortBy('nm_mata_pelajaran');
        return Datatables::of($deskripsi)
            ->editColumn('deskripsi1', function ($item) {
                if (strlen($item->deskripsi1) < 75) {
                    return $item->deskripsi1;
                } else {
                    return substr($item->deskripsi1, 0, 75) . '...';
                }
            })->editColumn('deskripsi2', function ($item) {

                if (strlen($item->deskripsi2) < 75) {
                    return $item->deskripsi2;
                } else {
                    return substr($item->deskripsi2, 0, 75) . '...';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_rapor_sisipan_deskripsi'     => $item->id_rapor_sisipan_deskripsi
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakRapor(Request $request, $id_semester, $id_kelas)
    {
        set_time_limit(-1);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('id_kelas', $id_kelas)->with('jurusan')->first();
        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $id_kelas)->first();
        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->orderBy('urutan', 'asc')->get();
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->whereHas('pengguna.status_pengguna', function ($query) {
            $query->where('aktif_status_pengguna', '=', '1');
        })->orderBy('nis_siswa')->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smamaryamsby') {
            $nilai_siswa = [];
            $data = [];

            $rapor_sisipans = RaporSisipan::where('id_kelas', $id_kelas)->where('id_semester', $id_semester)->with('nilai_rapor_sisipan')->get();
            foreach ($rapor_sisipans as $rapor_sisipan) {
                foreach ($rapor_sisipan->nilai_rapor_sisipan as  $nilai_rapor_sisipan) {
                    $nilai_siswa[$nilai_rapor_sisipan['id_siswa'] . $rapor_sisipan['id_mata_pelajaran'] . $nilai_rapor_sisipan['id_komponen_nilai']] = $nilai_rapor_sisipan['nilai'];
                }
            }

            // return $nilai_siswa;

            $kelas_sisipan = KelasSisipan::where('id_kelas', $id_kelas)->get();
            $kelompok_sisipan = KelompokSisipan::with(['mata_pelajaran_sisipan' => function ($q) use ($kelas_sisipan) {
                $q->whereIn('id_mata_pelajaran_sisipan', $kelas_sisipan->pluck('id_mata_pelajaran_sisipan'))->with('mata_pelajaran');
            }, 'sub_kelompok_sisipan.mata_pelajaran_sisipan' => function ($q) use ($kelas_sisipan) {
                $q->whereIn('id_mata_pelajaran_sisipan', $kelas_sisipan->pluck('id_mata_pelajaran_sisipan'))->with('mata_pelajaran');
            }])->get();


            foreach ($kelompok_sisipan as $k_sisipan) {
                $data[$k_sisipan->urutan]['nama'] = $k_sisipan->nm_kelompok_sisipan;
                foreach ($k_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                    if ($mata_pelajaran_sisipan->jenis == '0') {
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                    } else {
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] =  $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                        $data[$k_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                    }
                }

                foreach ($k_sisipan->sub_kelompok_sisipan as $sub_kelompok_sisipan) {
                    $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['nama'] = $sub_kelompok_sisipan->nm_sub_kelompok_sisipan;
                    foreach ($sub_kelompok_sisipan->mata_pelajaran_sisipan as $mata_pelajaran_sisipan) {
                        if ($mata_pelajaran_sisipan->jenis == '0') {
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->keterangan;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = null;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                        } else {
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['nm_point'][] = $mata_pelajaran_sisipan->mata_pelajaran->nm_mata_pelajaran;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['kkm'][] = $mata_pelajaran_sisipan->mata_pelajaran->nilai_kkm;
                            $data[$k_sisipan->urutan + $sub_kelompok_sisipan->urutan]['data'][$mata_pelajaran_sisipan->urutan]['id_mata_pelajaran'][] = $mata_pelajaran_sisipan->id_mata_pelajaran;
                        }
                    }
                }
            }

            // dd($data);

            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-maryam', compact('auth_data', 'list_siswa', 'nilai_siswa', 'rapor_sisipan', 'data', 'kelas', 'list_komponen'));
        }






        // $list_nilai = NilaiRaporSisipan::with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')
        //     ->whereHas('siswa', function ($query) use ($id_kelas) {
        //         $query->where('id_kelas', '=', $id_kelas);
        //     })->whereHas('komponen_nilai', function ($query) {
        //         $query->where('status', 1)->where('type', '!=', 'uas');
        //     })->get();



        // $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
        // if ($setting == '0') {
        //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas', 'sub'));
        // } elseif ($setting == '1') {
        //     $nilai_siswa = [];
        //     $nilai_komponen = [];
        //     if ($list_siswa) {
        //         $nilai = $list_nilai->toArray();
        //         foreach ($nilai as $nilaiRapor) {
        //             foreach ($nilaiRapor as $a) {
        //                 if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
        //                     $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
        //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
        //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
        //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
        //                     }
        //                 }
        //             }
        //         }
        //     }

        //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        // } elseif ($setting == '2') {
        //     $nilai_siswa = [];
        //     $nilai_komponen = [];
        //     if ($list_siswa) {
        //         $nilai = $list_nilai->toArray();
        //         foreach ($nilai as $nilaiRapor) {
        //             foreach ($nilaiRapor as $a) {
        //                 if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
        //                     $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
        //                     // $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
        //                     // $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
        //                     // $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
        //                     // $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();

        //                     // if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas->id_komponen_nilai) {
        //                     //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
        //                     // }
        //                     // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
        //                     //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
        //                     // }
        //                     // if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
        //                     //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
        //                     // }
        //                     // if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
        //                     //     $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
        //                     // }


        //                     $nilai_tugas1 = $list_komponen->firstWhere('urutan', '=', '1');
        //                     $nilai_tugas2 = $list_komponen->firstWhere('urutan', '=', '2');
        //                     $nilai_tugas3 = $list_komponen->firstWhere('urutan', '=', '3');
        //                     $nilai_tugas4 = $list_komponen->firstWhere('urutan', '=', '4');
        //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', '=', '5');
        //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', '=', '6');
        //                     $nilai_sumatif3 = $list_komponen->firstWhere('urutan', '=', '7');
        //                     $nilai_sumatif4 = $list_komponen->firstWhere('urutan', '=', '8');
        //                     $sts = $list_komponen->firstWhere('urutan', '=', '9');

        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas1->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas2->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '2'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas3->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '3'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas4->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '4'] =  $nilaiRapor['nilai'];
        //                     }

        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
        //                         // dd($nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5']);
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif3->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '7'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif4->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '8'] =  $nilaiRapor['nilai'];
        //                     }
        //                     if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
        //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . 'sts'] =  $nilaiRapor['nilai'];
        //                     }
        //                 }
        //             }
        //         }
        //     }

        //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        // } elseif ($setting == '3') {
        //     echo 'maintane';
        // } else { }
    }

    public function viewSiswaUas(Request $request, $id_semester, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('akademik/rapor-sisipan/cetak-rapor/view-siswa-cetak-uas', compact('auth_data', 'id_semester', 'id_kelas'));
    }

    public function datatablesSiswaUas(Request $request, $id_semester, $id_kelas)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_siswa = Siswa::where('id_kelas', $id_kelas)->with('pengguna')->get();

        return Datatables::of($list_siswa)
            // ->editColumn('deskripsi1', function ($item) {
            //     if (strlen($item->deskripsi1) < 75) {
            //         return $item->deskripsi1;
            //     } else {
            //         return substr($item->deskripsi1, 0, 75) . '...';
            //     }
            // })->editColumn('deskripsi2', function ($item) {

            //     if (strlen($item->deskripsi2) < 75) {
            //         return $item->deskripsi2;
            //     } else {
            //         return substr($item->deskripsi2, 0, 75) . '...';
            //     }
            // })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_siswa'     => $item->id_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function printCetakRaporAkhir(Request $request, $id_semester, $id_siswa)
    {
        set_time_limit(1800);

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_siswa', $id_siswa)->with('pengguna')->first();
        $kelas = Kelas::where('id_kelas', $siswa->id_kelas)->with('jurusan')->first();
        $sekolah = Sekolah::first();
        //untuk sub
        $k = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp')
            ->has('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanA = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'A');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanB = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'B');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanC = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'C')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.1')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.2')->orWhere('kode_jenis_mata_pelajaran', '=', 'C.3');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $raporSisipanD = RaporSisipan::where('id_kelas', $siswa->id_kelas)->with('mata_pelajaran.sub_rapor_sisipan_mp', 'mata_pelajaran.jenis_mata_pelajaran')
            ->whereHas('mata_pelajaran.jenis_mata_pelajaran', function ($query) {
                $query->where('kode_jenis_mata_pelajaran', '=', 'D');
            })->whereHas('semester', function ($query) use ($id_semester) {
                $query->where('id_semester', '=', $id_semester);
            })
            ->doesntHave('mata_pelajaran.sub_rapor_sisipan_mp')
            ->get()->sortBy('mata_pelajaran.urutan_rapor_sisipan.urutan');

        $sub = SubRaporSisipan::with('sub_rapor_sisipan_mp', 'jenis_mata_pelajaran')->get();


        $wali_kelas = WaliKelas::with('guru.pengguna')->where('is_aktif', 1)->where('id_kelas', $siswa->id_kelas)->first();
        $semester = Semester::where('id_semester', $id_semester)->first();


        $list_komponen = KomponenNilaiRaporSisipan::where('status', 1)->where('type', '!=', 'uas')->get();
        $list_nilai = NilaiRaporSisipan::where('id_siswa', $id_siswa)->with('siswa', 'komponen_nilai', 'rapor_sisipan.semester', 'rapor_sisipan.mata_pelajaran')->get();
        $list_deskripsi = RaporSisipanDeskripsi::get();

        $setting = Setting::where('key_setting', 'mode_rapor_sisipan')->first()->value;
        if ($setting == '0') {
            return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor-akhir', compact('auth_data', 'kelas', 'siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'raporSisipanD', 'list_nilai', 'wali_kelas', 'sub', 'sekolah', 'id_semester', 'semester', 'list_deskripsi'));
        } elseif ($setting == '1') {
            echo "Maintane";
            //     $nilai_siswa = [];
            //     $nilai_komponen = [];
            //     if ($list_siswa) {
            //         $nilai = $list_nilai->toArray();
            //         foreach ($nilai as $nilaiRapor) {
            //             foreach ($nilaiRapor as $a) {
            //                 if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
            //                     $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
            //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
            //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
            //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
            //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
            //                     }
            //                 }
            //             }
            //         }
            //     }

            //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor2', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } elseif ($setting == '2') {
            echo "Maintane";
            //     $nilai_siswa = [];
            //     $nilai_komponen = [];
            //     if ($list_siswa) {
            //         $nilai = $list_nilai->toArray();
            //         foreach ($nilai as $nilaiRapor) {
            //             foreach ($nilaiRapor as $a) {
            //                 if (isset($nilaiRapor['id_komponen_nilai']) && isset($nilaiRapor['id_siswa']) && isset($nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'])) {
            //                     $nilai_siswa[$nilaiRapor['id_komponen_nilai'] . $nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran']] = $nilaiRapor['nilai'];
            //                     $nilai_tugas = $list_komponen->firstWhere('urutan', 1);
            //                     $nilai_sumatif1 = $list_komponen->firstWhere('urutan', 5);
            //                     $nilai_sumatif2 = $list_komponen->firstWhere('urutan', 6);
            //                     $sts = $list_komponen->where('type', 'uts')->where('urutan', 9)->first();
            //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_tugas->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '1'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif1->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '5'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_nilai']  == $nilai_sumatif2->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '6'] =  $nilaiRapor['nilai'];
            //                     }
            //                     if ($nilaiRapor['id_komponen_nilai']  == $sts->id_komponen_nilai) {
            //                         $nilai_komponen[$nilaiRapor['id_siswa'] . $nilaiRapor['rapor_sisipan']['mata_pelajaran']['id_mata_pelajaran'] . '9'] =  $nilaiRapor['nilai'];
            //                     }
            //                 }
            //             }
            //         }
            //     }

            //     return view('akademik/rapor-sisipan/cetak-rapor/print-cetak-rapor3', compact('auth_data', 'kelas', 'list_siswa', 'k', 'raporSisipanA', 'raporSisipanB', 'raporSisipanC', 'sub', 'list_nilai', 'wali_kelas', 'nilai_siswa', 'list_komponen', 'nilai_komponen'));
        } else { }
    }

    public function addDeskripsi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $mata_pelajaran = MataPelajaran::select('nm_mata_pelajaran')->groupBy('nm_mata_pelajaran')->get();
        return view('akademik/rapor-sisipan/cetak-rapor/add-deskripsi-rapor-sisipan', compact('auth_data', 'mata_pelajaran'));
    }
}
