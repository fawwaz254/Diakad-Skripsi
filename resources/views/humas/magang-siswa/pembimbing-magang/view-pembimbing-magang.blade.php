<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PEMBIMBING MAGANG
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label>Periode Magang</label>
                            <select class="form-control show-tick" name="id_periode_magang" id="id_periode_magang">
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
                                    <th>Username</th>
                                    <th>Pembimbing Magang</th>
                                    <th>Aksi</th>
                                    <th>Periode Magang</th>
                                    <th>Semester</th>
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

<script type="text/javascript">
    function filterData() {
        primary_table.draw();
    }

    var modul_url = 'magang-siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembimbing-magang/datatables';
    var add_url = role_url + '#' + modul_url + '/' + 'pembimbing-magang/add';
    var edit_url = role_url + '#' + modul_url + '/' + 'pembimbing-magang/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-pembimbing-magang/delete';
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_periode_magang = $('select[name=id_periode_magang]').val()
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'rekanan.nm_rekanan_magang',
                name: 'rekanan.nm_rekanan_magang'
            },
            {
                data: 'username_pembimbing_magang.username',
                name: 'username_pembimbing_magang.username'
            },
            {
                data: 'username_pembimbing_magang.pembimbing_magang',
                name: 'username_pembimbing_magang.pembimbing_magang'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    var html = '';
                    if (data.pembimbingMagang) {
                        html +=
                            '<a class="target-link btn btn-warning btn-circle waves-effect waves-circle waves-float" href="' +
                            edit_url + '/' + data.pembimbingMagang + '">' +
                            '    <i class="material-icons">edit</i>' +
                            '</a> ' +
                            '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                            delete_url + '\', this)" data-id="' + data.pembimbingMagang + '">' +
                            '    <i class="material-icons">delete</i>' +
                            '</button> ';
                    } else {
                        html +=
                            '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            add_url + '/' + data.id + '">' +
                            '    <i class="material-icons">add</i>' +
                            '</a> ';

                    }
                    return html;
                }
            },
            {
                data: 'periode',
                name: 'periode'
            },
            {
                data: 'semester',
                name: 'semester'
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
</script>
