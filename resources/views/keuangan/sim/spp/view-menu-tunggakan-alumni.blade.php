<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TUNGGAKAN ALUMNI
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
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
                                    <th>Tunggakan</th>
                                    <th>Tunggakan Data Lama</th>
                                    <th>Selisih</th>
                                    <th>Total Tagihan</th>
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
    var delete_url = base_url + '/' + role_url + '/sim/spp/tunggakanAlumni/delete';
    var selisih = 0;
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
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
                data: 'nm_kelas',
                name: 'nm_kelas'
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
                data: 'tunggakan.jumlah_tunggakan',
                name: 'tunggakan.jumlah_tunggakan'
            },
            {
                data: 'tunggakan.selisih',
                name: 'tunggakan.selisih'
            },
            {
                data: 'total_tagihan',
                name: 'total_tagihan'
            },
            {
                data: 'tunggakan',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.selisih == '-') {
                        return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="detailAction(this)"  data-id="' +
                            data.action + '"  data-status="-" >' +
                            '    <i class="material-icons">pageview</i>' +
                            '</button>';
                    } else {
                        return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="detailAction(this)"  data-id="' +
                            data.action + '" data-status="' +
                            data.selisih + '" >' +
                            '    <i class="material-icons">pageview</i>' +
                            '</button>';
                    }
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

    function deleteTagihan(el) {
        var item = $(el);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id') + '/' + selisih,
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                            $('#place').html('');
                            var html = '<h4 style="text-align: center"> Selisih : ' + response
                                .selisih + '</h4>';
                            selisih = response.selisih;
                            html += '<table class="table">';
                            html += '<tr>';
                            html += '<th>No</th>';
                            html += '<th>Kelas</th>';
                            html += '<th>Bulan</th>';
                            html += '<th>Tahun Ajaran</th>';
                            html += '<th>Tagihan</th>';
                            if (response.selisih != '0') {
                                html += '<th>Action</th>'
                            };
                            html += '</tr>';
                            $.each(response.data, function(key,
                                item) {
                                html += '<tr>';
                                html += '<td>' + (key + 1) + '</td>';
                                html += '<td>' + item.kelas.nm_kelas + '</td>';
                                html += '<td>' + item.detail_biaya.bulan.nm_bulan + '</td>';
                                html += '<td>' + item.detail_biaya.biaya_sekolah.semester
                                    .tahun_ajaran +
                                    '</td>';
                                html += '<td>Rp ' + item.besar_biaya.toLocaleString() +
                                    '</td>';
                                if (response.selisih > '0') {
                                    html +=
                                        '<td><button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteTagihan(this)"  data-id="' +
                                        item.id_tagihan_biaya + '">' +
                                        '    <i class="material-icons">delete</i>' +
                                        '</button></td>'
                                };
                                html += '</tr>';
                            });
                            html += '</table>';
                            $('#place').html(html);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function detailAction(el) {
        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/tunggakanAlumni/get-detail-data-tungakan-alumni`,
            data: {
                id_siswa: $(el).attr('data-id'),
                status: $(el).attr('data-status'),
            },
            success: function(response) {
                $('#place').html('');
                if (response['status'] != '-') {
                    var html = '<h4 style="text-align: center"> Selisih : ' + response['status'] + '</h4>';
                    selisih = response['status'];
                    html += '<table class="table">';
                } else {
                    var html = '<table  class="table">';
                }
                html += '<tr>';
                html += '<th>No</th>';
                html += '<th>Kelas</th>';
                html += '<th>Bulan</th>';
                html += '<th>Tahun Ajaran</th>';
                html += '<th>Tagihan</th>';
                if (response['status'] != '-') {
                    html += '<th>Action</th>'
                };
                html += '</tr>';
                $.each(response['data_tagihan_siswa_semester_lalu'], function(key, item) {
                    html += '<tr>';
                    html += '<td>' + (key + 1) + '</td>';
                    html += '<td>' + item.kelas.nm_kelas || '-' + '</td>';
                    html += '<td>' + item.detail_biaya.bulan.nm_bulan + '</td>';
                    html += '<td>' + item.detail_biaya.biaya_sekolah.semester.tahun_ajaran +
                        '</td>';
                    html += '<td>Rp ' + item.besar_biaya.toLocaleString() + '</td>';
                    if (response['status'] != '-') {
                        html +=
                            '<td><button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteTagihan(this)"  data-id="' +
                            item.id_tagihan_biaya + '">' +
                            '    <i class="material-icons">delete</i>' +
                            '</button></td>'
                    };

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