<?php

namespace App\Http\Controllers\PPDB\Pendaftaran;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Libraries\Ppdb\LibVoucherTarif as LibVoucherTarif;
use App\Models\Semester as Semester;
use App\Models\Jurusan as Jurusan;
use App\Models\Voucher as Voucher;
use App\Models\VoucherTarif as VoucherTarif;

use Validator;
use Carbon\Carbon;

/**
 * Pembukaan Voucher Controller
 * @author irianto
 */
class PembukaanVoucherController extends Controller
{
    /**
     * View page awal pembukaan voucher, show list data penerimaan
     * @param Request
     * @return View
     */
    public function viewPembukaanVoucher(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });;

        return view('ppdb/pendaftaran/pembukaan-voucher/view-pembukaan-voucher', compact('auth_data', 'penerimaan', 'grup_penerimaan_tahun'));
    }

    /**
     * Action post view for editing pembuatan voucher
     * @param String id_penerimaan
     * @return Code 300 fail, 204 success
     */
    public function actionViewPembuatanVoucher(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator  = Validator::make($request->all(), [
            'id_penerimaan' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            return [
                'status'    => 204, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/pembukaan-voucher/' . $input->id_penerimaan
            ];
        }
    }

    /**
     * View detail pembuatan voucher, to show voucher at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function pembukaanVoucher(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        /** retrieve voucher tarif by id_semester */
        $voucher_tarif = LibVoucherTarif::getVoucherTarifBySemester($penerimaan->id_semester);

        /** retrieve voucher by id_penerimaan */
        $vouchers = Voucher::select('voucher.id_voucher', 'voucher.id_penerimaan', 'voucher_tarif.tarif', 'kode_voucher', 'pin_password', 'tgl_ambil', 'tgl_bayar')
            ->where('voucher.id_penerimaan', $penerimaan->id_penerimaan)
            ->leftJoin('voucher_tarif', 'voucher.id_voucher_tarif', 'voucher_tarif.id_voucher_tarif')
            ->get();

        return view('ppdb/pendaftaran/pembukaan-voucher/pembukaan-voucher', compact('auth_data', 'penerimaan', 'voucher_tarif', 'vouchers'));
    }

    /**
     * View when admin ppdb adding tarif at spesific gelombang penerimaan
     * @param String id_penerimaan
     * @return View
     */
    public function addPembukaanVoucher(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id_penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        /** get all data jurusan */
        $jurusan = Jurusan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->orderBy('nm_jurusan', 'asc')->get();

        return view('ppdb/pendaftaran/pembukaan-voucher/add-pembukaan-voucher', compact('auth_data', 'penerimaan', 'jurusan'));
    }

    /**
     * action for add pembukaan voucher (add)
     * @param String id_penerimaan
     * @return Bool true for success / false for fail
     */
    public function actionAddPembukaanVoucher(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_penerimaan'     => 'required',
            'status_tarif'      => 'required',
            'id_semester'       => 'required',
            'deskripsi'         => 'sometimes',
            'tarif'             => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        } else {
            /** take time now attribute */
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            /** get penerimaan by id */
            $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

            /** data (id_penerimaan) tidak ditemukan */
            if (!$penerimaan) {
                return [
                    'status'    => 300, // FAILED
                    'message'   => 'Id data penerimaan tidak valid'
                ];
            }

            /** get semester by id semester */
            $semester = Semester::where('id_semester', $input->id_semester)->first();

            if (!$semester) {
                return [
                    'status'    => 300, // FAILED
                    'message'   => 'Id semester tidak valid'
                ];
            }

            /** generate id voucher tarif */
            $id_voucher_tarif = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

            $VoucherTarif                   = new VoucherTarif;
            $VoucherTarif->id_voucher_tarif = $id_voucher_tarif;
            $VoucherTarif->id_semester      = $penerimaan->id_semester;
            $VoucherTarif->id_jurusan       = ($input->status_tarif == "2" ? $input->id_jurusan : 0); // general
            $VoucherTarif->tarif            = $input->tarif;
            $VoucherTarif->deskripsi        = $input->deskripsi;
            $VoucherTarif->created_by       = $input->auth_data->pengguna->id_pengguna;
            $VoucherTarif->save();

            /** send return result */
            return [
                'status'    => 202, // SUCCESS AND LOAD CONTENT
                'path'      => 'pendaftaran/pembukaan-voucher/' . $input->id_penerimaan,
                'message'   => 'Add Tarif Voucher Successfully'
            ];
        }
    }

    /**
     * Action for deleting voucher tarif
     * @param String id_penerimaan,id_voucher_tarif
     * @return Bool true for success / false for fail
     */
    public function actionDeleteVoucherTarif(Request $request, $id_penerimaan, $id_voucher_tarif)
    {
        $input          = (object) $request->input();
        $VoucherTarif   = VoucherTarif::find($id_voucher_tarif);

        /** validasi (check voucher tarif is exist) */
        if ($VoucherTarif == null) {
            return [
                'status' => 300, // VOUCHER TARIF NOT EXIST
                'message' => 'Failed To Delete Voucher Tarif'
            ];
        }

        $kode_voucher_exist = Voucher::select('id_voucher')
            ->where('id_voucher_tarif', '=', $id_voucher_tarif)
            ->where('deleted_at', '=', null)
            ->first();

        if ($kode_voucher_exist != null) {
            return [
                'status' => 300,
                'message' => "Tarif tidak dapat dihapus karena tarif telah digunakan pada voucher"
            ];
        }

        /** deleting data voucher tarif */
        $VoucherTarif->deleted_by  = $input->auth_data->pengguna->id_pengguna;
        $VoucherTarif->save();
        $VoucherTarif->delete();

        return [
            'status'  => 202, // SUCCESS AND LOAD CONTENT
            'path'    => 'pendaftaran/pembukaan-voucher/' . $id_penerimaan,
            'message' => 'Delete Voucher Tarif Successfully'
        ];
    }

    /**
     * Generate voucher form view
     * @param $id_penerimaan
     * @return view
     */
    public function generateVoucher(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        /** get penerimaan by id */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data, $id_penerimaan);

        /** data (id_penerimaan) tidak ditemukan */
        if (!$penerimaan) {
            abort(404);
        }

        /** retrieve voucher tarif by id_semester */
        $voucher_tarif = LibVoucherTarif::getVoucherTarifBySemester($penerimaan->id_semester);

        return view('ppdb/pendaftaran/pembukaan-voucher/generate-voucher', compact('auth_data', 'penerimaan', 'voucher_tarif'));
    }


    /**
     * Action method to generate voucher
     * @param request details data generate voucher
     * @return Bool true for success / false for fail
     */
    public function actionGenerateVoucher(Request $request, $id_penerimaan)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_penerimaan'     => 'required',
            'id_voucher_tarif'  => 'required',
            'code_prefix'       => 'required',
            'seri_awal'         => 'required|numeric',
            'n_voucher'         => 'required|numeric|max:999', // maksimum 999
            'n_digit'           => 'required|numeric|max:25',
        ]);

        if ($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }

        // init
        $n_voucher  = (int) $input->n_voucher;
        $seri_awal  = (int) $input->seri_awal;
        $prefix     = $input->code_prefix;
        $n_digit    = (int) $input->n_digit;

        $vouchers = [];

        // generate voucher
        for ($i = 0; $i < $n_voucher; $i++) {
            $no_seri = $seri_awal + $i;

            /** take time now attribute */
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            /** create code voucher */
            $kode_voucher = $prefix . str_pad($no_seri, $n_digit, "0", STR_PAD_LEFT);

            /** cek kode voucher is available */
            $kode_voucher_exist = Voucher::select('id_voucher', 'kode_voucher')
                ->where('kode_voucher', '=', $kode_voucher)
                ->first();

            if ($kode_voucher_exist) {
                return [
                    'status'    => 300, // FAILED
                    'message'   => "Code voucher must be uniuqe."
                ];
            }

            $vouchers[] = [
                'id_voucher'        => $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid(),
                'id_voucher_tarif'  => $input->id_voucher_tarif,
                'id_penerimaan'     => $input->id_penerimaan,
                'kode_voucher'      => $kode_voucher,
                'pin_password'      => strtoupper(Str::random(6)), // uppercase string random
                'is_aktif'          => 1,
                'created_at'        => new \DateTime(),
                'created_by'        => $input->auth_data->pengguna->id_pengguna
            ];
        }

        Voucher::insert($vouchers);

        return [
            'status'  => 202, // SUCCESS AND LOAD CONTENT
            'path'    => 'pendaftaran/pembukaan-voucher/' . $id_penerimaan,
            'message' => 'Generate Voucher Successfully'
        ];
    }

    /**
     * Action for deleting voucher
     * @param String id_voucher
     * @return json success or fail
     */
    public function actionDeleteVoucher(Request $request, $id_penerimaan, $id_voucher)
    {
        $input     = (object) $request->input();
        $voucher   = Voucher::find($id_voucher);

        /** validasi (check voucher tarif is exist) */
        if ($voucher == null) {
            return [
                'status' => 300, // VOUCHER NOT EXIST
                'message' => 'Failed To Delete Voucher'
            ];
        }

        // =================================
        // cek apakah voucher sudah di ambil
        // =================================

        $voucher->deleted_by  = $input->auth_data->pengguna->id_pengguna;
        $voucher->save();
        $voucher->delete();

        return [
            'status'  => 202, // SUCCESS AND LOAD CONTENT
            'path'    => 'pendaftaran/pembukaan-voucher/' . $id_penerimaan,
            'message' => 'Delete Voucher Successfully'
        ];
    }
}
