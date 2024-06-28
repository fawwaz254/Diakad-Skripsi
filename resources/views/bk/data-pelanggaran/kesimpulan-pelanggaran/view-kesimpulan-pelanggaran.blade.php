<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-pelanggaran/kesimpulan-pelanggaran/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Kesimpulan Pelanggaran</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA KESIMPULAN PELANGGARAN</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kesimpulan</th>
                                    <th>Poin Bawah</th>
                                    <th>Poin Atas</th>
                                    <th>Kesimpulan Pelanggaran 1</th>
                                    <th>Kesimpulan Pelanggaran 2</th>
                                    <th>Kesimpulan Pelanggaran 3</th>
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
    var modul_url = 'data-pelanggaran';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'kesimpulan-pelanggaran/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'kesimpulan-pelanggaran/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-kesimpulan-pelanggaran/delete';


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
                data: 'nm_kesimpulan_pelanggaran',
                name: 'nm_kesimpulan_pelanggaran'
            },
            {
                data: 'poin_bawah_kesimpulan_pelanggaran',
                name: 'poin_bawah_kesimpulan_pelanggaran'
            },
            {
                data: 'poin_atas_kesimpulan_pelanggaran',
                name: 'poin_atas_kesimpulan_pelanggaran'
            },
            {
                data: 'deskripsi_kesimpulan_pelanggaran_1',
                name: 'deskripsi_kesimpulan_pelanggaran_1'
            },
            {
                data: 'deskripsi_kesimpulan_pelanggaran_2',
                name: 'deskripsi_kesimpulan_pelanggaran_2'
            },
            {
                data: 'deskripsi_kesimpulan_pelanggaran_3',
                name: 'deskripsi_kesimpulan_pelanggaran_3'
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
</script>
