<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if (auth_data()->role_aktif->id_role != '7')
                <a type="button" class="btn btn-success" style=" margin-right: 10px"
                    href="{{ url('guru#e-learning-soal/paket-soal/manage') }}">
                    <i class="material-icons">add_box</i>
                    <span>Tambah Paket Soal</span>
                </a>
                <a type="button" class="btn btn-success" style=" margin-right: 10px"
                    href="{{ url('guru#e-learning-soal/paket-soal/bank-soal') }}">
                    <i class="material-icons">collections_bookmark</i>
                    <span>Bank Soal</span>
                </a>
                <a type="button" class="btn btn-primary" style=" margin-right: 10px"
                    href="{{ url('guru#e-learning-soal/paket-soal/bank-soal/kategori') }}">
                    <i class="material-icons">settings</i>
                    <span>Mata Pelajaran</span>
                </a>
            @endif

            <input type="checkbox" id="data_alumni" class="checkbox">
            <label for="data_alumni">Sudah Dikerjakan</label>
            <input type="hidden" id="status" value="0">
            <br><br>
            <div class="card">
                <div class="header">
                    <h2>
                        List Paket Soal
                    </h2>

                    {{-- <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{url('guru#e-learning-soal/paket-soal/manage')}}">Adding new package</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Soal</th>
                                    <th>Soal type Sub</th>
                                    <th>Tambah Soal</th>
                                    {{-- <th>Total Answer</th> --}}
                                    <th>Poin Soal</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Durasi Pengerjaan</th>
                                    <th>Versi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.checkbox').on('change', function() {
        if (this.checked) {
            $('#status').val(1);
            primary_table.draw();
        } else {
            $('#status').val(0);
            primary_table.draw();
        }
    });

    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/table';
    var detail_url = role_url + '#' + modul_url + '/' + 'paket-soal';
    var delete_url = role_url + '/' + modul_url + '/' + 'paket-soal/delete';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        // serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(d) {
                d.status = $('#status').val()
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'text',
                name: 'text'
            },
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    // alert(data.nm_kelas); 
                    let html = '';
                    data.nm_kelas.forEach(element => {
                        html += '- ' +
                            element + ` <br>`;
                    });
                    return html;

                }
            },
            {
                data: 'kategori_soal.nm_kategori_soal'
            },
            {
                data: 'question',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return data.soal;
                }

            },
            {
                data: 'question',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return data.soalsub;
                }

            },
            {
                data: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.status == 0) {
                        return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/detail/' + data.id + '">' +
                            '    <i class="material-icons">add</i>' +
                            '</a>';
                    } else {
                        return '';
                    }

                }
            },
            // { data: 'total_answer', name: 'total_answer', searchable: false, orderable: false },
            {
                data: 'question',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return data.nilai;
                }
            }, {
                data: 'waktu_mulai',
                className: 'align-center'
            }, {
                data: 'waktu_selesai',
                className: 'align-center'
            }, {
                data: 'waktu_pengerjaan',
                className: 'align-center'
            },
            {
                data: 'version',
                className: 'align-center'
            },
            {
                data: 'action',

                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.status == 0) {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/manage/' + data.id + '">' +
                            '    <i class="material-icons">mode_edit</i>' +
                            '</a>' +
                            '<a type="button" class="btn btn-orange btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/test/' + data.id + '">' +
                            '    T' +
                            '</a>' +
                            '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="' +
                            data.id + '" onclick="actionDelete(this)">' +
                            '    <i class="material-icons">delete</i>' +
                            '</button>';
                    } else {
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/manage/' + data.id + '">' +
                            '    <i class="material-icons">mode_edit</i>' +
                            '</a>' +
                            '<a type="button" class="btn btn-orange btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/test/' + data.id + '">' +
                            '    T' +
                            '</a>';

                    }

                }
            }
        ],
        order: [
            [2, 'asc'],
            [1, 'asc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        });
    }).draw();


    function actionDelete(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url;
        vex.dialog.confirm({
            message: 'Are you sure to delete this item?',
            callback: function(value) {
                if (value) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            question_package_id: item.attr('data-id')
                        },
                        success: function(data) {
                            vex.dialog.alert(data.message);
                            setTimeout(() => {
                                // $('.primary_table').DataTable().ajax.reload(null, false);
                                // primary_table.ajax.reload(null, false);
                                primary_table.ajax.reload(null, false);
                                //    location.reload();
                            }, 2000);

                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    item.prop('disabled', false);
                }
            }
        })
    }
</script>
