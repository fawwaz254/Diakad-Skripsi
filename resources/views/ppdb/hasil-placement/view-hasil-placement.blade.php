<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/upload') }}"><i
                    class="material-icons">add</i><span>Tambah Hasil Placement</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Hasil Placement</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">No</th>
                                    <th style="vertical-align : middle;text-align:center;">Nomor Pendaftaran</th>
                                    <th style="vertical-align : middle;text-align:center;">Nama</th>
                                    <th style="vertical-align : middle;text-align:center;">No HP</th>
                                    <th style="vertical-align : middle;text-align:center;">Asal Sekolah</th>
                                    <th style="vertical-align : middle;text-align:center;">File</th>
                                    <th style="vertical-align : middle;text-align:center;">Action</th>
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

<div class="modal fade" id="modal-opsi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Opsi File</h4>
            </div>
            <div class="modal-body">

                <center id="place">


                </center>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var modul_url = 'report';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-hasil-placement/datatables';
    // var edit_url = role_url + '#' + modul_url + '/' + 'input-hasil-placement/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'input-hasil-placement/action-hasil-placement/delete';
    var preview_file_url = role_url + '#' + modul_url + '/' + 'input-hasil-placement/preview-file';
    // var download_file_url = role_url + '/' + modul_url + '/' + 'tambah-jurnal-harian/download-file';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
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
                data: 'nm_sekolah_asal',
                name: 'calon_siswa_sekolah.nm_sekolah_asal'
            },
            {
                data: 'action',
                name: 'file',
                class: 'text-center',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.file) {
                        return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-link="' +
                            data.file + '" onclick="open_modal(\'' + data.id + '\' , this)">' +
                            '    <i class="material-icons">insert_drive_file</i>' +
                            '</a> '
                    } else {
                        return `-`;
                    }
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
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
            primary_table.cell(cell).invalidate('dom');
        });
    }).draw();

    function open_modal(id, element) {

        var item = $(element);
        $('#place').empty();
        $('#place').append(`
            <a href="` + preview_file_url + `/` + id + `" target="_blank"><button type="button" data-color="pink" class="btn bg-pink waves-effect"> <i class="material-icons">visibility</i><span>Preview File</span></button></a>
        `);
        $('#modal-opsi').modal('show');
    }
</script>
