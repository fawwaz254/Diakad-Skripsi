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
            ->editColumn('waktu_pengerjaan', '{{$waktu_pengerjaan}} Menit')
            ->addColumn('total_answer', function ($item) {
                $value = 0;
                foreach ($item->detail_paket_soal as $data) {
                    $value += $data->soal->pilihan_soal->count();
                }
                return $value;
            })->addColumn('status', function ($item) use ($statusTests, $waktu) {
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
        if ($test = Test::where('id_pengguna', $account)->where('id_paket_soal', $id_paket_soal)->first() && session()->has($id_paket_soal)) {
            return redirect('siswa/e-learning-soal/list-ujian/test/' . $id_paket_soal . '/1');
        } else {
            $soal = PaketSoal::find($id_paket_soal);
            $test_duration = $soal->waktu_pengerjaan;
            $start_time = Carbon::now('Asia/Jakarta');
            $end_time = Carbon::now('Asia/Jakarta')->addMinutes($test_duration);

            $question_number = 1;
            $urutan_soal = array();
            $question_package_details = DetailPaketSoal::with('soal.pilihan_soal')->where('id_paket_soal', $id_paket_soal)->inRandomOrder()->get();
            foreach ($question_package_details as $question_package_detail) {
                $urutan_soal[$question_number] = $question_package_detail;
                $question_number++;
            }

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            if ($test) { } else {
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

            $all_data = array();
            $all_data['bank_soal']  =  $urutan_soal;
            $all_data['start_time'] =  $start_time;
            $all_data['end_time']   =  $end_time;
            $all_data['id_test']    =  $test->id_test;
            $all_data['point_pilihan_ganda']    =  $soal->nilai;

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
        } else {
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
        }

        ElearningAnswer::dispatch($test_answer);
        unset($test_answer);

        return redirect('siswa#e-learning-soal/list-ujian/test/' . $input->paket_soal . '/' . $input->no);
    }


    public function indexTest2(Request $request, $id_paket_soal = 0, $no = 0)
    {
        $all_session = session($id_paket_soal);

        if (isset($all_session['bank_soal'][$no])) {
            $detailPaketSoal = $all_session['bank_soal'][$no];
        } else {
            return redirect('siswa/e-learning-soal/list-ujian');
        }

        $allDetailPaketSoal = $all_session['bank_soal'];
        $jawabanTest = session()->has($id_paket_soal . '_jawaban' . $no) ? session($id_paket_soal . '_jawaban' . $no) : null;
        $sisaWaktu =  Carbon::now('Asia/Jakarta')->diffInSeconds($all_session['end_time']);
        $start_date = Carbon::createFromFormat('Y-m-d H:i:s', $all_session['start_time']);
        $end_date = Carbon::createFromFormat('Y-m-d H:i:s',  $all_session['end_time']);
        $waktu = Carbon::now('Asia/Jakarta');

        if (strtotime($start_date) < strtotime($waktu) && strtotime($end_date) > strtotime($waktu)) {
            return view('siswa/e-learning-soal/list-ujian/test-ujian', compact('detailPaketSoal', 'no', 'sisaWaktu', 'jawabanTest', 'allDetailPaketSoal',));
        } else {
            $input = (object) $request->input();
            $test = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test', session($id_paket_soal)['id_test'])->first();
            $test->status = 1;
            $test->save();
            return redirect('siswa/e-learning-soal/list-ujian');
        }
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
