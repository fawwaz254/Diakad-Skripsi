<head>
    <title>
        {{ $paket_soal->text }}
    </title>
    <style>
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
                    <h3>BERITA ACARA<br>
                        PENYELENGGARA STS DAN PHB<br>
                        TAHUN AJARAN {{ $semester_aktif->tahun_ajaran }}
                    </h3>
                </td>
                <td width="20%"></td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 15px; ">
            <tr>
                <td style="text-align: center;">Pada hari ini <b>{{ $time->locale('id')->dayName }}</b> tanggal
                    <b>{{ $time->format('d') }}</b> bulan
                    <b>{{ $time->locale('id')->monthName }}</b> tahun
                    <b>{{ $time->format('Y') }}</b>
                </td>
            </tr>
            <tr>
                <td style="text-align: center">Telah diselenggarakan PAS dari pukul
                    <b>{{ \Carbon\Carbon::parse($paket_soal->waktu_mulai)->format('H:i') }}</b> sampai
                    dengan pukul <b>{{ \Carbon\Carbon::parse($paket_soal->waktu_selesai)->format('H:i') }}
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top: 15px; margin-left: 50px; margin-right: 50px;">
            @php
                $jumlah_siswa = \App\Models\Test::whereIn('id_pengguna', $paket_soal_kelas->kelas->siswa->pluck('id_pengguna'))
                    ->where('id_paket_soal', $paket_soal->id_paket_soal)
                    ->get()
                    ->count();
            @endphp
            <tr>
                <td width="30%">Pada Sekolah</td>
                <td>{{ ':  ' . $auth_data->sekolah_data->nm_sekolah }}</td>
            </tr>
            <tr>
                <td width="30%">Ruang</td>
                <td>{{ ':  ' }}</td>
            </tr>
            <tr>
                <td width="30%">Kelas</td>
                <td>{{ ':  ' . $paket_soal_kelas->kelas->nm_kelas }}</td>
            </tr>
            <tr>
                <td width="30%">Mata Pelajaran</td>
                <td>{{ ':  ' . $paket_soal->kategori_soal->nm_kategori_soal }}</td>
            </tr>
            <tr>
                <td width="30%">Jumlah Peserta Seharusnya</td>
                <td>{{ ':  ' . $paket_soal_kelas->kelas->siswa->count() . ' Orang' }}</td>
            </tr>
            <tr>
                <td width="30%">Jumlah Peserta yang Tidak Hadir</td>
                <td>{{ ':  ' . ($paket_soal_kelas->kelas->siswa->count() - $jumlah_siswa) . ' Orang' }}</td>
            </tr>
            <tr>
                <td width="30%">Jumlah Peserta yang Hadir</td>
                <td>{{ ':  ' . $jumlah_siswa . ' Orang' }}</td>
            </tr>
        </table>

        <table width="70%" style="margin-top: 15px; margin-left: 50px; margin-right: 50px;">
            <tr>
                <td>Catatan selama pelaksanaan Ujian *)</td>
            </tr>
            <tr>
                <td style="border-bottom: 1px dotted #000"> <br></td>
            </tr>
            <tr>
                <td style="border-bottom: 1px dotted #000"><br> </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px dotted #000"><br> </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px dotted #000"><br> </td>
            </tr>
            <tr>

                <td>
                    <br>Berita acara ini dibuat dengan sesungguhnya.
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 45px; ">
            <tr>
                <td width="70%"></td>
                <td width="30%">Yang membuat berita acara <br>Pengawas
                    <br><br><br><br><br><br>
                    (____________________)
                </td>

            </tr>
        </table>
        <div class="break-after"></div>
    @endforeach

</body>
<script>
    window.print();
</script>
