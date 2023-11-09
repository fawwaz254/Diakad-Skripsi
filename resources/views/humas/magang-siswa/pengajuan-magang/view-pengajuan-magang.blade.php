<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect" onclick="pengajuanSiswaMagang()"><i
                    class="material-icons">note_add</i><span>Tambah Siswa Magang</span></a>
            <a class="btn bg-green waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/import-excel') }}"><i
                    class="material-icons">attach_file</i><span>Import Siswa Magang</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PENGAJUAN MAGANG
                    </h2>
                </div>
                <div class="body">

                    <div class="row clearfix">

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Rekanan Magang</label>
                            <select class="form-control show-tick" name="id_rekanan_magang" id="id_rekanan_magang"
                                required>
                                <option value="0"> Semua Rekanan </option>
                                @foreach ($data_rekanan_magang as $data)
                                    <option value="{{ $data->id_rekanan_magang }}">{{ $data->nm_rekanan_magang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Periode Magang</label>
                            <select class="form-control show-tick" name="id_periode_magang" id="id_periode_magang">
                                <option value="0"> Semua Periode </option>
                                @foreach ($data_periode_magang as $data)
                                    <option value="{{ $data->id_periode_magang }}"
                                        @if ($semester_aktif->id_semester == $data->id_semester) selected @endif>{{ $data->nm_magang }} -
                                        {{ $data->nm_periode_magang }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" onclick="filterData()"><i
                                    class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Rekanan Magang</th>
                                    <th>Periode Magang</th>
                                    <th>Semester</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Status Approval</th>
                                    <th>Status Magang</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-validation" method="post"
    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/pengajuan-magang/action-pengajuan-magang') }}">
    {{ csrf_field() }}

    <!-- Modal Pengajuan -->
    <div class="modal fade" id="modalMaster" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Pengajuan Magang</h4>
                </div>
                <input type="hidden" name="id_pengambilan_magang" id="id_pengambilan_magang">
                <div class="modal-body" id="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Pengajuan -->
</form>

@include('scriptjs')

<script type="text/javascript">
    function pengajuanSiswaMagang() {

        var periode_magang = $('#id_periode_magang').val();
        var rekanan_magang = $('#id_rekanan_magang').val();

        if (periode_magang == 0) {
            alert('silahkan pilih periode terlebih dahulu');
            return false;
        }
        if (rekanan_magang == 0) {
            alert('silahkan pilih rekanan terlebih dahulu');
            return false;
        }

        loadURI('magang-siswa/pengajuan-magang/add/' + $('#id_rekanan_magang').val() + "/" + periode_magang);
    }

    function filterData() {
        primary_table.draw();
    }

    var modul_url = 'magang-siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengajuan-magang/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_rekanan_magang = $('select[name=id_rekanan_magang]').val(),
                    d.id_periode_magang = $('select[name=id_periode_magang]').val()
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_rekanan_magang',
                name: 'nm_rekanan_magang'
            },
            {
                data: 'nm_periode_magang',
                name: 'nm_periode_magang'
            },
            {
                data: 'semester',
                name: 'semester'
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'status_apv_pengambilan_magang',
                name: 'status_apv_pengambilan_magang'
            },
            {
                data: 'status_magang',
                name: 'status_magang'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {

                    var html = '';

                    if (data.status_apv_pengambilan_magang == 2) {
                        html +=
                            '<button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                            data.id + '\', \`tidak-diapprove`\)">' +
                            '    <i class="material-icons">input</i>' +
                            '</button> ';
                    }

                    if (data.status_apv_pengambilan_magang == 1) {

                        html +=
                            '<button class="btn btn-success btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                            data.id + '\', \`pengubahan-status-magang`\)">' +
                            '    <i class="material-icons">input</i>' +
                            '</button> ';

                    }

                    html +=
                        ' <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                        data.id + '\', \`hapus-data`\)">' +
                        '    <i class="material-icons">delete</i>' +
                        '</button> ';
                    return html;
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

    function pengajuanAction(id_pengambilan_magang, mode) {
        $('#id_pengambilan_magang').val(id_pengambilan_magang);
        if (mode == 'pengajuan') {
            $('#modal-body').html(`
                <input  type="hidden" value="pengajuan" name="mode">
                <select class="form-control show-tick" name="status_apv_pengambilan_magang" required>
                  <option value="2">Waiting Approval</option>
                  <option value="1">Approve</option>
                </select>
            `)
        } else if (mode == 'tidak-diapprove') {
            $('#modal-body').html(`
                <input  type="hidden" value="tidak-diapprove" name="mode">
                <select class="form-control show-tick" name="status_apv_pengambilan_magang" required>
                  <option value="3">Tidak Di Approve</option>
                </select>

                <br>

                <label>Keterangan</label>
                <div class="form-group">
                    <div class="form-line">
                        <textarea rows="4" class="form-control no-resize" name="keterangan"></textarea>
                    </div>
                </div>

            `)
        } else if (mode == 'pengubahan-status-magang') {
            $('#modal-body').html(`

                <input  type="hidden" value="pengubahan-status-magang" name="mode">

                <select class="form-control show-tick" name="status_magang" required>
                  <option value="1">Selesai</option>
                  <option value="10">Batal</option>
                </select>

            `);
        } else if (mode == 'hapus-data') {
            $('#modal-body').html(`
                <input  type="hidden" value="hapus-data" name="mode">
                <p>Apakah anda yakin ingin menghapus data ini ?</p>
            `);
        }

        $('#modalMaster').modal('show');
    }
</script>
