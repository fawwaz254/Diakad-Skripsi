<?php

namespace App\Http\Controllers\Guru\JurnalPimpinan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use App\Models\JenisJurnalPimpinan;
use App\Models\LaporanJurnalPimpinan;
use Yajra\Datatables\Datatables;
use Auth;
use DB;
use Illuminate\Validation\Rule;
use Session;
use Validator;
use Carbon\Carbon;
use Predis\Response\Status;

class JurnalPimpinanController extends Controller
{
    public function viewLaporanJurnalPimpinan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/jurnal-pimpinan/laporan-jurnal-pimpinan/view-data-laporan-jurnal-pimpinan',compact('auth_data'));

    }
    public function addLaporanHarianJurnalPimpinan(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $waktu = Carbon::today()->toDateString();
        // $unit_kerja = CategoryKelompokJurnalHarianTendik::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)->with('category_jurnal_harian_tendik','category_jurnal_harian_tendik.unit_kerja')->get();
        $jenis = JenisJurnalPimpinan::all();
        return view('guru/jurnal-pimpinan/laporan-jurnal-pimpinan/add-data-laporan-jurnal-pimpinan',compact('auth_data','waktu','jenis'));

    }
//     public function previewFile($id,$no,Request $request){

//         $input = (object) $request->input();
//         $auth_data = $input->auth_data;

//         $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
//         $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);
//         $link = Storage::disk('spaces')->url($laporan_kerja_harian->path_file);
//         return view('tendik/jurnal-harian/laporan-jurnal-harian/preview-file-mgmp',compact('auth_data','laporan_kerja_harian','link','ext','id','no'));

//     }

//     public function downloadFile(Request $request, $id = null)
//     {
//         $input = (object) $request->input();
//         // $file_pengguna = FilePengguna::where('file_pengguna_id', $id)->first();
//         $laporan_kerja_harian = LaporanKerjaHarianTendik::findOrFail($id);
//         $ext = pathinfo($laporan_kerja_harian->path_file, PATHINFO_EXTENSION);

//         return Storage::disk('spaces')->download($laporan_kerja_harian->path_file, $laporan_kerja_harian->nm_file . "." . $ext);
//     }

    public function actionLaporanJurnalPimpinan(Request $request, $mode = 0,$id=0){
        $input = (object) $request->input();

        $list_validator = [
                            'tanggal'            => 'required',
                            // 'unit_kerja'         => 'required',
                            'jenis'              => 'required',
                            'status'             => 'required',
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

                $data                               = new LaporanJurnalPimpinan();
                $data->id_laporan_jurpin            = $id;
                $data->tanggal                      = date_format(date_create($input->tanggal),"Y-m-d");
                $data->jenis                        = $input->jenis;
                // $data->id_category_jh_tendik        = $input->unit_kerja;
                $data->keterangan_progres           = $input->keterangan;
                $data->status                       = $input->status;
                $data->catatan                      = $input->keterangan;
                $data->id_pengguna                  = $input->auth_data->pengguna->id_pengguna;
                $data->created_by                   = $input->auth_data->pengguna->id_pengguna;

                if($request->hasFile('file')){

                    $validator = Validator::make($request->all(),[
                        'file' => 'mimes:pptx,docx,doc,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                    ]);

                    if($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    else{
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/jurnal-pimpinan/'.$id, request()->file, 'public');
                        $data->path_file = $file;

                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;

                    }
                }
                $data->save();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'jurnal-pimpinan/laporan-jurnal-pimpinan',
                    'message' => 'Save Laporan Laporan Kerja Harian successfully'
                ];
            }

            elseif($mode == 'edit'){
                $data                               = LaporanKerjaHarianTendik::find($id);
                // $data->id_role                   = $input->auth_data->role_aktif->id_role;
                $data->tanggal                      = date_format(date_create($input->tanggal),"Y-m-d");
                $data->jenis                        = $input->jenis;
                $data->id_category_jh_tendik        = $input->unit_kerja;
                $data->keterangan_progres           = $input->keterangan;
                $data->catatan                      = $input->status;
                $data->updated_by                   = $input->auth_data->pengguna->id_pengguna;

                if($request->hasFile('file')){
                    $validator = Validator::make($request->all(),[
                        'file' => 'mimes:pptx,docx,doc,xlsx,jpeg,jpg,png,pdf|required|max:5120'
                    ]);
                    if($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    else{
                        $singkat_sekolah = $input->auth_data->sekolah_data->nm_singkat_sekolah;
                        $file = Storage::disk('spaces')->putFile($singkat_sekolah.'/tendik/'.$id, request()->file, 'public');
                        $data->path_file = $file;
                        $upload = $request->file('file');
                        $filename = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
                        $data->nm_file = $filename;
                    }
                }

                $data->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'jurnal-harian/laporan-individu-jurnal-harian/',
                    'message' => 'Update Laporan Laporan Kerja Harian  successfully'
                ];

            }

            elseif($mode == 'delete'){

                $data               = LaporanKerjaHarianTendik::find($id);
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

    public function datatablesJurnalPimpinan(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = LaporanJurnalPimpinan::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)
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
                        'id'        => $item->id_laporan_jurpin,
                        'jenis'    =>$item->jenis,
                        'status'    => $item->status,
                        'file'      =>$file,
                        'note'      => $item->catatan,
                    );
                    return $data;
                })
                ->make(true);
    }


