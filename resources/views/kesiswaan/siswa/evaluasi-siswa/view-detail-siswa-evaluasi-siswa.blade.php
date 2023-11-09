<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#siswa/evaluasi-siswa/view-detail/' . $nis_nama_siswa_asli) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>
                        BIODATA SISWA NIS/NISN : {{ $nis_siswa }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">BIODATA SISWA</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">
                                    @if (!empty($siswa->path_foto_pengguna))
                                        <img src="{{ Storage::disk('spaces')->url($siswa->path_foto_pengguna) }}"
                                            style="height: 270px; width: 180px">
                                    @else
                                        <img src="{{ asset('media/blank-user.png') }}"
                                            style="height: 270px; width: 180px">
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Lengkap</td>
                                <td style="width: 50%">{{ $siswa->nm_pengguna }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nomor Pendaftaran</td>
                                <td style="width: 50%">{{ $siswa->kode_voucher }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NIS</td>
                                <td style="width: 50%">{{ $siswa->nis_siswa }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">NISN</td>
                                <td style="width: 50%">{{ $siswa->nisn_siswa }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jenis Kelamin</td>
                                <td style="width: 50%">{{ $jenis_kelamin }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tempat, Tanggal Lahir</td>
                                <td style="width: 50%">{{ $kota_lahir }},{{ $siswa->tgl_lahir }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Tahun Masuk</td>
                                <td style="width: 50%">{{ $siswa->thn_masuk_siswa }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jalur Masuk</td>
                                <td style="width: 50%">{{ $siswa->nm_jalur }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Sekolah Asal</td>
                                <td style="width: 50%">{{ $siswa->asal_sekolah }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Jurusan</td>
                                <td style="width: 50%">{{ $siswa->nm_jurusan }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Kelas</td>
                                <td style="width: 50%">{{ $siswa->nm_kelas }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Status Siswa</td>
                                <td style="width: 50%">{{ $siswa->nm_status_pengguna }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ayah</td>
                                <td style="width: 50%">{{ $siswa->nm_ayah }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ibu</td>
                                <td style="width: 50%">{{ $siswa->nm_ibu }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Siswa</td>
                                <td style="width: 50%">{{ $alamat_jalan_siswa }} {{ $alamat_dusun_siswa }}
                                    {{ $alamat_kelurahan_siswa }} {{ $alamat_rt_siswa }} {{ $alamat_rw_siswa }}
                                    {{ $alamat_kecamatan_siswa }} {{ $alamat_kodepos_siswa }} {{ $siswa->nm_kota }}
                                    {{ $siswa->nm_provinsi }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Alamat Ortu</td>
                                <td style="width: 50%">{{ $alamat_jalan_ortu }} {{ $alamat_dusun_ortu }}
                                    {{ $alamat_kelurahan_ortu }} {{ $alamat_rt_ortu }} {{ $alamat_rw_ortu }}
                                    {{ $alamat_kecamatan_ortu }} {{ $alamat_kodepos_ortu }} {{ $alamat_kota_ortu }}
                                    {{ $alamat_provinsi_ortu }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Siswa</td>
                                <td style="width: 50%">{{ $siswa->nomor_hp }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. Telepon Ortu</td>
                                <td style="width: 50%">{{ $siswa->nomor_telp_ortu }}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">No. HP Ortu</td>
                                <td style="width: 50%">{{ $siswa->nomor_hp_ortu }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <h2 class="card-inside-title">Beasiswa</h2>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table_beasiswa">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Jenis Beasiswa</th>
                                    <th>Tahun Mulai</th>
                                    <th>Tahun Selesai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <h2 class="card-inside-title">Prestasi</h2>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table_prestasi">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Nama Prestasi</th>
                                    <th>Jenis Prestasi</th>
                                    <th>Tingkat Prestasi</th>
                                    <th>Semester</th>
                                    <th>Lokasi - Penyelenggara</th>
                                    <th>Peringkat</th>
                                    <th>Tanggal Prestasi</th>
                                    <th>Guru Pendamping</th>
                                    <th>Ekstrakurikuler</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <h2 class="card-inside-title">Ekstrakurikuler</h2>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table_ekskul">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Nama Ekstrakurikuler</th>
                                    <th>Semester</th>
                                    <th>Nilai Angka</th>
                                    <th>Nilai Huruf</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var nis_nama_siswa = {!! json_encode($nis_siswa) !!};
    var modul_url = 'siswa';
    var datatable_url_beasiswa = base_url + '/' + role_url + '/' + modul_url + '/' +
        'evaluasi-siswa/datatables-beasiswa/' + nis_nama_siswa;
    var datatable_url_prestasi = base_url + '/' + role_url + '/' + modul_url + '/' +
        'evaluasi-siswa/datatables-prestasi/' + nis_nama_siswa;
    var datatable_url_ekskul = base_url + '/' + role_url + '/' + modul_url + '/' + 'evaluasi-siswa/datatables-ekskul/' +
        nis_nama_siswa;

    var primary_table_beasiswa = $('#primary_table_beasiswa').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url_beasiswa,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'jenis_beasiswa_siswa',
                name: 'jenis_beasiswa_siswa'
            },
            {
                data: 'tahun_mulai_beasiswa_siswa',
                name: 'tahun_mulai_beasiswa_siswa'
            },
            {
                data: 'tahun_selesai_beasiswa_siswa',
                name: 'tahun_selesai_beasiswa_siswa'
            },
            {
                data: 'keterangan_beasiswa_siswa',
                name: 'keterangan_beasiswa_siswa'
            }
        ]
    });

    primary_table_beasiswa.on('draw', function() {
        primary_table_beasiswa.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    var primary_table_prestasi = $('#primary_table_prestasi').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url_prestasi,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_prestasi_siswa',
                name: 'nm_prestasi_siswa'
            },
            {
                data: 'jenis_prestasi',
                name: 'jenis_prestasi'
            },
            {
                data: 'nm_tingkat_prestasi_siswa',
                name: 'nm_tingkat_prestasi_siswa'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'lokasi_penyelenggara',
                name: 'lokasi_penyelenggara'
            },
            {
                data: 'peringkat_prestasi_siswa',
                name: 'peringkat_prestasi_siswa'
            },
            {
                data: 'tgl_prestasi_siswa',
                name: 'tgl_prestasi_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_ekskul',
                name: 'nm_ekskul'
            }
        ]
    });

    primary_table_prestasi.on('draw', function() {
        primary_table_prestasi.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    var primary_table_ekskul = $('#primary_table_ekskul').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url_ekskul,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_ekskul',
                name: 'nm_ekskul'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'nilai_angka',
                name: 'nilai_angka'
            },
            {
                data: 'nilai_huruf',
                name: 'nilai_huruf'
            }
        ]
    });

    primary_table_ekskul.on('draw', function() {
        primary_table_ekskul.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
