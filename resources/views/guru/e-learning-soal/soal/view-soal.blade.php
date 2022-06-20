<div class="container-fluid">
    <div class="row clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url('guru#e-learning-soal/soal/new/pilihan-ganda') }}">
                <i class="material-icons">add_box</i>
                <span>Tambah Pilihan Ganda</span>
            </a>
            <a type="button" class="btn btn-success" style="margin-bottom: 15px"
                href="{{ url('guru#e-learning-soal/soal/new/essay') }}">
                <i class="material-icons">add_box</i>
                <span>Tambah Soal Essay</span>
            </a>
            <div class="card">
                <div class="header">
                    <h2>
                        Bank Soal
                        {{-- <a href=""> <i class="material-icons">add_box</i></a> --}}
                    </h2>
                    {{-- <button type="button" class="btn btn-success">Tambah Soal</button> --}}
                    {{-- <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="{{url('guru#e-learning-soal/soal/new')}}">tambah soal baru</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>
                <div class="body">


                    {{-- <button type="button" class="btn btn-success" style="margin-bottom: 15px"><i class="material-icons"></i> Tambah Soal</button> --}}
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tipe Soal</th>
                                    <th>Pembuat</th>
                                    <th>Pertanyaan</th>
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
    <!-- #END# Basic Examples -->
</div>
@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'soal/table';
    var detail_url = role_url + '#' + modul_url + '/' + 'soal';
    var delete_url = role_url + '/' + modul_url + '/' + 'soal';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tipe_soal'
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'text',
                name: 'text',
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/edit/' + data.id + '">' +
                        '    <i class="material-icons">mode_edit</i>' +
                        '</a>' +
                        '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/test/' + data.id + '">' +
                        '    <i class="material-icons">reorder</i>' +
                        '</a>' +
                        '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="' +
                        data.id + '" onclick="actionDelete(this)">' +
                        '    <i class="material-icons">delete</i>' +
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
            cell.innerHTML = i + 1;
        });
    }).draw();

    function actionDelete(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url + '/delete';
        // var url = '{{ url('organizer/question/delete') }}';
        vex.dialog.confirm({
            message: 'Apakah yakin mau menghapus soal?',
            callback: function(value) {
                // alert(url)
                if (value) {
                    // alert(url);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            id_soal: item.attr('data-id')
                        },
                        success: function(data) {
                            vex.dialog.alert(data.message);
                            setTimeout(() => {
                                // $('.primary_table').DataTable().ajax.reload(null, false);
                                // primary_table.ajax.reload(null, false);
                                primary_table.ajax.reload(null, false);
                                //    location.reload();
                            }, 2000)
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
