<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-keuangan/detail-biaya-internal/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Detail Biaya Internal</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA DETAIL BIAYA INTERNAL</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row clearfix">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <h2 class="card-inside-title">
                                        Biaya Internal
                                    </h2>
                                    <select class="form-control show-tick" name="kelompok_biaya_internal">
                                        <option value="">Semua Biaya Internal</option>
                                        @foreach ($data_kelompok_biaya_internal as $data)
                                            <option value="{{ $data->id_kelompok_biaya_internal }}">
                                                {{ $data->nm_kelompok_biaya_internal }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Kelompok Biaya</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Biaya Internal</th>
                                    <th>Nama Detail Biaya Internal</th>
                                    <th>Besar Biaya</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'detail-biaya-internal/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'detail-biaya-internal/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-detail-biaya-internal/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(params) {
                params.kelompok_biaya_internal = $('select[name=kelompok_biaya_internal]').val();
                params.kelompok_biaya = $('select[name=kelompok_biaya]').val();
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_biaya_internal',
                name: 'kelompok_biaya_internal.nm_kelompok_biaya_internal'
            },
            {
                data: 'nm_detail_biaya_internal',
                name: 'detail_biaya_internal.nm_detail_biaya_internal'
            },
            {
                data: 'besar_biaya',
                name: 'detail_biaya_internal.besar_biaya'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
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
