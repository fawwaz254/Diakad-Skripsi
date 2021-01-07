<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rapor</title>
    <!-- source https://gist.github.com/alfredoem/c7a6fbcba33c57948132 -->

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

        .mb-2 {
            margin-bottom: 20px;
        }

        /* .presensi tr td:last-child, .presensi tr th:last-child {
            border-right: 1px solid #000000;
        }

        .presensi tr:last-child td{
            border-bottom: 1px solid #000000;
        } */

        .presensi th{
            padding: 9px;
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
    </style>

</head>

<body>

    <table width="100%" style="margin-bottom: 30px;">
        <tr>
            <td width="60">
                <img id="logo"
                    src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah"
                    height="100">
            </td>
            <td width="400">
                <h3>RAPOR<br>
                    {{strtoupper($auth_data->sekolah_data->nm_sekolah)}}
                </h3>
                <hr>
            </td>
        </tr>
    </table>
    <h5>Data Siswa</h5>
    <table width="100%" style="margin-bottom: 30px;" class="">
        <tr>
            <td width="30%">Nama Peserta Didik</td>
            <td>: {{ $data_siswa->pengguna['nm_pengguna'] }}</td>
        </tr>
        <tr>
            <td>NISN/NIS</td>
            <td>: {{ $data_siswa['nisn_siswa'] ?? "-" }} / {{ $data_siswa['nis_siswa'] }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: {{ collect($data_detail_rapor)->first()->kelas['nm_kelas'] }}</td>
        </tr>
        <tr>
            <td>Semester</td>
            <td>: {{ collect($data_detail_rapor)->first()->nm_semester }}</td>
        </tr>
        <tr>
            <td>Tahun Pelajaran</td>
            <td>: {{ collect($data_detail_rapor)->first()->tahun_ajaran }}</td>
        </tr>
    </table>
    <hr>
    <h4 class="text-center mb-0">CAPAIAN HASIL BELAJAR</h4>
    @foreach($format_rapor_kategori as $kategori)
    <div class="mb-2">
        <h5>{{ $kategori->nm_rapor_kategori }}</h5>
        @if($kategori->kode_rapor_kategori == 'catatan_akademik')
        <table class="presensi" width="100%">
            <tr>
                <td style="padding: 10px;">
                    <p class="small">{{ collect($data_detail_rapor)->first()->deskripsi_catatan_wali_kelas ?? '-' }}</p>
                </td>
            </tr>
        </table>
        @elseif($kategori->kode_rapor_kategori == 'nilai_akademik')
        <table width="100%" class="presensi">
            <!-- start loop for format_rapor_kelompok -->
            <tr>
                <th width="10px">No.</th>
                <th>Mata Pelajaran</th>
                <th width="15%">Pengetahuan</th>
                <th width="15%">Keterampilan</th>
                <th width="15%">Nilai Akhir</th>
                <th width="15%">Predikat</th>
            </tr>
            @foreach($format_rapor_kelompok as $kel)
            <tr class="">
                <th colspan="6" class="text-left">{{ $kel->nm_rapor_kelompok }}</th>
            </tr>
                <!-- loop for each detail_rapor based on kelompok (Muatan nasional dll) -->
                @php
                    $no = 1;
                    $nm_rapor_kelompok_mp = null;
                @endphp
                @foreach(collect($data_detail_rapor)->where('id_rapor_kelompok', $kel->id_rapor_kelompok) as $key => $detail)
                @php
                $maxCol = count(collect($data_detail_rapor)->max('nilai_komponen'));
                @endphp
                    @if($detail['nm_rapor_kelompok_mp'] == null)
                    <tr class="presensi">
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail['nm_mata_pelajaran'] }}</td>
                        @php 
                        $i = 0;
                        @endphp
                        @foreach($detail['nilai_komponen'] as $key => $nilai_komponen)
                            <td class="text-center">{{ $nilai_komponen }}</td>
                            @php
                            $i++
                            @endphp
                        @endforeach
                        @while($i < $maxCol)
                            <td class="text-center">-</td>
                            @php
                            $i++
                            @endphp
                        @endwhile
                        <td class="text-center">{{ $detail['nilai_angka'] }}</td>
                        <td class="text-center">{{ $detail['nilai_huruf'] }}</td>
                    </tr>
                    @else
                        @if($nm_rapor_kelompok_mp != $detail['nm_rapor_kelompok_mp'] )
                        <tr>
                            <td colspan="6">{{ $detail['nm_rapor_kelompok_mp'] }}</td>
                        </tr>
                        @endif
                        <tr class="presensi">
                            <td>{{ $no++ }}</td>
                            <td>{{ $detail['nm_mata_pelajaran'] }}</td>
                            @php 
                            $i = 0;
                            @endphp
                            @foreach($detail['nilai_komponen'] as $key => $nilai_komponen)
                                <td class="text-center">{{ $nilai_komponen }}</td>
                                @php
                                $i++
                                @endphp
                            @endforeach
                            @while($i < $maxCol)
                                <td class="text-center">-</td>
                                @php
                                $i++
                                @endphp
                            @endwhile
                            <td class="text-center">{{ $detail['nilai_angka'] }}</td>
                            <td class="text-center">{{ $detail['nilai_huruf'] }}</td>
                        </tr>
                    @endif
                    @php
                        $nm_rapor_kelompok_mp = $detail['nm_rapor_kelompok_mp']
                    @endphp
                @endforeach
                @if(collect($data_detail_rapor)->where('id_rapor_kelompok', $kel->id_rapor_kelompok)->isEmpty())
                <tr class="presensi">
                    <td>-</td>
                    <td>-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                </tr>
                @endif
            @endforeach
            <!-- end loop for format_rapor_kelompok -->
        </table>
        @elseif($kategori->kode_rapor_kategori == 'pkl')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 20px;">No.</th>
                <th>Mitra DU/DI</th>
                <th>Lokasi</th>
                <th>Lamanya (bulan)</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
            @foreach($data_magang as $key => $magang)
            @php
                $start = date_create($magang['tgl_magang_mulai']);
                $end = date_create($magang['tgl_magang_selesai']);
                $diff = $start->diff($end);
            @endphp
            <tr>
                <td>{{ $key +1 }}</td>
                <td>{{ $magang['nm_rekanan_magang'] }}</td>
                <td>{{ $magang['alamat_rekanan_magang'] }}</td>
                <td>{{ $diff->format('%m Bulan') }}</td>
                <td>{{ $magang['nilai_angka'] }}</td>
                <td>-</td>
            </tr>
            @endforeach
            @if($data_magang->isEmpty())
            <tr>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @endif
        </table>
        @elseif($kategori->kode_rapor_kategori == 'ekstrakurikuler')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 20px;">No.</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th>Keterangan</th>
            </tr>
            @foreach($data_ekskul as $key => $ekskul)
            <tr>
                <td style="width: 20px;">{{ $key +1 }}</td>
                <td>{{ $ekskul['nm_ekskul'] }}</td>
                <td>{{ $ekskul['deskripsi_rapor'] }}</td>
            </tr>
            @endforeach
            @if($data_ekskul->isEmpty())
            <tr>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @endif
        </table>
        @elseif($kategori->kode_rapor_kategori == 'prestasi')
        <table width="100%" class="presensi">
            <tr>
                <th style="width: 30px;">No.</th>
                <th>Jenis Prestasi</th>
                <th>Keterangan</th>
            </tr>
            @foreach($data_prestasi as $key => $prestasi)
            <tr>
                <td style="width: 20px;">{{ $key +1 }}</td>
                <td>
                    @switch($prestasi['jenis_prestasi_siswa'])
                        @case(1)
                            Sains
                            @break
                        @case(2)
                            Seni
                            @break
                        @case(3)
                            Olahraga
                            @break
                        @case(4)
                            Lain-lain
                            @break
                    @endswitch
                </td>
                <td>{{ $prestasi['nm_prestasi_siswa'] }}</td>
            </tr>
            @endforeach
            @if($data_prestasi->isEmpty())
            <tr>
                <td>-</td>
                <td>-</td>
                <td>-</td>
            </tr>
            @endif
        </table>
        @elseif($kategori->kode_rapor_kategori == 'ketidakhadiran')
        <table class="presensi">
            <tr>
                <td>Izin</td>
                <td>: {{ $presensi['izin'] }} Hari</td>
            </tr>
            <tr>
                <td>Sakit</td>
                <td>: {{ $presensi['sakit'] }} Hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td>: {{ $presensi['tanpa_keterangan'] }} Hari</td>
            </tr>
        </table>
        @elseif($kategori->kode_rapor_kategori == 'catatan_wali_kelas')
        <table style="border: 1px solid #000;" width="100%">
            <tr>
                <td style="padding: 10px;">
                    <p class="small">{{ collect($data_detail_rapor)->first()->deskripsi_catatan_wali_kelas }}</p>
                </td>
            </tr>
        </table>
        @elseif($kategori->kode_rapor_kategori == 'tanggapan_orang_tua_wali')
        <table style="border: 1px solid #000;" width="100%">
            <tr>
                <td><br><br><br><br></td>
            </tr>
        </table>
        @endif
    </div>
    @endforeach
    @if(collect($data_detail_rapor)->first()->nm_semester == 'Genap')
    <div class="mb-2">
        <h5>Keputusan</h5>
        <p class="small">Berdasarkan hasil yang dicapai pada semester 1 dan 2, maka peserta didik ini ditetapkan :</p>
        <table>
            <tr>
                <td>Naik ke kelas</td>
                <td>:</td>
            </tr>
            <tr>
                <td>Tinggal di kelas</td>
                <td>:</td>
            </tr>
        </table>
    </div>
    @endif
    <div>
        <table width="100%" class="text-center">
            <tr>
                <td></td>
                <td>Mengetahui</td>
                <td>Taman, 23 Deesember 2020</td>
            </tr>
            <tr>
                <td>Orang Tua/Wali</td>
                <td>Kepala Sekolah</td>
                <td>Wali Kelas<td>
            </tr>
            <tr>
                <td><br><br><br></td>
            </tr>
            <tr>
                <td>__________________</td>
                <td>__________________</td>
                <td>__________________</td>
            </tr>
        </table>
    </div>
    
</body>

</html>