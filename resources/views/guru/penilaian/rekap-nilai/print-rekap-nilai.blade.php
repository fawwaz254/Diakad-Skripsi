<head>
    <title>Rekap Nilai</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
            border-collapse: collapse;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }

        .presensi td{
            padding: 9px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
        }

        .small {
            font-size: x-small;
        }

        .vertical {
            text-align:center;
            white-space:nowrap;
            transform: rotate(90deg);
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

        /* .presensi tr td:last-child, .presensi tr th:last-child {
            border-right: 1px solid #000000;
        }

        .presensi tr:last-child td{
            border-bottom: 1px solid #000000;
        } */

        .presensi th{
            padding: 5px;
            border: 1px solid #000000;
            /* border-left: 1px solid #000000; */
        }

        .gray {
            background-color: lightgray
        }

        #logo {
            -webkit-filter: grayscale(100%);
            /* Safari 6.0 - 9.0 */
            filter: grayscale(100%);
        }

        .avoid-page-break {
            page-break-inside: avoid;
        }
        @page{
            margin: 3cm 1.5cm 3.2cm 1.5cm;
            size: landscape;
        }
    </style>
</head>
<body>
    <h4>Rekap Nilai : {{ $data_kelas->nm_mata_pelajaran .' - '. $data_kelas->nm_kelas .' - '. $data_kelas->tahun_ajaran . ' (' . $data_kelas->nm_semester . ')' }}</h4>
    <table class="presensi" id="primary_table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 10px;">No</th>
                <th rowspan="2" style="width: 40%;">NIS - Nama Siswa</th>
                @foreach($list_data as $komponen => $sub_komponen)
                    <th colspan="{{count($sub_komponen)}}" style="text-align:  center;">
                        {{$komponen}}
                    </th>
                @endforeach
                <th rowspan="2">Nilai Angka <br>(Rata-rata)</th>
                <th rowspan="2">Nilai Huruf</th>
            </tr>
            <tr>
                @foreach($list_data as $komponen => $sub_komponen)
                    @foreach($sub_komponen as $sub)
                        <th style="width: 10%;">
                            {{ $sub->kd_subkomponen_mp }} <br> {{ $sub->nm_subkomponen_mp }}
                        </th>
                    @endforeach
                @endforeach
                
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
                @foreach($list_data as $komponen)
                    @foreach($komponen as $nilai)
                        <td>
                            {{ collect($siswa->nilai_siswa_komponen)->where('id_subkomponen_mp', $nilai->id_subkomponen_mp)->first()['nilai_subkomponen_mp'] }}
                        </td>
                    @endforeach
                @endforeach
                <td>
                    {{isset($siswa->nilai_angka) ? $siswa->nilai_angka : 0}}
                </td>
                <td>{{$siswa->nilai_huruf}}</td>
        </form>
            </tr>
            @endforeach
        </tbody>
    </table> 
    
</body>
<script>
    window.print();
</script>