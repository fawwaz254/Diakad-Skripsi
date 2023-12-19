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
            font-size: 17px;
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
                        <th rowspan="2">No</th>
                        <th rowspan="2">Mata Pelajaran</th>
                        <th rowspan="2">KKM</th>
                        <th colspan="2">PENGETAHUAN</th>
                        <th colspan="2">KETERAMPILAN</th>
                        <th rowspan="2">DESKRIPSI</th>
                        <th rowspan="2">SIKAP</th>
                    </tr>
                    <tr>
                        <th>
                            ANGKA
                        </th>
                        <th>PREDIKAT</th>
                        <th>
                            ANGKA
                        </th>
                        <th>PREDIKAT</th>

                    </tr>
                </thead>
                <tbody class="body">
                    @foreach ($data as $kelompok)
                        @if (isset($kelompok['data']))
                            <tr>
                                <td colspan="9" style="font-weight: bold;">
                                    {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                            </tr>
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    <td style="text-align: center;">{{ $data2['kkm'][0] }}</td>
                                    @php
                                        $nilai_akhir = 0;
                                    @endphp

                                    <td style="text-align: center;">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                    </td>
                                    <td style="text-align: center;">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                    </td>
                                    <td style="padding: 10px">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[2]->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[2]->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                    </td>
                                    @php
                                        if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'nilai']) && isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'nilai'])) {
                                            $nilai_akhir += $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[0]->id_komponen_jenis_rapor . 'nilai'];
                                            $nilai_akhir += $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[1]->id_komponen_jenis_rapor . 'nilai'];
                                            $nilai_akhir = $nilai_akhir / 2;
                                        }

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
                                    <td style="text-align: center;"> {{ $hasil }}</td>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
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
                        {{ 'Surabaya, 21 Desember 2023' }}
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
