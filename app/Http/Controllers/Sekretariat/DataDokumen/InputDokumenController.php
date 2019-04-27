<?php

namespace App\Http\Controllers\Sekretariat\DataDokumen;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipKategori as ArsipKategori;
use App\Models\UnitKerja as UnitKerja;
use App\Models\ArsipDokumen as ArsipDokumen;
use App\Models\ArsipDokumenFile as ArsipDokumenFile;
use App\Models\ArsipPemilik as ArsipPemilik;
use App\Models\ArsipLoker as ArsipLoker;
use App\Models\ArsipSubkategori as ArsipSubkategori;

use Auth;
use DB;
use Session;
use Validator;

class InputDokumenController extends BaseController
{
    public function viewInputDokumen(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
       
        return view('sekretariat/data-dokumen/input-dokumen/view-input-dokumen',compact('auth_data'));
    }

    public function addInputDokumen(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        //kategori
        $kategori 	= ArsipKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $unit 		= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $loker 		= ArsipLoker::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $pemilik 	= ArsipPemilik::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();


        // $unit = UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();

        return view('sekretariat/data-dokumen/input-dokumen/add-input-dokumen',compact('auth_data','kategori','unit','loker','pemilik'));
    }

    public function uploadInputDokumen(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $dokumen = ArsipDokumen::select('arsip_dokumen.id_arsip_dokumen','arsip_dokumen.kode_katalog','arsip_dokumen.nm_arsip_dokumen','arsip_dokumen.nomor_arsip_dokumen','arsip_dokumen.jumlah_halaman','arsip_dokumen.tgl_penyusunan','arsip_dokumen.tgl_penyusunan','unit_kerja.nm_unit_kerja','arsip_loker.nm_arsip_loker','arsip_pemilik.nm_arsip_pemilik','arsip_subkategori.nm_arsip_subkategori','arsip_kategori.nm_arsip_kategori','arsip_dokumen.id_arsip_dokumen')
        ->leftJoin('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_dokumen.id_unit_kerja')
        ->join('arsip_loker','arsip_loker.id_arsip_loker','=','arsip_dokumen.id_arsip_loker')
        ->join('arsip_pemilik','arsip_pemilik.id_arsip_pemilik','=','arsip_pemilik.id_arsip_pemilik')
        ->join('arsip_subkategori','arsip_subkategori.id_arsip_subkategori','=','arsip_dokumen.id_arsip_subkategori')
        ->join('arsip_kategori','arsip_kategori.id_arsip_kategori','=','arsip_subkategori.id_arsip_kategori')
        ->where('id_arsip_dokumen','=',$id)
        ->first();

        $arsip_dokumen_file = ArsipDokumenFile::where('id_arsip_dokumen','=',$id)->get();

        return view('sekretariat/data-dokumen/input-dokumen/upload-input-dokumen',compact('auth_data','dokumen','arsip_dokumen_file'));
    }

    public function editInputDokumen(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kategori 		= ArsipKategori::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $subkategori 	= ArsipSubkategori::get();
        $unit 			= UnitKerja::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $loker 			= ArsipLoker::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $pemilik 		= ArsipPemilik::where('id_sekolah','=',$auth_data->pengguna->id_sekolah)->get();
        $dokumen 		= ArsipDokumen::where('id_arsip_dokumen','=',$id)->first();
       
        return view('sekretariat/data-dokumen/input-dokumen/edit-input-dokumen',compact('auth_data','kategori','unit','loker','pemilik','dokumen','subkategori'));
    }

    public function ajaxGetSubkategori(Request $request) {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data all siswa
        $subkategori = ArsipSubkategori::where('id_arsip_kategori','=',$input->kategori)->get();

        return $subkategori;
    }

