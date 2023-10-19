<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor STS</title>


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

        <br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

            <tr>
                <td colspan="10" style="border-style : hidden">

                    <h2 align="center" style="margin-top: 3px">
                        DAFTAR NILAI RAPOR SISIPAN<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                        TAHUN AJARAN {{ $rapor_sisipan->semester->tahun_ajaran }}

                    </h2>
                </td>
            <tr>

            <tr style="border-style : hidden">
                <td style="border-style : hidden;font-weight: bold;float:left">MATA PELAJARAN :
                    {{ $rapor_sisipan->mata_pelajaran->nm_mata_pelajaran }}</td>
                <td style="border-style : hidden;font-weight: bold;float:right">KELAS :
                    {{ $rapor_sisipan->kelas->nm_kelas }}</td>
            </tr>
        </table>

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
            <thead class="head">
                <tr>
                    <td colspan="2" style="text-align: center;font-weight: bold;">NOMOR</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">NAMA SISWA<br></td>
                    <td colspan="5" style="text-align: center;font-weight: bold;">Nilai Tugas dan Ulangan</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">UTS<br></td>
                    <td colspan="2" style="text-align: center;font-weight: bold;">Nilai Akhir</td>
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;">URT</td>
                    <td style="text-align: center;font-weight: bold;">INDUK</td>
                    <td style="text-align: center;font-weight: bold;">1</td>
                    <td style="text-align: center;font-weight: bold;">2</td>
                    <td style="text-align: center;font-weight: bold;">3</td>
                    <td style="text-align: center;font-weight: bold;">4</td>
                    <td style="text-align: center;font-weight: bold;">Rata-rata</td>
                    <td style="text-align: center;font-weight: bold;">Rata-rata</td>
                    <td style="text-align: center;font-weight: bold;">Kriteria</td>
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
                            @if ($nilai->type != 'uts')
                                <td style="text-align: center;">
                                    {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa] != '0' ? $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa] : null }}
                                </td>
                            @endif
                        @endforeach

                        <td style="text-align: center;">
                            {{ $nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa] != '0' ? intval($nilai_siswa['rata_rata_nilai_tugas' . $siswa->id_siswa]) : null }}
                        </td>
                        <td style="text-align: center;">
                            {{ $nilai_siswa['uts' . $siswa->id_siswa] != '0' ? $nilai_siswa['uts' . $siswa->id_siswa] : null }}
                        </td>
                        <td style="text-align: center;">
                            {{ $nilai_siswa['rata_rata' . $siswa->id_siswa] != '0' ? intval($nilai_siswa['rata_rata' . $siswa->id_siswa]) : null }}
                        </td>
                        <td style="text-align: center;">
                            {{ $nilai_siswa['kriteria' . $siswa->id_siswa] != '0' ? $nilai_siswa['kriteria' . $siswa->id_siswa] : null }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <br>
        <table style="width: 60%; margin-left:10%;">
            <tr>
                <td colspan="4">
                    <p style="padding: 10px">
                        KD 3.1 & KD 3.2 = Pengetahuan (Proses & Tugas)
                        <br>
                        KD 4.1 & KD 4.2 = Keterampilan (Praktek/Projek/Produk/Portofolio)
                    </p>
                </td>
            </tr>
        </table>
        <br><br><br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
            <tr>
                <td style=" border-style : hidden; width:65%; vertical-align: text-top; padding:0">
                    <p style="margin-left: 10%;">

                    </p>
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
