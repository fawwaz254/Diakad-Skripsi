<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style type="text/css">
        tr td:first-child {
            width: 5%;
        }

        tr td:nth-child(2) {
            width: 30%;
        }
    </style>
    <title>Biodata Siswa {{ $siswa->nis_siswa }}</title>
</head>

<body>

    <div class="container">
        <h4 class="text-center" style="margin-top: 40px;">LEMBAR DATA PRIBADI PESERTA DIDIK <br> TAHUN PELAJARAN :
            {{ $semester_aktif->tahun_ajaran }}</h4>

        <ol type="A" style="margin-top: 30px;">
            <li style="font-weight: 500;">KETERANGAN TENTANG DIRI PESERTA DIDIK</li>
            @if (!empty($siswa->path_foto_pengguna))
                <img src="{{ Storage::disk('spaces')->url($siswa->path_foto_pengguna) }}"
                    style="position: absolute; right: 10%; height: 270px; width: 180px">
            @endif
            <table style="width: 100%;">
                <tr>
                    <td>1.</td>
                    <td>Nama lengkap Peserta Didik</td>
                    <td>: {{ $siswa->nm_c_siswa }}</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Nama panggilan</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Jenis kelamin</td>
                    @if ($siswa->jenis_kelamin)
                        <td>: {{ $siswa->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
                    @else
                        <td>:</td>
                    @endif
                </tr>
                <tr>
                    <td>4.</td>
                    <td>NISN</td>
                    <td>: {{ $siswa->nisn_siswa }}</td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>NIK</td>
                    <td>: {{ $siswa->nik_siswa }}</td>
                </tr>
                <tr>
                    <td>6.</td>
                    <td>Tempat dan tanggal lahir</td>
                    <td>: {{ $siswa->nm_kota_lahir ? $siswa->nm_kota_lahir : '-' }},
                        {{ $siswa->tgl_lahir ? date('d F Y', strtotime($siswa->tgl_lahir)) : '-' }}</td>
                </tr>
                <tr>
                    <td>7.</td>
                    <td>Agama</td>
                    <td>: {{ $siswa->nm_agama }}</td>
                </tr>
                <tr>
                    <td>8.</td>
                    <td>Kewarganegaraan</td>
                    @if ($siswa->kewarganegaraan)
                        <td>: {{ $siswa->kewarganegaraan == 1 ? 'WNI' : 'WNA' }}</td>
                    @else
                        <td>:</td>
                    @endif

                </tr>
                <tr>
                    <td>9.</td>
                    <td>Anak ke</td>
                    <td>: {{ $siswa->anak_ke }}</td>
                </tr>
                <tr>
                    <td>10.</td>
                    <td>Jumlah saudara kandung</td>
                    <td>: {{ $siswa->dari_x_bersaudara }}</td>
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
                    @if ($siswa->status_ayah && $siswa->status_ibu)
                        @if ($siswa->status_ayah == 1 && $siswa->status_ibu == 2)
                            <td>: piatu</td>
                        @elseif($siswa->status_ayah == 2 && $siswa->status_ibu == 1)
                            <td>: yatim</td>
                        @elseif($siswa->status_ayah == 2 && $siswa->status_ibu == 2)
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
                    <td>: {{ $siswa->bahasa_sehari_hari }}</td>
                </tr>
            </table>
            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN TEMPAT TINGGAL</li>
            <table style="width: 100%;">
                <tr>
                    <td>15.</td>
                    <td>Alamat</td>
                    <td>: {{ $siswa->alamat_jalan }}</td>
                </tr>
                <tr>
                    <td>16.</td>
                    <td>Nomor telepon / HP</td>
                    <td>: Telp. {{ $siswa->nomor_telp_ortu ? $siswa->nomor_telp_ortu : '-' }} / Hp.
                        {{ $siswa->nomor_hp_ortu ? $siswa->nomor_hp_ortu : '-' }}</td>
                </tr>
                <tr>
                    <td>17.</td>
                    <td>Email Pribadi</td>
                    <td>: {{ $siswa->email_pengguna }}</td>
                </tr>
                <tr>
                    <td>18.</td>
                    <td>Jenis Tinggal</td>
                    <td>: {{ $siswa->nm_jenis_tinggal }}</td>
                </tr>
                <tr>
                    <td>19.</td>
                    <td>Jarak tempat tinggal ke sekolah</td>
                    <td>: {{ $siswa->jarak_rumah_sekolah }} km</td>
                </tr>
                <tr>
                    <td>20.</td>
                    <td>Alat transportasi ke sekolah</td>
                    <td>: {{ $siswa->nm_jenis_transportasi }}</td>
                </tr>
                <tr>
                    <td>21.</td>
                    <td>Waktu tempuh ke sekolah</td>
                    <td>: {{ $siswa->waktu_tempuh_sekolah_jam * 60 + $siswa->waktu_tempuh_sekolah_menit }} menit</td>
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN KESEHATAN</li>
            <table style="width: 100%;">
                <tr>
                    <td>22.</td>
                    <td>Golongan darah</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>23.</td>
                    <td>Penyakit yang pernah diderita</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>24.</td>
                    <td>Kelainan jasmani</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>25.</td>
                    <td>Tinggi dan berat badan</td>
                    <td>: {{ $siswa->tinggi_badan }} cm / {{ $siswa->berat_badan }} kg</td>
                </tr>
                <tr>
                    <td>26.</td>
                    <td>Berkebutuhan khusus</td>
                    <td>: {{ $siswa->nm_kebutuhan_khusus }}</td>
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
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>b. Tanggal dan nomor ijazah</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>c. Tanggal dan nomor SKHUN</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>d. Lama belajar</td>
                    <td>:</td>
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
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>b. Tanggal</td>
                    <td>: {{ $siswa->tgl_diterima }}</td>
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN KARTU PERLINDUNGAN SOSIAL</li>
            <table style="width: 100%;">
                <tr>
                    <td>30.</td>
                    <td>Nomor KPS</td>
                    <td>: {{ $siswa->nomor_kps }}</td>
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG AYAH KANDUNG</li>
            <table style="width: 100%;">
                <tr>
                    <td>31.</td>
                    <td>Nama</td>
                    <td>: {{ $siswa->nm_ayah }}</td>
                </tr>
                <tr>
                    <td>32.</td>
                    <td>Tahun Lahir</td>
                    <td>: {{ date('Y', strtotime($siswa->tgl_lahir_ayah)) }}</td>
                </tr>
                <tr>
                    <td>33.</td>
                    <td>Agama</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td>34.</td>
                    <td>Kewarganegaraan</td>
                    <td>: </td>
                </tr>
                <tr>
                    <td>35.</td>
                    <td>Pekerjaan</td>
                    <td>: {{ $siswa->nm_jenis_pekerjaan_ayah }}</td>
                </tr>
                <tr>
                    <td>36.</td>
                    <td>Pendidikan</td>
                    <td>: {{ $siswa->nm_jenis_pendidikan_ayah }}</td>
                </tr>
                <tr>
                    <td>37.</td>
                    <td>Penghasilan per bulan</td>
                    <td>: {{ $siswa->nm_jenis_penghasilan_ayah }}</td>
                </tr>
                <tr>
                    <td>38.</td>
                    <td>Alamat rumah / nomor telepon</td>
                    <td>: {{ $siswa->alamat_jalan_ayah }}
                        {{ $siswa->almat_rt_ayah ? 'RT ' . $siswa->almat_rt_ayah : '' }}
                        {{ $siswa->alamat_rw_ayah ? 'RW ' . $siswa->alamat_rw_ayah : '' }}
                        {{ $siswa->alamat_kelurahan_ayah ? $siswa->alamat_kelurahan_ayah : '' }}
                        {{ $siswa->alamat_kodepos_ayah ? $siswa->alamat_kodepos_ayah : '' }}
                        {{ $siswa->alamat_kecamatan_ayah ? $siswa->alamat_kecamatan_ayah : '' }}
                        {{ $siswa->nm_kota_ayah ? $siswa->nm_kota_ayah : '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Telp. - / Hp. -</td>
                </tr>
                <tr>
                    <td>39.</td>
                    <td>Masih hidup / meninggal dunia</td>
                    @if ($siswa->status_ayah)
                        <td>: {{ $siswa->status_ayah == 1 ? 'Masih hidup' : 'Meninggal dunia' }}</td>
                    @else
                        <td>:</td>
                    @endif
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG IBU KANDUNG</li>
            <table style="width: 100%;">
                <tr>
                    <td>40.</td>
                    <td>Nama</td>
                    <td>: {{ $siswa->nm_ibu }}</td>
                </tr>
                <tr>
                    <td>41.</td>
                    <td>Tahun Lahir</td>
                    <td>: {{ date('Y', strtotime($siswa->tgl_lahir_ibu)) }}</td>
                </tr>
                <tr>
                    <td>42.</td>
                    <td>Agama</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>43.</td>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>44.</td>
                    <td>Pekerjaan</td>
                    <td>: {{ $siswa->nm_jenis_pekerjaan_ibu }}</td>
                </tr>
                <tr>
                    <td>45.</td>
                    <td>Pendidikan</td>
                    <td>: {{ $siswa->nm_jenis_pendidikan_ibu }}</td>
                </tr>
                <tr>
                    <td>46.</td>
                    <td>Penghasilan per bulan</td>
                    <td>: {{ $siswa->nm_jenis_penghasilan_ibu }}</td>
                </tr>
                <tr>
                    <td>47.</td>
                    <td>Alamat rumah / nomor telepon</td>
                    <td>: {{ $siswa->alamat_jalan_ibu }} {{ $siswa->almat_rt_ibu ? 'RT ' . $siswa->almat_rt_ibu : '' }}
                        {{ $siswa->alamat_rw_ibu ? 'RW ' . $siswa->alamat_rw_ibu : '' }}
                        {{ $siswa->alamat_kelurahan_ibu ? $siswa->alamat_kelurahan_ibu : '' }}
                        {{ $siswa->alamat_kodepos_ibu ? $siswa->alamat_kodepos_ibu : '' }}
                        {{ $siswa->alamat_kecamatan_ibu ? $siswa->alamat_kecamatan_ibu : '' }}
                        {{ $siswa->nm_kota_ibu ? $siswa->nm_kota_ibu : '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Telp. - / Hp. -</td>
                </tr>
                <tr>
                    <td>48.</td>
                    <td>Masih hidup / meninggal dunia</td>
                    @if ($siswa->status_ibu)
                        <td>: {{ $siswa->status_ibu == 1 ? 'Masih hidup' : 'Meninggal dunia' }}</td>
                    @else
                        <td>:</td>
                    @endif
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN TENTANG AYAH WALI</li>
            <table style="width: 100%;">
                <tr>
                    <td>49.</td>
                    <td>Nama</td>
                    <td>: {{ $siswa->nm_wali }}</td>
                </tr>
                <tr>
                    <td>50.</td>
                    <td>Tahun Lahir</td>
                    <td>: {{ date('Y', strtotime($siswa->tgl_lahir_wali)) }}</td>
                </tr>
                <tr>
                    <td>51.</td>
                    <td>Agama</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>52.</td>
                    <td>Kewarganegaraan</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>53.</td>
                    <td>Pekerjaan</td>
                    <td>: {{ $siswa->nm_jenis_pekerjaan_wali }}</td>
                </tr>
                <tr>
                    <td>54.</td>
                    <td>Pendidikan</td>
                    <td>: {{ $siswa->nm_jenis_pendidikan_wali }}</td>
                </tr>
                <tr>
                    <td>55.</td>
                    <td>Penghasilan per bulan</td>
                    <td>: {{ $siswa->nm_jenis_penghasilan_wali }}</td>
                </tr>
                <tr>
                    <td>56.</td>
                    <td>Alamat rumah / nomor telepon</td>
                    <td>:</td>
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KEGEMARAN PESERTA DIDIK</li>
            <table style="width: 100%;">
                <tr>
                    <td>57.</td>
                    <td>Kesenian</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>58.</td>
                    <td>Olahraga</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>59.</td>
                    <td>Kemasyarakatan / Organisasi</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td>60.</td>
                    <td>Lain-lain</td>
                    <td>:</td>
                </tr>
            </table>

            <li style="font-weight: 500;margin-top: 30px;">KETERANGAN PERKEMBANG PESERTA DIDIK</li>
            <table style="width: 100%;">
                @foreach ($data_beasiswa as $r)
                    <tr>
                        <td>{{ $r['urutan_1'] }}</td>
                        <td>{{ $r['urutan_2'] }}</td>
                        <td>{{ $r['urutan_3'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>62.</td>
                    <td>Meninggalkan sekolah</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>a. Tgl meninggalkan sekolah</td>
                    <td>:</td>
                </tr>
                <tr>
                    <td></td>
                    <td>b. Alasan</td>
                    <td>:</td>
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
                    <td>:</td>
                </tr>
                <tr>
                    <td>65.</td>
                    <td>Bekerja</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>a. Tanggal mulai bekerja</td>
                    <td>:</td>
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script>
        window.print();
    </script>
</body>

</html>
