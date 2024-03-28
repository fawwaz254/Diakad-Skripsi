<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Tengah Semester</title>


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
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr>
                    <td style="border-style : hidden;width: 10%;"><img
                            src="{{ url('https://diakad.sgp1.digitaloceanspaces.com/smktanada/global/logo-sekolah') }}"
                            alt="" style="width: 100px; height: 100px;"></td>
                    <td colspan="10" style="border-style : hidden">

                        <h2 align="center" style="margin-top: 3px">
                            LAPORAN PENILAIAN HASIL BELAJAR<br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                            TENGAH SEMESTER GENAP<br>
                            @php
                                // $nama = $list_nilai->first();

                                // echo isset($nama->rapor_sisipan->semester->tahun_ajaran) ? 'TAHUN AJARAN ' . $nama->rapor_sisipan->semester->tahun_ajaran : '';
                            @endphp

                        </h2>
                        <hr>
                        <br>
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NAMA SISWA
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">BIDANG KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; ">
                        {{ $kelas->jurusan->bidang_keahlian }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">NIS / NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">
                        {{ $siswa->nis_siswa }} / {{ $siswa->nisn_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">PROGRAM KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $kelas->jurusan->program_keahlian }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 14%;font-weight: bold;">KELAS
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;">{{ $kelas->nm_kelas }}
                    </td>
                    <td style="border-style : hidden;width: 24%;font-weight: bold;">
                        @if ($kelas->tingkat == '1')
                            KONSENTRASI KEAHLIAN
                        @else
                            KOMPETENSI KEAHLIAN
                        @endif
                    </td>
                    <td style="border-style : hidden;width: 1%;font-weight: bold;"> :
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">
                        {{ $kelas->jurusan->kompetensi_keahlian }}
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <th rowspan="2"style="text-align: center;font-weight: bold;">No</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">Mata Pelajaran</th>
                        <th colspan="5" style="text-align: center;font-weight: bold;">Nilai Tugas dan Ulangan</th>
                        <th rowspan="2" style="text-align: center;font-weight: bold;">UTS</th>
                        <th colspan="2" style="text-align: center;font-weight: bold;">Nilai Akhir</th>
                    </tr>
                    <tr>
                        <th style="text-align: center;font-weight: bold;">1</th>
                        <th style="text-align: center;font-weight: bold;">2</th>
                        <th style="text-align: center;font-weight: bold;">3</th>
                        <th style="text-align: center;font-weight: bold;">4</th>
                        <th style="text-align: center;font-weight: bold;">Rata-rata</th>
                        {{-- <th style="text-align: center;font-weight: bold;">UTS</th> --}}
                        <th style="text-align: center;font-weight: bold;">Rata-rata</th>
                        <th style="text-align: center;font-weight: bold;">Kriteria</th>
                    </tr>
                </thead>
                <br>
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
                                    <td style="text-align: center;" rowspan="{{ $jumlah }}">
                                        {{ $loop->iteration }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;font-weight: bold;">
                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_jenis_rapor] : '' }}
                                        </td>
                                    @endforeach
                                    <td style="text-align: center;font-weight: bold;">
                                        {{ isset($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]]) ? intval($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]]) : '' }}
                                    </td>

                                    <td style="text-align: center;font-weight: bold;">
                                        {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'uts']) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . 'uts'] : '' }}
                                    </td>
                                    <td style="text-align: center;font-weight: bold;">
                                        {{ isset($nilai_siswa['rata_rata' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]]) ? intval($nilai_siswa['rata_rata' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]]) : '' }}
                                    </td>
                                    <td style="text-align: center;font-weight: bold;">
                                        {{ isset($nilai_siswa['kriteria' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]]) ? $nilai_siswa['kriteria' . $siswa->id_siswa . $data2['id_mata_pelajaran'][0]] : '' }}
                                    </td>
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>

                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;font-weight: bold;">
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_jenis_rapor] : '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br>
            {{-- Kepribadian dan Kehadiran --}}
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <tr>
                    <td style="vertical-align: top; border-style : hidden;">
                        <table cellspacing="0" cellpadding="10"
                            style="width: 100%; margin: 0 auto; border-collapse: collapse; border: 2px solid black;">
                            @foreach ($pribadi_sisipan_kepribadian as $key => $k)
                                <tr>
                                    <td style="width: 40%; border: none;">{{ $k->nm_pribadi_sisipan }}</td>
                                    <td style="width: 10%; border: none;">:</td>
                                    @if (isset($nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan]))
                                        <td style="width: 50%; border: none; text-align: center;">
                                            {{ $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan] }}
                                        </td>
                                    @else
                                        <td style="width: 50%; border: none; text-align: center;">-</td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </td>
                    <td style="vertical-align: top; border-style : hidden;">
                        <table cellspacing="0" cellpadding="10"
                            style="width: 100%; margin: 0 auto; border-collapse: collapse; border: 2px solid black;">
                            @foreach ($pribadi_sisipan_kehadiran as $key => $k)
                                <tr>
                                    <td style="width: 40%; border: none;">{{ $k->nm_pribadi_sisipan }}</td>
                                    <td style="width: 10%; border: none;">:</td>
                                    @if (isset($nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan]))
                                        <td style="width: 50%; border: none; text-align: center;">
                                            {{ $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan] . ' Hari' }}
                                        </td>
                                    @else
                                        <td style="width: 50%; border: none; text-align: center;">- Hari</td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
            <br>
            {{-- Catatan wali kelas --}}
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border: 2px solid black;">
                @foreach ($pribadi_sisipan_catatan_orang_tua as $key => $k)
                    <tr>
                        <td style="width: 100%; border: none; text-align: center; padding: 30px;">
                            @if (isset($nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan]))
                                {{ $nilai_pengembangan_diri[$siswa->id_siswa . $k->id_pribadi_sisipan] }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <br>
            {{-- Tanda tangan --}}
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="left">
                        Mengetahui,
                        <br>
                        Orang Tua/Wali,
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                                border-bottom: 2px solid   black;">
                        </p>
                        {{-- {{ $auth_data->sekolah_data->nm_kepala_sekolah }} --}}
                    </td>
                    <td style="width:45%; border-style : hidden"></td>

                    <td style="width:30%" align="left">Surabaya,
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}

                        <br>
                        Wali Kelas,
                        <br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <u><b> {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}<b></u>
                        @else
                            <p
                                style="width: 250px;
                                        border-bottom: 2px solid   black;">
                            </p>
                        @endif
                    </td>
                </tr>
                <td style=" width:25%; border-style : hidden;"></td>
                <td style="width:45%; border-style : hidden;text-align: center">Mengetahui, <br>
                    Kepala Sekolah,
                    <br><br><br><br><br><br><br>
                    <u><b>
                            {{ $auth_data->sekolah_data->nm_kepala_sekolah }}<b></u>
                </td>
                <td style="width:30%; border-style : hidden"> </td>
            </table>
        </div>
    @endforeach
    </div>


</body>
<script>
    window.print();
</script>

</html>
