<?php

namespace App\Http\Controllers\MGMP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CategoriFileGuru;
use App\Models\CategoriFileMGMP;
use App\Models\FilePengguna;
use App\Models\SubCategoryFileMGMP;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class DataFileController extends Controller
{
    public function viewDataFile(Request $request)
    {
        # code...
        $input = (object) $request->input();
        // $auth_data = $input->auth_data->role_aktif->id_role;
        $pengguna = $input->auth_data->pengguna->id_pengguna;
      
    
        // $category = CategoriFileGuru::where('id_pengguna' , $pengguna)->get();
        $category = CategoriFileGuru::join('category_file_mgmp', 'category_file_mgmp.category_file_mgmp_id', '=', 'category_file_guru.category_file_mgmp_id')
            ->where('category_file_guru.id_pengguna', $pengguna)
            ->select('category_file_mgmp.*')->get();
 
        return view('guru/mgmp/data-file/view-data-file', compact('auth_data', 'category'));
    }

    public function viewDataFileCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $category = CategoriFileMGMP::find($id);
        $sub_category = SubCategoryFileMGMP::where('category_file_mgmp_id', $id)->get();
        return view('guru/mgmp/data-file/view-data-file-category', compact('auth_data', 'sub_category', 'category'));
    }

    public function viewDataFileSubCategory(Request $request, $id)
    {

        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
      
        $sub_category = SubCategoryFileMGMP::find($id);
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
      
        return view('guru/mgmp/data-file/view-data-file-sub-category', compact('auth_data', 'sub_category', 'data_file','files'));
    }

    public function dropdownCategory(Request $request)
    {
        $input = (object) $request->input();
        $sub_category = SubCategoryFileMGMP::where('category_file_mgmp_id', $input->category_file_mgmp_id)->get();
        return $sub_category;
    }

    public function addDataFile(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data->pengguna->id_pengguna;
    
        $category = CategoriFileGuru::join('category_file_mgmp', 'category_file_mgmp.category_file_mgmp_id', '=', 'category_file_guru.category_file_mgmp_id')
            ->where('category_file_guru.id_pengguna', $auth_data)
            ->select('category_file_mgmp.*')->get();
            
        $sub_category = DB::table('sub_category_file_mgmp')
            ->join('category_file_mgmp', 'category_file_mgmp.category_file_mgmp_id', '=', 'sub_category_file_mgmp.category_file_mgmp_id')
            ->join('category_file_guru', 'category_file_guru.category_file_mgmp_id', '=', 'category_file_mgmp.category_file_mgmp_id')
            ->where('category_file_guru.id_pengguna', '=', $auth_data)
            ->where('sub_category_file_mgmp.deleted_at', '=', null)
            ->select('sub_category_file_mgmp.*')->distinct()->get();
      
        return view('guru/mgmp/data-file/add-data-file', compact('auth_data', 'category', 'sub_category'));
    }

    public function actionDataFile(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

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
                'path' => 'mgmp/data-file-mapel/sub-category/' . $input->sub_category_file_id,
                'message' => 'Delete File Successfully'
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
                'path' => 'mgmp/data-file-mapel/sub-category/' . $input->sub_category_file_id,
                'message' => 'Delete File Successfully'
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
                    'path' => 'mgmp/data-file-mapel/sub-category/'.$input->sub_category_file_id,
                    'message' => 'Save File Pegguna Successfully'
                ];
            }
        }
    }
}
