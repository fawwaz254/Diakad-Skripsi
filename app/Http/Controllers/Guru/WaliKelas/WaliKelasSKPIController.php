<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Guru;
use App\Models\Pengguna;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Siswa;

class WaliKelasSKPIController extends Controller
{
    public function viewListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/view-list-siswa', compact('auth_data'));
    }

    public function datatablesListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);


        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)->with('pengguna', 'kelas', 'kegiatan_siswa', 'prestasi_siswa', 'informasi_tambahan');

        return Datatables::of($list_siswa)
            ->addColumn('kegiatan_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_pengguna,
                    'count' => $item->kegiatan_siswa->count()
                );
                return $data;
            })
            ->addColumn('prestasi_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_pengguna,
                    'count' => $item->prestasi_siswa->count()
                );
                return $data;
            })->addColumn('informasi_tambahan', function ($item) {
                $data = array(
                    'id' => $item->id_pengguna,
                    'count' => $item->informasi_tambahan->count()
                );
                return $data;
            })
            ->make(true);
    }
}
