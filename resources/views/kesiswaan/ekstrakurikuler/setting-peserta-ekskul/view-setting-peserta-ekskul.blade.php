<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        Setting Peserta Ekskul
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-setting-peserta-ekskul') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Ekstrakurikuler
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if ($id_ekskul != null)
                                    <select class="form-control show-tick" name="id_ekskul">
                                        @foreach ($ekskul as $data)
                                            <option value="{{ $data->id_ekskul }}"
                                                @if ($id_ekskul == $data->id_ekskul) selected @endif>{{ $data->nm_ekskul }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-control show-tick" name="id_ekskul">
                                        @foreach ($ekskul as $data)
                                            <option value="{{ $data->id_ekskul }}">{{ $data->nm_ekskul }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Semester</label>
                                <select class="form-control show-tick" name="id_semester" required="">
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}"
                                            @if ($id_ekskul == $data->id_ekskul) selected @endif>
                                            {{ $data->tahun_ajaran }}
                                            {{ $data->nm_semester }}
                                            @if ($data->is_aktif_semester == 1)
                                                (Aktif)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
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
            </div>

            @if ($id_ekskul != null && $id_semester != null)
                <div class="card">
                    <div class="body">
                        <div class="block-header">
                            <h2>
                                <a class="btn bg-blue waves-effect target-link"
                                    href="{{ url(Request::segment(1) . '#ekstrakurikuler/setting-peserta-ekskul/add/' . $id_ekskul) }}"><i
                                        class="material-icons">note_add</i><span>Tambah Peserta Ekskul</span></a>
                                {{-- <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ekstrakurikuler/setting-peserta-ekskul/setting/'.$id_ekskul)}}"><i class="material-icons">settings_applications</i><span>Setting Pengambilan Ekskul</span></a> --}}
                            </h2>
                        </div>
                        <div class="row clearfix">
                            <div class="body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                        id="primary_table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIS - Nama Siswa</th>
                                                <th>Kelas</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    // var id_semester = {!! json_encode($id_semester) !!};
    var id_ekskul = {!! json_encode($id_ekskul) !!};
    var id_semester = {!! json_encode($id_semester) !!};

    var modul_url = 'ekstrakurikuler';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-peserta-ekskul/datatables/' +
        id_ekskul + '/' + id_semester;
    var edit_url = role_url + '#' + modul_url + '/' + 'setting-peserta-ekskul/edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-setting-peserta-ekskul/delete';


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
                data: 'nm_siswa',
                name: 'nm_siswa'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'is_aktif',
                name: 'is_aktif'
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
                        '</a> ' +
                        '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
                        delete_url + '\', this)" data-id="' + data.id + '">' +
                        '    <i class="material-icons">delete_forever</i>' +
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
</script>
