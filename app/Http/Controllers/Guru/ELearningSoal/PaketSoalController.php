<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\Kelas;
use App\Models\PaketSoal;
use App\Models\Soal;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;


class PaketSoalController extends Controller
{
    public function indexList(Request $request){
        return view('guru/e-learning-soal/paket-soal/view-paket-soal');
    }

    public function indexDetail(Request $request, $id_paket_soal = 0){
        if($question_package = PaketSoal::find($id_paket_soal)){
         
            return view('guru/e-learning-soal/paket-soal/paket-soal-detail', compact('question_package'));
        }else{
         
            // return abort();
        }
    }

    public function indexManage(Request $request, $id = 0){
        $kelas = Kelas::get();
        // $events = Event::get();
        // $events = null;
        if(!empty($id)){
            $item = PaketSoal::find($id);
        }else{
            $item = null;
        }
        return view('guru/e-learning-soal/paket-soal/manage-paket-soal', compact('item', 'kelas'));
    }

    public function indexTest(Request $request, $id = 0){
        $item = PaketSoal::find($id);
        $question_package_details = DetailPaketSoal::with('soal', 'soal.pilihan_soal')->where('id_paket_soal', $item->id_paket_soal)->get();

        return view('guru/e-learning-soal/paket-soal/test-paket-soal', compact('item', 'question_package_details'));
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

    public function detailList(Request $request,$question_package_id = 0, $tipe ){
        // $id_paket_soal = 'D4Ka216526782936281de9586433';
        // $tipe = 1;
        $question_package_details = DetailPaketSoal::where('id_paket_soal', $question_package_id)->get();
        $list_question_selected = $question_package_details->pluck('id_soal');
        if($tipe == 1){
            $list_data = Soal::with('kategori_soal')->whereNotIn('id_soal', $list_question_selected);
        }else{
            $list_data = Soal::with('kategori_soal')->whereIn('id_soal', $list_question_selected);
        }

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_soal
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionSave(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'event' => 'required'
        // ]);

        // if($validator->fails()) {
        //     return back()->with('toast', $validator->errors()->first());
        // }

        $input = (object) $request->input();

        if($question_package = PaketSoal::find($input->question_package_id)){
            // $question_package->title = $input->title;
            // $question_package->event_id = $input->event;
            // $question_package->save();

            // return back()->with('toast', 'Your changed save successfully');
        }else{
    
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $question_package = new PaketSoal;
            $question_package->id_paket_soal = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $question_package->text = $input->title;
            $question_package->id_kelas = $input->kelas;
            $question_package->nilai = $input->nilai;
            $question_package->waktu_mulai = $input->waktu_mulai;
            $question_package->waktu_selesai = $input->waktu_selesai;
            $question_package->waktu_pengerjaan = $input->waktu_pengerjaan;
            $question_package->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];

            // return back()->with('toast', 'Save item successfully');
        }
    }

    public function actionDelete(Request $request){
        $input = (object) $request->input();
        if($question_package = PaketSoal::find($input->question_package_id)){
            $question_package->delete();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];

        }else{
            return response()->json([
                'status' => 500,
                'message' => 'Error'
            ]);
        }    
    }

    public function actionDetailAdd(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'question_package_id' => 'required',
        //     'question_id' => 'required'
        // ]);

        // if($validator->fails()) {
        //     return back()->with('toast', $validator->errors()->first());
        // }

        $input = (object) $request->input();
        if($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $input->id_soal])->first()){
            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Error'
            // ]);
        }else{
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $question_package_detail = new DetailPaketSoal;
            $question_package_detail->id_detail_paket_soal = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $question_package_detail->id_paket_soal = $input->id_paket_soal;
            $question_package_detail->id_soal = $input->id_soal;
            $question_package_detail->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];
        }
    }

    // public function actionDetailDelete(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'question_package_id' => 'required',
    //         'question_id' => 'required'
    //     ]);

    //     if($validator->fails()) {
    //         return back()->with('toast', $validator->errors()->first());
    //     }

    //     $input = (object) $request->input();
    //     if($question_package_detail = QuestionPackageDetail::where(['question_package_id' => $input->question_package_id, 'question_id' => $input->question_id])->first()){
    //         $question_package_detail->delete();
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Delete successfully'
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'Error'
    //         ]);
    //     }
    // }
}
