<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tanda Terima Ijazah</title>
    <!-- source https://gist.github.com/alfredoem/c7a6fbcba33c57948132 -->

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        body {
            width: 900px;
            margin: 20px;
        }

        .container {
            margin: 20px;
            padding: 10px;
        }

        p {
            font-size: small;
        }

        table {
            font-size: small;
            border-collapse: collapse;
        }

        tr th {
            font-weight: bold;
            font-size: small;
            text-align: left;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: small;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }

        .presensi td{
            padding: 9px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
        }

        .small {
            font-size: small;
        }

        .vertical {
            text-align:center;
            white-space:nowrap;
            transform: rotate(90deg);
        }

        .mb-0 {
            margin-bottom: 0px;
        }
        
        .mb-05 {
            margin-bottom: 5px;
        }
        
        .mb-1 {
            margin-bottom: 10px;
        }

        .mb-2 {
            margin-bottom: 20px;
        }

        .mt-2 {
            margin-top: 20px;
        }
        
        .mt-4 {
            margin-top: 40px;
        }

        /* .presensi tr td:last-child, .presensi tr th:last-child {
            border-right: 1px solid #000000;
        }

        .presensi tr:last-child td{
            border-bottom: 1px solid #000000;
        } */

        .presensi th{
            padding: 9px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
        }

        .gray {
            background-color: lightgray
        }

        /* #logo { */
            /* -webkit-filter: grayscale(100%); */
            /* Safari 6.0 - 9.0 */
            /* filter: grayscale(100%); */
        /* } */

        .ttd {
            max-width: 80%;
        }

        .avoid-page-break {
            page-break-inside: avoid;
        }
        @page{
            margin: 3cm 1.5cm 3.2cm 1.5cm;
        }
    </style>

</head>

<body>
    <div class="container">
        <table width="100%" style="margin-bottom: 30px;">
            <tr>
                <td width="60">
                    <img id="logo"
                        src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah"
                        height="100px">
                </td>
                <td width="400">
                    <h3>Surat Keterangan Serah Terima Ijazah<br>
                        {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}
                    </h3>
                    <hr>
                </td>
            </tr>
        </table>
        <h4 class="text-center">Surat Keterangan Serah Terima Ijazah</h4>
        <p>Yang bertanda tangan di bawah ini:</p>
        <div class="mb-2">
            <table width="90%" class="" style="margin: auto;">
                <tr>
                    <th width="30%">Penerima</th>
                    <td>: {{ $ijazah->penerima_ijazah != null ? $ijazah->penerima_ijazah : $ijazah->siswa->pengguna->nm_pengguna }}</td>
                </tr>
            </table>
        </div>
        <p>Dengan ini menerangkan bahwa: "Telah menerima Ijazah a.n. {{$ijazah->siswa->pengguna->nm_pengguna}} ({{ ($ijazah->siswa->nisn_siswa != null ? $ijazah->siswa->nisn_siswa : '-' ) .' / '. ($ijazah->siswa->nis_siswa != null ? $ijazah->siswa->nis_siswa : '-' ) }}) dari {{strtoupper($auth_data->sekolah_data->nm_sekolah)}} dengan nomor seri Ijazah: <b>{{ $ijazah->siswa->pengajuan_wisuda->nomor_ijasah }}</b> pada {{ Carbon\Carbon::createFromTimeString($ijazah->tgl_pengambilan_ijazah)->format('H:i - d F Y') }}"</p>

        <p>Demikian surat keterangan ini kami buat dengan sesungguhnya. Atas perhatian, kerjasama, dan kesepakatannya, kami ucapkan terimakasih.</p>
        <div class="mt-4">
            <table width="90%" class="" style="margin: auto; text-align: center;">
                <tr>
                    <td width="50%"></td>
                    <td width="50%">{{ $auth_data->sekolah_data->alamat_kecamatan != null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}{{ Carbon\Carbon::now()->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td width="50%">Mengetahui,</td>
                    <td width="50%">Penerima <br>{{ $ijazah->penerima_ijazah != null ? ('a.n. '. $ijazah->siswa->pengguna->nm_pengguna) : null }}</td>
                </tr>
                <tr>
                    <td width="50%"><br><br><br><u>{{ $auth_data->pengguna->nm_pengguna }}</u></td>
                    <td width="50%"><br><br><br><u>{{ $ijazah->penerima_ijazah != null ? $ijazah->penerima_ijazah : $ijazah->siswa->pengguna->nm_pengguna }}</u></td>
                </tr>
            </table>
        </div>
    </div>
</body>
<script>
    window.print();
</script>
</html>