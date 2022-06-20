<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\KategoriSoal;
use App\Models\PaketSoal;
use App\Models\PilihanSoal;
use App\Models\Soal;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class SoalController extends Controller
{
    public function indexList(Request $request)
    {
        return view('guru/e-learning-soal/soal/view-soal');
    }

    public function indexNew(Request $request, $tipe_soal)
    {
        if ($tipe_soal == "pilihan-ganda") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda');
        } elseif ($tipe_soal == "essay") {
            return view('guru/e-learning-soal/soal/add-soal-essay');
        }
        return view('404');
    }

    public function indexManage(Request $request, $id_soal = 0)
    {
        if ($item = Soal::find($id_soal)) {
            if ($item->id_tipe_soal == 1) {
                $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
                return view('guru/e-learning-soal/soal/edit-soal-pilihan-ganda', compact('item', 'question_options'));
            } else {
                return view('guru/e-learning-soal/soal/edit-soal-essay', compact('item'));
            }
        }

        // if ($item = Soal::find($id_soal)) {
        //     $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
        // } else {
        //     $question_options = null;
        // }
    }


    public function actionDelete(Request $request)
    {
        $input = (object) $request->input();
        if ($soal = DetailPaketSoal::where('id_soal', $input->id_soal)->first()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Gagal dihapus, Soal sudah digunakan'
            ];
        } else {
            $question = Soal::find($input->id_soal);
            $question->delete();
            return [
                // 'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/soal',
                'message' => 'Berhasil Menghapus Soal'
            ];
        }
    }


    public function actionSave(Request $request)
    {
        $input = (object) $request->input();

        if ($input->id_tipe_soal == 1) {
            $validator = Validator::make($request->all(), [
                'soal' => 'required',
                'jawaban_benar' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'soal' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        if (!empty($input->id_soal) && $question = Soal::find($input->id_soal)) {

            $question->content = $input->soal;
            $question->text = $input->soal;
            if ($input->id_tipe_soal == 1) {
                $id_pilihan_soal_benar = 0;
                foreach ($input->jawaban as $no_answer => $answer) {
                    $question_option = PilihanSoal::find($input->id_jawaban[$no_answer]);
                    $question_option->id_soal = $question->id_soal;
                    $question_option->number_option = $no_answer;
                    $question_option->content = $answer;
                    $question_option->text = $answer;
                    if ($input->jawaban_benar == $no_answer) {
                        $question_option->correct = 1;
                        $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                    } else {
                        $question_option->correct = 0;
                    }
                    $question_option->save();
                }
                $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
            }
            $question->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/soal',
                'message' => 'Berhasil Mengubah Soal'
            ];
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $question = new Soal;
            $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
            $question->id_tipe_soal = $input->id_tipe_soal;
            $question->content = $input->soal;
            $question->text = $input->soal;
            $question->save();

            // $true_answer_id = 0;
            if ($input->id_tipe_soal == 1) {
                foreach ($input->jawaban as $no_answer => $answer) {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $question_option = new PilihanSoal;
                    $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question_option->number_option = $no_answer;
                    $question_option->id_soal = $question->id_soal;
                    $question_option->content = $answer;
                    $question_option->text = $answer;
                    if ($input->jawaban_benar == $no_answer) {
                        $question_option->correct = 1;
                    } else {
                        $question_option->correct = 0;
                    }
                    $question_option->save();

                    if ($input->jawaban_benar == $no_answer) {
                        $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                    }
                }
                $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                $question->save();
            }

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/soal',
                'message' => 'Berhasil Menambah Soal'
            ];
        }
    }



    public function indexTest(Request $request, $id_soal = 0)
    {
        $question = Soal::find($id_soal);

        if ($question->id_tipe_soal == 1) {
            $question_options = PilihanSoal::where('id_soal', $question->id_soal)->orderBy('number_option')->get();
            return view('guru/e-learning-soal/soal/test-soal-pilihan-ganda', compact('question', 'question_options'));
        }
        return view('guru/e-learning-soal/soal/test-soal-essay', compact('question'));
    }


    public function commonList(Request $request)
    {
        $list_data = Soal::with('pengguna')->orderBy('created_at', 'DESC')->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_soal
                );
                return $data;
            })
            ->addColumn('tipe_soal', function ($item) {
                if ($item->id_tipe_soal == 1) {
                    return "Pilihan Ganda";
                }
                return "Essay";
            })
            ->make(true);
    }

    public function indexOrder(Request $request, $id_soal = 0)
    {
        $item = Soal::find($id_soal);
        $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
        return view('guru/e-learning-soal/soal/detail-soal', compact('item', 'question_options'));
    }
}