// public function editKerjaHarian(Request $request, $id = null){
//     $input = (object) $request->input();
//     $auth_data = $input->auth_data;
//     $laporan_kerja_harian_tendik = LaporanKerjaHarianTendik::findOrFail($id);
//     $unit_kerja = CategoryKelompokJurnalHarianTendik::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)->with('category_jurnal_harian_tendik.unit_kerja')->get();
//     $jenis = JenisJurnalHarianTendik::all();
//     return view('tendik/jurnal-harian/laporan-jurnal-harian/edit-data-laporan-jurnal-harian-t',compact('auth_data','unit_kerja','laporan_kerja_harian_tendik','jenis'));
// }



//     public function viewLaporanKelompokKerjaHarian(Request $request){
//         # code...
//         $input = (object) $request->input();
//         $auth_data = $input->auth_data;

//         return view('tendik/jurnal-harian/laporan-jurnal-harian/view-data-laporan-harian-tendik-kelompok',compact('auth_data'));

//     }

//     public function datatablesKerjaHarianKelompokTendik(Request $request){

//         $input = (object) $request->input();
//         $auth_data = $input->auth_data;

//         $list_data = CategoryKelompokJurnalHarianTendik::where('id_pengguna',$input->auth_data->pengguna->id_pengguna)
//                                     ->with('category_jurnal_harian_tendik','laporan_kerja_harian_tendik.pengguna','category_jurnal_harian_tendik.unit_kerja')
//                                     ->get();
// // dd($list_data);
//         return Datatables::of($list_data)
//         ->addColumn('action', function ($item) {
//             $data = array(
//                 'id' => $item->category_jurnal_harian_tendik->id_category_jh_tendik
//             );
//             return $data;
//         })
//         ->addColumn('selesai', function ($item) {
//             $data = [];
//             if ($item->laporan_kerja_harian_tendik ?? false) {
//                 foreach ($item->laporan_kerja_harian_tendik as $key => $value) {
//                     $data[$key]['jenis'] = $item->laporan_kerja_harian_tendik[$key]->jenis;
//                     $data[$key]['pengguna'] = $item->laporan_kerja_harian_tendik[$key]->pengguna->nm_pengguna;
//                     $data[$key]['status'] = $item->laporan_kerja_harian_tendik[$key]->status;
//                 }
//             }
//             return $data;
//         })->make(true);
//     }


//     public function detailLaporanKelompokTendik(Request $request, $id  = null){
//         $input = (object) $request->input();
//         $auth_data = $input->auth_data;
//         $data = CategoryKelompokJurnalHarianTendik::where('id_c_k_jh_tendik', $id)->with('pengguna')->get();
//         return view('tendik/jurnal-harian/laporan-jurnal-harian/detail-data-laporan-harian-kelompok-tendik',compact('auth_data','data','id'));
//     }

//     public function datatablesDetailKerjaHarianKelompokTendik(Request $request, $id  = null){
//         $input = (object) $request->input();
//         $auth_data = $input->auth_data;

//         $list_data = LaporanKerjaHarianTendik::
//                                     // where('id_role',$input->auth_data->role_aktif->id_role)
//                                     where('id_category_jh_tendik',$id)
//                                     ->where('status',1)
//                                     ->with('category_jurnal_harian_tendik.unit_kerja','pengguna')
//                                     ->get();

//         return Datatables::of($list_data)
//                 ->editColumn('tanggal',function($item){
//                     return Carbon::parse($item->tanggal)->format('d M Y');
//                 })
//                 ->addColumn('action', function($item){
//                     if($item->path_file){
//                         $file =  Storage::disk('spaces')->url($item->path_file);
//                         $ext = pathinfo($item->path_file, PATHINFO_EXTENSION);
//                         if($ext=='pdf'||$ext=='doc'||$ext=='docx'){
//                             $note = 'file';
//                         }
//                         else{
//                             $note= 'image';
//                         }
//                     }
//                     else{
//                         $file = null;
//                         $note = null;
//                     }

//                     $data = array(
//                         'id'        => $item->id_lap_kerha_t,
//                         'jenis'    =>$item->jenis,
//                         'status'    => $item->status,
//                         'file'      =>$file,
//                         'note'      => $item->keterangan,
//                     );
//                     return $data;
//                 })
//                 ->make(true);

//     }
}
