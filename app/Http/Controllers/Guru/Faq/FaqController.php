<?php

namespace App\Http\Controllers\Guru\Faq;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

use function JmesPath\search;

class FaqController extends BaseController
{
    public function viewIndex(Request $request, $search = null){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $pengguna = Pengguna::get();
        $faq = collect((object) [
            (object) [
                'question' => 'Apabila muncul notifikasi gagal mengirimkan akun verifikasi, apa yang harus saya lakukan?',
                'answer' => '<p>Pastikan terlebih dahulu data yang didaftarkan :</p>
                                <ol>
                                    <li>Masukkan username dan password lalu klik login</li>
                                    <li>Apabila gagal untuk log in dan muncul “Sign in failed”</li>
                                    <li>Klik “lupa password” untuk mengubah password</li>
                                </ol>',
            ],
            (object) [
                'question' => 'Kenapa belum mendapat email verifikasi?',
                'answer' => '<p>
                                Hal ini bisa disebabkan karena handphone atau device belum sync ke email. Bisa juga 
                                terjadi karena kesalahan <i>input</i> alamat <i>email (typo)</i>, alamat <i>email</i> yang di cek dengan yang 
                                didaftarkan berbeda, sehingga <i>email</i> tidak diterima.
                            </p>
                            <p>Apa yang bisa dilakukan :</p>
                            <ol>
                                <li>Pastikan terlebih dahulu <i>email</i> yang didaftarkan tidak salah dalam penulisan</li>
                                <li>Pastikan kembali sudah mengecek di seluruh <i>folder email</i> terdaftar, <i>inbox, promotion, 
                                    spam, junk,</i> dan <i>all mail</i></li>
                                <li>Pastikan <i>Mark as Not Spam</i> bagi email yang masuk ke folder spam</li>
                                <li>Pastikan maksimum pengiriman <i>email</i> verifikasi adalah 1 x 24 jam setelah mendaftar 
                                    di <i>website recruitment</i></li>
                            </ol>',
            ],
            (object) [
                'question' => 'Bagaimana kalau sudah melebihi 1 x 24 jam belum mendapat email verifikasi ?',
                'answer' => '<ol>
                                <li>
                                    Pelamar dapat melakukan pendaftaran ulang (re-registrasi) dengan menggunakan ID 
                                    KTP dan alamat <i>email</i> yang sama. Jangan sampai beda, karena sistem akan kembali 
                                    mengirimkan <i>email</i> verifikasi ke <i>email</i> yang didaftarkan.
                                </li>
                                <li>
                                    Setelah melakukan pendaftaran ulang (re-registrasi), cek secara berkala seluruh 
                                    folder <i>email, inbox, promotion, spam, junk,</i> dan <i>all mail</i>.
                                </li>
                            </ol>
                            <p>
                                Jika masih mengalami kendala yang sama setelah 1x24 jam, silakan hubungi tim diakad
                            </p>',
            ],
            (object) [
                'question' => 'Bagaimana jika saya lupa password?',
                'answer' => '<ol>
                                <li>
                                klik tombol lupa <i>password</i> di halaman login. Kemudian reset <i>password</i> akan dikirimkan 
                                ke <i>email</i> yang didaftarkan
                                </li>
                            </ol>',
            ],
            (object) [
                'question' => 'Mengapa saya selalu gagal melakukan lupa password?',
                'answer' => '<P>
                                Kejadian seperti ini bisa terjadi karena proses penggantian <i>password</i> yang terputus, bisa 
                                disebabkan karena jaringan yang tidak stabil atau session untuk akses <i>website</i> telah 
                                berakhir. Hal-hal yang harus dilakukan:
                            </P>
                            <ol>
                                <li>Pastikan telah melakukan “Lupa <i>Password</i>” dan memasukan <i>email</i> yang terdaftar di sistem.</li>
                                <li>Cek email “<i>Reset Password</i>” di seluruh folder <i>email, inbox, spam, junk,</i> dan <i>all mail.</i></li>
                                <li>Klik tombol “<i>Reset Password</i>” yang ada di email.</li>
                                <li>Masukkan <i>password</i> baru sesuai dengan ketentuan (minimum 8 karakter, gabungan angka, huruf kecil, serta huruf kapital) dan ulangi <i>password</i>.</li>
                                <li>Klik <i>“Reset”</i> sampai muncul notifikasi “<i>Password telah diubah</i>”.</li>
                                <li>Lakukan <i>login</i> menggunakan <i>email</i> dan <i>password</i> yang telah diubah.</li>
                            </ol>',
            ],
            (object) [
                'question' => 'Email dan password salah. Bagaimana caranya agar dapat login kembali?SMA Wahid Hasyim 3 Taman',
                'answer' => '<P>
                                Hal ini dapat disebabkan oleh 2 kondisi, yaitu <i>email</i> belum terdaftar atau salah 
                                penulisan <i>email</i> dan password. Jika <i>email</i> belum terdaftar, silahkan
                                melakukan <i>register</i>  terlebih dahulu. Jika akun lupa <i>password</i>, silahkan klik 
                                tombol <b>Lupa Password</b> di halaman login. Kemudian <i>reset password</i> akan dikirimkan 
                                ke <i>email</i> Anda
                            </P>',
            ],
        ]);

        // $tes = $faq->when(request()->search, function ($faq) {
        //     $faq = $faq->where('question', 'like', '%' . request()->search . '%');
        // });
        // dd($search);
        if ($search!=null) {
            $cari= $search;
            $faq=$faq->filter(function ($item) use ($cari) {
                // replace stristr with your choice of matching function
                return false !== stristr($item->question, $cari);
            });
        }
        return (view('guru/faq/index',compact('auth_data','faq')));
    }

    public function SearchFaq(Request $request){
        
        // dd($request);

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'faq/lihat-faq/'.$request->search,
            
        ];
               
    }
}
