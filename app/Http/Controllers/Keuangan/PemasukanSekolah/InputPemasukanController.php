<?php

namespace App\Http\Controllers\Keuangan\PemasukanSekolah;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PemasukanBiaya as PemasukanBiaya;
use App\Models\Staff as Staff;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Keuangan\LibDataKeuangan;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class InputPemasukanController extends BaseController
{

    public function viewInputPemasukan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('keuangan/pemasukan-sekolah/input-pemasukan/view-input-pemasukan', compact('auth_data'));
    }

    public function addInputPemasukan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        // mengambil waktu sekarang
        $now = Carbon::now();

        $data_subkategori_pemasukan = LibDataKeuangan::fetchDataSubkategoriPemasukan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $id_pemasukan_biaya = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('keuangan/pemasukan-sekolah/input-pemasukan/add-input-pemasukan', compact('auth_data', 'data_subkategori_pemasukan', 'data_semester', 'id_pemasukan_biaya'));
    }

    public function editInputPemasukan($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_subkategori_pemasukan = LibDataKeuangan::fetchDataSubkategoriPemasukan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        $data_pemasukan = LibDataKeuangan::fetchDataPemasukan($auth_data, $id);

        // convert format date
        $tgl_pemasukan_biaya = strftime("%A, %d %B %Y", strtotime($data_pemasukan->tgl_pemasukan_biaya));

        return view('keuangan/pemasukan-sekolah/input-pemasukan/edit-input-pemasukan', compact('auth_data', 'data_subkategori_pemasukan', 'data_semester', 'data_pemasukan', 'tgl_pemasukan_biaya'));
    }

    public function datatablesInputPemasukan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataKeuangan::fetchDataPemasukan($auth_data, null, "1");

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return $item->tahun_ajaran . " " . $item->nm_semester;
            })
            ->addColumn('nm_pemasukan_biaya_subkategori', function ($item) {
                return $item->nm_pemasukan_biaya_subkategori . " - " . $item->nm_pemasukan_biaya_kategori;
            })
            ->addColumn('nm_pengguna', function ($item) {
                if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna . ", " . $item->gelar_belakang;
                } elseif (!empty($item->gelar_depan)) {
                    return $item->gelar_depan . " " . $item->nm_pengguna;
                } elseif (!empty($item->gelar_belakang)) {
                    return $item->nm_pengguna . ", " . $item->gelar_belakang;
                } else {
                    return $item->nm_pengguna;
                }
            })
            ->addColumn('tgl_pemasukan_biaya', function ($item) {
                return strftime("%A, %d %B %Y", strtotime($item->tgl_pemasukan_biaya));
            })
            ->addColumn('besar_pemasukan_biaya', function ($item) {
                return "Rp" . number_format($item->besar_pemasukan_biaya);
            })
            ->addColumn('is_upload_file', function ($item) {
                if ($item->is_upload_file == 1) {
                    return "Ya";
                } else {
                    return "Tidak";
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pemasukan_biaya
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionInputPemasukan(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_semester'                       => 'required',
            'id_pemasukan_biaya_subkategori'    => 'required',
            'tgl_pemasukan_biaya'               => 'required',
            'besar_pemasukan_biaya'             => 'required',
            'keterangan_pemasukan_biaya'        => 'required',
            'is_upload_file'                    => 'required'
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
                if (auth_data()->pengguna->status_join_table == 1) {
                    // get id_guru
                    $staff = Staff::select('id_staff')
                        ->where('id_pengguna', '=', auth_data()->pengguna->id_pengguna)
                        ->first();

                    $id_staff = $staff->id_staff;
                } else {
                    $id_staff = null;
                }

                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $pemasukan                                  = new PemasukanBiaya;
                $pemasukan->id_pemasukan_biaya              = $id;
                $pemasukan->id_pemasukan_biaya_subkategori  = $input->id_pemasukan_biaya_subkategori;
                $pemasukan->id_semester                     = $input->id_semester;
                $pemasukan->id_staff                        = $id_staff;
                // convert format date
                $pemasukan->tgl_pemasukan_biaya             = date_format(date_create($input->tgl_pemasukan_biaya), "Y-m-d H:i:s");
                $pemasukan->besar_pemasukan_biaya           = $input->besar_pemasukan_biaya;
                $pemasukan->keterangan_pemasukan_biaya      = $input->keterangan_pemasukan_biaya;
                $pemasukan->is_upload_file                  = $input->is_upload_file;
                $pemasukan->created_by                      = auth_data()->pengguna->id_pengguna;
                $pemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/input-pemasukan',
                    'message' => 'Save Pemasukan Successfully'
                ];
            } elseif ($mode == 'edit') {
                // make object to find id
                $pemasukan                                    = PemasukanBiaya::find($id);
                $pemasukan->id_pemasukan_biaya_subkategori  = $input->id_pemasukan_biaya_subkategori;
                $pemasukan->id_semester                     = $input->id_semester;
                $pemasukan->id_staff                        = $id_staff;
                // convert format date
                $pemasukan->tgl_pemasukan_biaya             = date_format(date_create($input->tgl_pemasukan_biaya), "Y-m-d H:i:s");
                $pemasukan->besar_pemasukan_biaya           = $input->besar_pemasukan_biaya;
                $pemasukan->keterangan_pemasukan_biaya      = $input->keterangan_pemasukan_biaya;
                $pemasukan->is_upload_file                  = $input->is_upload_file;
                $pemasukan->updated_by                      = auth_data()->pengguna->id_pengguna;
                $pemasukan->updated_at                      = $now;
                $pemasukan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'pemasukan-sekolah/input-pemasukan',
                    'message' => 'Update Pemasukan Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $pemasukan               = PemasukanBiaya::find($id);
                $pemasukan->deleted_by   = auth_data()->pengguna->id_pengguna;
                $pemasukan->save();

                $pemasukan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Pemasukan Successfully'
                ];
            }
        }
    }
}
