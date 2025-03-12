<?php

namespace App\Http\Controllers\ManajemenFile;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;

use App\Models\SubCategoryFile;
use App\Models\CategoryFile;
use App\Models\CategoryFileRole;
use App\Models\FilePengguna;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use Validator;

class DataFileController extends BaseController
{

    public function viewDataFile(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data()->role_aktif->id_role;
        $category = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->where('category_file_role.id_role', $auth_data)
            ->select('category_file.*')->get();
        return view('manajemen-file/data-file/view-data-file', compact('auth_data', 'category'));
    }

    public function viewDataFileCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        $category = CategoryFile::find($id);
        $sub_category = SubCategoryFile::where('category_file_id', $id)->get();
        return view('manajemen-file/data-file/view-data-file-category', compact('auth_data', 'sub_category', 'category'));
    }

    public function viewDataFileSubCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

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
        return view('manajemen-file/data-file/view-data-file-sub-category', compact('auth_data', 'sub_category', 'files'));
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
        $auth_data = auth_data()->role_aktif->id_role;
        $category = CategoryFileRole::join('category_file', 'category_file.category_file_id', '=', 'category_file_role.category_file_id')
            ->where('category_file_role.id_role', $auth_data)
            ->select('category_file.*')->get();
        $sub_category = DB::table('sub_category_file')
            ->join('category_file', 'category_file.category_file_id', '=', 'sub_category_file.category_file_id')
            ->join('category_file_role', 'category_file_role.category_file_id', '=', 'category_file.category_file_id')
            ->where('category_file_role.id_role', '=', $auth_data)
            ->where('sub_category_file.deleted_at', '=', null)
            ->select('sub_category_file.*')->distinct()->get();
        return view('manajemen-file/data-file/add-data-file', compact('auth_data', 'category', 'sub_category'));
    }

    public function actionDataFile(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $id_pengguna = auth_data()->pengguna->id_pengguna;

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
                        $now = Carbon::now();
                        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                        $data = new FilePengguna;
                        $data->file_pengguna_id = $id;
                        $data->pengguna_id = $id_pengguna;
                        $data->judul = $filename;
                        $data->keterangan = $input->keterangan;
                        $data->sub_category_file_id = $input->sub_category_file_id;
                        $data->created_by = $id_pengguna;
                        $singkat_sekolah = auth_data()->sekolah_data->nm_singkat_sekolah;
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

                    $now = Carbon::now();
                    $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
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
                    'message' => 'Save File Pengguna Successfully'
                ];
            }
        }
    }

    public function downloadDataFile(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $file_pengguna = FilePengguna::where('file_pengguna_id', $id)->first();

        return Storage::disk('spaces')->download($file_pengguna->link_file, $file_pengguna->judul . "." . $file_pengguna->extension_file);
    }
}
