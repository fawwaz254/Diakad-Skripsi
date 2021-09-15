<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<style type="text/css">
  @media print{@page {size: landscape}}
  td{
    padding: 1px;
  }
</style>

<title>Laporan Pembayaran Kelas</title>
</head>
<body>

<div style="margin-top:40px;">

<center>
  <h4>Laporan Pembayaran Kelas {{$data_kelas->nm_kelas}} Tahun {{$tahun_akademik_semester}}</h4>
</center>
  
    <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align:middle;text-align: center">Nama</th>
                <th rowspan="2" style="vertical-align:middle;text-align: center">NIS</th>
                <th class="text-center" colspan="{{count($data_bulan_tagihan)}}">SPP</th>
                @if(count($data_ket_tagihan) > 0)
                <th class="text-center" colspan="{{count($data_ket_tagihan)}}">{{$data_ket_tagihan[0]->nm_biaya}}</th>
                @endif
            </tr>
            <tr>
                @foreach($data_bulan_tagihan as $bulan)
                @if(!empty($bulan->id_bulan))
                <th class="tdbg-{{$bulan->id_bulan}}">{{$bulan->nm_bulan}}</th>
                @else
                <th class="tdbg">{{$bulan->nm_biaya}}</th>
                @endif
                @endforeach
                @foreach($data_ket_tagihan as $ket)
                    <td class="tdbg-0" rowspan="2">{!! $ket->title_biaya !!}</td>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($data_siswa as $siswa)
            @if($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
            <tr>
            @else
            <tr style="background-color: #ffc109;">
            @endif
                @if($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                <td>{{$siswa->pengguna->nm_pengguna}}</td>
                @else
                <td>{{$siswa->pengguna->nm_pengguna}}<br>(Mutasi/Keluar)</td>
                @endif
                <td  style="vertical-align:middle;text-align: center;">{{$siswa->nis_siswa}}</td>
                @foreach($data_bulan_tagihan as $bulan)
                    @php
                        $tagihan = $data_tagihan->where('id_siswa', $siswa->id_siswa)->where('id_bulan', $bulan->id_bulan)->first();
                    @endphp
                    @if(!empty($tagihan) > 0)
                        @if($tagihan->is_tagih == 1)
                        @php
                            $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                        @endphp
                        <td  style="vertical-align:middle;text-align: center;">Rp{{number_format($tagihan_bulanan)}}</td>
                        @elseif($tagihan->is_tagih == 0)
                        <td class="tdbg-{{date_format(date_create($tagihan->tgl_pembayaran),'n')}}" style="vertical-align:middle;text-align: center;">Lunas</td>
                        @endif
                    @else
                    <td></td>
                    @endif
                @endforeach
                @foreach($data_ket_tagihan as $ket)
                    @php
                        $tagihan = $data_tagihan_non_bulanan->where('id_siswa', $siswa->id_siswa)->where('id_detail_biaya', $ket->id_detail_biaya)->first();
                    @endphp
                    @if(!empty($tagihan) > 0)
                        @if($tagihan->is_tagih == 1)
                        @php
                            $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                        @endphp
                        <td  style="vertical-align:middle;text-align: center;">Rp{{number_format($tagihan_bulanan)}}</td>
                        @elseif($tagihan->is_tagih == 0)
                        <td class="tdbg-{{date_format(date_create($tagihan->tgl_pembayaran),'n')}}" style="vertical-align:middle;text-align: center;">Lunas
                        </td>
                        @endif
                    @else
                    <td></td>
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
-->
</body>
</html>