<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Laporan Kerja Harian Kelompok</h2>
                </div>
                <div class="body">
                    <div
                        style="display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 10px">
                        <div class="">
                            <label for="filterNama">Filter Nama:</label>
                            <form action="{{ url()->current() }}" method="GET"
                                style="display: flex; align-items: center">
                                <div class="row clearfix">
                                    <div class="col-md-4" style="width: 100%;">
                                        <select id="filterNama" name="id_pengguna" class="form-control">
                                            <option value="">-- Pilih Nama --</option>
                                            @foreach ($data as $d)
                                                <option value="{{ $d->id_pengguna }}"
                                                    {{ request('id_pengguna') == $d->id_pengguna ? 'selected' : '' }}>
                                                    {{ $d->nm_pengguna }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <button id="tombol-print" class="hidden btn btn-warning" title="mode baca">
                            <i class="material-icons">print</i>
                        </button>

                    </div>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Tanggal</th>
                                    <th colspan="2" style="vertical-align : middle;text-align:center;">Kategori</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Uraian
                                        Kegiatan</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Tuntas</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">File</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">Jenis</th>
                                    <th style="vertical-align : middle;text-align:center;">Mapel</th>
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
    $(document).ready(function() {
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

            window.location.href = "/tendik/laporan/kerja-harian/print-kerja-harian/" + start_date +
                "/" + end_date;
        })

        var modul_url = 'mpmp';
        var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'laporan-mgmp/datatables';
        var preview_file_url = role_url + '#' + modul_url + '/' + 'laporan-mgmp/preview-file';
        var download_file_url = role_url + '/' + modul_url + '/' + 'laporan-mgmp/download-file';


        var primary_table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            dom: 'Bfrtip',
            lengthMenu: dtLengButton,
            buttons: dtButtonConfig,
            ajax: {
                url: datatable_url,
                type: 'GET',
                data: function(d) {
                    d.id_pengguna = $('#filterNama').val();
                }
            },
            columns: [{
                    data: 'index_column',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'tanggal',
                    name: 'tanggal'
                },
                {
                    data: 'jenis',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'mapel.category_file_name',
                    class: 'text-center',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'keterangan_progres',
                    name: 'keterangan_progres',
                    render: function(data) {
                        return '<span style="white-space:normal">' + data + "</span>";
                    }
                },
                {
                    data: 'action',
                    class: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return data.status == 1 ? 'selesai' : 'belum selesai';
                    }
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
                                data.file + '" onclick="open_modal(\'' + data.id +
                                '\' , this)">' +
                                '<i class="material-icons">insert_drive_file</i></a>';
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: 'nm_pengguna',
                    name: 'nm_pengguna',
                    orderable: false
                }
            ]
        });


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


        $('#filterNama').on('change', function() {
            primary_table.ajax.reload();
            if ($("#filterNama").val()) {
                $("#tombol-print").removeClass("hidden");
            } else {
                $("#tombol-print").addClass("hidden");
            }
        });

        $('#tombol-print').click(function() {
            console.log($('#filterNama').val());
            window.open(`${base_url}/akademik#mpmp/laporan-mgmp/${$('#filterNama').val()}`, '_blank')

        })


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
    })
</script>
