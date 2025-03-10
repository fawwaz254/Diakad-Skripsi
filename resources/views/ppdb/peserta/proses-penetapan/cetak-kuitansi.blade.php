<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Bukti Pembayaran Formulir</title>
    <style>
        @media print {

            /* Hilangkan header dan footer browser */
            @page {
                margin: 0;
            }

            body {
                margin: 1cm;
                /* Margin untuk konten */
            }

            /* Sembunyikan elemen yang tidak perlu dicetak */
            .no-print {
                display: none;
            }

            /* Hilangkan shadow dan border yang tidak diperlukan */
            .print-shadow {
                box-shadow: none;
            }

            .print-border {
                border: none;
            }

            /* Pastikan gambar dan teks terlihat jelas */
            img {
                max-width: 100%;
                height: auto;
            }
        }
    </style>
</head>

<body class="bg-white">
    <main class="max-w-3xl mx-auto my-8 bg-white rounded-lg border shadow-lg print-shadow">
        <!-- Header -->
        <div class="bg-gray-800 text-white p-6 rounded-t-lg">
            <div class="flex items-center justify-between gap-10">
                <img src="https://smawh2.sch.id/wp-content/uploads/2016/02/SMA-WACHID-HASYIM-2-TAMAN.png"
                    alt="logo-sekolah" class="h-20">
                <div class="text-center">
                    <h1 class="text-2xl font-bold">PANITIA PENERIMAAN
                        PESERTA DIDIK BARU
                        SMK YPM 2 TAMAN
                        TAHUN PELAJARAN 2025 / 2026
                    </h1>
                </div>
                <div class="bg-white text-gray-800 rounded-lg p-4 text-center">
                    <p class="text-sm font-bold">Nomor Pendaftaran:</p>
                    <h1 class="text-4xl font-bold">{{ $nomer_pendaftaran }}</h1>
                </div>
            </div>
        </div>

        <!-- Judul Bukti Pembayaran -->
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-center text-gray-800">TANDA BUKTI PEMBAYARAN FORMULIR</h1>
        </div>

        <!-- Informasi Siswa -->
        <div class="p-6">
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-center text-gray-700 mb-4">Telah tercatat sebagai Calon Siswa SMK YPM 2 Taman Sidoarjo
                    Tahun Pelajaran 2025 / 2026.</p>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <p class="font-semibold text-gray-700">Nama:</p>
                        <p class="text-gray-600">{{ $siswa->nm_c_siswa }}</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="font-semibold text-gray-700">Asal Sekolah:</p>
                        <p class="text-gray-600">{{ $siswa->asal_sekolah }}</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="font-semibold text-gray-700 min-w-40">Alamat Rumah:</p>
                        <p class="text-gray-600">{{ $alamat }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="p-6 border-t">
            <div class="text-center">
                <p class="text-gray-700">Yang bersangkutan telah membayar Uang Pendaftaran Sebesar:</p>
                <p class="text-2xl font-bold text-gray-800 mt-2">Rp. 250.000,-</p>
                <p class="text-sm text-gray-600">(Dua Ratus Lima Puluh Ribu Rupiah)</p>
            </div>
        </div>

        <!-- Tanggal dan Tanda Tangan -->
        <div class="p-6 border-t">
            <div class="flex justify-between items-start h-[100px]">
                <div>
                    <p class="text-gray-700">Sidoarjo, {{ $tanggal }}</p>
                    <p class="font-semibold text-gray-700">Panitia PPDB</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Tanda Tangan:</p>
                    <div class="mt-2 h-16 w-32 border-b  border-gray-300"></div>
                </div>
            </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="p-6 border-t bg-gray-50 rounded-b-lg">
            <p class="font-bold text-gray-700">Info Lebih Lanjut:</p>
            <p class="text-gray-600">1. Ibu Fitriah (0853-3374-8589)</p>
            <p class="text-sm text-gray-600 mt-2"><strong>NB*</strong>: Uang yang sudah dibayarkan tidak dapat diminta
                kembali.</p>
        </div>

        <!-- Tombol Cetak -->
        <div class="no-print p-6">
            <button onclick="window.print()"
                class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Cetak Bukti Pembayaran
            </button>
        </div>
    </main>
</body>

</html>
