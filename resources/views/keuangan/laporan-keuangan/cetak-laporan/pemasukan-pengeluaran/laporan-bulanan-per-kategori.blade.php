<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        .page {
            width: 1200px;
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

        td {
            font-size: 10px;
            padding-top: 0;
            padding-bottom: 0;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bg-grey {
            background-color: #9e9e9e80;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>
@php
    $bulan = $data_laporan['bulan'];
    $tahun = $data_laporan['tahun'];
    $tutup_buku_bulanan_biaya = $data_laporan['tutup_buku_bulanan_biaya'];
    $subkategori = $data_laporan['subkategori'];
    $total_bayar_non_kbm = $data_laporan['total_bayar_non_kbm'];
    $subkategori_in = $subkategori->where('kategori.tipe_kategori_rapb', 1)->values();
    $subkategori_out = $subkategori->where('kategori.tipe_kategori_rapb', 2)->values();

    $length_column = 5 + $subkategori_in->count() + $subkategori_out->count();
@endphp

<body>
    <img style="position: absolute;"
        src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
        height="65">
    <table width="100%">
        <tr valign=top>
            <td>
                <center>
                    <h1 style="margin: 0;">{{ strtoupper($auth_data->sekolah_data->nm_yayasan_sekolah) }}</h1>
                </center>
            </td>
        </tr>
        <tr valign=top>
            <td>
                <center>
                    <h2 style="margin: 0;">{{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h2>
                </center>
            </td>
        </tr>
        <tr valign=top>
            <td>
                <center>
                    <h3 style="margin: 0;">{{ $auth_data->sekolah_data->alamat_jalan }} Telp.
                        {{ $auth_data->sekolah_data->nomor_telp_sekolah }}</h3>
                </center>
            </td>
        </tr>
    </table>
    <hr>
    <table width="100%">
        <tr valign=top>
            <td>
                <center>
                    <h4 style="margin: 0;">LAPORAN HARIAN</h4>
                </center>
            </td>
        </tr>
        <tr valign=top>
            <td>
                <center>
                    <h4 style="margin: 0;">{{ $bulan->nm_bulan }} {{ $tahun }}</h4>
                </center>
            </td>
        </tr>
    </table>
    <table class="is-bordered" width="100%" border=1 cellpadding=0.75 cellspacing=0
        style="background-color: #ffffff; word-wrap:break-word; table-layout: fixed; width: 1440px;">
        <thead>
            <tr valign=middle>
                <td class="text-center text-bold" width="15" rowspan="2">TL</td>
                <td class="text-center text-bold" width="200" rowspan="2">URAIAN</td>
                <td class="text-center text-bold" width="275" colspan="{{ $subkategori_in->count() }}">Penerimaan
                </td>
                <td class="text-center text-bold" width="55" rowspan="2">Jumlah Penerimaan</td>
                <td class="text-center text-bold" width="680" colspan="{{ $subkategori_out->count() }}">Pengeluaran
                </td>
                <td class="text-center text-bold" width="55" rowspan="2">Jumlah Pengeluaran</td>
                <td class="text-center text-bold" width="55" rowspan="2">SALDO KAS</td>
            </tr>
            @php
                $total_all = [];
                $total_all['subtotal_in'] = 0;
                $total_all['subtotal_out'] = 0;
                $total_all['grand_total'] = 0;
                $subsidi_bos = 0;
            @endphp
            <tr valign=middle>
                @foreach ($subkategori_in as $data_subkategori)
                    <td class="text-center text-bold">{{ $data_subkategori->deskripsi_subkategori_rapb }}</td>
                    @php
                        $total_all[$data_subkategori->id_subkategori_rapb] = 0;
                    @endphp
                @endforeach
                @foreach ($subkategori_out as $data_subkategori)
                    <td class="text-center text-bold" style="font-size: 6px;padding:3px">
                        {{ $data_subkategori->deskripsi_subkategori_rapb }}</td>
                    @php
                        $total_all[$data_subkategori->id_subkategori_rapb] = 0;
                    @endphp
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr valign=middle>
                @for ($i = 1; $i <= $length_column; $i++)
                    <td style="background-color: black; color: white;">
                        <center>{{ $i }}</center>
                    </td>
                @endfor

            </tr>
            <tr valign=middle>
                @for ($i = 1; $i <= $length_column; $i++)
                    <td style="height: 16px;"></td>
                @endfor
            </tr>
            @foreach ($data_laporan['report'] as $report)
                @php
                    $jumlah_in_per_date = 0;
                    $jumlah_out_per_date = 0;
                @endphp
                @foreach ($report['in'] as $report_in)
                    @if ($report_in['category'] != 'Subsidi BOS')
                        <tr valign=middle>
                            <td class="text-center">{{ $report['day'] }}</td>
                            <td class="text-bold">{{ $report_in['text'] }}</td>
                            @foreach ($subkategori_in as $data_subkategori)
                                <td class="text-right" style="font-size:9px">
                                    {{ $report_in['category'] == $data_subkategori->deskripsi_subkategori_rapb ? number_format($report_in['value']) : '' }}
                                </td>
                                @php
                                    if ($report_in['category'] == $data_subkategori->deskripsi_subkategori_rapb && $report_in['text'] != 'Saldo') {
                                        $total_all[$data_subkategori->id_subkategori_rapb] += $report_in['value'];
                                    }
                                @endphp
                            @endforeach
                            @php
                                $jumlah_in_per_date += $report_in['value'];
                            @endphp
                            <td class="text-right">
                                {{ number_format($report_in['value']) }}
                            </td>
                            @foreach ($subkategori_out as $data_subkategori)
                                <td></td>
                            @endforeach
                            <td></td>
                            <td></td>
                        </tr>
                    @else
                        @php
                            $subsidi_bos += $report_in['value'];
                        @endphp
                    @endif
                @endforeach

                @foreach ($report['out'] as $report_out)
                    <tr valign=middle>
                        <td class="text-center">{{ $report['day'] }}</td>
                        <td class="text-bold">{{ $report_out['text'] }}</td>
                        @foreach ($subkategori_in as $data_subkategori)
                            <td></td>
                        @endforeach
                        <td></td>
                        @foreach ($subkategori_out as $data_subkategori)
                            <td class="text-right" style="font-size:8px">
                                {{ $report_out['category'] == $data_subkategori->deskripsi_subkategori_rapb ? number_format($report_out['value']) : '' }}
                            </td>
                            @php
                                if ($report_out['category'] == $data_subkategori->deskripsi_subkategori_rapb) {
                                    $total_all[$data_subkategori->id_subkategori_rapb] += $report_out['value'];
                                }
                            @endphp
                        @endforeach
                        @php
                            $jumlah_out_per_date += $report_out['value'];
                        @endphp
                        <td class="text-right">
                            {{ number_format($report_out['value']) }}
                        </td>
                        <td></td>
                    </tr>
                @endforeach
                <tr valign=middle>
                    <td class="bg-grey"></td>
                    <td class="bg-grey text-bold text-right">Jumlah</td>
                    @foreach ($subkategori_in as $data_subkategori)
                        <td class="bg-grey"></td>
                    @endforeach
                    <td class="bg-grey text-bold text-right" style=" font-size:9px">
                        {{ number_format($jumlah_in_per_date) }}</td>
                    @foreach ($subkategori_out as $data_subkategori)
                        <td class="bg-grey"></td>
                    @endforeach
                    <td class="bg-grey text-bold text-right">{{ number_format($jumlah_out_per_date) }}</td>
                    <td class="bg-grey text-bold text-right" style=" font-size:9px">
                        {{ number_format($jumlah_in_per_date - $jumlah_out_per_date) }}</td>
                </tr>
            @endforeach


            <tr valign=middle>
                <td></td>
                <td class="text-bold">Tunggakan SPP Tahun Lalu yang masuk</td>
                @foreach ($subkategori_in as $data_subkategori)
                    @if ($data_subkategori->deskripsi_subkategori_rapb == 'Lain-Lain')
                        <td class="text-bold text-right">
                            {{ number_format($tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu) }}
                        </td>
                        @php
                            $total_all[$data_subkategori->id_subkategori_rapb] += $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu;
                            $jumlah_in_per_date += $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu;
                        @endphp
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="text-bold text-right">
                    {{ number_format($tutup_buku_bulanan_biaya->jml_pembayaran_biaya_tahun_lalu) }}
                </td>
                @foreach ($subkategori_out as $data_subkategori)
                    <td></td>
                @endforeach
                <td class="text-bold text-right"></td>
                <td class="text-bold text-right"></td>
            </tr>
            <tr valign=middle>
                <td></td>
                <td class="text-bold">Subsidi BOS</td>
                @foreach ($subkategori_in as $data_subkategori)
                    @if ($data_subkategori->deskripsi_subkategori_rapb == 'Subsidi BOS')
                        <td class="text-bold text-right">{{ number_format($subsidi_bos) }}</td>
                        @php
                            $total_all[$data_subkategori->id_subkategori_rapb] += $subsidi_bos;
                            $jumlah_in_per_date += $subsidi_bos;
                        @endphp
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="text-bold text-right">{{ number_format($subsidi_bos) }}</td>
                @foreach ($subkategori_out as $data_subkategori)
                    <td></td>
                @endforeach
                <td class="text-bold text-right"></td>
                <td class="text-bold text-right"></td>
            </tr>
            <tr valign=middle>
                <td></td>
                <td class="text-bold">Pembayaran Wajib Lain Bersama SPP</td>
                @foreach ($subkategori_in as $data_subkategori)
                    <td></td>
                @endforeach
                <td class="text-bold text-right"></td>
                @foreach ($subkategori_out as $data_subkategori)
                    @if ($data_subkategori->deskripsi_subkategori_rapb == 'Pembelajaran Non KBM')
                        <td class="text-bold text-right" style="font-size:9px">
                            {{ number_format($total_bayar_non_kbm) }}</td>
                        @php
                            $total_all[$data_subkategori->id_subkategori_rapb] += $total_bayar_non_kbm;
                        @endphp
                    @else
                        <td></td>
                    @endif
                @endforeach
                <td class="text-bold text-right">{{ number_format($total_bayar_non_kbm) }}</td>
                <td class="text-bold text-right"></td>
            </tr>
            <tr valign=middle>
                <td class="bg-grey"></td>
                <td class="bg-grey text-bold text-right">Jumlah</td>
                @foreach ($subkategori_in as $data_subkategori)
                    <td class="bg-grey"></td>
                @endforeach
                <td class="bg-grey text-bold text-right">
                    {{ number_format($jumlah_in_per_date - $jumlah_out_per_date) }}</td>
                @foreach ($subkategori_out as $data_subkategori)
                    <td class="bg-grey"></td>
                @endforeach
                <td class="bg-grey text-bold text-right">{{ number_format($total_bayar_non_kbm) }}</td>
                <td class="bg-grey text-bold text-right">
                    {{ number_format($jumlah_in_per_date - $jumlah_out_per_date - $total_bayar_non_kbm) }}</td>
            </tr>
            <tr>
                <td colspan="{{ $length_column }}" style="height: 16px;">

                </td>
            </tr>
            <tr valign=middle>
                <td class="bg-grey"></td>
                <td class="bg-grey text-bold text-right">TOTAL</td>
                @foreach ($subkategori_in as $data_subkategori)
                    <td class="bg-grey text-bold text-right">
                        {{ number_format($total_all[$data_subkategori->id_subkategori_rapb]) }}</td>
                    @php
                        $total_all['subtotal_in'] += $total_all[$data_subkategori->id_subkategori_rapb];
                    @endphp
                @endforeach
                <td class="bg-grey text-bold text-right" style="font-size: 9px; ">
                    {{ number_format($total_all['subtotal_in']) }}</td>
                @foreach ($subkategori_out as $data_subkategori)
                    <td class="bg-grey text-bold text-right" style="font-size:8px">
                        {{ number_format($total_all[$data_subkategori->id_subkategori_rapb]) }}</td>
                    @php
                        $total_all['subtotal_out'] += $total_all[$data_subkategori->id_subkategori_rapb];
                    @endphp
                @endforeach
                <td class="bg-grey text-bold text-right">{{ number_format($total_all['subtotal_out']) }}</td>
                <td class="bg-grey text-bold text-right">
                    {{ number_format($total_all['subtotal_in'] - $total_all['subtotal_out']) }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <div class="avoid-break">
        <table cellspacing="0" style="width: 80%; margin:auto; text-align:center">
            <tr>
                <td style="width: 50%;">Mengetahui</td>
                <td>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                    {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                </td>
            </tr>
            <tr></tr>
            <tr style="vertical-align: top;">
                <td>
                    Kepala Sekolah
                    <br><br><br><br><br>
                    <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                </td>
                <td>
                    Keuangan
                    <br><br><br><br><br>
                    <b><u>{{ $auth_data->pengguna->nm_pengguna }}</u></b>
                </td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
</body>
<script>
    var bulan = '{{ $data_laporan['bulan']['nm_bulan'] }}';
    var tahun = '{{ $data_laporan['tahun'] }}';

    document.title = 'Laporan Bulanan dengan Tunggakan' + ' - ' + bulan + ' - ' + tahun;
    window.print();
</script>

</html>
