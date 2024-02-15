<?php

namespace App\Http\Controllers\SaranaPrasarana\PerawatanSarpras;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PerawatanSarpras as PerawatanSarpras;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\SaranaPrasarana\LibDataSarpras;

use Auth;
use DB;
use Session;
use Validator;

class InputPerawatanRutinController extends BaseController
{
    public function viewInputPerawatanRutin(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('sarana-prasarana/perawatan-sarpras/perawatan-rutin/view-perawatan-rutin', compact('auth_data'));
    }

    public function ajaxGetInventarisByRuangan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // ambil data inventaris ruangan
        $data_inventaris = LibDataSarpras::fetchDataInventarisRuangan($auth_data, $input->id_ruangan);

        return $data_inventaris;
    }

    public function addInputPerawatanRutin(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data);

        $id_perawatan_sarpras = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('sarana-prasarana/perawatan-sarpras/perawatan-rutin/add-perawatan-rutin', compact('auth_data', 'data_ruangan', 'data_buku_alat', 'id_perawatan_sarpras'));
    }

    public function editInputPerawatanRutin($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_ruangan = LibDataSarpras::fetchDataRuangan($auth_data);

        $data_inventaris_ruangan = LibDataSarpras::fetchDataInventarisRuangan($auth_data);

        $data_buku_alat = LibDataSarpras::fetchDataBukuAlat($auth_data);

        $data_perawatan_sarpras = LibDataSarpras::fetchDataPerawatanSarpras($auth_data, 0, $id);

        // convert format date
        $tgl_perawatan = strftime("%A, %d %B %Y", strtotime($data_perawatan_sarpras->tgl_perawatan));

        return view('sarana-prasarana/perawatan-sarpras/perawatan-rutin/edit-perawatan-rutin', compact('auth_data', 'data_ruangan', 'data_inventaris_ruangan', 'data_buku_alat', 'data_perawatan_sarpras', 'tgl_perawatan'));
    }

    public function datatablesInputPerawatanRutinBelum(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPerawatanSarpras($auth_data, 0, null, 1);

        return Datatables::of($list_data)
            ->addColumn('nm_ruangan', function ($item) {
                return $item->nm_ruangan . " - " . $item->nm_jenis_ruangan . " (" . $item->nm_gedung . ")";
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
            ->addColumn('tgl_perawatan', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_perawatan));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_perawatan_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    public function datatablesInputPerawatanRutinSudah(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = LibDataSarpras::fetchDataPerawatanSarpras($auth_data, 1, null, 1);

        return Datatables::of($list_data)
            ->addColumn('nm_ruangan', function ($item) {
                return $item->nm_ruangan . " - " . $item->nm_jenis_ruangan . " (" . $item->nm_gedung . ")";
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
            ->addColumn('tgl_perawatan', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_perawatan));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_perawatan_sarpras
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionInputPerawatanRutin(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'tgl_perawatan' => 'required',
            'keterangan_perawatan' => 'required',
            'is_sudah_perawatan' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            if ($mode == 'add') {
                if (!empty($input->id_ruangan) or !empty($input->id_inventaris_ruangan) or !empty($input->id_buku_alat)) {
                    // make object to find id
                    $perawatanSarpras                           = new PerawatanSarpras;
                    $perawatanSarpras->id_perawatan_sarpras     = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                    $perawatanSarpras->id_ruangan               = $input->id_ruangan;
                    $perawatanSarpras->id_inventaris_ruangan    = $input->id_inventaris_ruangan;
                    $perawatanSarpras->id_buku_alat             = $input->id_buku_alat;
                    // convert format date
                    $perawatanSarpras->tgl_perawatan            = date_format(date_create($input->tgl_perawatan), "Y-m-d H:i:s");
                    $perawatanSarpras->keterangan_perawatan     = $input->keterangan_perawatan;
                    $perawatanSarpras->is_sudah_perawatan       = $input->is_sudah_perawatan;
                    $perawatanSarpras->created_by               = $input->auth_data->pengguna->id_pengguna;
                    $perawatanSarpras->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'perawatan-sarpras/input-perawatan-rutin',
                        'message' => 'Input Perawatan Rutin Successfully'
                    ];
                } else {
                    return [
                        'status' => 300, // SUCCESS AND LOAD TABLE
                        'message' => 'Isi Salah Satu (Ruangan, Inventaris atau Buku/Alat)'
                    ];
                }
            } elseif ($mode == 'edit') {
                // make object to find id
                $perawatanSarpras                           = PerawatanSarpras::find($id);
                // convert format date
                $perawatanSarpras->tgl_perawatan            = date_format(date_create($input->tgl_perawatan), "Y-m-d H:i:s");
                $perawatanSarpras->keterangan_perawatan     = $input->keterangan_perawatan;
                $perawatanSarpras->is_sudah_perawatan       = $input->is_sudah_perawatan;
                $perawatanSarpras->updated_by               = $input->auth_data->pengguna->id_pengguna;
                $perawatanSarpras->updated_at               = $now;
                $perawatanSarpras->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'perawatan-sarpras/input-perawatan-rutin',
                    'message' => 'Update Perawatan Rutin Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $perawatanSarpras               = PerawatanSarpras::find($id);
                $perawatanSarpras->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $perawatanSarpras->save();

                $perawatanSarpras->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Perawatan Rutin Successfully'
                ];
            }
        }
    }
}
