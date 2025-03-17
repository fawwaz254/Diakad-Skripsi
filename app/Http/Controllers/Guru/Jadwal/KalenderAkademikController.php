<?php

namespace App\Http\Controllers\Guru\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class KalenderAkademikController extends BaseController{

    public function viewKalenderAkademik(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

    	return view('guru/jadwal/kalender-akademik/view-kalender-akademik',compact('auth_data', 'semester_aktif'));

    }

    public function datatablesKalenderAkademik(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $list_data = LibDataAkademik::fetchDataKalenderAkademik($auth_data, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('semester', function($item){
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('tgl_mulai', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_mulai));
                })
                ->addColumn('tgl_selesai', function($item){
                    return strftime( "%A, %d %B %Y", strtotime($item->tgl_selesai));
                })
                ->make(true);
    }

}