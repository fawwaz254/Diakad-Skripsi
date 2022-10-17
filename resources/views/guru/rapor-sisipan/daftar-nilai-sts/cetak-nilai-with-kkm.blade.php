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

        td{
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
        {{-- <table style="width: 40%; margin-left:5%;">
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
        <br> --}}
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

            <tr>
                <td colspan="10" style="border-style : hidden">

                    <h2 align="center" style="margin-top: 3px">
                        LAPORAN PENILAIAN HASIL BELAJAR<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                        TENGAH SEMESTER GASAL<br>
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
                    {{-- <td colspan="4" style="text-align: center;font-weight: bold;">MATA PELAJARAN</td> --}}
                    <td colspan="2" style="text-align: center;font-weight: bold;">NILAI UTS</td>
                    {{-- <td rowspan="2" style="text-align: center;font-weight: bold;"></td> --}}
                    {{-- <td rowspan="2" style="text-align: center;font-weight: bold;">RT2<br>SMT</td>
                    <td rowspan="2" style="text-align: center;font-weight: bold;">RAPOR<br>SISIPAN</td> --}}
                </tr>
                <tr>
                    <td style="text-align: center;font-weight: bold;">URT</td>
                    <td style="text-align: center;font-weight: bold;">INDUK</td>
                    <td style="text-align: center;font-weight: bold;">KKM</td>
                    <td style="text-align: center;font-weight: bold;">NILAI</td>
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
                @foreach ($list_data as $nilai)
                    <tr>
                        <td style="text-align: center;">{{ ++$no }}</td>
                        <td style="text-align: center;">{{ $siswa->nis_siswa }}</td>
                        <td>{{ strtoupper($siswa->pengguna->nm_pengguna) }}</td>
                        <td style="text-align: center;">{{ $rapor_sisipan->kkm }}</td>
                        <td style="text-align: center;">{{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}</td>
                        {{-- @foreach ($list_data as $nilai)
                        @if (in_array($nilai->urutan, [1, 2]))
                            <td style="text-align: center;">
                                
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach --}}
                    {{-- <td></td>
                    <td></td> --}}
                    {{-- @foreach ($list_data as $nilai)
                        @if (in_array($nilai->urutan, [5, 6]))
                            <td style="text-align: center;">
                               
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach --}}
                    {{-- <td></td>
                    <td></td> --}}
                    {{-- @foreach ($list_data as $nilai)
                        @if ($nilai->urutan == 9)
                            <td style="text-align: center;">
                           
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2']) / 2) }}
                        </td>
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . 'sts']) / 3) }}

                        </td> --}}
                    </tr>
                    @endforeach
                @endforeach
            </tbody>

        </table>
        {{-- <table style="width: 30%; margin-left:10%; margin-top:20px">
             <tr>
                <td colspan="4">
                    <p align="center" style="display: inline">
                        RAPOR =
                    </p>
                    <p align="center" style="display: inline" class="under-below">
                        {(2 x RT2 SMT)+(STS)}
                    </p>
                </td>
            </tr> 
        </table> --}}
<br><br>
        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
            <tr>
                <td style=" border-style : hidden; width:65%; vertical-align: text-top; padding:0">
                   {{-- <p style="margin-left: 10%;">
                        SMT = Sumatif
                    <br>
                        STS = Sumatif Tengah Semester
                    </p> --}}
                </td>

                <td style="width:25%">Sidoarjo, {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                   <br>
                    Guru Bidang Study 
                    <br><br><br><br><br><br><br>
                    {{ $rapor_sisipan->pengguna->gelar_depan }} {{ $rapor_sisipan->pengguna->nm_pengguna }} {{ $rapor_sisipan->pengguna->gelar_belakang }}
                </td>

            </tr>

        </table> 

    </div>
</body>
<script>
    window.print();
</script>

</html>
