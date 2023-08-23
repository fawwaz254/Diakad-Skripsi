<?php

namespace App\Http\Controllers\Alumni\TracerAlumni;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TracerAlumniController extends Controller
{

    public function addTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 12)->orWhere('tingkat', 9)->orWhere('tingkat', 3)->get();

        $alumni = null;
        $siswa = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->with('calon_siswa', 'pengguna')->first();

        return view('siswa.alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas', 'siswa'));
    }
}
