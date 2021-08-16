<?php

namespace App\Http\Controllers\Guru\ELearning;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru;
use App\Models\MateriAjar;
use App\Models\MateriAjarFile;
use App\Models\MataPelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;

use Auth;
use DB;
use Session;
use Validator;

class ManajemenMateriAjarController extends BaseController
{

    public function viewManajemenMateriAjar(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/e-learning/manajemen-materi-ajar/view-manajemen-materi-ajar',compact('auth_data'));
    }

    public function addManajemenMateriAjar(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data['list_mapel'] = MataPelajaran::all();
        $data['list_jurusan'] = Jurusan::all();
        $data['list_tingkat'] = Kelas::select('tingkat')->groupBy('tingkat')->get();

        return view('guru/e-learning/manajemen-materi-ajar/add-manajemen-materi-ajar',compact('auth_data'),$data);
    }

    public function editManajemenMateriAjar(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data['list_mapel'] = MataPelajaran::all();
        $data['list_jurusan'] = Jurusan::all();
        $data['list_tingkat'] = Kelas::select('tingkat')->groupBy('tingkat')->get();

        $materi_ajar = MateriAjar::with('materi_ajar_file')->find($id);
        return view('guru/e-learning/manajemen-materi-ajar/edit-manajemen-materi-ajar',compact('auth_data','materi_ajar'),$data);

    }

    public function actionManajemenMateriAjar(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if($mode=='add'){
            $validator = Validator::make($request->all(), [
                'judul_materi'      => 'required',
                'id_mata_pelajaran' => 'required',
                'id_jurusan'        => 'required',
                'tingkat'           => 'required',
                'status'            => 'required',
                'nm_file'           => 'required|array',
                'nm_file.*'         => 'required',
                'file'              => 'required|array',
                'file.*'            => 'required|file|mimes:pptx,docx,xlsx,pdf|max:10240',
            ]);
        }

        elseif($mode == 'edit'){
            $validator = Validator::make($request->all(), [
                'judul_materi'  => 'required',
                'status'        => 'required',
                'id_mata_pelajaran' => 'required',
                'id_jurusan'        => 'required',
                'tingkat'           => 'required',
            ]);

        }
        
        if($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        else{

            $now = Carbon::now(env('APP_TIMEZONE', ''));
            $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();

            $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
            $nm_file = $request->nm_file;
            $files = request()->file;

            if($mode == 'add') {

                DB::beginTransaction();

                try {

                    $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $materi_ajar                    = new MateriAjar;
                    $materi_ajar->id_materi_ajar    = $id;
                    $materi_ajar->judul_materi      = $input->judul_materi;
                    $materi_ajar->id_mata_pelajaran = $input->id_mata_pelajaran;
                    $materi_ajar->id_jurusan        = $input->id_jurusan;
                    $materi_ajar->tingkat           = $input->tingkat;
                    $materi_ajar->status            = $input->status;
                    $materi_ajar->id_guru           = $guru->id_guru;
                    $materi_ajar->created_by        = $input->auth_data->pengguna->id_pengguna;
                    $materi_ajar->save();

                    foreach($nm_file as $key => $value){

                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru/'.$guru->id_guru.'/materi-ajar/', $files[$key], 'public');

                        $materi_ajar_file                        = new MateriAjarFile;
                        $materi_ajar_file->id_materi_ajar_file   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                        $materi_ajar_file->id_materi_ajar        = $id;
                        $materi_ajar_file->nm_file               = $value;
                        $materi_ajar_file->link_file             = $file;
                        $materi_ajar_file->type_file             = pathinfo($files[$key]->getClientOriginalName(), PATHINFO_EXTENSION);
                        $materi_ajar_file->views                 = 0;
                        $materi_ajar_file->created_by            = $input->auth_data->pengguna->id_pengguna;
                        $materi_ajar_file->save();

                    }

                    DB::Commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'e-learning/manajemen-materi-ajar',
                        'message' => 'Save Materi Ajar successfully'
                    ];


                }

                catch (\Exception $e) {
                    
                    DB::rollback();

                    return [
                            'status' => 203, // GAGAL
                            'message' => $e->getMessage()
                        ];

                }
            }

            elseif($mode == 'edit'){

                DB::beginTransaction();

                try {

                    $materi_ajar                    = MateriAjar::find($id);
                    $materi_ajar->judul_materi      = $input->judul_materi;
                    $materi_ajar->id_mata_pelajaran = $input->id_mata_pelajaran;
                    $materi_ajar->id_jurusan        = $input->id_jurusan;
                    $materi_ajar->tingkat           = $input->tingkat;
                    $materi_ajar->status            = $input->status;
                    $materi_ajar->updated_by        = $input->auth_data->pengguna->id_pengguna;
                    $materi_ajar->save();

                    if($files){

                        foreach($files as $key => $value){

                            $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/guru/'.$guru->id_guru.'/materi-ajar/', $files[$key], 'public');

                            $materi_ajar_file                        = new MateriAjarFile;
                            $materi_ajar_file->id_materi_ajar_file   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                            $materi_ajar_file->id_materi_ajar        = $id;
                            $materi_ajar_file->nm_file               = $nm_file[$key];
                            $materi_ajar_file->link_file             = $file;
                            $materi_ajar_file->type_file             = pathinfo($files[$key]->getClientOriginalName(), PATHINFO_EXTENSION);
                            $materi_ajar_file->views                 = 0;
                            $materi_ajar_file->created_by            = $input->auth_data->pengguna->id_pengguna;
                            $materi_ajar_file->save();

                        }

                    }

                    DB::Commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'e-learning/manajemen-materi-ajar',
                        'message' => 'Save Materi Ajar successfully'
                    ];

                }

                catch (\Exception $e) {
                    
                    DB::rollback();

                    return [
                            'status' => 203, // GAGAL
                            'message' => $e->getMessage()
                        ];

                }

            }

        }


    }

    public function datatablesManajemenMateriAjar(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = MateriAjar::with('materi_ajar_file','mapel')->where('created_by',$auth_data->pengguna->id_pengguna)->get();

        return Datatables::of($list_data)
                ->addColumn('mapel',function($item){
                    return $item->mapel->nm_mata_pelajaran;
                })
                ->addColumn('action', function($item){

                    $file = [];
                    foreach($item->materi_ajar_file as $key => $r){
                        $file[$key]['nm_file'] = $r->nm_file;
                        $file[$key]['type_file'] = $r->type_file;
                        $file[$key]['link_file'] =  Storage::disk('spaces')->url($r->link_file);
                    }

                    $data = array(
                        'id'     => $item->id_materi_ajar,
                        'status' => $item->status,
                        'materi_ajar_file' => $file
                    );
                    return $data;
                })
                ->make(true);
    }

}