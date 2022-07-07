<style>
    #expired {
        background: red;
        color: white;
        padding: 10px;
    }

    #will-expired {
        background: yellow;
        color: white;
        padding: 10px;
    }

    #not-expired {
        background: green;
        color: white;
        padding: 10px;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/add') }}">
                <i class="material-icons">note_add</i><span>Tambah Kerjasama</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA KERJASAMA</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th> No</th>
                                    <th> Nama Kerjasama </th>
                                    <th> Instansi </th>
                                    <th> Status Kerjasama</th>
                                    <th> Jenis Kerjasama </th>
                                    <th> Tanggal Awal Kerjasama </th>
                                    <th> Tanggal Akhir Kerjasama </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var modul_url = '{{ Request::segment(2) }}';

    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/datatables';
    var edit_url = role_url + '#' + modul_url + '/edit';
    var detail_url = role_url + '#' + modul_url + '/show';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/delete';
    var upload_url = role_url + '#' + modul_url + '/' + 'berkas/add';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_kerjasama'
            },
            {
                data: 'instansi'
            },
            {
                data: 'status_kadaluarsa',
                render: function(data) {
                    // console.log(data.status_kadaluarsa)
                    if (data.status_kadaluarsa == 10) {
                        return `<span id="expired">Sudah Expired</span>`
                    }
                    if (data.status_kadaluarsa >= 1) {
                        return `<span id="not-expired">Belum Expired</span>`
                    }
                    if (data.status_kadaluarsa == 1) {
                        return `<span id="will-expired">Akan Expired</span>`
                    }
                    if (data.status_kadaluarsa == 0) {
                        return `<span id="will-expired">Akan Expired</span>`
                    }
                }
            },
            {
                data: 'jenis_kerjasama'
            },
            {
                data: 'tanggal_kerjasama'
            },
            {
                data: 'tanggal_akhir_kerjasama',
            },
            {
                data: 'status'
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
                        '</button>' +
                        '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" style="margin-left: 5px" href="' +
                        upload_url + '/' + data.id + '">' +
                        '    <i class="material-icons">cloud_upload</i>' +
                        '</a>';
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
</script>
