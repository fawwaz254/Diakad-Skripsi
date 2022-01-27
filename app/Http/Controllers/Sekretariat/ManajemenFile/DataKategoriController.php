<?php

namespace App\Http\Controllers\Sekretariat\ManajemenFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use App\Models\CategoryFile;
use App\Models\CategoryFileRole;
use App\Models\Role;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;


use Auth;
use DB;
use Session;
use Validator;

class DataKategoriController extends BaseController
{
    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('sekretariat/manajemen-file/data-kategori/view-data-kategori', compact('auth_data'));
    }

    public function addDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $role = Role::all();
        return view('sekretariat/manajemen-file/data-kategori/add-data-kategori', compact('auth_data', 'role'));
    }

    public function datatablesCategoryfile(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = CategoryFile::all();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->category_file_id
                );
                return $data;
            })
            ->make(true);
    }

    public function editDataKategori($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kategori = CategoryFile::find($id);
        $role = Role::all();
        $allowed_role = CategoryFileRole::where('category_file_id', $data_kategori->category_file_id)->get();
        foreach ($allowed_role as $allowed_r) {
            echo $allowed_r->id_role;
        }

        return view('sekretariat/manajemen-file/data-kategori/edit-data-kategori', compact('auth_data', 'data_kategori', 'role', 'allowed_role'));
    }

    //action POST
    public function actionDataKategori(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'category_file_name' => 'required',
            'category_file_explanation' => 'required',
            // 'is_allowed_role' => 'required'
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
                $datakategori                               = new CategoryFile;
                $datakategori->category_file_id             = $id;
                $datakategori->category_file_name           = $input->category_file_name;
                $datakategori->category_file_explanation    = $input->category_file_explanation;
                $datakategori->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $datakategori->save();

                foreach ($input->is_allowed_role as $key => $value) {
                    $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori_role = new CategoryFileRole;
                    $datakategori_role->category_file_role_id = $uuid;
                    $datakategori_role->id_role = $input->is_allowed_role[$key];
                    $datakategori_role->category_file_id = $datakategori->category_file_id;
                    $datakategori_role->save();
                }
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-kategori',
                    'message' => 'Save Data Kategori Succesfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $datakategori                               = CategoryFile::find($id);
                $datakategori->category_file_name           = $input->category_file_name;
                $datakategori->category_file_explanation    = $input->category_file_explanation;
                $datakategori->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $datakategori->updated_at                   = $now;
                $datakategori->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-kategori',
                    'message' => 'Update Data Kategori Succesfully'
                ];
            } elseif ($mode == 'delete') {
                if (!CategoryFile::where('category_file_id', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Delete Data Kategori'
                    ];
                } else {
                    // make object to find id 
                    $datakategori                       = CategoryFile::find($id);
                    $datakategori->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->save();

                    $datakategori->delete();

                    $datakategori_role = CategoryFileRole::where('category_file_id', $id)->get();
                    foreach ($datakategori_role as $datakategori_r) {
                        $datakategori_role = CategoryFileRole::where('category_file_id', $datakategori_r->category_file_id)->first();
                        if ($datakategori_role) {
                            $datakategori_role->delete();
                        }
                    }
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Kategori succesfully'

                    ];
                }
            }
        }
    }
}
