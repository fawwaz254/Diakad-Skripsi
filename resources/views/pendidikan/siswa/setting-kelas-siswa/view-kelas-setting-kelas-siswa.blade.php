<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Setting Kelas Siswa
                    </h2>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#kelas-siswa" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">meeting_room</i> Kelas Siswa
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#daftar-siswa" data-toggle="tab">
                                <i class="material-icons">people</i> Daftar Siswa
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="kelas-siswa">
                            <form id="form-validation" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-setting-kelas-siswa') }}">
                                {{ csrf_field() }}
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <select class="form-control show-tick" name="id_kelas">
                                            @foreach ($data_kelas as $data)
                                                <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="daftar-siswa">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display"
                                    id="primary_table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No. </th>
                                            <th>NIS</th>
                                            <th>NISN</th>
                                            <th>Nama Siswa</th>
                                            <th>Action</th>
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
</div>
@include('scriptjs')
<script type="text/javascript">
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-kelas-siswa/datatables-siswa/';
    var edit_url = role_url + '#' + modul_url + '/' + 'setting-kelas-siswa/edit';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
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
                data: 'nisn_siswa',
                name: 'nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
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
                        '</a>';
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
