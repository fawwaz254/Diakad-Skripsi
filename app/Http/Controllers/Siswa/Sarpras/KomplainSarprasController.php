<?php

namespace App\Http\Controllers\Siswa\Sarpras;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\KomplainSarpras as KomplainSarpras;
use App\Models\Siswa as Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class KomplainSarprasController extends BaseController
{

    public function viewKomplainSarpras(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data);

        return view('siswa/sarpras/komplain-sarpras/view-komplain-sarpras', compact('auth_data', 'data_ruangan', 'data_buku_alat'));
    }

    //** ACTION RUANGAN **//
    public function actionViewRuanganKomplainSarpras(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_ruangan' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'sarpras/komplain-sarpras/ruangan-sarpras/view-ruangan/' . $input->id_ruangan
            ];
        }
    }

    public function viewRuanganKomplainSarpras(Request $request, $id_ruangan)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, 1, $id_ruangan);

        return view('siswa/sarpras/komplain-sarpras/view-ruangan-komplain-sarpras', compact('auth_data', 'data_ruangan'));
    }

    public function datatablesRuanganKomplainSarpras(Request $request, $id_ruangan)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataKomplainRuangan($auth_data, $id_ruangan);

        return Datatables::of($list_data)
            ->addColumn('nm_ruangan', function ($item) {
                return $item->nm_ruangan . " - " . $item->nm_jenis_ruangan;
            })
            ->addColumn('user_komplain', function ($item) {
                if (!empty($item->nm_pengguna_siswa)) {
                    return $item->nis_siswa . " - " . $item->nm_pengguna_siswa . " - " . $item->nm_kelas;
                } else {
                    if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                        return $item->gelar_depan . " " . $item->nm_pengguna_guru . ", " . $item->gelar_belakang;
                    } elseif (!empty($item->gelar_depan)) {
                        return $item->gelar_depan . " " . $item->nm_pengguna_guru;
                    } elseif (!empty($item->gelar_belakang)) {
                        return $item->nm_pengguna_guru . ", " . $item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna_guru;
                    }
                }
            })
            ->addColumn('is_urgent', function ($item) {
                if ($item->is_urgent == 0) {
                    return "Tidak Urgent";
                } elseif ($item->is_urgent == 1) {
                    return "Urgent";
                } else {
                    return "Sangat Urgent";
                }
            })
            ->addColumn('is_sudah_perbaikan', function ($item) {
                if ($item->is_sudah_perbaikan == 0) {
                    return "Belum Perbaikan";
                } else {
                    return "Sudah Perbaikan";
                }
            })
            ->addColumn('nm_pengguna_guru_sarpras', function ($item) {
                if (!empty($item->nm_pengguna_guru_sarpras)) {
                    if (!empty($item->gelar_depan_sarpras) && !empty($item->gelar_belakang_sarpras)) {
                        return $item->gelar_depan_sarpras . " " . $item->nm_pengguna_guru_sarpras . ", " . $item->gelar_belakang_sarpras;
                    } elseif (!empty($item->gelar_depan_sarpras)) {
                        return $item->gelar_depan_sarpras . " " . $item->nm_pengguna_guru_sarpras;
                    } elseif (!empty($item->gelar_belakang_sarpras)) {
                        return $item->nm_pengguna_guru_sarpras . ", " . $item->gelar_belakang_sarpras;
                    } else {
                        return $item->nm_pengguna_guru_sarpras;
                    }
                } else {
                    return "-";
                }
            })
            ->addColumn('keterangan_perbaikan', function ($item) {
                if (!empty($item->keterangan_perbaikan)) {
                    return $item->keterangan_perbaikan;
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_komplain_sarpras,
                    'user_perbaikan' => $item->id_guru_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    public function addRuanganKomplainSarpras(Request $request, $id_ruangan)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, 1, $id_ruangan);

        $data_inventaris_ruangan = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $id_ruangan);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_komplain_sarpras = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('siswa/sarpras/komplain-sarpras/add-ruangan-komplain-sarpras', compact('auth_data', 'data_ruangan', 'data_inventaris_ruangan', 'id_komplain_sarpras'));
    }

    public function editRuanganKomplainSarpras(Request $request, $id_ruangan, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data, 1, $id_ruangan);

        $data_inventaris_ruangan = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $id_ruangan);

        $data_komplain_sarpras = LibDataSarpras::fetchDataKomplainRuangan($auth_data, $id_ruangan, $id);

        return view('siswa/sarpras/komplain-sarpras/edit-ruangan-komplain-sarpras', compact('auth_data', 'data_ruangan', 'data_inventaris_ruangan', 'data_komplain_sarpras'));
    }

    //** ACTION BUKU ALAT **//
    public function actionViewBukualatKomplainSarpras(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_buku_alat' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'sarpras/komplain-sarpras/bukualat-sarpras/view-bukualat/' . $input->id_buku_alat
            ];
        }
    }

    public function viewBukualatKomplainSarpras(Request $request, $id_buku_alat)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data, $id_buku_alat);

        return view('siswa/sarpras/komplain-sarpras/view-bukualat-komplain-sarpras', compact('auth_data', 'data_buku_alat'));
    }

    public function datatablesBukualatKomplainSarpras(Request $request, $id_buku_alat)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataKomplainBukuAlat($auth_data, $id_buku_alat);

        return Datatables::of($list_data)
            ->addColumn('nm_buku_alat', function ($item) {
                return $item->nm_buku_alat . " - " . $item->nm_jenis_buku_alat;
            })
            ->addColumn('user_komplain', function ($item) {
                if (!empty($item->nm_pengguna_siswa)) {
                    return $item->nis_siswa . " - " . $item->nm_pengguna_siswa . " - " . $item->nm_kelas;
                } else {
                    if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                        return $item->gelar_depan . " " . $item->nm_pengguna_guru . ", " . $item->gelar_belakang;
                    } elseif (!empty($item->gelar_depan)) {
                        return $item->gelar_depan . " " . $item->nm_pengguna_guru;
                    } elseif (!empty($item->gelar_belakang)) {
                        return $item->nm_pengguna_guru . ", " . $item->gelar_belakang;
                    } else {
                        return $item->nm_pengguna_guru;
                    }
                }
            })
            ->addColumn('is_urgent', function ($item) {
                if ($item->is_urgent == 0) {
                    return "Tidak Urgent";
                } elseif ($item->is_urgent == 1) {
                    return "Urgent";
                } else {
                    return "Sangat Urgent";
                }
            })
            ->addColumn('is_sudah_perbaikan', function ($item) {
                if ($item->is_sudah_perbaikan == 0) {
                    return "Belum Perbaikan";
                } else {
                    return "Sudah Perbaikan";
                }
            })
            ->addColumn('nm_pengguna_guru_sarpras', function ($item) {
                if (!empty($item->nm_pengguna_guru_sarpras)) {
                    if (!empty($item->gelar_depan_sarpras) && !empty($item->gelar_belakang_sarpras)) {
                        return $item->gelar_depan_sarpras . " " . $item->nm_pengguna_guru_sarpras . ", " . $item->gelar_belakang_sarpras;
                    } elseif (!empty($item->gelar_depan_sarpras)) {
                        return $item->gelar_depan_sarpras . " " . $item->nm_pengguna_guru_sarpras;
                    } elseif (!empty($item->gelar_belakang_sarpras)) {
                        return $item->nm_pengguna_guru_sarpras . ", " . $item->gelar_belakang_sarpras;
                    } else {
                        return $item->nm_pengguna_guru_sarpras;
                    }
                } else {
                    return "-";
                }
            })
            ->addColumn('keterangan_perbaikan', function ($item) {
                if (!empty($item->keterangan_perbaikan)) {
                    return $item->keterangan_perbaikan;
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_komplain_sarpras,
                    'user_perbaikan' => $item->id_guru_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    public function addBukualatKomplainSarpras(Request $request, $id_buku_alat)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data, $id_buku_alat);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_komplain_sarpras = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('siswa/sarpras/komplain-sarpras/add-bukualat-komplain-sarpras', compact('auth_data', 'data_buku_alat', 'id_komplain_sarpras'));
    }

    public function editBukualatKomplainSarpras(Request $request, $id_buku_alat, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data, $id_buku_alat);

        $data_komplain_sarpras = LibDataSarpras::fetchDataKomplainBukuAlat($auth_data, $id_buku_alat, $id);

        return view('siswa/sarpras/komplain-sarpras/edit-bukualat-komplain-sarpras', compact('auth_data', 'data_buku_alat', 'data_komplain_sarpras'));
    }

    // Action POST
    public function actionKomplainSarpras(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            /*'id_ruangan' => 'required',
            'id_buku_alat' => 'required',*/
            //'id_inventaris_ruangan' => 'required',
            'keterangan_komplain' => 'required',
            'is_urgent' => 'required'
        ]);

        $mode_delete = array("delete-ruangan", "delete-bukualat");

        if ($validator->fails() && !in_array($mode, $mode_delete)) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // get id_siswa
            $siswa = Siswa::where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)->first();
            $id_siswa = $siswa->id_siswa;

            //** MODE UNTUK RUANGAN
            if ($mode == 'add-ruangan') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $komplainSarpras                            = new KomplainSarpras;
                $komplainSarpras->id_komplain_sarpras       = $id;
                $komplainSarpras->id_ruangan                = $input->id_ruangan;
                if (!empty($input->id_inventaris_ruangan)) {
                    $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                } else {
                    $komplainSarpras->id_inventaris_ruangan     = null;
                }
                $komplainSarpras->id_siswa_komplain         = $id_siswa;
                $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                $komplainSarpras->is_urgent                 = $input->is_urgent;
                $komplainSarpras->is_sudah_perbaikan        = 0;
                $komplainSarpras->created_by                = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sarpras/komplain-sarpras/ruangan-sarpras/view-ruangan/' . $input->id_ruangan,
                    'message' => 'Save Komplain Sarpras Successfully'
                ];
            } elseif ($mode == 'edit-ruangan') {
                // make object to find id
                $komplainSarpras                            = KomplainSarpras::find($id);
                $komplainSarpras->id_ruangan                = $input->id_ruangan;
                if (!empty($input->id_inventaris_ruangan)) {
                    $komplainSarpras->id_inventaris_ruangan     = $input->id_inventaris_ruangan;
                } else {
                    $komplainSarpras->id_inventaris_ruangan     = null;
                }
                $komplainSarpras->id_siswa_komplain         = $id_siswa;
                $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                $komplainSarpras->is_urgent                 = $input->is_urgent;
                $komplainSarpras->updated_by                = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->updated_at                = $now;
                $komplainSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sarpras/komplain-sarpras/ruangan-sarpras/view-ruangan/' . $input->id_ruangan,
                    'message' => 'Update Komplain Sarpras Successfully'
                ];
            } elseif ($mode == 'delete-ruangan') {
                // make object to find id
                $komplainSarpras               = KomplainSarpras::find($id);
                $komplainSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->save();

                $komplainSarpras->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Komplain Sarpras Successfully'
                ];
            }
            //** MODE UNTUK BUKU/ALAT
            elseif ($mode == 'add-bukualat') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $komplainSarpras                            = new KomplainSarpras;
                $komplainSarpras->id_komplain_sarpras       = $id;
                $komplainSarpras->id_buku_alat              = $input->id_buku_alat;
                $komplainSarpras->id_siswa_komplain         = $id_siswa;
                $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                $komplainSarpras->is_urgent                 = $input->is_urgent;
                $komplainSarpras->is_sudah_perbaikan        = 0;
                $komplainSarpras->created_by                = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sarpras/komplain-sarpras/bukualat-sarpras/view-bukualat/' . $input->id_buku_alat,
                    'message' => 'Save Komplain Sarpras Successfully'
                ];
            } elseif ($mode == 'edit-bukualat') {
                // make object to find id
                $komplainSarpras                            = KomplainSarpras::find($id);
                $komplainSarpras->id_buku_alat              = $input->id_buku_alat;
                $komplainSarpras->id_siswa_komplain         = $id_siswa;
                $komplainSarpras->keterangan_komplain       = $input->keterangan_komplain;
                $komplainSarpras->is_urgent                 = $input->is_urgent;
                $komplainSarpras->updated_by                = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->updated_at                = $now;
                $komplainSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'sarpras/komplain-sarpras/bukualat-sarpras/view-bukualat/' . $input->id_buku_alat,
                    'message' => 'Update Komplain Sarpras Successfully'
                ];
            } elseif ($mode == 'delete-bukualat') {
                // make object to find id
                $komplainSarpras               = KomplainSarpras::find($id);
                $komplainSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $komplainSarpras->save();

                $komplainSarpras->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Komplain Sarpras Successfully'
                ];
            }
        }
    }
}
