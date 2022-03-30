<?php

namespace App\Http\Controllers\Akademik\ManajemenFile;

use Illuminate\Support\Facades\Hash;

use App\Models\CategoriFileGuru;
use App\Models\CategoriFileMGMP;
use App\Models\CategoryFileRole;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Pengguna;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;


class DataKategoriMGMPController extends BaseController
{

    public function viewDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data->role_aktif->id_pengguna;

        return view('akademik/manajemen-file/data-kategori/view-data-kategori', compact('auth_data'));
    }

    public function datatablesCategoryfile(Request $request)
    {
        //$list_data = CategoryFileGuru::all();

        // $input = (object) $request->input();
        // dd($input->auth_data);
        // $auth_data = $input->auth_data->role_aktif->id_pengguna;
        // $list_data = CategoryFileGuru::join('category_file_mgmp', 'category_file.category_file_mgmp_id', '=', 'category_file_guru.category_file_mgmp_id')
        //     ->where('category_file_guru.id_pengguna', $auth_data)
        //     ->select('category_file_mgmp.*')->get();

        ///////////////////////////

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $list_data = CategoriFileMGMP::all();
        $list_data = '';
        $list_data = CategoriFileMGMP::with('category_file_guru.pengguna')->get();
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->category_file_mgmp_id
                );
                return $data;
            })
            ->addColumn('guru', function ($item) {
                $data = [];
                if ($item->category_file_guru ?? false) {
                    foreach ($item->category_file_guru as $key => $value) {
                        $data[$key]['guru'] = $item->category_file_guru[$key]->pengguna->nm_pengguna;
                    }
                }
                return $data;
            })
            ->make(true);
    }
    public function addDataKategori(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pengguna = Pengguna::where('status_join_table', 2)->get();
        // $pengguna = Role::where('id_role', '<>', '14')->get();
        return view('akademik/manajemen-file/data-kategori/add-data-kategori', compact('auth_data', 'pengguna'));
    }

    public function editDataKategori(Request $request, $category_file_id = null)
    {
        // dd($category_file_id);
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kategori = CategoriFileGuru::where('category_file_mgmp_id', $category_file_id)->first();
        $allowed_role = CategoryFileRole::where('category_file_id', $data_kategori->category_file_mgmp_id)->pluck('id_role');
        $pengguna = Pengguna::with('role_pengguna')->where('status_join_table', 2)->get();

        return view('akademik/manajemen-file/data-kategori/edit-data-kategori', compact('auth_data', 'data_kategori', 'pengguna', 'allowed_role'));
    }

    //action POST
    public function actionDataKategori(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'category_file_name' => 'required',
            'category_file_explanation' => 'required',
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

                if ($input->allowed_guru ?? false) {
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $datakategori                               = new CategoriFileMGMP;
                    $datakategori->category_file_mgmp_id        = $id;
                    $datakategori->category_file_name           = $input->category_file_name;
                    $datakategori->category_file_explanation    = $input->category_file_explanation;
                    $datakategori->created_by                   = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->save();

                    foreach ($input->allowed_guru as $key => $value) {
                        $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $datakategori_guru = new CategoriFileGuru;
                        $datakategori_guru->category_file_guru_id = $uuid;
                        $datakategori_guru->id_pengguna = $input->allowed_guru[$key];
                        $datakategori_guru->category_file_mgmp_id =  $datakategori->category_file_mgmp_id;
                        //  $datakategori_guru->category_file_mgmp_id = $datakategori->category_file_mgmp_id;
                        $datakategori_guru->save();
                    }
                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'mpmp/data-kategori-mapel',
                        'message' => 'Save Data Kategori Succesfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Create Data Kategori'
                    ];
                }
            } elseif ($mode == 'edit') {
                if ($input->allowed_role ?? false) {
                    // make object to find id
                    $datakategori                               = CategoryFile::find($id);
                    $datakategori->category_file_name           = $input->category_file_name;
                    $datakategori->category_file_explanation    = $input->category_file_explanation;
                    $datakategori->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->updated_at                   = $now;
                    $datakategori->save();

                    // replace category file role
                    CategoryFileRole::where('category_file_id', $id)->delete();
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    foreach ($input->allowed_role as $key => $value) {
                        $uuid = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        $datakategori_role = new CategoryFileRole;
                        $datakategori_role->category_file_role_id = $uuid;
                        $datakategori_role->id_role = $input->allowed_role[$key];
                        $datakategori_role->category_file_id = $id;
                        $datakategori_role->save();
                    }

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'manajemen-file/data-kategori',
                        'message' => 'Update Data Kategori Succesfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Edit Data Kategori'
                    ];
                }
            } elseif ($mode == 'delete') {
                if (!CategoriFileMGMP::where('category_file_mgmp_id', $id)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed to Delete Data Kategori'
                    ];
                } else {
                    // make object to find id 
                    $datakategori                       = CategoriFileMGMP::where('category_file_mgmp_id', $id)->first();
                    $datakategori->deleted_by           = $input->auth_data->pengguna->id_pengguna;
                    $datakategori->save();

                    $datakategori->delete();

                    $datakategori_role = CategoryFileRole::where('category_file_id', $id)->delete();
                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Kategori succesfully'

                    ];
                }
            }
        }
    }
}
