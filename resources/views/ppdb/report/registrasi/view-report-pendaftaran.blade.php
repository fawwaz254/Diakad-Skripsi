<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if ($mode == 'view')
                <div class="card">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>DATA REPORT PENDAFTARAN</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun</th>
                                        <th>Penerimaan</th>
                                        <th>Gelombang</th>
                                        <th>Submit Form</th>
                                        <th>Antri Verifikasi</th>
                                        <th>Perbaikan</th>
                                        <th>Lolos Verifikasi</th>
                                        <th>Bayar</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif($mode == 'rekap')
                <div class="block-header">
                    <h2><a class="btn bg-blue waves-effect target-link "
                            href="{{ url(Request::segment(1) . '#report/report-pendaftaran') }}"><i
                                class="material-icons">backspace</i><span>Kembali</span></a></h2>
                </div>
                <div class="card is-gap">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>REKAP JURUSAN PER PILIHAN ({{ $data_penerimaan->nm_penerimaan }} Gelombang
                            {{ $data_penerimaan->gelombang_penerimaan }} Tahun {{ $data_penerimaan->tahun_penerimaan }})
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jurusan</th>
                                        <th>Pilihan 1</th>
                                        <th>Pilihan 2</th>
                                        <th>Pilihan 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $nomor = 1;
                                        $total_pilihan_1 = 0;
                                        $total_pilihan_2 = 0;
                                        $total_pilihan_3 = 0;
                                    @endphp
                                    @foreach ($data_rekap as $rekap)
                                        <tr>
                                            <td class="center">{{ $nomor }}</td>
                                            <td>{{ $rekap->nm_jurusan }}</td>
                                            <td class="center">{{ $rekap->jumlah_p1 }}</td>
                                            <td class="center">{{ $rekap->jumlah_p2 }}</td>
                                            <td class="center">{{ $rekap->jumlah_p3 }}</td>
                                        </tr>
                                        @php
                                            $nomor = $nomor + 1;
                                            $total_pilihan_1 = $total_pilihan_1 + $rekap->jumlah_p1;
                                            $total_pilihan_2 = $total_pilihan_2 + $rekap->jumlah_p2;
                                            $total_pilihan_3 = $total_pilihan_3 + $rekap->jumlah_p3;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Jumlah</td>
                                        <td class="center">{{ $total_pilihan_1 }}</td>
                                        <td class="center">{{ $total_pilihan_2 }}</td>
                                        <td class="center">{{ $total_pilihan_3 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>REKAP JURUSAN PER STATUS ({{ $data_penerimaan->nm_penerimaan }} Gelombang
                            {{ $data_penerimaan->gelombang_penerimaan }} Tahun
                            {{ $data_penerimaan->tahun_penerimaan }})</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jurusan</th>
                                        <th>Submit Form</th>
                                        <th>Antri Verifikasi</th>
                                        <th>Lolos Verifikasi</th>
                                        <th>Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $nomor = 1;
                                        $total_submit_form = 0;
                                        $total_antri_verifikasi = 0;
                                        $total_verifikasi = 0;
                                        $total_bayar = 0;
                                    @endphp
                                    @foreach ($data_rekap as $rekap)
                                        <tr>
                                            <td class="center">{{ $nomor }}</td>
                                            <td>{{ $rekap->nm_jurusan }}</td>
                                            <td class="center">{{ $rekap->jumlah_submit_form }}</td>
                                            <td class="center">{{ $rekap->jumlah_antri_verifikasi }}</td>
                                            <td class="center">{{ $rekap->jumlah_verifikasi }}</td>
                                            <td class="center">{{ $rekap->jumlah_bayar }}</td>
                                        </tr>
                                        @php
                                            $nomor = $nomor + 1;
                                            $total_submit_form = $total_submit_form + $rekap->jumlah_submit_form;
                                            $total_antri_verifikasi = $total_antri_verifikasi + $rekap->jumlah_antri_verifikasi;
                                            $total_verifikasi = $total_verifikasi + $rekap->jumlah_verifikasi;
                                            $total_bayar = $total_bayar + $rekap->jumlah_bayar;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2">Jumlah</td>
                                        <td class="center">{{ $total_submit_form }}</td>
                                        <td class="center">{{ $total_antri_verifikasi }}</td>
                                        <td class="center">{{ $total_verifikasi }}</td>
                                        <td class="center">{{ $total_bayar }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif($mode == 'detail')
                <div class="card">
                    {{ csrf_field() }}
                    <div class="block-header">
                        <h2><a class="btn bg-blue waves-effect target-link "
                                href="{{ url(Request::segment(1) . '#report/report-pendaftaran') }}"><i
                                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
                    </div>
                    @if (count($data_jurusan) == 0)
                        <div class="header">
                            <h2>({{ $data_penerimaan->nm_penerimaan }} Gelombang
                                {{ $data_penerimaan->gelombang_penerimaan }} Tahun
                                {{ $data_penerimaan->tahun_penerimaan }})</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                    id="primary_table_0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            {{-- <th>No Ujian</th> --}}
                                            <th>No Pendaftaran</th>
                                            <th>Nama</th>
                                            <th>Nomor HP</th>
                                            <th>Nomor HP Ortu</th>
                                            <th>Sekolah Asal</th>
                                            <th>Nama Ayah</th>
                                            <th>Nama Ibu</th>
                                            <th>Telp Ortu</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endif
                    @foreach ($data_jurusan as $jurusan)
                        <div class="header">
                            <h2>{{ $jurusan->nm_jurusan }} ({{ $data_penerimaan->nm_penerimaan }} Gelombang
                                {{ $data_penerimaan->gelombang_penerimaan }} Tahun
                                {{ $data_penerimaan->tahun_penerimaan }})</h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                    id="primary_table_{{ $jurusan->id_jurusan }}">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Ujian</th>
                                            <th>No Pendaftaran</th>
                                            <th>Nama</th>
                                            <th>Nomor HP</th>
                                            <th>Nomor HP Ortu</th>
                                            <th>Sekolah Asal</th>
                                            <th>Nama Ayah</th>
                                            <th>Nama Ibu</th>
                                            <th>Telp Ortu</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</div>

@if ($mode == 'detail')
    @foreach ($data_jurusan as $jurusan)
        <script>
            var id_penerimaan = {!! json_encode($data_penerimaan->id_penerimaan) !!};
            var id_jurusan = {!! json_encode($jurusan->id_jurusan) !!};

            var modul_url = 'report';
            var datatable_detail_url = base_url + '/' + role_url + '/' + modul_url + '/' +
                'report-pendaftaran/detail/datatables/' + id_penerimaan + '/' + id_jurusan;

            var primary_table_{{ $jurusan->id_jurusan }} = $('#primary_table_' + id_jurusan).DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                ajax: {
                    url: datatable_detail_url,
                    type: 'GET'
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'nomor_ujian',
                        name: 'calon_siswa_baru.nomor_ujian'
                    },
                    {
                        data: 'kode_voucher',
                        name: 'calon_siswa_baru.kode_voucher'
                    },
                    {
                        data: 'nm_c_siswa',
                        name: 'calon_siswa_baru.nm_c_siswa'
                    },
                    {
                        data: 'nomor_hp',
                        name: 'calon_siswa_baru.nomor_hp'
                    },
                    {
                        data: 'nomor_hp_ortu',
                        name: 'calon_siswa_ortu.nomor_hp_ortu'
                    },
                    {
                        data: 'nm_sekolah_asal',
                        name: 'calon_siswa_sekolah.nm_sekolah_asal'
                    },
                    {
                        data: 'nm_ayah',
                        name: 'calon_siswa_ortu.nm_ayah'
                    },
                    {
                        data: 'nm_ibu',
                        name: 'calon_siswa_ortu.nm_ibu'
                    },
                    {
                        data: 'nomor_telp_ortu',
                        name: 'calon_siswa_ortu.nomor_telp_ortu'
                    }
                ]
            });

            primary_table_{{ $jurusan->id_jurusan }}.on('draw', function() {
                primary_table_{{ $jurusan->id_jurusan }}.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    var start = this.page.info().page * 10;
                    cell.innerHTML = start + i + 1;
                });
            }).draw();
        </script>
    @endforeach
    @if (count($data_jurusan) == '0')
        <script>
            var id_penerimaan = {!! json_encode($data_penerimaan->id_penerimaan) !!};
            var id_jurusan = '0';

            var modul_url = 'report';
            var datatable_detail_url = base_url + '/' + role_url + '/' + modul_url + '/' +
                'report-pendaftaran/detail/datatables/' + id_penerimaan + '/0';

            var primary_table_0 = $('#primary_table_0').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                ajax: {
                    url: datatable_detail_url,
                    type: 'GET'
                },
                columns: [{
                        data: null,
                        searchable: false,
                        orderable: false
                    },
                    // {
                    //     data: 'nomor_ujian',
                    //     name: 'calon_siswa_baru.nomor_ujian'
                    // },
                    {
                        data: 'kode_voucher',
                        name: 'calon_siswa_baru.kode_voucher'
                    },
                    {
                        data: 'nm_c_siswa',
                        name: 'calon_siswa_baru.nm_c_siswa'
                    },
                    {
                        data: 'nomor_hp',
                        name: 'calon_siswa_baru.nomor_hp'
                    },
                    {
                        data: 'nomor_hp_ortu',
                        name: 'calon_siswa_ortu.nomor_hp_ortu'
                    },
                    {
                        data: 'nm_sekolah_asal',
                        name: 'calon_siswa_sekolah.nm_sekolah_asal'
                    },
                    {
                        data: 'nm_ayah',
                        name: 'calon_siswa_ortu.nm_ayah'
                    },
                    {
                        data: 'nm_ibu',
                        name: 'calon_siswa_ortu.nm_ibu'
                    },
                    {
                        data: 'nomor_telp_ortu',
                        name: 'calon_siswa_ortu.nomor_telp_ortu'
                    }
                ]
            });

            primary_table_0.on('draw', function() {
                primary_table_0.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    var start = this.page.info().page * 10;
                    cell.innerHTML = start + i + 1;
                });
            }).draw();
        </script>
    @endif

