<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi</title>


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
                        REKAP ABSENSI <br>
                        {{-- {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br> --}}
                         {{  \Carbon\Carbon::parse($start_date)->format('d F Y')}} <span style="font-weight:normal">-</span> {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}

                    </h2>
                </td>
            <tr>

            <tr style="border-style : hidden">
                <td style="border-style : hidden;width: 75%;font-weight: bold;">Nama :
                    {{ $pengguna->gelar_depan }} {{ $pengguna->nm_pengguna }} {{ $pengguna->gelar_belakang }}
                <td style="border-style : hidden;width: 25%;font-weight: bold;">Unit Kerja :
                    {{ isset($pengguna->guru->unit_kerja->nm_unit_kerja) ? $pengguna->guru->unit_kerja->nm_unit_kerja : 'Pegawai' }}
            </tr>
        </table>

        <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
            <thead class="head">
                <tr>
                    <td style="text-align: center;font-weight: bold;">Nomor</td>
                    <td style="text-align: center;font-weight: bold;">Status<br></td>
                    <td style="text-align: center;font-weight: bold;">Jumlah</td>
                    <td style="text-align: center;font-weight: bold;">Hari, Tanggal</td>
                    <td style="text-align: center;font-weight: bold;">Check-in</td>
                    <td style="text-align: center;font-weight: bold;">Check-out</td>
                </tr>
            </thead>
            <tbody class="body">
                @php
                    $no = 0;
                @endphp
                {{-- Masuk --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Tepat Waktu</td>
                    <td style="text-align: center;"> {{ $data['masuk'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Masuk')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Masuk')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Masuk')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
{{-- Terlambat --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Terlambat</td>
                    <td style="text-align: center;"> {{ $data['telat'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
{{-- Izin --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Izin</td>
                    <td style="text-align: center;"> {{ $data['izin'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'izin')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                                Note : {{ isset($h['note']) ? isset($h['note']) : '-'   }} <br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'izin')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'izin')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
{{-- Sakit --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Sakit</td>
                    <td style="text-align: center;"> {{ $data['sakit'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'sakit')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                                Note : {{ isset($h['note']) ? isset($h['note']) : '-'   }} <br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'sakit')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'sakit')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
{{-- Alpha --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Alpha</td>
                    <td style="text-align: center;"> {{ $data['alpha'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Alpha')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Alpha')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Alpha')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>

                {{-- Tidak Checkout --}}

                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Tidak Checkout</td>
                    <td style="text-align: center;"> {{ $data['tidakCheckout'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Tidak Checkout')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Tidak Checkout')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Tidak Checkout')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>

                {{-- Terlambat tidak Checkout --}}

                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Terlambat, Tidak Checkout</td>
                    <td style="text-align: center;"> {{ $data['Telat & Tidak Checkout'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat & Tidak Checkout')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat & Tidak Checkout')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat & Tidak Checkout')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>

                {{-- Pulang lebih awal --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Pulang Lebih Awal</td>
                    <td style="text-align: center;"> {{ $data['pulang'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Pulang lebih awal')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Pulang lebih awal')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Pulang lebih awal')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>
{{-- Terlambat dan Pulang Lebih Awal --}}
                <tr>
                    <td style="text-align: center;">{{ ++$no }}</td>
                    <td style="text-align: center;">Terlambat, Pulang Lebih Awal</td>
                    <td style="text-align: center;"> {{ $data['telatDanPulangLebihAwal'] }} x</td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat dan Pulang lebih awal')
                                {{ $h['day'] }}, {{ $h['date'] }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat dan Pulang lebih awal')
                                {{ isset($h['check_in']) ? $h['check_in'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                    <td style="text-align: center;">
                        @foreach ($hasil as $h)
                            @if ($h['status'] == 'Telat dan Pulang lebih awal')
                                {{ isset($h['check_out']) ? $h['check_out'] : '-' }}<br>
                            @endif
                        @endforeach
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</body>
<script>
    window.print();
</script>

</html>
