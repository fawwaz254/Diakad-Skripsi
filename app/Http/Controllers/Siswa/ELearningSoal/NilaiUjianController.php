<?php

namespace App\Http\Controllers\Siswa\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JawabanTest;
use App\Models\Test;
use Yajra\Datatables\Datatables;

class NilaiUjianController extends Controller
{
    public function indexList(Request $request)
    {
        return view('siswa/e-learning-soal/nilai-ujian/view-nilai-ujian');
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $test = Test::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('status', 1)->with('detail_paket_soal', 'paket_soal.kategori_soal', 'jawaban_test');

        return Datatables::of($test)
            ->editColumn('jawaban_test', function ($item) {
                return $item->jawaban_test->count();
            })
            ->editColumn('detail_paket_soal', function ($item) {
                return $item->detail_paket_soal->count();
            })
            ->addColumn('total_nilai', function ($item) {
                $nilai = $item->jawaban_test->pluck('nilai')->sum();
                $nilai_pilihan_ganda = $item->jawaban_test->whereIn('id_tipe_soal', [1, 4, 5, 6, 7])->pluck('nilai')->sum();
                $nilai_pilihan_essay_submit = $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->pluck('nilai')->sum();
                $validasi_pilihan_essay_submit =  $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->first();
                $belum_dikoreksi =  $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->where('status_koreksi', '0')->first();

                $data = array(
                    'nilai_pilihan_ganda' => $nilai_pilihan_ganda,
                    'nilai_pilihan_essay_submit' => $nilai_pilihan_essay_submit,
                    'nilai' => $nilai,
                    'status_koreksi' => 1,
                    'belum_dikoreksi' => $belum_dikoreksi ? true : false,
                    'validasi_pilihan_essay_submit' =>  $validasi_pilihan_essay_submit ? true : false,
                    'id_test' => $item->id_test
                );
                return $data;
            })
            ->make(true);
    }
    public function indexPenilaian(Request $request, $id_test = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $questions = JawabanTest::where('id_test', $id_test)->whereIn('id_tipe_soal', [2, 3])->where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('soal')->get();

        return view('siswa/e-learning-soal/nilai-ujian/view-penilaian-ujian', compact('questions'));
    }
}
