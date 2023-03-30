<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/add') }}"><i
                    class="material-icons">note_add</i><span>Tambah Ketidaksesuaian SOP</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>DATA KETIDAKSESUAIAN SOP</h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Guru</th>
                                    <th>Catatan Pelanggaran</th>
                                    <th>Tanggal Pelanggaran</th>
                                    <th>File</th>
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
<script>
    $('#print').click(function() {
        $('#modal_print').modal('show');
    });

    $('#print_laporan').click(function() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        if (!start_date || !end_date) {
            alert('Mohon diisi start date dan end date terlebih dahulu');
            return;
        }

        window.location.href = "/tendik/laporan/kerja-harian/print-kerja-harian/" + start_date + "/" + end_date;
    })
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'ketidaksesuaian-sop';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-ketidaksesuaian-sop/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-pelanggaran/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-pelanggaran/delete';
    var preview_file_url = role_url + '#' + modul_url + '/' + 'input-ketidaksesuaian-sop/preview-file';
    var download_file_url = role_url + '/' + modul_url + '/' + 'laporan-mgmp/download-file';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: 'index_table',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'catatan_pelanggaran',
                name: 'catatan_pelanggaran'
            },
            {
                data: 'tgl_pelanggaran',
                name: 'tgl_pelanggaran',
                searchable: false,
                orderable: false,
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
                    if (data.is_sudah_tindakan == 1) {
                        return '<a>Sudah Ada Tindakan</a>';
                    } else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            edit_url + '/' + data.id + '">' +
                            '    <i class="material-icons">edit</i>' +
                            '</a>' +
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                            delete_url + '\', this)" data-id="' + data.id + '">' +
                            '    <i class="material-icons">delete_forever</i>' +
                            '</button>';
                    }
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
    <a href="` + download_file_url + `/` + id + `" target="_blank"><button type="button" data-color="indigo" class="btn bg-indigo waves-effect"> <i class="material-icons">file_download</i>
    <span>Download File</span></button></a>
`);
        $('#modal-opsi').modal('show');
    }
</script>
