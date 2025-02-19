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
            margin: 20px 10px;
            text-align: center;
            width: 33%;
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
            size: auto !important;
        }
    </style>
</head>

<body>
    <div class="page">
        <table>
            <tr>
                <td>
                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        alt="Logo Sekolah" style="height:50px;" />
                </td>
                <td>
                    <h2 style="margin-left: 10px;">Tanda Bukti Pembayaran<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h2>
                </td>
            </tr>
        </table>
        @if (isset($id_siswa))
            @foreach ($pembayaranByDate as $tgl_pembayaran => $pembayarans)
                <table border="1" cellspacing="0" cellpadding="5" style="width: 100%;" class="text-left">
                    <tr>
                        <td colspan="2" style="text-align: center;"><b>TANDA BUKTI PEMBAYARAN
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100%; vertical-align: top;">
                            <table border="0" cellspacing="0" cellpadding="5"
                                style="width: 100%; vertical-align: top;" class="text-left">
                                <tr>
                                    <th style="width: 25%;">No.</th>
                                    <td style="width: 5px;">:</td>
                                    <td>.........................................................................</td>
                                <tr>
                                    <th>Terima Dari</th>
                                    <td>:</td>
                                    <td>{{ $pembayarans[0]->tagihan_biaya->siswa->pengguna->nm_pengguna }}</td>
                                </tr>
                                @php
                                    $data_month = [
                                        (object) ['id' => 1, 'name' => 'Januari'],
                                        (object) ['id' => 2, 'name' => 'Februari'],
                                        (object) ['id' => 3, 'name' => 'Maret'],
                                        (object) ['id' => 4, 'name' => 'April'],
                                        (object) ['id' => 5, 'name' => 'Mei'],
                                        (object) ['id' => 6, 'name' => 'Juni'],
                                        (object) ['id' => 7, 'name' => 'Juli'],
                                        (object) ['id' => 8, 'name' => 'Agustus'],
                                        (object) ['id' => 9, 'name' => 'September'],
                                        (object) ['id' => 10, 'name' => 'Oktober'],
                                        (object) ['id' => 11, 'name' => 'November'],
                                        (object) ['id' => 12, 'name' => 'Desember'],
                                    ];
                                @endphp
                                <tr style="vertical-align: top;">
                                    <th>Untuk Pembayaran</th>
                                    <td>:</td>
                                    <td>
                                        @foreach ($pembayarans as $bayar)
                                            @if ($bayar->id_bulan)
                                                {{ $bayar->nm_biaya }}
                                                @foreach ($data_month as $month)
                                                    @if ($month->id == $bayar->id_bulan)
                                                        {{ $month->name }}
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ $bayar->nm_biaya }}
                                            @endif
                                            {{ $bayar->tahun }}
                                            @if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jumlah</th>
                                    <td>:</td>
                                    <td>
                                        {{ 'Rp ' . number_format($pembayarans->sum('besar_pembayaran')) }}
                                    </td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <th>Terbilang</th>
                                    <td>:</td>
                                    <td>
                                        <i>
                                            {{ App\Libraries\Keuangan\LibDataKeuangan::getTerbilang($pembayarans->sum('besar_pembayaran')) }}
                                            rupiah
                                        </i>
                                    </td>
                                </tr>
                            </table>
                            <table cellspacing="0" cellpadding="10" style="width: 100%;">
                                <tr>
                                    <td class="ttd"></td>
                                    <td class="ttd"></td>
                                    <td class="ttd">
                                        {{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ',' : null }}
                                        {{ date_format(new DateTime($tgl_pembayaran), 'j M Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ttd">
                                        Kepala Sekolah
                                        <br><br><br><br>
                                        <span style="text-decoration: underline;">
                                            {{ $auth_data->sekolah_data->nm_kepala_sekolah }} </span>
                                    </td>
                                    <td class="ttd">
                                        Keuangan
                                        <br><br><br><br>
                                        @if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smamaryamsby')
                                            <span style="text-decoration: underline;">
                                                {{ $auth_data->pengguna->nm_pengguna }}
                                            </span>
                                        @else
                                            <span style="text-decoration: underline;"> Bendahara SMA Maryam
                                            </span>
                                        @endif
                                    </td>
                                    <td class="ttd">
                                        Penerima
                                        <br><br><br><br>
                                        .........................
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            @endforeach
        @else
            <table border="1" cellspacing="0" cellpadding="5" style="width: 100%;" class="text-left">
                <tr>
                    <td colspan="2" style="text-align: center;"><b>TANDA BUKTI PEMBAYARAN
                            @if ($pembayaran->tagihan_biaya->keterangan == '-' || $pembayaran->tagihan_biaya->keterangan == null)
                                SPP
                            @else
                                {{ $pembayaran->tagihan_biaya->keterangan }}
                            @endif
                        </b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%; vertical-align: top;">
                        <table border="0" cellspacing="0" cellpadding="5" style="width: 100%; vertical-align: top;"
                            class="text-left">
                            <tr>
                                <th style="width: 25%;">No.</th>
                                <td style="width: 5px;">:</td>
                                <td>.........................................................................</td>
                            <tr>
                                <th>Terima Dari</th>
                                <td>:</td>
                                <td>{{ $pembayaran->tagihan_biaya->siswa->pengguna->nm_pengguna }}</td>
                            </tr>
                            @php
                                $data_month = [
                                    (object) ['id' => 1, 'name' => 'Januari'],
                                    (object) ['id' => 2, 'name' => 'Februari'],
                                    (object) ['id' => 3, 'name' => 'Maret'],
                                    (object) ['id' => 4, 'name' => 'April'],
                                    (object) ['id' => 5, 'name' => 'Mei'],
                                    (object) ['id' => 6, 'name' => 'Juni'],
                                    (object) ['id' => 7, 'name' => 'Juli'],
                                    (object) ['id' => 8, 'name' => 'Agustus'],
                                    (object) ['id' => 9, 'name' => 'September'],
                                    (object) ['id' => 10, 'name' => 'Oktober'],
                                    (object) ['id' => 11, 'name' => 'November'],
                                    (object) ['id' => 12, 'name' => 'Desember'],
                                ];
                            @endphp
                            <tr style="vertical-align: top;">
                                <th>Untuk Pembayaran</th>
                                <td>:</td>
                                <td>
                                    @if ($pembayaran->tagihan_biaya->keterangan == '-' || $pembayaran->tagihan_biaya->keterangan == null)
                                        SPP
                                        @foreach ($data_month as $month)
                                            @if ($pembayaran->id_bulan == $month->id)
                                                {{ $month->name }}
                                            @endif
                                        @endforeach
                                    @else
                                        {{ $pembayaran->tagihan_biaya->keterangan }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>:</td>
                                <td>{{ 'Rp ' . number_format($pembayaran->besar_pembayaran) }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <th>Terbilang</th>
                                <td>:</td>
                                <td><i>{{ ucfirst(trim($terbilang)) }} rupiah</i></td>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="10" style="width: 100%;">
                            <tr>
                                <td class="ttd"></td>
                                <td class="ttd"></td>
                                <td class="ttd">
                                    {{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ',' : null }}
                                    {{ date_format(new DateTime($pembayaran->tgl_pembayaran), 'j M Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="ttd">
                                    Kepala Sekolah
                                    <br><br><br><br>
                                    <span style="text-decoration: underline;">
                                        {{ $auth_data->sekolah_data->nm_kepala_sekolah }} </span>
                                </td>
                                <td class="ttd">
                                    Keuangan
                                    <br><br><br><br>
                                    @if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smamaryamsby')
                                        <span style="text-decoration: underline;">
                                            {{ $auth_data->pengguna->nm_pengguna }}
                                        </span>
                                    @else
                                        <span style="text-decoration: underline;"> Bendahara SMA Maryam
                                        </span>
                                    @endif
                                </td>
                                <td class="ttd">
                                    Penerima
                                    <br><br><br><br>
                                    .........................
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        @endif
        <div>
            <p style="font-size: x-small;">Tanggal sekarang: {{ \Carbon\Carbon::now()->format('j M Y') }}</p>
        </div>
        <div class="clear"></div>
    </div>
    {{-- <div class="page">
        <table>
            <tr>
                <td>
                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                        alt="Logo Sekolah" style="height:50px;" />
                </td>
                <td>
                    <h2 style="margin-left: 10px;">Tanda Bukti Pembayaran<br>
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h2>
                </td>
            </tr>
        </table>

        <div>
            <p style="font-size: x-small;">Tanggal sekarang: {{ \Carbon\Carbon::now()->format('j M Y') }}</p>
        </div>
        <div class="clear"></div>
    </div> --}}
</body>
<script>
    window.print();
</script>

</html>
