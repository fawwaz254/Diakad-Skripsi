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
    public function indexList(Request $request)
    {
        return view('guru/e-learning-soal/hasil-test/view-hasil-test');
    }

    public function commonList(Request $request)
    {
        $list_data = PaketSoal::with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal','kategori_soal')->with(['detail_paket_soal.soal.pilihan_soal' => function ($q) {
            return $q->whereNotNull('content');
        }]);
        // dd($list_data);
        return Datatables::of($list_data)
            ->addColumn('total_siswa', function ($item) {
                $total = Siswa::where('id_kelas', $item->id_kelas)->count();
                // $statusTest = Test::where('id_paket_soal', $item->id_paket_soal)->where('id_pengguna', Auth::id())->first();
                return $total;
            })
            ->addColumn('total_mengerjakan', function ($item) {
                $mengerjakan = Test::where('id_paket_soal', $item->id_paket_soal)->count();

                return $mengerjakan;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_paket_soal
                );
                return $data;
            })
            ->make(true);
    }

    public function indexDetail(Request $request, $id_paket_soal = 0)
    {
        if ($question_package = PaketSoal::where('id_paket_soal', $id_paket_soal)->first()) {
            return view('guru/e-learning-soal/hasil-test/detail-hasil-test', compact('question_package'));
        } else {
            return view('404');
        }
    }

    public function indexKoreksi(Request $request, $id_paket_soal = null, $id_test = null, $id_pengguna = null)
    {
        $questions = JawabanTest::where('id_test', $id_test)->where('id_pengguna', $id_pengguna)->where('status_koreksi', 0)->with('soal')->get();
        return view('guru/e-learning-soal/hasil-test/koreksi-hasil-test', compact('questions', 'id_paket_soal', 'id_pengguna'));
    }

    public function actionKoreksiHasilTest(Request $request)
    {
        $input = (object) $request->input();
        $validator = Validator::make($request->all(), [
            'id_jawaban_test' => 'required',
            'nilai' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        };
        foreach ($input->id_jawaban_test as $id_jawaban_test) {
            $jawaban_test = JawabanTest::where('id_jawaban_test', $id_jawaban_test)->first();
            $jawaban_test->nilai = $input->nilai[$id_jawaban_test];
            $jawaban_test->status_koreksi = 1;
            $jawaban_test->save();
        }
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'e-learning-soal/hasil-test/detail/' . $input->id_paket_soal,
            'message' => 'Berhasil Mengkoreksi Hasil Test'
        ];
    }

    public function detailList(Request $request, $question_package_id = 0)
    {
        // $id_paket_soal = 'D4Ka216526782936281de9586433';
        // $tipe = 1;

        $test = Test::where('id_paket_soal', $question_package_id)->with('pengguna','paket_soal','detail_paket_soal')->get();
    
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
        ->editColumn('detail_paket_soal',function($item){
            return $item->detail_paket_soal->count();
            // $counter = 0 ;
            // foreach($item->detail_paket_soal as $k ){
            //     $counter++;
            // }
          
            // return  $counter;
        })
            ->addColumn('total_nilai', function ($item) use ($question_package_id) {

                // Jika jawaban ada soal essay
                if ($jawaban_test = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->where('status_koreksi', 0)->first()) {
                    $nilai_pilihan_ganda = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->where('id_tipe_soal',1)->pluck('nilai')->sum();
                    $nilai = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->pluck('nilai')->sum();
                    $data = array(
                        'nilai_pilihan_ganda' => $nilai_pilihan_ganda,
                        'nilai' => $nilai,
                        'id_test' => $jawaban_test->id_test,
                        'status_koreksi' => 0,
                        'id_pengguna' => $item->id_pengguna,
                        'id_paket_soal' => $question_package_id
                    );
                } else {
                    $nilai = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->pluck('nilai')->sum();
                    $nilai_pilihan_ganda = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->where('id_tipe_soal',1)->pluck('nilai')->sum();
                    $nilai_pilihan_essay_submit = JawabanTest::where('id_test', $item->id_test)->where('id_pengguna', $item->id_pengguna)->whereIn('id_tipe_soal',[2,3])->pluck('nilai')->sum();
                    $data = array(
                        'nilai_pilihan_ganda' => $nilai_pilihan_ganda,
                        'nilai_pilihan_essay_submit' => $nilai_pilihan_essay_submit,
                        'nilai' => $nilai,
                        'status_koreksi' => 1,
                    );
                }
                return $data;
                // $total = 0;
                // foreach ($data as $da) {
                //     $total = $total + $da->nilai;
                // }

            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->pengguna->id_pengguna
                );
                return $data;
            })
            ->make(true);
    }
}
