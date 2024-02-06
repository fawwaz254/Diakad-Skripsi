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

        @media print {
            .pagebreak {
                page-break-before: always;
            }
        }
    </style>
    {{-- <title>Biodata Siswa Kelas</title> --}}
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="14">KETERANGAN TENTANG DIRI
                    PESERTA DIDIK</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="4">KETERANGAN TEMPAT TINGGAL
                </th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="5">KETERANGAN KESEHATAN</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="7">KETERANGAN PENDIDIKAN</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" colspan="1">KETERANGAN KARTU
                    PERLINDUNGAN SOSIAL</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="7">KETERANGAN TENTANG AYAH
                    KANDUNG</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="7">KETERANGAN TENTANG IBU
                    KANDUNG</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="5">KETERANGAN TENTANG AYAH WALI
                </th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="3">KEGEMARAN PESERTA DIDIK</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="6">KETERANGAN PERKEMBANG
                    PESERTA DIDIK</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="4">KETERANGAN SETELAH SELESAI
                    PENDIDIKAN</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="1">LAIN-LAIN</th>
            </tr>
            <tr>
                <td style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nama lengkap Peserta Didik
                </td>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nama panggilan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jenis kelamin</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">NIS</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">NISN</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">NIK</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Tempat dan tanggal lahir
                </th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Agama</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kewarganegaraan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Anak ke</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jumlah saudara kandung</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jumlah saudara tiri</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jumlah saudara angkat</th> --}}
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Anak yatim/piatu/yatim-piatu
                </th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Bahasa sehari-hari di rumah --}}
                {{-- </th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Alamat</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nomor telepon / HP</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Email Pribadi</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jenis Tinggal</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Jarak tempat tinggal ke
                    sekolah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Alat transportasi ke sekolah
                </th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Waktu tempuh ke sekolah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Golongan darah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Penyakit yang pernah
                    diderita</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kelainan jasmani</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Tinggi dan berat badan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Berkebutuhan khusus</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="3">Pendidikan sebelumnya</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="2">Pindahan</th>
                <th style="text-align: center;font-weight: bold;border : 1;">Diterima di sekolah ini</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Menerima Beasiswa</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nomor KPS</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nama</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Tahun Lahir</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Agama</th> --}}
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kewarganegaraan</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pekerjaan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pendidikan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Penghasilan per bulan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Alamat rumah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Masih hidup / meninggal
                    dunia</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nama</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Tahun Lahir</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Agama</th> --}}
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kewarganegaraan</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pekerjaan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pendidikan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Penghasilan per bulan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Alamat rumah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Masih hidup / meninggal
                    dunia</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Nama</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Tahun Lahir</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Agama</th> --}}
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kewarganegaraan</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pekerjaan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Pendidikan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Penghasilan per bulan</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Alamat rumah / nomor
                    telepon</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kesenian</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Olahraga</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Kemasyarakatan /
                    Organisasi</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Lain-lain</th> --}}
                {{-- <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Menerima Beasiswa</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="2">Meninggalkan sekolah</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="3">Akhir Pendidikan</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Akan melanjutkan ke-</th>
                <th style="text-align: center;font-weight: bold;border : 1;" colspan="3">Bekerja</th>
                <th style="text-align: center;font-weight: bold;border : 1;" rowspan="2">Catatan yang penting</th>
            </tr>
            <tr>
                <th style="text-align: center;font-weight: bold;border : 1;">a. Tamatan dari</th>
                <th style="text-align: center;font-weight: bold;border : 1;">b. Tanggal dan nomor ijazah</th>
                <th style="text-align: center;font-weight: bold;border : 1;">c. Tanggal dan nomor SKHUN</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;">d. Lama belajar</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;">a. Dari sekolah</th>
                <th style="text-align: center;font-weight: bold;border : 1;">b. Alasan</th>
                <th style="text-align: center;font-weight: bold;border : 1;">a. Di kelas / Semester</th>
                {{-- <th style="text-align: center;font-weight: bold;border : 1;">b. Tanggal</th> --}}
                <th style="text-align: center;font-weight: bold;border : 1;">a. Tgl meninggalkan sekolah</th>
                <th style="text-align: center;font-weight: bold;border : 1;">b. Alasan</th>
                <th style="text-align: center;font-weight: bold;border : 1;">a. Tamat Belajar</th>
                <th style="text-align: center;font-weight: bold;border : 1;">b. Ijazah</th>
                <th style="text-align: center;font-weight: bold;border : 1;">c. Nomor STTB</th>
                <th style="text-align: center;font-weight: bold;border : 1;">a. Tanggal mulai bekerja</th>
                <th style="text-align: center;font-weight: bold;border : 1;">b. Nama Perusahaan / Lembaga</th>
                <th style="text-align: center;font-weight: bold;border : 1;">c. Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa1 as $dsiswa)
                @php
                    $auth_data = auth_data();
                    $semester_aktif = App\Libraries\Pendidikan\LibDataAkademik::fetchDataSemesterAktif($auth_data);
                    $siswa = App\Libraries\Pendidikan\LibSiswa::fetchDataDetailSiswa($auth_data, $dsiswa->nis_siswa);
                    $beasiswa = App\Models\CalonSiswaBeasiswa::where('id_c_siswa', $siswa->id_c_siswa)->get();
                    $semester = App\Models\Semester::where('is_aktif_semester', '=', 1)->first();
                    $data_beasiswa[0]['urutan_1'] = '61.';
                    $data_beasiswa[0]['urutan_2'] = 'Menerima Beasiswa';
                    $data_beasiswa[0]['urutan_3'] = '';

                    if ($beasiswa) {
                        foreach ($beasiswa as $key => $value) {
                            if ($key == 0) {
                                $data_beasiswa[$key]['urutan_1'] = '61.';
                                $data_beasiswa[$key]['urutan_2'] = 'Menerima Beasiswa';
                                $data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
                            } else {
                                $data_beasiswa[$key]['urutan_1'] = '';
                                $data_beasiswa[$key]['urutan_2'] = '';
                                $data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $siswa->nm_c_siswa }}</td>
                    <td>{{ $siswa->nm_panggilan }}</td>
                    @if ($siswa->jenis_kelamin)
                        <td>{{ $siswa->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $siswa->nis_siswa }}</td>
                    <td>{{ $siswa->nisn_siswa }}</td>
                    <td>'{{ $siswa->nik_siswa }}</td>
                    <td>{{ $siswa->nm_kota_lahir ? $siswa->nm_kota_lahir : '-' }},
                        {{ $siswa->tgl_lahir ? date('d F Y', strtotime($siswa->tgl_lahir)) : '' }}</td>
                    <td>{{ $siswa->nm_agama }}</td>
                    @if ($siswa->kewarganegaraan)
                        <td>{{ $siswa->kewarganegaraan == 1 ? 'WNI' : 'WNA' }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $siswa->anak_ke }}</td>
                    <td>{{ $siswa->dari_x_bersaudara }}</td>

                    {{-- @if ($siswa->status_ayah && $siswa->status_ibu)
                        @if ($siswa->status_ayah == 1 && $siswa->status_ibu == 2)
                            <td>Piatu</td>
                        @elseif($siswa->status_ayah == 2 && $siswa->status_ibu == 1)
                            <td>Yatim</td>
                        @elseif($siswa->status_ayah == 2 && $siswa->status_ibu == 2)
                            <td>Yatim piatu</td>
                        @else
                            <td>Lengkap</td>
                        @endif
                    @else
                        <td></td>
                    @endif
                    <td>{{ $siswa->bahasa_sehari_hari }}</td> --}}
                    <td>{{ $siswa->alamat_jalan }}
                        {{ $siswa->alamat_rt ? 'RT ' . $siswa->alamat_rt : '' }}
                        {{ $siswa->alamat_rw ? 'RW ' . $siswa->alamat_rw : '' }}
                        {{ $siswa->alamat_kelurahan ? $siswa->alamat_kelurahan : '' }}
                        {{ $siswa->alamat_kodepos ? $siswa->alamat_kodepos : '' }}
                        {{ $siswa->alamat_kecamatan ? $siswa->alamat_kecamatan : '' }}
                        {{ $siswa->nm_kota ? $siswa->nm_kota : '' }}</td>
                    <td>Telp. {{ $siswa->nomor_telp_ortu ? $siswa->nomor_telp_ortu : '-' }} / Hp.
                        {{ $siswa->nomor_hp_ortu ? $siswa->nomor_hp_ortu : '-' }}</td>
                    <td>{{ $siswa->email_pengguna }}</td>
                    <td>{{ $siswa->nm_jenis_tinggal }}</td>
                    <td>{{ $siswa->jarak_rumah_sekolah }} km</td>
                    <td>{{ $siswa->nm_jenis_transportasi }}</td>
                    <td>{{ $siswa->waktu_tempuh_sekolah_jam * 60 + $siswa->waktu_tempuh_sekolah_menit }} menit</td>
                    <td>{{ $siswa->golongan_darah }}</td>
                    <td>{{ $siswa->riwayat_penyakit }}</td>
                    <td>{{ $siswa->riwayat_kelainan_jasmani }}</td>
                    <td>{{ $siswa->tinggi_badan }} cm / {{ $siswa->berat_badan }} kg</td>
                    <td>{{ $siswa->nm_kebutuhan_khusus }}</td>
                    <td>{{ $siswa->asal_sekolah }}</td>
                    <td>{{ $siswa->tanggal_sttb ? date('d F Y', strtotime($siswa->tanggal_sttb)) : '' }}
                        {{ $siswa->nomor_sttb }}</td>
                    <td>{{ $siswa->tanggal_skhus_sebelumnya ? date('d F Y', strtotime($siswa->tanggal_skhus_sebelumnya)) : '' }}
                        {{ $siswa->nomor_skhus_sebelumnya }}</td>
                    {{-- <td></td> --}}
                    <td>{{ $siswa->asal_sekolah2 }}</td>
                    <td>{{ $siswa->alasan_mutasi }}</td>
                    <td>{{ $siswa->nm_kelas }} / {{ $semester->nm_semester }}</td>
                    <td>{{ $siswa->is_penerima_kps == 0 ? 'Tidak' : 'Ya' }}</td>
                    {{-- <td>{{ $siswa->tgl_diterima }}</td> --}}
                    {{-- <td>{{ $siswa->nomor_kps }}</td> --}}
                    <td>{{ $siswa->nm_ayah }}</td>
                    <td>{{ $siswa->tgl_lahir_ayah ? date('Y', strtotime($siswa->tgl_lahir_ayah)) : '' }}</td>
                    <td>{{ $siswa->nm_jenis_pekerjaan_ayah }}</td>
                    <td>{{ $siswa->nm_jenis_pendidikan_ayah }}</td>
                    <td>{{ $siswa->nm_jenis_penghasilan_ayah }}</td>
                    <td>{{ $siswa->alamat_jalan_ayah }}
                        {{ $siswa->almat_rt_ayah ? 'RT ' . $siswa->almat_rt_ayah : '' }}
                        {{ $siswa->alamat_rw_ayah ? 'RW ' . $siswa->alamat_rw_ayah : '' }}
                        {{ $siswa->alamat_kelurahan_ayah ? $siswa->alamat_kelurahan_ayah : '' }}
                        {{ $siswa->alamat_kodepos_ayah ? $siswa->alamat_kodepos_ayah : '' }}
                        {{ $siswa->alamat_kecamatan_ayah ? $siswa->alamat_kecamatan_ayah : '' }}
                        {{ $siswa->nm_kota_ayah ? $siswa->nm_kota_ayah : '' }}</td>
                    @if ($siswa->status_ayah)
                        <td>{{ $siswa->status_ayah == 1 ? 'Masih hidup' : 'Meninggal dunia' }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $siswa->nm_ibu }}</td>
                    <td>{{ $siswa->tgl_lahir_ibu ? date('Y', strtotime($siswa->tgl_lahir_ibu)) : '' }}</td>
                    <td>{{ $siswa->nm_jenis_pekerjaan_ibu }}</td>
                    <td>{{ $siswa->nm_jenis_pendidikan_ibu }}</td>
                    <td>{{ $siswa->nm_jenis_penghasilan_ibu }}</td>
                    <td>{{ $siswa->alamat_jalan_ibu }} {{ $siswa->almat_rt_ibu ? 'RT ' . $siswa->almat_rt_ibu : '' }}
                        {{ $siswa->alamat_rw_ibu ? 'RW ' . $siswa->alamat_rw_ibu : '' }}
                        {{ $siswa->alamat_kelurahan_ibu ? $siswa->alamat_kelurahan_ibu : '' }}
                        {{ $siswa->alamat_kodepos_ibu ? $siswa->alamat_kodepos_ibu : '' }}
                        {{ $siswa->alamat_kecamatan_ibu ? $siswa->alamat_kecamatan_ibu : '' }}
                        {{ $siswa->nm_kota_ibu ? $siswa->nm_kota_ibu : '' }}</td>
                    @if ($siswa->status_ibu)
                        <td>{{ $siswa->status_ibu == 1 ? 'Masih hidup' : 'Meninggal dunia' }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $siswa->nm_wali }}</td>
                    <td>{{ $siswa->tgl_lahir_wali ? date('Y', strtotime($siswa->tgl_lahir_wali)) : '' }}</td>
                    <td>{{ $siswa->nm_jenis_pekerjaan_wali }}</td>
                    <td>{{ $siswa->nm_jenis_pendidikan_wali }}</td>
                    <td>{{ $siswa->nm_jenis_penghasilan_wali }}</td>
                    <td>{{ $siswa->kegemaran_kesenian }}</td>
                    <td>{{ $siswa->kegemaran_olahraga }}</td>
                    <td>{{ $siswa->kegemaran_organisasi }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach

        </tbody>
    </table>


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
</body>

</html>