@endif
<script>
    var modul_url = 'report';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'report-pendaftaran/datatables';
    var rekap_url = role_url + '#' + modul_url + '/' + 'report-pendaftaran/rekap';
    var detail_url = role_url + '#' + modul_url + '/' + 'report-pendaftaran/detail';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tahun_penerimaan',
                name: 'tahun_penerimaan'
            },
            {
                data: 'nm_penerimaan',
                name: 'nm_penerimaan'
            },
            {
                data: 'gelombang_penerimaan',
                name: 'gelombang_penerimaan'
            },
            {
                data: 'jumlah_submit_form',
                name: 'jumlah_submit_form'
            },
            {
                data: 'jumlah_antri_verifikasi',
                name: 'jumlah_antri_verifikasi'
            },
            {
                data: 'jumlah_verifikasi_kembali',
                name: 'jumlah_verifikasi_kembali'
            },
            {
                data: 'jumlah_verifikasi',
                name: 'jumlah_verifikasi'
            },
            {
                data: 'jumlah_bayar',
                name: 'jumlah_bayar'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a href="' + rekap_url + '/' + data.id +
                        '"><i class="material-icons">folder_open</i></a> <a href="' + detail_url + '/' +
                        data.id + '"><i class="material-icons">details</i></a> ';
                }
            }
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * 10;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
