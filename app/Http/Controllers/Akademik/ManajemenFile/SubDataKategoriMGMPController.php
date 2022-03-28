<?php

namespace App\Http\Controllers\Akademik\ManajemenFile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoriFileMGMP;
use App\Models\CategoriFileGuru;
use App\Models\SubCategoryFileMGMP;


use Carbon\Carbon;
use Yajra\Datatables\Datatables;


use Auth;
use DB;
use Session;
use Validator;


class SubDataKategoriMGMPController extends Controller
{
    public function viewDataKategori(Request $request){
    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    return view('akademik/manajemen-file/data-sub-kategori/view-data-sub-kategori', compact('auth_data'));
        
    }

    public function addSubDataKategori(Request $request)
    {
        #code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $category_file = CategoriFileMGMP::all();
// dd($category_file);
        return view('akademik/manajemen-file/data-sub-kategori/add-data-sub-kategori', compact('auth_data', 'category_file'));
   
   
    }

    public function datatablesSubCategoryfile(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // $list_data = SubCategoryFileMGMP::all();
        $list_data = SubCategoryFileMGMP::join('category_file_mgmp', 'category_file_mgmp.category_file_mgmp_id', '=', 'sub_category_file_mgmp.category_file_mgmp_id')
        ->select('category_file_mgmp.category_file_name as category_file_mgmp', 'sub_category_file_mgmp.*');

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->sub_category_file_id
                );
                return $data;
            })
            ->make(true);
    }

    public function actionSubDataKategori(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'sub_category_file_name' => 'required',
            'sub_category_file_explanation' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            //mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $subdatakategori                                = new SubCategoryFileMGMP();

                $subdatakategori->sub_category_file_id          = $id;
                $subdatakategori->sub_category_file_name        = $input->sub_category_file_name;
                $subdatakategori->sub_category_file_explanation = $input->sub_category_file_explanation;
                $subdatakategori->category_file_mgmp_id         = $input->category_file_id;
                $subdatakategori->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $subdatakategori->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'mpmp/data-sub-folder-kategori-mapel',
                    'message' => 'Save Data Kategori Succesfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $subdatakategori                                 = SubCategoryFile::find($id);
                $subdatakategori->sub_category_file_name         = $input->sub_category_file_name;
                $subdatakategori->sub_category_file_explanation  = $input->sub_category_file_explanation;
                $subdatakategori->category_file_id               = $input->category_file_id;
                $subdatakategori->updated_by                     = $input->auth_data->pengguna->id_pengguna;
                $subdatakategori->updated_at                     = $now;
                $subdatakategori->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-sub-kategori',
                    'message' => 'Update Data Kategori Succesfully'
                ];
            } elseif ($mode == 'delete') {
                if (!SubCategoryFile::where('sub_category_file_id', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Delete Data Kategori'
                    ];
                } else {
                    // make object to find id 
                    $subdatakategori                       = SubCategoryFile::find($id);
                    $subdatakategori->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                    $subdatakategori->save();

                    $subdatakategori->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Kategori succesfully'

                    ];
                }
            }
        }
    }

}
