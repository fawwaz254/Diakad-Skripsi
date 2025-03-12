<?php

namespace App\Http\Controllers\Guru\Faq;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

use function JmesPath\search;

class FaqController extends BaseController
{
    public function viewIndex(Request $request, $search = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $pengguna = Pengguna::get();
        $faq = collect((object) [
            (object) [
                'question' => 'Apabila muncul notifikasi "Sign in failed", apa yang harus saya lakukan ?',
                'answer' => '<p>Pastikan terlebih dahulu data yang didaftarkan :</p>
                                <ol>
                                    <li>Masukkan username dan password lalu klik login</li>
                                    <li>Apabila gagal untuk login dan muncul “Sign in failed”</li>
                                    <li>Klik “lupa password” untuk mengubah password</li>
                                </ol>',
            ],
            (object) [
                'question' => 'Kenapa belum mendapat email verifikasi ?',
                'answer' => '<p>
                                Hal ini bisa disebabkan karena handphone atau device belum sync ke email. Bisa juga 
                                terjadi karena kesalahan input alamat <i>email (typo)</i>, alamat <i>email</i> pengguna dengan yang 
                                didaftarkan berbeda, sehingga <i>email</i> tidak diterima.
                            </p>
                            <p>Apa yang bisa dilakukan :</p>
                            <ol>
                                <li>Pastikan terlebih dahulu <i>email</i> yang didaftarkan tidak salah dalam penulisan</li>
                                <li>Pastikan kembali sudah mengecek di seluruh folder email terdaftar, inbox, promotion, 
                                    spam, junk, dan <i>all mail</i></li>
                                <li>Pastikan <i>Mark as Not Spam</i> bagi email yang masuk ke folder spam</li>
                                <li>Pastikan maksimum pengiriman <i>email</i> verifikasi adalah 1 x 24 jam<i>website recruitment</i></li>
                            </ol>',
            ],
            (object) [
                'question' => 'Bagaimana jika saya lupa password ?',
                'answer' => '<ol>
                                <li>
                                klik tombol lupa <i>password</i> di halaman login. Kemudian reset <i>password</i> akan dikirimkan 
                                ke <i>email</i> yang didaftarkan
                                </li>
                            </ol>',
            ],
            (object) [
                'question' => 'Mengapa saya selalu gagal melakukan lupa password ?',
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
                'question' => 'Email dan password salah. Bagaimana caranya agar dapat login kembali ?',
                'answer' => '<P>
                                Hal ini dapat disebabkan oleh 2 kondisi, yaitu <i>email</i> belum terdaftar atau salah 
                                penulisan <i>email</i> dan password. Jika <i>email</i> belum terdaftar, silahkan
                                melakukan <i>register</i>  terlebih dahulu. Jika akun lupa <i>password</i>, silahkan klik 
                                tombol lupa password di halaman login. Kemudian <i>reset password</i> akan dikirimkan 
                                ke <i>email</i> Anda
                            </P>',
            ],
            (object) [
                'question' => 'Bagaimana mencari role lainnya di edumate ?',
                'answer' => '<P>
                               Klik tanda panah diatas kiri lalu klik <i>Profile</i>. Pilih <i>Aktif Role</i>. Dan pilih <i>Save</i>. 
                            </P>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin mengubah password baru ?',
                'answer' => '<P>
                              Klik tanda panah diatas sebelah kiri. Pilih <i>Password</i>. Masukkan <i>Old Password</i> lalu masukan 
         <i>password baru (New Password & Re-type New Password)</i>. Lalu klik <i>Save</i>. 
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Biodata ?',
                'answer' => '<P>
                              Modul <i>Biodata</i> berfungsi untuk melengkapi data pribadi anda serta ada menu <i>Data Prestasi</i> dan <i>Data Kegiatan</i> yang 
                              bertujuan untuk menyimpan data prestasi dan kegiatan anda selama menjadi guru/ tendik.
                            </P>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin melihat histori absensi anda ?',
                'answer' =>  '<ol>
                                <li> Modul <i>Histori Absensi</i> adalah sebuah modul yang berfungsi untuk summary (ringkasan) 
                                absen anda saat check in dan check out di mesin fingerprint.</li>
                                <li> Klik absensi. Pilih <i>Tanggal Mulai</i> dan <i>Tanggal Akhir</i></li>
                                <li>Klik tampilkan, maka akan muncul absensi anda.</li>
                            </ol>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin melihat histori absensi siswa ?',
                'answer' =>  '<ol>
                                <li> Klik Absensi. Pilih <i>Kelas, Status (Masuk, Izin, Sakit) dan Pilih Tanggal Absensi</i>.</li>
                                <li> Klik tampilkan, maka muncul histori absensi siswa</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Apa itu modul Kesekretariatan ?',
                'answer' =>  '<ol>
                                <li> Modul yang berfungsi untuk <i>menyimpan dokumen kurikulum, dokumen pribadi, arsip 
                                    surat masuk dan keluar dan dokumen tata usaha.</i></li>
                                <li> Terdapat dua menu di modul <i>Kesekretariatan</i> yaitu menu <i>Lihat Dokumen</i> yang berfungsi  
                                    untuk melihat dokumen yang ada di loker dan pemiliknya dan menu <i>Upload Dokumen</i> yang 
                                    berfungsi untuk menambah data dokumen di dalam loker.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Apa itu modul Kegiatan Harian ?',
                'answer' =>  '<ol>
                                <li> Modul yang berfungsi untuk <i>melihat status kegiatan harian anda, dengan berdasarkan 
                                    input jawaban dari beberapa pertanyaan yang telah disediakan.</i></li>
                                <li> Cara untuk input data modul <i>Kegiatan Harian</i> adalah sama seperti mengisi form yaitu dengan   
                                    pilih di pertanyaan ganda, essay dan bahkan bisa input foto/gambar.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana cara menambah Manajemen Materi Ajar ?',
                'answer' =>  '<ol>
                                <li> Pilih modul <i>E-Learning Materi</i> lalu klik menu <i>Manajemen Materi Ajar</i>. Klik <i>Tambah 
                                    Materi Ajar</i>. Masukkan data-data seperti <i>Jurusan, Kelas, Status, Mata Pelajaran, Judul 
                                    Materi, Pilih Upload File Dari Mana, Keterangan, dan Pilih File.</i></li>
                                <li> Lalu klik Save.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana cara membuat soal ?',
                'answer' =>  '<ol>
                                <li> Klik modul <i>E-learning Soal.</i> Klik <i>Soal.</i> 
                                Pilih <i>Tambah Pilihan Ganda, Tambah Jawaban Essay, Tambah Jawaban File, Kategori Mata Pelajaran.</i> 
                                Pilih salah satu jika ingin digunakan.</li>
                                <li> Lalu klik save untuk menyimpannya.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana cara untuk mengatur waktu dalam mengerjakan soal ?',
                'answer' =>  '<ol>
                                <li> Klik modul <i>E-learning Soal.</i> Klik <i>Tambah Paket Soal.</i> </li>
                                <li> Isi semua pilihan yang tertera. Lalu klik save.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin melihat hasil test ?',
                'answer' =>  '<ol>
                                <li> Klik modul <i>E-learning soal,</i> Klik <i>“Hasil Test”.</i></li>
                                <li> Pilih <i>Action</i> jika ingin melihat hasilnya.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin melihat memasukkan nilai di modul Rapor Sisipan ?',
                'answer' =>  '<ol>
                                <li> Klik “Rapor Sisipan”. Pilih “Daftar Nilai Tengah Semester” atau “Daftar Nilai Akhir Semester”.</li>
                                <li> Klik tambah “Tambah Nilai” jika ingin memasukkan nilainya melalui diakad. 
                                Atau pilih “Import Excel” jika ingin memasukkan nilai melalui unggah file.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Bagaimana jika ingin Set Jadwal Kelas ?',
                'answer' =>  '<ol>
                                <li> Pilih <i>Jadwal.</i> Lalu klik <i>Set Jadwal Kelas</i></li>
                                <li> Pilih <i>Kelas</i> dan <i>Semester.</i> Lalu klik tampilkan.</li>
                                 </ol>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Jurnal Harian ?',
                'answer' =>  '<ol>
                                <li> Pada Modul Jurnal Harian terdapat menu <i>Laporan Individu Jurnal Harian</i> dan 
                                    <i>Laporan Kelompok Jurnal Harian</i> yang digunakan untuk mencatat kegiatan harian    
                                    selama di sekolah.maupun di luar sekolah</li>
                                <li> Klik menu <i>Laporan Individu Jurnal Harian</i>. Klik <i>Tambah Laporan Kerja Harian</i>
                                    dengan input Tanggal, Mata Pelajaran, Jenis Jurnal Harian, Status, File Pendukung,   
                                    beserta Keterangan.</li>
                                <li> Klik menu <i>Laporan Kelompok Jurnal Harian</i></li>
                                </ol>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Presensi Kelas ?',
                'answer' => '<P>
                              Modul Presensi Kelas berfungsi untuk membuat serta merekap absensi siswa. 
                              Dalam presensi kelas terdapat menu <i>Absensi Siswa Kelas, Rekap Absen Kelas, Absensi Tanpa Jadwal, dan Rekap Absen Tanpa Jadwal.</i> 
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Penilaian ?',
                'answer' => '<P>
                              Penilaian adalah sebuah modul untuk memasukkan atau menginput nilai siswa. 
                              Dalam modul penilaian terdapat menu <i>Komponen Nilai, Input Nilai KBM, dan Rekap Nilai.</i>
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud modul Pelanggaran Siswa ?',
                'answer' => '<P>
                              Pelanggaran Siswa adalah sebuah modul untuk data-data pelanggaran siswa.
                               Dalam pelanggaran siswa terdapat menu <i>Data Pelanggaran Siswa KBM dan Data Pelanggaran Siswa Non KBM.</i>
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Sarana Prasana ?',
                'answer' => '<P>
                             Sarana Prasana adalah sebuah modul untuk komplain apabila dalam proses mengajar terdapat kendala. 
                             Contohnya apabila AC dalam kelas tidak menyala maka bisa complain. Modul Sarana Prasana tedapat menu <i>Komplain Investaris/Sarpras.</i> 
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Tutorial ?',
                'answer' => '<P>
                            Tutorial adalah modul yang berisi sebuah video untuk memudahkan dalam memahami setiap modul guru. 
                            Dalam modul tutorial terdapat menu <i>Video.</i>
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Guru Piket ?',
                'answer' => '<P>
                            Guru Piket adalah modul yang berisi menu <i>absensi siswa, 
                            monitoring kelas kosong, merekap monitoring, input pelanggaran, rekap kesehatan siswa, serta rekap absen tanpa jadwal.</i> 
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Wali Kelas ?',
                'answer' => '<P>
                            Wali Kelas adalah modul untuk melihat rekap absensi, pelanggaran, keuangan serta nilai siswa didiknya. 
                            </P>',
            ],
            (object) [
                'question' => 'Apa yang dimaksud dengan modul Guru KPI ?',
                'answer' => '<P>
                            Guru KPI adalah modul untuk menginput dan merekap nilai KPI siswa. 
                            </P>',
            ],
        ]);

        // $tes = $faq->when(request()->search, function ($faq) {
        //     $faq = $faq->where('question', 'like', '%' . request()->search . '%');
        // });
        // dd($search);
        if ($search != null) {
            $cari = $search;
            $faq = $faq->filter(function ($item) use ($cari) {
                // replace stristr with your choice of matching function
                return false !== stristr($item->question, $cari);
            });
        }
        return (view('guru/faq/index', compact('auth_data', 'faq')));
    }

    public function SearchFaq(Request $request)
    {

        // dd($request);

        return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'faq/lihat-faq/' . $request->search,

        ];
    }
}
