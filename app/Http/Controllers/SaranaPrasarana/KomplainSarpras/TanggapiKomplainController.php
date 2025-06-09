<?php

namespace App\Http\Controllers\SaranaPrasarana\KomplainSarpras;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\KomplainSarpras as KomplainSarpras;
use App\Models\Guru as Guru;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class TanggapiKomplainController extends BaseController
{

    public function viewTanggapiKomplain(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('sarana-prasarana/komplain-sarpras/tanggapi-komplain/view-tanggapi-komplain', compact('auth_data'));
    }

    public function editTanggapiKomplain($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_komplain_sarpras = LibDataSarpras::fetchDataTanggapiKomplain($auth_data, 0, $id);

        $nm_ruangan = $data_komplain_sarpras->nm_ruangan . " - " . $data_komplain_sarpras->nm_jenis_ruangan;

        if (!empty($data_komplain_sarpras->nm_inventaris_ruangan)) {
            $nm_inventaris_ruangan = $data_komplain_sarpras->nm_inventaris_ruangan;
        } else {
            $nm_inventaris_ruangan = "-";
        }

        $nm_buku_alat = $data_komplain_sarpras->nm_buku_alat . " - " . $data_komplain_sarpras->nm_jenis_buku_alat;

        if (!empty($data_komplain_sarpras->nm_pengguna_siswa)) {
            $user_komplain = $data_komplain_sarpras->nis_siswa . " - " . $data_komplain_sarpras->nm_pengguna_siswa . " - " . $data_komplain_sarpras->nm_kelas;
        } else {
            if (!empty($data_komplain_sarpras->gelar_depan) && !empty($data_komplain_sarpras->gelar_belakang)) {
                $user_komplain = $data_komplain_sarpras->gelar_depan . " " . $data_komplain_sarpras->nm_pengguna_guru . ", " . $data_komplain_sarpras->gelar_belakang;
            } elseif (!empty($data_komplain_sarpras->gelar_depan)) {
                $user_komplain = $data_komplain_sarpras->gelar_depan . " " . $data_komplain_sarpras->nm_pengguna_guru;
            } elseif (!empty($data_komplain_sarpras->gelar_belakang)) {
                $user_komplain = $data_komplain_sarpras->nm_pengguna_guru . ", " . $data_komplain_sarpras->gelar_belakang;
            } else {
                $user_komplain = $data_komplain_sarpras->nm_pengguna_guru;
            }
        }

        if ($data_komplain_sarpras->is_urgent == 0) {
            $is_urgent = "Tidak Urgent";
        } elseif ($data_komplain_sarpras->is_urgent == 1) {
            $is_urgent = "Urgent";
        } else {
            $is_urgent = "Sangat Urgent";
        }

        return view('sarana-prasarana/komplain-sarpras/tanggapi-komplain/edit-tanggapi-komplain', compact('auth_data', 'data_komplain_sarpras', 'nm_ruangan', 'nm_inventaris_ruangan', 'nm_buku_alat', 'user_komplain', 'is_urgent'));
    }

    public function datatablesTanggapiKomplainBelum(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataSarpras::fetchDataTanggapiKomplain($auth_data, 0);

        return Datatables::of($list_data)
            ->addColumn('nm_ruangan', function ($item) {
                return $item->nm_ruangan . " - " . $item->nm_jenis_ruangan;
            })
            ->addColumn('nm_inventaris_ruangan', function ($item) {
                if (!empty($item->nm_inventaris_ruangan)) {
                    return $item->nm_inventaris_ruangan;
                } else {
                    return "-";
                }
            })
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
            ->addColumn('tgl_komplain', function ($item) {
                return strftime("%A, %d %B %Y %H:%M:%S", strtotime($item->created_at));
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
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_komplain_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesTanggapiKomplainSudah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataSarpras::fetchDataTanggapiKomplain($auth_data, 1);

        return Datatables::of($list_data)
            ->addColumn('nm_ruangan', function ($item) {
                return $item->nm_ruangan . " - " . $item->nm_jenis_ruangan;
            })
            ->addColumn('nm_inventaris_ruangan', function ($item) {
                if (!empty($item->nm_inventaris_ruangan)) {
                    return $item->nm_inventaris_ruangan;
                } else {
                    return "-";
                }
            })
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
                    if (!empty($item->gelar_depan_staff_sarpras) && !empty($item->gelar_belakang_staff_sarpras)) {
                        return $item->gelar_depan_staff_sarpras . " " . $item->nm_pengguna_staff_sarpras . ", " . $item->gelar_belakang_staff_sarpras;
                    } elseif (!empty($item->gelar_depan_staff_sarpras)) {
                        return $item->gelar_depan_staff_sarpras . " " . $item->nm_pengguna_staff_sarpras;
                    } elseif (!empty($item->gelar_belakang_staff_sarpras)) {
                        return $item->nm_pengguna_staff_sarpras . ", " . $item->gelar_belakang_staff_sarpras;
                    } else {
                        return $item->nm_pengguna_staff_sarpras;
                    }
                }
            })
            ->addColumn('tgl_komplain', function ($item) {
                return strftime("%A, %d %B %Y %H:%M:%S", strtotime($item->created_at));
            })
            ->addColumn('tgl_perbaikan', function ($item) {
                return strftime("%A, %d %B %Y %H:%M:%S", strtotime($item->tgl_perbaikan));
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
                    'id' => $item->id_komplain_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionTanggapiKomplain(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'tgl_perbaikan' => 'required',
            'keterangan_perbaikan' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'edit') {
                if (auth_data()->pengguna->status_join_table == 2) {
                    // get id_guru
                    $guru = Guru::select('id_guru')
                        ->where('id_pengguna', '=', auth_data()->pengguna->id_pengguna)
                        ->first();

                    $id_guru_sarpras = $guru->id_guru;
                } else {
                    $id_guru_sarpras = null;
                }

                // make object to find id
                $komplainSarpras                        = KomplainSarpras::find($id);
                $komplainSarpras->is_sudah_perbaikan    = 1;
                $komplainSarpras->id_guru_sarpras       = $id_guru_sarpras;
                // convert format date
                $komplainSarpras->tgl_perbaikan         = date_format(date_create($input->tgl_perbaikan), "Y-m-d H:i:s");
                $komplainSarpras->keterangan_perbaikan  = $input->keterangan_perbaikan;
                $komplainSarpras->updated_by            = auth_data()->pengguna->id_pengguna;
                $komplainSarpras->updated_at            = $now;
                $komplainSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'komplain-sarpras/tanggapi-komplain',
                    'message' => 'Tanggapi Komplain Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $komplainSarpras               = KomplainSarpras::find($id);
                $komplainSarpras->deleted_by   = auth_data()->pengguna->id_pengguna;
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
