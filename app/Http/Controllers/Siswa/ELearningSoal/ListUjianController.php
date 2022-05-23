<?php

namespace App\Http\Controllers\Siswa\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\JawabanTest;
use App\Models\Kelas;
use App\Models\PaketSoal;
use App\Models\PilihanSoal;
use App\Models\Soal;
use App\Models\Test;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;


class ListUjianController extends Controller
{
    public function indexList(Request $request){
        return view('siswa/e-learning-soal/list-ujian/view-list-ujian');
    }

    public function commonList(Request $request){
        $list_data = PaketSoal::with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal')->with(['detail_paket_soal.soal.pilihan_soal' => function($q){ return $q->whereNotNull('content'); }]);
// dd($list_data);
        return Datatables::of($list_data)
                ->addColumn('total_question', function($item){
// dd($item->detail_paket_soal->count());
                    return $item->detail_paket_soal->count();
                })
                ->addColumn('total_answer', function($item){
                    $value = 0;
                    foreach($item->detail_paket_soal as $data){
                        $value += $data->soal->pilihan_soal->count();
                    }
                    return $value;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_paket_soal
                    );
                    return $data;
                })
                ->make(true);
    }

    // public function indexTest(Request $request, $id_soal = 0){
    //     $paket_soal =  PaketSoal::where('id_paket_soal',$id_soal)->with('detail_paket_soal')->first();
    //         //  $question = Soal::find();
    //         //  dd($paket_soal->detail_paket_soal);
    //        // $question_options = PilihanSoal::where('id_soal', $paket_soal->detail_paket_soal->id_soal->orderBy('number_option')->get();
            
    //         return view('siswa/e-learning-soal/list-ujian/test-ujian', compact( 'paket_soal'));
    // }

    public function indexTest(Request $request, $id_paket_soal = 0){

        $input = (object) $request->input();
        $soal = PaketSoal::find($id_paket_soal);
        $account = $input->auth_data->pengguna->id_pengguna;
        if($cek = Test::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)->where('id_paket_soal', $soal->id_paket_soal)->first()){
            $soaltest = JawabanTest::where('nomer', 1)->where(['id_pengguna' => $account])->where('id_test', $cek->id_test)->first();
            // dd($soaltest->);
          
            return redirect('siswa/e-learning-soal/list-ujian/test/'.$soaltest->id_test.'/1');
        
        }else{

        $waktu = $soal->waktu_pengerjaan;
        $test_duration = $waktu; // Minutes
        $start_time = Carbon::now('Asia/Jakarta');
        $end_time = Carbon::now('Asia/Jakarta')->addMinutes($test_duration);
       
    DB::transaction(function () use ($start_time, $end_time, $soal, $account, $input) {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $test = new Test;
        $test->id_test = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
        $test->id_pengguna = $account;
        $test->id_paket_soal = $soal->id_paket_soal;
        $test->waktu_mulai_pengerjaan = $start_time;
        $test->waktu_selesai_pengerjaan = $end_time;
        $test->status = 0;
        $test->save();

        $question_package_details = DetailPaketSoal::with('soal')->where('id_paket_soal', $test->id_paket_soal)->inRandomOrder()->get();
        $question_number = 1;
        foreach($question_package_details->chunk(40) as $chunk){
            $data = array();
            foreach($chunk as $question_package_detail){
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $question_package_detail = (object) $question_package_detail;
                $test_answer = array(
                    'id_jawaban_test' => $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid(),
                    'id_pengguna' => $account,
                    'id_test' => $test->id_test,
                    'nomer' => $question_number,
                    'id_soal' => $question_package_detail->id_soal,
                    'nilai' => 0,
                    'created_at' => Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon::now('Asia/Jakarta')
                );

                $data[] = $test_answer;
                $question_number++;
            }

            JawabanTest::insert($data);
        }
    }, 1);
    $idtest = Test::where('id_paket_soal', $soal->id_paket_soal)->first();
    $soaltest = JawabanTest::where('nomer', 1)->where(['id_pengguna' => $account])->where('id_test', $idtest->id_test)->first();
    // dd($soaltest->);
    return redirect('siswa/e-learning-soal/list-ujian/test/'.$soaltest->id_test.'/1');

}
   
    // return [
    //     'status' => 202, // SUCCESS AND LOAD CONTENT
    //     'path' => 'e-learning-soal/kategori-soal',
    //     'message' => 'Tambah data Kategori berhasil'
    // ];

     }

