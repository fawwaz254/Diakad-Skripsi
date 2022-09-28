<?php

namespace App\Http\Controllers\Humas\KegiatanHarian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KegiatanHarian;
use App\Models\KegiatanHarianKategori;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class InputKegiatanController extends BaseController{

    public function viewInputKegiatan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('humas/kegiatan-harian/input-kegiatan/view-input-kegiatan',compact('auth_data'));
    }

    public function viewAddEditInputKegiatan(Request $request, $id = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(!empty($id)){
            $item = KegiatanHarian::find($id);
        }else{
            $item = null;
        }

        return view('humas/kegiatan-harian/input-kegiatan/view-add-edit-input-kegiatan',compact('auth_data','item'));

    }

    public function viewInputKategoriPertanyaan(Request $request, $id_kegiatan_harian){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = KegiatanHarian::find($id_kegiatan_harian);

    	return view('humas/kegiatan-harian/input-kegiatan/view-input-kategori-pertanyaan',compact('auth_data', 'item'));
    }

    public function viewAddEditInputKategoriPertanyaan(Request $request, $id_kegiatan_harian, $id = null){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if(!empty($id)){
            $item = KegiatanHarianKategori::find($id);
        }else{
            $item = null;
        }

        return view('humas/kegiatan-harian/input-kegiatan/view-add-edit-input-kategori-pertanyaan',compact('auth_data','item', 'id_kegiatan_harian'));

    }

    public function showDatatablesInputKegiatan(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = KegiatanHarian::with('kategori_pertanyaan');

        return Datatables::of($list_data)
                ->editColumn('is_aktif', function($item){
                    return $item->is_aktif_to_text();
                })
                ->addColumn('kategori', function($item){
                    $data = array(
                        'id' => $item->id_kegiatan_harian,
                        'count' => $item->kategori_pertanyaan->count()
                    );
                    return $data;
                })
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kegiatan_harian
                    );
                    return $data;
                })
                ->make(true);
    }

    public function showDatatablesInputKategoriPertanyaan(Request $request, $id_kegiatan_harian){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = KegiatanHarianKategori::where('id_kegiatan_harian', $id_kegiatan_harian);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_kegiatan_harian_kategori
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionInputKegiatan(Request $request, $mode, $id){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kegiatan_harian'  => 'required',
            'is_aktif'           => 'required',
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $kegiatan_harian                        = new KegiatanHarian;
                $kegiatan_harian->id_kegiatan_harian    = $id;
                $kegiatan_harian->nm_kegiatan_harian    = $input->nm_kegiatan_harian;
                $kegiatan_harian->is_aktif              = $input->is_aktif;
                $kegiatan_harian->created_by            = $input->auth_data->pengguna->id_pengguna;
                $kegiatan_harian->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-kegiatan',
                    'message' => 'Save successfully'
                ];
            }
            elseif($mode == 'edit'){
                $kegiatan_harian                        = KegiatanHarian::find($id);
                $kegiatan_harian->nm_kegiatan_harian    = $input->nm_kegiatan_harian;
                $kegiatan_harian->is_aktif              = $input->is_aktif;
                $kegiatan_harian->updated_by            = $input->auth_data->pengguna->id_pengguna;
                $kegiatan_harian->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-kegiatan',
                    'message' => 'Update successfully'
                ];
            }
            elseif($mode == 'delete'){
                if($subkategoriPemasukan = KegiatanHarianKategori::where('id_kegiatan_harian',$id)->first()){
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Kategori Pemasukan'
                    ]; 
                }
                else{
                    // make object to find id
                    $kategoriPemasukan               = KegiatanHarian::find($id);
                    $kategoriPemasukan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kategoriPemasukan->save();

                    $kategoriPemasukan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Kategori Pemasukan successfully'
                    ];
                }
            }
        }
    }


    public function actionInputKategoriPertanyaan(Request $request, $mode,  $id){
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_kegiatan_harian_kategori'  => 'required'
        ]);

        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $kegiatan_harian                                 = new KegiatanHarianKategori;
                $kegiatan_harian->id_kegiatan_harian_kategori    = $id;
                $kegiatan_harian->id_kegiatan_harian             = $input->id_kegiatan_harian;
                $kegiatan_harian->nm_kegiatan_harian_kategori    = $input->nm_kegiatan_harian_kategori;
                $kegiatan_harian->created_by                     = $input->auth_data->pengguna->id_pengguna;
                $kegiatan_harian->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-kegiatan/kategori-pertanyaan/detail/'.$kegiatan_harian->id_kegiatan_harian,
                    'message' => 'Save successfully'
                ];
            }
            elseif($mode == 'edit'){
                $kegiatan_harian                                 = KegiatanHarianKategori::find($input->id_kegiatan_harian_kategori);
                $kegiatan_harian->nm_kegiatan_harian_kategori    = $input->nm_kegiatan_harian_kategori;
                $kegiatan_harian->updated_by                     = $input->auth_data->pengguna->id_pengguna;
                $kegiatan_harian->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'kegiatan-harian/input-kegiatan/kategori-pertanyaan/detail/'.$kegiatan_harian->id_kegiatan_harian,
                    'message' => 'Update successfully'
                ];
            }
            elseif($mode == 'delete'){
                // if($subkategoriPemasukan = PemasukanBiayaSubkategori::where('id_pemasukan_biaya_kategori',$id)->first()){
                //     return [
                //         'status' => 300, // SUCCESS AND LOAD TABLE
                //         'message' => 'Failed To Delete Kategori Pemasukan'
                //     ]; 
                // }
                // else{
                    // make object to find id
                    $kategoriPemasukan               = KegiatanHarianKategori::find($id);
                    $kategoriPemasukan->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $kategoriPemasukan->save();

                    $kategoriPemasukan->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete kegiatan harian successfully'
                    ];
                }
            // }
        }
    }


}