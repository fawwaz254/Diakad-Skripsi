<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class InputPertanyaanController extends BaseController
{

    public function viewInputPertanyaan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('humas/kegiatan-harian/input-pertanyaan/view-input-pertanyaan', compact('auth_data'));
    }

    public function viewAddEditInputPertanyaan(Request $request, $id = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        if (!empty($id)) {
            $item = KegiatanHarianPertanyaan::find($id);
        } else {
            $item = null;
        }

        $data_kegiatan_harian_kategori = KegiatanHarianKategori::with('kegiatan_harian')->orderBy('nm_kegiatan_harian_kategori')->get();

        return view('humas/kegiatan-harian/input-pertanyaan/view-add-edit-input-pertanyaan', compact('auth_data', 'item', 'data_kegiatan_harian_kategori'));
    }

    public function viewInputJawaban(Request $request, $id_kegiatan_harian)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $item = KegiatanHarianPertanyaan::find($id_kegiatan_harian);

        return view('humas/kegiatan-harian/input-pertanyaan/view-input-jawaban', compact('auth_data', 'item'));
    }

    public function viewAddEditInputJawaban(Request $request, $id_kegiatan_harian_pertanyaan, $id = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        if (!empty($id)) {
            $item = KegiatanHarianJawaban::find($id);
        } else {
            $item = null;
        }

        return view('humas/kegiatan-harian/input-pertanyaan/view-add-edit-input-jawaban', compact('auth_data', 'item', 'id_kegiatan_harian_pertanyaan'));
    }

    public function showDatatablesInputPertanyaan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = KegiatanHarianPertanyaan::with('kategori_pertanyaan', 'jawaban');

        return Datatables::of($list_data)
            ->addColumn('jawaban', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_harian_pertanyaan,
                    'count' => $item->jawaban->count()
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_harian_pertanyaan
                );
                return $data;
            })
            ->make(true);
    }

    public function showDatatablesInputJawaban(Request $request, $id_kegiatan_harian_pertanyaan)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = KegiatanHarianJawaban::where('id_kegiatan_harian_pertanyaan', $id_kegiatan_harian_pertanyaan);

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_harian_jawaban
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputPertanyaan(Request $request, $mode, $id)
    {
        $input = (object) $request->input();

        // dd($mode);

        $validator = Validator::make($request->all(), [
            'id_kegiatan_harian_kategori'           => 'required',
            'show_order'              => 'required',
            /*'id_jabatan_pegawai'    => 'required',*/
            'isi_pertanyaan'         => 'required',
            // 'jenis_jabatan'         => 'required',
            // 'id_status_pengguna'    => 'required'
        ]);

        // if($validator->fails() && $mode != 'delete') {
        //     return [
        //         'status' => 300, // FAILED
        //         'message' => $validator->errors()->first()
        //     ];
        // }

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $kegiatan_harian_pertanyaan                        = new KegiatanHarianPertanyaan;
                $kegiatan_harian_pertanyaan->id_kegiatan_harian_pertanyaan  = $id;
                $kegiatan_harian_pertanyaan->id_kegiatan_harian_kategori    = $input->id_kegiatan_harian_kategori;
                $kegiatan_harian_pertanyaan->show_order                     = $input->show_order;
                $kegiatan_harian_pertanyaan->isi_pertanyaan                 = $input->isi_pertanyaan;
                $kegiatan_harian_pertanyaan->created_by                     = auth_data()->pengguna->id_pengguna;
                $kegiatan_harian_pertanyaan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-pertanyaan',
                    'message' => 'Save Successfully'
                ];
            } elseif ($mode == 'edit') {
                $kegiatan_harian_pertanyaan                                 = KegiatanHarianPertanyaan::find($input->id_kegiatan_harian_pertanyaan);
                $kegiatan_harian_pertanyaan->id_kegiatan_harian_kategori    = $input->id_kegiatan_harian_kategori;
                $kegiatan_harian_pertanyaan->show_order                     = $input->show_order;
                $kegiatan_harian_pertanyaan->isi_pertanyaan                 = $input->isi_pertanyaan;
                $kegiatan_harian_pertanyaan->updated_by                     = auth_data()->pengguna->id_pengguna;
                $kegiatan_harian_pertanyaan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-pertanyaan',
                    'message' => 'Update Successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($subkategoriPemasukan = KegiatanHarianJawaban::where('id_kegiatan_harian_pertanyaan', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kategori Pemasukan'
                    ];
                } else {
                    // make object to find id
                    $kategoriPemasukan               = KegiatanHarianPertanyaan::find($id);
                    $kategoriPemasukan->deleted_by   = auth_data()->pengguna->id_pengguna;
                    $kategoriPemasukan->save();

                    $kategoriPemasukan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Pertanyaan Successfully'
                    ];
                }
            }
        }
    }


    public function actionInputJawaban(Request $request, $id_kegiatan_harian_pertanyaan, $mode)
    {
        $input = (object) $request->input();

        switch ($mode) {
            case 'add':
                $syarat = [
                    'bobot_jawaban' => 'required',
                    'warna_keadaan' => 'required',
                    'show_order' => 'required',
                    'isi_jawaban' => 'required',
                ];
                break;
            case 'edit':
                $syarat = [
                    'id_kegiatan_harian_jawaban' => 'required',
                    'bobot_jawaban' => 'required',
                    'warna_keadaan' => 'required',
                    'show_order' => 'required',
                    'isi_jawaban' => 'required',
                ];
                break;
            case 'delete':
                $syarat = [
                    'id_kegiatan_harian_jawaban' => 'required',
                ];
                break;
            default:
                return;
        }

        $validator = Validator::make($request->all(), $syarat);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $kegiatan_harian_jawaban                                = new KegiatanHarianJawaban;
                $kegiatan_harian_jawaban->id_kegiatan_harian_jawaban    = $id;
                $kegiatan_harian_jawaban->id_kegiatan_harian_pertanyaan = $id_kegiatan_harian_pertanyaan;
                $kegiatan_harian_jawaban->bobot_jawaban                 = $input->bobot_jawaban;
                $kegiatan_harian_jawaban->warna_keadaan                 = $input->warna_keadaan;
                $kegiatan_harian_jawaban->show_order                    = $input->show_order;
                $kegiatan_harian_jawaban->isi_jawaban                   = $input->isi_jawaban;
                $kegiatan_harian_jawaban->created_by                    = auth_data()->pengguna->id_pengguna;
                $kegiatan_harian_jawaban->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-pertanyaan/jawaban/detail/' . $kegiatan_harian_jawaban->id_kegiatan_harian_pertanyaan,
                    'message' => 'Save Successfully'
                ];
            } elseif ($mode == 'edit') {
                $kegiatan_harian_jawaban                                 = KegiatanHarianJawaban::find($input->id_kegiatan_harian_jawaban);
                $kegiatan_harian_jawaban->bobot_jawaban                  = $input->bobot_jawaban;
                $kegiatan_harian_jawaban->warna_keadaan                  = $input->warna_keadaan;
                $kegiatan_harian_jawaban->show_order                     = $input->show_order;
                $kegiatan_harian_jawaban->isi_jawaban                    = $input->isi_jawaban;
                $kegiatan_harian_jawaban->updated_by                     = auth_data()->pengguna->id_pengguna;
                $kegiatan_harian_jawaban->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-pertanyaan/jawaban/detail/' . $kegiatan_harian_jawaban->id_kegiatan_harian_pertanyaan,
                    'message' => 'Update Successfully'
                ];
            } elseif ($mode == 'delete') {
                $kegiatan_harian_jawaban                = KegiatanHarianJawaban::find($input->id_kegiatan_harian_jawaban);
                $kegiatan_harian_jawaban->deleted_at     = $now;
                $kegiatan_harian_jawaban->deleted_by    = auth_data()->pengguna->id_pengguna;
                $kegiatan_harian_jawaban->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Successfully'
                ];
            }
        }
    }
}
