<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/soal') }}">
                <i class="material-icons">keyboard_backspace</i>
                <span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="card">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="header bg-pink">
                    <h2>
                        Tambah Kategori
                    </h2>
                </div>
                <div class="body">
                    <form class="form-validation" id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/soal/kategori') }}">
                        {{ csrf_field() }}

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Nama Kategori (Mata Pelajaran + Tingkat + Jurusan) </h2>
                            <input type="text" class="form-control" name="nm_kategori_soal" required=""
                                aria-required="true" aria-invalid="true">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <button id="submit" class="btn btn-block bg-blue waves-effect" type="submit"
                                style="margin-top: 30px">
                                <i class="material-icons">add</i><span>Tambah
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 30px">
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var menu_url = '{{ Request::segment(3) }}';

    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/soal/kategori/table';
    // var edit_url        = role_url + '#' + modul_url + '/tracer-alumni/edit';
    // var detail_url      = role_url + '#' + modul_url + '/kategori-pertanyaan/detail';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/soal/kategori';
    // alert(datatable_url);
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
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
                data: 'nm_kategori_soal',
                name: 'nm_kategori_soal',

            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" data-id="' +
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
            cell.innerHTML = start + i + 1;
        });
    }).draw();


    function actionDelete(element) {
        var item = $(element);
        item.prop('disabled', true);
        var url = delete_url + '/delete';
        // var url = '{{ url('organizer/question/delete') }}';
        vex.dialog.confirm({
            message: 'Apakah yakin mau menghapus Kategori?',
            callback: function(value) {
                // alert(url)
                if (value) {
                    // alert(url);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            id_kategori_soal: item.attr('data-id')
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
