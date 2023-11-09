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
            /* font-size: 1px; */
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* th,
        td {
            padding: 15px;
        } */

        /* body {
            -webkit-print-color-adjust: exact !important;
        } */

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
            /* font-size: 12px; */
        }

        tr {
            font-size: 8px;
        }
    </style>

    <title>Laporan Pembayaran Kelas</title>
</head>

<body>

    <div style="margin-top:40px;">
        <center>
            <img class="logo"
                src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
                alt="Logo Sekolah" style="height:50px; width:45px" />
            <h2 style="margin-top:-2px">{{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h2>
            <h3 style="margin-top:-2px">Laporan Pembayaran Kelas {{ $data_kelas->nm_kelas }} Tahun
                {{ $tahun_akademik_semester }}</h3>
        </center>
        </td>

        {{--        
        <center>
            
        </center> --}}

        <table id="primary_table">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align:middle;text-align: center;background:gray;color:black">No
                    </th>
                    <th rowspan="2" style="vertical-align:middle;text-align: center;background:gray;color:black">NIS
                    </th>
                    <th rowspan="2" style="vertical-align:middle;text-align: center;background: gray;color:black">
                        Nama</th>
                    <th style="vertical-align:middle;text-align: center;background:gray;color:black"
                        colspan="{{ count($data_bulan_tagihan) }}">SPP
                    </th>
                    @if (count($data_ket_tagihan) > 0)
                        <th class="text-center" colspan="{{ count($data_ket_tagihan) }}">
                            Non-SPP</th>
                    @endif
                    <th rowspan="2" style="vertical-align:middle;text-align: center;color:black">
                        Total Tagihan</th>

                </tr>
                <tr>
                    @foreach ($data_bulan_tagihan as $bulan)
                        @if (!empty($bulan->id_bulan))
                            <th style="width:67px;" class="tdbg-{{ $bulan->id_bulan }}">{{ $bulan->nm_bulan }}</th>
                        @else
                            <th class="tdbg">{{ $bulan->nm_biaya }}</th>
                        @endif
                    @endforeach
                    @foreach ($data_ket_tagihan as $ket)
                        <td style="width:67px; text-align: center; " class="tdbg">{!! $ket->keterangan !!}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach ($data_siswa as $siswa)
                    @if ($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                        <tr>
                        @else
                        <tr style="background-color: #ffc109;">
                    @endif
                    <td style="vertical-align:middle;text-align: center;width:10px">{{ $loop->iteration }}</td>
                    <td style="vertical-align:middle;text-align: center;width:10px">{{ $siswa->nis_siswa }}</td>
                    @if ($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                        <td style="width: 200px">{{ $siswa->pengguna->nm_pengguna }}</td>
                    @else
                        <td style="width: 200px">{{ $siswa->pengguna->nm_pengguna }}<br>(Mutasi/Keluar)</td>
                    @endif
                    @foreach ($data_bulan_tagihan as $bulan)
                        @php
                            $tagihan = $data_tagihan
                                ->where('id_siswa', $siswa->id_siswa)
                                ->where('id_bulan', $bulan->id_bulan)
                                ->first();
                        @endphp
                        @if (!empty($tagihan) > 0)
                            @if ($tagihan->is_tagih == 1)
                                @php
                                    $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                @endphp
                                <td style="vertical-align:middle;text-align: center;">{{ $tagihan_bulanan }}</td>
                            @elseif($tagihan->is_tagih == 0)
                                <td class="tdbg-{{ date_format(date_create($tagihan->tgl_pelunasan), 'n') }}"
                                    style="vertical-align:middle;text-align: center;">
                                    <b
                                        style="color: black;">{{ date_format(date_create($tagihan->tgl_pelunasan), 'd/m') }}</b>
                                </td>
                            @endif
                        @else
                            <td></td>
                        @endif
                    @endforeach
                    @foreach ($data_ket_tagihan as $ket)
                        @php
                            $tagihan = $data_tagihan_non_bulanan
                                ->where('id_siswa', $siswa->id_siswa)
                                // ->where('id_detail_biaya', $ket->id_detail_biaya)
                                // ->where('title_biaya', $ket->title_biaya)
                                ->where('keterangan', $ket->keterangan)

                                ->first();
                        @endphp
                        @if (!empty($tagihan) > 0)
                            @if ($tagihan->is_tagih == 1)
                                @php
                                    $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                @endphp
                                <td style="vertical-align:middle;text-align: center;">{{ $tagihan_bulanan }}</td>
                            @elseif($tagihan->is_tagih == 0)
                                <td class="tdbg-{{ date_format(date_create($tagihan->tgl_pelunasan), 'n') }}"
                                    style="vertical-align:middle;text-align: center;">
                                    <b
                                        style="color: black;">{{ date_format(date_create($tagihan->tgl_pelunasan), 'd/m') }}</b>
                                </td>
                            @endif
                        @else
                            <td></td>
                        @endif
                    @endforeach
                    @php
                        $total_tagihan_spp = $data_tagihan->where('id_siswa', $siswa->id_siswa);

                        $total_tagihan = 0;
                        foreach ($total_tagihan_spp as $tagihan) {
                            $total_tagihan += $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                        }

                        $total_tagihan_non_spp = $data_tagihan_non_bulanan->where('id_siswa', $siswa->id_siswa);
                        foreach ($total_tagihan_non_spp as $tagihan) {
                            $total_tagihan += $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                        }

                    @endphp
                    <td style="width:67px;vertical-align:middle;text-align: center;">{{ $total_tagihan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding-right:20px">
            <p style="text-align: right">Wali Kelas</p>
            <br>
            <br>
            <br>
            @php
                $nm_wali_kelas = App\Models\WaliKelas::where('id_kelas', $id_kelas)
                    ->where('is_aktif', 1)
                    ->with('guru.pengguna')
                    ->first();
            @endphp
            <p style="text-align: right">
                {{ $nm_wali_kelas->guru->pengguna->gelar_depan }}{{ $nm_wali_kelas->guru->pengguna->nm_pengguna }}{{ $nm_wali_kelas->guru->pengguna->gelar_belakang }}
            </p>
        </div>
    </div>
</body>

</html>
