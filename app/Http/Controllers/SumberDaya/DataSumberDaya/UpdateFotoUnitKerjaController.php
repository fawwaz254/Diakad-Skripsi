<?php

namespace App\Http\Controllers\SumberDaya\DataSumberDaya;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;

class UpdateFotoUnitKerjaController extends Controller
{
    public function viewUpdateFoto(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_unit_kerja = UnitKerja::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();

        return view('sumber-daya/data-sumber-daya/update-foto/view-update-foto', compact('auth_data', 'list_unit_kerja'));
    }

    public function viewBatchUpdateFoto(Request $request)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/data-sumber-daya/update-foto/view-batch-upload-foto', compact('auth_data'));
    }

    public function actionViewUpdateFoto(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $validator = Validator::make($request->all(), [

      ]);

        if ($validator->fails()) {
            return [
              'status' => 300, // FAILED
              'message' => $validator->errors()->first()
          ];
        } else {
            return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/update-foto/view-detail-update-foto/'.$input->unit_kerja.'/'.$input->status_join_table
                ];
        }
    }

    public function viewDetailUpdateFoto(Request $request, $unit_kerja, $status_join_table)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_unit_kerja = UnitKerja::where('id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)->get();

    

        return view('sumber-daya/data-sumber-daya/update-foto/view-detail-update-foto', compact('auth_data', 'list_unit_kerja','unit_kerja','status_join_table'));
    }

    public function datatablesUpdateFoto(Request $request, $unit_kerja, $status_join_table)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // $siswa = LibSiswa::fetchDataSiswaDetail($auth_data, $id_jurusan, $id_kelas, $thn_masuk_siswa, $id_jalur, $id_status_pengguna);

        if($status_join_table == '0' && $unit_kerja == '0'   ){
            $pengguna = Pengguna::whereIn('status_join_table',[1,2])->with('status_pengguna','guru','staff')
            ->whereHas('status_pengguna', function ($query) {
                $query->where('nm_status_pengguna', '=', 'AKTIF');
            })->get();

        }else{
            if($status_join_table == '0'){
                // $pengguna = Pengguna::whereIn('status_join_table',[1,2])->with('status_pengguna','guru','staff')
                // ->whereHas('status_pengguna', function ($query) {
                //     $query->where('nm_status_pengguna', '=', 'AKTIF');
                // })->get();
                $pengguna = Pengguna::whereIn('status_join_table',[1,2])->with('status_pengguna','guru','staff')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('guru', function ($query) use($unit_kerja) {
                    $query->where('id_unit_kerja', '=', $unit_kerja);
                })->whereHas('staff', function ($query) use($unit_kerja) {
                    $query->where('id_unit_kerja', '=', $unit_kerja);
                })
                ->get();

                // $pengguna = Pengguna::whereIn('status_join_table',[1,2])
            
                // ->leftjoin('guru', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                // ->leftjoin('staff', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
             
                // ->where('guru.id_unit_kerja', '=', $unit_kerja)->orWhere('staff.id_unit_kerja','=', $unit_kerja)
                // ->get();

                // $pengguna = Pengguna::whereIn('status_join_table',[1,2])->with('status_pengguna','guru','staff')
                // ->whereHas('status_pengguna', function ($query) {
                //     $query->where('nm_status_pengguna', '=', 'AKTIF');
                // })->whereHas('staff', function ($query) use($unit_kerja) {
                //     $query->where('id_unit_kerja', '=', $unit_kerja);
                // })->get();

                // if($unit_kerja == '1'){
                   
                // }else{
                //     $pengguna = Pengguna::whereIn('status_join_table',[1,2])->with('status_pengguna','guru','staff')
                //     ->whereHas('status_pengguna', function ($query) {
                //         $query->where('nm_status_pengguna', '=', 'AKTIF');
                //     })->whereHas('guru', function ($query) use($unit_kerja) {
                //         $query->where('id_unit_kerja', '=', $unit_kerja);
                //     })->get();
                // }
            }else if($unit_kerja == '0')
            {
                $pengguna = Pengguna::where('status_join_table',$status_join_table)->with('status_pengguna','guru','staff')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })
                ->get();
            }
            else{
                $pengguna = Pengguna::where('status_join_table',$status_join_table)->with('status_pengguna','guru','staff')
                ->whereHas('status_pengguna', function ($query) {
                    $query->where('nm_status_pengguna', '=', 'AKTIF');
                })->whereHas('guru', function ($query) use($unit_kerja) {
                    $query->where('id_unit_kerja', '=', $unit_kerja);
                })->whereHas('staff', function ($query) use($unit_kerja) {
                    $query->where('id_unit_kerja', '=', $unit_kerja);
                })
                ->get();


            }
            // $pengguna = Pengguna::where('status_join_table',$status_join_table)->with('status_pengguna','guru','staff','guru.unit_kerja','staff.unit_kerja')
            // ->whereHas('status_pengguna', function ($query) {
            //     $query->where('nm_status_pengguna', '=', 'AKTIF');
            // })->get();
        }

        // dd($pengguna);
        return Datatables::of($pengguna)
                ->editColumn('path_foto_pengguna', function ($item) {
                    if (!empty($item->path_foto_pengguna)) {
                        return Storage::disk('spaces')->url($item->path_foto_pengguna);
                    } else {
                        return asset('media/blank-user.png');
                    }
                })->editColumn('role', function ($item) {
                    if (!empty($item->guru)) {
                        return 'Guru';
                    } else {
                        return 'Tendik';
                    }
                })->editColumn('unit_kerja', function ($item) {
                    if (!empty($item->guru)) {
                        return $item->guru->unit_kerja->nm_unit_kerja;
                    } else {
                        return $item->staff->unit_kerja->nm_unit_kerja;
                    }
                })
                // ->addColumn('thn_masuk_siswa', function ($item) {
                //     return $item->thn_masuk_siswa;
                // })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_pengguna
                    );
                    return $data;
                })
                ->make(true);
    }

    public function viewUpload(Request $request, $id_pengguna)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sumber-daya/data-sumber-daya/update-foto/view-upload-foto', compact('auth_data', 'id_pengguna'));
    }

    public function actionUpdateFoto(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [

        ]);

        if ($validator->fails() && $mode != 'delete' && $mode != 'upload' && $mode != 'delete-file') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'upload') {
                $validator = Validator::make($request->all(), [
                        'file' => 'file|required|max:2048|mimes:jpg,jpeg,bmp,png'
                    ]);


                $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

                $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/penguna/'.$id, request()->file, 'public');
                
                //save file name to database
                $siswa                          = Pengguna::find($id);
                $siswa->path_foto_pengguna      = $file;
                $siswa->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $siswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'message' => 'Sukses Mengunggah Foto', // SUCCESS AND LOAD CONTENT
                    'path' => 'data-sumber-daya/update-foto/upload/'.$id
                ];
            }
        }
    }

    public function actionBatchUploadFoto(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'file' => 'file|required|max:2048|mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        $upload_image = $request->file('file');
        $filename = pathinfo($upload_image->getClientOriginalName(), PATHINFO_FILENAME);

        if($pengguna = Pengguna::where('username', $filename)->first()){
            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
    
            $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/pengguna/'.$pengguna->id_pengguna, $upload_image, 'public');
            
            //save file name to database
            $pengguna                          = Pengguna::find($pengguna->id_pengguna);
            $pengguna->path_foto_pengguna      = $file;
            $pengguna->updated_by              = $input->auth_data->pengguna->id_pengguna;
            $pengguna->save();

            return Response::json('success', 200);
        }else{
            return Response::json('error '.$filename, 400);
        }
    }
}
