<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <style type="text/css">
        table,
        td {
            border: 1px solid black;
            text-align: left;
            font-size: 10px;

        }

        th {
            border: 1px solid black;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        @media print {
            @page {
                margin-left: 0.5in;
                margin-right: 0.5in;
                margin-top: 0;
                margin-bottom: 0;
            }
        }

        .tdbg-1 {
            background: #efee9d;
        }

        .tdbg-2 {
            background: #d1eaa3;
        }

        .tdbg-3 {
            background: #dbc6eb;
        }

        .tdbg-4 {
            background: #abc2e8;
        }

        .tdbg-5 {
            background: #ddf3f5;
        }

        .tdbg-6 {
            background: #f2aaaa;
        }

        .tdbg-7 {
            background: #f6def6;
        }

        .tdbg-8 {
            background: #f4ebc1;
        }

        .tdbg-9 {
            background: #a6dcef;
        }

        .tdbg-10 {
            background: #f2aaaa;
        }

        .tdbg-11 {
            background: #ddf3f5;
        }

        .tdbg-12 {
            background: #a0c1b8;
        }

        td {
            padding: 2px;
        }

        tr {
            font-size: 8px;
        }
    </style>

    <title>Laporan Absensi Guru dan Pegawai</title>
</head>

<body>

    <div style="margin-top:40px;">
        <center>
            <img class="logo"
                src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                alt="Logo Sekolah" style="height:50px; width:45px" />
            <h2 style="margin-top:-2px">{{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h2>
            {{-- @dd($bulan) --}}
            <h3 style="margin-top:-2px">Laporan Absensi Guru dan Pegawai Bulan
                {{ $data_bulan }} Tahun
                {{ $data_tahun }}</h3>
        </center>
        </td>

        <div class="body">
            <div class="table-responsive">
                <table id="primary_table">
                    <thead style="background:#4e4e4e;color:white">
                        <tr>
                            <th rowspan="2"
                                style="vertical-align:middle;text-align: center;background:gray;color:black">No
                            </th>
                            <th rowspan="2"
                                style="vertical-align:middle;text-align: center;background:gray;color:black">Nama
                            </th>
                            <th colspan="{{ $dates->count() }}" style="text-align: center;">Tanggal</th>

                        </tr>
                        <tr>
                            @foreach ($dates as $date)
                                <th>
                                    {{ substr(\Carbon\Carbon::create($date)->isoFormat('dddd'), 0, 3) }}
                                    <br>
                                    {{ $date->format('d') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($pengguna as $p)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ isset($hasil[$p->id_pengguna]['nm_pengguna']) ? $hasil[$p->id_pengguna]['nm_pengguna'] : '' }}
                                    
                                </td>
                                @foreach ($dates as $date)
                                    @php
                                        $status = '';
                                        if (isset($hasil[$p->id_pengguna][$date->format('Y-m-d')])) {
                                            $status = $hasil[$p->id_pengguna][$date->format('Y-m-d')];
                                        }
                                    @endphp
                                    <td
                                        @if ($status == 'M') style="text-align: center;background: #b5ffe0" @elseif($status == 'A') style="text-align: center;background: #ff9494" @else style="text-align: center;" @endif>
                                        {{ $status }}
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        window.print();
    </script>

</body>

</html>
