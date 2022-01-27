<?php

namespace App\Http\Controllers\ManajemenFile;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\SubCategoryFile;
use App\Models\CategoryFileRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

use Validator;

class SubDataKategoriController extends BaseController
{
    public function viewSubDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('manajemen-file/data-sub-kategori/view-data-sub-kategori', compact('auth_data'));
    }

    public function datatablesSubCategoryfile(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_role;
        // $list_data = SubCategoryFile::join('category_file', 'category_file.category_file_id', '=', 'sub_category_file.category_file_id')
        //     ->select('category_file.category_file_name as category_file', 'sub_category_file.*');
        $list_data = DB::table('sub_category_file')
            ->join('category_file', 'category_file.category_file_id', '=', 'sub_category_file.category_file_id')
            ->join('category_file_role', 'category_file_role.category_file_id', '=', 'category_file.category_file_id')
            ->where('category_file_role.id_role', '=', $auth_data)
            ->select('category_file.category_file_name as category_file', 'sub_category_file.*');
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->sub_category_file_id
                );
                return $data;
            })
            ->make(true);
    }

    public function addSubDataKategori(Request $request)
    {
        #code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_role;
        $category_file = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->where('category_file_role.id_role', $auth_data)
            ->select('category_file.*')->get();

        return view('manajemen-file/data-sub-kategori/add-data-sub-kategori', compact('auth_data', 'category_file'));
    }


    public function editSubDataKategori($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_role;
        $sub_data_kategori = SubCategoryFile::find($id);
        $data_kategori = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->where('category_file_role.id_role', $auth_data)
            ->select('category_file.*')->get();
        return view('manajemen-file/data-sub-kategori/edit-data-sub-kategori', compact('auth_data', 'data_kategori', 'sub_data_kategori'));
    }

    //action POST
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

                $subdatakategori                                = new SubCategoryFile;

                $subdatakategori->sub_category_file_id          = $id;
                $subdatakategori->sub_category_file_name        = $input->sub_category_file_name;
                $subdatakategori->sub_category_file_explanation = $input->sub_category_file_explanation;
                $subdatakategori->category_file_id              = $input->category_file_id;
                $subdatakategori->created_by                    = $input->auth_data->pengguna->id_pengguna;
                $subdatakategori->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-sub-kategori',
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
