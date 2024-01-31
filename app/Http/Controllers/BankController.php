<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use App\Models\PembayaranTrs;
use App\Models\Siswa;
use App\Models\Sekolah;
use App\Models\Semester;
use Carbon\Carbon;
use App\Models\TagihanBiaya;
use App\Models\PembayaranBiaya;
use App\Models\PembayaranTrsDetail;
use DB;
use DateTimeImmutable;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Builder;


class BankController extends BaseController
{

    public function generateJwt(Request $request)
    {
        $kode_request       = $request->input('Kode');
        $nis_siswa = $request->input('nis_siswa');
        $kode_tagihan = $request->input('kode_tagihan');
        $nomor_transaksi = $request->input(('nomor_transaksi'));
        $total_pembayaran = $request->input('total_pembayaran');

        $key   = InMemory::base64Encoded(
            'crGyqM0orACXsibBpa5HyU9hFlsOHWCrnlQENXmzY6wrZSXOdtBbbdoVxf8lmDkG'
        );
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::
            default()));
        $algorithm    = new Sha256();

        $now   = new DateTimeImmutable();
        $token = $tokenBuilder
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'));
        if ($kode_request == 'A0001') {
            $token->withClaim('nis_siswa', $nis_siswa);
        } elseif ($kode_request == 'A0002') {
            $token->withClaim('nis_siswa', $nis_siswa);
            $token->withClaim('kode_tagihan', $kode_tagihan);
        } elseif ($kode_request == 'A0003') {
            $token->withClaim('nomor_transaksi', $nomor_transaksi);
        } elseif ($kode_request == 'A0004') {
            $token->withClaim('nomor_transaksi', $nomor_transaksi);
            $token->withClaim('total_pembayaran', $total_pembayaran);
        } elseif ($kode_request == 'A0005') {
            $token->withClaim('nomor_transaksi', $nomor_transaksi);
        }
        return $token->withHeader('foo', 'bar')
            ->getToken($algorithm, $key)->toString();
    }


    public function processJwt(Request $request)
    {
        $jwtEncoded = $request->input('JwtEncoded');
        $kode_request       = $request->input('Kode');
        //Validasi Signature
        $now   = new DateTimeImmutable();
        $key   = InMemory::base64Encoded(
            'crGyqM0orACXsibBpa5HyU9hFlsOHWCrnlQENXmzY6wrZSXOdtBbbdoVxf8lmDkG'
        );
        $algorithm    = new Sha256();

        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::
            default()));

        $retunToken = $tokenBuilder
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+1 hour'));


        try {
            $token = (new JwtFacade())->parse(
                $jwtEncoded,
                new Constraint\SignedWith($algorithm, $key),
                new Constraint\StrictValidAt(
                    new FrozenClock($now)
                )
            );
        } catch (\Exception $e) {

            $retunToken->withClaim('kode', '01');
            $retunToken->withClaim('keterangan', 'Invalid signature (jwt)');
            return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
        }

        //Validasi Decode
        try {
            $claims             = $token->claims();
            $nis_siswa          = $claims->get('nis_siswa');
            $kode_tagihan       = $claims->get('kode_tagihan');
            $total_pembayaran   = $claims->get('total_pembayaran');
            $nomor_transaksi    = $claims->get('nomor_transaksi');
        } catch (\Exception $e) {

            $retunToken->withClaim('kode', '02');
            $retunToken->withClaim('keterangan', 'Decode jwt error (format jwt error)');
            return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
        }

        //Validasi Institusi
        $now = Carbon::now();
        $sekolah = Sekolah::first();

        if ($kode_request == 'A0001') {
            $siswa = Siswa::where('nis_siswa', $nis_siswa)->first();
            if (empty($siswa)) {
                $token->withClaim('kode', '03');
                $token->withClaim('keterangan', 'Siswa Not Found');
                return $token->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }
            //Get Data Tagihan
            $tahun =  Carbon::parse($now)->format("Y");
            $semester = Semester::where('thn_akademik_semester', $tahun)->first();
            $tagihan = TagihanBiaya::with('detail_biaya.biaya_sekolah.semester')->where('is_tagih', '1')->where('id_siswa', $siswa->id_siswa)->get();
            $total_tagihan = $tagihan->sum('besar_biaya');
            $jumlah_tagihan = $tagihan->count();
            $kode_tagihan = array();

            foreach ($tagihan as $t) {
                if ($t->detail_biaya->id_jenis_detail_biaya == '4') {
                    $tahun_semester = $t->detail_biaya->biaya_sekolah->semester->tahun_ajaran;
                    $kode_tagihan[strtolower(str_replace(' ', '_', $t->detail_biaya->keterangan_biaya . '_' . $t->detail_biaya->id_bulan . '_' . $tahun_semester))] = $t->besar_biaya;
                } else {
                    $kode_tagihan[strtolower(str_replace(' ', '_', $t->detail_biaya->keterangan_biaya))] = $t->besar_biaya;
                }
            }

            $data = [
                "nis_siswa" => $nis_siswa,
                "total_tagihan" => $total_tagihan,
                "jumlah_tagihan" => $jumlah_tagihan,
                "kode_tagihan" =>  $kode_tagihan
            ];

            $retunToken->withClaim('kode', '00');
            $retunToken->withClaim('keterangan', 'success get data tagihan');
            $retunToken->withClaim('data', $data);
            return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
        } elseif ($kode_request == 'A0002') {
            $siswa = Siswa::where('nis_siswa', $nis_siswa)->first();
            if (empty($siswa)) {
                $retunToken->withClaim('kode', '03');
                $retunToken->withClaim('keterangan', 'Siswa Not Found');
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }
            $data_tagihan = explode(";", $kode_tagihan);
            $total_tagihan = 0;
            $list_id_tagihan = array();
            foreach ($data_tagihan as $no => $k_tagihan) {
                $nm_tagihan = explode("_", $k_tagihan);
                if ($nm_tagihan[0] == 'spp') {
                    $tagihan = TagihanBiaya::where('is_tagih', '1')->where('id_siswa', $siswa->id_siswa)->whereHas('detail_biaya', function ($q) use ($nm_tagihan) {
                        $q->where('id_bulan', $nm_tagihan[1]);
                    })->whereHas('detail_biaya.biaya_sekolah.semester', function ($q) use ($nm_tagihan) {
                        $q->where('tahun_ajaran', $nm_tagihan[2]);
                    })->first();
                    if ($tagihan) {
                        $total_tagihan += $tagihan->besar_biaya;
                        $list_id_tagihan[$no] = $tagihan->id_tagihan_biaya;
                    } else {
                        $retunToken->withClaim('kode', '05');
                        $retunToken->withClaim('keterangan', 'Kode Tagihan ' . $k_tagihan . ' Not Found');
                        return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
                    }
                } else {
                    $keterangan_biaya = strtoupper(str_replace('_', ' ', $k_tagihan));
                    $tagihan = TagihanBiaya::where('is_tagih', '1')->where('id_siswa', $siswa->id_siswa)->whereHas('detail_biaya', function ($q) use ($keterangan_biaya) {
                        $q->where('keterangan_biaya', $keterangan_biaya);
                    })->first();
                    if ($tagihan) {
                        $total_tagihan += $tagihan->besar_biaya;
                        $list_id_tagihan[$no] = $tagihan->id_tagihan_biaya;
                    } else {
                        $retunToken->withClaim('kode', '05');
                        $retunToken->withClaim('keterangan', 'Kode Tagihan ' . $k_tagihan . ' Not Found');
                        return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
                    }
                }
            }

            DB::beginTransaction();

            do {
                $sama = false;
                $generate_va =  random_int(1000000000, 9999999999);
                if (PembayaranTrs::where('nomor_transaksi', $generate_va)->first()) {
                    $sama = true;
                }
            } while ($sama);


            try {
                $semester = Semester::where('is_aktif_semester', '1')->first();
                $pembayaran_trs = new PembayaranTrs;
                $pembayaran_trs->id_pembayaran_trs  = $sekolah->prefix . strtotime($now) . uniqid();
                $pembayaran_trs->id_siswa           = $siswa->id_siswa;
                $pembayaran_trs->nomor_transaksi   = $generate_va; //va
                $pembayaran_trs->besar_pembayaran   = $total_tagihan;
                $pembayaran_trs->status_pembayaran  = 0; //belum lunas
                $pembayaran_trs->token              = '';
                $pembayaran_trs->id_semester_bayar  = $semester->id_semester;
                $pembayaran_trs->tgl_pembayaran     = null;
                $pembayaran_trs->keterangan         = 'tagihan kbbs';
                $pembayaran_trs->fee_admin          = 0;
                $pembayaran_trs->payment_code =  $kode_tagihan;
                $pembayaran_trs->payment_channel = 'KBBS';
                $pembayaran_trs->save();

                //detail pembayaran
                foreach ($list_id_tagihan as $id_tagihan) {
                    $tagihan = TagihanBiaya::find($id_tagihan);
                    if ($tagihan) {
                        $pembayaran_trs_detail = new PembayaranTrsDetail;
                        $pembayaran_trs_detail->id_pembayaran_trs_detail = $sekolah->prefix . strtotime($now) . uniqid();
                        $pembayaran_trs_detail->id_pembayaran_trs = $pembayaran_trs->id_pembayaran_trs;
                        $pembayaran_trs_detail->id_tagihan_biaya = $id_tagihan;
                        $pembayaran_trs_detail->besar_pembayaran = $tagihan->besar_biaya;
                        $pembayaran_trs_detail->save();
                    }
                }

                $data = [
                    "nis_siswa" => $nis_siswa,
                    "nomor_transaksi" => $generate_va,
                ];

                DB::commit();
            } catch (\Exception $e) {
                $retunToken->withClaim('kode', '09');
                $retunToken->withClaim('keterangan', $e->getMessage());
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }

            $retunToken->withClaim('kode', '00');
            $retunToken->withClaim('keterangan', 'success create tagihan');
            $retunToken->withClaim('data', $data);
            return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
        } elseif ($kode_request == 'A0003') {

            $pembayaran_trs = PembayaranTrs::where('nomor_transaksi', $nomor_transaksi)->first();

            if ($pembayaran_trs && $pembayaran_trs->status_pembayaran == '1') {
                $siswa = Siswa::with('pengguna')->find($pembayaran_trs->id_siswa);
                $data = [
                    "tagihan" => 0,
                    "nis_siswa" => $siswa->nis_siswa,
                    "nama_siswa" => $siswa->pengguna->nm_pengguna
                ];
                $retunToken->withClaim('kode', '00');
                $retunToken->withClaim('keterangan', 'success inquiry');
                $retunToken->withClaim('data', $data);
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            } elseif ($pembayaran_trs && $pembayaran_trs->status_pembayaran == '0') {
                $siswa = Siswa::with('pengguna')->find($pembayaran_trs->id_siswa);
                $data = [
                    "nomor_transaksi" => $nomor_transaksi,
                    "tagihan" => $pembayaran_trs->besar_pembayaran,
                    'keterangan' => $pembayaran_trs->payment_code,
                    "nis_siswa" => $siswa->nis_siswa,
                    "nama_siswa" => $siswa->pengguna->nm_pengguna
                ];
                $retunToken->withClaim('kode', '00');
                $retunToken->withClaim('keterangan', 'success inquiry');
                $retunToken->withClaim('data', $data);
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            } else {
                $retunToken->withClaim('kode', '07');
                $retunToken->withClaim('keterangan', 'Nomor VA Tidak Ditemukan');
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }
        } elseif ($kode_request == 'A0004') {

            $pembayaran_trs = PembayaranTrs::with('pembayaran_trs_detail')->where('nomor_transaksi', $nomor_transaksi)->first();
            if ($pembayaran_trs && $pembayaran_trs->status_pembayaran == '1') {
                $retunToken->withClaim('kode', '04');
                $retunToken->withClaim('keterangan', 'Duplicate Payment');
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            } elseif ($pembayaran_trs && $pembayaran_trs->status_pembayaran == '0') {
                $semester = Semester::where('is_aktif_semester', '1')->first();
                if ($total_pembayaran >= $pembayaran_trs->besar_pembayaran) {
                    $pembayaran_trs->status_pembayaran  = 1;
                    $pembayaran_trs->tgl_pembayaran     = $now->format("Y-m-d");
                    $pembayaran_trs->fee_admin          = $total_pembayaran - $pembayaran_trs->besar_pembayaran;
                    $pembayaran_trs->save();

                    foreach ($pembayaran_trs->pembayaran_trs_detail as $pembayaran_trs_detail) {
                        $tagihan = TagihanBiaya::find($pembayaran_trs_detail->id_tagihan_biaya);
                        if ($tagihan) {
                            $tagihan->is_tagih = 0;
                            $tagihan->tgl_pelunasan =  $now->format("Y-m-d");
                            $tagihan->besar_pembayaran = $tagihan->besar_biaya;
                            $tagihan->updated_by = "KBBS";
                            $tagihan->save();

                            $pembayaran = new PembayaranBiaya;
                            $pembayaran->id_pembayaran_biaya = $sekolah->prefix . strtotime($now) . uniqid();
                            $pembayaran->id_bank = "02";
                            $pembayaran->id_bank_via = "02";
                            $pembayaran->id_tagihan_biaya = $tagihan->id_tagihan_biaya;
                            $pembayaran->id_semester_bayar = $semester->id_semester;
                            $pembayaran->besar_pembayaran = $tagihan->besar_biaya;
                            $pembayaran->tgl_pembayaran =  $now->format("Y-m-d");
                            $pembayaran->nomor_transaksi = $nomor_transaksi; // va
                            $pembayaran->keterangan = 'Pembayaran Online Bank Bukopin';
                            $pembayaran->created_by = "KBBS";
                            $pembayaran->save();
                        }
                    }

                    $data = [
                        "nomor_transaksi" => $nomor_transaksi,
                    ];
                    $retunToken->withClaim('kode', '00');
                    $retunToken->withClaim('keterangan', 'success payment');
                    $retunToken->withClaim('data', $data);
                    return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
                } else {

                    $retunToken->withClaim('kode', '06');
                    $retunToken->withClaim('keterangan', 'less payment');
                    return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
                }
            } else {
                $retunToken->withClaim('kode', '07');
                $retunToken->withClaim('keterangan', 'Nomor VA Tidak Ditemukan');
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }
        } elseif ($kode_request == 'A0005') {
            $pembayaran_trs = PembayaranTrs::with('pembayaran_trs_detail')->where('nomor_transaksi', $nomor_transaksi)->first();

            if ($pembayaran_trs) {
                foreach ($pembayaran_trs->pembayaran_trs_detail as $pembayaran_trs_detail) {
                    $tagihan = TagihanBiaya::with('pembayaran')->find($pembayaran_trs_detail->id_tagihan_biaya);

                    if ($tagihan) {
                        foreach ($tagihan->pembayaran as $pembayaran) {
                            $pembayaran->deleted_by = 'bank bukopin';
                            $pembayaran->save();
                            $pembayaran->delete();
                        }
                        $tagihan->is_tagih = '1';
                        $tagihan->tgl_pelunasan = null;
                        $tagihan->besar_pembayaran = 0;
                        $tagihan->updated_by = "batal bayar KBBS";
                        $tagihan->save();
                    }

                    // $pembayaran_trs_detail->deleted_by = 'bank bukopin';
                    // $pembayaran_trs_detail->save();
                    // $pembayaran_trs_detail->delete();
                }

                // $pembayaran_trs->deleted_by = 'bank bukopin';
                $pembayaran_trs->status_pembayaran = '0';
                $pembayaran_trs->fee_admin = '0';
                $pembayaran_trs->tgl_pembayaran = null;
                $pembayaran_trs->save();
                // $pembayaran_trs->delete();

                $data = [
                    "nomor_transaksi" => $nomor_transaksi,
                ];

                $retunToken->withClaim('kode', '00');
                $retunToken->withClaim('keterangan', 'success cancel payment');
                $retunToken->withClaim('data', $data);
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            } else {
                $retunToken->withClaim('kode', '07');
                $retunToken->withClaim('keterangan', 'Nomor transaksi Not Found');
                return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
            }
        } else {

            $retunToken->withClaim('kode', '08');
            $retunToken->withClaim('keterangan', 'kode request Not Found');
            return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
        }

        $retunToken->withClaim('kode', '99');
        $retunToken->withClaim('keterangan', 'Error Unknown');
        return $retunToken->withHeader('foo', 'bar')->getToken($algorithm, $key)->toString();
    }
}
