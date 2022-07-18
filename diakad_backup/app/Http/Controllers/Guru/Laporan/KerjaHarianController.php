<?php

namespace App\Http\Controllers\Guru\Laporan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Wisuda as Wisuda;
use App\Models\PeriodeWisuda as PeriodeWisuda;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\LaporanKerjaHarian;

use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;

class KerjaHarianController extends BaseController{

    public function viewKerjaHarian(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('guru/laporan/kerja-harian/view-kerja-harian',compact('auth_data'));
    }

    public function addKerjaHarian(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/laporan/kerja-harian/add-kerja-harian',compact('auth_data'));

    }


    public function editKerjaHarian($id, Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_kerja_harian = LaporanKerjaHarian::findOrFail($id);
        $tanggal = strftime( "%d %B %Y", strtotime($laporan_kerja_harian->tanggal));

        return view('guru/laporan/kerja-harian/edit-kerja-harian',compact('auth_data','laporan_kerja_harian','tanggal'));

    }

    public function previewFile($id,Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $laporan_kerja_harian = LaporanKerjaHarian::findOrFail($id);
        $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
        $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);

        return view('guru/laporan/kerja-harian/preview-file-kerja-harian',compact('auth_data','laporan_kerja_harian','link','ext'));

    }

    public function actionKerjaHarian(Request $request, $mode, $id = null){

        $input = (object) $request->input();

        $list_validator = [
                            'tanggal'         => 'required',
                            'uraian_kegiatan' => 'required',
                            'status' => 'required',
                            'lokasi' => 'required',
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

                $data                          = new LaporanKerjaHarian;
                $data->id_laporan_kerja_harian = $id;
                $data->id_role                 = $input->auth_data->role_aktif->id_role;
                $data->tanggal                 = date_format(date_create($input->tanggal),"Y-m-d");
                $data->lokasi                  = $input->lokasi;
                $data->uraian_kegiatan         = $input->uraian_kegiatan;
                $data->status                  = $input->status;
                $data->created_by              = $input->auth_data->pengguna->id_pengguna;

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
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru-tendik/'.$id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;

                    }

                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'laporan/kerja-harian',
                    'message' => 'Save Laporan Laporan Kerja Harian successfully'
                ];

            }

            elseif($mode == 'edit'){
 
                $data                        = LaporanKerjaHarian::find($id);
                $data->id_role               = $input->auth_data->role_aktif->id_role;
                $data->tanggal               = date_format(date_create($input->tanggal),"Y-m-d");
                $data->lokasi                  = $input->lokasi;
                $data->uraian_kegiatan       = $input->uraian_kegiatan;

                $data->status                = $input->status;
                $data->updated_by            = $input->auth_data->pengguna->id_pengguna;

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
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru-tendik/'.$id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;

                    }

                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'laporan/kerja-harian',
                    'message' => 'Update Laporan Laporan Kerja Harian  successfully'
                ];

            }

            elseif($mode == 'delete'){
                    
                $data               = LaporanKerjaHarian::find($id);
                $data->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $data->save();
                $data->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Laporan Kerja Harian successfully'
                ];

            }

        }
    }

    public function printKerjaHarian($start_date,$end_date,Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

//    dd($auth_data);
        $data['laporan'] = LaporanKerjaHarian::where('created_by',$auth_data->pengguna->id_pengguna)
                                    ->whereBetween('tanggal', [$start_date, $end_date])
                                    ->orderBy('tanggal','asc')->get();
   
         $data['biodata'] = $auth_data->pengguna->nm_pengguna;
        $data['tanggal'] = Carbon::today()->format('d-M-Y');
        $data['alamat'] =  $auth_data->sekolah_data->alamat_kecamatan;
        $data['kepala_sekolah'] =  $auth_data->sekolah_data->nm_kepala_sekolah;
        return view('guru/laporan/kerja-harian/print-kerja-harian',$data);

    }

    public function datatablesKerjaHarian(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanKerjaHarian::where('created_by',$input->auth_data->pengguna->id_pengguna)
                                    ->where('id_role',$input->auth_data->role_aktif->id_role)
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
                        'id'        => $item->id_laporan_kerja_harian,
                        'lokasi'    =>$item->lokasi,
                        'status'    => $item->status,
                        'file'      =>$file,
                        'note'      => $note
                    );
                    return $data;
                })
                ->make(true);
    }

}