<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CARI DATA BUKU INDUK BERDASARKAN KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-cetak-by-kelas') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Pilih Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select name="id_kelas" id="id_kelas" class="form-control">
                                    <option value="" selected disabled>-- PILIH KELAS --</option>
                                    @foreach ($kelas as $kls)
                                        <option
                                            value="{{ $kls['id_kelas'] }}"{{ isset($id_kelas) && $id_kelas == $kls['id_kelas'] ? 'selected' : '' }}>
                                            {{ $kls['nm_kelas'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                            @if (isset($id_kelas))
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <a class="btn btn-block bg-blue waves-effect" target="_blank"
                                        href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cetak-by-kelas/cetak-all-kelas/' . $id_kelas) }}"><i
                                            class="material-icons">print</i><span> Cetak Semua Kelas</span></a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>

                @if ($id_kelas != null)
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Jalur Masuk</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url = 'buku-induk';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-by-kelas/datatables/' + id_kelas;
    var print_url = role_url + '/' + modul_url + '/' + 'cetak-by-kelas/print-rapor';

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
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'nm_status_pengguna',
                name: 'nm_status_pengguna'
            },
            {
                data: 'nm_jalur',
                name: 'nm_jalur'
            },
            {
                data: 'nis_siswa',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="btn bg-red btn-circle waves-effect waves-circle waves-float" target="_blank" href="' +
                        print_url + '/' + data + '">' +
                        '    <i class="material-icons">print</i>' +
                        '</a>'

                    ;
                }
            },
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
