<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TUNGGAKAN Alumni
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Masuk Siswa
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                                @foreach ($thn_masuk_siswa as $thn)
                                    <option value="{{ $thn }}">
                                        {{ $thn }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Masuk</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No </th>
                                    <th>Nis</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tahun Masuk</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center">List Detail Tagihan</h4>
            </div>
            <div id="place">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var datatable_url = base_url + '/' + role_url + '/sim/spp/tunggakanAlumni/datatables';
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params) {
                params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
            },
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa',
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'siswa.last_kelas_siswa.kelas.nm_kelas',
                name: 'siswa.last_kelas_siswa.kelas.nm_kelas'
            },
            {
                data: 'thn_masuk_siswa',
                name: 'thn_masuk_siswa'
            },
            {
                data: 'total_biaya',
                name: 'total_biaya'
            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="detailAction(this)"  data-id="' +
                        data.id + '">' +
                        '    <i class="material-icons">pageview</i>' +
                        '</button>';
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
        });
    }).draw();


    function filterAction() {
        primary_table.ajax.reload(null, false);
    }

    function detailAction(el) {
        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/tunggakanAlumni/get-detail-data-tungakan-alumni`,
            data: {
                id_siswa: $(el).attr('data-id'),
            },
            success: function(response) {
                $('#place').html('');
                var html = '<table  class="table">';
                html += '<tr>';
                html += '<th>No</th>';
                html += '<th>Kelas</th>';
                html += '<th>Bulan</th>';
                html += '<th>Tahun Ajaran</th>';
                html += '<th>Tagihan</th>';
                html += '</tr>';
                $.each(response, function(key, item) {
                    html += '<tr>';
                    html += '<td>' + (key + 1) + '</td>';
                    html += '<td>' + item.kelas.nm_kelas + '</td>';
                    html += '<td>' + item.detail_biaya.bulan.nm_bulan + '</td>';
                    html += '<td>' + item.detail_biaya.biaya_sekolah.semester.tahun_ajaran +
                        '</td>';
                    html += '<td>' + item.besar_biaya + '</td>';
                    html += '<tr>';
                    html += '</tr>';
                });
                html += '</table>';
                $('#place').html(html);
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
                $('#myModal').modal('show');
            }
        });
    }
</script>
