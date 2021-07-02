<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style type="text/css">
table , td, th {
  border: 1px solid black;
  padding: 8px;
}

table {
  width: 100%;
  border-collapse: collapse;
}
.header, .header tr td{
	border: none;
}

.avoid-break {
    page-break-inside: avoid;
}
.mt-4 {
    margin-top: 40px;
}
.mb-4 {
    margin-bottom: 40px;
}
@media print {
  .break-after {page-break-after: always;}
}
</style>
@if($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
<style>
table.bg-color tr td{
	background-color: rgb(162, 219, 250, 0.6);
}
</style>
@else
<style>
table.bg-color tr td{
	background-color: rgb(102, 222, 147, 0.6);
}
</style>
@endif
<title>SKPI Siswa</title>
</head>
<body>

<table class="header" cellspacing="0" cellpadding="10" style="width: 100%;">
      <tr>
          <td colspan=1><img src="https://diakad.sgp1.digitaloceanspaces.com/{{$auth_data->sekolah_data->nm_singkat_sekolah}}/global/logo-sekolah" alt="Logo Sekolah" style="height:160px;" /></td>
          <td colspan=6><h1 align="center">{{ strtoupper($auth_data->sekolah_data->nm_yayasan_sekolah) }}<br>{{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}</h1>
          <h3 align="center">TERAKREDITASI  :  A ( UNGGUL )
          @if($auth_data->sekolah_data->nm_singkat_sekolah != 'smawh2')
          <br>NSS : 204050214055     NDS  :  2005020203          NPSN : {{ $auth_data->sekolah_data->npsn_sekolah }}
          @endif
          </h3></td>
      </tr>
      <tr>
          <td colspan=7><p align="center">Alamat :  {{ $auth_data->sekolah_data->alamat_jalan }} {{ $auth_data->sekolah_data->alamat_kelurahan }} Tlp. {{ $auth_data->sekolah_data->nomor_telp_sekolah }} – {{ $auth_data->sekolah_data->nomor_fax_sekolah }} Kecamatan {{ $auth_data->sekolah_data->alamat_kecamatan }}  Kabupaten {{ $auth_data->sekolah_data->kota->nm_kota }}, Kode Pos {{ $auth_data->sekolah_data->alamat_kodepos }} <br>E-mail : {{ $auth_data->sekolah_data->email_sekolah }} Website : {{ $auth_data->sekolah_data->website_sekolah }}</p></td>
      </tr>
  </table>
  <hr>
<h4 class="text-center" style="margin-top: 30px;text-decoration: underline;">SURAT KETERANGAN PENDAMPING IJAZAH</h4>
<h5 class="text-center">Nomor : 
@if($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
448/C-3/WH-2/VI/2021
@endif
</h5>
<hr>
<p class="text-center">Surat Keterangan Pendamping Ijazah yang dikeluarkan oleh {{ $auth_data->sekolah_data->nm_sekolah }} sebagai pelengkap ijazah yang menerangkan capaian pembelajaran dan prestasi dari pemegang ijazah selama masa studi</p>
<hr>
<div class="container" style="margin-top: 40px;">

<h5  style="margin-top: 30px;">I.	INFORMASI TENTANG IDENTITAS  DIRI PEMEGANG SKPI</h5>
<table class="bg-color">
	<tr>
    <td style="width: 5%;">1.A1</td>
		<td style="width: 30%;">Nama Lengkap </td>
		<td>: {{$siswa->nm_c_siswa}}</td>
	</tr>
	<tr>
    <td style="width: 5%;">1.A2</td>
		<td style="width: 30%;">Tempat, Tanggal Lahir </td>
		<td>: {{ $siswa->calon_siswa->kota_lahir? $siswa->calon_siswa->kota_lahir->nm_kota : ''}}, {{ indonesiaDate($siswa->calon_siswa->tgl_lahir)  }}</td>
	</tr>
	<tr>
    <td style="width: 5%;">1.A3</td>
		<td style="width: 30%;">No. Induk Sekolah dan Nasional </td>
		<td>: {{$siswa->nis_siswa}}</td>
	</tr>
	<tr>
    <td style="width: 5%;">1.A4</td>
		<td style="width: 30%;">Tahun Masuk </td>
		<td>: {{$siswa->thn_masuk_siswa}}</td>
	</tr>
	<tr>
    <td style="width: 5%;">1.A5</td>
		<td style="width: 30%;">Tahun Keluar </td>
		<td>: {{ date_format(date_create(), 'Y') }}</td>
	</tr>
	<tr>
    <td style="width: 5%;">1.A6</td>
		<td style="width: 30%;">Nomor Seri Ijazah </td>
		<td>: {{$siswa->pengajuan_wisuda ? $siswa->pengajuan_wisuda->nomor_ijasah : ''}}</td>
	</tr>
</table>

@php
  $no = 0;
@endphp
<h5  style="margin-top: 30px;">II.	INFORMASI TENTANG IDENTITAS PENYELENGGARA </h5>
<table class="bg-color break-after">
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Nama Satuan Pendidikan </td>
		<td>: {{ $auth_data->sekolah_data->nm_sekolah }}</td>
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
    <td style="width: 30%;">Surat  Izin Operasional Sekolah </td>
    <td>: {{ $auth_data->sekolah_data->nomor_sk_izin_operasional }}</td>
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Jenis dan Jenjang Pendidikan</td>
    @if($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
		<td>: Pendidikan Formal/Sekolah Menengah Atas</td>
    @else
		<td>: Pendidikan Formal/Sekolah Menengah Pertama</td>
    @endif
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Status Akreditasi</td>
		<td>: A (Unggul)</td>
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Nomor SK Akreditasi</td>
		<td>: 599/BAN-SM/SK/2019</td>
	</tr>
  @if($auth_data->sekolah_data->nm_singkat_sekolah != 'smawh2')
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Jenjang Kualifikasi Sesuai KKNI</td>
		<td>: Level 1</td>
	</tr>
  @endif
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Persyaratan Penerimaan </td>
    @if($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
		<td>: Lulus SMP/Mts dan Lulus Seleksi Penerimaan Peserta Didik Baru</td>
    @else
		<td>: Lulus SD dan Lulus Seleksi Penerimaan Peserta Didik Baru</td>
    @endif
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Bahasa Pengantar Sekolah </td>
		<td>: Bahasa Indonesia</td>
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Lama Studi Reguler </td>
		<td>: Tiga Tahun (3 Tahun)</td>
	</tr>
	<tr>
    <td style="width: 5%;">2.A{{$no++}}</td>
		<td style="width: 30%;">Jenis dan Jenjang Pendidikan Lanjutan</td>
    @if($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
		<td>: Perguruan Tinggi</td>
    @else
		<td>: SMA/Sederajat</td>
    @endif
	</tr>
</table>

<h5 style="margin-top: 30px;">III.	INFORMASI TENTANG KECAKAPAN DAN HASIL PEMBELAJARAN </h5>
<h6  style="margin-top: 15px;">A.	CAPAIAN PERMBELAJARAN</h6>
<table class="bg-color">
  <tr>
    <td style="width: 5%;">3.A1</td>
    <td>Bertaqwa kepada Tuhan Yang Maha Esa dan menjunjung tinggi sikap religius</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A2</td>
    <td>Mampu mengintegrasikan keilmuan dan etika yang berkarakter</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A3</td>
    <td>Memiliki kemampuan hidup mandiri, kerjasama, disiplin, tanggung jawab, dan tenggang rasa terhadap sesama</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A4</td>
    <td>Terampil dalam aktivitas rumah tangga dasar</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A5</td>
    <td>Terampil dalam aktivitas perawatan dasar</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A6</td>
    <td>Menguasai konsep komunikasi dan terampil dalam komunikasi personal</td>
  </tr>
  <tr>
    <td style="width: 5%;">3.A7</td>
    <td>Bekerjasama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungannya</td>
  </tr>
</table>

@if($prestasi->count()>0)
<h6  style="margin-top: 15px;">B.	CAPAIAN PRESTASI</h6>
<table class="bg-color">
  <thead>
    <tr>
        <td></td>
        <td>Nama Prestasi</td>
        <td>Tingkat Prestasi</td>
        <td>Jenis Prestasi</td>
        <td>Jenis Lomba</td>
        <td>Peringkat</td>
        <td>Lokasi</td>
        <td>Penyelenggara</td>
        <td>Tanggal</td>
    </tr>
  </thead>
  <tbody>
  	@foreach($prestasi as $r)
  		<tr>
  			<td>3.B{{$loop->iteration}}</td>
  			<td>{{$r->nm_prestasi_siswa}}</td>
  			<td>{{$r->nm_tingkat_prestasi_siswa}}</td>
  			<td>
  			@if($r->jenis_prestasi_siswa == 1)
  			Sains
            @elseif ($r->jenis_prestasi_siswa == 2)
            Seni
            @elseif ($r->jenis_prestasi_siswa == 3)
            Olahraga
            @else
            Lain lain
            @endif 
            </td>
            <td>{{$r->jenis_lomba_siswa}}</td>
            <td>{{$r->peringkat_prestasi_siswa}}</td>
            <td>{{$r->lokasi_prestasi_siswa}}</td>
            <td>{{$r->penyelenggara_prestasi_siswa}}</td>
            <td>{{ indonesiaDate($r->tgl_prestasi_siswa)  }}</td>
  		</tr>
  	@endforeach
  </tbody>
</table>
@endif

@if($kegiatan->count()>0)
<h6  style="margin-top: 15px;">C.	MACAM KEGIATAN</h6>
<table class="bg-color">
  <thead>
    <tr>
        <td></td>
        <td>Nama Kegiatan</td>
        <td>Lokasi</td>
        <td>Penyelenggara</td>
        <td>Tingkat Kegiatan</td>
        <td>Tanggal</td>
    </tr>
  </thead>
  <tbody>
  	@foreach($kegiatan as $r)
  		<tr>
  			<td>3.C{{$loop->iteration}}</td>
  			<td>{{$r->nm_kegiatan_siswa}}</td>
        <td>{{$r->lokasi_kegiatan_siswa}}</td>
        <td>{{$r->penyelenggara_kegiatan_siswa}}</td>
  			<td>{{$r->nm_tingkat_prestasi_siswa}}</td>
            <td>{{date("d F Y", strtotime($r->tgl_kegiatan_siswa))}}</td>
  		</tr>
  	@endforeach
  </tbody>
</table>
@endif

<div class="avoid-break mt-4 mb-4">
    <table cellspacing="0" style="width: 80%; border:none; margin:auto; text-align:right">
        <tr>
            <td style="border: none;" >{{ $auth_data->sekolah_data->alamat_kecamatan !== null ? $auth_data->sekolah_data->alamat_kecamatan . ', ' : null }}
                {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d'))  }}
            </td>
        </tr>
        <tr></tr>
        <tr style="vertical-align: top;">
            <td style="border: none;" >
                Kepala Sekolah
                <br><br><br><br>
                <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
            </td>
        </tr>
    </table>
</div>
</div>

<script>
        window.print();
</script>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

