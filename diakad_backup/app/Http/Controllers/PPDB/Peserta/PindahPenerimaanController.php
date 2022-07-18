<?php

namespace App\Http\Controllers\PPDB\Peserta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Ppdb\LibPenerimaan as LibPenerimaan;
use App\Models\Voucher as Voucher;
use App\Models\Bank as Bank;
use App\Models\BankVia as BankVia;
use Validator;
use Carbon\Carbon;

class PindahPenerimaanController extends Controller
{
    
    /** 
     * View pindah penerimaan 
     * @return view
     */
    public function viewPindahPenerimaan(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;
        $voucher    = null;
        
        return view('ppdb/peserta/pindah-penerimaan/pindah-penerimaan', compact('voucher'));
    }

    /** 
     * Action for find voucher
     * @param String kode_voucher
     * @return Model Voucher
     */
    public function findVoucher(Request $request)
    {
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;
        $kode_voucher   = $input->kode_voucher;

        $validator = Validator::make($request->all(), [
            'kode_voucher' => 'required'
        ]);
        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
        
        $voucher = Voucher::where('kode_voucher', '=', $kode_voucher)->first();
        if($voucher == null) {
            return [
                'status' => 300,
                'message' => 'Voucher not exist'
            ];
        }

        return [
            'status'    => 204, // SUCCESS AND LOAD CONTENT
            'path'      => 'peserta/pindah-penerimaan/'.$kode_voucher
        ];
    }

    /** 
     * View detail voucher
     * @param string kode_voucher
     */
    public function showVoucher(Request $request, $kode_voucher)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $voucher = Voucher::select(
                        'voucher.id_voucher', 'voucher.id_penerimaan', 'voucher.kode_voucher', 'voucher.pin_password', 'voucher.tgl_ambil', 'voucher.tgl_bayar', 'voucher_tarif.tarif', 'penerimaan.nm_penerimaan', 'voucher.besar_biaya', 'voucher.is_tagih_bank', 'voucher.nomor_transaksi', 'voucher.id_bank', 'voucher.id_bank_via', 'calon_siswa_baru.nm_c_siswa'
                    )
                    ->where('voucher.kode_voucher', '=', $kode_voucher)
                    ->leftJoin('voucher_tarif', 'voucher.id_voucher_tarif', '=', 'voucher_tarif.id_voucher_tarif')
                    ->leftJoin('calon_siswa_baru', 'voucher.kode_voucher', '=', 'calon_siswa_baru.kode_voucher')
                    ->leftJoin('penerimaan', 'voucher.id_penerimaan', '=', 'penerimaan.id_penerimaan')
                    ->first();
        if($voucher == null) {
            return [
                'status'    => 204, // VOUCHER NOT EXIST
                'path'      => 'peserta/pindah-penerimaan'
            ];
        }

        $grup_penerimaan_tahun = null;

        /** get all data penerimaan */
        $penerimaan = LibPenerimaan::fetchDataPenerimaan($auth_data);

        /** groupping by year and semester */
        $grup_penerimaan_tahun = $penerimaan->groupBy('tahun_penerimaan')->transform(function($item, $k) {
            return $item->groupBy('nm_semester_penerimaan');
        });

        /** get all data penerimaan */
        $penerimaan_tujuan = LibPenerimaan::fetchDataPindahPenerimaan($auth_data, $voucher->id_penerimaan);

        $bank       = Bank::all();
        $bank_via   = BankVia::all();

        return view('ppdb/peserta/pindah-penerimaan/pindah-penerimaan', compact('voucher', 'bank', 'bank_via', 'grup_penerimaan_tahun', 'penerimaan_tujuan'));
    }

    /** 
     * Action post for pindah penerimaan on voucher
     * @param $id_voucher @id_penerimaan_tujuan
     */
    public function actionPindahVoucher(Request $request, $kode_voucher)
    {
        $input          = (object) $request->input();
        $auth_data      = $input->auth_data;
        $kode_voucher   = $input->kode_voucher;

        $validator = Validator::make($request->all(), [
            'kode_voucher'      => 'required',
            'penerimaan_tujuan' => 'required'
        ]);
        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
        
        $voucher = Voucher::where('kode_voucher', '=', $kode_voucher)->first();
        if($voucher == null) {
            return [
                'status' => 300,
                'message' => 'Voucher not exist'
            ];
        }

        $penerimaan_tujuan = LibPenerimaan::fetchDataPenerimaan($auth_data, $input->penerimaan_tujuan);
        if($penerimaan_tujuan == null) {
            return [
                'status' => 300,
                'message' => 'Penerimaan Tujuan not exist'
            ];
        }

        // change id_penerimaan on voucher (done)
        // change id_penerimaan on calon_siswa_baru 

        $voucher->id_penerimaan = $input->penerimaan_tujuan;
        $voucher->updated_by    = $input->auth_data->pengguna->id_pengguna;
        $voucher->save();

        return [
            'status'    => 204,
            'message'   => 'Voucher berhasil dipindah',
            'path'      => 'peserta/pindah-penerimaan/'.$kode_voucher
        ];
        
    }

}
