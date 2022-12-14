<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Absensi</title>

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
                    REKAP ABSENSI <br>
                    {{-- {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br> --}}
                    {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} <span style="font-weight:normal">-</span>
                    {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}

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
        <div id="piechart" class="center"></div>
        <thead class="head">
            <tr>
                <td style="text-align: center;font-weight: bold;">Tepat Waktu</td>
                <td style="text-align: center;font-weight: bold;">Terlambat<br></td>
                <td style="text-align: center;font-weight: bold;">Izin</td>
                <td style="text-align: center;font-weight: bold;">Sakit</td>
                <td style="text-align: center;font-weight: bold;">Alpha</td>
                <td style="text-align: center;font-weight: bold;">Tidak Checkout</td>
                <td style="text-align: center;font-weight: bold;">Terlambat, Tidak Checkout</td>
                <td style="text-align: center;font-weight: bold;">Pulang, Lebih Awal</td>
                <td style="text-align: center;font-weight: bold;">Terlambat, Pulang Lebih Awal</td>
                {{-- <td style="text-align: center;font-weight: bold;">Check-out</td> --}}
            </tr>
        </thead>
        <tbody class="body">
            <tr>
                <td style="text-align: center;">{{ $data['masuk'] }}</td>
                <td style="text-align: center;">{{ $data['telat'] }}</td>
                <td style="text-align: center;">{{ $data['izin'] }}</td>
                <td style="text-align: center;">{{ $data['sakit'] }}</td>
                <td style="text-align: center;">{{ $data['alpha'] }}</td>
                <td style="text-align: center;">{{ $data['tidakCheckout'] }}</td>
                <td style="text-align: center;">{{ $data['Telat & Tidak Checkout'] }}</td>
                <td style="text-align: center;">{{ $data['pulang'] }}</td>
                <td style="text-align: center;">{{ $data['telatDanPulangLebihAwal'] }}</td>
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
            ['Masuk', {{ $data['masuk'] }}],
            ['Sakit', {{ $data['sakit'] }}],
            ['Izin', {{ $data['izin'] }}],
            ['Telat', {{ $data['telat'] }}],
            ['Alpha', {{ $data['alpha'] }}],
            ['Tidak Checkout', {{ $data['tidakCheckout'] }}],
            ['Telat dan Tidak Checkout', {{ $data['Telat & Tidak Checkout'] }}],
            ['Pulang', {{ $data['pulang'] }}],
            ['Telat dan Pulang Lebih Awal', {{ $data['telatDanPulangLebihAwal'] }}]
        ]);

        var options = {
            'width': 550,
            'height': 400
        };
        // Optional; add a title and set the width and height of the chart

        // Display the chart inside the <div> element with id="piechart"
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

</html>