    public function datatablesInputDokumen(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = ArsipDokumen::select('arsip_dokumen.id_arsip_dokumen','arsip_dokumen.id_arsip_loker','arsip_dokumen.id_arsip_pemilik','arsip_dokumen.id_arsip_subkategori','arsip_dokumen.id_unit_kerja','arsip_loker.nm_arsip_loker','arsip_pemilik.nm_arsip_pemilik','arsip_subkategori.nm_arsip_subkategori','arsip_kategori.nm_arsip_kategori','unit_kerja.nm_unit_kerja','arsip_dokumen.kode_katalog','arsip_dokumen.nm_arsip_dokumen','arsip_dokumen.nomor_arsip_dokumen','arsip_dokumen.jumlah_halaman','arsip_dokumen.tgl_penyusunan','arsip_dokumen.contact_person','arsip_dokumen.is_upload',
            DB::raw("(SELECT COUNT(*) FROM arsip_dokumen_file WHERE arsip_dokumen_file.id_arsip_dokumen = arsip_dokumen.id_arsip_dokumen AND arsip_dokumen_file.deleted_at IS NULL) AS jml_file"))
        	->join('arsip_loker','arsip_loker.id_arsip_loker','=','arsip_dokumen.id_arsip_loker')
        	->join('arsip_pemilik','arsip_pemilik.id_arsip_pemilik','=','arsip_dokumen.id_arsip_pemilik')
        	->join('arsip_subkategori','arsip_subkategori.id_arsip_subkategori','=','arsip_dokumen.id_arsip_subkategori')
        	->join('arsip_kategori','arsip_kategori.id_arsip_kategori','=','arsip_subkategori.id_arsip_kategori')
        	->leftJoin('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_dokumen.id_unit_kerja')
        	->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah);

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_arsip_dokumen
                    );
                    return $data;
                })
                ->make(true);
    }

    public function actionInputDokumen(Request $request, $mode, $id = null){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $validator = Validator::make($request->all(), [
            'id_arsip_loker'        => 'required',
            'id_arsip_pemilik'      => 'required',
            'id_arsip_subkategori'  => 'required',
            'kode_katalog' 			=> 'required',
            'nm_arsip_dokumen' 		=> 'required',
            'nomor_arsip_dokumen' 	=> 'required',
            'jumlah_halaman' 		=> 'required',
            'tgl_penyusunan' 		=> 'required',
            'contact_person' 		=> 'required' 

        ]);

        if($validator->fails() && $mode != 'delete' && $mode != 'upload' && $mode != 'delete-file') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }
        else{
            // ACTION ADD
            if($mode == 'add') {
               $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $arsip 							= new ArsipDokumen;
                $arsip->id_arsip_dokumen		= $id;
                $arsip->id_arsip_loker			= $input->id_arsip_loker;
                $arsip->id_arsip_pemilik		= $input->id_arsip_pemilik;
                $arsip->id_arsip_subkategori	= $input->id_arsip_subkategori;
                $arsip->id_unit_kerja			= $input->id_unit_kerja;
                $arsip->kode_katalog			= $input->kode_katalog;
                $arsip->nm_arsip_dokumen 		= $input->nm_arsip_dokumen;
                $arsip->nomor_arsip_dokumen		= $input->nomor_arsip_dokumen;
                $arsip->jumlah_halaman			= $input->jumlah_halaman;
                $arsip->tgl_penyusunan			= date_format(date_create($input->tgl_penyusunan),"Y-m-d");
                $arsip->contact_person 			= $input->contact_person;
                $arsip->is_upload 				= 0;
                $arsip->id_sekolah				= $auth_data->pengguna->id_sekolah;
                $arsip->created_at 				= $now;
                $arsip->created_by				= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-dokumen/input-dokumen',
                    'message' => 'Save Data Dokumen successfully'
                ];
            }
            elseif($mode == 'edit') {
               // $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                
                $arsip 							= ArsipDokumen::find($id);
                $arsip->id_arsip_loker			= $input->id_arsip_loker;
                $arsip->id_arsip_pemilik		= $input->id_arsip_pemilik;
                $arsip->id_arsip_subkategori	= $input->id_arsip_subkategori;
                $arsip->id_unit_kerja			= $input->id_unit_kerja;
                $arsip->kode_katalog			= $input->kode_katalog;
                $arsip->nm_arsip_dokumen 		= $input->nm_arsip_dokumen;
                $arsip->nomor_arsip_dokumen		= $input->nomor_arsip_dokumen;
                $arsip->jumlah_halaman			= $input->jumlah_halaman;
                $arsip->tgl_penyusunan			= date_format(date_create($input->tgl_penyusunan),"Y-m-d");
                $arsip->contact_person 			= $input->contact_person;
                $arsip->updated_at 				= $now;
                $arsip->updated_by				= $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-dokumen/input-dokumen',
                    'message' => 'Save Data Dokumen successfully'
                ];
            }
            elseif($mode == 'upload') {
                $validator = Validator::make($request->all(), [
                        'file' => 'file|required|max:2048|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,bmp,png'
                    ]);


                $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;

                $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/sekretariat/'.$id, request()->file, 'public');

                $arsipDokumen                          = ArsipDokumen::find($id);
                $arsipDokumen->is_upload               = 1;
                $arsipDokumen->updated_at              = $now;
                $arsipDokumen->updated_by              = $input->auth_data->pengguna->id_pengguna;
                $arsipDokumen->save();

                $arsip                          = new ArsipDokumenFile;
                $arsip->id_arsip_dokumen_file   = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $arsip->id_arsip_dokumen        = $id;
                $arsip->nm_arsip_dokumen_file   = $file;
                $arsip->created_by              = $input->auth_data->pengguna->id_pengguna;
                $arsip->save();

                return redirect('sekretariat#data-dokumen/input-dokumen/upload/'.$id);
            }
            elseif($mode == 'delete-file') {

                $arsipDokumenFile = ArsipDokumenFile::find($input->id_arsip_dokumen_file);

                $id_arsip_dokumen = $arsipDokumenFile->id_arsip_dokumen;

                // delete DO Spaces
                $files = Storage::disk('spaces')->delete($arsipDokumenFile->nm_arsip_dokumen_file);

                // delete tabel DB
                $arsipDokumenFile->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $arsipDokumenFile->deleted_at  = $now;
                $arsipDokumenFile->save();

                $arsipDokumenFile->delete();

                $arsipDokumenFileCek = ArsipDokumenFile::where('id_arsip_dokumen','=',$id_arsip_dokumen)->first();

                if ( ! $arsipDokumenFileCek) {
                    $arsipDokumen                          = ArsipDokumen::find($id_arsip_dokumen);
                    $arsipDokumen->is_upload               = 0;
                    $arsipDokumen->updated_at              = $now;
                    $arsipDokumen->updated_by              = $input->auth_data->pengguna->id_pengguna;
                    $arsipDokumen->save();
                }

                return [
                        'status' => 202, // SUCCESS AND LOAD TABLE
                        'path' => 'data-dokumen/input-dokumen/upload/'.$id_arsip_dokumen,
                        'message' => 'Delete File Dokumen successfully'
                    ];               
            }
            elseif($mode == 'delete'){
            	if(ArsipDokumenFile::where('id_arsip_dokumen','=',$id)->first()){
            		 return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Dokumen gagal'
                    ];
            	}else{
            		$arsip 	= ArsipDokumen::find($id);
            		$arsip->deleted_by	= $input->auth_data->pengguna->id_pengguna;
            		$arsip->deleted_at 	= $now;
            		$arsip->save();

            		$arsip->delete();

            		return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Data Dokumen successfully'
                    ];
                }
            }            
        }
    }
}
