<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#jurnal-pimpinan/tambah-jurnal-pimpinan') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA PENGGUNA</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/tambah-jurnal-pimpinan/action-setting-jurnal-pimpinan/add/0') }}">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                            class="material-icons">save</i><span>Save</span></button>
                                </div>
                            </div>
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all" type="checkbox" name="select_all"
                                                class="filled-in">
                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Unit Kerja</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'jurnal-pimpinan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'tambah-jurnal-pimpinan/datatablesJurnalPimpinan';

    var primary_table = $('#primary_table').DataTable({
        'iDisplayLength': 100,
        processing: true,
        // serverSide: true,
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
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<input id="checkbox-' + data.id +
                        '" type="checkbox" name="id_pengguna[]" class="filled-in" value="' + data.id +
                        '">' +
                        '<label for="checkbox-' + data.id + '"></label>';

                }
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nip_pengguna',
                name: 'nip_pengguna',
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_unit_kerja',
                name: 'unit_kerja.nm_unit_kerja',
                searchable: false,
                orderable: false
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
        /* Select All Checkbox */
        $('input[name="select_all"]').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({
                'search': 'applied'
            }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>
