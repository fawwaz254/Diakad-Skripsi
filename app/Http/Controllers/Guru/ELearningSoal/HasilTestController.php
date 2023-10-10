<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use App\Exports\RekapNilaiElearning;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\JawabanTest;
use App\Models\PaketSoal;
use App\Models\Siswa;
use App\Models\Test;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class HasilTestController extends Controller
{
    public function indexList()
    {
        return view('guru/e-learning-soal/hasil-test/view-hasil-test');
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();

        if (auth_data()->role_aktif->id_role == '7') {
            $list_data = PaketSoal::query();
        } else {
            $list_data = PaketSoal::where('paket_soal.created_by', $input->auth_data->pengguna->id_pengguna);
        }

        $list_data->with('test', 'kategori_soal', 'paket_soal_kelas.kelas.siswa');

        return Datatables::of($list_data)
            ->addColumn('total_siswa', function ($item) {
                $total = 0;
                foreach ($item->paket_soal_kelas as $kelas) {
                    if (!empty($kelas->kelas)) {
                        $total += $kelas->kelas->siswa->count();
                    }
                }
                return $total;
            })
            ->addColumn('total_mengerjakan', function ($item) {
                return $item->test->count();
            })
            ->addColumn('action', function ($item) {
                $nm_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $kelas) {
                    if (!empty($kelas->kelas)) {
                        $nm_kelas[$key] = $kelas->kelas->nm_kelas;
                    }
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
            return abort(404);
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
        $test = Test::where('test.id_paket_soal', $question_package_id)->with('pengguna.siswa.kelas', 'paket_soal', 'detail_paket_soal', 'jawaban_test');

        return Datatables::of($test)
            ->editColumn('detail_paket_soal', function ($item) {
                return $item->detail_paket_soal->count();
            })->addColumn('soal_terisi', function ($item) {
                return $item->jawaban_test->count();
            })
            // ->addColumn('essay', function ($item) {
            //     return $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->count();
            // })
            ->addColumn('total_nilai', function ($item) {
                //pilihan ganda
                $nilai_pilihan_ganda = number_format($item->jawaban_test->whereIn('id_tipe_soal', [1, 4, 5, 6, 7])->pluck('nilai')->sum());
                $nilai_paket_soal_pilihan_ganda = $item->paket_soal->nilai;

                //essay
                $nilai_pilihan_essay_submit = $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->pluck('nilai')->sum();
                $jawaban_test = $item->jawaban_test->whereIn('id_tipe_soal', [2, 3])->where('status_koreksi', 0)->first();

                //total
                $nilai = number_format($item->jawaban_test->pluck('nilai')->sum());

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
                    'id' => $item->id_test
                );
                return $data;
            })
            ->make(true);
    }

    public function actionDeleteTest(Request $request, $id)
    {
        if ($test = Test::find($id)) {
            JawabanTest::where('id_test', $test->id_test)->delete();
            $test->delete();

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Hasil Test Successfully'
            ];
        } else {
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Failed'
            ];
        }
    }

    public function printHasilTest(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa', 'test', 'kategori_soal')->first();
        $time = Carbon::parse($paket_soal->waktu_mulai);
        // $kelas = '';
        // $siswa_seharusnya = 0;
        // foreach ($paket_soal->paket_soal_kelas as $paket_soal_kelas) {
        //     $kelas =  $kelas . $paket_soal_kelas->kelas->nm_kelas . ', ';
        //     $siswa_seharusnya += $paket_soal_kelas->kelas->siswa->count();
        // }

        // $siswa_tidak_masuk = $siswa_seharusnya - $paket_soal->test->count();

        return view('guru/e-learning-soal/hasil-test/print-hasil-test', compact('paket_soal', 'auth_data', 'semester_aktif', 'time'));
    }

    public function printHasilTest2(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa.pengguna', 'test', 'kategori_soal')->first();
        $time = Carbon::parse($paket_soal->waktu_mulai);

        return view('guru/e-learning-soal/hasil-test/print-daftar-hasil', compact('paket_soal', 'auth_data', 'semester_aktif', 'time'));
    }

    public function printHasilTest3(Request $request, $id)
    {
        $paket_soal = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas.kelas.siswa.pengguna', 'test.jawaban_test', 'kategori_soal', 'detail_paket_soal')->first();

        $nilai_siswa = [];

        foreach ($paket_soal->test as $test) {
            $nilai_siswa[$test->id_pengguna] =  $test->jawaban_test->sum('nilai');
        }

        $data['nilai_siswa'] = $nilai_siswa;
        $data['paket_soal'] = $paket_soal;

        return Excel::download(new RekapNilaiElearning($data), 'Rekap Nilai E-learning' . $paket_soal->text . '(' . $paket_soal->kategori_soal->nm_kategori_soal . ').xlsx');
    }
}
