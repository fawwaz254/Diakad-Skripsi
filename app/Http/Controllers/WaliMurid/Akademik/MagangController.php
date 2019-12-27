<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;

use Auth;
use DB;
use Session;
use Validator;

class MagangController extends BaseController
{
    public function viewMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $data_anak_murid_aktif->id_pengguna);

        return view('wali-murid/akademik/magang/view-magang', compact('auth_data', 'data_siswa'));
    }

    public function datatablesMagang(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchDataMagang($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
                ->addColumn('nm_magang', function ($item) {
                    return $item->nm_magang." ".$item->periode_magang;
                })
                ->addColumn('tgl_magang_mulai', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_magang_mulai));
                })
                ->addColumn('tgl_magang_selesai', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_magang_selesai));
                })
                ->addColumn('nilai', function ($item) {
                    if ($item->is_tampil == 1) {
                        return $item->nilai_angka." - ".$item->nilai_huruf;
                    } else {
                        return "-";
                    }
                })
                ->make(true);
    }
}
