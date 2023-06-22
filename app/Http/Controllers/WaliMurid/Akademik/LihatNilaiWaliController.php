<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Models\PengambilanMp;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;

class LihatNilaiWaliController extends BaseController
{
    public function index(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $walimurid= WaliMurid::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();

        $siswa = Siswa::where('id_wali_murid',$walimurid->id_wali_murid)->first();
        // dd($siswa);
        $pengambilanmp = PengambilanMp::where('id_siswa',$siswa->id_siswa)->get();
        // dd($pengambilanmp);
        


        
    	return view('siswa/akademik/lihat-nilai/view-lihat-nilai',compact('auth_data','siswa','pengambilanmp'));

    }
}
