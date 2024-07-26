<?php

namespace App\Http\Controllers\WaliMurid\Keuangan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\WinpayPHP\WinpayCheckout;
use App\Models\PembayaranTrs;
use App\Models\PembayaranTrsDetail;
use App\Models\TagihanBiaya;
use App\Models\Siswa;

use Auth;
use DB;
use Session;
use Validator;

class TagihanController extends BaseController
{
    public function viewTagihan(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
        $pembayaran_aktif = PembayaranTrs::with('siswa', 'siswa.pengguna')->where('id_siswa', $data_anak_murid_aktif->id_siswa)->where('status_pembayaran', 0)->orderBy('created_at', 'desc')->get();

        return view('wali-murid/keuangan/tagihan/view-tagihan', compact('auth_data', 'pembayaran_aktif'));
    }

    public function datatablesTagihan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        /*$nmeng = array('January', 'February', 'March', '');
        $nmtur = array('Januari', 'Februari', 'Maret, '');
        $dt = 'January 22, 2012';
        $dt = str_ireplace($nmeng, $nmtur, $dt);*/

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $list_data = LibSiswa::fetchTagihanSiswa($auth_data, $data_anak_murid_aktif->id_pengguna);

        return Datatables::of($list_data)
            ->addColumn('biaya_sekolah', function ($item) {
                return $item->nm_kelompok_biaya . " (" . $item->tahun_ajaran . " " . $item->nm_semester . ")";
            })
            ->addColumn('semester', function ($item) {
                return $item->tahun_ajaran . " " . $item->nm_semester;
            })
            ->addColumn('jenis_biaya', function ($item) {
                if ($item->id_jenis_detail_biaya == 4) {
                    return $item->nm_jenis_detail_biaya . " " . $item->nm_bulan . "";
                } else {
                    return $item->nm_jenis_detail_biaya;
                }
            })
            ->addColumn('besar_biaya', function ($item) {
                return "Rp" . number_format($item->besar_biaya);
            })
            ->addColumn('denda_biaya', function ($item) {
                return "Rp" . number_format($item->denda_biaya);
            })
            ->addColumn('besar_pembayaran', function ($item) {
                return "Rp" . number_format($item->besar_pembayaran);
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_tagihan_biaya
                );
                return $data;
            })
            ->make(true);
    }

    public function actionGenerate(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'payment_channel' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => 300,
                'message' => $validator->errors()->first(),
            ]);
        }

        if (empty($input->id_tagihan_biaya)) {
            return response()->json([
                'status'     => 300,
                'message' => 'Mohon pilih siswa terlebih dahulu',
            ]);
        }
        // $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);
        // dd($data_anak_murid_aktif->id_siswa);

        DB::beginTransaction();

        try {
            $now = Carbon::now();
            $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

            $siswa = Siswa::find($data_anak_murid_aktif->id_siswa);

            if (!empty($siswa->id_wali_murid)) {
                $wali_murid_phone = $siswa->wali_murid->nomor_hp_wali_murid;
                $wali_murid_email = '';
            } else {
                $wali_murid_phone = '';
                $wali_murid_email = '';
            }

            $winpay_checkout = new WinpayCheckout;

            $pembayaran_trs = new PembayaranTrs;
            $pembayaran_trs->id_pembayaran_trs = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $pembayaran_trs->id_siswa = $siswa->id_siswa;
            $pembayaran_trs->nomor_transaksi = $this->generateNumberTransaction($now, $auth_data->sekolah_data->prefix);
            $pembayaran_trs->id_semester_bayar = '';
            $pembayaran_trs->status_pembayaran = 0;
            $pembayaran_trs->created_by = $auth_data->pengguna->id_pengguna;
            $pembayaran_trs->save();

            $nomor = 1;
            $subtotal_pembayaran = 0;
            $items = array();
            foreach ($input->id_tagihan_biaya as $id_tagihan_biaya) {
                $tagihan_biaya = TagihanBiaya::with('detail_biaya', 'siswa', 'detail_biaya.biaya', 'detail_biaya.bulan')->where('id_siswa', $siswa->id_siswa)->where('id_tagihan_biaya', $id_tagihan_biaya)->where('is_tagih', 1)->where('is_request', 0)->first();
                $tagihan_biaya->is_request = 1;
                $tagihan_biaya->save();

                if ($nomor == 1) {
                    if ($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4) {
                        $trs_keterangan = $tagihan_biaya->detail_biaya->biaya->nm_biaya . ' ' . $tagihan_biaya->detail_biaya->bulan->nm_bulan;
                    } else {
                        $trs_keterangan = $tagihan_biaya->detail_biaya->biaya->nm_biaya;
                    }
                } else {
                    if ($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4) {
                        $trs_keterangan .= ', ' . $tagihan_biaya->detail_biaya->biaya->nm_biaya . ' ' . $tagihan_biaya->detail_biaya->bulan->nm_bulan;
                    } else {
                        $trs_keterangan .= ', ' . $tagihan_biaya->detail_biaya->biaya->nm_biaya;
                    }
                }

                $pembayaran_trs_detail = new PembayaranTrsDetail;
                $pembayaran_trs_detail->id_pembayaran_trs_detail = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $pembayaran_trs_detail->id_pembayaran_trs = $pembayaran_trs->id_pembayaran_trs;
                $pembayaran_trs_detail->id_tagihan_biaya = $tagihan_biaya->id_tagihan_biaya;
                $pembayaran_trs_detail->besar_pembayaran = $tagihan_biaya->besar_biaya;
                $pembayaran_trs_detail->save();

                $subtotal_pembayaran += $tagihan_biaya->besar_biaya;
                $nomor++;

                if ($tagihan_biaya->detail_biaya->id_jenis_detail_biaya == 4) {
                    $item_title = $tagihan_biaya->detail_biaya->biaya->nm_biaya . ' ' . $tagihan_biaya->detail_biaya->bulan->nm_bulan;
                } else {
                    $item_title = $tagihan_biaya->detail_biaya->biaya->nm_biaya;
                }

                $items[] = array(
                    "name" => $item_title,
                    "qty" => 1,
                    "price" => $tagihan_biaya->besar_biaya
                );
            }


            $pembayaran_trs->besar_pembayaran = $subtotal_pembayaran;
            $pembayaran_trs->keterangan = $trs_keterangan;
            $pembayaran_trs->save();

            if (empty($wali_murid_phone)) {
                $wali_murid_phone = '0859106809904';
            }

            if (empty($wali_murid_email)) {
                $wali_murid_email = 'admin@edumate.id';
            }

            $array_payload = [
                "customer" => [
                    "name" => $tagihan_biaya->siswa->pengguna->nm_pengguna,
                    "email" => $wali_murid_email,
                    "phone" => $wali_murid_phone,
                ],
                "invoice" => [
                    "ref" => $pembayaran_trs->nomor_transaksi,
                    "products" => $items
                ],
                "back_url" => "https://edumate.id",
                "interval" => 120
            ];

            $response = $winpay_checkout->process($array_payload);

            $pembayaran_trs->token = $response->responseData->id;
            // $pembayaran_trs->payment_channel = $return_array->data->payment_method;
            // $pembayaran_trs->payment_code = $return_array->data->payment_method_code;
            // $pembayaran_trs->fee_admin = $return_array->data->fee_admin;
            $pembayaran_trs->save();

            DB::commit();

            return response()->json([
                'status'     => 202,
                'message' => 'Success',
                'path' => 'keuangan/tagihan',
                'data' => json_encode($response)
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status'     => 300,
                'status_text'     => 'Failed',
                'message' => 'Terdapat error '
            ]);
        }
    }

    public function generateNumberTransaction($tanggal_transaksi, $kode_sekolah)
    {
        $tanggal_totime = strtotime($tanggal_transaksi);

        $tahun = date("Y", $tanggal_totime);
        $bulan = date("m", $tanggal_totime);

        $max_nomor_transaksi = DB::select('SELECT MAX(nomor_transaksi) AS maxID FROM `pembayaran_trs`')[0]->maxID;
        if ($max_nomor_transaksi == '') {
            $nomor_transaksi = $tahun . "" . $bulan . "000001";
        } else {
            $max_id = substr($max_nomor_transaksi, 0, 12);
            $nomor_urut = (int) substr($max_id, 6, 6);
            $nomor_bulan = (int) substr($max_id, 4, 2);
            if ($nomor_bulan != $bulan) {
                $nomor_urut = 1;
            } else {
                $nomor_urut++;
            }
            $nomor_transaksi = $tahun . "" . $bulan . sprintf("%06s", $nomor_urut) . "-" . $kode_sekolah;
        }
        return $nomor_transaksi;
    }
}
