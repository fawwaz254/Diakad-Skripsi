<?php

namespace App\Http\Controllers\Alumni\BursaKerja;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\LowonganKerja;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use Auth;
use DB;
use Session;
use Validator;

class BKKController extends BaseController{

    public function viewBkk(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

    	return view('alumni/bursa-kerja/bkk/view-bkk',compact('auth_data'));
    }

    public function showDatatablesBkk(Request $request){

    	$input = (object) $request->input();
        $auth_data = $input->auth_data;
    	$list_data = LowonganKerja::all();

    	return Datatables::of($list_data)
                           ->addColumn('action', function($item){
                                if($item->poster_lowongan_kerja){
                                    $poster =  Storage::disk('spaces')->url($item->poster_lowongan_kerja);
                                    $ext = pathinfo($item->poster_lowongan_kerja, PATHINFO_EXTENSION);
                                    if($ext=='pdf'||$ext=='doc'||$ext=='docx'){
                                        $note = 'file';
                                    }
                                    else{
                                        $note= 'image';
                                    }
                                }
                                else{
                                    $poster = null;
                                    $note = null;
                                }

                                $data = array(
                                    'id' => $item->id_lowongan_kerja,
                                    'poster' => $poster,
                                    'note' => $note
                                );
                                return $data;
                            })
                            ->make(true);

    }

    public function viewDetailBkk(Request $request,$id){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $item = LowonganKerja::findOrFail($id);
        return view('alumni/bursa-kerja/bkk/view-detail-bkk',compact('auth_data','item'));

    }



}