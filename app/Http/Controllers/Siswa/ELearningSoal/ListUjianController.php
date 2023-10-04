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
            'kelas',
            'detail_paket_soal',
            'detail_paket_soal.soal',
            'kategori_soal',
        )->whereHas('paket_soal_kelas', function ($query) use ($id_kelas) {
            $query->where('id_kelas', '=', $id_kelas);
        })->orderBy('paket_soal.created_at', 'desc')->with(['detail_paket_soal.soal.pilihan_soal' => function ($q) {
            return
                $q->whereNotNull('content');
        }]);

        $waktu = Carbon::now('Asia/Jakarta');
        $statusTests = Test::where('id_pengguna', $auth_data->pengguna->id_pengguna)->get();

        return Datatables::of($list_data)
            ->addColumn('total_question', function ($item) {
                return $item->detail_paket_soal->count();
            })
            // ->editColumn('nilai', function ($item) {
            //     if ($item->nilai == '0') {
            //         return intval(100 / $item->detail_paket_soal->count());
            //     } else {
            //         return $item->nilai;
            //     }
            // })
            ->editColumn('waktu_pengerjaan', '{{$waktu_pengerjaan}} Menit')
            // ->addColumn('total_answer', function ($item) {
            //     $value = 0;
            //     foreach ($item->detail_paket_soal as $data) {
            //         $value += $data->soal->pilihan_soal->count();
            //     }
            //     return $value;
            // })
            ->addColumn('status', function ($item) use ($statusTests, $waktu) {
                $statusTest = $statusTests->where('id_paket_soal', $item->id_paket_soal)->first();
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_mulai);
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_selesai);

                if (!$statusTest && strtotime($waktu) > strtotime($end_date)) {
                    $status = "Waktu Berakhir";
                } else {
                    if (strtotime($start_date) > strtotime($waktu) || $item->detail_paket_soal->count() == 0) {
                        $status = "Test Belum dimulai";
                    } else {
                        if ($statusTest) {
                            if ($statusTest->status == 1) {
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
            ->addColumn('action', function ($item) use ($statusTests, $waktu) {
                $statusTest = $statusTests->where('id_paket_soal', $item->id_paket_soal)->first();
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_mulai);
                $end_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->waktu_selesai);

                if (!$statusTest && strtotime($waktu) > strtotime($end_date)) {
                    $status = "98";
                } else {
                    if (strtotime($start_date) > strtotime($waktu) || $item->detail_paket_soal->count() == 0) {
                        $status = "99";
                    } else {
                        if ($statusTest) {
                            if ($statusTest->status == 1) {
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
        $now = Carbon::now(env('APP_TIMEZONE', ''));
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
                        }
                    }
                }
            }


            $point = $soal->nilai;
            if ($point == '0') {
                $point =  intval(100 / $question_package_details->count());
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
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $test_answer = array();
        $nilai = 0;
        if ($input->id_tipe_soal == 1) {
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_soal']->where('id_pilihan_soal', $input->question_option)->first();
            if ($pilihan_jawaban->correct == 1) {
                $nilai = session($input->paket_soal)['point_pilihan_ganda'];
            }

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
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
            $jawaban_benar = 0;
            $jawaban = [];
            if(isset($input->question_option)){
                foreach ($input->question_option as $question_option) {
                    $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_soal']->where('id_pilihan_soal', $question_option)->first();
                    if ($pilihan_jawaban->correct == 1) {
                        $jawaban_benar++;
                    } else {
                        $jawaban_benar--;
                    }
                    $jawaban[] = $question_option;
                }
    
                if ($jawaban_benar < 0) {
                    $jawaban_benar = 0;
                }
            }

            $nilai = (session($input->paket_soal)['point_pilihan_ganda'] * $jawaban_benar) / 5;

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
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
                if(isset($input->jawaban[$jawaban->nomer])){
                    if ($input->jawaban[$jawaban->nomer] == $jawaban->jawaban) {
                        $jawaban_benar++;
                    } else {
                        $jawaban_benar--;
                    }
                }
            }

            if ($jawaban_benar < 0) {
                $jawaban_benar = 0;
            }

            $nilai = (session($input->paket_soal)['point_pilihan_ganda'] * $jawaban_benar) / $pilihan_jawaban->count();

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
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

            session([$input->paket_soal . '_jawaban' . $input->no => $input->jawaban]);
        } elseif ($input->id_tipe_soal == 7) {
            $jawaban_benar = 0;
            $jawaban = [];
            $pilihan_jawaban = session($input->paket_soal)['bank_soal'][$input->no]['soal']['pilihan_pertanyaan'];
            foreach ($pilihan_jawaban as $jawaban) {
                if(isset($input->jawaban[$jawaban->nomer])){
                    if ($input->jawaban[$jawaban->nomer] == $jawaban->jawaban) {
                        $jawaban_benar++;
                    } else {
                        $jawaban_benar--;
                    }
                }
            }

            if ($jawaban_benar < 0) {
                $jawaban_benar = 0;
            }

            $nilai = (session($input->paket_soal)['point_pilihan_ganda'] * $jawaban_benar) / $pilihan_jawaban->count();

            $test_answer = array(
                'id_jawaban_test' => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_pengguna' => $input->auth_data->pengguna->id_pengguna,
                'id_test' => session($input->paket_soal)['id_test'],
                'nomer' => $input->no,
                'id_soal' => $input->question,
                'nilai' => $nilai,
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

            session([$input->paket_soal . '_jawaban' . $input->no => $input->jawaban]);
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
        // $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $all_session['start_time']);
        // $end_date = Carbon::createFromFormat('Y-m-d H:i:s',  $all_session['end_time']);
        // $waktu = Carbon::now('Asia/Jakarta');

        // if (strtotime($start_date) < strtotime($waktu) && strtotime($end_date) > strtotime($waktu)) {
        // if ($waktu > $end_date) {
        //     $test = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test', session($id_paket_soal)['id_test'])->first();
        //     if ($test) {
        //         $test->status = 1;
        //         $test->save();
        //         return redirect('siswa/e-learning-soal/list-ujian');
        //     } else {

        $paket_soal = PaketSoal::find($id_paket_soal);


        if (session($id_paket_soal)['version'] == $paket_soal->version) {
            return view('siswa/e-learning-soal/list-ujian/test-ujian', compact('detailPaketSoal', 'no', 'sisaWaktu', 'jawabanTest', 'allDetailPaketSoal',));
        } else {
            session()->forget($id_paket_soal);
            return redirect('siswa/e-learning-soal/list-ujian');
            // return view('siswa/e-learning-soal/list-ujian/view-list-ujian');
            // return redirect('siswa');
        }

        // }
        // }

        // } else {
        //     $input = (object) $request->input();
        //     $test = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test', session($id_paket_soal)['id_test'])->first();
        //     if ($test) {
        //         $test->status = 1;
        //         $test->save();
        //     }

        //     return redirect('siswa/e-learning-soal/list-ujian');
        // }
    }

    public function actionEndTest(Request $request)
    {
        $input = (object) $request->input();
        $test = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test', session($input->paket_soal)['id_test'])->first();
        $test->status = 1;
        $test->save();
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'e-learning-soal/list-ujian',
            'message' => 'Berhasil menyelesaikan Soal'
        ];
    }
}
