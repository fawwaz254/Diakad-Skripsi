<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        LAPORAN ABSENSI SISWA PER KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-absensi-siswa') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            {{ csrf_field() }}

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        @if ($id_semester != null)
                                            @if ($data->id_semester == $id_semester)
                                                @if ($data->is_aktif_semester == 1)
                                                    <option value="{{ $data->id_semester }}" selected>
                                                        {{ $data->tahun_ajaran }} {{ $data->nm_semester }} (Aktif)
                                                    </option>
                                                @else
                                                    <option value="{{ $data->id_semester }}" selected>
                                                        {{ $data->tahun_ajaran }} {{ $data->nm_semester }}</option>
                                                @endif
                                            @else
                                                @if ($data->is_aktif_semester == 1)
                                                    <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                        {{ $data->nm_semester }} (Aktif)</option>
                                                @else
                                                    <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                        {{ $data->nm_semester }}</option>
                                                @endif
                                            @endif
                                        @else
                                            @if ($data->is_aktif_semester == 1)
                                                <option value="{{ $data->id_semester }}" selected>
                                                    {{ $data->tahun_ajaran }} {{ $data->nm_semester }} (Aktif)
                                                </option>
                                            @else
                                                <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                    {{ $data->nm_semester }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jurusan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jurusan" id="jurusan">
                                    @if ($id_jurusan != null)
                                        <option value="" disabled>-- Pilih Jurusan --</option>
                                        @foreach ($jurusan as $jurusan)
                                            @if ($jurusan->id_jurusan == $id_jurusan)
                                                <option value="{{ $jurusan->id_jurusan }}" selected="">
                                                    {{ $jurusan->nm_jurusan }}</option>
                                            @else
                                                <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        <option value="0">-- Pilih Jurusan --</option>
                                        @foreach ($jurusan as $jurusan)
                                            <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas" id="kelas">
                                    <option value="0">-- Pilih Kelas --</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Mulai <br>
                            <small>Tanggal mulai absensi</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_mulai" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="@if ($tgl_mulai != null) {{ strftime('%d %B %Y', strtotime($tgl_mulai)) }} @endif"
                                    readonly>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Selesai <br>
                            <small>Tanggal selesai absensi</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_selesai" required=""
                                    aria-required="true" aria-invalid="true"
                                    value="@if ($tgl_selesai != null) {{ strftime('%d %B %Y', strtotime($tgl_selesai)) }} @endif"
                                    readonly>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($id_semester != null and $id_jurusan != null and $tgl_selesai != null and $tgl_selesai != null)
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Jalur Masuk</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
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
    var id_semester = {!! json_encode($id_semester) !!};
    var id_kelas = {!! json_encode($id_kelas) !!};

    var modul_url = 'siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'admisi-siswa/datatables/' + id_semester +
        '/' + id_kelas;
    var admisi_url = role_url + '#' + modul_url + '/' + 'admisi-siswa/view-detail';


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
                data: 'nisn_siswa',
                name: 'nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_status_pengguna',
                name: 'nm_status_pengguna'
            },
            {
                data: 'nm_jalur',
                name: 'nm_jalur'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.admisi != null) {
                        return '-';
                    } else {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            admisi_url + '/' + data.id + '">' +
                            '    <i class="material-icons">person_add</i>' +
                            '</a>';
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('select').select();
    });

    var modul_url = 'siswa';

    $('#jurusan').on('change', function(e) {
        console.log(e);
        var id_jurusan = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'data-siswa/get-kelas/' + id_jurusan,
            function(data) {
                console.log(data);
                $('#kelas').empty();


                $('#kelas').append($("<option>")
                    .attr("value", 0)
                    .text("-- Pilih Kelas --")
                );
                $.each(data, function(index, kelasObj) {
                    $('#kelas').append($("<option>")
                        .attr("value", kelasObj.id_kelas)
                        .text(kelasObj.nm_kelas)
                    );
                })

                $('select').select();
            });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            // time: true
        });

    });
</script>
