<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Bukti Pembayaran Formulir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

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
    <main class="space-y-2 border-2 border-dashed p-4 max-w-3xl mx-auto my-4 bg-white rounded-xl ">
        <!-- Header -->
        <div class="bg-white text-gray-800 border-2 border-gray-800 p-4 rounded-t-lg">
            <div class="flex items-center justify-between gap-14">
                <img src="https://smawh2.sch.id/wp-content/uploads/2016/02/SMA-WACHID-HASYIM-2-TAMAN.png"
                    alt="logo-sekolah" class="h-16">
                <div class="text-center">
                    <h1 class="text-xl font-bold">PANITIA PENERIMAAN
                        PESERTA DIDIK BARU
                        SMK YPM 2 TAMAN
                        TAHUN PELAJARAN 2025 / 2026
                    </h1>
                </div>
                <div class="bg-white border-2 border-gray-800 text-gray-800 rounded-lg p-2 text-center">
                    <p class="text-sm font-bold">Nomor Pendaftaran:</p>
                    <h1 class="text-2xl font-bold">{{ $nomer_pendaftaran }}</h1>
                </div>
            </div>
        </div>

        <!-- Judul Bukti Pembayaran -->
        <div class="p-4 border-2 border-gray-800">
            <h1 class="text-xl font-bold text-center text-gray-800">TANDA BUKTI PEMBAYARAN FORMULIR</h1>
        </div>

        <!-- Informasi Siswa -->
        <div class="p-4 border-2 border-gray-800">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-center text-gray-700 mb-2">Telah tercatat sebagai Calon Siswa SMK YPM 2 Taman Sidoarjo
                    Tahun Pelajaran 2025 / 2026.</p>

                <div class="space-y-2">
                    <div class="flex flex-col gap-1">
                        <p class="text-gray-600">Nama:</p>
                        <p class="font-bold text-base text-gray-800">{{ $siswa->nm_c_siswa }}</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-gray-600">Asal Sekolah:</p>
                        <p class="font-bold text-base text-gray-800">{{ $siswa->asal_sekolah }}</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-gray-600 min-w-40">Alamat Rumah:</p>
                        <p class="font-bold text-base text-gray-800">{{ $alamat }}</p>
                    </div>
                    <div class="flex flex-col gap-1">
                        <p class="text-gray-600 min-w-40">Nomor Telepon:</p>
                        <p class="font-bold text-base text-gray-800">{{ $siswa->nomor_hp }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="gap-2 grid grid-cols-3">
            <div class="border-2 p-4 text-gray-600 border-gray-800">
                <p>Pilihan Jurusan 1:</p>
                <p class="font-bold text-base text-gray-800">{{ $jurusan_1 }}</p>
            </div>
            <div class="border-2 p-4 text-gray-600 border-gray-800">
                <p>Pilihan Jurusan 2:</p>
                <p class="font-bold text-base text-gray-800">{{ $jurusan_2 }}</p>
            </div>
            <div class="border-2 p-4 text-gray-600 border-gray-800">
                <p>Pilihan Jurusan 3:</p>
                <p class="font-bold text-base text-gray-800">{{ $jurusan_3 }}</p>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div class="p-4 border-2 border-gray-800">
            <div class="text-center">
                <p class="text-gray-700">Yang bersangkutan telah membayar Uang Pendaftaran Sebesar:</p>
                <p class="text-xl font-bold text-gray-800 mt-1">Rp. 250.000,-</p>
                <p class="text-sm text-gray-600">(Dua Ratus Lima Puluh Ribu Rupiah)</p>
            </div>
        </div>

        <!-- Tanggal dan Tanda Tangan -->
        <div class="p-4 pr-[3rem] border-2 border-gray-800">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-700">Sidoarjo, {{ $tanggal }}</p>
                    <p class="font-semibold text-gray-700">Panitia PPDB</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Tanda Tangan:</p>
                    <div class="mt-1 h-20 w-24 border-b  border-gray-300"></div>
                </div>
            </div>
        </div>

        <!-- Informasi Kontak -->
        <div class="p-4 border-2 border-gray-800 rounded-b-lg">
            <p class="font-bold text-gray-700">Info Lebih Lanjut:</p>
            <p class="text-gray-600">1. Ibu Fitriah (0853-3374-8589)</p>
            <p class="text-sm text-gray-600 mt-1"><strong>NB*</strong>: Uang yang sudah dibayarkan tidak dapat diminta
                kembali.</p>
        </div>

        <!-- Tombol Cetak -->
        <div class="no-print p-4">
            <button onclick="window.print()"
                class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Cetak Bukti Pembayaran
            </button>
        </div>
    </main>
    <script>
        window.print();
    </script>
</body>

</html>
