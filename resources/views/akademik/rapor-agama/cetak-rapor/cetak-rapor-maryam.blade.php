<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Agama</title>


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
            font-size: 15px;
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
            {{-- <img src="{{ asset('media/ttd/kop_atas_maryam.jpg') }}" alt="" style="width: 100%"> --}}
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 128px auto 0; border-style : hidden">
                <tr>
                    <th colspan="10" style="border-style : hidden">
                        <br>
                        <p align="center" style="margin-top: 3px;  font-size:20px">
                            RAPORT PENDIDIKAN AGAMA ISLAM DAN PENGEMBANGAN DIRI
                        </p>
                    </th>
                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        SMA MARYAM SURABAYA
                    </td>
                    <td style="border-style : hidden;width: 24%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        JL. MANYAR SAMBONGAN 119
                    </td>
                    <td style="border-style : hidden;width: 24%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">No Induk / NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nis_siswa . '/' . $siswa->nisn_siswa }}
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
                        <th rowspan="2" style="width: 5%">No</th>
                        <th rowspan="2">Mata Pelajaran</th>
                        <th rowspan="2" style="width: 5%;font-size: 12px;">KKM</th>
                        <th colspan="2" style="width: 10%">PENGETAHUAN</th>
                        <th colspan="2" style="width: 10%">KETERAMPILAN</th>
                        <th rowspan="2">DESKRIPSI</th>
                        <th rowspan="2" style="width: 5%;font-size: 12px;"">SIKAP</th>
                    </tr>
                    <tr>
                        <th style="font-size: 12px;">
                            ANGKA
                        </th>
                        <th style="font-size: 12px;">PREDIKAT</th>
                        <th style="font-size: 12px;">
                            ANGKA
                        </th>
                        <th style="font-size: 12px;">PREDIKAT</th>

                    </tr>
                </thead>
                <tbody class="body">
                    @php
                        $pengetahuan = 0;
                        $keterampilan = 0;
                        $jumlah_pengetahuan = 0;
                        $jumlah_keterampilan = 0;
                    @endphp
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
                                        if (
                                            isset(
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[0]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ],
                                            )
                                        ) {
                                            $pengetahuan +=
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[0]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ];
                                            $jumlah_pengetahuan += 1;
                                        }
                                        if (
                                            isset(
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[1]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ],
                                            )
                                        ) {
                                            $keterampilan +=
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[1]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ];
                                            $jumlah_keterampilan += 1;
                                        }

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
                                    <td style="padding: 2px">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[2]->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $list_komponen[2]->id_komponen_jenis_rapor . 'nilai'] : '' }}
                                    </td>
                                    @php
                                        if (
                                            isset(
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[0]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ],
                                            ) &&
                                            isset(
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[1]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ],
                                            )
                                        ) {
                                            $nilai_akhir +=
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[0]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ];
                                            $nilai_akhir +=
                                                $nilai_siswa[
                                                    $siswa->id_siswa .
                                                        $data2['id_mata_pelajaran'][0] .
                                                        $list_komponen[1]->id_komponen_jenis_rapor .
                                                        'nilai'
                                                ];
                                            $nilai_akhir = $nilai_akhir / 2;
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
                                        } else {
                                            $hasil = '';
                                        }

                                    @endphp
                                    <td style="text-align: center;"> {{ $hasil }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    @php
                        if ($pengetahuan == '0' || $jumlah_pengetahuan == '0') {
                            $rata_pengetahuan = 0;
                        } else {
                            $rata_pengetahuan = round($pengetahuan / $jumlah_pengetahuan);
                        }

                        $predikat_pengetahuan = $rata_pengetahuan;
                        if ($predikat_pengetahuan >= 90 && $predikat_pengetahuan <= 100) {
                            $predikat_pengetahuan = 'A';
                        } elseif ($predikat_pengetahuan >= 80 && $predikat_pengetahuan < 90) {
                            $predikat_pengetahuan = 'B';
                        } elseif ($predikat_pengetahuan >= 70 && $predikat_pengetahuan < 80) {
                            $predikat_pengetahuan = 'C';
                        } elseif ($predikat_pengetahuan >= 1 && $predikat_pengetahuan < 70) {
                            $predikat_pengetahuan = 'D';
                        } else {
                            $predikat_pengetahuan = '';
                        }

                        if ($keterampilan == '0' || $jumlah_keterampilan == '0') {
                            $rata_keterampilan = 0;
                        } else {
                            $rata_keterampilan = round($keterampilan / $jumlah_keterampilan);
                        }

                        $predikat_keterampilan = $rata_keterampilan;
                        if ($predikat_keterampilan >= 90 && $predikat_keterampilan <= 100) {
                            $predikat_keterampilan = 'A';
                        } elseif ($predikat_keterampilan >= 80 && $predikat_keterampilan < 90) {
                            $predikat_keterampilan = 'B';
                        } elseif ($predikat_keterampilan >= 70 && $predikat_keterampilan < 80) {
                            $predikat_keterampilan = 'C';
                        } elseif ($predikat_keterampilan >= 1 && $predikat_keterampilan < 70) {
                            $predikat_keterampilan = 'D';
                        } else {
                            $predikat_keterampilan = '';
                        }

                    @endphp
                    <tr>
                        <td></td>
                        <td colspan="2" style="font-weight: bold;">JUMLAH</td>
                        <td style="text-align: center;font-weight: bold;">{{ $pengetahuan }}</td>
                        <td style="text-align: center;">{{ $predikat_pengetahuan }}</td>
                        <td style="text-align: center;font-weight: bold;">{{ $keterampilan }}</td>
                        <td style="text-align: center;">{{ $predikat_keterampilan }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" style="font-weight: bold;">RATA-RATA NILAI</td>
                        <td style="text-align: center;font-weight: bold;">{{ $rata_pengetahuan }}</td>
                        <td style="text-align: center;">{{ $predikat_pengetahuan }}</td>
                        <td style="text-align: center;font-weight: bold;">{{ $rata_keterampilan }}
                        </td>
                        <td style="text-align: center;">{{ $predikat_keterampilan }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
            <br>

            <table cellspacing="0" cellpadding="10"
                style="width: 90%; margin: 0 auto; border-style : hidden; text-align:center">
                <tr>
                    <td style="border-style : hidden; width:35%;">
                        Mengetahui, <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br><br>
                        <p style="width: 230px;
								border-bottom: 1px solid   black; margin-left: 20%">
                        </p>
                    </td>
                    <td style="width:30%; border-style : hidden;">

                    </td>
                    <td style="width: 35%;">
                        Surabaya, {{ $tanggal_cetak }}
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
                    <td style="width:30%; border-style : hidden; text-align:center">
                        Mengetahui, <br>
                        Kepala Sekolah
                        <br><br><br><br><br><br><br>
                        <b><u>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td style="width: 35%;">
                    </td>
                </tr>
            </table>
            {{-- <img src="{{ asset('media/ttd/kop_bawah_maryam.jpg') }}" alt="" style="width: 100%;"> --}}

        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
