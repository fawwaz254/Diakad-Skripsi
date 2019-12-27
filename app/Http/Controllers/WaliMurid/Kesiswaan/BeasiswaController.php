<?php

namespace App\Http\Controllers\WaliMurid\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\BeasiswaSiswa as BeasiswaSiswa;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class BeasiswaController extends BaseController
{
    public function viewBeasiswa(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('wali-murid/kesiswaan/beasiswa/view-beasiswa', compact('auth_data', 'data_kelas', 'id_kelas'));
    }

    public function datatablesBeasiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = BeasiswaSiswa::select('beasiswa_siswa.jenis_beasiswa_siswa', 'beasiswa_siswa.keterangan_beasiswa_siswa', 'beasiswa_siswa.tahun_mulai_beasiswa_siswa', 'beasiswa_siswa.tahun_selesai_beasiswa_siswa', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'beasiswa_siswa.id_beasiswa_siswa')
                                    ->join('siswa', 'siswa.id_siswa', '=', 'beasiswa_siswa.id_siswa')
                                    ->join('kelas', 'kelas.id_kelas', '=', 'beasiswa_siswa.id_kelas')
                                    ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                                    ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                                    ->where('siswa.id_pengguna', $data_anak_murid_aktif->id_pengguna)
                                    ->get();

        return Datatables::of($list_data)
                ->addColumn('jenis_beasiswa_siswa', function ($item) {
                    if ($item->jenis_beasiswa_siswa == 1) {
                        return "Anak Berprestasi";
                    } elseif ($item->jenis_beasiswa_siswa == 2) {
                        return "Anak Miskin";
                    } elseif ($item->jenis_beasiswa_siswa == 3) {
                        return "Pendidikan";
                    } elseif ($item->jenis_beasiswa_siswa == 99) {
                        return "Lain-Lain";
                    } elseif ($item->jenis_beasiswa_siswa == 4) {
                        return "Unggulan";
                    }
                })
                ->make(true);
    }
}
