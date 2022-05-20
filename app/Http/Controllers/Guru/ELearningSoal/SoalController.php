<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;
use App\Models\PilihanSoal;
use App\Models\Soal;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class SoalController extends Controller
{
    public function indexList(Request $request){
        return view('guru/e-learning-soal/soal/view-soal');
    }

    public function indexNew(Request $request, $account_id = 0, $event_id = 0){
        $question_categories = KategoriSoal::get();
        return view('guru/e-learning-soal/soal/add-soal', compact('question_categories'));
    }

    public function indexManage(Request $request, $id_soal = 0){
        $question_categories = KategoriSoal::get();
        if($item = Soal::find($id_soal)){
            $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
        }else{
            $question_options = null;
        }
        return view('guru/e-learning-soal/soal/edit-soal', compact('item', 'question_categories', 'question_options'));
    }


    public function actionDelete(Request $request){
        $input = (object) $request->input();
        // dd( );
        if($question = Soal::find($input->id_soal)){
            
    //  dd($question);
            $question->delete();

            return [
                // 'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/soal',
                'message' => 'Berhasil Menghapus Soal'
            ];
        
        }
        // else{
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'Error'
        //     ]);
        // }   
    }


    public function actionSave(Request $request){
        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'soal' => 'required',
            'jawaban_benar' => 'required'
        ]);

        if($validator->fails()) {
            // return back()->with('toast', $validator->errors()->first());
        }

        $input = (object) $request->input();

        if(!empty($input->id_soal) && $question = Soal::find($input->id_soal)){
            // DB::beginTransaction();
            // try {
            //     $bom = '\xEF\xBB\xBF';
                // dd($input);
                $question->id_kategori_soal = $input->kategori;
                $question->content = $input->soal;
                $question->text = $input->soal;
                $question->save();
                
                $id_pilihan_soal_benar = 0;
                foreach($input->jawaban as $no_answer => $answer){
                    $question_option = PilihanSoal::find($input->id_jawaban[$no_answer]);
                    $question_option->id_soal = $question->id_soal;
                    $question_option->number_option = $no_answer;
                    $question_option->content = $answer;
                    $question_option->text = $answer;
                    if($input->jawaban_benar == $no_answer){
                        $question_option->correct = 1;
                        $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                    }else{
                        $question_option->correct = 0;
                    }
                    $question_option->save();
                }
                
                $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                $question->save();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/soal',
                    'message' => 'Berhasil Menambah Soal'
                ];
            //     DB::commit();

            //     return back()->with('toast', 'Your changed save successfully');
            // } catch (\Exception $e) {
            //     DB::rollback();

            //     return back()->with('toast', 'Your changed save failed');
            // }
        }else{
            // DB::beginTransaction();
            // try {
            //     // $bom = '\xEF\xBB\xBF';
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $question = new Soal;
                $question->id_soal =  $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $question->id_kategori_soal = $input->kategori;
                $question->content = $input->soal;
                $question->text = $input->soal;
               
                $question->save();
                
                $true_answer_id = 0;
                foreach($input->jawaban as $no_answer => $answer){
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $question_option = new PilihanSoal;
                    $question_option->id_pilihan_soal = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                    $question_option->number_option = $no_answer;
                    $question_option->id_soal = $question->id_soal;
                    $question_option->content = $answer;
                    $question_option->text =$answer;
                    if($input->jawaban_benar == $no_answer){
                        $question_option->correct = 1;
                    }else{
                        $question_option->correct = 0;
                    }
                    // dd($question_option );
                    $question_option->save();

                    if($input->jawaban_benar == $no_answer){
                        $id_pilihan_soal_benar = $question_option->id_pilihan_soal;
                    }
                }
                
                $question->id_pilihan_soal_benar = $id_pilihan_soal_benar;
                $question->save();
                
                // DB::commit();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/soal',
                    'message' => 'Berhasil Menambah Soal'
                ];
                
                // return redirect('organizer/question')->with('toast', 'Your changed save successfully');
            // } catch (\Exception $e) {
            //     DB::rollback();

                // return [
                //     'status' => 300, // FAILED
                //     'message' => $validator->errors()->first()
                // ];
            }
        }



        public function indexTest(Request $request, $id_soal = 0){
            $question = Soal::find($id_soal);
            $question_options = PilihanSoal::where('id_soal', $question->id_soal)->orderBy('number_option')->get();
            
            return view('guru/e-learning-soal/soal/test-soal', compact('question', 'question_options'));
        }


        public function commonList(Request $request){
            $list_data = Soal::with('kategori_soal')->orderBy('soal.created_at', 'desc');
    
            return Datatables::of($list_data)
                    ->addColumn('action', function($item){
                        $data = array(
                            'id' => $item->id_soal
                        );
                        return $data;
                    })
                    ->make(true);
        }

        public function indexOrder(Request $request, $id_soal = 0){
            $item = Soal::find($id_soal);
            $question_options = PilihanSoal::where('id_soal', $item->id_soal)->orderBy('number_option')->get();
            return view('guru/e-learning-soal/soal/detail-soal', compact('item', 'question_options'));
        }
    }
// }
