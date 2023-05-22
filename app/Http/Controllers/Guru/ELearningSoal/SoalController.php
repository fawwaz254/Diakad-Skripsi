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
        $kategori = KategoriSoal::all();
        if ($tipe_soal == "pilihan-ganda") {
            return view('guru/e-learning-soal/soal/add-soal-pilihan-ganda', compact('kategori'));
        } elseif ($tipe_soal == "essay") {
            return view('guru/e-learning-soal/soal/add-soal-essay', compact('kategori'));
        } elseif ($tipe_soal == "submit") {
            return view('guru/e-learning-soal/soal/add-soal-submit', compact('kategori'));
        }
        return view('404');
    }
    public function addKategori(Request $request)
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
            } else {
                return view('guru/e-learning-soal/soal/edit-soal-essay', compact('item', 'kategori'));
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
            $question->text = $input->text;
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
            }
            $question->save();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/soal',
                'message' => 'Berhasil Mengubah Soal'
            ];
        } else {
            DB::beginTransaction();
            try {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                if ($input->id_tipe_soal == 1) {
                    for ($i = 1; $i <= count($input->soal); $i++) {
                        $question = new Soal;
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]);
                        $question->save();
                        if (empty(strip_tags($input->soal[$i]))) {
                            DB::rollback();
                            return [
                                'status' => 300,
                                'message' => 'Eror Ada Kolom yg kosong'
                            ];
                        }
                        foreach ($input->jawaban[$i] as $no_answer => $answer) {
                            $now = Carbon::now(env('APP_TIMEZONE', ''));
                            $question_option = new PilihanSoal;
                            $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                            $question_option->number_option = $no_answer;
                            $question_option->id_soal = $question->id_soal;
                            $question_option->content = $answer;
                            $question_option->text = $answer;
                            if (empty(strip_tags($answer))) {
                                DB::rollback();
                                return [
                                    'status' => 300,
                                    'message' => 'Erorr Ada Kolom yg kosong atau save sekali lagi'
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
                        $question->id_kategori_soal = $input->kategori;
                        $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                        $question->id_tipe_soal = $input->id_tipe_soal;
                        $question->content = $input->soal[$i];
                        $question->text = strip_tags($input->soal[$i]);
                        $question->jawaban = $input->jawaban[$i];
                        $question->save();
                    }
                } else {
                    $question = new Soal;
                    $question->id_kategori_soal = $input->kategori;
                    $question->id_soal =  $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question->id_pengguna = $input->auth_data->pengguna->id_pengguna;
                    $question->id_tipe_soal = $input->id_tipe_soal;
                    $question->content = $input->soal;
                    $question->text = strip_tags($input->soal);
                    // $question->jawaban = $input->jawaban;
                    $question->save();
                }
                DB::commit();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/soal',
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
        $question = Soal::find($id_soal);

        if ($question->id_tipe_soal == 1) {
            $question_options = PilihanSoal::where('id_soal', $question->id_soal)->orderBy('number_option')->get();
            return view('guru/e-learning-soal/soal/test-soal-pilihan-ganda', compact('question', 'question_options'));
        } else if ($question->id_tipe_soal == 2) {
            return view('guru/e-learning-soal/soal/test-soal-essay', compact('question'));
        } else {
            return view('guru/e-learning-soal/soal/test-soal-submit', compact('question'));
        }
    }


    public function commonList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = Soal::with('pengguna', 'kategori_soal')->orderBy('created_at', 'DESC')->when($input->status == 0, function ($q) use ($auth_data) {
            $q->where('id_pengguna', $auth_data->pengguna->id_pengguna);
        });

        return Datatables::of($list_data)
            ->addColumn('time', function ($item) {
                return $item->created_at->diffForHumans();
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_soal
                );
                return $data;
            })
            ->addColumn('tipe_soal', function ($item) {
                if ($item->id_tipe_soal == 1) {
                    return "Pilihan Ganda";
                } else if ($item->id_tipe_soal == 2) {
                    return "Essay";
                }
                return "File";
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
