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
            padding: 10px 5px 10px 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            /* border: 5px double; */
        }

        .head {
            border: 5px double;
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
                page-break-before: always;
            }
        }
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page" style="margin-top: 3rem">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr>
                    <td colspan="10" style="border-style : hidden">

                        <h2 align="center" style="margin-top: 3px">
                            RAPOR SISIPAN<br>
                            CAPAIAN KOMPETENSI SISWA<br>
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
                    <td style="border-style : hidden;width: 20%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 39%;">
                        {{ $auth_data->sekolah_data->nm_sekolah }}
                    </td>
                    <td style="border-style : hidden;width: 24%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 30%;">
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 20%;">Alamat
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 39%;">
                        {{ $auth_data->sekolah_data->alamat_jalan }}
                    </td>
                    <td style="border-style : hidden;width: 9%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 30%;">
                        {{ $semester->nm_semester == 'Ganjil' ? '1 (Satu)' : '2 (Dua)' }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 20%;">Nama Siswa
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 39%;">
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 9%;">Tahun Pelajaran
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 30%;">
                        {{ $semester->tahun_ajaran }}
                    </td>
                </tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 20%;">Nomor Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 1%;"> :
                    </td>
                    <td style="border-style : hidden;width: 39%;">
                        {{ $siswa->nis_siswa . ' / ' . $siswa->nisn_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 9%;"></td>
                    <td style="border-style : hidden;width: 1%;"></td>
                    <td style="border-style : hidden;width: 25%;"></td>
                </tr>
            </table>

            <div style="width: 90%; margin: 0 auto;margin-top:3rem;font-weight: bold;">A. Pengetahuan</div>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">Mata Pelajaran<br></td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">KKTP</td>
                        <td colspan="3" style="text-align: center;">Penilaian Harian Siswa</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">STS</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">TP(I)</td>
                        <td style="text-align: center;">TP(II)</td>
                        <td style="text-align: center;">TP(III)</td>
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
                                    <td style="text-align: center;">{{ $key }}</td>
                                    <td>{{ $data2['nm_point'][0] }}</td>
                                    <td style="text-align: center;">{{ $data2['kkm'][0] }}</td>
                                    @foreach ($list_komponen as $komponen)
                                        <td style="text-align: center;font-weight: bold;">

                                            {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][0] . $komponen->id_komponen_nilai] : '' }}
                                        </td>
                                    @endforeach
                                </tr>
                                @for ($i = 1; $i < $jumlah; $i++)
                                    <tr>
                                        <td></td>
                                        <td>{{ $data2['nm_point'][$i] }}</td>
                                        <td style="text-align: center;">{{ $data2['kkm'][$i] }}</td>
                                        @foreach ($list_komponen as $komponen)
                                            <td style="text-align: center;font-weight: bold;">
                                                {{ isset($nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai]) ? $nilai_siswa[$siswa->id_siswa . $data2['id_mata_pelajaran'][$i] . $komponen->id_komponen_nilai] : '' }}
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

            <div style="width: 90%; margin: 3rem auto 1rem auto;font-weight: bold;">B. Ketidakhadiran</div>
            <table cellspacing="0" cellpadding="10" style="width: 40%; margin-left:60px">
                @foreach ($siswa->nilai_pribadi_sisipan as $item)
                    <tr>
                        <td style="width: 70%;">
                            {{ $item->pribadi_sisipan->nm_pribadi_sisipan }}
                        </td>
                        <td style="width: 30%;">
                            {{ $item->nilai }} hari
                        </td>
                    </tr>
                @endforeach
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 4rem auto 0 auto;">
                <tr style="border-style : hidden;">
                    <td style="border-style : hidden;width: 33%;">
                        <br>Mengetahui: <br>
                        Orang Tua/Wali,<br><br><br><br><br><br><br><br><br>
                        ...........................
                    </td>
                    <td style="border-style : hidden;width: 33%;">
                        <br><br>Wali Kelas,<br><br><br><br><br><br><br><br><br>
                        @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                            <strong>
                                <u>
                                    {{ $wali_kelas->guru->pengguna->gelar_depan }}
                                    {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                                    {{ $wali_kelas->guru->pengguna->gelar_belakang }}
                                </u>
                            </strong>
                        @else
                            ...........................
                        @endif
                    </td>
                    <td style="border-style : hidden;width: 33%;position: relative;">
                        Sidoarjo, 21 Oktober 2023<br><br>
                        Kepala Sekolah,<br><br><br><br><br><br><br><br><br>
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                            <img style="position: absolute; top: 5%; margin-left:-20px; margin-top:35px"
                                src="{{ asset('media/ttd/smpypm22.png') }}" alt="TTD" width="200px"
                                height="200px" class="ttd">
                        @endif
                        <strong><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></strong>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
    {{-- <br><br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
            <tr>
                <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="center">
                    Mengetahui,
                    <br>
                    Kepala Sekolah
                    <br><br><br><br><br><br><br>
                    {{ $auth_data->sekolah_data->nm_kepala_sekolah }}
                </td>
                <td style="width:50%; border-style : hidden"></td>

                <td style="width:25%" align="center">Sidoarjo,
                    {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                    <br>
                    Wali Kelas
                    <br><br><br><br><br><br><br>
                    @if (isset($wali_kelas->guru->pengguna->nm_pengguna))
                        {{ $wali_kelas->guru->pengguna->gelar_depan }}
                        {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                        {{ $wali_kelas->guru->pengguna->gelar_belakang }}
                    @else
                        <p style="width: 250px;
								border-bottom: 1px solid   black;"></p>
                    @endif
                </td>
            </tr>
        </table>
    </div> --}}

</body>
<script>
    window.print();
</script>

</html>
