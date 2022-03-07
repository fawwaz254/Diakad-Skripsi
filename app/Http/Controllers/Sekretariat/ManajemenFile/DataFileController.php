<?php

namespace App\Http\Controllers\Sekretariat\ManajemenFile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\SubCategoryFile;
use App\Models\CategoryFile;
use App\Models\SubategoryFile;
use App\Models\FilePengguna;
use App\Models\Pengguna;
use Auth;
use DB;
use Session;
use Validator;

class DataFileController extends BaseController
{

    public function viewDataFile(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $category = CategoryFile::all();
        return view('sekretariat/manajemen-file/data-file/view-data-file', compact('auth_data', 'category'));
    }

    public function viewDataFileCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $category = CategoryFile::find($id);
        $sub_category = SubCategoryFile::where('category_file_id', $id)->get();
        return view('sekretariat/manajemen-file/data-file/view-data-file-category', compact('auth_data', 'sub_category', 'category'));
    }

    public function viewDataFileSubCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $sub_category = SubCategoryFile::find($id);
        $data_file = Pengguna::Has('file_pengguna')
            ->with(["file_pengguna" => function ($q) use ($id) {
                return $q->where('sub_category_file_id', $id);
            }])->get();

        $files = collect([]);
        foreach ($data_file as $file) {
            if ($file->file_pengguna->isNotEmpty()) {
                $files->push($file);
            }
        }
        return view('sekretariat/manajemen-file/data-file/view-data-file-sub-category', compact('auth_data', 'sub_category', 'files'));
    }

    public function dropdownCategory(Request $request)
    {
        $input = (object) $request->input();
        $sub_category = SubCategoryFile::where('category_file_id', $input->category_file_id)->get();
        return $sub_category;
    }

    public function addDataFile(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $category = CategoryFile::all();
        $sub_category = SubCategoryFile::all();

        return view('sekretariat/manajemen-file/data-file/add-data-file', compact('auth_data', 'category', 'sub_category'));
    }

    public function actionDataFile(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $files = $request->file('file');
        $id_pengguna = $input->auth_data->pengguna->id_pengguna;

        if ($mode == 'delete-many') {
            $validator = Validator::make($request->all(), [
                'id_file' => 'required',
                'sub_category_file_id' => 'required'
            ]);
            if ($validator->fails()) {
                return [
                    'status' => 300, // FAILED
                    'message' => $validator->errors()->first()
                ];
            }
            foreach ($input->id_file as $id_file) {
                $file = FilePengguna::where('file_pengguna_id', $id_file)->first();
                $is_google_drive = filter_var($file->link_file, FILTER_VALIDATE_URL);
                if (!$is_google_drive) {
                    Storage::disk('spaces')->delete($file->link_file);
                }
                $file->delete();
            }
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'manajemen-file/data-file/sub-category/' . $input->sub_category_file_id,
                'message' => 'Delete File successfully'
            ];
        }

        if ($mode == 'delete') {
            $file = FilePengguna::where('file_pengguna_id', $input->id_file)->first();
            $is_google_drive = filter_var($file->link_file, FILTER_VALIDATE_URL);
            if (!$is_google_drive) {
                Storage::disk('spaces')->delete($file->link_file);
            }
            $file->delete();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'manajemen-file/data-file/sub-category/' . $input->sub_category_file_id,
                'message' => 'Delete File successfully'
            ];
        }
        $list_validator = [
            'keterangan'    => 'required',
            'file_from'     => 'required'
        ];

        $validator = Validator::make($request->all(), $list_validator);

        if ($validator->fails() && $mode != 'delete') {

            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {

                if ($input->file_from == 1) {

                    $validator = Validator::make($request->all(), [
                        'file.*' => 'mimes:pptx,docx,xlsx,xlsm,jpeg,jpg,png,pdf|required|max:10000'
                    ]);

                    if ($validator->fails() && $mode != 'delete') {

                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    $files = $request->file('file');
                    if (count($files) > 50) {
                        return [
                            'status' => 300, // FAILED
                            'message' => "Max 50 File"
                        ];
                    }

                    foreach ($files as $file) {
                        $now = Carbon::now(env('APP_TIMEZONE', ''));
                        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                        $data = new FilePengguna;
                        $data->file_pengguna_id = $id;
                        $data->pengguna_id = $id_pengguna;
                        $data->judul = $filename;
                        $data->keterangan = $input->keterangan;
                        $data->sub_category_file_id = $input->sub_category_file_id;
                        $data->created_by = $id_pengguna;
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $uploaded_file = Storage::disk('spaces')->putFile($singkat_sekolah . '/file-pengguna/' . $id, $file, 'public');
                        $data->link_file = $uploaded_file;
                        $data->extension_file = $file->extension();
                        $data->is_google_drive = 0;
                        $data->save();
                    }
                } else {
                    $validator = Validator::make($request->all(), [
                        'judul' => 'required',
                        'link_google_drive' => 'required'
                    ]);

                    if ($validator->fails() && $mode != 'delete') {

                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $data = new FilePengguna;
                    $data->file_pengguna_id = $id;
                    $data->pengguna_id = $id_pengguna;
                    $data->judul = $input->judul;
                    $data->keterangan = $input->keterangan;
                    $data->sub_category_file_id = $input->sub_category_file_id;
                    $data->created_by = $id_pengguna;

                    $data->link_file = $input->link_google_drive;
                    $data->is_google_drive = 1;
                    $data->save();
                }


                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-file',
                    'message' => 'Save File Pegguna successfully'
                ];
            }
        }
    }
}
