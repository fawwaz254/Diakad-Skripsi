<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Kwitansi Pengeluaran</title>

    <style>
        .page {
            width: 900px;
        }
        
        .ttd {
            margin: 30px 30px 20px;
            text-align: right;
        }
        
        .clear {
            clear: both;
        }

        .text-left {
            text-align: left;
        }

    </style>
    <style type="text/css" media="print">
        @page {
            size: A4;
        }
    </style>
</head>

<body>
    <div class="page">
        
        <table>
            <tr>
                <td>
                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:50px;" />
                </td>
                <td>
                    <h2 style="margin-left: 10px;">Kwitansi Pembayaran<br> {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}</h2>
                </td>
            </tr>
        </table>
        <table border="1" cellspacing="0" cellpadding="5" style="width: 100%;" class="text-left">
            <tr>
                <td colspan="2" style="text-align: center;"><b>KWITANSI PEMBAYARAN</b> </td>
            </tr>
            <tr>
                <td style="width: 30%; vertical-align: top;">
                    <table border="0" cellspacing="0" cellpadding="5" style="width: 100%;">
                        <tr>
                            <th style="width: 40%;">No.</th>
                            <td style="width: 5px;">:</td>
                            <td style="width: 60%;">..................</td>
                        </tr>
                        <tr>
                            <th>Terima Dari</th>
                            <td style="width: 5px;">:</td>
                            <td>{{ $auth_data->pengguna->nm_pengguna }}</td>
                        </tr>
                        <tr>
                            <th>Untuk Pembayaran</th>
                            <td style="width: 5px;">:</td>
                            <td>{{ $pengeluaran->nm_realisasi }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td style="width: 5px;">:</td>
                            <td>{{ "Rp " . number_format($pengeluaran->dana_realisasi) }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 70%; vertical-align: top;">
                    <table border="0" cellspacing="0" cellpadding="5" style="width: 100%;" class="text-left">
                        <tr>
                            <th style="width: 25%;">No.</th>
                            <td style="width: 5px;">:</td>
                            <td>.........................................................................</td>
                        <tr>
                            <th>Terima Dari</th>
                            <td>:</td>
                            <td>{{ $auth_data->pengguna->nm_pengguna }}</td>
                        </tr>
                        <tr>
                            <th>Untuk Pembayaran</th>
                            <td>:</td>
                            <td>{{ $pengeluaran->nm_realisasi }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>:</td>
                            <td>{{ "Rp " . number_format($pengeluaran->dana_realisasi) }}</td>
                        </tr>
                    </table>
                    <div class="ttd">
                        {{$auth_data->sekolah_data->alamat_kecamatan}}, {{ \Carbon\Carbon::now()->format('j M Y') }} 
                        <br><br><br><br> 
                        .........................
                    </div>
                </td>
            </tr>
        </table>
        <div>
            <p>Tanggal input: {{ date_format(new DateTime($pengeluaran->tgl_realisasi), 'j M Y') }}</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>