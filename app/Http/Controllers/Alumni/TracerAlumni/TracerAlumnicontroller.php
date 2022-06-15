<?php

namespace App\Http\Controllers\Alumni\TracerAlumni;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;

class TracerAlumnicontroller extends Controller
{

    public function addTracerAlumni(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('tingkat', 12)->orWhere('tingkat',9)->get();
        $alumni = null;
        $siswa = Siswa::where('id_pengguna',$auth_data->pengguna->id_pengguna)->with('calon_siswa','pengguna')->first();
        return view('siswa.alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas','siswa'));
    }
}
