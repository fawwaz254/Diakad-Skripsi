<?php

namespace App\Http\Controllers\WaliMurid\Pelanggaran;

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

class RiwayatPelanggaranController extends BaseController
{
    public function viewRiwayatPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('wali-murid/pelanggaran/riwayat-pelanggaran/view-riwayat-pelanggaran', compact('auth_data'));
    }

    public function datatablesPelanggaranNonKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
        
        $list_data = LibSiswa::fetchPelanggaranNonKBM($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('tgl_pelanggaran', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_pelanggaran));
                })
                ->addColumn('aktor_input_pelanggaran', function ($item) {
                    if ($item->aktor_input_pelanggaran == 1) {
                        return "Guru BK";
                    } elseif ($item->aktor_input_pelanggaran == 2) {
                        return "Kesiswaan";
                    } elseif ($item->aktor_input_pelanggaran == 3) {
                        return "Wali Kelas";
                    } elseif ($item->aktor_input_pelanggaran == 4) {
                        return "Guru Reguler";
                    } elseif ($item->aktor_input_pelanggaran == 5) {
                        return "Guru Piket";
                    }
                })
                ->addColumn('nm_jenis_tindakan', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return $item->nm_jenis_tindakan;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('catatan_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return $item->catatan_tindakan_pelanggaran;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('tgl_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return strftime("%A, %d %B %Y", strtotime($item->tgl_tindakan_pelanggaran));
                    } else {
                        return "-";
                    }
                })
                ->addColumn('aktor_input_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        if ($item->aktor_input_tindakan_pelanggaran == 1) {
                            return "Guru BK";
                        } elseif ($item->aktor_input_tindakan_pelanggaran == 2) {
                            return "Kesiswaan";
                        }
                    } else {
                        return "-";
                    }
                })
                ->make(true);
    }

    public function datatablesPelanggaranKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchPelanggaranKBM($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
                ->addColumn('semester', function ($item) {
                    return $item->tahun_ajaran." ".$item->nm_semester;
                })
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
                ->addColumn('tgl_entry', function ($item) {
                    return strftime("%A, %d %B %Y", strtotime($item->tgl_entry));
                })
                ->addColumn('nm_jenis_tindakan', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return $item->nm_jenis_tindakan;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('catatan_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return $item->catatan_tindakan_pelanggaran;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('tgl_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        return strftime("%A, %d %B %Y", strtotime($item->tgl_tindakan_pelanggaran));
                    } else {
                        return "-";
                    }
                })
                ->addColumn('aktor_input_tindakan_pelanggaran', function ($item) {
                    if ($item->is_sudah_tindakan == 1) {
                        if ($item->aktor_input_tindakan_pelanggaran == 1) {
                            return "Guru BK";
                        } elseif ($item->aktor_input_tindakan_pelanggaran == 2) {
                            return "Kesiswaan";
                        }
                    } else {
                        return "-";
                    }
                })
                ->make(true);
    }
}
