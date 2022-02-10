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
        $file = Pengguna::Has('file_pengguna')
            ->with(["file_pengguna" => function ($q) use ($id) {
                return $q->where('sub_category_file_id', $id);
            }])->get();
        return view('sekretariat/manajemen-file/data-file/view-data-file-sub-category', compact('auth_data', 'sub_category', 'file'));
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
        $id_pengguna = $input->auth_data->pengguna->id_pengguna;
        if ($mode == 'delete') {
            $is_google_drive = filter_var($input->link_file, FILTER_VALIDATE_URL);
            if (!$is_google_drive) {
                Storage::disk('spaces')->delete($input->link_file);
            }
            FilePengguna::destroy($id);
            return;
        }
        $list_validator = [
            'judul'         => 'required',
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

            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {

                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $data = new FilePengguna;
                $data->file_pengguna_id = $id;
                $data->pengguna_id = $id_pengguna;
                $data->judul = $input->judul;
                $data->keterangan = $input->keterangan;
                $data->sub_category_file_id = $input->sub_category_file_id;
                $data->created_by = $id_pengguna;

                if ($input->file_from == 1) {

                    $validator = Validator::make($request->all(), [
                        'file' => 'mimes:pptx,docx,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if ($validator->fails() && $mode != 'delete') {

                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }


                    $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                    $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/file-pengguna/' . $id, request()->file, 'public');
                    $data->link_file = $file;

                    $data->extension_file = $request->file('file')->extension();

                    $data->is_google_drive = 0;
                } else {

                    $validator = Validator::make($request->all(), [
                        'link_google_drive' => 'required'
                    ]);

                    if ($validator->fails() && $mode != 'delete') {

                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    $data->link_file = $input->link_google_drive;
                    $data->is_google_drive = 1;
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-file/data-file',
                    'message' => 'Save File Pegguna successfully'
                ];
            }
        }
    }
}
