<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor STS</title>


    <style>
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

        .page {
            width: 1200px;
        }

        .body {

            border: 5px double;
            border-top-style: none;
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

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
            <tr style="border-style : hidden;">
                <td colspan="10" style="border-style : hidden">

                    <h2 align="center" style="font-family: Tahoma;  margin-top: 5px">
                        DAFTAR NILAI RAPOR SISIPAN<br>
                        {{ $auth_data->sekolah_data->nm_sekolah }}<br>
                        {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
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
                    <td colspan="2" style="text-align: center;font-weight: bold;">NILAI FORMATIF</td>
                    <td colspan="2" style="text-align: center;font-weight: bold;">NILAI SUMATIF</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">STS</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">RT2SMT</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">RAPOR<br>SISIPAN</td>
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;">URT</td>
                    <td style="text-align: center;font-weight: bold;">INDUK</td>
                    <td style="text-align: center;font-weight: bold;">1</td>
                    <td style="text-align: center;font-weight: bold;">2</td>
                    <td style="text-align: center;font-weight: bold;">1</td>
                    <td style="text-align: center;font-weight: bold;">2</td>
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
                        <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                        @foreach ($list_data as $nilai)
                            <td style="text-align: center;">


                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}

                            </td>
                        @endforeach
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2']) / 2) }}
                        </td>
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . 'sts']) / 3) }}

                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</body>
<script>
    window.print();
</script>

</html>
