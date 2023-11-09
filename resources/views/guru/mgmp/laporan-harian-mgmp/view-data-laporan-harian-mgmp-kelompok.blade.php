<div class="container-fluid">
    {{-- <div class="block-header">
        <h2>
            <button class="btn btn-success" id="print"><i class="material-icons">print</i> Print Laporan Kerja Harian</button>
        </h2>
    </div> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Laporan Kerja Harian Kelompok</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">No</th>
                                    <th style="vertical-align : middle;text-align:center;">Nama Mapel</th>
                                    <th style="vertical-align : middle;text-align:center;">Guru yang sudah selesai</th>
                                    <th style="vertical-align : middle;text-align:center;">Jenjang kelas</th>
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

<div class="modal" id="modal_print" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Laporan Kerja Harian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-6">
                        <label>
                            Tanggal Awal
                        </label>
                        <input type="date" class="datepicker form-control" id="start_date" name="start_date"
                            required="" aria-required="true" aria-invalid="true">
                    </div>

                    <div class="col-md-6">
                        <label>
                            Tanggal Akhir
                        </label>
                        <input type="date" class="datepicker form-control" id="end_date" name="end_date"
                            required="" aria-required="true" aria-invalid="true">
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" id="print_laporan" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
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

    var modul_url = 'mgmp';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'laporan-kelompok-mgmp/datatables';
    var detail_url = role_url + '#' + modul_url + '/' + 'laporan-kelompok-mgmp/detail';
    var preview_file_url = role_url + '#' + modul_url + '/' + 'kerja-harian/preview-file';

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
                data: 'index_column',
                class: 'text-center',
                defaultContent: '',
                searchable: false,
                orderable: false
            },
            {
                data: 'category_file_name',
                name: 'category_file_name'
            },
            {
                data: 'selesai',
                name: 'selesai',
                render: function(data) {
                    var role_text = '';
                    data.forEach(d => {
                        if (d.total > 0) {
                            role_text +=
                                `<li>${d.pengguna}  |  Total : (${d.total}) </li>`
                        }
                    });
                    return `<ul>${role_text}</ul>`
                }
            },
            {
                data: 'category_file_explanation',
                class: 'text-center',
                name: 'category_file_explanation'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/' + data.id + '">' +
                        '    <i class="material-icons">pageview</i>' +
                        '</a> ';
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
            primary_table.cell(cell).invalidate('dom');
        });
    }).draw();

    function open_modal(id, element) {

        var item = $(element);
        $('#place').empty();
        $('#place').append(`
            <a href="` + preview_file_url + `/` + id + `" target="_blank"><button type="button" data-color="pink" class="btn bg-pink waves-effect"> <i class="material-icons">visibility</i><span>Preveiw File</span></button></a>
            <a href="` + item.attr('data-link') + `" target="_blank"><button type="button" data-color="indigo" class="btn bg-indigo waves-effect"> <i class="material-icons">file_download</i>
            <span>Download File</span></button></a>
        `);
        $('#modal-opsi').modal('show');
    }
</script>
