<head>
<title>
    REKAP NILAI EKSKUL {{$data_ekskul->nm_ekskul}} Semester {{$semester->tahun_ajaran}} {{$semester->nm_semester}}
</title>
    <style>
        table.is-bordered, table.is-bordered th, table.is-bordered td{
            border: 1px solid black;
            border-spacing: 0;
        }
        table.is-bordered th, table.is-bordered td{
            padding: 0.5em;
        }
        table.is-bordered th, .is-center{
            text-align:center; 
            vertical-align:middle !important;
        }
        table th.is-right, table td.is-right{
            text-align:right;
        }
    </style>
    <style type="text/css" media="print">
        @page { size: potrait; }
    </style>
</head>

<body>
    <h2>
        REKAP NILAI EKSKUL
    </h2>
    <table>
        <tbody>
            <tr>
                <th>Ekskul</th>
                <td style="width: 50%;">: {{$data_ekskul->nm_ekskul}}</td>
                <th class="is-right">Semester</th>
                <td class="is-right">: {{$semester->tahun_ajaran . ' - ' . $semester->nm_semester}}</td>
            </tr>
            <tr>
                <th>Pelatih</th>
                <td colspan="3">: {{ implode(', ', $data_pelatih) }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>

    <table class="is-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS - Nama Siswa</th>
                <th>Kelas</th>
                @foreach($list_komponen as $komponen)
                    <th style="text-align:  center;">
                        {{ $komponen->nm_komponen_ekskul }}
                    </th>
                @endforeach
                <th>Nilai Angka <br>(Rata-rata)</th>
                <th>Nilai Huruf</th>
            </tr>
        </thead>
        <tbody>
        @php
            $no = 0;
        @endphp
        @foreach($list_siswa as $siswa)
            <tr>
                <td>{{++$no}}</td>
                <td>{{$siswa->nis_siswa}} - {{$siswa->nm_pengguna}}</td>
                <td>{{ $siswa->nm_kelas }}</td>
                @foreach($list_komponen as $komponen)
                    <td>
                        {{ collect($siswa->nilai_ekskul)->where('id_komponen_ekskul', $komponen->id_komponen_ekskul)->first()['nilai_komponen_ekskul'] }}
                    </td>
                @endforeach
                <td>
                    {{ isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0 }}
                </td>
                <td>{{ $siswa->nilai_huruf }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
<script>
    window.print();
</script>