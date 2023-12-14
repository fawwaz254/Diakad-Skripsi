<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Semester</title>


    <style>
        * {
            font-family: 'Tahoma';
            letter-spacing: 1.5px;
        }

        table,
        td,
        th {
            border: 1px solid;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* border: 5px double; */
        }

        .head {
            border: 5px double;
        }

        td {
            /* font-size: 10px; */
            padding: 2px;
        }

        .page {
            width: 1200px;
        }

        .body {

            border: 5px double;
            border-top-style: none;
        }

        .under-below {
            text-decoration: underline;
            -webkit-text-underline-position: under;
            -ms-text-underline-position: below;
            text-underline-position: under;
        }
    </style>

    <style type="text/css" media="print">
        @page {
            /* margin: 125mm 125mm 125mm 125mm;    */
            size: portrait;
            size: auto;
            margin: 0mm;

        }

        @media print {
            .page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page">

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Peserta Didik
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NISN/NIS
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nisn_siswa . '/' . $siswa->nis_siswa }}
                    </td>

                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>
                <tr>
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>




            </table>

            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;">
                    <td>
                        A. Nilai Akademik
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #d4d4d4">
                    <tr>
                        <th>No</th>
                        <th>Mata Pelajaran</th>
                        @foreach ($list_komponen as $komponen)
                            <th>{{ $komponen->nm_komponen_jenis_rapor }}</th>
                        @endforeach
                        <th>Nilai Akhir</th>
                        <th>Predikat</th>
                    </tr>
                </thead>
                <tbody class="body">
                    @foreach ($data as $kelompok)
                        @if (isset($kelompok['data']))
                            <tr>
                                <td colspan="2" style="font-weight: bold;">
                                    {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                            </tr>
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    @php
                                        $nilai_akhir = 0;
                                    @endphp
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                        </td>
                                        @php
                                            if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'])) {
                                                $nilai_akhir += $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'];
                                            }
                                        @endphp
                                    @endforeach
                                    @php
                                        $nilai_akhir = $nilai_akhir / 2;
                                    @endphp
                                    <td style="text-align: center;">{{ $nilai_akhir != '0' ? $nilai_akhir : '' }}
                                    </td>
                                    @php
                                        if ($nilai_akhir >= 90 && $nilai_akhir <= 100) {
                                            $hasil = 'A';
                                        } elseif ($nilai_akhir >= 80 && $nilai_akhir < 90) {
                                            $hasil = 'B';
                                        } elseif ($nilai_akhir >= 70 && $nilai_akhir < 80) {
                                            $hasil = 'C';
                                        } elseif ($nilai_akhir >= 1 && $nilai_akhir < 70) {
                                            $hasil = 'D';
                                        } else {
                                            $hasil = '';
                                        }
                                    @endphp
                                    <td style="text-align: center;">
                                        {{ $hasil }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr>
                    <td><b>B. Catatan Akademik</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="padding: 10px;text-align: center;">Orang - orang yang berhenti belajar akan menjadi
                        pemilik masa lalu, <br>orang
                        - orang yang masih terus belajar akan menjadi pemilik masa depan. <br>- Mario Teguh -</td>
                </tr>
            </table>
            <br>
        </div>
        <div class="page">


            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Peserta Didik
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">NISN/NIS
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nisn_siswa . '/' . $siswa->nis_siswa }}
                    </td>

                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>
                <tr>
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>C. Praktik Kerja Lapangan</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mitra DU/DI</th>
                        <th>Lokasi</th>
                        <th>Lamanya<br>(Bulan)</th>
                        <th>Keterangan</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>D. Ekstrakurikuler</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <th>No</th>
                    <th>Ekstrakurikuler</th>
                    <th>Keterangan</th>
                </tr>

                @php
                    $no_ekskul = 0;
                @endphp
                @foreach ($ekskul_tambahan_rapor as $ekskul)
                    @if (isset($tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor]))
                        <tr>
                            <td style="text-align: center">{{ ++$no_ekskul }}</td>
                            <td style="text-align: center">{{ $ekskul->nm_tambahan_rapor }}</td>
                            <td style="text-align: center">
                                {{ $tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor] }}
                            </td>
                        </tr>
                    @endif
                @endforeach

                @if (!isset($tambahan['ekskul'][$siswa->id_siswa]))
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                @endif

            </table>
            <br>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>E. Ketidakhadiran</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; ">
                @foreach ($kehadiran_tambahan_rapor as $k)
                    <tr>
                        <td>
                            {{ $k->nm_tambahan_rapor }}
                        </td>
                        <td style="text-align: center">
                            {{ isset($tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor]) ? $tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor] . ' Hari' : ' - Hari' }}
                        </td>
                        <td
                            style="width: 50%;border-right: hidden; 
					border-bottom: hidden; 
					border-top: hidden;">
                        </td>
                    </tr>
                @endforeach
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>F. Keterangan</b></td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">

                <tr>
                    <td style="padding: 10px;text-align: center">
                        Berdasarkan hasil yang dicapai maka peserta didik dinyatakan : <br>
                        {{ isset($tambahan['Kelulusan'][$siswa->id_siswa]) ? $tambahan['Kelulusan'][$siswa->id_siswa] : '-' }}

                    </td>
                </tr>
            </table>
            <br>

            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                        Mengetahui, <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
								border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">

                    </td>
                    <td style="width: 30%;">
                        {{ 'Surabaya, ' . indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p style="width: 250px;
										border-bottom: 1px solid   black;">
                            </p>
                        @endif

                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">
                        Mengetahui, <br>
                        Kepala Madrasah
                        <br><br><br><br><br><br><br>
                        <b><u>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td style="width: 30%;">
                    </td>
                </tr>
            </table>
        </div>
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>G. Deskripsi Pengembangan Karakter</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead>
                    <tr>
                        <th>Karakter yang dibangun</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengembangan_karakter_tambahan_rapor as $p)
                        <tr>
                            <td style="text-align: center">{{ $p->nm_tambahan_rapor }}</td>
                            <td style="padding: 10px">
                                {{ isset($tambahan['pengembangan_karakter'][$siswa->id_siswa][$p->id_tambahan_rapor]) ? $tambahan['pengembangan_karakter'][$siswa->id_siswa][$p->id_tambahan_rapor] : '' }}
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                    </tr> --}}
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td><b>H. Catatan Pengembangan Karakter</b></td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="padding: 10px">Sikap daya tangguh, daya juang yang di miliki harus di tingkatkan,
                        berusahalah untuk selalu
                        tepat waktu, karena sikap keseharian anda di sekolah menunjukkan performa kalian di dunia kerja,
                        belajarlah untuk siap menerima perubahan dan berusahalah untuk menjemput bola, gunakanlah
                        kesempatan yang ada sebaik mungkin, dan belajarlah untuk tidak mengecewakan orang lain,
                        keluarlah dari zona nyaman anda. Jangan pernah takut akan kegagalan selama kita belum mencoba,
                        berusahalah untuk melakukan perubahan/hal baru yang baik dan gunakanlah usaha dan do'a sebagai
                        senjata kita untuk sukses.</td>
                </tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                        Mengetahui, <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
								border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">

                    </td>
                    <td style="width: 30%;">
                        {{ 'Surabaya, ' . indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p style="width: 250px;
										border-bottom: 1px solid   black;">
                            </p>
                        @endif

                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">
                        Mengetahui, <br>
                        Kepala Madrasah
                        <br><br><br><br><br><br><br>
                        <b><u>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td style="width: 30%;">
                    </td>
                </tr>
            </table>

        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
