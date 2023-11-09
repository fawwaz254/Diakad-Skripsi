<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#magang-siswa/pengajuan-magang') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PENGAJUAN SISWA MAGANG Rekanan : {{ $data_rekanan_magang->nm_rekanan_magang }} Periode :
                        {{ $data_periode_magang->nm_periode_magang }}
                    </h2>
                </div>
                <div class="body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
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
    <input type="hidden" value="{{ Request::segment(5) }}" name="id_rekanan_magang" id="rm">
    <input type="hidden" value="{{ Request::segment(6) }}" name="id_periode_magang" id="pm">

    <!-- Modal Pengajuan -->
    <div class="modal fade" id="modalMaster" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="defaultModalLabel">Pengajuan Magang</h4>
                </div>
                <input type="hidden" name="id_siswa" id="id_siswa">
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
    var modul_url = 'magang-siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengajuan-magang/datatables-list-siswa/' +
        $('#rm').val() + "/" + $('#pm').val();

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
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'pengguna.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'status_apv_pengambilan_magang',
                name: 'status_apv_pengambilan_magang'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.id_pengambilan_magang) {

                        var html = '';

                        if (data.status_apv_pengambilan_magang == 2) {
                            html +=
                                '<button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                                data.id + '\', \`tidak-diapprove`\)">' +
                                '    <i class="material-icons">input</i>' +
                                '</button> ';
                        }
                        html +=
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                            data.id + '\', \`hapus-data`\)">' +
                            '    <i class="material-icons">delete</i>' +
                            '</button> ';
                        return html;

                    } else {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="pengajuanAction(\'' +
                            data.id + '\', \`pengajuan`\)">' +
                            '    <i class="material-icons">input</i>' +
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
        });
    }).draw();

    function pengajuanAction(id_siswa, mode) {
        $('#id_siswa').val(id_siswa);
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
        } else if (mode == 'hapus-data') {
            $('#modal-body').html(`
                <input  type="hidden" value="hapus-data" name="mode">
                <p>Apakah anda yakin ingin menghapus data ini ?</p>
            `);
        }

        $('#modalMaster').modal('show');
    }
</script>
