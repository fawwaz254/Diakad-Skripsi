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
use Yajra\Datatables\Datatables;
use Carbon\Carbon;


class PaketSoalController extends Controller
{
    public function indexList()
    {
        return view('guru/e-learning-soal/paket-soal/view-paket-soal');
    }

    public function indexDetail(Request $request, $id_paket_soal = 0)
    {
        if ($question_package = PaketSoal::find($id_paket_soal)) {
            return view('guru/e-learning-soal/paket-soal/paket-soal-detail', compact('question_package'));
        } else {
            return view('guru/e-learning-soal/paket-soal/view-paket-soal');
        }
    }

    public function indexManage(Request $request, $id = 0)
    {
        $input = (object) $request->input();
        $kelas = Kelas::where('is_aktif', 1)->get();
        $kategori = KategoriSoal::all();
        $wali_kelas = get_keterangan_wali_kelas($input->auth_data->pengguna->id_pengguna);

        if (!empty($id)) {
            $item = PaketSoal::where('id_paket_soal', $id)->with('paket_soal_kelas')->first();
        } else {
            $item = null;
        }
        return view('guru/e-learning-soal/paket-soal/manage-paket-soal', compact('item', 'kelas', 'kategori', 'wali_kelas'));
    }

    public function indexTest(Request $request, $id = 0)
    {
        $item = PaketSoal::where('id_paket_soal', $id)->with('detail_paket_soal.soal.pilihan_soal', 'detail_paket_soal.soal.pilihan_jawaban', 'detail_paket_soal.soal.pilihan_pertanyaan')->first();
        return view('guru/e-learning-soal/paket-soal/test-paket-soal', compact('item'));
    }

    public function commonList(Request $request)
    {
        $input = (object) $request->input();

        if (auth_data()->role_aktif->id_role == '7') {
            $list_data = PaketSoal::query();
        } else {
            $list_data = PaketSoal::where('paket_soal.created_by', $input->auth_data->pengguna->id_pengguna);
        }

        $list_data->with('kelas', 'detail_paket_soal', 'detail_paket_soal.soal', 'kategori_soal', 'paket_soal_kelas.kelas')->orderBy('paket_soal.created_at', 'desc')
            ->when($input->status == '0', function ($q) {
                $q->doesntHave('test');
            })->when($input->status == '1', function ($q) {
                $q->whereHas('test');
            });

        return Datatables::of($list_data)
            ->addColumn('total_question', function ($item) {
                return  $item->detail_paket_soal->count();
            })
            ->addColumn('nilai', function ($item) {
                if ($item->nilai == '0') {
                    if ($item->detail_paket_soal->count() == '0') {
                        return intval(100 / 1);
                    } else {
                        return intval(100 / $item->detail_paket_soal->count());
                    }
                }
                return  $item->nilai;
            })
            ->editColumn('waktu_mulai', function ($item) {
                return Carbon::parse($item->waktu_mulai)->format('d-m-Y (H:i)');
            })
            ->editColumn('waktu_selesai', function ($item) {
                return Carbon::parse($item->waktu_selesai)->format('d-m-Y (H:i)');
            })
            ->editColumn('waktu_pengerjaan', function ($item) {
                return $item->waktu_pengerjaan . ' Menit';
            })->editColumn('version', function ($item) {
                return $item->version ? $item->version : '-';
            })
            ->addColumn('action', function ($item) use ($input) {
                $nm_kelas = [];
                foreach ($item->paket_soal_kelas as $key => $paket_soal_kelas) {
                    if ($paket_soal_kelas->kelas) {
                        $nm_kelas[$key] = $paket_soal_kelas->kelas->nm_kelas;
                    } else {
                        $nm_kelas[$key] = '';
                    }
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
        $input = (object) $request->input();
        $question_package_details = DetailPaketSoal::where('id_paket_soal', $question_package_id);
        $list_question_selected = $question_package_details->pluck('id_soal');
        $paket_soal = PaketSoal::find($question_package_id);
        if ($tipe == 1) {
            $list_data = Soal::where('id_kategori_soal', $paket_soal->id_kategori_soal)->with('pengguna', 'kategori_soal', 'detail_paket_soal.paket_soal.paket_soal_kelas.kelas')->whereNotIn('id_soal', $list_question_selected)->orderBy('soal.created_at', 'desc');
        } else if ($tipe == 0) {
            $list_data = Soal::where('id_kategori_soal', '!=', $paket_soal->id_kategori_soal)->with('pengguna', 'kategori_soal', 'detail_paket_soal.paket_soal.paket_soal_kelas.kelas')->whereNotIn('id_soal', $list_question_selected)->orderBy('soal.created_at', 'desc');
        } else {
            $list_data = Soal::with('pengguna', 'kategori_soal', 'detail_paket_soal.paket_soal.paket_soal_kelas.kelas')->whereIn('id_soal', $list_question_selected)->orderBy('soal.created_at', 'desc');
        }

        // $id_pengguna = $input->auth_data->pengguna->id_pengguna;

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_soal,
                    // 'edit' => $item->id_pengguna == $id_pengguna ? true : false
                );
                return $data;
            })->addColumn('kelas', function ($item) {
                $nm_kelas = [];
                if ($item->detail_paket_soal) {
                    foreach ($item->detail_paket_soal as $detail_paket_soal) {
                        if ($detail_paket_soal->paket_soal) {
                            foreach ($detail_paket_soal->paket_soal->paket_soal_kelas as $paket_soal_kelas) {
                                $nm_kelas[] = $paket_soal_kelas->kelas->nm_kelas;
                            }
                        }
                    }
                }
                return $nm_kelas;
            })
            ->addColumn('tipe_soal', function ($item) {
                if ($item->id_tipe_soal == 1) {
                    return "Pilihan Ganda";
                } else if ($item->id_tipe_soal == 2) {
                    return "Isian";
                } else if ($item->id_tipe_soal == 3) {
                    return "File";
                } else if ($item->id_tipe_soal == 4) {
                    return "Pilihan Kompleks";
                } else if ($item->id_tipe_soal == 5) {
                    return "Isian Singkat";
                } else if ($item->id_tipe_soal == 6) {
                    return "Menjodohkan";
                } else if ($item->id_tipe_soal == 7) {
                    return "True/False";
                }
            })
            ->make(true);
    }

