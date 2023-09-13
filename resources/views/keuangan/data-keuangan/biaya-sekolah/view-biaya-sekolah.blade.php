<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-keuangan/biaya-sekolah/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Biaya Sekolah</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA BIAYA SEKOLAH</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                                @foreach ($data_semester as $semester)
                                    <option value="{{ $semester->thn_akademik_semester }}"
                                        @if ($semester->thn_akademik_semester == $tahun_akademik_semester) selected @endif>
                                        {{ $semester->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelompok Biaya
                            </h2>
                            <select class="form-control show-tick" name="kelompok_biaya">
                                <option value="">Semua Kelompok Biaya</option>
                                @foreach ($data_kelompok_biaya as $data)
                                    <option value="{{ $data->id_kelompok_biaya }}">
                                        {{ $data->nm_kelompok_biaya }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Ajaran/Kelompok
                                    Biaya</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelompok Biaya</th>
                                    <th>Semester</th>
                                    <!--    <th>Jalur</th> -->
                                    <th>Besar Biaya</th>
                                    <th>Validasi</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a class="target-link btn btn-block bg-blue waves-effect"
                                href="{{ url(Request::segment(1) . '#data-keuangan/biaya-sekolah/copy') }}"><i
                                    class="material-icons">file_copy</i><span>Copy Biaya Sekolah & Detail
                                    Biaya</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'data-keuangan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'biaya-sekolah/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'biaya-sekolah/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-biaya-sekolah/delete';

    var detail_biaya_url = role_url + '#' + modul_url + '/' + 'biaya-sekolah/detail-biaya';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(params) {
                params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
                params.kelompok_biaya = $('select[name=kelompok_biaya]').val();
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_kelompok_biaya',
                name: 'kelompok_biaya.nm_kelompok_biaya'
            },
            {
                data: 'semester',
                name: 'semester.nm_semester'
            },
            // { data: 'jalur', name: 'jalur.nm_jalur' },
            {
                data: 'besar_biaya_sekolah',
                name: 'biaya_sekolah.besar_biaya_sekolah',
                searchable: false,
                orderable: false
            },
            {
                data: 'validasi_biaya_sekolah',
                searchable: false,
                orderable: false
            },
            {
                data: 'keterangan_biaya_sekolah',
                name: 'biaya_sekolah.keterangan_biaya_sekolah'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_biaya_url + '/' + data.id + '">' +
                        '    <i class="material-icons">remove_red_eye</i>' +
                        '</a> ' +
                        '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
                        '</button>';
                }
            }
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

    function filterAction() {
        primary_table.ajax.reload(null, false);
    }
</script>
