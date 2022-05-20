<?php

namespace App\Http\Controllers\Guru\ELearningSoal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KategoriSoal;

use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Validator;
use Carbon\Carbon;

class KategoriSoalController extends Controller
{
    public function indexList(Request $request){
        return view('guru/e-learning-soal/organizer/question-category');
    }

    public function indexManage(Request $request, $id_kategori_soal = 0){
        if(!empty($id_kategori_soal)){
            $item = KategoriSoal::find($id_kategori_soal);
        }else{
            $item = null;
        }
        return view('guru/e-learning-soal/organizer/question-category-manage', compact('item'));
    }

    public function commonList(Request $request){
        $list_data = KategoriSoal::selectRaw('id_kategori_soal,
                                                    nama,
                                                    nilai_benar,
                                                    nilai_salah,
                                                    nilai_kosong');

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kategori_soal
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionSave(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nilai_benar' => 'required',
            'nilai_salah' => 'required',
            'nilai_kosong' => 'required'
        ]);

        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }


        $input = (object) $request->input();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        if($kategori_soal = KategoriSoal::find($input->id_kategori_soal)){
            $kategori_soal->nama = $input->nama;
            $kategori_soal->nilai_benar = $input->nilai_benar;
            $kategori_soal->nilai_salah = $input->nilai_salah;
            $kategori_soal->nilai_kosong = $input->nilai_kosong;
            $kategori_soal->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/kategori-soal',
                'message' => 'Edit data Kategori berhasil'
            ];
            // return back()->with('toast', 'Your changed save successfully');
        }else{
            $kategori_soal = new KategoriSoal();
            $kategori_soal->id_kategori_soal =  $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
            $kategori_soal->nama = $input->nama;
            $kategori_soal->nilai_benar = $input->nilai_benar;
            $kategori_soal->nilai_salah = $input->nilai_salah;
            $kategori_soal->nilai_kosong= $input->nilai_kosong;
            $kategori_soal->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'e-learning-soal/kategori-soal',
                'message' => 'Tambah data Kategori berhasil'
            ];
        }
    }

    public function actionDelete(Request $request){
        $input = (object) $request->input();

        if($kategori_soal = KategoriSoal::find($input->id_kategori_soal)){
            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Berhasil Menghapus Kategori Soal'
            ];
        }else{
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Gagal Menghapus Kategori Soal'
            ];
        }
    }
}