    public function actionSave(Request $request)
    {
        $input = (object) $request->input();
        if ($paket_soal = PaketSoal::find($input->id_paket_soal)) {

            $paket_soal_kelass = PaketSoalKelas::where('id_paket_soal', $input->id_paket_soal)->get();
            foreach ($paket_soal_kelass as $paket_soal_kelas) {
                $paket_soal_kelas->delete();
            }

            $paket_soal->text = $input->title;
            $paket_soal->id_kategori_soal = $input->kategori;
            // $paket_soal->id_kelas = $input->kelas;
            $paket_soal->nilai = $input->nilai;
            $paket_soal->waktu_mulai = $input->waktu_mulai;
            $paket_soal->waktu_selesai = $input->waktu_selesai;
            $paket_soal->waktu_pengerjaan = $input->waktu_pengerjaan;
            $paket_soal->version = $input->version;
            $paket_soal->save();

            foreach ($input->kelas as $id_kelas) {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $question_package_class = new PaketSoalKelas;
                $question_package_class->id_paket_soal_kelas    = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $question_package_class->id_paket_soal          = $input->id_paket_soal;
                $question_package_class->id_kelas               = $id_kelas;
                $question_package_class->created_by             = $input->auth_data->pengguna->id_pengguna;
                $question_package_class->save();
            }

            if (auth_data()->role_aktif->id_role != '7') {
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'e-learning-soal/paket-soal',
                    'message' => 'Berhasil Merubah paket Soal'
                ];
            } else {
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'aktivitas-semester/paket-soal',
                    'message' => 'Berhasil Merubah paket Soal'
                ];
            }
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
            $question_package->version               = 1;
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
        } else { }
    }

    public function actionDetailAdd(Request $request)
    {
        $input = (object) $request->input();
        if ($input->id_soal != '0') {
            if ($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $input->id_soal])->first()) { } else {
                $now = Carbon::now(env('APP_TIMEZONE', ''));
                $question_package_detail = new DetailPaketSoal;
                $question_package_detail->id_detail_paket_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $question_package_detail->id_paket_soal = $input->id_paket_soal;
                $question_package_detail->id_soal = $input->id_soal;
                $question_package_detail->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    // 'path' => 'e-learning-soal/paket-soal',
                    'message' => 'Berhasil Menambah paket Soal'
                ];
            }
        } else {

            $paket_soal = PaketSoal::where('id_paket_soal', $input->id_paket_soal)->first();
            $soals = Soal::where('id_kategori_soal', $paket_soal->id_kategori_soal)->get();

            foreach ($soals as $soal) {
                if ($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $soal->id_soal])->first()) { } else {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $question_package_detail = new DetailPaketSoal;
                    $question_package_detail->id_detail_paket_soal = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $question_package_detail->id_paket_soal = $input->id_paket_soal;
                    $question_package_detail->id_soal = $soal->id_soal;
                    $question_package_detail->save();
                }
            }
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah Semua Soal'
            ];
        }
    }

    public function actionDetailDelete(Request $request)
    {
        $input = (object) $request->input();
        if ($question_package_detail = DetailPaketSoal::where(['id_paket_soal' => $input->id_paket_soal, 'id_soal' => $input->id_soal])->first()) {
            $question_package_detail->delete();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menghapus paket Soal'
            ];
        }
    }
}
