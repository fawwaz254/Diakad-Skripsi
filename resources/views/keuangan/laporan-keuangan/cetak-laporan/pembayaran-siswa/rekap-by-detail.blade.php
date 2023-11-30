<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan Pembayaran Siswa</title>

    <style>
        .page {
            width: 900px;
        }

        table {
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: portrait;
        }

        @media print {
            /* .page {
                page-break-after: always;
            } */
        }
    </style>
</head>

<body>
    @foreach ($data_laporan as $data)
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%;  ">
                <tr>
                    <td>
                        <h4 align="center">LAPORAN PENERIMAAN PEMBAYARAN SISWA<br>
                            {{ 'TANGGAL ' . strtoupper(indonesiaDate($data['tanggal_pembayaran'])) }}</h4>
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10"
                style="width: 90%; border-top: 2px dashed #000; 
			border-bottom: 2px dashed #000;margin: 0 auto;font-weight: bold;">
                <tr>
                    <td style="width: 5%;text-align: center;">No.</td>
                    <td style="width: 10%;text-align: center;">NIS</td>
                    <td style="width: 30%;">Nama Siswa</td>
                    <td style="width: 10%;text-align: center;">Kelas</td>
                    <td style="width: 30%;">Deskripsi</td>
                    <td style="width: 15%;">Penerimaan</td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%;  margin: 0 auto; font-size:12px;">
                @foreach ($data['detail'] as $key => $detail)
                    <tr>
                        <td style="width: 5%;text-align: center;">
                            {{ $key + 1 }}
                        </td>
                        <td style="width: 10%;text-align: center;">
                            {{ $detail['siswa']->nis_siswa }}
                        </td>
                        <td style="width: 30%;">
                            {{ $detail['siswa']->pengguna->nm_pengguna }}
                        </td>
                        <td style="width: 10%;text-align: center;">
                            {{ $detail['siswa']->kelas->nm_kelas }}
                        </td>
                        <td style="width: 30%;">
                            {{ $detail['nama_biaya'] }}
                        </td>
                        <td style="width: 15%;">{{ 'Rp ' . number_format($detail['besar_pembayaran']) }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight: bold;">
                    <td colspan="4" style="width: 65%; "></td>
                    <td style="width: 30%; border-top: 2px dashed #000; 
					border-bottom: 2px dashed #000;">Sub Total
                        Tanggal
                        {{ indonesiaDate($data['tanggal_pembayaran']) }}</td>
                    <td style="width: 15%;border-top: 2px dashed #000; 
					border-bottom: 2px dashed #000;">
                        {{ 'Rp ' . number_format($data['total_pembayaran']) }}</td>
                </tr>
            </table>

        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
