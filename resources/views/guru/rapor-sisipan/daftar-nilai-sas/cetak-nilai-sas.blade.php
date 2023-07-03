<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Akhir Semester</title>


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
    </style>
</head>

<body>
    <div class="page">
        <table style="width: 40%; margin-left:5%;">
            <tr>
                <td colspan="4">
                    <h2 align="center" style="margin-top: 1px; font-family: 'Calibri';">
                        {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                        <br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                    </h2>
                </td>
            </tr>
        </table>
        <br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

            <tr>
                <td colspan="10" style="border-style : hidden">

                    <h2 align="center" style="margin-top: 3px">
                        DAFTAR NILAI<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                        TAHUN AJARAN {{ $rapor_sisipan->semester->tahun_ajaran }}

                    </h2>
                </td>
            <tr>

            <tr style="border-style : hidden">
                <td style="border-style : hidden;width: 75%;font-weight: bold;">MATA PELAJARAN :
                    {{ $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran }}</td>
                <td style="border-style : hidden;width: 25%;font-weight: bold;">KELAS :
                    {{ $rapor_sisipan->kelas->nm_kelas }}</td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
            <thead class="head">
                <tr>
                    <td colspan="2" style="text-align: center;font-weight: bold;">NOMOR</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">NAMA SISWA<br></td>
                    <td colspan="4" style="text-align: center;font-weight: bold;">NILAI FORMATIF</td>
                    <td colspan="6" style="text-align: center;font-weight: bold;">NILAI SUMATIF</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">RT2<br>SMT</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">STS</td>
                    {{-- <td colspan="1" style="text-align: center;font-weight: bold;">STS</td> --}}
                    <td rowspan="2" style="text-align: center;font-weight: bold;">SAS</td>
                    {{-- <td colspan="1" style="text-align: center;font-weight: bold;">SAS</td> --}}
                    {{-- <td rowspan="2" style="text-align: center;font-weight: bold;">RAPOR</td> --}}
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;">URT</td>
                    <td style="text-align: center;font-weight: bold;">INDUK</td>
                    <td style="text-align: center;font-weight: bold;">1</td>
                    <td style="text-align: center;font-weight: bold;">2</td>
                    <td style="text-align: center;font-weight: bold;">3</td>
                    <td style="text-align: center;font-weight: bold;">4</td>
                    <td style="text-align: center;font-weight: bold;">1</td>
                    <td style="text-align: center;font-weight: bold;">2</td>
                    <td style="text-align: center;font-weight: bold;">3</td>
                    <td style="text-align: center;font-weight: bold;">4</td>
                    <td style="text-align: center;font-weight: bold;">5</td>
                    <td style="text-align: center;font-weight: bold;">6</td>
                    {{-- <td style="text-align: center;font-weight: bold;">40%</td> --}}
                    {{-- <td style="text-align: center;font-weight: bold;">60%</td> --}}
                </tr>
            </thead>
            <tbody class="body">
                @php
                    $no = 0;
                @endphp
                @php
                    $no = 0;
                @endphp

                @foreach ($list_siswa as $siswa)
                    <tr>
                        <td style="text-align: center;">{{ ++$no }}</td>
                        <td style="text-align: center;">{{ $siswa->nis_siswa }}</td>
                        <td>{{ strtoupper($siswa->pengguna->nm_pengguna) }}</td>
                        @foreach ($list_data as $nilai)
                            @if (!in_array($nilai->nm_nilai, ['STS', 'SAS']))
                                <td style="text-align: center;">
                                    {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                                </td>
                            @endif
                        @endforeach
                        <td style="text-align: center;">
                            <?php
                            $test = [$nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'], $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2'], $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi3'], $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi4']];
                            $test2 = array_diff($test, [0]);
                            $tes = array_sum($test2);
                            if ($tes==0) {
                                $test3= 0;
                            } else {
                                $test3 = array_sum($test2) / count($test2);
                            }
                            ?>
                            {{ round($test3) }}
                        </td>
                        <td style="text-align: center;">{{ round($nilai_komponen[$siswa->id_siswa . 'sts']) }}</td>
                        {{-- <td style="text-align: center;">
                            {{ round(($nilai_komponen[$siswa->id_siswa . 'sts'] * 40) / 100) }}
                        </td> --}}
                        <td style="text-align: center;">{{ round($nilai_komponen[$siswa->id_siswa . 'sas']) }}</td>
                        {{-- <td style="text-align: center;">
                            {{ round(($nilai_komponen[$siswa->id_siswa . 'sas'] * 60) / 100) }}
                        </td>
                        <td style="text-align: center;">
                            {{ round(($test3 * 2 + ($nilai_komponen[$siswa->id_siswa . 'sts'] * 40) / 100 + ($nilai_komponen[$siswa->id_siswa . 'sas'] * 60) / 100) / 3) }}
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>

        </table>
        {{-- <table style="width: 38%; margin-left:5%; margin-top:20px">
            <tr>
                <td colspan="1" style="  border-left: 0px solid;
                border-right: 0px solid;"> --}}

        {{-- 
            <p align="center">
                        RAPOR =
                    </p>
                </td>
                <td colspan="2" style="  border-left: 0px solid;
                border-right: 0px solid;">
                    <p align="center" class="under-below" style="padding-bottom: 0">
                        {(2 x RT2 SMT)+(40%STS)+(60%SAS)}
                    </p>
                    <p align="center" style="margin-top: -8px">3</p>
               --}}
        {{-- </td>
            </tr>
        </table> --}}

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">

            <tr>
                <td style=" border-style : hidden; width:65%; vertical-align: text-top; padding:0">
                    {{-- <p style="margin-left: 10%;">
                        SMT = Sumatif
                        <br>
                        STS = Sumatif Tengah Semester
                        <br>
                        SAS = Sumatif Akhir Semester
                        <br>
                        SAT = Sumatif Akhir Tahun
                    </p> --}}
                </td>

                <td style="width:25%">Sidoarjo, {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                    <br>
                    Guru Bidang Study
                    <br><br><br><br><br><br><br>
                    {{ $rapor_sisipan->pengguna->gelar_depan }} {{ $rapor_sisipan->pengguna->nm_pengguna }}
                    {{ $rapor_sisipan->pengguna->gelar_belakang }}
                </td>

            </tr>

        </table>

    </div>
</body>
<script>
    window.print();
</script>

</html>
