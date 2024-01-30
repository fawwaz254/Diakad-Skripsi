<?php

namespace App\Http\Controllers\Siswa\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\JawabanTest;
use App\Models\Kelas;
use App\Models\PaketSoal;
use App\Models\PilihanSoal;
use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;
use App\Models\Soal;
use App\Models\Test;
use Yajra\Datatables\Datatables;
use App\Jobs\ElearningAnswer;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;


class ListUjianController extends Controller
{
    public function indexList(Request $request)
    {
        return view('siswa/e-learning-soal/list-ujian/view-list-ujian');
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $id_kelas =  $siswa->id_kelas;
        $list_data = PaketSoal::with(
            [
                'testSiswa' => function ($query) use ($auth_data) {
                    $query->where('id_pengguna', $auth_data->pengguna->id_pengguna);
                }, 'kategori_soal',
            ]
        )->whereHas('paket_soal_kelas', function ($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
        })->orderBy('created_at', 'desc');
        $waktu = Carbon::now('Asia/Jakarta');
        return Datatables::of($list_data)
            ->editColumn('waktu_pengerjaan', '{{$waktu_pengerjaan}} Menit')
            ->addColumn('status', function ($item) use ($waktu) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_mulai);
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_selesai);

                if (!$item->testSiswa && strtotime($waktu) > strtotime($end_date)) {
                    $status = "Waktu Berakhir";
                } else {
                    if (strtotime($start_date) > strtotime($waktu)) {
                        $status = "Test Belum dimulai";
                    } else {
                        if ($item->testSiswa) {
                            if ($item->testSiswa->status == 1) {
                                $status = "Sudah Mengerjakan";
                            } else {
                                $status = "Sedang dikerjakan";
                            }
                        } else {
                            $status = "Siap dimulai";
                        }
                    }
                }
                return $status;
            })
            ->addColumn('action', function ($item) use ($waktu) {
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_mulai);
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_selesai);

                if (!$item->testSiswa && strtotime($waktu) > strtotime($end_date)) {
                    $status = "98";
                } else {
                    if (strtotime($start_date) > strtotime($waktu)) {
                        $status = "99";
                    } else {
                        if ($item->testSiswa) {
                            if ($item->testSiswa->status == 1) {
                                $status = "1";
                            } else {
                                $status = "2";
                            }
                        } else {
                            $status = "0";
                        }
                    }
                }
                $data = array(
                    'id' => $item->id_paket_soal,
                    'status' => $status
                );
                return $data;
            })
            ->make(true);
    }

    public function indexTest(Request $request, $id_paket_soal = 0)
    {
        $input = (object) $request->input();
        $account = $input->auth_data->pengguna->id_pengguna;
        $now = Carbon::now();
        $test = Test::where('id_pengguna', $account)->where('id_paket_soal', $id_paket_soal)->first();
        if ($test && session()->has($id_paket_soal)) {
            if ($now > $test->waktu_selesai_pengerjaan) {
                $test->status = 1;
                $test->save();
                return redirect('siswa/e-learning-soal/list-ujian');
            } else {
                return redirect('siswa/e-learning-soal/list-ujian/test/' . $id_paket_soal . '/1');
            }
        } else {
            $soal = PaketSoal::find($id_paket_soal);
            $test_duration = $soal->waktu_pengerjaan;
            $start_time = Carbon::now('Asia/Jakarta');
            $end_time = Carbon::now('Asia/Jakarta')->addMinutes($test_duration);

            $question_number = 1;
            $urutan_soal = array();
            $question_package_details = DetailPaketSoal::with('soal.pilihan_soal', 'soal.pilihan_pertanyaan', 'soal.pilihan_jawaban')->where('id_paket_soal', $id_paket_soal)->inRandomOrder()->get();
            foreach ($question_package_details as $question_package_detail) {
                $urutan_soal[$question_number] = $question_package_detail;
                $question_number++;
            }

            if ($test) {
                if ($now > $test->waktu_selesai_pengerjaan) {
                    $test->status = 1;
                    $test->save();
                    return redirect('siswa/e-learning-soal/list-ujian');
                }
            } else {
                $test = new Test;
                $test->id_test = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $test->id_pengguna = $account;
                $test->id_paket_soal = $id_paket_soal;
                $test->waktu_mulai_pengerjaan = $start_time;
                $test->waktu_selesai_pengerjaan = $end_time;
                $test->status = 0;
                $test->save();
            }

            //hapus cache lama
            $no = 1;
            session()->forget($id_paket_soal);
            while ($no <= $question_package_details->count()) {
                session()->has($id_paket_soal . '_jawaban' . $no) ? session()->forget($id_paket_soal . '_jawaban' . $no) : null;
                $no++;
            }

            if ($test) {
                $jawaban_test = JawabanTest::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)
                    ->where('id_test', $test->id_test)
                    ->whereIn('id_soal', $question_package_details->pluck('id_soal'))->get();

                foreach ($urutan_soal as $key => $value) {
                    $jawaban = $jawaban_test->firstWhere('id_soal', $value->id_soal);
                    if ($jawaban) {
                        if ($jawaban->id_tipe_soal == '1') {
                            session([$id_paket_soal . '_jawaban' . $key => $jawaban->id_pilihan_soal]);
                        } elseif ($jawaban->id_tipe_soal == '2') {
                            session([$id_paket_soal . '_jawaban' . $key => $jawaban->jawaban_essay]);
                        } elseif ($jawaban->id_tipe_soal == '3') {
                            session([$id_paket_soal . '_jawaban' . $key => $jawaban->link_file]);
                        } elseif ($jawaban->id_tipe_soal == '4') {
                            session([$id_paket_soal . '_jawaban' . $key => [1 => $jawaban->id_pilihan_soal_kompleks1, 2 => $jawaban->id_pilihan_soal_kompleks2, 3 => $jawaban->id_pilihan_soal_kompleks3, 4 => $jawaban->id_pilihan_soal_kompleks4, 5 => $jawaban->id_pilihan_soal_kompleks5]]);
                        } elseif ($jawaban->id_tipe_soal == '5') {
                            session([$id_paket_soal . '_jawaban' . $key => $jawaban->link_file]);
                        } elseif ($jawaban->id_tipe_soal == '6') {
                            session([$id_paket_soal . '_jawaban' . $key => [1 => $jawaban->pilihan_jawaban1, 2 => $jawaban->pilihan_jawaban2, 3 => $jawaban->pilihan_jawaban3, 4 => $jawaban->pilihan_jawaban4, 5 => $jawaban->pilihan_jawaban5]]);
                        } elseif ($jawaban->id_tipe_soal == '7') {
                            session([$id_paket_soal . '_jawaban' . $key => [1 => $jawaban->pilihan_jawaban1, 2 => $jawaban->pilihan_jawaban2, 3 => $jawaban->pilihan_jawaban3, 4 => $jawaban->pilihan_jawaban4, 5 => $jawaban->pilihan_jawaban5]]);
                        } elseif ($jawaban->id_tipe_soal == '8') {
                            session([$id_paket_soal . '_jawaban' . $key => [1 => $jawaban->pilihan_jawaban1, 2 => $jawaban->pilihan_jawaban2, 3 => $jawaban->pilihan_jawaban3, 4 => $jawaban->pilihan_jawaban4, 5 => $jawaban->pilihan_jawaban5]]);
                        }
                    }
                }
            }


            $point = $soal->nilai;
            if ($point == '0') {
                $soal_biasa = $question_package_details->whereIn('soal.id_tipe_soal', [1, 2, 3, 4, 5])->count();
                $soal_cabang = 0;
                $query_soal_cabang = $question_package_details->whereIn('soal.id_tipe_soal', [6, 7, 8]);
                foreach ($query_soal_cabang as $soal) {
                    $soal_cabang += $soal->soal->pilihan_pertanyaan->count();
                }

                if ($soal_biasa == '0' &&  $soal_cabang == '0') {
                    $point = 0;
                } else {
                    $point = number_format(100 / ($soal_biasa + $soal_cabang), 1);
                }
            }

            $all_data = array();
            $all_data['bank_soal']  =  $urutan_soal;
            $all_data['start_time'] =  $start_time;
            $all_data['end_time']   =  $end_time;
            $all_data['id_test']    =  $test->id_test;
            $all_data['point_pilihan_ganda']    =  $point;
            $all_data['version'] = $soal->version;

            session([$id_paket_soal => $all_data]);
            return redirect('siswa/e-learning-soal/list-ujian/test/' . $id_paket_soal . '/1');
        }
    }

    public function actionSaveAnswer(Request $request)
    {
        $input = (object) $request->input();
        $now = Carbon::now();
        $test_answer = array();
        $nilai = 0;
        $correct = 0;
        if ($input->id_tipe_soal == 1) {
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_soal']->where('id_pilihan_soal', $input->question_option)->first();
            if ($pilihan_jawaban->correct == 1) {
                $nilai = session($input->paket_soal)['point_pilihan_ganda'];
                $correct = 1;
            }

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'id_pilihan_soal' => $input->question_option,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );

            session([$input->paket_soal . '_jawaban' . $input->no => $input->question_option]);
        } elseif ($input->id_tipe_soal == 2) {
            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => null,
                'jawaban_essay' => $input->jawaban_essay,
                'status_koreksi' => 0,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );
            session([$input->paket_soal . '_jawaban' . $input->no => $input->jawaban_essay]);
        } elseif ($input->id_tipe_soal == 3) {
            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
            $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/jawaban_test', request()->file, 'public');
            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => null,
                'link_file' => $file,
                'type_file' => pathinfo(request()->file->getClientOriginalName(), PATHINFO_EXTENSION),
                'status_koreksi' => 0,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );
            session([$input->paket_soal . '_jawaban' . $input->no => $file]);
        } elseif ($input->id_tipe_soal == 4) {
            $jawaban_benar = false;
            $jawaban = [];
            if (isset($input->question_option)) {
                $jawaban_benar = true;
                $correct = 1;
                foreach ($input->question_option as $question_option) {
                    $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_soal']->where('id_pilihan_soal', $question_option)->first();
                    if ($pilihan_jawaban->correct == 1) {
                    } else {
                        $jawaban_benar = false;
                        $correct = 0;
                    }
                    $jawaban[] = $question_option;
                }
            }

            $nilai =  $jawaban_benar ? session($input->paket_soal)['point_pilihan_ganda'] : 0;

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'id_pilihan_soal' => null,
                'id_pilihan_soal_kompleks1' => isset($jawaban[0]) ? $jawaban[0] : null,
                'id_pilihan_soal_kompleks2' => isset($jawaban[1]) ? $jawaban[1] : null,
                'id_pilihan_soal_kompleks3' => isset($jawaban[2]) ? $jawaban[2] : null,
                'id_pilihan_soal_kompleks4' => isset($jawaban[3]) ? $jawaban[3] : null,
                'id_pilihan_soal_kompleks5' => isset($jawaban[4]) ? $jawaban[4] : null,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );

            session([$input->paket_soal . '_jawaban' . $input->no => $jawaban]);
        } elseif ($input->id_tipe_soal == 5) {
            $alternatif_jawaban1 = session($input->paket_soal)['bank_soal'][$input->no]['soal']['alternatif_jawaban1'];
            $alternatif_jawaban2 = session($input->paket_soal)['bank_soal'][$input->no]['soal']['alternatif_jawaban2'];
            $alternatif_jawaban3 = session($input->paket_soal)['bank_soal'][$input->no]['soal']['alternatif_jawaban3'];
            $alternatif_jawaban4 = session($input->paket_soal)['bank_soal'][$input->no]['soal']['alternatif_jawaban4'];
            $alternatif_jawaban5 = session($input->paket_soal)['bank_soal'][$input->no]['soal']['alternatif_jawaban5'];
            if (
                strtolower($alternatif_jawaban1)  == strtolower($input->jawaban_essay) ||
                strtolower($alternatif_jawaban2)  == strtolower($input->jawaban_essay) ||
                strtolower($alternatif_jawaban3)  == strtolower($input->jawaban_essay) ||
                strtolower($alternatif_jawaban4)  == strtolower($input->jawaban_essay) ||
                strtolower($alternatif_jawaban5)  == strtolower($input->jawaban_essay)
            ) {
                $nilai =  session($input->paket_soal)['point_pilihan_ganda'];
                $correct = 1;
            } else {
                $nilai = 0;
            }

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'jawaban_essay' => $input->jawaban_essay,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );
            session([$input->paket_soal . '_jawaban' . $input->no => $input->jawaban_essay]);
        } elseif ($input->id_tipe_soal == 6) {
            $jawaban_benar = 0;
            $jawaban = [];
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_pertanyaan'];

            foreach ($pilihan_jawaban as $jawaban) {
                if (isset($input->jawaban[$jawaban->nomer])) {
                    if ($input->jawaban[$jawaban->nomer] == $jawaban->jawaban) {
                        $jawaban_benar += session($input->paket_soal)['point_pilihan_ganda'];
                        $correct++;
                    }
                }
            }


            $nilai = $jawaban_benar;

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'pilihan_jawaban1' => isset($input->jawaban[1]) ? $input->jawaban[1] : null,
                'pilihan_jawaban2' => isset($input->jawaban[2]) ? $input->jawaban[2] : null,
                'pilihan_jawaban3' => isset($input->jawaban[3]) ? $input->jawaban[3] : null,
                'pilihan_jawaban4' => isset($input->jawaban[4]) ? $input->jawaban[4] : null,
                'pilihan_jawaban5' => isset($input->jawaban[5]) ? $input->jawaban[5] : null,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );

            session([$input->paket_soal . '_jawaban' . $input->no => isset($input->jawaban) ? $input->jawaban : null]);
        } elseif ($input->id_tipe_soal == 7) {
            $jawaban_benar = 0;
            $jawaban = [];
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_pertanyaan'];
            foreach ($pilihan_jawaban as $jawaban) {
                if (isset($input->jawaban[$jawaban->nomer])) {
                    if ($input->jawaban[$jawaban->nomer] == $jawaban->jawaban) {
                        $jawaban_benar += session($input->paket_soal)['point_pilihan_ganda'];
                        $correct++;
                    }
                }
            }


            $nilai = $jawaban_benar;

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'pilihan_jawaban1' => isset($input->jawaban[1]) ? $input->jawaban[1] : null,
                'pilihan_jawaban2' => isset($input->jawaban[2]) ? $input->jawaban[2] : null,
                'pilihan_jawaban3' => isset($input->jawaban[3]) ? $input->jawaban[3] : null,
                'pilihan_jawaban4' => isset($input->jawaban[4]) ? $input->jawaban[4] : null,
                'pilihan_jawaban5' => isset($input->jawaban[5]) ? $input->jawaban[5] : null,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );
            session([$input->paket_soal . '_jawaban' . $input->no => isset($input->jawaban) ? $input->jawaban : null]);
        } elseif ($input->id_tipe_soal == 8) {
            $jawaban_benar = 0;
            $jawaban = [];
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_pertanyaan'];
            foreach ($pilihan_jawaban as $jawaban) {
                if (isset($input->jawaban[$jawaban->nomer])) {
                    if ($input->jawaban[$jawaban->nomer] == $jawaban->jawaban) {
                        $jawaban_benar += session($input->paket_soal)['point_pilihan_ganda'];
                        $correct++;
                    }
                }
            }
            $nilai = $jawaban_benar;

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
                'correct' => $correct,
                'pilihan_jawaban1' => isset($input->jawaban[1]) ? $input->jawaban[1] : null,
                'pilihan_jawaban2' => isset($input->jawaban[2]) ? $input->jawaban[2] : null,
                'pilihan_jawaban3' => isset($input->jawaban[3]) ? $input->jawaban[3] : null,
                'pilihan_jawaban4' => isset($input->jawaban[4]) ? $input->jawaban[4] : null,
                'pilihan_jawaban5' => isset($input->jawaban[5]) ? $input->jawaban[5] : null,
                'status_koreksi' => 1,
                'id_tipe_soal' => $input->id_tipe_soal,
                'created_at' => Carbon::now('Asia/Jakarta'),
                'created_by' => $input->auth_data->pengguna->id_pengguna,
                'updated_at' => Carbon::now('Asia/Jakarta')
            );
            session([$input->paket_soal . '_jawaban' . $input->no => isset($input->jawaban) ? $input->jawaban : null]);
        }

        ElearningAnswer::dispatch($test_answer);
        unset($test_answer);

        return redirect('siswa#e-learning-soal/list-ujian/test/' . $input->paket_soal . '/' . $input->no);
    }


    public function indexTest2(Request $request, $id_paket_soal = 0, $no = 0)
    {
        $input = (object) $request->input();
        $all_session = session($id_paket_soal);

        if (isset($all_session['bank_soal'][$no])) {
            $detailPaketSoal = $all_session['bank_soal'][$no];
        } else {
            return redirect('siswa/e-learning-soal/list-ujian');
        }

        $allDetailPaketSoal = $all_session['bank_soal'];
        $jawabanTest = session()->has($id_paket_soal . '_jawaban' . $no) ? session($id_paket_soal . '_jawaban' . $no) : null;
        $sisaWaktu =  Carbon::now('Asia/Jakarta')->diffInSeconds($all_session['end_time']);

        $paket_soal = PaketSoal::find($id_paket_soal);

        // if(!isset(session($id_paket_soal)['version'])){
        //     $all_data = session($id_paket_soal);
        //     $all_data['version'] = $paket_soal->version;

        //     session([$id_paket_soal => $all_data]);
        // }

        // if (session($id_paket_soal)['version'] == $paket_soal->version) {
        return view('siswa/e-learning-soal/list-ujian/test-ujian', compact('detailPaketSoal', 'no', 'sisaWaktu', 'jawabanTest', 'allDetailPaketSoal',));
        // } else {
        //     session()->forget($id_paket_soal);
        //     return redirect('siswa/e-learning-soal/list-ujian');
        // }
    }

    public function actionEndTest(Request $request)
    {
        $input = (object) $request->input();
        $test = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test', session($input->paket_soal)['id_test'])->first();
        if ($test) {
            $no = 1;
            session()->forget($test->id_paket_soal);
            while ($no <= 50) {
                session()->has($test->id_paket_soal . '_jawaban' . $no) ? session()->forget($test->id_paket_soal . '_jawaban' . $no) : null;
                $no++;
            }

            $test->status = 1;
            $test->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/list-ujian',
                'message' => 'Berhasil menyelesaikan Soal'
            ];
        } else {
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/list-ujian',
                'message' => 'Berhasil menyelesaikan Soal'
            ];
        }
    }
}
