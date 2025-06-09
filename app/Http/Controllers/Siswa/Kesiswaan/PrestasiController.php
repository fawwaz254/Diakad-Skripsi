<?php

namespace App\Http\Controllers\Siswa\Kesiswaan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Kelas as Kelas;
use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\Guru as Guru;
use App\Models\Ekskul as Ekskul;
use App\Models\PrestasiSiswa as PrestasiSiswa;
use App\Models\TingkatPrestasiSiswa as TingkatPrestasiSiswa;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;

use Auth;
use DB;
use Session;
use Validator;

class PrestasiController extends BaseController
{
    public function viewPrestasi(Request $request, $id_kelas = null)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('siswa/kesiswaan/prestasi/view-prestasi', compact('auth_data'));
    }
    
    public function datatablesPrestasi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nisn_siswa',
            'siswa.nis_siswa',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'kelas.nm_kelas',
            'prestasi_siswa.lokasi_prestasi_siswa',
            'prestasi_siswa.penyelenggara_prestasi_siswa',
            'prestasi_siswa.tgl_prestasi_siswa',
            'ekskul.nm_ekskul',
            'prestasi_siswa.id_prestasi_siswa',
            'prestasi_siswa.id_guru_pendamping',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
        ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
        ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
        ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
        ->join('semester', 'semester.id_semester', '=', 'prestasi_siswa.id_semester')
        ->join('kelas', 'kelas.id_kelas', '=', 'prestasi_siswa.id_kelas')
        ->leftJoin('ekskul', 'ekskul.id_ekskul', '=', 'prestasi_siswa.id_ekskul')
        ->leftJoin('guru', 'guru.id_guru', '=', 'prestasi_siswa.id_guru_pendamping')
        ->leftJoin('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
        ->orderBy('prestasi_siswa.created_at', 'desc')
        ->orderBy('semester.thn_akademik_semester', 'desc')
        ->orderBy('semester.nm_semester', 'desc')
        ->where('p1.id_pengguna', '=', $auth_data->pengguna->id_pengguna)
        ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->nm_semester.' ('.$item->tahun_ajaran.')';
                })
                ->addColumn('jenis_prestasi', function ($item) {
                    if ($item->jenis_prestasi_siswa == 1) {
                        return "Sains";
                    } elseif ($item->jenis_prestasi_siswa == 2) {
                        return "Seni";
                    } elseif ($item->jenis_prestasi_siswa == 3) {
                        return "Olahraga";
                    } elseif ($item->jenis_prestasi_siswa == 99) {
                        return "Lain-Lain";
                    }
                })
              ->addColumn('tgl_prestasi_siswa', function ($item) {
                  return strftime("%d %B %Y", strtotime($item->tgl_prestasi_siswa));
              })
              ->addColumn('nm_guru_pendamping', function ($item) {
                  if (! empty($item->gelar_depan) && ! empty($item->gelar_belakang)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } elseif (! empty($item->gelar_depan)) {
                      return $item->gelar_depan." ".$item->nm_guru_pendamping;
                  } elseif (! empty($item->gelar_belakang)) {
                      return $item->nm_guru_pendamping.", ".$item->gelar_belakang;
                  } else {
                      return $item->nm_guru_pendamping;
                  }
              })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_prestasi_siswa
                    );
                    return $data;
                })
                ->make(true);
    }
}
