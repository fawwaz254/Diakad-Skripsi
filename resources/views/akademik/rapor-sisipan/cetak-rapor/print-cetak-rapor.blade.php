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
                    <td colspan="10" style="border-style : hidden">

                        <h2 align="center" style="margin-top: 3px">
                            LAPORAN PENILAIAN HASIL BELAJAR<br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                            TENGAH SEMESTER GASAL<br>
                            @php
                            $nama = $list_nilai->first();
                                // dd($nama->rapor_sisipan->semester->tahun_ajaran);
                            echo 'TAHUN AJARAN '.$nama->rapor_sisipan->semester->tahun_ajaran ;
                        @endphp

                        </h2>
                        <hr>
                        <br>
                    </td>
                <tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">NAMA SISWA
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> :
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">BIDANG KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold; font-size:11px"> :
                        {{ $kelas->jurusan->nm_jurusan }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">NO. INDUK
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> : {{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">PROGRAM KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;font-size:11px"> :
                        {{ $kelas->jurusan->nm_jurusan }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;font-weight: bold;">KELAS
                    </td>
                    <td style="border-style : hidden;width: 35%;font-weight: bold;"> : {{ $kelas->nm_kelas }}
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;">KOMPETENSI KEAHLIAN
                    </td>
                    <td style="border-style : hidden;width: 25%;font-weight: bold;font-size:11px"> :
                        {{ $kelas->jurusan->nm_jurusan }}
                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;">
                <thead class="head" style="background-color: #C2D69B">
                    <tr>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">NO</td>
                        <td rowspan="2" style="text-align: center;font-weight: bold;">MATA PELAJARAN<br></td>
                        <td colspan="2" style="text-align: center;font-weight: bold;">NILAI UTS</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;font-weight: bold;">KKM</td>
                        <td style="text-align: center;font-weight: bold;">NILAI</td>
                    </tr>
                </thead>
                <br>
                <tbody class="body">
                    {{-- @php
                        $no = 0;
                    @endphp --}}

                    <tr>
                        <td style="text-align: center;">A</td>
                        <td>
                            @php
                                $nama = $k->mapel->firstWhere('mata_pelajaran.jenis_mata_pelajaran.kode_jenis_mata_pelajaran', 'A');
                                echo $nama->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran;
                            @endphp
                        </td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    @php
                        $no = 1;
                    @endphp

                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'A')
                            <tr>
                                <td></td>
                                <td>{{ $no++ . '.    ' . $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $nama = $list_nilai
                                            ->where('siswa.id_siswa', $siswa->id_siswa)
                                            ->where('rapor_sisipan.id_mata_pelajaran', $m->mata_pelajaran->id_mata_pelajaran)
                                            ->first();
                                        echo $nama->nilai ?? 'Belum Diset';
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">B</td>
                        <td> @php
                            $nama = $k->mapel->firstWhere('mata_pelajaran.jenis_mata_pelajaran.kode_jenis_mata_pelajaran', 'B');
                            echo $nama->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran;
                        @endphp</td>
                        <td style="text-align: center;"></td>
                        <td style="text-align: center;">
                        </td>
                    </tr>
                    @php
                    $no = 1;
                @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'B')
                            <tr>
                                <td></td>
                                <td>{{ $no++ . '.    ' . $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $nama = $list_nilai
                                            ->where('siswa.id_siswa', $siswa->id_siswa)
                                            ->where('rapor_sisipan.id_mata_pelajaran', $m->mata_pelajaran->id_mata_pelajaran)
                                            ->first();
                                        echo $nama->nilai ?? 'Belum Diset';
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">C</td>
                        <td>
                            @php
                                $nama = $k->mapel->firstWhere('mata_pelajaran.jenis_mata_pelajaran.kode_jenis_mata_pelajaran', 'C');
                                echo $nama->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran ?? 'MUATAN PEMINATAN KEJURUAN';
                            @endphp
                        </td>
                        <td></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    @php
                    $no = 1;
                @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'C')
                            <tr>
                                <td></td>
                                <td>{{ $no++ . '.    ' . $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $nama = $list_nilai
                                            ->where('siswa.id_siswa', $siswa->id_siswa)
                                            ->where('rapor_sisipan.id_mata_pelajaran', $m->mata_pelajaran->id_mata_pelajaran)
                                            ->first();
                                        echo $nama->nilai ?? 'Belum Diset';
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                        <td><br></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">D</td>
                        <td> @php
                            $nama = $k->mapel->firstWhere('mata_pelajaran.jenis_mata_pelajaran.kode_jenis_mata_pelajaran', 'D');
                            echo $nama->mata_pelajaran->jenis_mata_pelajaran->nm_jenis_mata_pelajaran;
                        @endphp</td>
                        <td></td>
                        <td style="text-align: center;"></td>
                    </tr>
                    @php
                    $no = 1;
                @endphp
                    @foreach ($k->mapel as $m)
                        @if ($m->mata_pelajaran->jenis_mata_pelajaran->kode_jenis_mata_pelajaran == 'D')
                            <tr>
                                <td></td>
                                <td>{{ $no++ . '.    ' .  $m->mata_pelajaran->nm_mata_pelajaran }}</td>
                                <td style="text-align: center;">
                                    {{ $m->mata_pelajaran->nilai_kkm ?? 'Nilai KKM belum di Set' }}</td>
                                <td style="text-align: center;">
                                    @php
                                        $nama = $list_nilai
                                            ->where('siswa.id_siswa', $siswa->id_siswa)
                                            ->where('rapor_sisipan.id_mata_pelajaran', $m->mata_pelajaran->id_mata_pelajaran)
                                            ->first();
                                        // dd($nama);
                                        echo $nama->nilai ?? 'Belum Diset';
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endforeach




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

                    <td style="width:25%" align="center">Sidoarjo, {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>
                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        {{ $wali_kelas->guru->pengguna->gelar_depan }} {{ $wali_kelas->guru->pengguna->nm_pengguna }}  {{ $wali_kelas->guru->pengguna->gelar_belakang }}
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
