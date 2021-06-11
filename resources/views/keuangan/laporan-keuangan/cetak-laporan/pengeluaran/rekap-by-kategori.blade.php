<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan</title>

    <style>
        .page {
            width: 900px;
        }
        
        .ttd {
            margin-top: 30px;
            text-align: right;
        }
        
        .clear {
            clear: both;
        }

        .avoid-break {
            page-break-inside: avoid;
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

        .mb-4 {
            margin-bottom: 40px;
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
        
        <table cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" /></td>
                <td colspan=6><h1 align="center">LAPORAN PEMBAYARAN PENGELUARAN PER KATEGORI<br> {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1></td>
            </tr>
        </table>
        <table>
            <tr>
            @if($start_date != $end_date)
                <td colspan="3"><b>TANGGAL   {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $end_date)->format('d M Y') }}</b></td>
            @else
                <td colspan="3"><b>TANGGAL   {{ \Carbon\Carbon::createFromFormat('Y-m-d', $start_date)->format('d M Y') }}</b></td>
            @endif
            </tr>
        </table>
        <br>
        <table border="1" cellspacing="0" cellpadding="10" style="width: 100%;">
        @if(isset($data_laporan['data']))
            @foreach($data_laporan['data'] as $nm_kategori => $kategori_laporan)
            <tr>
                <th colspan="6" style="text-align: left; background:lightyellow">
                    {{ $nm_kategori }}
                </th>
            </tr>
            <tr>
                <th style="width: 10px;">No.</th>
                <th colspan="3">Keterangan</th>
                <th>Tanggal Pengeluaran</th>
                <th>Nominal</th>
            </tr>
            @php
                $no = 1;
                $subkategori_non_kbm = $data_laporan['subkategori_non_kbm'];
            @endphp
                @foreach($kategori_laporan as $laporan)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td colspan="3">{{ $laporan->nm_realisasi }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $laporan->tgl_realisasi)->format('d M Y') }}</td>
                    <td style="text-align: right;">{{ number_format($laporan->dana_realisasi) }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="5">TOTAL</th>
                    <th>{{ number_format($kategori_laporan->sum('dana_realisasi')) }}</th>
                </tr>
                <tr><td colspan="6"></td></tr>

            @endforeach
        @if(isset($subkategori_non_kbm))
            @if($subkategori_non_kbm['status'])
            <tr>
                <th colspan="6" style="text-align: left; background:lightyellow">
                    Beban Pembelajaran Non KBM
                </th>
            </tr>
            <tr>
                <th style="width: 10px;">No.</th>
                <th>Keterangan</th>
                @foreach($data_laporan['tingkat'] as $tingkat)
                <th>{{$tingkat}}</th>
                @endforeach
                <th>Total</th>
            </tr>
            @php
                $no = 1;
                $data_laporan['total_data'] += $subkategori_non_kbm['total_bayar'];
            @endphp
                @foreach($subkategori_non_kbm['data'] as $nm_bayar => $data_bayar_non_kbm)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $nm_bayar }}</td>
                    @foreach($data_laporan['tingkat'] as $tingkat)
                    <td style="text-align: right;">{{ number_format($data_bayar_non_kbm[$tingkat]) }}</td>
                    @endforeach
                    <td>{{ number_format(collect($data_bayar_non_kbm)->sum()) }}</td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="5">TOTAL</th>
                    <th>{{ number_format($subkategori_non_kbm['total_bayar']) }}</th>
                </tr>
                <tr><td colspan="6"></td></tr>
            @endif
            <tr>
                <th colspan="5">GRAND TOTAL</th>
                <th>{{ number_format($data_laporan['total_data']) }}</th>
            </tr>
        @endif
        @endif
        </table>
        <div class="avoid-break mt-4 mb-4">
            <table cellspacing="0" style="width: 80%; margin:auto; text-align:center">
                <tr>
                    <td style="width: 50%;">Mengetahui</td>
                    <td>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d'))  }}
                    </td>
                </tr>
                <tr></tr>
                <tr style="vertical-align: top;">
                    <td>
                        Kepala Sekolah
                        <br><br><br><br>
                        <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td>
                        Keuangan
                        <br><br><br><br> 
                        <b><u>{{ $auth_data->pengguna->nm_pengguna }}</u></b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
    </div>
</body>
<script>
    window.print();
</script>
</html>