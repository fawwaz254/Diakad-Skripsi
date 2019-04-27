<?php

namespace App\Http\Controllers\PPDB\Peserta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voucher as Voucher;
use App\Models\Bank as Bank;
use App\Models\BankVia as BankVia;
use Validator;
use Carbon\Carbon;

class PembayaranFormulirController extends Controller
{
    
    /** 
     * View pembayaran formulir
     * @param Request 
     * @return View
     */
    public function viewPembayaranFormulir(Request $request)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;
        $voucher    = null;

        return view('ppdb/peserta/pembayaran-formulir/pembayaran-formulir', compact('voucher'));
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
        $this->validationCheck($validator);
        
        $voucher = Voucher::where('kode_voucher', '=', $kode_voucher)->first();
        if($voucher == null) {
            return [
                'status' => 300,
                'message' => 'Voucher not exist'
            ];
        }

        return [
            'status'    => 204, // SUCCESS AND LOAD CONTENT
            'path'      => 'peserta/pembayaran-formulir/'.$kode_voucher
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
                'path'      => 'peserta/pembayaran-formulir'
            ];
        }

        $bank       = Bank::all();
        $bank_via   = BankVia::all();

        return view('ppdb/peserta/pembayaran-formulir/pembayaran-formulir', compact('voucher', 'bank', 'bank_via'));
    }

    /** 
     * Throw exception when validator error
     */
    public function validationCheck($validator)
    {
        if($validator->fails()) {
            return [
                'status'    => 300, // FAILED
                'message'   => $validator->errors()->first()
            ];
        }
    }

    /** 
     * Method to bayar voucher
     * @param id_voucher, form_data bayar
     * @return json fail or success
     */
    public function bayarVoucher(Request $request, $kode_voucher)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'kode_voucher'      => 'required',
            'besar_biaya'       => 'required|numeric',
            'metode_pembayaran' => 'required'
        ]);
        $this->validationCheck($validator);

        $voucher = Voucher::where('kode_voucher', '=', $kode_voucher)->first();
        if($voucher == null) {
            return [
                'status' => 300,
                'message' => 'Voucher not exist'
            ];
        }
        // SET DEFAULT
        $voucher->is_tagih_bank = $voucher->nomor_transaksi = $voucher->id_bank = $voucher->id_bank_via  = null;

        /** validasi hanya untuk metode pembayaran == transfer */
        if ($input->metode_pembayaran == 1) {
            $validator_tf = Validator::make($request->all(), [
                'nomor_transaksi'   => 'required',
                'id_bank'           => 'required',
                'id_bank_via'       => 'required'
            ]);
            $this->validationCheck($validator_tf);

            $voucher->is_tagih_bank     = 1; // if voucher dibayar via transfer
            $voucher->id_bank           = $input->id_bank;
            $voucher->id_bank_via       = $input->id_bank_via;
            $voucher->nomor_transaksi   = $input->nomor_transaksi;
        }

        // =====================================
        // cek apakah tanggal bayar sudah diisi?
        // =====================================

        $now                    = Carbon::now(env('APP_TIMEZONE', ''));
        $voucher->tgl_bayar     = $now;
        $voucher->besar_biaya   = $input->besar_biaya;
        $voucher->updated_by    = $input->auth_data->pengguna->id_pengguna;
        $voucher->save();

        return [
            'status'    => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'peserta/pembayaran-formulir/'.$kode_voucher ,
            'message'   => 'Voucher Berhasil Dibayar'
        ];
    }

    /** 
     * Method to delete pembayaran formulir user
     * @param $kode_voucher
     * @return boolean fail or success
     */
    public function deletePembayaranFormulir(Request $request, $kode_voucher)
    {
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;
        
        $validator = Validator::make($request->all(), [
            'kode_voucher'      => 'required'
        ]);
        $this->validationCheck($validator);
        
        $voucher = Voucher::where('kode_voucher', '=', $kode_voucher)->first();
        if($voucher == null) {
            return [
                'status' => 300,
                'message' => 'Voucher not exist'
            ];
        }
            
        // BACK SET TO DEFAULT
        $voucher->is_tagih_bank = null; 
        $voucher->id_bank = null;
        $voucher->id_bank_via = null;
        $voucher->besar_biaya = null;
        $voucher->nomor_transaksi = null;
        $voucher->tgl_bayar = null;
        $voucher->updated_by = $input->auth_data->pengguna->id_pengguna;
        $voucher->save();

        return [
            'status'    => 202, // SUCCESS AND LOAD CONTENT
            'path'      => 'peserta/pembayaran-formulir/'.$kode_voucher ,
            'message'   => 'Voucher Berhasil Direset'
        ];
    }
}
