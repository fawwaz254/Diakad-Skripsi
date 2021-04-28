<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        .page {
            width: 1200px;
        }
        
        .ttd {
            margin-top: 30px;
            text-align: right;
        }
        
        .clear {
            clear: both;
        }

        .avoid-break {
            page-break-inside: avoid;
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

        .mb-4 {
            margin-bottom: 40px;
        }

        td {
            font-size: 12px;
            padding-top: 0;
            padding-bottom: 0;
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
</head>

@php
    $data_tutup_buku_bulanan_biaya = $data_laporan['data_tutup_buku_bulanan_biaya'];
    $data_realisasi = $data_laporan['data_realisasi'];
    $bulan = $data_laporan['bulan'];
    $tahun = $data_laporan['tahun'];
    $sekolah = $data_laporan['sekolah'];
    $tutup_buku_tahun_ini = $data_laporan['tutup_buku_tahun_ini'];
    $tutup_buku_kas_bulan_ini = $data_laporan['tutup_buku_kas_bulan_ini'];
    $tutup_buku_kas_bulan_lalu = $data_laporan['tutup_buku_kas_bulan_lalu'];


    $data_realisasi_pemasukan = collect($data_realisasi->where('tipe_kategori_rapb', 1)->all());
    $data_realisasi_pengeluaran = collect($data_realisasi->where('tipe_kategori_rapb', 2)->all());
@endphp

<body>
    <table width="100%">
        <tr valign=top>
            <td><center><h3 style="margin: 4px;">LAPORAN BULANAN KAS SEKOLAH</h3></center></td>
        </tr>
    </table>
    <table width="100%">
        <tr valign=top>
            <td><h4 style="margin: 4px;" class="text-bold">Nama Sekolah: {{$sekolah->nm_sekolah}}</h4></td>
            <td><h4 style="margin: 4px; float:right;">Bulan: {{$bulan->nm_bulan}} {{$tahun}}</h4></td>
        </tr>
    </table>
    <table class="is-bordered" width="100%" border=1 cellpadding=5 cellspacing=0 style="background-color: #ffffff; word-wrap:break-word;">
        <tr valign=top>
            <td class="text-center text-bold" rowspan=2>Jml Siswa Perkelas</td>
            <td class="text-center text-bold" colspan=3>Pembayaran uang sekolah dalam bulan ini</td>
            <td class="text-center text-bold" colspan=2>Tunggakan bulan lalu</td>
            <td class="text-center text-bold" rowspan=2>Jumlah belum masuk Keseluruhan</td>
            <td class="text-center text-bold" rowspan=2>Jumlah uang masuk Keseluruhan</td>
        </tr>
        <tr valign=top>
            <td class="text-center text-bold">Bila Masuk 100 %</td>
            <td class="text-center text-bold">Yang Masuk</td>
            <td class="text-center text-bold">Belum Masuk</td>
            <td class="text-center text-bold">Masuk Bulan Ini</td>
            <td class="text-center text-bold">Belum Masuk</td>
        </tr>
        <tr valign=top>
            <td class="text-center text-bold">A</td>
            <td class="text-center text-bold">B</td>
            <td class="text-center text-bold">C</td>
            <td class="text-center text-bold">B - C</td>
            <td class="text-center text-bold">D</td>
            <td class="text-center text-bold">E</td>
            <td class="text-center text-bold">(B - C ) + E</td>
            <td class="text-center text-bold">C + D</td>
        </tr>
        @foreach($data_tutup_buku_bulanan_biaya as $tutup_buku_bulanan_biaya)
        <tr valign=top>
            <td>{{$tutup_buku_bulanan_biaya->tingkat}} = {{$tutup_buku_bulanan_biaya->jml_siswa}}</td>
            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_tagihan_biaya)}}</td>
            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_pembayaran_biaya)}}</td>

            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya)}}</td>

            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu)}}</td>
            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_tunggakan_biaya)}}</td>

            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_tagihan_biaya - $tutup_buku_bulanan_biaya->jml_pembayaran_biaya + $tutup_buku_bulanan_biaya->jml_tunggakan_biaya)}}</td>
            <td class="text-right">{{number_format($tutup_buku_bulanan_biaya->jml_pembayaran_biaya + $tutup_buku_bulanan_biaya->jml_pembayaran_biaya_bulan_lalu)}}</td>
        </tr>
        @endforeach
        <tr valign=top>
            <td>JUMLAH = {{number_format($data_tutup_buku_bulanan_biaya->sum('jml_siswa'))}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tagihan_biaya') )}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') )}}</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tagihan_biaya') - $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') )}}</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') )}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tunggakan_biaya') )}}</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tagihan_biaya') - $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + $data_tutup_buku_bulanan_biaya->sum('jml_tunggakan_biaya'))}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') )}}</td>
        </tr>
        <tr valign=top>
            <td class="no-bordered" colspan=3></td>

            <td>Tunggakan tahun lalu</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') )}}</td>
            <td class="text-right">{{number_format( $tutup_buku_tahun_ini->jml_tunggakan_biaya )}}</td>

            <td class="text-right">{{number_format( $tutup_buku_tahun_ini->jml_tunggakan_biaya )}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') )}}</td>
        </tr>
        <tr valign=top>
            <td class="no-bordered" colspan=3></td>

            <td class="test-bold">TOTAL</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') )}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tunggakan_biaya') + $tutup_buku_tahun_ini->jml_tunggakan_biaya )}}</td>

            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_tagihan_biaya') - $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + $data_tutup_buku_bulanan_biaya->sum('jml_tunggakan_biaya') + $tutup_buku_tahun_ini->jml_tunggakan_biaya )}}</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') )}}</td>
        </tr>
        <tr valign=top>
            <td class="no-bordered" colspan=5></td>
            <td class="text-bold" colspan=2>Jumlah Pemasukan</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') + $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') )}}</td>
        </tr>
        @foreach($data_realisasi_pemasukan as $realisasi)
        <tr valign=top>
            <td class="no-bordered" colspan=5></td>
            <td class="text-right">{{$realisasi->kode_subkategori_rapb}}</td>
            <td>{{$realisasi->nm_subkategori_rapb}}</td>
            <td class="text-right">{{number_format($realisasi->total_realisasi)}}</td>
        </tr>
        @endforeach
        <tr valign=top>
            <td class="no-bordered" colspan=5></td>
            <td class="text-bold" colspan=2>Saldo Kas Bulan Lalu</td>
            <td class="text-right">{{ number_format($tutup_buku_kas_bulan_lalu->kas_akhir_bulan) }}</td>
        </tr>
        <tr valign=top>
            <td class="no-bordered" colspan=5></td>
            <td class="text-bold" colspan=2>Kas tersedia dalam bulan ini</td>
            <td class="text-right">{{number_format( $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya') + 
                                    $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_bulan_lalu') + 
                                    $data_tutup_buku_bulanan_biaya->sum('jml_pembayaran_biaya_tahun_lalu') +
                                    $data_realisasi_pemasukan->sum('total_realisasi') + 
                                    $tutup_buku_kas_bulan_lalu->kas_akhir_bulan )}}</td>
        </tr>
    </table>
    <br>

    <table class="is-bordered" width="100%" border=1 cellpadding=5 cellspacing=0 style="background-color: #ffffff; word-wrap:break-word;">
        <tr valign=top>
            <td colspan=3>PENGELUARAN</td>
            <td>TARGET</td>
            <td>REALISASI</td>
            <td>PROSENTASE ( % )</td>
            <td></td>
        </tr>
        @php
            $no = 1;
        @endphp
        @foreach($data_realisasi_pengeluaran as $realisasi)
        <tr valign=top>
            <td>{{$no++}}.</td>
            <td>{{$realisasi->nm_kategori_rapb}} :</td>
            <td>{{$realisasi->kode_subkategori_rapb}} {{$realisasi->nm_subkategori_rapb}}</td>
            <td class="text-right">{{number_format($realisasi->dana_perkiraan_rapb)}}</td>
            <td class="text-right">{{number_format($realisasi->total_realisasi)}}</td>
            @if($realisasi->dana_perkiraan_rapb == 0)
            <td class="text-right">0%</td>
            @else
            <td class="text-right">{{round($realisasi->total_realisasi/$realisasi->dana_perkiraan_rapb * 100, 2)}}%</td>
            @endif
            <td></td>
        </tr>
        @endforeach
        <tr valign=top>
            <td colspan=3>JUMLAH</td>
            <td class="text-right">{{number_format($data_realisasi_pengeluaran->sum('dana_perkiraan_rapb'))}}</td>
            <td class="text-right">{{number_format($data_realisasi_pengeluaran->sum('total_realisasi'))}}</td>
            @if($data_realisasi_pengeluaran->sum('dana_perkiraan_rapb') == 0)
            <td class="text-right">0%</td>
            @else
            <td class="text-right">{{round($data_realisasi_pengeluaran->sum('total_realisasi')/$data_realisasi_pengeluaran->sum('dana_perkiraan_rapb') * 100, 2)}}%</td>
            @endif
            <td class="text-right">{{number_format($data_realisasi_pengeluaran->sum('total_realisasi'))}}</td>
        </tr>
        <tr valign=top>
            <td colspan=6>SALDO AKHIR BULAN</td>
            <td class="text-right">{{number_format( $tutup_buku_kas_bulan_ini->kas_akhir_bulan )}}</td>
        </tr>
    </table>
    <br>
    <br>

    <div class="avoid-break mt-4 mb-4">
            <table cellspacing="0" style="width: 80%; margin:auto; text-align:center">
                <tr>
                    <td style="width: 50%;">Mengetahui</td>
                    <td>{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d'))  }}
                    </td>
                </tr>
                <tr></tr>
                <tr style="vertical-align: top;">
                    <td>
                        Kepala Sekolah
                        <br><br><br><br>
                        <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                    <td>
                        Keuangan
                        <br><br><br><br> 
                        <b><u>{{ $auth_data->pengguna->nm_pengguna }}</u></b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
</body>
<script>
    window.print();
</script>

</html>