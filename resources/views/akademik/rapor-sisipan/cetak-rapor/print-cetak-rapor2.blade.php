<!DOCTYPE html>
<html lang="eng">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nilai Rapor Tengaah Semester</title>


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

                <tr style="border-style : hidden">
                    <td width="15%" align="center" style="margin-right: 10px" style="border-style : hidden">
                        <img id="logo"
                            src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                            height="150">
                    </td>
                    <td width="85%" style="border-style : hidden">
                        <span style="margin-top: -10px; font-family: 'Brush Script MT'; font-size:35px">
                            {{ $auth_data->sekolah_data->nm_yayasan_sekolah }}
                        </span>
                        <br>
                        <span style="margin-top: -10px; font-family: 'Impact'; font-size:50px">
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
                        </span>
                        <br>
                        <span style="margin-top: -10px; font-family: 'Impact'; font-size:25px">
                            {{ $auth_data->sekolah_data->akreditasi }}
                        </span>
                        <br>
                        <span style="margin-top: -10px; font-family: 'Tahoma'; font-size:15px">
                            {{ 'NSS : ' . $auth_data->sekolah_data->nss_sekolah . ',       ' }}
                        </span>
                        <span style="margin-top: -10px; font-family: 'Tahoma'; font-size:15px">
                            {{ 'NPSN : ' . $auth_data->sekolah_data->npsn_sekolah }}
                        </span>
                        {{-- <h3>PRESENSI KELAS <br>
                            {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }} <br> --}}
                        {{-- TAHUN AJARAN {{$semester_aktif->tahun_ajaran}}
                            </h3> --}}

                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr width="90%" style="background-color: black;color:white">
                    <td align="center" style="border-style : hidden">Alamat :
                        {{ $auth_data->sekolah_data->alamat_jalan .', ' .$auth_data->sekolah_data->alamat_kelurahan .', ' .substr($auth_data->sekolah_data->nomor_telp_sekolah, 0, 3) .' '.substr($auth_data->sekolah_data->nomor_telp_sekolah, 3, 7) .' - ' .substr($auth_data->sekolah_data->nomor_fax_sekolah, 3, 7) .' ' .$auth_data->sekolah_data->alamat_kecamatan .' - ' .App\Models\Kota::where('id_kota', $auth_data->sekolah_data->alamat_kota)->pluck('nm_kota')->first() .' ' .$auth_data->sekolah_data->alamat_kodepos .' ' .App\Models\Provinsi::where('id_provinsi', $auth_data->sekolah_data->alamat_provinsi)->pluck('nm_provinsi')->first() }}
                    </td>
                </tr>
            </table>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin: 0 auto;" style="border-style : hidden">
                <tr>
                    <td colspan="10" style="border-style : hidden">
                        <br>
                        <h2 align="center" style="margin-top: 3px; font-family:'Times New Roman', Times, serif; font-size:30px">
                            PENCAPAIAN KOMPETENSI PESERTA DIDIK<br>
                            {{-- {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}<br>
                            TENGAH SEMESTER GASAL<br>
                           
                        </h2>
                        {{-- <hr> --}}
                            
                    </td>
                <tr>

                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">Nama Sekolah
                    </td>
                    <td style="border-style : hidden;width: 35%;"> :
                        {{ $auth_data->sekolah_data->nm_sekolah }}

                    </td>
                    <td style="border-style : hidden;width: 25%;">Kelas
                    </td>
                    <td style="border-style : hidden;width: 25%; "> :
                        {{ $kelas->nm_kelas }}
                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">Nama Pst. Didik
                    </td>
                    <td style="border-style : hidden;width: 35%;"> :
                        {{ $siswa->pengguna->nm_pengguna }}
                    </td>
                    <td style="border-style : hidden;width: 25%;">Semester
                    </td>
                    <td style="border-style : hidden;width: 25%;"> : I


                    </td>
                </tr>
                <tr style="border-style : hidden">
                    <td style="border-style : hidden;width: 15%;">No. Induk/NISN
                    </td>
                    <td style="border-style : hidden;width: 35%;"> : {{ $siswa->nis_siswa }}
                    </td>
                    <td style="border-style : hidden;width: 25%;">Tahun Ajaran
                    </td>
                    <td style="border-style : hidden;width: 25%;"> :
                        @php
                            $nama = $list_nilai->first();
                            // dd($nama->rapor_sisipan->semester->tahun_ajaran);
                            echo $nama->rapor_sisipan->semester->tahun_ajaran ?? '';
                        @endphp
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
                                            @if(isset($nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran]) && $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] != '0' )
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran]}}
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    {{-- @if (round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] ?? (0 + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] ?? 0)) / 2) != 0) --}}
                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']))
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

                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']))
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
                                            @if(isset($nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran]) && $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] != "0")
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran]  }}
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']))
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
                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']))
                                        {{ round(($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) / 3) }}
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
                                            @if(isset( $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran]) &&  $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] != '0')
                                            {{ $nilai_siswa[$komponen->id_komponen_nilai . $siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran] }}
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td style="text-align: center;">
                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']))
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
                                    @if (isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2']) &&
                                        isset($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']))
                                        {{ round($nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi1'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'nilai_sumasi2'] + $nilai_komponen[$siswa->id_siswa . $m->mata_pelajaran->id_mata_pelajaran . 'uts']) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tr>

                </tbody>

            </table>
            <table style="width: 90%; margin-left:10%; margin-top:20px">
                <tr style="font-weight:bold;border-style : hidden;">
                    <td width="20%" style="border-style : hidden;">
                        KETIDAKHADIRAN
                    </td>
                    <td width="15%" style="border-style : hidden;"></td>
                    <td style="border-style : hidden;">CATATAN
                    </td>
                </tr>
                <tr style="border-style : hidden;">
                    <td style="border-style : hidden;">
                        Sakit
                        <br>
                        Izin
                        <br>
                        Tanpa Keterangan
                    </td>
                    <td>: ..... hari<br>
                        : ..... hari<br>
                        : ..... hari</td>
                    <td style="border-style : hidden;"><br>
                        <p style="width: 300px;
                    border-bottom: 2px dotted  black;"></p><br>
                        <p style="width: 300px;
                        border-bottom: 2px dotted  black;"></p>
                    </td>
                </tr>
            </table>
            <br><br>
            <table cellspacing="0" cellpadding="10" style="width: 90%; margin-left:10%; border-style : hidden">
                <tr>
                    <td style=" border-style : hidden; width:15%; vertical-align: text-top; padding:0">
                        <br>
                        Orang Tua/Wali,
                        <br><br><br><br><br><br><br>
                        <p style="width: 250px;
                    border-bottom: 1px solid   black;"></p>
                    </td>
                    <td style="width:40%; border-style : hidden"></td>

                    <td style="width:25%;border-style : hidden;">Sidoarjo,
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                        <br>

                        Wali Kelas
                        <br><br><br><br><br><br><br>
                        {{-- <p style="width: 250px;
                        border-bottom: 1px solid   black;"> --}}
                        <u><b>
                            {{ $wali_kelas->guru->pengguna->gelar_depan }}
                            {{ $wali_kelas->guru->pengguna->nm_pengguna }}
                            {{ $wali_kelas->guru->pengguna->gelar_belakang }}</b></u>
                    </td>

                </tr>
                <tr>
                    <td></td>
                    <td align="center" style="border-style : hidden; position: relative;">Mengetahui<br>Kepala Sekolah,
                        <img style="position: absolute; margin-left:-140px " src="{{ asset('media/ttd/smpypm1.png') }}"
                                alt="TTD" width="160px" height="160px" class="ttd">
                                <br><br><br><br><br>
                        {{-- <p style="width: 250px;
                        border-bottom: 1px solid   black;"> --}}
                        <u><b>
                            {{ $auth_data->sekolah_data->nm_kepala_sekolah }}</b></u>
                    </td>
                    <td></td>
                </tr>

            </table>

        </div>
    @endforeach
</body>
<script>
    window.print();
</script>

</html>
