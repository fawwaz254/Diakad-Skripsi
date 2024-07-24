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

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; " style="border-style : hidden">
                <tr style="border-style : hidden ;">
                    <td style="border-style : hidden;width: 14%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%; ">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>


                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;vertical-align: top;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;vertical-align: top;">
                        Perum Gunung Sari Indah Blok CC,<br> Kedurus Karangpilang Surabaya
                    </td>
                    <td style="border-style : hidden;width: 24%;vertical-align: top;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;vertical-align: top;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;vertical-align: top; ">
                        {{ $semester->nm_semester }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
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
                    <td style="border-style : hidden;width: 14%;">No Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;">
                        {{ $siswa->nis_siswa . '/' . $siswa->nisn_siswa }}
                    </td>
                </tr>
            </table>

            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr style="font-weight: bold;border-style : hidden">
                    <td>CAPAIAN HASIL BELAJAR</td>
                </tr>
                <br>
                <tr style="font-weight: bold; border-style : hidden">
                    <td>A. Sikap</td>
                </tr>
                <tr></tr>
                <tr>
                    <td style="border-style: solid">Deskripsi :
                        <br>
                        {{ isset($tambahan['sikap'][$siswa->id_siswa]) ? $tambahan['sikap'][$siswa->id_siswa] : '-' }}
                        {{-- Selalu Bersyukur, selalu berdoa sebelum melakukan kegiatan, toleran pada agama yang berbeda dan
                        perlu meningkatkan ketaatan beribadah serta selalu bersikap sikap santun, peduli, percaya diri,
                        dan perlu meningkatkan sikap jujur, disiplin dan tanggung jawab. --}}
                    </td>
                </tr>
                <tr></tr>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;">
                    <td>B. Pengetahuan dan Ketrampilan</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #ffffcc">
                    <tr>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">MATA PELAJARAN<br></td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KKTP</td>
                        @foreach ($list_komponen as $komponen)
                            <td colspan="2" style="text-align: center;font-weight: bold;">
                                {{ $komponen->nm_komponen_jenis_rapor }}
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">Angka</td>
                        <td style="text-align: center;font-weight: bold;">Predikat</td>
                        <td style="text-align: center;font-weight: bold;">Angka</td>
                        <td style="text-align: center;font-weight: bold;">Predikat</td>
                    </tr>
                </thead>
                {{-- <br> --}}
                <tbody class="body">

                    @foreach ($data as $kelompok)
                        {{-- @php
                            dd($data);
                        @endphp --}}
                        <tr>
                            <td colspan="2" style="font-weight: bold;">
                                {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                        </tr>
                        @if (isset($kelompok['data']))
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    <td style="text-align: center;">{{ $data2['kkm'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;font-weight: bold;">
                                            <span @if (isset(
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) &&
                                                    isset($data2['kkm'][0]) &&
                                                    $data2['kkm'][0] != '0' &&
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'] <
                                                        $data2['kkm'][0]
                                            ) style="color:red" @endif>
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'] : '' }}</span>
                                        </td>
                                        <td style="text-align: center;font-weight: bold;">
                                            <span @if (isset(
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai']) &&
                                                    isset($data2['kkm'][0]) &&
                                                    $data2['kkm'][0] != '0' &&
                                                    $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'nilai'] <
                                                        $data2['kkm'][0]
                                            ) style="color:red" @endif>
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                                {{-- @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">{{ $data2['kkm'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai']) && isset($data2['kkm'][$i]) && $data2['kkm'][$i] != '0' && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai'] < $data2['kkm'][$i]) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai']) && isset($data2['kkm'][$i]) && $data2['kkm'][$i] != '0' && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai'] < $data2['kkm'][$i]) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor --}}
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style="border-style : hidden; width:35%;" align="left">
                        <br>
                        <br>
                        Orang Tua/Wali
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                                border-bottom: 1px solid   black;">
                        </p>
                    </td>
                    <td style="width:35%; border-style : hidden;" align="left">
                        Mengetahui, <br>
                        Kepala Sekolah
                        <br><br><br><br><br><br><br>
                        <b><u>
                                {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td style="width: 30%;">
                        Surabaya, {{ $tanggal_cetak }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <b><u> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}</u></b>
                        @else
                            <p
                                style="width: 250px;
                                        border-bottom: 1px solid   black;">
                            </p>
                        @endif

                    </td>
                </tr>
            </table>

        </div>

        <div class="page">
            <br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
                <tr style="font-weight: bold;">
                    <td>Deskripsi Dari Pengetahuan dan Ketrampilan</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #ffffcc">
                    <tr>
                        <td style="text-align: center;font-weight: bold;">NO</td>
                        <td style="text-align: center;font-weight: bold;">MATA PELAJARAN<br></td>
                        <td style="text-align: center;font-weight: bold;">KOMPETENSI</td>
                        <td style="text-align: center;font-weight: bold;">CATATAN</td>
                </thead>
                <tbody class="body">
                    @foreach ($data as $kelompok)
                        <tr>
                            <td colspan="2" style="font-weight: bold;">
                                {{ isset($kelompok['nama']) ? $kelompok['nama'] : '' }}</td>
                        </tr>
                        @if (isset($kelompok['data']))
                            @foreach ($kelompok['data'] as $key => $data2)
                                @php
                                    $jumlah = count($data2['nm_point']);
                                @endphp
                                <tr>
                                    <td rowspan="2" style="text-align: center;">{{ $key }}</td>
                                    <td rowspan="2">{{ $data2['nm_point'][0] }}</td>

                                    @foreach ($list_komponen as $key => $komponen)
                                        @if ($key == '1')
                                <tr>
                            @endif
                            <td style="text-align: center; padding: 10px">{{ $komponen->nm_komponen_jenis_rapor }}
                            </td>
                            <td>
                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor . 'keterangan'] : '' }}
                            </td>
                            @if ($key == '0')
                                </tr>
                            @endif
                        @endforeach
                        </tr>
                        {{-- @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">{{ $data2['kkm'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai']) && isset($data2['kkm'][$i]) && $data2['kkm'][$i] != '0' && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai'] < $data2['kkm'][$i]) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                                </span>
                                            </td>
                                            <td style="text-align: center;font-weight: bold;">
                                                <span @if (isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai']) && isset($data2['kkm'][$i]) && $data2['kkm'][$i] != '0' && $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'nilai'] < $data2['kkm'][$i]) style="color:red" @endif>
                                                    {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor . 'predikat'] : '' }}
                                                </span>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor --}}
                        </tr>
                    @endforeach
    @endif
    @endforeach
    </tbody>
    </table>
    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
        <tr style="font-weight: bold;">
            <td>C. Praktek Kerja Lapangan</td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
        <thead style="background-color: #ffffcc">
            <tr>
                <th>No</th>
                <th>Mitra DU/DI</th>
                <th>Lokasi</th>
                <th>Lamanya<br>(Bulan)</th>
                <th>Keterangan</th>
                {{-- <th>Keterangan</th> --}}
            </tr>

            {{-- @if (isset($nilai_ekskul[$siswa->id_siswa . 'ekskul']))
                    @foreach ($nilai_ekskul[$siswa->id_siswa . 'ekskul'] as $key => $ekskul)
                        <tr>
                            {{-- <td style="text-align: center;">{{ $key + 1 }}</td> --}}
            {{-- <td> {{ $key + 1 . '. ' . $ekskul }}</td>
                <td style="text-align: center;">
                    {{ $nilai_ekskul[$siswa->id_siswa . 'nilai_ekskul'][$key] }}</td> --}}
            {{-- <td>
                                        {{ $nilai_ekskul[$siswa->id_siswa . 'keterangan_ekskul'][$key] }}</td> --}}
            {{-- </tr> --}}
            {{-- @endforeach --}}
            {{-- @else --}}

            {{-- @endif --}}
            {{--  --}}
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
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
        <tr style="font-weight: bold;">
            <td>D. Ekstrakurikuler</td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
        <thead style="background-color: #ffffcc">
            <tr>
                <th>No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($ekskul_tambahan_rapor as $no_ekskul => $ekskul)
                @if (isset($tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor]))
                    <tr>
                        <td style="text-align: center">{{ ++$no_ekskul }}</td>
                        <td style="text-align: center">{{ $ekskul->nm_tambahan_rapor }}</td>
                        <td style="text-align: center">
                            {{ $tambahan['ekskul'][$siswa->id_siswa][$ekskul->id_tambahan_rapor] }}</td>
                    </tr>
                @endif
            @endforeach
            @if (!isset($tambahan['ekskul'][$siswa->id_siswa]))
                <tr>
                    <td>-</td>
                </tr>
            @endif

            </tr>
        </tbody>
    </table>


    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;border-style : hidden">
        <tr style="font-weight: bold;">
            <td>E. Ketidakhadiran</td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

        @foreach ($kehadiran_tambahan_rapor as $key => $k)
            <tr>
                <td>{{ $k->nm_tambahan_rapor }}</td>
                @if (isset($tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor]))
                    <td style="text-align: center;">
                        {{ $tambahan['ketidakhadiran'][$siswa->id_siswa][$k->id_tambahan_rapor] . ' Hari' }}
                    </td>
                @else
                    <td style="text-align: center;">- Hari</td>
                @endif
                <td
                    style="width: 50%;border-right: hidden; 
                border-bottom: hidden; 
                border-top: hidden;">
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
        <tr>
            <td style="font-weight: bold;border-style : hidden;padding: 10px">F. Catatan Wali Kelas</td>
        </tr>
        <tr></tr>
        <tr>
            <td style="padding: 10px">
                {{ isset($tambahan['catatan_wali_kelas'][$siswa->id_siswa]) ? $tambahan['catatan_wali_kelas'][$siswa->id_siswa] : '-' }}
                {{-- Tingkatkan terus semangat dan motivasi belajarmu agar dapat mencapai keberhasilan serta
                kesuksesan --}}
            </td>
        </tr>

    </table>
    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
        <tr>
            <td style="font-weight: bold;border-style : hidden">G. Kelulusan</td>
        </tr>
        <tr></tr>
        <tr>
            <td> {{ isset($tambahan['kelulusan'][$siswa->id_siswa]) ? $tambahan['kelulusan'][$siswa->id_siswa] : '-' }}
                {{-- LULUS --}}
            </td>
        </tr>

    </table>
    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
        <tr>
            <td style="border-style : hidden; width:35%;" align="left">
                <br>
                <br>
                Orang Tua/Wali
                <br><br><br><br><br><br><br>
                <p style="width: 250px;
                        border-bottom: 1px solid   black;">
                </p>
            </td>
            <td style="width:35%; border-style : hidden;" align="left">
                Mengetahui, <br>
                Kepala Sekolah
                <br><br><br><br><br><br><br>
                <b><u>
                        {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
            </td>
            <td style="width: 30%;">
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

    </div>
    @endforeach

</body>
<script>
    window.print();
</script>

</html>
