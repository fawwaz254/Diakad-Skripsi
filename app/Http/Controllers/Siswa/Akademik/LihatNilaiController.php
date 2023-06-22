<?php

namespace App\Http\Controllers\Siswa\Akademik;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Models\PengambilanMp;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LihatNilaiController extends BaseController
{
    public function index(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        $pengambilanmp = PengambilanMp::where('id_siswa',$siswa->id_siswa)->get();
        // dd($pengambilanmp);
        


        
    	return view('siswa/akademik/lihat-nilai/view-lihat-nilai',compact('auth_data','siswa','pengambilanmp'));

    }
}