///////////////////////////////////sekarang yang ini
public function actionSaveAnswer(Request $request){
    $input = (object) $request->input();

    $validator = Validator::make($request->all(), [
        'question' => 'required'
    ]);

    $test_answer = JawabanTest::where('id_test',$input->test)->where('nomer',$input->no)->first();

    $test_answer->id_pilihan_soal = $input->question_option;
     $test_answer->save();
    return redirect('siswa#e-learning-soal/list-ujian/test/'.$input->test.'/'.$input->no);
    // dd($input);
    // return redirect()->back();
    // if($validator->fails()) {
    //     return back()->with('toast', $validator->errors()->first());
    // }

    // $account = Auth::user();
    // if($test = Test::with('event_time')->where('account_id', $account->account_id)->first()){
        // if(true){
        // $check_running = TestTime::checkRunning($test);
        // if($check_running->code == 500){
        //     $message = $check_running->message;
        //     return view('blank-page', compact('message'));
        // }else{
            // if($check_running->code == 200){
                // if($test_answer = TestAnswer::where(['account_id' => $account->account_id, 'test_id' => $test->test_id, 'question_id' => $input->question])->first()){
                //     if(!empty($input->question_option)){
                //         if($question_option = QuestionOption::where(['question_id' => $test_answer->question_id, 'question_option_id' => $input->question_option])->first()){
                //             $question_category = QuestionCategory::join('questions', 'questions.question_category_id', '=', 'question_categories.question_category_id')->where('question_id', $test_answer->question_id)->first();
                //             $test_answer->question_option_id = $question_option->question_option_id;
                //             if($question_option->correct == 1){
                //                 $test_answer->correct = 1;
                //                 $test_answer->value = $question_category->true_value;
                //             }else{
                //                 $test_answer->correct = 2;
                //                 $test_answer->value = $question_category->false_value;
                //             }
                //             $test_answer->submit_answer = now();
                //             $test_answer->save();

                //             if($next_test_answer = TestAnswer::where(['account_id' => $account->account_id, 'test_id' => $test->test_id, 'number' => ($test_answer->number + 1)])->first()){
                //                 return redirect('test/question/'.$next_test_answer->question_id);
                //             }else{
                //                 return back();

                //             }
                //         }else{
                //             $message = 'Tidak bisa memjawab soal';
                //             return view('blank-page', compact('message'));
                //         }
                //     }else{
                //         $question_category = QuestionCategory::join('questions', 'questions.question_category_id', '=', 'question_categories.question_category_id')->where('question_id', $test_answer->question_id)->first();
                //         $test_answer->question_option_id = null;
                //         $test_answer->correct = 0;
                //         $test_answer->value = $question_category->null_value;
                //         $test_answer->save();

                //         return back();
                //     }
                // }else{
                //     $message = 'Tidak bisa memjawab soal';
                //     return view('blank-page', compact('message'));


    //             }
    //         }else{
    //             $message = $check_running->message;
    //             return view('blank-page', compact('message'));
    //         }
    //     }
    // }else{
    //     $message = 'Tidak bisa menjawab soal';
    //     return view('blank-page', compact('message'));
    // }
}
 public function indexTest2(Request $request,$id_soal = 0,$no=0){
    
     $test =JawabanTest::where('id_test',$id_soal)->where('nomer',$no)->with('test','soal')->first();

    //  $soal = Soal::where('id_soal',$test->id_soal)-get();
   
        $paket_soal =  PaketSoal::where('id_paket_soal',$test->test->id_paket_soal)->with('detail_paket_soal')->first();
            //  $question = Soal::find();
            //  dd($paket_soal->detail_paket_soal);
        $question_options = PilihanSoal::where('id_soal', $test->id_soal)->orderBy('number_option')->get();
        
            return view('siswa/e-learning-soal/list-ujian/test-ujian', compact( 'test','paket_soal', 'question_options','no'));
    }

    







    public function actionEndTest(Request $request){
        $input = (object) $request->input();
       
     
      

$testi = Test::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->where('id_test',$input->test)->first();

$testi->status = 1;
 $testi->save();
 return [
    'status' => 202, // SUCCESS AND LOAD CONTENT
    'path' => 'e-learning-soal/list-ujian',
    'message' => 'Berhasil menjawab Soal'
];
        // $account = Auth::user();
        // if($test = Test::with('event_time')->where('account_id', $account->account_id)->first()){
        //     $check_running = TestTime::checkRunning($test);
        //     if($check_running->code == 500){
        //         $message = $check_running->message;
        //         return view('blank-page', compact('message'));
        //     }else{
        //         if($check_running->code == 200){
        //             $test->end_at = Carbon::now('Asia/Jakarta');
        //             $test->save();
        //             return back()->with('toast', 'Test Anda sudah selesai dikerjakan');
        //         }else{
        //             $message = $check_running->message;
        //             return view('blank-page', compact('message'));
        //         }
        //     }
        // }else{
        //     $message = 'Tidak bisa mengerjakan soal';
        //     return view('blank-page', compact('message'));
        // }
    }


}
