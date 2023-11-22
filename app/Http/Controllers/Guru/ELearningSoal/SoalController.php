<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\KategoriSoal;
use App\Models\PaketSoal;
use App\Models\PilihanJawaban;
use App\Models\PilihanPertanyaan;
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
        $kategori = KategoriSoal::all();
        if ($tipe_soal == "pilihan-ganda") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda', compact('kategori'));
        } elseif ($tipe_soal == "essay") {
            return view('guru/e-learning-soal/soal/add-soal-essay', compact('kategori'));
        } elseif ($tipe_soal == "submit") {
            return view('guru/e-learning-soal/soal/add-soal-submit', compact('kategori'));
        } elseif ($tipe_soal == "pilihan-ganda-kompleks") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda-kompleks', compact('kategori'));
        } elseif ($tipe_soal == "simple-essay") {
            return view('guru/e-learning-soal/soal/add-soal-simple-essay', compact('kategori'));
        } elseif ($tipe_soal == "match") {
            return view('guru/e-learning-soal/soal/add-soal-match', compact('kategori'));
        } elseif ($tipe_soal == "true-false") {
            return view('guru/e-learning-soal/soal/add-soal-true-false', compact('kategori'));
        }
        return abort(404);
    }

    public function indexNew2(Request $request, $tipe_soal, $id_paket_soal)
    {
        $paket_soal = PaketSoal::find($id_paket_soal);
        if ($tipe_soal == "pilihan-ganda") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda2', compact('paket_soal'));
        } elseif ($tipe_soal == "essay") {
            return view('guru/e-learning-soal/soal/add-soal-essay2', compact('paket_soal'));
        } elseif ($tipe_soal == "submit") {
            return view('guru/e-learning-soal/soal/add-soal-submit2', compact('paket_soal'));
        } elseif ($tipe_soal == "pilihan-ganda-kompleks") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda-kompleks2', compact('paket_soal'));
        } elseif ($tipe_soal == "simple-essay") {
            return view('guru/e-learning-soal/soal/add-soal-simple-essay2', compact('paket_soal'));
        } elseif ($tipe_soal == "match") {
            return view('guru/e-learning-soal/soal/add-soal-match2', compact('paket_soal'));
        } elseif ($tipe_soal == "true-false") {
            return view('guru/e-learning-soal/soal/add-soal-true-false2', compact('paket_soal'));
        } elseif ($tipe_soal == "pilihan-ganda-batch") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda2-batch', compact('paket_soal'));
        }
        return abort(404);
    }

    public function addKategori()
    {
        return view('guru/e-learning-soal/soal/add-kategori-soal');
    }

    public function actionKategori(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'nm_kategori_soal' => 'required|unique:kategori_soal',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300,
                'message' => $validator->errors()->first()
            ];
        }

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $kategori_soal = new KategoriSoal();
        $kategori_soal->id_kategori_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
        $kategori_soal->nm_kategori_soal = $input->nm_kategori_soal;
        $kategori_soal->id_pengguna = $input->auth_data->pengguna->id_pengguna;
        $kategori_soal->save();
        return [
            'status' => 203,
            'message' => 'Berhasil Menambah Kategori Mata Pelajaran'
        ];
    }

    public function uploadImageCkeditor(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
            $request->file('upload')->move(public_path('pages'), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('pages/' . $fileName);
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url')</script>";
            echo $response;
        }
    }

    public function commonListKategori(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $id_pengguna = $auth_data->pengguna->id_pengguna;
        $list_data = KategoriSoal::orderBy('created_at', 'DESC')->with('soal', 'paket_soal', 'pengguna');

        return Datatables::of($list_data)
            ->addColumn('use', function ($item) {
                return $item->soal;
            })
            ->addColumn('action', function ($item) use ($id_pengguna) {

                if ($item->id_pengguna == $id_pengguna) {
                    $is_pengguna = true;
                } else {
                    $is_pengguna = false;
                }

                $data = array(
                    'id' => $item->id_kategori_soal,
                    'is_pengguna' => $is_pengguna,
                );
                return $data;
            })->make(true);
    }

    public function actionDeleteKategori(Request $request)
    {
        $input = (object) $request->input();

        $validasi1 = PaketSoal::where('id_kategori_soal', $input->id_kategori_soal)->first();
        $validasi2 = Soal::where('id_kategori_soal', $input->id_kategori_soal)->first();
        if ($validasi1 || $validasi2) {
            return [
                'message' => 'Kategori Sudah Digunakan'
            ];
        } else {
            $kategori_soal = KategoriSoal::find($input->id_kategori_soal);
            $kategori_soal->deleted_by =  $input->auth_data->pengguna->id_pengguna;
            $kategori_soal->save();
            $kategori_soal->delete();
            return [
                'message' => 'Berhasil Menghapus Kategori'
            ];
        }
    }


    public function indexManage(Request $request, $id_soal = 0)
    {
        $kategori = KategoriSoal::all();
        if ($item = Soal::find($id_soal)) {
            if ($item->id_tipe_soal == 1) {
                $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
                return view('guru/e-learning-soal/soal/edit-soal-pilihan-ganda', compact('item', 'question_options', 'kategori'));
            } else if ($item->id_tipe_soal == 2) {
                return view('guru/e-learning-soal/soal/edit-soal-essay', compact('item', 'kategori'));
            } else if ($item->id_tipe_soal == 3) {
                return view('guru/e-learning-soal/soal/edit-soal-essay', compact('item', 'kategori'));
            } else if ($item->id_tipe_soal == 4) {
                $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
                return view('guru/e-learning-soal/soal/edit-soal-pilihan-ganda-kompleks', compact('item', 'question_options', 'kategori'));
            } else if ($item->id_tipe_soal == 5) {
                return view('guru/e-learning-soal/soal/edit-soal-simple-essay2', compact('item', 'kategori'));
            } else if ($item->id_tipe_soal == 6) {
                $data_pilihan_pertanyaan = PilihanPertanyaan::where('id_soal', $item->id_soal)->orderBy('nomer')->get();
                $data_pilihan_jawaban = PilihanJawaban::where('id_soal', $item->id_soal)->orderBy('nomer')->get();
                return view('guru/e-learning-soal/soal/edit-soal-match2', compact('item', 'data_pilihan_jawaban', 'data_pilihan_pertanyaan', 'kategori'));
            } else if ($item->id_tipe_soal == 7) {
                $question_options = PilihanPertanyaan::where('id_soal', $item->id_soal)->orderBy('nomer')->get();
                return view('guru/e-learning-soal/soal/edit-soal-true-false2', compact('item', 'question_options', 'kategori'));
            }
        }
    }


    public function actionDelete(Request $request)
    {
        $input = (object) $request->input();
        if (DetailPaketSoal::where('id_soal', $input->id_soal)->first()) {
            return [
                'status' => 300, // FAILED
                'message' => 'Gagal dihapus, Soal sudah digunakan'
            ];
        } else {
            $question = Soal::find($input->id_soal);
            $question->deleted_by =  $input->auth_data->pengguna->id_pengguna;
            $question->save();
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
                'jawaban_benar' => 'required',
                'kategori' => 'required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'soal' => 'required',
                'kategori' => 'required'
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
            $question->text = strip_tags($input->soal);
            $question->id_kategori_soal = $input->kategori;
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
            } else if ($input->id_tipe_soal == 5) {
                $question->alternatif_jawaban1 = $input->jawaban[1];
                $question->alternatif_jawaban2 = $input->jawaban[2];
                $question->alternatif_jawaban3 = $input->jawaban[3];
                $question->alternatif_jawaban4 = $input->jawaban[4];
                $question->alternatif_jawaban5 = $input->jawaban[5];
            } else if ($input->id_tipe_soal == 6) {
                foreach ($input->id_pilihan_pertanyaan as $no_array => $id_pilihan_pertanyaan) {
                    $pilihan_pertanyaan = PilihanPertanyaan::find($id_pilihan_pertanyaan);
                    $pilihan_pertanyaan->text = $input->pertanyaan_text[$no_array];
                    $pilihan_pertanyaan->jawaban = $input->pertanyaan_jawaban[$no_array];
                    $pilihan_pertanyaan->save();
                }

                foreach ($input->id_pilihan_jawaban as $no_array => $id_pilihan_jawaban) {
                    $pilihan_jawaban = PilihanJawaban::find($id_pilihan_jawaban);
                    $pilihan_jawaban->text = $input->jawaban_text[$no_array];
                    $pilihan_jawaban->save();
                }
            } else if ($input->id_tipe_soal == 7) {
                foreach ($input->pertanyaan as $no_pertanyaan => $pertanyaan) {
                    $question_option = PilihanPertanyaan::find($input->id_pilihan_pertanyaan[$no_pertanyaan]);
                    $question_option->text = $pertanyaan;
                    $question_option->jawaban = $input->noJawaban[$no_pertanyaan];
                    $question_option->save();
                }
            }
            $question->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal/bank-soal',
                'message' => 'Berhasil Mengubah Soal'
            ];
        } else {
            DB::beginTransaction();
            try {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                if ($input->id_tipe_soal == 1) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        //validasi ketika ada data yg sama
                        // if (Soal::where(['id_kategori_soal' => $input->kategori, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';
                        $question->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $question->save();
                        foreach ($input->jawaban[$i] as $no_answer => $answer) {
                            $now = Carbon::now(env('APP_TIMEZONE', ''));
                            $question_option = new PilihanSoal;
                            $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question_option->number_option = $no_answer;
                            $question_option->id_soal = $question->id_soal;
                            $question_option->content = $answer;
                            $question_option->text = strip_tags($answer) ? strip_tags($answer) : 'gambar';
                            $question_option->created_by = $input->auth_data->pengguna->id_pengguna;
                            if (empty($answer)) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                ];
                            }
                            if ($input->jawaban_benar[$i] == $no_answer) {
                                $question_option->correct = 1;
                            } else {
                                $question_option->correct = 0;
                            }
                            $question_option->save();

                            if ($input->jawaban_benar[$i] == $no_answer) {
                                $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                            }
                        }
                        $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                        $question->save();
                        // }
                    }
                } else if ($input->id_tipe_soal == 2) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        // if (Soal::where(['id_kategori_soal' => $input->kategori, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ?  strip_tags($input->soal[$i]) : 'gambar';
                        $question->jawaban = $input->jawaban[$i];
                        $question->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $question->save();
                        // }
                    }
                } else if ($input->id_tipe_soal == 3) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->kategori;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ? strip_tags($input->soal) : 'gambar';
                    $question->created_by = $input->auth_data->pengguna->id_pengguna;
                    // $question->jawaban = $input->jawaban;
                    if (empty($input->soal)) {
                        DB::rollback();
                        return [
                            'status' => 300,
                            'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                        ];
                    }
                    $question->save();
                } else if ($input->id_tipe_soal == 4) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        //validasi ketika ada data yg sama
                        // if (Soal::where(['id_kategori_soal' => $input->kategori, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';
                        $question->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $question->save();
                        foreach ($input->jawaban[$i] as $no_answer => $answer) {
                            $now = Carbon::now(env('APP_TIMEZONE', ''));
                            $question_option = new PilihanSoal;
                            $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question_option->number_option = $no_answer;
                            $question_option->id_soal = $question->id_soal;
                            $question_option->content = $answer;
                            $question_option->text = strip_tags($answer) ? strip_tags($answer) : 'gambar';
                            $question_option->created_by = $input->auth_data->pengguna->id_pengguna;
                            if (empty($answer)) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                ];
                            }
                            if (in_array($no_answer, $input->jawaban_benar[$i])) {
                                $question_option->correct = 1;
                            } else {
                                $question_option->correct = 0;
                            }
                            $question_option->save();

                            // if ($input->jawaban_benar[$i] == $no_answer) {
                            //     $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                            // }
                        }
                        // $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                        $question->save();
                        // }
                    }
                } else if ($input->id_tipe_soal == 5) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        // if (Soal::where(['id_kategori_soal' => $input->kategori, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';
                        $question->alternatif_jawaban1 = $input->jawaban[1];
                        $question->alternatif_jawaban2 = $input->jawaban[2];
                        $question->alternatif_jawaban3 = $input->jawaban[3];
                        $question->alternatif_jawaban4 = $input->jawaban[4];
                        $question->alternatif_jawaban5 = $input->jawaban[5];
                        $question->created_by = $input->auth_data->pengguna->id_pengguna;

                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }

                        $question->save();
                        // }
                    }
                } else if ($input->id_tipe_soal == 6) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->kategori;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ? strip_tags($input->soal) : 'gambar';
                    $question->created_by = $input->auth_data->pengguna->id_pengguna;
                    if (empty($input->soal)) {
                        DB::rollback();
                        return [
                            'status' => 300,
                            'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                        ];
                    }
                    $question->save();
                    for ($i = 1; $i <= count($input->pertanyaan); $i++) {
                        $pertanyaan = new PilihanPertanyaan;
                        $pertanyaan->id_pilihan_pertanyaan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $pertanyaan->id_soal =  $question->id_soal;
                        $pertanyaan->nomer = $i;
                        $pertanyaan->text = $input->pertanyaan[$i];
                        $pertanyaan->jawaban = $input->noJawaban[$i];
                        $pertanyaan->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->pertanyaan[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $pertanyaan->save();
                    }

                    for ($i = 1; $i <= count($input->jawaban); $i++) {
                        $jawaban = new PilihanJawaban;
                        $jawaban->id_pilihan_jawaban = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $jawaban->id_soal =  $question->id_soal;
                        $jawaban->nomer = $i;
                        $jawaban->text = $input->jawaban[$i];
                        $jawaban->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->jawaban[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }

                        $jawaban->save();
                    }
                } else if ($input->id_tipe_soal == 7) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->kategori;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ? strip_tags($input->soal) : 'gambar';
                    $question->created_by = $input->auth_data->pengguna->id_pengguna;
                    $question->save();

                    for ($i = 1; $i <= count($input->pertanyaan); $i++) {
                        $pertanyaan = new PilihanPertanyaan;
                        $pertanyaan->id_pilihan_pertanyaan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $pertanyaan->id_soal =  $question->id_soal;
                        $pertanyaan->nomer = $i;
                        $pertanyaan->text = $input->pertanyaan[$i];
                        $pertanyaan->jawaban = $input->noJawaban[$i];
                        $pertanyaan->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->pertanyaan[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $pertanyaan->save();
                    }
                }
                DB::commit();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/paket-soal/bank-soal',
                    'message' => 'Berhasil Menambah Soal'
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return [
                    'status' => 300,
                    'message' => $e->getMessage()
                ];
            }
        }
    }

    public function actionSave2(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();

        if ($input->id_tipe_soal == 1) {
            $validator = Validator::make($request->all(), [
                'soal' => 'required',
                'jawaban_benar' => 'required',
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
            $question->text = strip_tags($input->soal);
            $question->id_kategori_soal = $input->id_kategori_soal;
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
            } else if ($input->id_tipe_soal == 5) {
                $question->alternatif_jawaban1 = $input->jawaban[1];
                $question->alternatif_jawaban2 = $input->jawaban[2];
                $question->alternatif_jawaban3 = $input->jawaban[3];
                $question->alternatif_jawaban4 = $input->jawaban[4];
                $question->alternatif_jawaban5 = $input->jawaban[5];
            }
            $question->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal/bank-soal',
                'message' => 'Berhasil Mengubah Soal'
            ];
        } else {
            DB::beginTransaction();
            try {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                if ($input->id_tipe_soal == 1) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->id_kategori_soal;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';

                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }

                        $question->save();

                        $detail_paket_soal = new DetailPaketSoal;
                        $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                        $detail_paket_soal->id_soal = $question->id_soal;
                        $detail_paket_soal->save();


                        foreach ($input->jawaban[$i] as $no_answer => $answer) {
                            $now = Carbon::now(env('APP_TIMEZONE', ''));
                            $question_option = new PilihanSoal;
                            $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question_option->number_option = $no_answer;
                            $question_option->id_soal = $question->id_soal;
                            $question_option->content = $answer;
                            $question_option->text = strip_tags($answer) ?  strip_tags($answer) : 'gambar';
                            if (empty($answer)) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                ];
                            }
                            if ($input->jawaban_benar[$i] == $no_answer) {
                                $question_option->correct = 1;
                            } else {
                                $question_option->correct = 0;
                            }
                            $question_option->save();

                            if ($input->jawaban_benar[$i] == $no_answer) {
                                $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                            }
                        }
                        $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                        $question->save();
                    }
                } else if ($input->id_tipe_soal == 2) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->id_kategori_soal;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';
                        $question->jawaban = $input->jawaban[$i];

                        if (empty($input->soal[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $question->save();
                        $detail_paket_soal = new DetailPaketSoal;
                        $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                        $detail_paket_soal->id_soal = $question->id_soal;
                        $detail_paket_soal->save();
                    }
                } else if ($input->id_tipe_soal == 3) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->id_kategori_soal;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ?  strip_tags($input->soal) : 'gambar';
                    if (empty($input->soal)) {
                        DB::rollback();
                        return [
                            'status' => 300,
                            'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                        ];
                    }
                    // $question->jawaban = $input->jawaban;
                    $question->save();

                    $detail_paket_soal = new DetailPaketSoal;
                    $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                    $detail_paket_soal->id_soal = $question->id_soal;
                    $detail_paket_soal->save();
                } else if ($input->id_tipe_soal == 4) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        //validasi ketika ada data yg sama
                        if (Soal::where(['id_kategori_soal' => $input->id_kategori_soal, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                            $question = new Soal;
                            $question->id_kategori_soal = $input->id_kategori_soal;
                            $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                            $question->id_tipe_soal = $input->id_tipe_soal;
                            $question->content = $input->soal[$i];
                            $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i]) : 'gambar';
                            $question->created_by = $input->auth_data->pengguna->id_pengguna;

                            if (empty($input->soal[$i])) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                ];
                            }
                            $question->save();
                            foreach ($input->jawaban[$i] as $no_answer => $answer) {
                                $now = Carbon::now(env('APP_TIMEZONE', ''));
                                $question_option = new PilihanSoal;
                                $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                                $question_option->number_option = $no_answer;
                                $question_option->id_soal = $question->id_soal;
                                $question_option->content = $answer;
                                $question_option->text = strip_tags($answer) ? strip_tags($answer) : 'gambar';
                                $question_option->created_by = $input->auth_data->pengguna->id_pengguna;
                                if (empty($answer)) {
                                    DB::rollback();
                                    return [
                                        'status' => 300,
                                        'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                    ];
                                }
                                if (in_array($no_answer, $input->jawaban_benar[$i])) {
                                    $question_option->correct = 1;
                                } else {
                                    $question_option->correct = 0;
                                }
                                $question_option->save();

                                // if ($input->jawaban_benar[$i] == $no_answer) {
                                //     $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                                // }
                            }
                            // $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                            $question->save();
                            $detail_paket_soal = new DetailPaketSoal;
                            $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                            $detail_paket_soal->id_soal = $question->id_soal;
                            $detail_paket_soal->save();
                        }
                    }
                } else if ($input->id_tipe_soal == 5) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        if (Soal::where(['id_kategori_soal' => $input->id_kategori_soal, 'id_pengguna' => $input->auth_data->pengguna->id_pengguna, 'id_tipe_soal' => $input->id_tipe_soal, 'text' => strip_tags($input->soal[$i])])->first()) { } else {
                            $question = new Soal;
                            $question->id_kategori_soal = $input->id_kategori_soal;
                            $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                            $question->id_tipe_soal = $input->id_tipe_soal;
                            $question->content = $input->soal[$i];
                            $question->text = strip_tags($input->soal[$i]) ? strip_tags($input->soal[$i])  : 'gambar';
                            $question->alternatif_jawaban1 = $input->jawaban[1];
                            $question->alternatif_jawaban2 = $input->jawaban[2];
                            $question->alternatif_jawaban3 = $input->jawaban[3];
                            $question->alternatif_jawaban4 = $input->jawaban[4];
                            $question->alternatif_jawaban5 = $input->jawaban[5];
                            $question->created_by = $input->auth_data->pengguna->id_pengguna;

                            if (empty($input->soal[$i])) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                                ];
                            }
                            $question->save();
                            $detail_paket_soal = new DetailPaketSoal;
                            $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                            $detail_paket_soal->id_soal = $question->id_soal;
                            $detail_paket_soal->save();
                        }
                    }
                } else if ($input->id_tipe_soal == 6) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->id_kategori_soal;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ? strip_tags($input->soal) : 'gambar';
                    $question->created_by = $input->auth_data->pengguna->id_pengguna;
                    if (empty($input->soal)) {
                        DB::rollback();
                        return [
                            'status' => 300,
                            'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                        ];
                    }
                    $question->save();

                    for ($i = 1; $i <= count($input->pertanyaan); $i++) {
                        $pertanyaan = new PilihanPertanyaan;
                        $pertanyaan->id_pilihan_pertanyaan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $pertanyaan->id_soal =  $question->id_soal;
                        $pertanyaan->nomer = $i;
                        $pertanyaan->text = $input->pertanyaan[$i];
                        $pertanyaan->jawaban = $input->noJawaban[$i];
                        $pertanyaan->created_by = $input->auth_data->pengguna->id_pengguna;

                        if (empty($input->pertanyaan[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $pertanyaan->save();
                    }

                    for ($i = 1; $i <= count($input->jawaban); $i++) {
                        $jawaban = new PilihanJawaban;
                        $jawaban->id_pilihan_jawaban = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $jawaban->id_soal =  $question->id_soal;
                        $jawaban->nomer = $i;
                        $jawaban->text = $input->jawaban[$i];
                        $jawaban->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->jawaban[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses upload'
                            ];
                        }
                        $jawaban->save();
                    }

                    $detail_paket_soal = new DetailPaketSoal;
                    $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                    $detail_paket_soal->id_soal = $question->id_soal;
                    $detail_paket_soal->save();
                } else if ($input->id_tipe_soal == 7) {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->id_kategori_soal;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal) ? strip_tags($input->soal) : 'gambar';
                    $question->created_by = $input->auth_data->pengguna->id_pengguna;
                    if (empty($input->soal)) {
                        DB::rollback();
                        return [
                            'status' => 300,
                            'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses uploadi'
                        ];
                    }
                    $question->save();
                    for ($i = 1; $i <= count($input->pertanyaan); $i++) {
                        $pertanyaan = new PilihanPertanyaan;
                        $pertanyaan->id_pilihan_pertanyaan = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $pertanyaan->id_soal =  $question->id_soal;
                        $pertanyaan->nomer = $i;
                        $pertanyaan->text = $input->pertanyaan[$i];
                        $pertanyaan->jawaban = $input->noJawaban[$i];
                        $pertanyaan->created_by = $input->auth_data->pengguna->id_pengguna;
                        if (empty($input->pertanyaan[$i])) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi karena masih proses uploadi'
                            ];
                        }

                        $pertanyaan->save();
                    }

                    $detail_paket_soal = new DetailPaketSoal;
                    $detail_paket_soal->id_detail_paket_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $detail_paket_soal->id_paket_soal = $input->id_paket_soal;
                    $detail_paket_soal->id_soal = $question->id_soal;
                    $detail_paket_soal->save();
                }

                DB::commit();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/paket-soal/detail/' . $input->id_paket_soal,
                    'message' => 'Berhasil Menambah Soal'
                ];
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return [
                    'status' => 300,
                    'message' => $e->getMessage()
                ];
            }
        }
    }


    public function indexTest(Request $request, $id_soal = 0)
    {
        $question = Soal::where('id_soal', $id_soal)->with('pilihan_soal', 'pilihan_pertanyaan', 'pilihan_jawaban')->first();

        if ($question->id_tipe_soal == 1) {
            return view('guru/e-learning-soal/soal/test-soal-pilihan-ganda', compact('question'));
        } else if ($question->id_tipe_soal == 2) {
            return view('guru/e-learning-soal/soal/test-soal-essay', compact('question'));
        } else if ($question->id_tipe_soal == 3) {
            return view('guru/e-learning-soal/soal/test-soal-submit', compact('question'));
        } else if ($question->id_tipe_soal == 4) {
            return view('guru/e-learning-soal/soal/test-soal-pilihan-ganda-kompleks', compact('question'));
        } else if ($question->id_tipe_soal == 5) {
            return view('guru/e-learning-soal/soal/test-soal-simple-essay', compact('question'));
        } else if ($question->id_tipe_soal == 6) {
            return view('guru/e-learning-soal/soal/test-soal-match', compact('question'));
        } else if ($question->id_tipe_soal == 7) {
            return view('guru/e-learning-soal/soal/test-soal-true-false', compact('question'));
        }
    }


    public function commonList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Soal::with('pengguna', 'kategori_soal', 'detail_paket_soal.paket_soal.paket_soal_kelas.kelas')->orderBy('created_at', 'DESC')->when($input->status == 0, function ($q) use ($auth_data) {
            $q->where('soal.id_pengguna', $auth_data->pengguna->id_pengguna);
        });

        return Datatables::of($list_data)
            ->addColumn('time', function ($item) {
                return $item->created_at->diffForHumans();
            })->addColumn('gambar', function ($item) {
                $gambar = false;
                $text = $item->text;
                if ($item->text == 'gambar') {
                    $pattern = '/<img[^>]+src=["\'](https:\/\/[^"\']+)["\']/';
                    preg_match($pattern, $item->content, $matches);
                    if (isset($matches[1])) {
                        $imgSrc = $matches[1];
                        $text = $imgSrc;
                        $gambar = true;
                    }
                }

                $data = array(
                    'gambar' => $gambar,
                    'text' => $text,
                );
                return $data;
            })

            ->addColumn('kelas', function ($item) {
                $nm_kelas = [];
                if ($item->detail_paket_soal) {
                    foreach ($item->detail_paket_soal as $detail_paket_soal) {
                        if ($detail_paket_soal->paket_soal) {
                            foreach ($detail_paket_soal->paket_soal->paket_soal_kelas as $paket_soal_kelas) {
                                $nm_kelas[] = $paket_soal_kelas->kelas->nm_kelas;
                            }
                        }
                    }
                }
                return $nm_kelas;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_soal,
                    'delete' => count($item->detail_paket_soal) == '0' ? true : false,
                );
                return $data;
            })
            ->addColumn('tipe_soal', function ($item) {
                if ($item->id_tipe_soal == 1) {
                    return "Pilihan Ganda";
                } else if ($item->id_tipe_soal == 2) {
                    return "Isian";
                } else if ($item->id_tipe_soal == 3) {
                    return "File";
                } else if ($item->id_tipe_soal == 4) {
                    return "Pilihan Ganda Kompleks";
                } else if ($item->id_tipe_soal == 5) {
                    return "Isian Singkat";
                } else if ($item->id_tipe_soal == 6) {
                    return "Menjodohkan";
                } else if ($item->id_tipe_soal == 7) {
                    return "True/False";
                }
                return $item->tipe_soal_to_text();
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
