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
                    <br>
                    Unit Kerja :
                    {{ $nm_unit_kerja }}
                </h2>
            </td>
        <tr>
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
            ['Tepat Waktu', {{ $jumlah_hadir }}],
            ['Sakit', {{ $jumlah_sakit }}],
            ['Izin', {{ $jumlah_izin }}],
            ['Telat', {{ $jumlah_telat }}],
            ['Alpha', {{ $jumlah_alpha }}],
            ['Tidak Checkout', {{ $tidak_checkout }}],
            ['Pulang Lebih Cepat', {{ $jumlah_pulangcepat }}]
        ]);

        var options = {
            'width': 550,
            'height': 400,
            colors: ['#0da300', '#afb607', '#b66907', '#b63b07', '#b60707', '#00b0bf', '#0050bf'],
            is3D: true
        };
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }

    ready(function() {
        window.print();
    });
</script>

</html>
