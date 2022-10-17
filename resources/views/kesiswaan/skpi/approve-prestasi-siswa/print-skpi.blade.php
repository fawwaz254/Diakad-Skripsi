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
        * {
            font-family: 'Times New Roman', Times, serif;
        }

        .bg {
            background-image: url("{{ asset('media/vxskpi.png') }}");
            /* Full height */
            height: 1600px;
            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .logo {
            left: 0;
            right: 0;
            padding-left: 10px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            position: absolute;
            margin-top: 45px;
        }

        table,
        td,
        th {
            border: 1px solid black;
            padding: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-left: auto;
            margin-right: auto;
        }

        .header,
        .header tr td {
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
            .ttd {
                position: relative;
            }

            .break-after {
                page-break-after: always;
            }

            .bg {
                background-image: url("{{ asset('media/vxskpi.png') }}");
                /* Center and scale the image nicely */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;

            }

            .bg-color {
                width: 93%;
                border-collapse: collapse;
                margin-left: auto;
                margin-right: auto;
            }

            table,
            td,
            th {
                border: 1px solid black;
                padding-left: 10px;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #6e9c6e;
            }

            .atas {
                background-color: white;
            }
        }

    </style>




    @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
        <style type="text/css">
            .bg {
                background-image: url("{{ asset('media/skpi-wh2.png') }}");
            }

            @media print {
                .bg {
                background-image: url("{{ asset('media/skpi-wh2.png') }}"); 
                }

                tr:nth-child(even) {
                            background-color: #439dd6;
                }

                .logo {
                    left: 0;
                    right: 0;
                    padding-left: 0px;
                    text-align: center;
                    margin-left: auto;
                    margin-right: auto;
                    position: absolute;
                    margin-top: 45px;
                }

                tr:nth-child(even) {
                    background-color: #439dd6;
                }
            }
        </style>
    @endif

    @if ($siswa->keterangan_kelas == 'Internasional')
        <style>
            @media print {
                table,
                td,
                th {
                    line-height: normal;
                    border: 1px solid black;
                    padding-top: 1px;
                    padding-bottom: 1px;
                }
            }
        </style>
    @endif

    {{-- @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
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
    @endif --}}
    <title>SKPI Siswa</title>
</head>

<body>
    <div class="bg">
        <img class="logo"
            src="https://diakad.sgp1.digitaloceanspaces.com/{{ $auth_data->sekolah_data->nm_singkat_sekolah }}/global/logo-sekolah"
            alt="Logo Sekolah" style="height:100px; width:90px" />

        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

        <table class="header" cellspacing="0" cellpadding="10" style="width: 100%;">
            <tr>
                <td colspan="10">
                    <h5 align="center" style="font-family: Segoe Print; color:green; margin-top: 5px">
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                            Yayasan Pendidikan & Sosial Ma'arif
                        @else
                            {{ strtoupper($auth_data->sekolah_data->nm_yayasan_sekolah) }}
                        @endif
                    </h5>
                </td>
        </table>

        <table class="header" cellspacing="0" cellpadding="10" style="width: 100%; margin-top:-15px">
            <tr>
                <td colspan="10">
                    <h1 align="center" style="font-family: Arial Black; color:blue;">
                        {{ strtoupper($auth_data->sekolah_data->nm_sekolah) }}
                    </h1>
                    <h3 align="center" style=" margin-top:-10px"><b>TERAKREDITASI : A ( UNGGUL )</b>
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smawh2')
                            <br>NSS : 204050214055 NDS : 2005020203 NPSN : {{ $auth_data->sekolah_data->npsn_sekolah }}
                        @endif
                    </h3>
                </td>
            </tr>
        </table>
        
        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
            <table class="header" cellspacing="0" cellpadding="10" style="width: 80%; border:1px solid; margin-left: auto;
            margin-right: auto; background-color: rgb(158, 198, 255);">
        @else
            <table class="header" cellspacing="0" cellpadding="10" style="width: 80%; border:1px solid; margin-left: auto;
            margin-right: auto; background-color: rgb(214, 227, 188);">
        @endif
            <tr>
                <td colspan=9>
                    <p align="center">Alamat : {{ $auth_data->sekolah_data->alamat_jalan }}
                        {{ $auth_data->sekolah_data->alamat_kelurahan }} Tlp.
                        {{ $auth_data->sekolah_data->nomor_telp_sekolah }} -
                        {{ $auth_data->sekolah_data->nomor_fax_sekolah }} Kecamatan
                        {{ $auth_data->sekolah_data->alamat_kecamatan }}
                        Kabupaten {{ $auth_data->sekolah_data->kota->nm_kota }}, Kode Pos
                        {{ $auth_data->sekolah_data->alamat_kodepos }} <br>E-mail :
                        {{ $auth_data->sekolah_data->email_sekolah }}
                        Website : {{ $auth_data->sekolah_data->website_sekolah }}</p>
                </td>
            </tr>
        </table>
        <table class="header" cellspacing="0" cellpadding="10" style="width: 100%;">
            @if ($siswa->keterangan_kelas == 'Internasional')
                <h4 class="text-center" style="margin-top: 10px; font-family: 'Franklin Gothic Demi Cond, monospace">
                    <b>SURAT KETERANGAN PENDAMPING IJAZAH</b>
                </h4>
            @else
                <h4 class="text-center" style="margin-top: 20px;  font-family: 'Franklin Gothic Demi Cond, monospace">
                    <b>SURAT KETERANGAN PENDAMPING IJAZAH</b>
                </h4>
            @endif

            <h5 class="text-center"><b>Diploma Supplement</b></h5>
            <h5 class="text-center">Nomor :
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    466/C-3/WH2/VI/2022
                @endif
            </h5>

        </table>
        <table class="header" cellspacing="0" cellpadding="10" style="width: 100%;">
            <p style=" border-top: 3px solid; border-bottom: 3px solid; width: 80%;margin-left: auto;margin-right: auto;">Surat Keterangan Pendamping Ijazah yang dikeluarkan oleh {{ $auth_data->sekolah_data->nm_sekolah }} sebagai pelengkap ijazah yang menerangkan capaian pembelajaran dan prestasi dari pemegang ijazah selama masa studi
                @if ($siswa->keterangan_kelas == 'Internasional')
                    <br><i>The Diploma Supplement is issued by {{ $auth_data->sekolah_data->nm_sekolah }} Sidoarjo
                        accompanies a higher education certificate providing a standardized description of the nature, level, context,
                        content, and status of the studies completed by its holder</i>
                @endif
            </p>
        </table>

        @if ($siswa->keterangan_kelas == 'Internasional')
            <div class="container" style="margin-top: 20px;">
                <h5 style="margin-left: 15px"><b>I. INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI
                        <br><i style="margin-left: 17px">INFORMATION OF PERSONAL DIPLOMA SUPPLEMENT HOLDER</i> </b>
                </h5>
        @else
            <div class="container" style="margin-top: 20px;">
                <h5 style="margin-top: 30px; margin-left: 35px"><b>I. INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI
                    </b>
                </h5>
        @endif

        <table class="bg-color">
            <tr>
                <td style="width: 5%;">1.A1</td>
                <td style="width: 30%;">Nama Lengkap
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Full Name</i>
                </td>
                @endif
                <td> {{ $siswa->nm_c_siswa }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">1.A2</td>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    <td style="width: 30%;">Tanggal Lahir </td>
                    <td> {{ indonesiaDate($siswa->calon_siswa->tgl_lahir) }}</td>
                @else
                    <td style="width: 30%;">Tempat, Tanggal Lahir
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><i>Place and Date of Birth</i>
                        @endif
                    </td>
                    <td> {{ $siswa->calon_siswa->kota_lahir ? $siswa->calon_siswa->kota_lahir->nm_kota : '' }},
                        {{ indonesiaDate($siswa->calon_siswa->tgl_lahir) }}</td>
                @endif
            </tr>
            <tr>
                <td style="width: 5%;">1.A3</td>
                <td style="width: 40%;">No. Induk Sekolah dan Nasional
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>School and National Student Identification Number</i>
                    @endif
                </td>
                <td> {{ $siswa->nis_siswa }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">1.A4</td>
                <td style="width: 30%;">Tahun Masuk
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Admission Year</i>
                    @endif
                </td>
                <td> {{ $siswa->thn_masuk_siswa }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">1.A5</td>
                <td style="width: 30%;">Tahun Keluar
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Graduation Year</i>
                    @endif
                </td>
                <td> {{ date_format(date_create(), 'Y') }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">1.A6</td>
                <td style="width: 30%;">Nomor Seri Ijazah
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Number of Certification</i>
                    @endif
                </td>
                <td> {{ $siswa->pengajuan_wisuda ? $siswa->pengajuan_wisuda->nomor_ijasah : '' }}</td>
            </tr>
        </table>

        @php
            $no = 0;
        @endphp

        @if ($siswa->keterangan_kelas == 'Internasional')
            <h5 style="margin-top: 40px;  margin-left: 10px"><b>II. INFORMASI TENTANG IDENTITAS PENYELENGGARA
                <br><i style="margin-left: 25px">INFORMATION OF IDENTITYHIGHER EDUCATION INSTITUTION</i></b></h5>
        @else
            <h5 style="margin-top: 40px;  margin-left: 35px"><b>II. INFORMASI TENTANG IDENTITAS PENYELENGGARA</b></h5>
        @endif

        <table class="bg-color break-after">
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Nama Satuan Pendidikan
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Name of School</i>
                    @endif
                </td>
                <td> {{ $auth_data->sekolah_data->nm_sekolah }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Surat Izin Operasional Sekolah
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Certificate of Establishment</i>
                    @endif
                </td>
                <td> {{ $auth_data->sekolah_data->nomor_sk_izin_operasional }}</td>
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 40%;">Jenis dan Jenjang Pendidikan
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Type and Level of Education</i>
                    @endif
                </td>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    <td> Pendidikan Formal/Sekolah Menengah Atas</td>
                @else
                    <td> Pendidikan Formal/Sekolah Menengah Pertama</td>
                @endif
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Status Akreditasi
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Accreditation Status</i>
                    @endif
                </td>
                <td> A (Unggul)</td>
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Nomor SK Akreditasi
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Accreditation Number</i>
                    @endif
                </td>
                <td> 599/BAN-SM/SK/2019</td>
            </tr>
            @if ($auth_data->sekolah_data->nm_singkat_sekolah != 'smawh2')
                <tr>
                    <td style="width: 5%;">2.A{{ $no++ }}</td>
                    <td style="width: 30%;">Jenjang Kualifikasi Sesuai KKNI
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><i>AppropriateLevel of Qualification</i>
                        @endif
                    </td>
                    <td> Level 1</td>
                </tr>
            @endif
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Persyaratan Penerimaan
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Access Requirements</i>
                    @endif
                </td>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    <td> Lulus SMP/Mts dan Lulus Seleksi Penerimaan Peserta Didik Baru</td>
                @else
                    <td> Lulus SD dan Lulus Seleksi Penerimaan Peserta Didik Baru</td>
                @endif
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Bahasa Pengantar Sekolah
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>School Language</i>
                    @endif
                </td>
                <td> Bahasa Indonesia</td>
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Lama Studi Reguler
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Reguler Study Period</i>
                    @endif
                </td>
                <td> Tiga Tahun (3 Tahun)</td>
            </tr>
            <tr>
                <td style="width: 5%;">2.A{{ $no++ }}</td>
                <td style="width: 30%;">Jenis dan Jenjang Pendidikan Lanjutan
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Access to Further Study</i>
                    @endif
                </td>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    <td> Perguruan Tinggi</td>
                @else
                    <td> SMA/Sederajat</td>
                @endif
            </tr>
        </table>
    </div>
    <br>

    <h5 style="margin-top: 80px;margin-left: 110px"><b>III. INFORMASI TENTANG KECAKAPAN DAN HASIL PEMBELAJARAN
            @if ($siswa->keterangan_kelas == 'Internasional')
                <br><i style="margin-left: 30px">INFORMATION OF PROFICIENCY AND LEARNING OUTCOME</i>
            @endif
        </b></h5>
    <h6 style="margin-top: 15px;margin-left: 110px">A. Capaian Pembelajaran
        @if ($siswa->keterangan_kelas == 'Internasional')
            <br><i style="margin-left: 17px">Learning Outcome</i>
        @endif
    </h6>

    <table class="bg-color" style="width: 80%;border-collapse: collapse;margin-left: auto;margin-right: auto;">
        <tr>
            <td style="width: 5%;">3.A1</td>
            <td>Bertaqwa kepada Tuhan Yang Maha Esa dan menjunjung tinggi sikap religius</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Believe in God Almighty and uphold a religious attitude</i>
            @endif
            </td>
        </tr>
        <tr>
            <td style="width: 5%;">3.A2</td>
            <td>Mampu mengintegrasikan keilmuan dan etika yang berkarakter</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Able to integrate science and ethics with character</i></td>
            @endif
        </tr>
        <tr>
            <td style="width: 5%;">3.A3</td>
            <td>Memiliki kemampuan hidup mandiri, kerjasama, disiplin, tanggung jawab, dan tenggang rasa terhadap sesama
            </td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Have the ability to live independently, cooperation, discipline,
                        responsibility,
                        and tolerance for others</i></td>
            @endif
        </tr>
        <tr>
            <td style="width: 5%;">3.A4</td>
            <td>Terampil dalam aktivitas rumah tangga dasar</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Skilled in basic household activities</i></td>
            @endif
        </tr>
        <tr>
            <td style="width: 5%;">3.A5</td>
            <td>Terampil dalam aktivitas perawatan dasar</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Skilled in basic care activities</i></td>
            @endif
        </tr>
        <tr>
            <td style="width: 5%;">3.A6</td>
            <td>Menguasai konsep komunikasi dan terampil dalam komunikasi personal</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Mastering communication concepts and skilled in personal communication</i>
                </td>
            @endif
        </tr>
        <tr>
            <td style="width: 5%;">3.A7</td>
            <td>Bekerjasama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungannya</td>
            @if ($siswa->keterangan_kelas == 'Internasional')
                <td style="width: 55%;"><i>Cooperate and have social sensitivity and concern for the community and the
                        environment</i></td>
            @endif
        </tr>
    </table>

    <p style="display: none"> {{ $urutan = 'B' }}</p>

    @if ($prestasi->count() > 0)
        <h6 style="margin-top: 15px;margin-left: 110px">{{ $urutan }}. Lomba/Olimpiade
            @if ($siswa->keterangan_kelas == 'Internasional')
                <br><i style="margin-left: 17px">Competition/Olympics</i>
            @endif
        </h6>

        <table class="bg-color" style="width: 80%;border-collapse: collapse;margin-left: auto;margin-right: auto;text-align: center;">
            @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                <thead style="background-color: #439dd6;">
            @else
                <thead style="background-color: #6e9c6e;">
            @endif
                <tr>
                    <td><b>Nomor</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Number</i></b>
                        @endif
                    </td>
                    <td><b>Nama Lomba/Olimpiade</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Name Of Competition/Olympics</i></b>
                        @endif
                    </td>
                    <td><b>Tingkat Lomba/Olimpiade</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Competition/Olympic Level</i></b>
                        @endif
                    </td>
                    <td><b>Jenis Lomba/Olimpiade</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>
                                    Type of Competition/Olympic</i></b>
                        @endif
                    </td>
                    <td><b>Peringkat</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Rating</i></b>
                        @endif
                    </td>
                    <td><b>Lokasi</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>location</i></b>
                        @endif
                    </td>
                    <td><b>Penyelenggara</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Organizer</i></b>
                        @endif
                    </td>
                    <td><b>Tanggal</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Date</i></b>
                        @endif
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($prestasi as $r)
                    <tr>
                        <td>3.{{ $urutan }}{{ $loop->iteration }}</td>
                        <td>{{ $r->nm_prestasi_siswa }}</td>
                        <td>{{ $r->nm_tingkat_prestasi_siswa }}</td>
                        {{-- <td>
                        @if ($r->jenis_prestasi_siswa == 1)
                        Sains
                        @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Science</i>
                        @endif
                        @elseif ($r->jenis_prestasi_siswa == 2)
                        Seni
                        @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Art</i>
                        @endif
                        @elseif ($r->jenis_prestasi_siswa == 3)
                        Olahraga
                        @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Sport</i>
                        @endif
                        @else
                        Lain lain
                        @if ($siswa->keterangan_kelas == 'Internasional')
                        <br><i>Etc</i>
                        @endif
                        @endif
                        </td> --}}
                        <td>{{ $r->jenis_lomba_siswa }}</td>
                        <td>
                            @if ($r->peringkat_prestasi_siswa == 1)
                                Peringkat 1
                            @elseif($r->peringkat_prestasi_siswa == 2)
                                Peringkat 2
                            @elseif($r->peringkat_prestasi_siswa == 3)
                                Peringkat 3
                            @elseif($r->peringkat_prestasi_siswa == 4)
                                Juara Harapan 1
                            @elseif($r->peringkat_prestasi_siswa == 5)
                                Juara Harapan 2
                            @elseif($r->peringkat_prestasi_siswa == 6)
                                Juara Harapan 3
                            @elseif($r->peringkat_prestasi_siswa == 7)
                                Peserta
                            @endif
                        </td>
                        <td>{{ $r->lokasi_prestasi_siswa }}</td>
                        <td>{{ $r->penyelenggara_prestasi_siswa }}</td>
                        <td>{{ indonesiaDate($r->tgl_prestasi_siswa) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="display: none"> {{ $urutan = 'C' }}</p>
    @endif

    @if ($kegiatan->count() > 0)
        <h6 style="margin-top: 15px;margin-left: 110px">{{ $urutan }}. Kegiatan Sosial
            @if ($siswa->keterangan_kelas == 'Internasional')
                <br><i style="margin-left: 17px">Social Activities</i>
            @endif
        </h6>
        <table class="bg-color" style="width: 80%;border-collapse: collapse;margin-left: auto;margin-right: auto;text-align: center;">
            @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                <thead style="background-color: #439dd6;">
            @else
                <thead style="background-color: #6e9c6e;">
            @endif
                <tr>
                    <td><b>Nomor</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Number</i></b>
                        @endif
                    </td>
                    <td><b>Nama Kegiatan</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Activity Name </i></b>
                        @endif
                    </td>
                    <td><b>Lokasi</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Location</i></b>
                        @endif
                    </td>
                    <td><b>Penyelenggara</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Organizer</i></b>
                        @endif
                    </td>
                    <td><b>Tanggal</b>
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br><b><i>Date</i>
                            </b>
                        @endif
                    </td>
                </tr>
            </thead>
            <tbody>
                @foreach ($kegiatan as $r)
                    <tr>
                        <td>3.{{ $urutan }}{{ $loop->iteration }}</td>
                        <td>{{ $r->nm_kegiatan_siswa }}</td>
                        <td>{{ $r->lokasi_kegiatan_siswa }}</td>
                        <td>{{ $r->penyelenggara_kegiatan_siswa }}</td>
                        {{-- <td>{{$r->nm_tingkat_prestasi_siswa}}</td> --}}
                        <td>{{ date('d F Y', strtotime($r->tgl_kegiatan_siswa)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($prestasi->count() > 0)
            <p style="display: none"> {{ $urutan = 'D' }}</p>
        @else
            <p style="display: none"> {{ $urutan = 'C' }}</p>
        @endif
    @endif

    {{-- //informasi tambahan --}}
    {{-- @if ($informasi_tambahan->count() > 0) --}}
    @if($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
    <h6 style="margin-top: 15px;margin-left: 110px">{{ $urutan }}. Informasi Tambahan
        @if ($siswa->keterangan_kelas == 'Internasional')
            <br><i style="margin-left: 17px">Additional Information</i>
        @endif
    </h6>

    <table class="bg-color" style="width: 80%;margin-top: -20px; border-collapse: collapse; margin-left: auto;">
        <tr>
            <td>3.{{ $urutan }}1</td>
            <td>Bahasa Internasional
                @if ($siswa->keterangan_kelas == 'Internasional')
                    <br><i>International Language</i>
                @endif
            </td>
            <td>Bahasa Inggris
                @if ($siswa->keterangan_kelas == 'Internasional')
                    <br><i>English</i>
                @endif
            </td>
        </tr>
        <tr>
            <td>3.{{ $urutan }}2</td>
            <td>Kegiatan Melatih Keterampilan Hidup
                @if ($siswa->keterangan_kelas == 'Internasional')
                    <br><i>Life Skills Training Activities</i>
                @endif
            </td>
            <td>Pramuka dan Kemah Wisata <br> (Kedisiplinan, kemandirian, kerjasama dan tanggung jawab)
                @if ($siswa->keterangan_kelas == 'Internasional')
                    <br><i>Scouting and Tourism Camp <br> (Discipline, independence, cooperation and responsibility)</i>
                @endif
            </td>
        </tr>

        <p style="display:none">{{ $no = 2 }}</p>

        {{-- //ektra --}}

        @if ($informasi_tambahan_ekstrakurikuler->count() > 0)
            <tr>
                <p style=" display:none">{{ $no++ }}</p>
                <td>3.{{ $urutan }}{{ $no }}</td>
                <td>Ekstrakurikuler<br>
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <i>Extracurricular</i>
                </td>
        @endif
                <td style="width: 55%;">
                    @foreach ($informasi_tambahan_ekstrakurikuler as $r)
                        {{ $r->nm_informasi_tambahan }}
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br>
                            <i>{{ $r->nm_informasi_tambahan_eng }}</i>
                        @endif
                        <br>
                    @endforeach
                </td>
            </tr>
        @endif

        {{-- produk lomba sosial --}}
        @if ($informasi_produk_lomba->count() > 0)
            <tr>
                <p style=" display:none">{{ $no++ }}</p>
                <td>3.{{ $urutan }}{{ $no }}</td>
                <td>Produk Lomba
                    @if ($siswa->keterangan_kelas == 'Internasional')
                        <br>
                        <i>Competition Product</i>
                </td>
        @endif
                <td style="width: 55%;">
                    @foreach ($informasi_produk_lomba as $r)
                        {{ $r->nm_informasi_tambahan }}
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br>
                            <i>{{ $r->nm_informasi_tambahan_eng }}</i>
                        @endif
                        <br>
                    @endforeach
                </td>
            </tr>
        @endif

        <br>
    </table>
    @endif
    <div class="avoid-break mt-4 mb-4">
        <table cellspacing="0" style="width: 80%; border:none; margin:auto; text-align:left; ">
            <tr>
                <td style="width: 70%; border: none;"></td>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                    <td style="border: none;"></td>
                @endif
                <td style="border: none;">Kab. Sidoarjo,
                    {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                </td>
            </tr>
            <tr>
                <tr style="vertical-align: top;top:20px">
                    <td style="width: 70%; border: none;"></td>
                    @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                        <td style="border: none;">
                            <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $siswa->pengguna->path_foto_pengguna }}" alt="img" style="height:165px; width:124px; margin-left:-160px;"/>
                        </td>
                    @endif
                    <td style="border: none; position: relative;">
                        Kepala Sekolah
                        <br>
                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smawh2')
                            <br>
                            <img  src="{{ asset('media/ttd/smawh2.png') }}" alt="TTD" style="height:90px; margin-left:-40px;"  width="220px" />
                            <br>
                        @elseif($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1')
                            <img style="position: absolute; top: 5%; margin-left:-40px;" src="{{ asset('media/ttd/smpypm1.png') }}"
                                alt="TTD" width="160px" height="160px" class="ttd">
                            <br>
                            <br>
                            <br>
                        @else
                            <br>
                            <br>
                            <br>
                        @endif
                        @if ($siswa->keterangan_kelas == 'Internasional')
                            <br>
                            <br>
                        @endif
                        <b><u>{{ $auth_data->sekolah_data->nm_kepala_sekolah }}</u></b>
                    </td>
                </tr>
            </tr>
        </table>
        {{-- @endif --}}
    </div>
<div>
    <script>
        window.print();
    </script>
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
