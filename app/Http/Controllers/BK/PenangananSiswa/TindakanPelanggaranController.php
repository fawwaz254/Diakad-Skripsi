<?php

namespace App\Http\Controllers\BK\PenangananSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PelanggaranSiswa as PelanggaranSiswa;
use App\Models\PresensiMpPelanggaran as PresensiMpPelanggaran;
use App\Models\TindakanPelanggaran as TindakanPelanggaran;
use App\Models\Guru as Guru;
use App\Models\Pengguna;
use App\Models\BkKelas;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Auth;
use DB;
use Session;
use Validator;

class TindakanPelanggaranController extends BaseController
{
    public function viewTindakanPelanggaran(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('bk/penanganan-siswa/tindakan-pelanggaran/view-tindakan-pelanggaran', compact('auth_data'));
    }

    public function addTindakanPelanggaranNonKBM($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // ambil data pelanggaran
        $data_pelanggaran_siswa = LibDataPelanggaran::fetchDataInputPelanggaran($auth_data, null, $id);

        // convert format date
        $tgl_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_pelanggaran_siswa->tgl_pelanggaran));

        $data_jenis_tindakan = LibDataPelanggaran::fetchDataJenisTindakan($auth_data);

        $id_pengguna = $auth_data->pengguna->id_pengguna;

        $id_tindakan_pelanggaran = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/penanganan-siswa/tindakan-pelanggaran/add-nonkbm-tindakan-pelanggaran', compact('auth_data', 'data_pelanggaran_siswa', 'tgl_pelanggaran', 'data_jenis_tindakan', 'id_pengguna', 'id_tindakan_pelanggaran'));
    }

    public function addTindakanPelanggaranKBM($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        // mengambil waktu sekarang
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        // ambil data presensi mp pelanggaran
        $data_presensi_mp_pelanggaran = LibDataPelanggaran::fetchDataPresensiPelanggaran($auth_data, $id);

        // convert format date
        $tgl_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_presensi_mp_pelanggaran->created_at));

        $data_jenis_tindakan = LibDataPelanggaran::fetchDataJenisTindakan($auth_data);

        $id_tindakan_pelanggaran = $auth_data->sekolah_data->prefix.strtotime($now).uniqid();

        return view('bk/penanganan-siswa/tindakan-pelanggaran/add-kbm-tindakan-pelanggaran', compact('auth_data', 'data_presensi_mp_pelanggaran', 'tgl_pelanggaran', 'data_jenis_tindakan', 'id_tindakan_pelanggaran'));
    }

    public function editTindakanPelanggaran($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_tindakan = LibDataPelanggaran::fetchDataJenisTindakan($auth_data);

        $data_tindakan_pelanggaran = LibDataPelanggaran::fetchDataTindakanPelanggaran($auth_data, null, $id);

        if (! empty($data_tindakan_pelanggaran->id_pelanggaran_siswa)) {
            $tgl_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_tindakan_pelanggaran->tgl_pelanggaran));
        } else {
            $tgl_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_tindakan_pelanggaran->tgl_pelanggaran_presensi));
        }

        // convert format date
        $tgl_tindakan_pelanggaran = strftime("%d %B %Y %H:%M:%S", strtotime($data_tindakan_pelanggaran->tgl_tindakan_pelanggaran));

        $id_pengguna = $auth_data->pengguna->id_pengguna;

        return view('bk/penanganan-siswa/tindakan-pelanggaran/edit-tindakan-pelanggaran', compact('auth_data', 'data_jenis_tindakan', 'data_tindakan_pelanggaran', 'tgl_pelanggaran', 'tgl_tindakan_pelanggaran', 'id_pengguna'));
    }

    public function datatablesBelumTindakanNonKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataPelanggaran::fetchDataTindakanPelanggaran($auth_data, 0, null, "1");

        $bk_kelas = [];
        $pengguna = Pengguna::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        if($pengguna){
            $bk_kelas = BkKelas::groupBy('id_kelas')->where('id_pengguna',$pengguna->id_pengguna)->pluck('id_kelas')->toArray();
        }

        return Datatables::of($list_data)
                ->addColumn('cek_pj_bk',function($item) use ($bk_kelas){
                    $hasil = false;
                    if(in_array($item->id_kelas,$bk_kelas)){
                        $hasil = true;
                    }
                    return $hasil;
                })
                ->addColumn('nm_siswa', function ($item) {
                    return $item->nm_pengguna;
                })
                ->editColumn('nm_subkategori_pelanggaran', function ($item) {
                    return strip_tags($item->nm_subkategori_pelanggaran);
                })
                ->addColumn('aktor_input_pelanggaran', function ($item) {
                    if ($item->aktor_input_pelanggaran == 1) {
                        return "Role BK";
                    } elseif ($item->aktor_input_pelanggaran == 2) {
                        return "Kesiswaan";
                    } elseif ($item->aktor_input_pelanggaran == 3) {
                        return "Wali Kelas";
                    }
                })
                ->addColumn('nm_input', function ($item) {
                    if (! empty($item->nm_guru_input)) {
                        if (! empty($item->gelar_depan_guru) && ! empty($item->gelar_belakang_guru)) {
                            return $item->gelar_depan_guru." ".$item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                        } elseif (! empty($item->gelar_depan_guru)) {
                            return $item->gelar_depan_guru." ".$item->nm_guru_input." (Guru)";
                        } elseif (! empty($item->gelar_belakang_guru)) {
                            return $item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                        } else {
                            return $item->nm_guru_input." (Guru)";
                        }
                    } else {
                        if (! empty($item->gelar_depan_staff) && ! empty($item->gelar_belakang_staff)) {
                            return $item->gelar_depan_staff." ".$item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                        } elseif (! empty($item->gelar_depan_staff)) {
                            return $item->gelar_depan_staff." ".$item->nm_staff_input." (Tendik)";
                        } elseif (! empty($item->gelar_belakang_staff)) {
                            return $item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                        } else {
                            return $item->nm_staff_input." (Tendik)";
                        }
                    }
                })
                ->addColumn('tingkat_pelanggaran', function ($item) {
                    return $item->tingkat_kategori_pelanggaran.".".$item->tingkat_subkategori_pelanggaran;
                })
                ->addColumn('catatan_pelanggaran_khusus', function ($item) use ($auth_data) {
                    if ($item->created_by == $auth_data->pengguna->id_pengguna) {
                        return "Klik Action Untuk Melihat/Mengedit";
                    } else {
                        return "Khusus User Input";
                    }
                })
                ->addColumn('tgl_pelanggaran', function ($item) {
                    return strftime("%d %B %Y %H:%M:%S", strtotime($item->tgl_pelanggaran));
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_pelanggaran_siswa
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesBelumTindakanKBM(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataPelanggaran::fetchDataPresensiPelanggaran($auth_data, null, "1");

        $bk_kelas = [];
        $pengguna = Pengguna::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        if($pengguna){
            $bk_kelas = BkKelas::groupBy('id_kelas')->where('id_pengguna',$pengguna->id_pengguna)->pluck('id_kelas')->toArray();
        }

        return Datatables::of($list_data)
                ->addColumn('cek_pj_bk',function($item) use ($bk_kelas){
                    $hasil = false;
                    if(in_array($item->id_kelas,$bk_kelas)){
                        $hasil = true;
                    }
                    return $hasil;
                })
                ->addColumn('nm_siswa', function ($item) {
                    return $item->nm_pengguna;
                })
                 ->editColumn('nm_subkategori_pelanggaran', function ($item) {
                    return strip_tags($item->nm_subkategori_pelanggaran);
                })
                ->addColumn('aktor_input_pelanggaran', function ($item) {
                    return "Guru Pengampu";
                })
                ->addColumn('nm_input', function ($item) {
                    if (! empty($item->gelar_depan_guru) && ! empty($item->gelar_belakang_guru)) {
                        return $item->gelar_depan_guru." ".$item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                    } elseif (! empty($item->gelar_depan_guru)) {
                        return $item->gelar_depan_guru." ".$item->nm_guru_input." (Guru)";
                    } elseif (! empty($item->gelar_belakang_guru)) {
                        return $item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                    } else {
                        return $item->nm_guru_input." (Guru)";
                    }
                })
                ->addColumn('nm_mapel', function ($item) {
                    return $item->kd_mata_pelajaran." - ".$item->nm_mata_pelajaran;
                })
                ->addColumn('tgl_pelanggaran', function ($item) {
                    return strftime("%d %B %Y %H:%M:%S", strtotime($item->created_at));
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_presensi_mp_pelanggaran
                    );
                    return $data;
                })
                ->make(true);
    }

    public function datatablesSudahTindakan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataPelanggaran::fetchDataTindakanPelanggaran($auth_data, 1, null, "1");

        $bk_kelas = [];
        $pengguna = Pengguna::where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        if($pengguna){
            $bk_kelas = BkKelas::groupBy('id_kelas')->where('id_pengguna',$auth_data->pengguna->id_pengguna)->pluck('id_kelas')->toArray();
        }

        return Datatables::of($list_data)
                ->addColumn('cek_pj_bk',function($item) use ($bk_kelas){
                    $hasil = false;
                    if(in_array($item->id_kelas,$bk_kelas)){
                        $hasil = true;
                    }
                    return $hasil;
                })
                ->addColumn('nm_siswa', function ($item) {
                    if (! empty($item->nm_siswa)) {
                        return $item->nm_siswa;
                    } elseif (! empty($item->nm_siswa_presensi)) {
                        return $item->nm_siswa_presensi;
                    } else {
                        return "-";
                    }
                })
                 ->editColumn('nm_subkategori_pelanggaran', function ($item) {
                    return strip_tags($item->nm_subkategori_pelanggaran);
                })
                ->addColumn('nm_kelas', function ($item) {
                    if (! empty($item->nm_kelas)) {
                        return $item->nm_kelas;
                    } elseif (! empty($item->nm_kelas_presensi)) {
                        return $item->nm_kelas_presensi;
                    } else {
                        return "-";
                    }
                })
                ->addColumn('nm_input', function ($item) {
                    if (! empty($item->id_pelanggaran_siswa)) {
                        if (! empty($item->nm_guru_input)) {
                            if (! empty($item->gelar_depan_guru) && ! empty($item->gelar_belakang_guru)) {
                                return $item->gelar_depan_guru." ".$item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                            } elseif (! empty($item->gelar_depan_guru)) {
                                return $item->gelar_depan_guru." ".$item->nm_guru_input." (Guru)";
                            } elseif (! empty($item->gelar_belakang_guru)) {
                                return $item->nm_guru_input.", ".$item->gelar_belakang_guru." (Guru)";
                            } else {
                                return $item->nm_guru_input." (Guru)";
                            }
                        } else {
                            if (! empty($item->gelar_depan_staff) && ! empty($item->gelar_belakang_staff)) {
                                return $item->gelar_depan_staff." ".$item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                            } elseif (! empty($item->gelar_depan_staff)) {
                                return $item->gelar_depan_staff." ".$item->nm_staff_input." (Tendik)";
                            } elseif (! empty($item->gelar_belakang_staff)) {
                                return $item->nm_staff_input.", ".$item->gelar_belakang_staff." (Tendik)";
                            } else {
                                return $item->nm_staff_input." (Tendik)";
                            }
                        }
                    } else {
                        if (! empty($item->gelar_depan_guru_presensi) && ! empty($item->gelar_belakang_guru_presensi)) {
                            return $item->gelar_depan_guru_presensi." ".$item->nm_guru_input_presensi.", ".$item->gelar_belakang_guru_presensi." (Guru)";
                        } elseif (! empty($item->gelar_depan_guru_presensi)) {
                            return $item->gelar_depan_guru_presensi." ".$item->nm_guru_input_presensi." (Guru)";
                        } elseif (! empty($item->gelar_belakang_guru_presensi)) {
                            return $item->nm_guru_input_presensinm_guru_input_presensi.", ".$item->gelar_belakang_guru_presensi." (Guru)";
                        } else {
                            return $item->nm_guru_input_presensi." (Guru)";
                        }
                    }
                })
                ->addColumn('tingkat_pelanggaran', function ($item) {
                    if (!empty($item->tingkat_kategori_pelanggaran)) {
                        return $item->tingkat_kategori_pelanggaran.".".$item->tingkat_subkategori_pelanggaran;
                    } elseif (!empty($item->tingkat_kategori_pelanggaran_mp)) {
                        return $item->tingkat_kategori_pelanggaran_mp.".".$item->tingkat_subkategori_pelanggaran_mp;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('keterangan_subkategori_pelanggaran', function ($item) {
                    if (!empty($item->keterangan_subkategori_pelanggaran)) {
                        return $item->keterangan_subkategori_pelanggaran;
                    } elseif (!empty($item->keterangan_subkategori_pelanggaran_mp)) {
                        return $item->keterangan_subkategori_pelanggaran_mp;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('catatan_pelanggaran', function ($item) {
                    if (! empty($item->id_pelanggaran_siswa)) {
                        return $item->catatan_pelanggaran;
                    } else {
                        return $item->catatan_pelanggaran_presensi;
                    }
                })
                ->addColumn('catatan_pelanggaran_khusus', function ($item) use ($auth_data) {
                    if (! empty($item->id_pelanggaran_siswa)) {
                        if ($item->created_by == $auth_data->pengguna->id_pengguna) {
                            return "Klik Action Untuk Melihat/Mengedit";
                        } else {
                            return "Khusus User Input";
                        }
                    } else {
                        return "-";
                    }
                })
                ->addColumn('tgl_pelanggaran', function ($item) {
                    if (! empty($item->id_pelanggaran_siswa)) {
                        return strftime("%d %B %Y %H:%M:%S", strtotime($item->tgl_pelanggaran));
                    } else {
                        return strftime("%d %B %Y %H:%M:%S", strtotime($item->tgl_pelanggaran_presensi));
                    }
                })
                ->addColumn('aktor_input_pelanggaran', function ($item) {
                    if (! empty($item->id_pelanggaran_siswa)) {
                        if ($item->aktor_input_pelanggaran == 1) {
                            return "Role BK";
                        } elseif ($item->aktor_input_pelanggaran == 2) {
                            return "Kesiswaan";
                        } elseif ($item->aktor_input_pelanggaran == 3) {
                            return "Wali Kelas";
                        }
                    } else {
                        return "Guru Pengampu";
                    }
                })
                ->addColumn('nm_input_tindakan', function ($item) {
                    if (! empty($item->gelar_depan_tindakan) && ! empty($item->gelar_belakang_tindakan)) {
                        return $item->gelar_depan_tindakan." ".$item->nm_input_tindakan.", ".$item->gelar_belakang_tindakan;
                    } elseif (! empty($item->gelar_depan_tindakan)) {
                        return $item->gelar_depan_tindakan." ".$item->nm_input_tindakan;
                    } elseif (! empty($item->gelar_belakang_tindakan)) {
                        return $item->nm_input_tindakan.", ".$item->gelar_belakang_tindakan;
                    } else {
                        return $item->nm_input_tindakan;
                    }
                })
                ->addColumn('catatan_tindakan_pelanggaran_khusus', function ($item) use ($auth_data) {
                    if ($item->created_by_tindakan == $auth_data->pengguna->id_pengguna) {
                        return "Klik Action Untuk Melihat/Mengedit";
                    } else {
                        return "Khusus User Input";
                    }
                })
                ->addColumn('tgl_tindakan_pelanggaran', function ($item) {
                    return strftime("%d %B %Y %H:%M:%S", strtotime($item->tgl_tindakan_pelanggaran));
                })
                ->addColumn('aktor_input_tindakan_pelanggaran', function ($item) {
                    if ($item->aktor_input_tindakan_pelanggaran == 1) {
                        return "Role BK";
                    } elseif ($item->aktor_input_tindakan_pelanggaran == 2) {
                        return "Kesiswaan";
                    }
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'id' => $item->id_tindakan_pelanggaran
                    );
                    return $data;
                })
                ->make(true);
    }

    // Action POST
    public function actionTindakanPelanggaran(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            /*'id_pelanggaran_siswa'          => 'required',
            'id_presensi_mp_pelanggaran'    => 'required',*/
            'id_jenis_tindakan'            => 'required',
            'catatan_tindakan_pelanggaran'  => 'required',
            /*'catatan_tindakan_pelanggaran_khusus'    => 'required',*/
            'tgl_tindakan_pelanggaran'      => 'required'
        ]);
        
        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add-nonkbm') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $tindakanPelanggaran                                        = new TindakanPelanggaran;
                $tindakanPelanggaran->id_tindakan_pelanggaran               = $id;
                $tindakanPelanggaran->id_pelanggaran_siswa                  = $input->id_pelanggaran_siswa;
                $tindakanPelanggaran->id_jenis_tindakan                     = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran          = $input->catatan_tindakan_pelanggaran;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus   = $input->catatan_tindakan_pelanggaran_khusus;
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran              = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->aktor_input_tindakan_pelanggaran      = 1;
                $tindakanPelanggaran->created_by                            = $input->auth_data->pengguna->id_pengguna;
                $tindakanPelanggaran->save();

                $pelanggaranSiswa                       = PelanggaranSiswa::find($input->id_pelanggaran_siswa);
                $pelanggaranSiswa->is_sudah_tindakan    = 1;
                $pelanggaranSiswa->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->updated_at           = $now;
                $pelanggaranSiswa->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Save Tindakan Pelanggaran Siswa successfully'
                ];
            } elseif ($mode == 'add-kbm') {
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                $tindakanPelanggaran                                        = new TindakanPelanggaran;
                $tindakanPelanggaran->id_tindakan_pelanggaran               = $id;
                $tindakanPelanggaran->id_presensi_mp_pelanggaran            = $input->id_presensi_mp_pelanggaran;
                $tindakanPelanggaran->id_jenis_tindakan                     = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran          = $input->catatan_tindakan_pelanggaran;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus   = $input->catatan_tindakan_pelanggaran_khusus;
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran              = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->aktor_input_tindakan_pelanggaran      = 1;
                $tindakanPelanggaran->created_by                            = $input->auth_data->pengguna->id_pengguna;
                $tindakanPelanggaran->save();

                $presensiMpPelanggaran                       = PresensiMpPelanggaran::find($input->id_presensi_mp_pelanggaran);
                $presensiMpPelanggaran->is_sudah_tindakan    = 1;
                $presensiMpPelanggaran->updated_by           = $input->auth_data->pengguna->id_pengguna;
                $presensiMpPelanggaran->updated_at           = $now;
                $presensiMpPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Save Tindakan Pelanggaran Siswa successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $tindakanPelanggaran                                            = TindakanPelanggaran::find($id);
                $tindakanPelanggaran->id_jenis_tindakan                         = $input->id_jenis_tindakan;
                $tindakanPelanggaran->catatan_tindakan_pelanggaran              = $input->catatan_tindakan_pelanggaran;
                if (! empty($input->catatan_tindakan_pelanggaran_khusus)) {
                    $tindakanPelanggaran->catatan_tindakan_pelanggaran_khusus   = $input->catatan_tindakan_pelanggaran_khusus;
                }
                // convert format date
                $tindakanPelanggaran->tgl_tindakan_pelanggaran                  = date_format(date_create($input->tgl_tindakan_pelanggaran), "Y-m-d H:i:s");
                $tindakanPelanggaran->updated_by                                = $input->auth_data->pengguna->id_pengguna;
                $tindakanPelanggaran->updated_at                                = $now;
                $tindakanPelanggaran->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'penanganan-siswa/tindakan-pelanggaran',
                    'message' => 'Update Tindakan Pelanggaran Siswa successfully'
                ];
            } elseif ($mode == 'delete') {
                if ($tindakanPelanggaran = TindakanPelanggaran::where('created_by', '<>', $input->auth_data->pengguna->id_pengguna)->first()) {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Failed To Delete Tindakan Pelanggaran Siswa'
                    ];
                } else {
                    // make object to find id
                    $tindakanPelanggaran               = TindakanPelanggaran::find($id);
                    if ($tindakanPelanggaran->id_pelanggaran_siswa) {
                        $pelanggaranSiswa                      = PelanggaranSiswa::find($tindakanPelanggaran->id_pelanggaran_siswa);
                        $pelanggaranSiswa->is_sudah_tindakan   = 0;
                        $pelanggaranSiswa->updated_by          = $input->auth_data->pengguna->id_pengguna;
                        $pelanggaranSiswa->updated_at          = $now;
                        $pelanggaranSiswa->save();
                    } elseif ($tindakanPelanggaran->id_presensi_mp_pelanggaran) {
                        $presensiMpPelanggaran                       = PresensiMpPelanggaran::find($tindakanPelanggaran->id_presensi_mp_pelanggaran);
                        $presensiMpPelanggaran->is_sudah_tindakan    = 0;
                        $presensiMpPelanggaran->updated_by           = $input->auth_data->pengguna->id_pengguna;
                        $presensiMpPelanggaran->updated_at           = $now;
                        $presensiMpPelanggaran->save();
                    }

                    $tindakanPelanggaran->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                    $tindakanPelanggaran->save();

                    $tindakanPelanggaran->delete();

                    return [
                        'status' => 203, // SUCCESS AND LOAD TABLE
                        'message' => 'Delete Tindakan Pelanggaran Siswa successfully'
                    ];
                }
            }
        }
    }
}
