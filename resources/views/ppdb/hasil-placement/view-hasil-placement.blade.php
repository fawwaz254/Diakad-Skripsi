{{-- <div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>HASIL PLACEMENT</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/view-hasil-placement') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" name="id_penerimaan" id="select-penerimaan">
                                    <option value="">- Pilih Penerimaan -</option>
                                    @foreach ($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                        @foreach ($grup_penerimaan as $semester => $datapergrup)
                                            <optgroup label="{{ $tahun }} {{ $semester }}">
                                                @foreach ($datapergrup as $data)
                                                    @if ($mode == 'show')
                                                        @if ($data->id_penerimaan == $penerimaan->id_penerimaan)
                                                            <option value="{{ $data->id_penerimaan }}" selected>
                                                                {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $data->id_penerimaan }}">
                                                                {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $data->id_penerimaan }}">
                                                            {{ $data->nm_penerimaan . ' ' . ($data->gelombang_penerimaan == '0' ? 'Inden' : $data->gelombang_penerimaan) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect"><i
                                class="material-icons">save</i><span>View Hasil Placement</span></button>
                    </form>
                </div>
            </div>
            <br>
            @if ($mode == 'show')
                <div class="card">
                    <div class="body">
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/input-hasil-placement/hasil-placement') }}">
                            {{ csrf_field() }}
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                    id="primary_table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor Pendaftaran</th>
                                            <th>Nama</th>
                                            <th>No HP</th>
                                            <th>Asal Sekolah</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="hidden" name="id_penerimaan"
                                            value="{{ $penerimaan->id_penerimaan }}"></input>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@include('scriptjs') --}}

{{-- @if ($mode == 'show')
    <script>
        var id_penerimaan = {!! json_encode($penerimaan->id_penerimaan) !!};

        var modul_url = 'report';
        var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-hasil-placement/datatables/' +
            id_penerimaan;
        var _url = role_url + '#' + modul_url + '/' + 'input-hasil-placement/upload';

        var primary_table = $('#primary_table').DataTable({
            processing: true,
            // serverSide: true,
            pageLength: 100,
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
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            _url + '/' + data.id + '">' +
                            '<i class="material-icons">edit</i>' +
                            '</a> '
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
@endif --}}


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
    var modul_url = 'report';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-hasil-placement/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'input-hasil-placement/edit';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +'tambah-jurnal-harian/action-jurnal-harian/delete';
    // var preview_file_url = role_url + '#' + modul_url + '/' + 'tambah-jurnal-harian/preview-file';
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
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a> ';
                    // '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                    // delete_url + '\', this)" data-id="' + data.id + '">' +
                    // '    <i class="material-icons">delete_forever</i>' +
                    // '</button>';
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

    // function open_modal(id, element) {

    //     var item = $(element);
    //     $('#place').empty();
    //     $('#place').append(`
    //         <a href="` + preview_file_url + `/` + id + `" target="_blank"><button type="button" data-color="pink" class="btn bg-pink waves-effect"> <i class="material-icons">visibility</i><span>Preview File</span></button></a>
    //         <a href="` + download_file_url + `/` + id + `" target="_blank"><button type="button" data-color="indigo" class="btn bg-indigo waves-effect"> <i class="material-icons">file_download</i>
    //         <span>Download File</span></button></a>
    //     `);
    //     $('#modal-opsi').modal('show');
    // }
</script>
