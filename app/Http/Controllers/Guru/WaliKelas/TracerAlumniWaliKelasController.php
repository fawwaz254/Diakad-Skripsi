<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Kelas;
use App\Exports\ExportAlumni;
use Illuminate\Routing\Controller as BaseController;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Guru;
use App\Models\WaliKelas;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;
use App\Libraries\Humas\LibAlumni;

class TracerAlumniWaliKelasController extends BaseController
{

    public function cetakTracerAlumniWaliKelas(Request $request, $id_kelas = null, $tahun_lulus = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $wali_kelas = WaliKelas::where('is_aktif', 1)->with('guru')
            ->whereHas('guru', function ($query) use ($auth_data) {
                $query->where('id_pengguna', '=', $auth_data->pengguna->id_pengguna);
            })->first();

        $data_kelas = Kelas::where('tingkat', 3)->orWhere('tingkat', 9)->get();
        $find_kelas = $data_kelas->firstWhere('id_kelas', $wali_kelas->id_kelas);

        if ($find_kelas) {
            return view('guru.wali-kelas.tracer-alumni.export-tracer-alumni', compact('auth_data', 'find_kelas', 'id_kelas', 'tahun_lulus'));
        } else {
        }
    }

    public function changeTracerAlumniWaliKelas(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'wali-kelas/tracer-alumni/' . $input->id_kelas . '/' . $input->tahun_lulus,
        ];
    }
    public function exportAlumnniWaliKelas(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        // $auth_data = $input->auth_data;
        $alumni = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->with('smp', 'calon_siswa', 'kelas')->get();
        return Excel::download(new ExportAlumni($alumni), 'download_harian.xlsx');
    }


    public function datatablesCetakTracerAlumniWaliKelas(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1') {
            $alumnis    = LibAlumni::getAlumnisSearchSmp($id_kelas, $tahun_lulus);
        } else {
            $alumnis    = LibAlumni::getAlumnis();
        }
        return Datatables::of($alumnis)->make(true);
    }
}
