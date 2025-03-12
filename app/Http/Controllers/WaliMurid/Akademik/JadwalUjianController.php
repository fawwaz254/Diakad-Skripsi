<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class JadwalUjianController extends BaseController
{
    public function viewJadwalUjian(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $data_anak_murid_aktif->id_pengguna);

        return view('wali-murid/akademik/jadwal-ujian/view-jadwal-ujian', compact('auth_data', 'semester_aktif', 'data_siswa'));
    }

    public function datatablesJadwalUTS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchDataJadwalUTS($auth_data, $data_anak_murid_aktif->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function ($item) {
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->addColumn('tgl_ujian', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_ujian_mp));
                })
                ->addColumn('jadwal_jam', function ($item) {
                    return $item->jam_mulai." - ".$item->jam_selesai;
                })
                ->addColumn('status_pjmp_uts', function ($item) {
                    if ($item->pjmp_uts == 1) {
                        return "PJMP";
                    } else {
                        return "Anggota";
                    }
                })
                ->addColumn('is_online', function ($item) {
                    if ($item->is_online == 1) {
                        return "Ujian Online";
                    } else {
                        return "Manual";
                    }
                })
                ->make(true);
    }

    public function datatablesJadwalUAS(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchDataJadwalUAS($auth_data, $data_anak_murid_aktif->id_pengguna, $semester_aktif->id_semester);

        return Datatables::of($list_data)
                ->addColumn('mata_pelajaran', function ($item) {
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('nm_pengguna', function ($item) {
                    if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                        return $item->gelar_depan." ".$item->nm_pengguna.", ".$item->gelar_belakang;
                    } elseif (! empty($item->gelar_depan)) {
                        return $item->gelar_depan." ".$item->nm_pengguna;
                    } elseif (! empty($item->gelar_belakang)) {
                        return $item->nm_pengguna.", ".$item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna;
                    }
                })
                ->addColumn('tgl_ujian', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_ujian_mp));
                })
                ->addColumn('jadwal_jam', function ($item) {
                    return $item->jam_mulai." - ".$item->jam_selesai;
                })
                ->addColumn('status_pjmp_uas', function ($item) {
                    if ($item->pjmp_uas == 1) {
                        return "PJMP";
                    } else {
                        return "Anggota";
                    }
                })
                ->addColumn('is_online', function ($item) {
                    if ($item->is_online == 1) {
                        return "Ujian Online";
                    } else {
                        return "Manual";
                    }
                })
                ->make(true);
    }
}
