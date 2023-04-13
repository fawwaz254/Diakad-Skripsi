<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetailPaketSoal;
use App\Models\KategoriSoal;
use App\Models\Kelas;

use App\Models\PaketSoal;
use App\Models\PaketSoalKelas;
use App\Models\Soal;
use App\Models\Test;
use App\Models\WaliKelas;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;


class PaketSoalController extends Controller
{
    public function indexList(Request $request)
    {
        return view('guru/e-learning-soal/paket-soal/view-paket-soal');
    }

    public function indexDetail(Request $request, $id_paket_soal = 0)
    {
        if ($question_package = PaketSoal::find($id_paket_soal)) {

            return view('guru/e-learning-soal/paket-soal/paket-soal-detail', compact('question_package'));
        } else {

            // return abort();
        }
    }

    public function indexManage(Request $request, $id = 0)
    {
        $input = (object) $request->input();
        $kelas = Kelas::get();
        $kategori = KategoriSoal::all();
        $wali_kelas = get_keterangan_wali_kelas($input->auth_data->pengguna->id_pengguna);
        // $events = Event::get();
        // $events = null;
        if (!empty($id)) {
            $item = PaketSoal::find($id);
        } else {
            $item = null;
        }
        return view('guru/e-learning-soal/paket-soal/manage-paket-soal', compact('item', 'kelas', 'kategori', 'wali_kelas'));
    }

    public function indexTest(Request $request, $id = 0)
    {
        $item = PaketSoal::find($id);
        $question_package_details = DetailPaketSoal::with('soal', 'soal.pilihan_soal')->where('id_paket_soal', $item->id_paket_soal)->get();

        return view('guru/e-learning-soal/paket-soal/test-paket-soal', compact('item', 'question_package_details'));
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();

        $list_data = PaketSoal::where('paket_soal.created_by', $input->auth_data->pengguna->id_pengguna)->with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal', 'kategori_soal', 'paket_soal_kelas.kelas')->orderBy('paket_soal.created_at', 'desc')->with(['detail_paket_soal.soal.pilihan_soal' => function ($q) {
            return $q->whereNotNull('content');
        }])
            ->when($input->status == 0, function ($q) {
                $q->where('status', 0);
            });

        return Datatables::of($list_data)
            ->addColumn('total_question', function ($item) {
                return $item->detail_paket_soal->count();
            })
            ->addColumn('total_answer', function ($item) {
                $value = 0;
                foreach ($item->detail_paket_soal as $data) {
                    $value += $data->soal->pilihan_soal->count();
                }
                return $value;
            })
            ->addColumn('action', function ($item) use ($input) {
                $nm_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $kelas) {
                    $nm_kelas[$key] = $kelas->kelas->nm_kelas;
                }
                $data = array(
                    'id' => $item->id_paket_soal,
                    'status' => $input->status,
                    'nm_kelas' => $nm_kelas,
                );
                return $data;
            })
            ->make(true);
    }

    public function detailList(Request $request, $question_package_id = 0, $tipe)
    {
        $question_package_details = DetailPaketSoal::where('id_paket_soal', $question_package_id)->get();
        $list_question_selected = $question_package_details->pluck('id_soal');
        $paket_soal = PaketSoal::find($question_package_id);
        if ($tipe == 1) {
            $list_data = Soal::where('id_kategori_soal', $paket_soal->id_kategori_soal)->with('pengguna', 'kategori_soal')->whereNotIn('id_soal', $list_question_selected);
        } else {
            $list_data = Soal::where('id_kategori_soal', $paket_soal->id_kategori_soal)->with('pengguna', 'kategori_soal')->whereIn('id_soal', $list_question_selected);
        }

        return Datatables::of($list_data)
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

    public function actionSave(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'event' => 'required'
        // ]);

        // if($validator->fails()) {
        //     return back()->with('toast', $validator->errors()->first());
        // }

        $input = (object) $request->input();


        if ($paket_soal = PaketSoal::find($input->id_paket_soal)) {
            $paket_soal->text = $input->title;
            $paket_soal->id_kategori_soal = $input->kategori;
            $paket_soal->id_kelas = $input->kelas;
            $paket_soal->nilai = $input->nilai;
            $paket_soal->waktu_mulai = $input->waktu_mulai;
            $paket_soal->waktu_selesai = $input->waktu_selesai;
            $paket_soal->waktu_pengerjaan = $input->waktu_pengerjaan;
            $paket_soal->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Merubah paket Soal'
            ];
        } else {

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            $question_package = new PaketSoal;
            $question_package->id_paket_soal        = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $question_package->text                 = $input->title;
            $question_package->id_kategori_soal     = $input->kategori;
            $question_package->nilai                = $input->nilai;
            $question_package->waktu_mulai          = $input->waktu_mulai;
            $question_package->waktu_selesai        = $input->waktu_selesai;
            $question_package->waktu_pengerjaan     = $input->waktu_pengerjaan;
            $question_package->status               = 0;
            $question_package->created_by           = $input->auth_data->pengguna->id_pengguna;
            $question_package->save();

            foreach ($input->kelas as $id_kelas) {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $question_package_class = new PaketSoalKelas;
                $question_package_class->id_paket_soal_kelas    = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $question_package_class->id_paket_soal          = $question_package->id_paket_soal;
                $question_package_class->id_kelas               = $id_kelas;
                $question_package_class->created_by             = $input->auth_data->pengguna->id_pengguna;
                $question_package_class->save();
            }


            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];

            // return back()->with('toast', 'Save item Successfully');
        }
    }

    public function actionDelete(Request $request)
    {
        $input = (object) $request->input();
        $cek = Test::where('id_paket_soal', $input->question_package_id)->first();
        if ($cek) {
            return [
                'status' => 300, // FAILED
                'message' => 'Gagal dihapus, Paket Soal sudah digunakan'
            ];
        }
        if ($question_package = PaketSoal::find($input->question_package_id)) {
            $question_package->delete();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menghapus paket Soal'
            ];
        } else {
            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Error'
            // ]);
        }
    }

    public function actionDetailAdd(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'question_package_id' => 'required',
        //     'question_id' => 'required'
        // ]);

        // if($validator->fails()) {
        //     return back()->with('toast', $validator->errors()->first());
        // }

        $input = (object) $request->input();
        if ($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $input->id_soal])->first()) {
            // return response()->json([
            //     'status' => 500,
            //     'message' => 'Error'
            // ]);
        } else {
            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $question_package_detail = new DetailPaketSoal;
            $question_package_detail->id_detail_paket_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
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

    public function actionDetailDelete(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'question_package_id' => 'required',
        //     'question_id' => 'required'
        // ]);

        // if($validator->fails()) {
        //     return back()->with('toast', $validator->errors()->first());
        // }

        $input = (object) $request->input();
        if ($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $input->id_soal])->first()) {
            $question_package_detail->delete();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menghapus paket Soal'
            ];
        }
        // }else{
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'Error'
        //     ]);
        // }
    }
}
