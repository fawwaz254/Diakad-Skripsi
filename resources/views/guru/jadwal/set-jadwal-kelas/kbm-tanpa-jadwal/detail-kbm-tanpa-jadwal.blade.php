<div class="container-fluid">
    {{-- <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#jadwal/input-jadwal')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        SET JADWAL KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option selected="" disabled="">Pilih Kelas</option>
                                    @foreach ($data_kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            {{ $kelas->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                            {{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach ($data_semester as $s)
                                        @if ($s->is_aktif_semester == 1)
                                            <option value="{{ $s->id_semester }}"
                                                {{ $semester->id_semester == $s->id_semester ? 'selected' : '' }}>
                                                {{ $s->tahun_ajaran }} {{ $s->nm_semester }} (Aktif)
                                            </option>
                                        @else
                                            <option value="{{ $s->id_semester }}"
                                                {{ $semester->id_semester == $s->id_semester ? 'selected' : '' }}>
                                                {{ $s->tahun_ajaran }} {{ $s->nm_semester }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card" style="margin-top: 15px">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Daftar Jadwal Mata Ajar</h2>
                    <br>
                    <h2 style="font-size: 18px">Semester : {{ $semester->tahun_ajaran }}
                        ({{ $semester->nm_semester }}) / Kelas : {{ $kelas->nm_kelas }}
                    </h2>
                    <h2 style="margin-top: 15px">
                        <a class="btn bg-blue waves-effect target-link"
                            href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/set-kbm-tanpa-jadwal/view-add/' . $id_kelas . '/' . $id_semester) }}">
                            <i class="material-icons">note_add</i>
                            <span>Tambah Jadwal</span>
                        </a>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Mata Ajar</th>
                                    <th>Jenis Mapel</th>
                                    <th>Pengampu</th>
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
@include('scriptjs')
<script>
    var id_kelas = {!! json_encode($id_kelas) !!};
    var id_semester = {!! json_encode($id_semester) !!};

    var modul_url = 'jadwal';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'set-kbm-tanpa-jadwal/datatables/' +
        id_kelas + '/' + id_semester;

    var edit_url = role_url + '#' + modul_url + '/' + 'set-kbm-tanpa-jadwal/view-edit';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-set-kbm-tanpa-jadwal/delete';


    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        pageLength: 25,
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
                data: 'kd_mata_pelajaran',
                name: 'mata_pelajaran.kd_mata_pelajaran'
            },
            {
                data: 'nm_kelas_mp',
                name: 'mata_pelajaran.nm_mata_pelajaran'
            },
            {
                data: 'nm_jenis_mata_pelajaran',
                name: 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran'
            },
            {
                data: 'nm_pengguna',
                name: 'guru.nm_guru'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id_kelas + '/' + data.id + '">' +
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
