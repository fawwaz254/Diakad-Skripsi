<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Cetak Jurnal Tindakan</title>

    <style>
        td p {
            margin: 0;
        }
        .signature-container {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
        }
    </style>

    <style type="text/css" media="print">
        @media print {
            .page {
                margin: 0;
                width: auto;
                min-height: initial;
                page-break-before: always;
            }
            .signature-container {
                display: flex;
                justify-content: space-around;
            }
        }
    </style>
</head>

<body>
    @foreach ($siswa as $siswa)
        @php
            $catatan_sekolah =
                'Mohon orang tua untuk mempertahankan dan meningkatkan perilaku siswa untuk lebih positif, sehingga tidak melakukan pelanggaran tata tertib sekolah.';
            $kategori_pelanggaran = 'Tidak Ada';
            $deskripsi_perilaku_1 = 'Tidak ada permasalahan yang tercatat di BK';
            $deskripsi_perilaku_2 = '';

            if ($list_data->count() > 0) {
                $check_siswa = $list_data->where('id_siswa', $siswa->id_siswa);
                if ($check_siswa->count() > 0) {
                    $total_poin = $list_data->where('id_siswa', $siswa->id_siswa)->sum('jumlah_poin');

                    $data = App\Models\KesimpulanPelanggaran::where(
                        'poin_bawah_kesimpulan_pelanggaran',
                        '<=',
                        $total_poin,
                    )
                        ->where('poin_atas_kesimpulan_pelanggaran', '>=', $total_poin)
                        ->first();

                    if ($data) {
                        $kategori_pelanggaran = strip_tags($data->deskripsi_kesimpulan_pelanggaran_2);
                        $deskripsi_perilaku_1 = strip_tags($data->deskripsi_kesimpulan_pelanggaran_1);
                        if ($data->nm_kesimpulan_pelanggaran) {
                            $kategori_pelanggaran = $data->nm_kesimpulan_pelanggaran;
                        }
                    }

                    $total_pelanggaran_yang_dilakukan = $list_data
                        ->where('id_siswa', $siswa->id_siswa)
                        ->sum('frekuensi');

                    if ($total_pelanggaran_yang_dilakukan == 1) {
                        $deskripsi_perilaku_2 =
                            'Ada perubahan perilaku siswa yang lebih baik setelah ditangani sekolah.';
                    } elseif ($total_pelanggaran_yang_dilakukan == 2) {
                        $deskripsi_perilaku_2 =
                            'Ada perubahan perilaku siswa yang cukup baik setelah ditangani sekolah.';
                    } else {
                        $deskripsi_perilaku_2 = 'Belum ada perubahan perilaku siswa setelah ditangani sekolah.';
                    }
                }
            }
        @endphp

        <div class="page">
            <div class="container text-center" style="margin-top:20px;">
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ asset('logo/logo-yayasan.png') }}" width="120">
                    </div>
                    <div class="col-md-8">
                        <h3>YAYASAN PENDIDIKAN DAN SOSIAL MA'ARIF <br> TAMAN – SEPANJANG – SIDOARJO</h3>
                        <p>Akte Notaris Goesti Djohan Nomor 91 Tanggal 17 September 1965</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p>Jl.Raya Ngelom 86 Telp. (031) 7874045 Fax. (031) 7884364 Sepanjang 61257 website
                            http.//www.ypm.ac.id- e-mail:ypm.ac.id.yayasan@ypm.ac.id</p>
                    </div>
                </div>
            </div>

            <div class="container text-center" style="margin-top:20px;">

                <h3>LAPORAN PRIBADI SISWA <br> {{ strtoupper($sekolah_data->nm_sekolah) }}</h3>
                <hr>
                <div class="row" style="margin-top: 15px;">
                    <table class="table table-borderless" style="text-align:left">
                        <thead>
                            <tr>
                                <th scope="col">Nama</th>
                                <th scope="col" style="font-weight: 400">: {{ $siswa->nm_pengguna }}</th>
                                <th scope="col">Kelas</th>
                                <th scope="col" style="font-weight: 400">: {{ $siswa->nm_kelas }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">No Induk</th>
                                <td>: {{ $siswa->nis_siswa }}</td>
                                <td style="font-weight: 700">Semester</td>
                                <td>: {{ $semester->nm_semester }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Jenis Kelamin</th>
                                @php
                                    if ($siswa->jenis_kelamin == '1') {
                                        $jenis_kelamin = 'Laki-Laki';
                                    } elseif ($siswa->jenis_kelamin == '2') {
                                        $jenis_kelamin = 'Perempuan';
                                    } else {
                                        $jenis_kelamin = ' ';
                                    }
                                @endphp
                                <td>: {{ $jenis_kelamin }}</td>
                                <td style="font-weight: 700">Tahun Pelajaran</td>
                                <td>: {{ $semester->tahun_ajaran }}</td>
                            </tr>

                        </tbody>
                    </table>

                    <div class="col-md-12">
                        <h6 style="text-align:left">A. Catatan Siswa</h6>
                        <table border="1" style="width:100%" cellspacing="0" cellpadding="10">
                            <tr>
                                <th style="width:5%">No.</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Pelanggaran Tingkat</th>
                                <th>Poin</th>
                                <th>Frekuensi</th>
                                <th>Jumlah</th>
                            </tr>
                            @php
                                $no = 1;
                                $jumlah = 0;
                            @endphp
                            @foreach ($list_data as $data)
                                @if ($siswa->id_siswa == $data->id_siswa)
                                    <tr>
                                        <td style="width:5%">{{ $no++ }}.</td>
                                        <td align="left">{!! $data->nm_subkategori_pelanggaran !!}</td>
                                        <td>{{ $data->nm_kategori_pelanggaran }}</td>
                                        <td>{{ $data->poin_subkategori_pelanggaran }}</td>
                                        <td>{{ $data->frekuensi }} x</td>
                                        <td>{{ $data->jumlah_poin }}</td>
                                        @php
                                            $jumlah += $data->jumlah_poin;
                                        @endphp
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td colspan="5" align="center"><b>TOTAL</b></td>
                                <td align="center"><b>{{ $jumlah }}</b></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="center"><b>KATEGORI PELANGGARAN</b></td>
                                <td align="center">
                                    <b>{{ $jumlah == '0' ? 'TIDAK ADA' : $kategori_pelanggaran }}</b>
                                </td>
                            </tr>
                            {{-- @if ($setting_bk)
                            @endif --}}
                        </table>
                    </div>

                    <!-- Deskripsi Perilaku Siswa -->

                    <div class="col-md-12">
                        <h6 style="margin-top:15px;text-align: left;">B. Deskripsi Perilaku Siswa</h6>
                        <table border="1" style="width:100%" cellspacing="0" cellpadding="10">
                            <tr>
                                <th style="width:5%">No.</th>
                                <th>Deskripsi</th>
                            </tr>
                            <tr>
                                <td style="width:5%">1</td>
                                <td align="left">{{ $deskripsi_perilaku_1 }}</td>
                            </tr>
                            <tr>
                                <td style="width:5%">2</td>
                                <td align="left">{{ $deskripsi_perilaku_2 }}</td>
                            </tr>
                        </table>
                        {{-- @if ($setting_bk)
                        @else --}}
                            {{-- <fieldset style="height: 100px;border:1px solid">
                                <p></p>
                            </fieldset> --}}
                        {{-- @endif --}}
                    </div>

                    <!-- Deskripsi Catatan Sekolah -->

                    <div class="col-md-12">
                        <h6 style="margin-top:15px;text-align: left;">C. Catatan Sekolah</h6>
                        <fieldset style="height: 100px;border:1px solid;text-align:left;padding:0 5px">
                            <p>{{ $catatan_sekolah }}</p>
                        </fieldset>
                    </div>

                    
                </div>
                <div class="signature-container">
                    <div class="col-md-4" style="margin-top:50px;">
                        Mengetahui <br> Kepala Sekolah,
                        <br>
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                            <br>
                            <img src="{{ asset('media/ttd/smawh2.png') }}" alt="TTD"
                                style="height:90px; margin-left:-40px;" width="220px" />
                            <br>
                        @elseif($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                            <img style="position: absolute; top: 5%; margin-left:-40px;"
                                src="{{ asset('media/ttd/smpypm1.png') }}" alt="TTD" width="160px" height="160px"
                                class="ttd">
                            <br>
                            <br>
                            <br>
                        @elseif($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                            <img style="position: absolute; top: 5%; margin-left:-70px; margin-top:17px"
                                src="{{ asset('media/ttd/smpypm2.png') }}" alt="TTD" width="160px" height="160px"
                                class="ttd">
                            <br>
                            <br>
                            <br>
                            <br>
                        @else
                            <br>
                            <br>
                            <br>
                            <br>
                        @endif
                        <div>{{ $sekolah_data->nm_kepala_sekolah }}</div>
                    </div>
    
                    <div class="col-md-4" style="margin-top:75px;">
                        Orang Tua / Wali Peserta Didik,
                        <div style="margin-top:100px;">...........................</div>
                    </div>
    
                    <div class="col-md-4" style="margin-top:50px;">
                        Sidoarjo,
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2')
                            {{ $tanggal_cetak }}
                        @else
                            {{ $tanggal_cetak }}
                        @endif
                        <br> Wali Kelas,
                        <div style="margin-top:100px;">
                            {{ $wali_kelas->gelar_depan }} {{ $wali_kelas->nm_wali_kelas }}
                            {{ $wali_kelas->gelar_belakang }}
                        </div>
                    </div>

                </div>
            </div>
    @endforeach

    <script>
        window.print();
    </script>
</body>

</html>
