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
use App\Models\Jurusan;

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

        $data_kelas = Kelas::where('is_aktif', 1)->get();
        $find_kelas = $data_kelas->firstWhere('id_kelas', $wali_kelas->id_kelas);
        if ($find_kelas) {
            return view('guru.wali-kelas.tracer-alumni.export-tracer-alumni', compact('auth_data', 'find_kelas', 'id_kelas', 'tahun_lulus', 'data_kelas'));
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

    public function editTracerAlumniWaliKelas(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_jurusan = Jurusan::all();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $data_kelas = Kelas::where('tingkat', 9)->where('is_aktif', 1)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('smp', 'calon_siswa')->first();
            // return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni-smp', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
        } else {
            $data_kelas = Kelas::where('tingkat', 12)->orWhere('tingkat', 3)->where('is_aktif', 1)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('calon_siswa')->first();
        }
        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
        // return view('guru.wali-kelas.tracer-alumni.edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
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
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $alumnis    = LibAlumni::getAlumnisSearchSmp($id_kelas, $tahun_lulus);
            return Datatables::of($alumnis)->addColumn('status', function ($item) {
                return $item->nm_sekolah;
            })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_alumni
                    );
                    return $data;
                })->make(true);
        } else {
            $alumnis    = LibAlumni::getAlumnis();
            return Datatables::of($alumnis)->addColumn('status', function ($item) {
                return $item->status;
            })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_alumni
                    );
                    return $data;
                })->make(true);
        }
    }
}
