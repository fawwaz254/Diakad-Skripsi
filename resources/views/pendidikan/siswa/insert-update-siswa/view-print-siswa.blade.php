<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style type="text/css">
	tr td:first-child {
	  width:5%;
	}
	tr td:nth-child(2) {
	 width: 30%;
	}	
</style>
<title>Hello, world!</title>
</head>
<body>

<div class="container">
<h4 class="text-center" style="margin-top: 40px;">LEMBAR DATA PRIBADI PESERTA DIDIK <br> TAHUN PELAJARAN : 2016/2017</h4>

<ol type="A" style="margin-top: 30px;">
	<li style="font-weight: 500;">KETERANGAN TENTANG DIRI PESERTA DIDIK</li>
	<table style="width: 100%;">
		<tr>
			<td>1.</td>
			<td>Nama lengkap Peserta Didik</td>
			<td>: {{$siswa->nm_c_siswa}}</td>
		</tr>
		<tr>
			<td>2.</td>
			<td>Nama panggilan</td>
			<td>: </td>
		</tr>
		<tr>
			<td>3.</td>
			<td>Jenis kelamin</td>
			@if($siswa->jenis_kelamin)
			<td>: {{$siswa->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan'}}</td>
			@else
			<td>:</td>
			@endif
		</tr>
		<tr>
			<td>4.</td>
			<td>NISN</td>
			<td>: {{$siswa->nisn_siswa}}</td>
		</tr>
		<tr>
			<td>5.</td>
			<td>NIK</td>
			<td>: {{$siswa->nik_siswa}}</td>
		</tr>
		<tr>
			<td>6.</td>
			<td>Tempat dan tanggal lahir</td>
			<td>: {{$siswa->nm_kota_lahir ? $siswa->nm_kota_lahir : '-'}}, {{$siswa->tgl_lahir ? date('d F Y', strtotime($siswa->tgl_lahir)) : '-'}}</td>
		</tr>
		<tr>
			<td>7.</td>
			<td>Agama</td>
			<td>: {{$siswa->nm_agama}}</td>
		</tr>
		<tr>
			<td>8.</td>
			<td>Kewarganegaraan</td>
			@if($siswa->kewarganegaraan)
			<td>: {{$siswa->kewarganegaraan == 1 ? 'WNI' : 'WNA'}}</td>
			@else
			<td>:</td>
			@endif
			
		</tr>
		<tr>
			<td>9.</td>
			<td>Anak ke</td>
			<td>: {{$siswa->anak_ke}}</td>
		</tr>
		<tr>
			<td>10.</td>
			<td>Jumlah saudara kandung</td>
			<td>: {{$siswa->dari_x_bersaudara}}</td>
		</tr>
		<tr>
			<td>11.</td>
			<td>Jumlah saudara tiri</td>
			<td>:</td>
		</tr>
		<tr>
			<td>12.</td>
			<td>Jumlah saudara angkat</td>
			<td>:</td>
		</tr>
		<tr>
			<td>13.</td>
			<td>Anak yatim/piatu/yatim-piatu</td>
			@if($siswa->status_ayah && $siswa->status_ibu)
			@if($siswa->status_ayah == 1 && $siswa->status_ibu ==2)
			<td>: piatu</td>
			@elseif($siswa->status_ayah == 2 && $siswa->status_ibu ==1)
			<td>: yatim</td>
			@elseif($siswa->status_ayah == 2 && $siswa->status_ibu ==2)
			<td>: yatim piatu</td>
			@else
			<td>: lengkap</td>
			@endif
			@else
			<td>:</td>
			@endif
		</tr>
		<tr>
			<td>14.</td>
			<td>Bahasa sehari-hari di rumah</td>
			<td>: {{$siswa->bahasa_sehari_hari}}</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN TEMPAT TINGGAL</li>
	<table style="width: 100%;">
		<tr>
			<td>15.</td>
			<td>Alamat</td>
			<td>: {{$siswa->alamat_jalan}}</td>
		</tr>
		<tr>
			<td>16.</td>
			<td>Nomor telepon / HP</td>
			<td>: Telp. {{ $siswa->nomor_telp_ortu ? $siswa->nomor_telp_ortu : '-' }} / Hp. {{ $siswa->nomor_hp_ortu ? $siswa->nomor_hp_ortu : '-' }}</td>
		</tr>
		<tr>
			<td>17.</td>
			<td>Email Pribadi</td>
			<td>: {{$siswa->email_ortu}}</td>
		</tr>
		<tr>
			<td>18.</td>
			<td>Jenis Tinggal</td>
			<td>: Bersama Orang Tua</td>
		</tr>
		<tr>
			<td>19.</td>
			<td>Jarak tempat tinggal ke sekolah</td>
			<td>: lebih dari 1 km</td>
		</tr>
		<tr>
			<td>20.</td>
			<td>Alat transportasi ke sekolah</td>
			<td>: Kendaraan pribadi</td>
		</tr>
		<tr>
			<td>21.</td>
			<td>Waktu tempuh ke sekolah</td>
			<td>: 30 - 60 menit</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN KESEHATAN</li>
	<table style="width: 100%;">
		<tr>
			<td>22.</td>
			<td>Golongan darah</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>23.</td>
			<td>Penyakit yang pernah diderita</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>24.</td>
			<td>Kelainan jasmani</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>25.</td>
			<td>Tinggi dan berat badan</td>
			<td>: 146 cm / 40 kg</td>
		</tr>
		<tr>
			<td>26.</td>
			<td>Berkebutuhan khusus</td>
			<td>: Tidak</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN PENDIDIKAN</li>
	<table style="width: 100%;">
		<tr>
			<td>27.</td>
			<td>Pendidikan sebelumnya</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Tamatan dari</td>
			<td>: SD DARUL ULUM</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Tanggal dan nomor ijazah</td>
			<td>: 25 Juni 2016 / DN-05 dds</td>
		</tr>
		<tr>
			<td></td>
			<td>c. Tanggal dan nomor SKHUN</td>
			<td>: 25 Juni 2016 / DN-05 dda</td>
		</tr>
		<tr>
			<td></td>
			<td>d. Lama belajar</td>
			<td>: 6 Tahun</td>
		</tr>
		<tr>
			<td>28.</td>
			<td>Pindahan</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Dari sekolah</td>
			<td>: -</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Alasan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>29.</td>
			<td>Diterima di sekolah ini</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Di kelas / Semester</td>
			<td>: VII AA-9 / 1 (Satu)</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Tanggal</td>
			<td>: 18 Juli 2016</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN KARTU PERLINDUNGAN SOSIAL</li>
	<table style="width: 100%;">
		<tr>
			<td>30.</td>
			<td>Nomor KPS</td>
			<td>: -</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG AYAH KANDUNG</li>
	<table style="width: 100%;">
		<tr>
			<td>31.</td>
			<td>Nama</td>
			<td>: Kasnadi</td>
		</tr>
		<tr>
			<td>32.</td>
			<td>Tahun Lahir</td>
			<td>: 1966</td>
		</tr>
		<tr>
			<td>33.</td>
			<td>Agama</td>
			<td>: Islam</td>
		</tr>
		<tr>
			<td>34.</td>
			<td>Kewarganegaraan</td>
			<td>: Indonesia</td>
		</tr>
		<tr>
			<td>35.</td>
			<td>Pekerjaan</td>
			<td>: PNS</td>
		</tr>
		<tr>
			<td>36.</td>
			<td>Pendidikan</td>
			<td>: SMP Sederajat</td>
		</tr>
		<tr>
			<td>37.</td>
			<td>Penghasilan per bulan</td>
			<td>: Lebih dari Rp 2.000.000</td>
		</tr>
		<tr>
			<td>38.</td>
			<td>Alamat rumah / nomor telepon</td>
			<td>: Jl.Langsep III / Telp. - / Hp. 0828282828</td>
		</tr>
		<tr>
			<td>39.</td>
			<td>Masih hidup / meninggal dunia</td>
			<td>: Masih hidup</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG IBU KANDUNG</li>
	<table style="width: 100%;">
		<tr>
			<td>40.</td>
			<td>Nama</td>
			<td>: Kasnadi</td>
		</tr>
		<tr>
			<td>41.</td>
			<td>Tahun Lahir</td>
			<td>: 1966</td>
		</tr>
		<tr>
			<td>42.</td>
			<td>Agama</td>
			<td>: Islam</td>
		</tr>
		<tr>
			<td>43.</td>
			<td>Kewarganegaraan</td>
			<td>: Indonesia</td>
		</tr>
		<tr>
			<td>44.</td>
			<td>Pekerjaan</td>
			<td>: PNS</td>
		</tr>
		<tr>
			<td>45.</td>
			<td>Pendidikan</td>
			<td>: SMP Sederajat</td>
		</tr>
		<tr>
			<td>46.</td>
			<td>Penghasilan per bulan</td>
			<td>: Lebih dari Rp 2.000.000</td>
		</tr>
		<tr>
			<td>47.</td>
			<td>Alamat rumah / nomor telepon</td>
			<td>: Jl.Langsep III / Telp. - / Hp. 0828282828</td>
		</tr>
		<tr>
			<td>48.</td>
			<td>Masih hidup / meninggal dunia</td>
			<td>: Masih hidup</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG AYAH WALI</li>
	<table style="width: 100%;">
		<tr>
			<td>49.</td>
			<td>Nama</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>50.</td>
			<td>Tahun Lahir</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>51.</td>
			<td>Agama</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>52.</td>
			<td>Kewarganegaraan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>53.</td>
			<td>Pekerjaan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>54.</td>
			<td>Pendidikan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>55.</td>
			<td>Penghasilan per bulan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>56.</td>
			<td>Alamat rumah / nomor telepon</td>
			<td>: -</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KEGEMARAN PESERTA DIDIK</li>
	<table style="width: 100%;">
		<tr>
			<td>57.</td>
			<td>Kesenian</td>
			<td>: Menggambar</td>
		</tr>
		<tr>
			<td>58.</td>
			<td>Olahraga</td>
			<td>: Sepak bola</td>
		</tr>
		<tr>
			<td>59.</td>
			<td>Kemasyarakatan / Organisasi</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>60.</td>
			<td>Lain-lain</td>
			<td>: -</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN PERKEMBANG PESERTA DIDIK</li>
	<table style="width: 100%;">
		<tr>
			<td>61.</td>
			<td>Menerima Bea-Siswa</td>
			<td>: Tahun -</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>: Tahun -</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>: Tahun -</td>
		</tr>
		<tr>
			<td>62.</td>
			<td>Meninggalkan sekolah</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Tgl meninggalkan sekolah</td>
			<td>: -</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Alasan</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>63.</td>
			<td>Akhir Pendidikan</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Tamat Belajar</td>
			<td>:</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Ijazah</td>
			<td>:</td>
		</tr>
		<tr>
			<td></td>
			<td>c. Nomor STTB</td>
			<td>:</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">KETERANGAN SETELAH SELESAI PENDIDIKAN</li>
	<table style="width: 100%;">
		<tr>
			<td>64.</td>
			<td>Akan melanjutkan ke-</td>
			<td>: -</td>
		</tr>
		<tr>
			<td>65.</td>
			<td>Bekerja</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>a. Tanggal mulai bekerja</td>
			<td>: -</td>
		</tr>
		<tr>
			<td></td>
			<td>b. Nama Perusahaan / Lembaga</td>
			<td>:</td>
		</tr>
		<tr>
			<td></td>
			<td>c. Penghasilan</td>
			<td>:</td>
		</tr>
	</table>

	<li style="font-weight: 500;margin-top: 30px;">LAIN-LAIN</li>

	<table style="width: 100%;">
		<tr>
			<td>66.</td>
			<td>Catatan yang penting</td>
			<td>: </td>
		</tr>
	</table>

</ol>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>