<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Test;
use App\Models\Siswa;
use App\Models\PaketSoal;
use App\Models\JawabanTest;
use App\Models\PilihanSoal;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DetailPaketSoal;
use Yajra\Datatables\Datatables;
use App\Models\PilihanPertanyaan;
use App\Exports\RekapNilaiElearning;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapNilaiElearning2;
use App\Libraries\Pendidikan\LibDataAkademik;

class HasilTestController extends Controller
{
    public function indexList()
    {
        return view('guru/e-learning-soal/hasil-test/view-hasil-test');
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();

        if (auth_data()->role_aktif->id_role == '7') {
            $list_data = PaketSoal::query();
        } else {
            $list_data = PaketSoal::where('paket_soal.created_by', auth_data()->pengguna->id_pengguna);
        }

        $list_data->withCount('test')
            ->with('kategori_soal', 'paket_soal_kelas.kelas');

        return Datatables::of($list_data)
            ->addColumn('total_siswa', function ($item) {
                $total = 0;
                foreach ($item->paket_soal_kelas as $paket_soal_kelas) {
                    $jumlah_siswa =  $paket_soal_kelas->kelas->loadCount('siswa');
                    $total += $jumlah_siswa->siswa_count;
                }
                return $total;
            })
            // ->addColumn('total_mengerjakan', function ($item) {
            //     return $item->test->count();
            // })
            ->addColumn('action', function ($item) {
                $nm_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $kelas) {
                    if (!empty($kelas->kelas)) {
                        $nm_kelas[$key] = $kelas->kelas->nm_kelas;
                    }
                }
                $data = array(
                    'id' => $item->id_paket_soal,
                    'nm_kelas' => $nm_kelas,
                );
                return $data;
            })
            ->make(true);
    }

    public function indexDetail(Request $request, $id_paket_soal = 0)
    {
        if ($question_package = PaketSoal::where('id_paket_soal', $id_paket_soal)->first()) {
            return view('guru/e-learning-soal/hasil-test/detail-hasil-test', compact('question_package'));
        } else {
            return abort(404);
        }
    }

    public function indexKoreksi(Request $request, $id_paket_soal = null, $id_test = null, $id_pengguna = null)
    {
        $questions = JawabanTest::where('id_test', $id_test)->where('id_pengguna', $id_pengguna)->where('status_koreksi', 0)->with('soal')->get();
        $jawaban_test = JawabanTest::where('id_test', $id_test)->where('id_pengguna', $id_pengguna)->where('id_tipe_soal', 1)->get();
        $total_nilai = 0;
        foreach ($jawaban_test as $test) {
            $total_nilai = $total_nilai + $test->nilai;
        }
        return view('guru/e-learning-soal/hasil-test/koreksi-hasil-test', compact('questions', 'id_paket_soal', 'id_pengguna', 'total_nilai'));
    }

    public function actionKoreksiHasilTest(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jawaban_test' => 'required',
            'nilai' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        };

        $total_nilai = 0;
        foreach ($input->id_jawaban_test as $id_jawaban_test) {
            $total_nilai =  $total_nilai + $input->nilai[$id_jawaban_test];
        }
        $total_nilai = $total_nilai + $input->total_nilai;

        if ($total_nilai > 100) {
            return [
                'status' => 300, // FAILED
                'message' => 'Total Nilai tidak boleh Lebih dari 100'
            ];
        }

        foreach ($input->id_jawaban_test as $id_jawaban_test) {
            $jawaban_test = JawabanTest::where('id_jawaban_test', $id_jawaban_test)->first();
            $jawaban_test->nilai = $input->nilai[$id_jawaban_test];
            $jawaban_test->correct = (empty($input->nilai[$id_jawaban_test]) ||  $input->nilai[$id_jawaban_test] == '0' ? '0' : '1');
            $jawaban_test->tangapan = $input->tangapan[$id_jawaban_test];
            $jawaban_test->status_koreksi = 1;
            $jawaban_test->save();
        }
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'e-learning-soal/hasil-test/detail/' . $input->id_paket_soal,
            'message' => 'Berhasil Mengkoreksi Hasil Test'
        ];
    }

    public function detailList($question_package_id = 0)
    {
        $test = Test::where('test.id_paket_soal', $question_package_id)->with('pengguna.siswa.kelas', 'paket_soal', 'detail_paket_soal')->get();
        $jawaban_test = JawabanTest::whereIn('id_test', $test->pluck('id_test'))->get();
        return Datatables::of($test)
            ->editColumn('detail_paket_soal', function ($item) {
                return $item->detail_paket_soal->count();
            })->addColumn('soal_terisi', function ($item) use ($jawaban_test) {
                return $jawaban_test->where('id_test', $item->id_test)->count();
            })
            // ->addColumn('essay', function ($item) {
            //     return $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->count();
            // })
            ->addColumn('total_nilai', function ($item) use ($jawaban_test) {
                //pilihan ganda

                //total


                $nilai_pilihan_ganda = number_format($jawaban_test->where('id_test', $item->id_test)->whereIn('id_tipe_soal', [1, 4, 5, 6, 7])->sum('nilai'));
                $nilai_paket_soal_pilihan_ganda = $item->paket_soal->nilai;

                //essay
                $nilai_pilihan_essay_submit = $jawaban_test->where('id_test', $item->id_test)->whereIn('id_tipe_soal', [2, 3])->sum('nilai');
                $jawaban_test = $jawaban_test->where('id_test', $item->id_test)->whereIn('id_tipe_soal', [2, 3])->where('status_koreksi', 0)->first();



                if ($nilai_pilihan_ganda > 100) {
                    $nilai_pilihan_ganda  = 100;
                }

                if ($nilai_pilihan_essay_submit  > 100) {
                    $nilai_pilihan_essay_submit = 100;
                }

                $nilai = $nilai_pilihan_ganda + $nilai_pilihan_essay_submit;
                if ($nilai > 100) {
                    $nilai = 100;
                }


                $data = array(
                    'nilai_pilihan_ganda' => $nilai_pilihan_ganda,
                    'nilai' => $nilai,
                    'id_test' => $jawaban_test ? $jawaban_test->id_test : '',
                    'status_koreksi' =>  $jawaban_test ? '0' : '1',
                    'id_pengguna' => $item->id_pengguna,
                    'id_paket_soal' => $item->id_paket_soal,
                    'nilai_paket_soal_pilihan_ganda' => $nilai_paket_soal_pilihan_ganda,
                    'nilai_pilihan_essay_submit' => $nilai_pilihan_essay_submit,
                    // 'total_nilai_pilihan_ganda_benar' => $total_nilai_pilihan_ganda_benar,
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_test
                );
                return $data;
            })
            ->make(true);
    }

    public function actionDeleteTest(Request $request, $id)
    {
        if ($test = Test::find($id)) {
            JawabanTest::where('id_test', $test->id_test)->delete();
            $test->delete();

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Hasil Test Successfully'
            ];
        } else {
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Failed'
            ];
        }
    }

    public function printHasilTest(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa', 'test', 'kategori_soal')->first();
        $time = Carbon::parse($paket_soal->waktu_mulai);
        // $kelas = '';
        // $siswa_seharusnya = 0;
        // foreach ($paket_soal->paket_soal_kelas as $paket_soal_kelas) {
        //     $kelas =  $kelas . $paket_soal_kelas->kelas->nm_kelas . ', ';
        //     $siswa_seharusnya += $paket_soal_kelas->kelas->siswa->count();
        // }

        // $siswa_tidak_masuk = $siswa_seharusnya - $paket_soal->test->count();

        return view('guru/e-learning-soal/hasil-test/print-hasil-test', compact('paket_soal', 'auth_data', 'semester_aktif', 'time'));
    }

    public function printHasilTest2(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa.pengguna', 'test', 'kategori_soal')->first();
        $time = Carbon::parse($paket_soal->waktu_mulai);

        return view('guru/e-learning-soal/hasil-test/print-daftar-hasil', compact('paket_soal', 'auth_data', 'semester_aktif', 'time'));
    }

    public function printHasilTest3(Request $request, $id)
    {
        set_time_limit(-1);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa.pengguna',  'kategori_soal', 'detail_paket_soal.soal')->first();
        $pilihan_pertanyaan = PilihanPertanyaan::whereIn('id_soal', $paket_soal->detail_paket_soal->pluck('id_soal'))->get();
        $pilihan_soal = PilihanSoal::whereIn('id_soal', $paket_soal->detail_paket_soal->pluck('id_soal'))->get();

        $tests = Test::where('id_paket_soal', $paket_soal->id_paket_soal)->get();
        // 'test.jawaban_test'
        $nilai_siswa = [];
        $benar = [];
        $isi = [];
        $mapping = ['A', 'B', 'C', 'D', 'E'];

        foreach ($tests as $test) {
            $jawaban_tests = JawabanTest::where('id_test', $test->id_test)->get();
            foreach ($jawaban_tests as $jawaban_test) {
                if (isset($nilai_siswa[$test->id_pengguna])) {
                    $nilai_siswa[$test->id_pengguna] +=  $jawaban_test->nilai;
                } else {
                    $nilai_siswa[$test->id_pengguna] =  $jawaban_test->nilai;
                }
                if ($jawaban_test->id_tipe_soal == '1' ||  $jawaban_test->id_tipe_soal == '2' ||  $jawaban_test->id_tipe_soal == '3' ||  $jawaban_test->id_tipe_soal == '4' || $jawaban_test->id_tipe_soal == '5') {
                    if (!empty($jawaban_test->nilai) && $jawaban_test->nilai != '0') {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = true;
                    }

                    if ($jawaban_test->id_tipe_soal == '1') {
                        $number_option = $jawaban_test->pilihan_soal->number_option;
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = isset($mapping[$number_option]) ? $mapping[$number_option] : '-';
                    } else if ($jawaban_test->id_tipe_soal == '2') {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = substr($jawaban_test->jawaban_essay, 0, 10);
                    } else if ($jawaban_test->id_tipe_soal == '3') {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = null;
                    } else if ($jawaban_test->id_tipe_soal == '4') {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = '';
                        $complex_ids = [
                            $jawaban_test->id_pilihan_soal_kompleks1,
                            $jawaban_test->id_pilihan_soal_kompleks2,
                            $jawaban_test->id_pilihan_soal_kompleks3,
                            $jawaban_test->id_pilihan_soal_kompleks4,
                            $jawaban_test->id_pilihan_soal_kompleks5,
                        ];

                        foreach ($complex_ids as $complex_id) {
                            $pilihan = $pilihan_soal->where('id_pilihan_soal', $complex_id)->first();
                            $pp = $pilihan ? (isset($mapping[$pilihan->number_option]) ? $mapping[$pilihan->number_option] : '') : null;
                            $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] .= $pp;
                        }
                    } else if ($jawaban_test->id_tipe_soal == '5') {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal] = substr($jawaban_test->jawaban_essay, 0, 10);
                    }
                } elseif ($jawaban_test->id_tipe_soal == '6') {
                    $p1 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '1')->first();

                    if ($p1) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] = $jawaban_test->pilihan_jawaban1;
                    }
                    if ($p1 && $p1->jawaban == $jawaban_test->pilihan_jawaban1) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] = true;
                    }

                    $p2 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '2')->first();
                    if ($p2) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] = $jawaban_test->pilihan_jawaban2;
                    }
                    if ($p2 && $p2->jawaban == $jawaban_test->pilihan_jawaban2) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] = true;
                    }

                    $p3 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '3')->first();
                    if ($p3) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = $jawaban_test->pilihan_jawaban3;
                    }
                    if ($p3 && $p3->jawaban == $jawaban_test->pilihan_jawaban3) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = true;
                    }

                    $p4 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '4')->first();
                    if ($p4) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = $jawaban_test->pilihan_jawaban4;
                    }
                    if ($p4 && $p4->jawaban == $jawaban_test->pilihan_jawaban4) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = true;
                    }

                    $p5 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '5')->first();
                    if ($p5) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][5] = $jawaban_test->pilihan_jawaban5;
                    }
                    if ($p5 && $p5->jawaban == $jawaban_test->pilihan_jawaban5) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][5] = true;
                    }
                } elseif ($jawaban_test->id_tipe_soal == '7') {

                    $p1 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '0')->first();
                    if ($p1) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][0] = $jawaban_test->pilihan_jawaban1 == '0' ? 'False' : 'True';
                    }
                    if ($p1 && $p1->correct == $jawaban_test->pilihan_jawaban1) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][0] = true;
                    }

                    $p2 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '1')->first();
                    if ($p2) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] = $jawaban_test->pilihan_jawaban2  == '0' ? 'False' : 'True';
                    }
                    if ($p2 && $p2->correct == $jawaban_test->pilihan_jawaban2) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] = true;
                    }

                    $p3 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '2')->first();
                    if ($p3) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] = $jawaban_test->pilihan_jawaban3  == '0' ? 'False' : 'True';
                    }
                    if ($p3 && $p3->correct == $jawaban_test->pilihan_jawaban3) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] = true;
                    }

                    $p4 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '3')->first();
                    if ($p4) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = $jawaban_test->pilihan_jawaban4  == '0' ? 'False' : 'True';
                    }
                    if ($p4 && $p4->correct == $jawaban_test->pilihan_jawaban4) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = true;
                    }

                    $p5 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '4')->first();
                    if ($p5) {
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = $jawaban_test->pilihan_jawaban5  == '0' ? 'False' : 'True';
                    }
                    if ($p5 && $p5->correct == $jawaban_test->pilihan_jawaban5) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = true;
                    }
                } elseif ($jawaban_test->id_tipe_soal == '8') {
                    $p1 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '1')->first();

                    if ($p1) {
                        switch ($jawaban_test->pilihan_jawaban1) {
                            case 0:
                                $hasil = "A";
                                break;
                            case 1:
                                $hasil = "B";
                                break;
                            case 2:
                                $hasil = "C";
                                break;
                            case 3:
                                $hasil = "D";
                                break;

                            case 4:
                                $hasil = "E";
                                break;

                            default:
                                $hasil = "-";
                        }
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] =  $hasil;
                    }
                    if ($p1 && $p1->jawaban == $jawaban_test->pilihan_jawaban1) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][1] = true;
                    }

                    $p2 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '2')->first();
                    if ($p2) {
                        switch ($jawaban_test->pilihan_jawaban2) {
                            case 0:
                                $hasil = "A";
                                break;
                            case 1:
                                $hasil = "B";
                                break;
                            case 2:
                                $hasil = "C";
                                break;
                            case 3:
                                $hasil = "D";
                                break;

                            case 4:
                                $hasil = "E";
                                break;

                            default:
                                $hasil = "-";
                        }
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] =  $hasil;
                    }
                    if ($p2 && $p2->jawaban == $jawaban_test->pilihan_jawaban2) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][2] = true;
                    }

                    $p3 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '3')->first();
                    if ($p3) {
                        switch ($jawaban_test->pilihan_jawaban3) {
                            case 0:
                                $hasil = "A";
                                break;
                            case 1:
                                $hasil = "B";
                                break;
                            case 2:
                                $hasil = "C";
                                break;
                            case 3:
                                $hasil = "D";
                                break;

                            case 4:
                                $hasil = "E";
                                break;

                            default:
                                $hasil = "-";
                        }
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = $hasil;
                    }
                    if ($p3 && $p3->jawaban == $jawaban_test->pilihan_jawaban3) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][3] = true;
                    }

                    $p4 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '4')->first();
                    if ($p4) {
                        switch ($jawaban_test->pilihan_jawaban4) {
                            case 0:
                                $hasil = "A";
                                break;
                            case 1:
                                $hasil = "B";
                                break;
                            case 2:
                                $hasil = "C";
                                break;
                            case 3:
                                $hasil = "D";
                                break;

                            case 4:
                                $hasil = "E";
                                break;

                            default:
                                $hasil = "-";
                        }
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = $hasil;
                    }
                    if ($p4 && $p4->jawaban == $jawaban_test->pilihan_jawaban4) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][4] = true;
                    }

                    $p5 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '5')->first();
                    if ($p5) {
                        switch ($jawaban_test->pilihan_jawaban5) {
                            case 0:
                                $hasil = "A";
                                break;
                            case 1:
                                $hasil = "B";
                                break;
                            case 2:
                                $hasil = "C";
                                break;
                            case 3:
                                $hasil = "D";
                                break;

                            case 4:
                                $hasil = "E";
                                break;

                            default:
                                $hasil = "-";
                        }
                        $isi[$jawaban_test->id_pengguna][$jawaban_test->id_soal][5] = $hasil;
                    }
                    if ($p5 && $p5->jawaban == $jawaban_test->pilihan_jawaban5) {
                        $benar[$jawaban_test->id_pengguna][$jawaban_test->id_soal][5] = true;
                    }
                }
            }
        }


        $data['nilai_siswa'] = $nilai_siswa;
        $data['paket_soal'] = $paket_soal;
        $data['benar'] = $benar;
        $data['isi'] = $isi;

        return Excel::download(new RekapNilaiElearning($data), 'Detail Jawaban Siswa' . $paket_soal->text . '(' . $paket_soal->kategori_soal->nm_kategori_soal . ').xlsx');
    }

    public function printHasilTest4(Request $request, $id)
    {
        set_time_limit(-1);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa.pengguna',  'kategori_soal', 'detail_paket_soal.soal')->first();
        $pilihan_pertanyaan = PilihanPertanyaan::whereIn('id_soal', $paket_soal->detail_paket_soal->pluck('id_soal'))->get();
        $pilihan_soal = PilihanSoal::whereIn('id_soal', $paket_soal->detail_paket_soal->pluck('id_soal'))->get();

        $tests = Test::where('id_paket_soal', $paket_soal->id_paket_soal)->get();

        $nilai_siswa = [];
        $benar = [];

        foreach ($tests as $test) {
            $type1 = 0;
            $type2 = 0;
            $jawaban_tests = JawabanTest::where('id_test', $test->id_test)->get();
            foreach ($jawaban_tests as $jawaban_test) {
                if (isset($nilai_siswa[$test->id_pengguna])) {
                    $nilai_siswa[$test->id_pengguna] +=  $jawaban_test->nilai;
                } else {
                    $nilai_siswa[$test->id_pengguna] =  $jawaban_test->nilai;
                }

                if ($jawaban_test->id_tipe_soal == '1' ||  $jawaban_test->id_tipe_soal == '2' ||  $jawaban_test->id_tipe_soal == '3' ||  $jawaban_test->id_tipe_soal == '4' || $jawaban_test->id_tipe_soal == '5') {
                    if (!empty($jawaban_test->nilai) && $jawaban_test->nilai != '0') {
                        $type1++;
                    }
                } elseif ($jawaban_test->id_tipe_soal == '6') {
                    $p1 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '1')->first();

                    if ($p1 && $p1->jawaban == $jawaban_test->pilihan_jawaban1) {
                        $type2++;
                    }

                    $p2 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '2')->first();

                    if ($p2 && $p2->jawaban == $jawaban_test->pilihan_jawaban2) {
                        $type2++;
                    }

                    $p3 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '3')->first();

                    if ($p3 && $p3->jawaban == $jawaban_test->pilihan_jawaban3) {
                        $type2++;
                    }

                    $p4 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '4')->first();

                    if ($p4 && $p4->jawaban == $jawaban_test->pilihan_jawaban4) {
                        $type2++;
                    }

                    $p5 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '5')->first();

                    if ($p5 && $p5->jawaban == $jawaban_test->pilihan_jawaban5) {
                        $type2++;
                    }
                } elseif ($jawaban_test->id_tipe_soal == '7') {
                    $p1 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '0')->first();

                    if ($p1 && $p1->correct == $jawaban_test->pilihan_jawaban1) {
                        $type2++;
                    }

                    $p2 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '1')->first();

                    if ($p2 && $p2->correct == $jawaban_test->pilihan_jawaban2) {
                        $type2++;
                    }

                    $p3 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '2')->first();

                    if ($p3 && $p3->correct == $jawaban_test->pilihan_jawaban3) {
                        $type2++;
                    }

                    $p4 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '3')->first();

                    if ($p4 && $p4->correct == $jawaban_test->pilihan_jawaban4) {
                        $type2++;
                    }

                    $p5 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '4')->first();

                    if ($p5 && $p5->correct == $jawaban_test->pilihan_jawaban5) {
                        $type2++;
                    }
                } elseif ($jawaban_test->id_tipe_soal == '8') {
                    $p1 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '1')->first();

                    if ($p1 && $p1->jawaban == $jawaban_test->pilihan_jawaban1) {
                        $type2++;
                    }

                    $p2 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '2')->first();

                    if ($p2 && $p2->jawaban == $jawaban_test->pilihan_jawaban2) {
                        $type2++;
                    }

                    $p3 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '3')->first();

                    if ($p3 && $p3->jawaban == $jawaban_test->pilihan_jawaban3) {
                        $type2++;
                    }

                    $p4 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '4')->first();

                    if ($p4 && $p4->jawaban == $jawaban_test->pilihan_jawaban4) {
                        $type2++;
                    }

                    $p5 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '5')->first();

                    if ($p5 && $p5->jawaban == $jawaban_test->pilihan_jawaban5) {
                        $type2++;
                    }
                }
            }
            $benar[$test->id_pengguna]['type1'] = $type1;
            $benar[$test->id_pengguna]['type2'] = $type2;
        }

        $soal_biasa = $paket_soal->detail_paket_soal->whereIn('soal.id_tipe_soal', [1, 2, 3, 4, 5])->count();
        $soal_cabang = 0;
        $query_soal_cabang = $paket_soal->detail_paket_soal->whereIn('soal.id_tipe_soal', [6, 7]);
        foreach ($query_soal_cabang as $soal) {
            $soal_cabang += $soal->soal->pilihan_pertanyaan->count();
        }

        if ($soal_biasa == '0' &&  $soal_cabang == '0') {
            $nilai = 0;
        } else {
            $nilai = number_format(100 / ($soal_biasa + $soal_cabang), 1);
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['paket_soal'] = $paket_soal;
        $data['benar'] = $benar;
        $data['point'] = $paket_soal->nilai != '0' ? $paket_soal->nilai : $nilai;

        return Excel::download(new RekapNilaiElearning2($data), 'Rekap Nilai E-learning' . Str::slug($paket_soal->text) . '(' . $paket_soal->kategori_soal->nm_kategori_soal . ').xlsx');
    }

    public function koreksiUlang(Request $request)
    {
        set_time_limit(-1);
        $paket_soals = PaketSoal::with('detail_paket_soal.soal.pilihan_pertanyaan', 'test.jawaban_test')->orderBy('paket_soal.created_at', 'desc')->whereHas('test')->get();

        $pilihan_pertanyaan = PilihanPertanyaan::get();
        $pilihan_soal = PilihanSoal::get();


        foreach ($paket_soals as $paket_soal) {
            $soal_biasa = $paket_soal->detail_paket_soal->whereIn('soal.id_tipe_soal', [1, 2, 3, 4, 5])->count();
            $soal_cabang = 0;
            $query_soal_cabang = $paket_soal->detail_paket_soal->whereIn('soal.id_tipe_soal', [6, 7]);
            foreach ($query_soal_cabang as $soal) {
                $soal_cabang += $soal->soal->pilihan_pertanyaan->count();
            }

            if ($soal_biasa == '0' &&  $soal_cabang == '0') {
                $nilai = 0;
            } else {
                $nilai = number_format(100 / ($soal_biasa + $soal_cabang), 1);
            }


            if ($paket_soal->nilai == '0') {
                foreach ($paket_soal->test as $test) {
                    foreach ($test->jawaban_test as $jawaban_test) {
                        if ($jawaban_test->id_tipe_soal == '1' ||  $jawaban_test->id_tipe_soal == '2' ||  $jawaban_test->id_tipe_soal == '3' ||  $jawaban_test->id_tipe_soal == '4' || $jawaban_test->id_tipe_soal == '5') {
                            if ($jawaban_test->id_tipe_soal == '4') {
                                $jawaban_benar = true;

                                $pilihan_jawaban = $pilihan_soal->where('id_pilihan_soal', $jawaban_test->id_pilihan_soal_kompleks1)->first();
                                if ($pilihan_jawaban) {
                                    if ($pilihan_jawaban->correct == 1) {
                                    } else {
                                        $jawaban_benar = false;
                                    }
                                }
                                $pilihan_jawaban = $pilihan_soal->where('id_pilihan_soal', $jawaban_test->id_pilihan_soal_kompleks2)->first();
                                if ($pilihan_jawaban) {
                                    if ($pilihan_jawaban->correct == 1) {
                                    } else {
                                        $jawaban_benar = false;
                                    }
                                }

                                $pilihan_jawaban = $pilihan_soal->where('id_pilihan_soal', $jawaban_test->id_pilihan_soal_kompleks3)->first();
                                if ($pilihan_jawaban) {
                                    if ($pilihan_jawaban->correct == 1) {
                                    } else {
                                        $jawaban_benar = false;
                                    }
                                }

                                $pilihan_jawaban = $pilihan_soal->where('id_pilihan_soal', $jawaban_test->id_pilihan_soal_kompleks4)->first();
                                if ($pilihan_jawaban) {
                                    if ($pilihan_jawaban->correct == 1) {
                                    } else {
                                        $jawaban_benar = false;
                                    }
                                }

                                $pilihan_jawaban = $pilihan_soal->where('id_pilihan_soal', $jawaban_test->id_pilihan_soal_kompleks5)->first();
                                if ($pilihan_jawaban) {
                                    if ($pilihan_jawaban->correct == 1) {
                                    } else {
                                        $jawaban_benar = false;
                                    }
                                }

                                $jawaban_test->nilai =  $jawaban_benar ? $nilai : 0;
                                $jawaban_test->updated_by = 'syahrul';
                                $jawaban_test->save();
                            } else {
                                if (!empty($jawaban_test->nilai) && $jawaban_test->nilai != '0') {
                                    $jawaban_test->nilai =  $nilai;
                                    $jawaban_test->updated_by = 'syahrul';
                                    $jawaban_test->save();
                                }
                            }
                        } elseif ($jawaban_test->id_tipe_soal == '6') {
                            $total_nilai = 0;
                            $p1 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '1')->first();
                            if ($p1 && $p1->jawaban == $jawaban_test->pilihan_jawaban1) {
                                $total_nilai += $nilai;
                            }

                            $p2 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '2')->first();
                            if ($p2 && $p2->jawaban == $jawaban_test->pilihan_jawaban2) {
                                $total_nilai += $nilai;
                            }

                            $p3 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '3')->first();
                            if ($p3 && $p3->jawaban == $jawaban_test->pilihan_jawaban3) {
                                $total_nilai += $nilai;
                            }

                            $p4 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '4')->first();
                            if ($p4 && $p4->jawaban == $jawaban_test->pilihan_jawaban4) {
                                $total_nilai += $nilai;
                            }

                            $p5 =  $pilihan_pertanyaan->where('id_soal', $jawaban_test->id_soal)->where('nomer', '5')->first();
                            if ($p5 && $p5->jawaban == $jawaban_test->pilihan_jawaban5) {
                                $total_nilai += $nilai;
                            }
                            $jawaban_test->nilai =  $total_nilai;
                            $jawaban_test->updated_by = 'syahrul';
                            $jawaban_test->save();
                        } elseif ($jawaban_test->id_tipe_soal == '7') {
                            $total_nilai = 0;
                            $p1 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '0')->first();
                            if ($p1 && $p1->correct == $jawaban_test->pilihan_jawaban1) {
                                $total_nilai += $nilai;
                            }

                            $p2 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '1')->first();
                            if ($p2 && $p2->correct == $jawaban_test->pilihan_jawaban2) {
                                $total_nilai += $nilai;
                            }

                            $p3 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '2')->first();
                            if ($p3 && $p3->correct == $jawaban_test->pilihan_jawaban3) {
                                $total_nilai += $nilai;
                            }

                            $p4 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '3')->first();
                            if ($p4 && $p4->correct == $jawaban_test->pilihan_jawaban4) {
                                $total_nilai += $nilai;
                            }

                            $p5 =  $pilihan_soal->where('id_soal', $jawaban_test->id_soal)->where('number_option', '4')->first();
                            if ($p5 && $p5->correct == $jawaban_test->pilihan_jawaban5) {
                                $total_nilai += $nilai;
                            }
                            $jawaban_test->nilai =  $total_nilai;
                            $jawaban_test->updated_by = 'syahrul';
                            $jawaban_test->save();
                        }
                    }
                }
            }
        }
        return 'sukses';
    }

    public function hapus()
    {
        set_time_limit(-1);
        $detail_paket_soals = DetailPaketSoal::where('id_paket_soal', 'Qjh121696250100651ab8f45d9be')->get();

        foreach ($detail_paket_soals as $detail_paket_soal) {
            $tests = Test::where('id_paket_soal', 'Qjh121696250100651ab8f45d9be')->get();
            foreach ($tests as $test) {
                $jawaban_test = JawabanTest::where('id_soal', $detail_paket_soal->id_soal)->where('id_test', $test->id_test)->first();

                if ($jawaban_test && $jawaban_test->nilai == "3.8") {
                    $jawaban_test->nilai = '2.9';
                    $jawaban_test->updated_by = "syahrul rabu";
                    $jawaban_test->save();
                    // $jawaban_test->delete();
                }
            }
        }

        return 'berhasil';
    }
}
