<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi Siswa</title>

</head>
<style>
    .center {
        margin: auto;
        width: 50%;
        padding: 10px;
    }
</style>
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

<body>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

        <tr>
            <td colspan="10" style="border-style : hidden">

                <h2 align="center" style="margin-top: 3px">
                    REKAP ABSENSI SISWA<br>
                    {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                    {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} <span style="font-weight:normal">-</span>
                    {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}
                    <br>
                    Kelas : {{ $nm_kelas }}

                </h2>
            </td>
        <tr>

    </table>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
        <div id="piechart" class="center"></div>
        <thead class="head" style="background-color: rgb(140, 182, 182)">
            <tr>
                <td style="text-align: center;font-weight: bold;">Hadir</td>
                <td style="text-align: center;font-weight: bold;">Terlambat<br></td>
                <td style="text-align: center;font-weight: bold;">Izin</td>
                <td style="text-align: center;font-weight: bold;">Sakit</td>
                <td style="text-align: center;font-weight: bold;">Alpha</td>
                <td style="text-align: center;font-weight: bold;">Tidak Checkout</td>
                <td style="text-align: center;font-weight: bold;">Pulang Lebih Cepat</td>
            </tr>
        </thead>
        <tbody class="body">
            <tr>
                <td style="text-align: center;">{{ $jumlah_hadir }}</td>
                <td style="text-align: center;">{{ $jumlah_telat }}</td>
                <td style="text-align: center;">{{ $jumlah_izin }}</td>
                <td style="text-align: center;">{{ $jumlah_sakit }}</td>
                <td style="text-align: center;">{{ $jumlah_alpha }}</td>
                <td style="text-align: center;">{{ $tidak_checkout }}</td>
                <td style="text-align: center;">{{ $jumlah_pulangcepat }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
        <thead class="head" style="background-color: rgb(140, 182, 182)">
            <tr>
                <td style="text-align: center;font-weight: bold;">Nomor</td>
                <td style="text-align: center;font-weight: bold;">Status<br></td>

                <td style="text-align: center;font-weight: bold;">Daftar</td>

            </tr>
        </thead>
        <tbody class="body">
            @php
                $no = 0;
                // dd($hasil);
            @endphp
            <tr>
                <td style="text-align: center;">{{ ++$no }}</td>
                <td style="text-align: center;">Terlambat</td>
                <td style="text-align: center;">
                    @foreach ($hasil as $h)
                        @foreach ($h as $h2)
                            @if ($h2['status'] == 'Telat')
                                {{ $h2['nm_pengguna'] }}<br>
                            @break
                        @endif
                    @endforeach
                    @foreach ($h as $h2)
                        @if ($h2['status'] == 'Telat')
                            {{ $h2['nm_hari'] }}, {{ $h2['date'] }} @if (!empty($h2['check_in']))
                                ({{ $h2['check_in'] }})
                            @endif <br>
                        @endif
                    @endforeach
                @endforeach
            </td>

        </tr>
        {{-- Izin --}}
        <tr style="background-color: rgb(207, 215, 215)">
            <td style="text-align: center;">{{ ++$no }}</td>
            <td style="text-align: center;">Izin</td>

            <td style="text-align: center;">
                @foreach ($hasil as $h)
                    @foreach ($h as $h2)
                        @if ($h2['status'] == 'izin')
                            <br>
                            <b>{{ $h2['nm_pengguna'] }}</b><br>
                        @break
                    @endif
                @endforeach
                @foreach ($h as $h2)
                    @if ($h2['status'] == 'izin')
                        {{ $h2['nm_hari'] }}, {{ $h2['date'] }} @if (!empty($h2['note']))
                            ({{ $h2['note'] }})
                        @endif
                        <br>
                    @endif
                @endforeach
            @endforeach
            <br>
        </td>
    </tr>
    {{-- Sakit --}}
    <tr>
        <td style="text-align: center;">{{ ++$no }}</td>
        <td style="text-align: center;">Sakit</td>
        <td style="text-align: center;">
            @foreach ($hasil as $h)
                @foreach ($h as $h2)
                    @if ($h2['status'] == 'sakit')
                        <br>
                        <b>
                            {{ $h2['nm_pengguna'] }}</b><br>
                    @break
                @endif
            @endforeach
            @foreach ($h as $h2)
                @if ($h2['status'] == 'sakit')
                    {{ $h2['nm_hari'] }}, {{ $h2['date'] }} @if (!empty($h2['note']))
                        ({{ $h2['note'] }})
                    @endif
                    <br>
                @endif
            @endforeach
        @endforeach
        <br>
    </td>

</tr>
{{-- Alpha --}}
<tr style="background-color: rgb(207, 215, 215)">
    <td style="text-align: center;">{{ ++$no }}</td>
    <td style="text-align: center;">Alpha</td>
    <td style="text-align: center;">
        @foreach ($hasil as $h)
            @foreach ($h as $h2)
                @if ($h2['status'] == 'Alpha')
                    <br>
                    <b>
                        {{ $h2['nm_pengguna'] }}</b><br>
                @break
            @endif
        @endforeach
        @foreach ($h as $h2)
            @if ($h2['status'] == 'Alpha')
                {{ $h2['nm_hari'] }}, {{ $h2['date'] }}
                <br>
            @endif
        @endforeach
    @endforeach
</td>
<br>
</tr>

{{-- Tidak Checkout --}}

<tr>
<td style="text-align: center;">{{ ++$no }}</td>
<td style="text-align: center;">Tidak Checkout</td>
<td style="text-align: center;">
    @foreach ($hasil as $h)
        @foreach ($h as $h2)
            @if ($h2['status'] == 'Tidak Checkout')
                <br>
                <b>
                    {{ $h2['nm_pengguna'] }}</b><br>
            @break
        @endif
    @endforeach
    @foreach ($h as $h2)
        @if ($h2['status'] == 'Tidak Checkout')
            {{ $h2['nm_hari'] }}, {{ $h2['date'] }}
            <br>
        @endif
    @endforeach
@endforeach
<br>
</td>

<tr style="background-color: rgb(207, 215, 215)">
<td style="text-align: center;">{{ ++$no }}</td>
<td style="text-align: center;">Pulang Lebih Cepat</td>
<td style="text-align: center;">
@foreach ($hasil as $h)
    @foreach ($h as $h2)
        @if ($h2['status'] == 'Pulang lebih awal')
            <br>
            <b>
                {{ $h2['nm_pengguna'] }}</b><br>
        @break
    @endif
@endforeach
@foreach ($h as $h2)
    @if ($h2['status'] == 'Pulang lebih awal')
        {{ $h2['nm_hari'] }}, {{ $h2['date'] }} @if (!empty($h2['check_out']))
            ({{ $h2['check_out'] }})
        @endif <br>
    @endif
@endforeach
@endforeach
<br>
</td>

</tr>

</tbody>

</table>


</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load google charts
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    // Draw the chart and set the chart values
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Masuk', {{ $jumlah_hadir }}],
            ['Sakit', {{ $jumlah_sakit }}],
            ['Izin', {{ $jumlah_izin }}],
            ['Telat', {{ $jumlah_telat }}],
            ['Alpha', {{ $jumlah_alpha }}]
        ]);

        // Optional; add a title and set the width and height of the chart
        var options = {
            'width': 550,
            'height': 400,
            colors: ['#0da300', '#afb607', '#b66907', '#b63b07', '#b60707'],
            is3D: true
        };
        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
    ready(function() {
        window.print();
    });
</script>

</html>
