<head>
    <title>
        {{ $paket_soal->text }}
    </title>
    <style>
        table {
            border-collapse: collapse;

        }

        .border {
            border: 1px solid #000000;
        }

        .center {
            text-align: center;
        }

        @media print {
            .break-after {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>
    @foreach ($paket_soal->paket_soal_kelas as $paket_soal_kelas)
        <table width="100%" style="margin-top: 30px;">
            <tr>
                <td width="20%" style="text-align: center;">
                    <img id="logo"
                        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        height="100">
                </td>
                <td width="40%" style="text-align: center;">
                    <h3>DAFTAR HADIR PESERTA<br>
                        STS GANJIL {{ $auth_data->sekolah_data->nm_sekolah }} {{ $semester_aktif->tahun_ajaran }}<br>
                        TAHUN AJARAN {{ $semester_aktif->tahun_ajaran }}
                    </h3>
                </td>
                <td width="20%" style="text-align: center;">
                    <img id="logo" src="{{ asset('logo/logo-kementrian.png') }}" height="100">
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 15px;margin-left: 50px; margin-right: 50px;">
            <tr>
                <td width="10%">SEKOLAH</td>
                <td width="30%">
                    {{ ':  ' . $auth_data->sekolah_data->nm_sekolah }}</u></td>
                <td width="20%">KELAS
                <td width="40%">
                    {{ ':  ' . $paket_soal_kelas->kelas->nm_kelas }}</td>
            </tr>
            <tr>
                <td width="10%">KOTA/KABUPATEN</td>
                <td width="30%">
                    : Surabaya
                </td>
                <td width="20%">WAKTU MULAI
                <td width="40%">
                    {{ ':  ' . $time->locale('id')->dayName . ', ' . \Carbon\Carbon::parse($paket_soal->waktu_mulai)->format('d M Y,  H:i') }}
                </td>
            </tr>
            <tr>
                <td width="10%">JENIS UJIAN</td>
                <td width="30%">
                    {{ ':  ' . $paket_soal->text }}</u></td>
                <td width="20%">WAKTU SELESAI
                <td width="40%">
                    {{ ':  ' . $time->locale('id')->dayName . ', ' . \Carbon\Carbon::parse($paket_soal->waktu_selesai)->format('d M Y,  H:i') }}
                </td>

        </table>


        <table width="80%" style="margin-top: 15px;margin-left: 50px; margin-right: 50px;"
            class="table table-bordered">
            <thead>
                <tr>
                    <th class="border center">No.</th>
                    <th class="border center">Username</th>
                    <th class="border center">Nama Peserta</th>
                    <th class="border center">Tanda Tangan</th>
                    <th class="border center">Mata Pelajaran</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp

                @foreach ($paket_soal_kelas->kelas->siswa as $siswa)
                    <tr>
                        <td class="border center">
                            {{ $no }}
                        </td>
                        <td class="border cener">
                            {{ $siswa->pengguna->username }}
                        </td>
                        <td class="border">
                            {{ $siswa->pengguna->nm_pengguna }}
                        </td>
                        <td class="border"></td>
                        <td class="border center">{{ $paket_soal->kategori_soal->nm_kategori_soal }}</td>
                    </tr>
                    </tr>
                    @php
                        $no++;
                    @endphp
                @endforeach
                <tr>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table width="70%" style="margin-top: 15px; margin-left: 50px; margin-right: 50px;">
            <tr>
                <td><b>Keterangan :</b></td>
            </tr>
            <tr>
                <td>1. Dibuat rangkap 2 (dua), masing-masing untuk Kurikulum Sekolah, dan Arsip Sekolah.</td>
            </tr>
            <tr>
                <td>2. Pengawas ruang menyilang Nama Peserta yang tidak hadir.</td>
            </tr>
        </table>

        <table width="80%" style="margin-top: 15px;  margin-left: 50px;  margin-right: 50px;">
            <tr>
                <td width="70%">
                    <table class="border">
                        @php
                            $jumlah_siswa = \App\Models\Test::whereIn('id_pengguna', $paket_soal_kelas->kelas->siswa->pluck('id_pengguna'))
                                ->where('id_paket_soal', $paket_soal->id_paket_soal)
                                ->get()
                                ->count();
                        @endphp
                        <tr>
                            <td>Jumlah Peserta yang Seharusnya Hadir {{ $paket_soal_kelas->kelas->siswa->count() }}
                                orang</td>
                        </tr>
                        <tr>
                            <td>Jumlah Peserta yang Tidak Hadir
                                {{ $paket_soal_kelas->kelas->siswa->count() - $jumlah_siswa }} orang </td>
                        </tr>
                        <tr class="border">
                            <td>Jumlah Peserta Hadir
                                {{ $jumlah_siswa }}
                                orang</td>
                        </tr>
                    </table>

                </td>
                <td width="30%" class="center"><span style="margin-left: auto;margin-right:auto">Pengawas</span>
                    <br><br><br><br><br><br>
                    (<span style="color: white">_____________________</span>)

                </td>
            </tr>
        </table>
        <div class="break-after"></div>
    @endforeach
</body>
<script>
    window.print();
</script>
