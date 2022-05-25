<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JawabanTest;
use App\Models\PaketSoal;
use App\Models\Siswa;
use App\Models\Test;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class HasilTestController extends Controller
{
    public function indexList(Request $request){
        return view('guru/e-learning-soal/hasil-test/view-hasil-test');
  
    }

    public function commonList(Request $request){
        $list_data = PaketSoal::with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal')->with(['detail_paket_soal.soal.pilihan_soal' => function($q){ return $q->whereNotNull('content'); }]);
// dd($list_data);
        return Datatables::of($list_data)
                ->addColumn('total_siswa', function($item){
                    $total = Siswa::where('id_kelas',$item->id_kelas)->count();
                    // $statusTest = Test::where('id_paket_soal', $item->id_paket_soal)->where('id_pengguna', Auth::id())->first();
                    return $total;
                })
                ->addColumn('total_mengerjakan', function($item){
                    $mengerjakan = Test::where('id_paket_soal',$item->id_paket_soal)->count();
               
                    return $mengerjakan;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_paket_soal
                    );
                    return $data;
                })
                ->make(true);
    }

    public function indexDetail(Request $request, $id_paket_soal = 0){
        if($question_package = PaketSoal::where('id_paket_soal',$id_paket_soal)->first()){
            // $siswa = Siswa::where('id_kelas',$question_package->id_kelas)->with('pengguna')->get();

         
            return view('guru/e-learning-soal/hasil-test/detail-hasil-test', compact('question_package'));
        }else{
         
            // return abort();
        }
    }
    public function detailList(Request $request,$question_package_id = 0 ){
        // $id_paket_soal = 'D4Ka216526782936281de9586433';
        // $tipe = 1;

$test = Test::where('id_paket_soal',$question_package_id)->with('pengguna')->get();

// $jawaban_test = JawabanTest('id_test', $test->id_test)

        // $question_package_details = DetailPaketSoal::where('id_paket_soal', $question_package_id)->get();
        // $list_question_selected = $question_package_details->pluck('id_soal');
        // if($tipe == 1){
        //     $list_data = Soal::with('pengguna')->whereNotIn('id_soal', $list_question_selected);
        // }else{
        //     $list_data = Soal::with('pengguna')->whereIn('id_soal', $list_question_selected);
        // }
        // ,$new_val)->make(true);
        return Datatables::of($test)
        ->addColumn('total_nilai', function($item){
            $data = JawabanTest::where('id_test',$item->id_test)->get();
            $total = 0;
            foreach($data as $da){
                $total = $total + $da->nilai;

            }

            return $total;
        })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->pengguna->id_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }





}
