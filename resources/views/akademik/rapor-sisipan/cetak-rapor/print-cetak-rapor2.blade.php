<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor STS</title>


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

        td {
            /* font-size: 10px; */
            padding: 2px;
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

        @media print {
            .page {
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    @foreach ($list_siswa as $siswa)
        <div class="page">
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">

                <tr>
                    <td width="20%">
                        <img id="logo"
                            src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                            height="140">
                    </td>
                    <td width="80%">
                        <span align="center" style="margin-top: 1px; font-family: 'Brush Script MT'; font-size:30px">
                            {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                        </span>
                        {{-- <h3>PRESENSI KELAS <br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }} <br> --}}
                            {{-- TAHUN AJARAN {{$semester_aktif->tahun_ajaran}}
                            </h3> --}}
                           
                    </td>
                </tr>

                <tr>
                    <td colspan="10" style="border-style : hidden">

                        <h2 align="center" style="margin-top: 3px">
                            LAPORAN PENILAIAN HASIL BELAJAR<br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                            TENGAH SEMESTER GASAL<br>
                            @php
                                $nama = $list_nilai->first();
                                // dd($nama->rapor_sisipan->semester->tahun_ajaran);
                                echo isset($nama->rapor_sisipan->semester->tahun_ajaran) ? 'TAHUN AJARAN ' . $nama->rapor_sisipan->semester->tahun_ajaran : '';
                            @endphp

                        </h2>
                        <hr>
                        <br>
                    </td>
                <tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> :
                        {{ $auth_data->sekolah_data->nm_singkat_sekolah }}

                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; "> :
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">Nama Pst. Didik
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> :
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">Semester
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;"> :

                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">No. Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> : {{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">Tahun Ajaran
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;"> :

                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        {{-- <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td> --}}
                        <td colspan="2" rowspan="2" style="text-align: center;font-weight: bold;">MATA
                            PELAJARAN<br></td>
                        <td colspan="4" style="text-align: center;font-weight: bold;">NILAI FORMATIF</td>
                        <td colspan="4" style="text-align: center;font-weight: bold;">NILAI SUMATIF</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">RATA2 <br> SMT</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">STS</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">RAPOR <br> SISIPAN</td>


                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                        <td style="text-align: center;font-weight: bold;">3</td>
                        <td style="text-align: center;font-weight: bold;">4</td>
                        <td style="text-align: center;font-weight: bold;">1</td>
                        <td style="text-align: center;font-weight: bold;">2</td>
                        <td style="text-align: center;font-weight: bold;">3</td>
                        <td style="text-align: center;font-weight: bold;">4</td>
                    </tr>
                </thead>
                <br>
                <tbody class="body">
                    <tr>
                        <td colspan="13" style="background-color: #A6A6A6;  font-weight: bold;">
                            KELOMPOK A
                        </td>
                    </tr>

                    @php
                        $no = 1;
                    @endphp

                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'A')
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td>{{ $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                {{-- <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td> --}}
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type != 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    {{-- @if (round(
                                        ($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] ?? 0 +
                                            $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] ?? 0) /
                                            2) != 0) --}}
                                            @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) )
                                        {{ round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] ) / 2) }}
                                    @endif
                                </td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type == 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach
                                <td style="text-align: center;">

                                    @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) )
                                        {{ round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) / 3) }}
                                    @endif
                                    {{-- {{ if((round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] ?? 0 + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']  + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts'] ) / 3) > 0) {
                                      round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] ?? 0 + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']  + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts'] ) / 3) : ''}}} --}}
                                </td>

                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="13" style="background-color: #A6A6A6 ;  font-weight: bold;">
                            KELOMPOK B
                        </td>
                    </tr>

                    @php
                        $no = 1;
                    @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'B')
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td>{{ $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                {{-- <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td> --}}
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type != 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) )
                                    {{ round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) / 2) }}
                                    @endif
                                </td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type == 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach
                                <td style="text-align: center;">
                                    @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) )
                                    {{  round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) / 3) }}
                                  
                                    @endif
                                </td>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="13" style="background-color: #A6A6A6;  font-weight: bold;">
                            KELOMPOK C
                        </td>
                    </tr>

                    @php
                        $no = 1;
                    @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'C')
                            <tr>
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td>{{ $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type != 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) )
                                    {{ round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) / 2) }}
                                    @endif
                                </td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type == 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach
                                <td style="text-align: center;">
                                    @if(isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) && isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) )
                                    {{ round($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    {{-- @php
                        $no = 1;
                    @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'D')
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type != 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    {{ isset(round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) / 2)) ? round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) / 2) : '' }}
                                </td>
                                @foreach ($list_komponen as $komponen)
                                    @if ($komponen->type == 'uts')
                                        <td style="text-align: center;">
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] ?? '' }}
                                        </td>
                                    @endif
                                @endforeach
                                <td style="text-align: center;">
                                    {{ isset(round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) / 3)) ? round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) / 3) : '' }}
                                </td>
                        @endif
                    @endforeach --}}




                    {{--    <td style="text-align: center;">{{ $siswa->nis_siswa }}</td>
                        <td>{{ strtoupper($siswa->pengguna->nm_pengguna) }}</td>
                        <td style="text-align: center;">{{ $rapor_sisipan->kkm }}</td>
                        <td style="text-align: center;">{{ $nilai_siswa[ $siswa->id_siswa . $id_rapor_sisipan] }}</td> --}}
                    {{-- @foreach ($list_data as $nilai)
                        @if (in_array($nilai->urutan, [1, 2]))
                            <td style="text-align: center;">
                                
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach --}}
                    {{-- <td></td>
                    <td></td> --}}
                    {{-- @foreach ($list_data as $nilai)
                        @if (in_array($nilai->urutan, [5, 6]))
                            <td style="text-align: center;">
                               
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach --}}
                    {{-- <td></td>
                    <td></td> --}}
                    {{-- @foreach ($list_data as $nilai)
                        @if ($nilai->urutan == 9)
                            <td style="text-align: center;">
                           
                                {{ $nilai_siswa[$nilai->id_komponen_nilai . $siswa->id_siswa . $id_rapor_sisipan] }}
                            </td>
                        @endif
                    @endforeach
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2']) / 2) }}
                        </td>
                        <td style="text-align: center;">

                            {{ round(($nilai_komponen[$siswa->id_siswa . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . 'sts']) / 3) }}

                        </td> --}}
                    </tr>

                </tbody>

            </table>
            {{-- <table style="width: 30%; margin-left:10%; margin-top:20px">
             <tr>
                <td colspan="4">
                    <p align="center" style="display: inline">
                        RAPOR =
                    </p>
                    <p align="center" style="display: inline" class="under-below">
                        {(2 x RT2 SMT)+(STS)}
                    </p>
                </td>
            </tr> 
        </table> --}}
            <br><br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:25%; vertical-align: text-top; padding:0" align="center">
                        Mengetahui,
                        <br>
                        Kepala Sekolah
                        <br><br><br><br><br><br><br>
                        {{ $auth_data->sekolah_data->nm_kepala_sekolah }}
                    </td>
                    <td style="width:50%; border-style : hidden"></td>

                    <td style="width:25%" align="center">Sidoarjo,
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        {{ $wali_kelas->guru->pengguna->gelar_depan }} {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                        {{ $wali_kelas->guru->pengguna->gelar_belakang }}
                        {{-- {{ $rapor_sisipan->pengguna->gelar_depan }} {{ $rapor_sisipan->pengguna->nm_pengguna }} {{ $rapor_sisipan->pengguna->gelar_belakang }} --}}
                    </td>

                </tr>

            </table>

        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
