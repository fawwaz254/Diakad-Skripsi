<?php

namespace App\Http\Controllers\Guru\LaporanKerjaHarianMGMP;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoriFileGuru;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\CategoriFileMGMP;
use App\Models\LaporanKerjaHarianMGMP;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Carbon\Carbon;
use Predis\Response\Status;

class LaporanKerjaHarianController extends Controller
{
    public function viewLaporanHarianMGMP(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/mgmp/laporan-harian-mgmp/view-data-laporan-harian-mgmp',compact('auth_data'));

    }
    public function addLaporanHarianMGMP(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $waktu = Carbon::today()->toDateString();
        $mapel = CategoriFileGuru::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)->with('categori_file_mgmp')->get();

        return view('guru/mgmp/laporan-harian-mgmp/add-data-laporan-harian-mgmp',compact('auth_data','mapel','waktu'));

    }
    public function actionLaporanHarianMGMP(Request $request, $mode = 0,$id=0){
        $input = (object) $request->input();

        $list_validator = [
                            'tanggal'           => 'required',
                            'mata_pelajaran'   => 'required',
                            'jenis'            => 'required',
                            'status'            => 'required',
                            'keterangan'         => 'required',
                        ];

        $validator = Validator::make($request->all(), $list_validator);
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if($mode == 'add') {

                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $data                               = new LaporanKerjaHarianMGMP();
                $data->id_laporan_kerja_harian_mgmp = $id;
                $data->id_role                      = $input->auth_data->role_aktif->id_role;
                $data->tanggal                      = date_format(date_create($input->tanggal),"Y-m-d");
                $data->jenis                        = $input->jenis;
                $data->mapel                        = $input->mata_pelajaran;
                $data->keterangan_progres           = $input->keterangan;
                $data->status                       = $input->status;
        
                $data->id_pengguna                  = $input->auth_data->pengguna->id_pengguna;
                $data->created_by                   = $input->auth_data->pengguna->id_pengguna;

                if($request->hasFile('file')){

                    $validator = Validator::make($request->all(),[
                        'file' => 'mimes:pptx,docx,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    else{
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru/'.$id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;

                    }

                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'mgmp/laporan-harian-mgmp',
                    'message' => 'Save Laporan Laporan Kerja Harian successfully'
                ];

            }

//             elseif($mode == 'edit'){
 
//                 // $data                        = LaporanKerjaHarian::find($id);
//                 // $data->id_role               = $input->auth_data->role_aktif->id_role;
//                 // $data->tanggal               = date_format(date_create($input->tanggal),"Y-m-d");
//                 // $data->lokasi                  = $input->lokasi;
//                 // $data->uraian_kegiatan       = $input->uraian_kegiatan;

//                 // $data->status                = $input->status;
//                 // $data->updated_by            = $input->auth_data->pengguna->id_pengguna;
// dd($input);
//                 if($request->hasFile('file')){ 

//                     $validator = Validator::make($request->all(),[
//                         'file' => 'mimes:pptx,docx,xlsx,jpeg,jpg,png,pdf|required|max:5120'
//                     ]);
        
//                     if($validator->fails()) {
//                         return [
//                             'status' => 300, // FAILED
//                             'message' => $validator->errors()->first()
//                         ];
//                     }

//                     else{

//                         $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
//                         $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru-tendik/'.$id, request()->file, 'public');
//                         $data->path_file = $file;

//                         $upload = $request->file('file');
//                         $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
//                         $data->nm_file = $filename;

//                     }

//                 }

//                 $data->save();

//                 return [
//                     'status' => 202, // SUCCESS AND LOAD CONTENT
//                     'path' => 'laporan/kerja-harian',
//                     'message' => 'Update Laporan Laporan Kerja Harian  successfully'
//                 ];

//             }

//             elseif($mode == 'delete'){

//                 $data               = LaporanKerjaHarian::find($id);
//                 $data->deleted_by   = $input->auth_data->pengguna->id_pengguna;
//                 $data->save();
//                 $data->delete();

//                 return [
//                     'status' => 203, // SUCCESS AND LOAD TABLE
//                     'message' => 'Delete Laporan Kerja Harian successfully'
//                 ];

//             }

        }
    }

    public function datatablesKerjaHarianMGMP(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanKerjaHarianMGMP::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)
                                    ->where('id_role',$input->auth_data->role_aktif->id_role)->with('mapel')
                                    ->get();

        return Datatables::of($list_data)
                ->editColumn('tanggal',function($item){
                    return Carbon::parse($item->tanggal)->format('d M Y');
                })
                ->addColumn('action', function($item){
                    if($item->path_file){
                        $file =  Storage::disk('spaces')->url($item->path_file);
                        $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
                        if($ext=='pdf'||$ext=='doc'||$ext=='docx'){
                            $note = 'file';
                        }
                        else{
                            $note= 'image';
                        }
                    }
                    else{
                        $file = null;
                        $note = null;
                    }

                    $data = array(
                        'id'        => $item->id_laporan_kerja_harian_mgmp,
                        'jenis'    =>$item->jenis,
                        'status'    => $item->status,
                        'file'      =>$file,
                        'note'      => $item->mapel,
                    );
                    return $data;
                })
                ->make(true);
    }


    public function viewLaporanKelompokMGMP(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/mgmp/laporan-harian-mgmp/view-data-laporan-harian-mgmp-kelompok',compact('auth_data'));

    }

    public function datatablesKerjaHarianKelompokMGMP(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanKerjaHarianMGMP::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)
                                    ->where('id_role',$input->auth_data->role_aktif->id_role)
                                    ->where('status',1)
                                    ->with('mapel','pengguna')
                                    ->get();

        return Datatables::of($list_data)
                ->editColumn('tanggal',function($item){
                    return Carbon::parse($item->tanggal)->format('d M Y');
                })
                ->addColumn('action', function($item){
                    if($item->path_file){
                        $file =  Storage::disk('spaces')->url($item->path_file);
                        $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
                        if($ext=='pdf'||$ext=='doc'||$ext=='docx'){
                            $note = 'file';
                        }
                        else{
                            $note= 'image';
                        }
                    }
                    else{
                        $file = null;
                        $note = null;
                    }

                    $data = array(
                        'id'        => $item->id_laporan_kerja_harian_mgmp,
                        'jenis'    =>$item->jenis,
                        'status'    => $item->status,
                        'file'      =>$file,
                        'note'      => $item->mapel,
                    );
                    return $data;
                })
                ->make(true);
    }
    }


