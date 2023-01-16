<div class="container-fluid">

    <div class="row-clearfix">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <h2>
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#aktivitas-semester/plotting-mapel-siswa/view-kelas-plotting/' . $id_semester . '/' . $angkatan) }}">
                        <i class="material-icons">backspace</i><span>kembali</span>
                    </a>
                </h2>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        Plotting Mapel Siswa
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/plotting-mapel-siswa/action-auto-plotting-mapel-siswa') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester" disabled="">
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}"
                                            @if ($id_semester === $data->id_semester) selected @endif>
                                            {{ $data->tahun_ajaran }}
                                            {{ $data->nm_semester }}
                                            @if ($data->is_aktif_semester == 1)
                                                (Aktif)
                                            @endif
                                        </option>
                                    @endforeach
                                    <input type="hidden" name="id_semester" value="{{ $id_semester }}">
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Angkatan
                                </h2>
                                <select class="form-control show-tick" name="angkatan" disabled="">
                                    @foreach ($thn_masuk_siswa as $data)
                                        <option value="{{ $data->thn_masuk_siswa }}"
                                            @if ($semester_aktif->thn_akademik_semester == $data->thn_masuk_siswa) selected @endif>
                                            {{ $data->thn_masuk_siswa }}
                                        </option>
                                    @endforeach
                                    <input type="hidden" name="angkatan" value="{{ $angkatan }}">
                                    <input type="hidden" name="id_jurusan" value="{{ $id_jurusan }}">
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach ($kelas as $data)
                                        <option value="{{ $data->id_kelas }}">
                                            {{ $data->nm_kelas }}
                                        </option>
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
                                        class="material-icons">play_arrow</i><span>Ploting Otomatis</span></button>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <p>
                                    1. Kalau sudah ploting tapi angka tidak berubah jangan klick Ploting lagi,
                                    <br>
                                    2. jumlah Kelas Mp Siswa tidak sama dengan jumlah kelas Mp maka perlu
                                    Ploting lagi.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                {{-- {{csrf_field()}} --}}
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Jurusan</th>
                                    <th>Kelas</th>
                                    <th>Sudah Diplotting</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Ploting Kelas Mp</th>
                                    <th>Jumlah Kelas Mp</th>
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
    var id_semester = {!! json_encode($id_semester) !!};
    var angkatan = {!! json_encode($angkatan) !!};
    var id_jurusan = {!! json_encode($id_jurusan) !!}

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'aktivitas-semester';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'plotting-mapel-siswa/datatables-auto-plotting-mapel-siswa/' + id_semester + '/' + angkatan + '/' + id_jurusan;
    // var detail_url        = role_url + '#' + modul_url + '/' + 'plotting-mapel-siswa/view-mapel-plotting/'+ id_semester + '/' + angkatan;
    // var auto_ploting      = role_url + '#' + modul_url + '/' + 'plotting-mapel-siswa/view-auto-plotting-mapel-siswa/'+ id_semester + '/' + angkatan;
    // alert(datatable_url);
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        "pageLength": 100,
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
                data: 'nm_jurusan',
                name: 'nm_jurusan'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'jml_siswa_krs',
                name: 'jml_siswa_krs'
            },
            {
                data: 'jml_siswa',
                name: 'jml_siswa'
            },
            {
                data: 'jml_kelas_mp_siswa',
                name: 'jml_kelas_mp_siswa'
            },
            {
                data: 'jml_kelas_mp',
                name: 'jml_kelas_mp'
            },
            // { data: 'action', name: 'action', searchable: false, orderable: false,
            //     render: function(data){
            //         return '<a class="target-link btn bg-blue waves-effect" href="'+ detail_url +'/' + data.id + '">Manual Plotting</a>'
            //         +'      <a class="target-link btn bg-red waves-effect" href="'+ auto_ploting +'/' + data.id + '">Auto Plotting</a>';
            //     }
            // },
            // {data: 'auto', searchable: false, orderable:false,
            // render: function(data) {
            //     var role_text = '';
            //         data.forEach((d, i) => {

            //     role_text += '<a class="target-link btn bg-red waves-effect" style="margin: 0px 5px 5px 5px" href="'+  d.id_kelas +' ">Auto Plotting Kelas '+ d.nm_kelas +' </a> '
            //     if(i%2 === 1){
            //         role_text += '<br>'
            //     }
            //             });
            //     return role_text
            // }}
        ]
    })

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
