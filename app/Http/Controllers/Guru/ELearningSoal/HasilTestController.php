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
        $input = (object) $request->input();
        $list_data = PaketSoal::where('paket_soal.created_by', $input->auth_data->pengguna->id_pengguna)->with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal', 'kategori_soal', 'paket_soal_kelas.kelas')->with(['detail_paket_soal.soal.pilihan_soal' => function ($q) {
            return $q->whereNotNull('content');
        }])->orderBy('paket_soal.created_at', 'desc');


        return Datatables::of($list_data)
            ->addColumn('total_siswa', function ($item) {
                $id_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $kelas) {
                    $id_kelas[$key] = $kelas->id_kelas;
                }
                $total = Siswa::whereIn('id_kelas', $id_kelas)->count();
                return $total;
            })
            ->addColumn('total_mengerjakan', function ($item) {
                $mengerjakan = Test::where('id_paket_soal', $item->id_paket_soal)->count();

                return $mengerjakan;
            })
            ->addColumn('action', function ($item) {
                $nm_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $kelas) {
                    $nm_kelas[$key] = $kelas->kelas->nm_kelas;
                }
                $data = array(
                    'id' => $item->id_paket_soal,
                    'nm_kelas' => $nm_kelas,
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
        $jawaban_test = JawabanTest::where('id_test', $id_test)->where('id_pengguna', $id_pengguna)->where('id_tipe_soal', 1)->get();
        $total_nilai = 0;
        foreach ($jawaban_test as $test) {
            $total_nilai = $total_nilai + $test->nilai;
        }
        return view('guru/e-learning-soal/hasil-test/koreksi-hasil-test', compact('questions', 'id_paket_soal', 'id_pengguna', 'total_nilai'));
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

        $total_nilai = 0;
        foreach ($input->id_jawaban_test as $id_jawaban_test) {
            $total_nilai =  $total_nilai + $input->nilai[$id_jawaban_test];
        }
        $total_nilai = $total_nilai + $input->total_nilai;

        if ($total_nilai > 100) {
            return [
                'status' => 300, // FAILED
                'message' => 'Total Nilai tidak boleh Lebih dari 100'
            ];
        }

        foreach ($input->id_jawaban_test as $id_jawaban_test) {
            $jawaban_test = JawabanTest::where('id_jawaban_test', $id_jawaban_test)->first();
            $jawaban_test->nilai = $input->nilai[$id_jawaban_test];
            $jawaban_test->tangapan = $input->tangapan[$id_jawaban_test];
            $jawaban_test->status_koreksi = 1;
            $jawaban_test->save();
        }
        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'e-learning-soal/hasil-test/detail/' . $input->id_paket_soal,
            'message' => 'Berhasil Mengkoreksi Hasil Test'
        ];
    }

    public function detailList($question_package_id = 0)
    {
        $test = Test::where('test.id_paket_soal', $question_package_id)->with('pengguna', 'paket_soal', 'detail_paket_soal', 'jawaban_test');

        return Datatables::of($test)
            ->editColumn('detail_paket_soal', function ($item) {
                return $item->detail_paket_soal->count();
            })->addColumn('pilihan_ganda', function ($item) {
                return $item->jawaban_test->where('id_tipe_soal', 1)->count() . ' (Benar : ' .  $item->jawaban_test->where('id_tipe_soal', 1)->where('nilai', '!=', '0')->count() . ' x ' . $item->paket_soal->nilai  . ')';
            })
            ->addColumn('essay', function ($item) {
                return $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->count();
            })
            ->addColumn('total_nilai', function ($item) {
                //pilihan ganda
                $nilai_pilihan_ganda = $item->jawaban_test->where('id_tipe_soal', 1)->pluck('nilai')->sum();
                $nilai_paket_soal_pilihan_ganda = $item->paket_soal->nilai;

                //essay
                $nilai_pilihan_essay_submit = $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->pluck('nilai')->sum();
                $jawaban_test = $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->where('status_koreksi', 0)->first();

                //total
                $nilai = $item->jawaban_test->pluck('nilai')->sum();

                $data = array(
                    'nilai_pilihan_ganda' => $nilai_pilihan_ganda,
                    'nilai' => $nilai,
                    'id_test' => $jawaban_test ? $jawaban_test->id_test : '',
                    'status_koreksi' =>  $jawaban_test ? '0' : '1',
                    'id_pengguna' => $item->id_pengguna,
                    'id_paket_soal' => $item->id_paket_soal,
                    'nilai_paket_soal_pilihan_ganda' => $nilai_paket_soal_pilihan_ganda,
                    'nilai_pilihan_essay_submit' => $nilai_pilihan_essay_submit,
                    // 'total_nilai_pilihan_ganda_benar' => $total_nilai_pilihan_ganda_benar,
                );
                return $data;
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
