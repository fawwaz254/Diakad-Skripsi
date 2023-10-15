<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Komponen KPI
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tingkat Kelas
                                </h2>
                                <select class="form-control show-tick" name="tingkat">
                                    <option selected="" disabled="">Pilih Tingkat Kelas</option>
                                    @foreach ($tingkat_kelas as $tingkat_kel)
                                        <option value="{{ $tingkat_kel }}"
                                            @if ($tingkat_kel == $tingkat) selected @endif>{{ $tingkat_kel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach ($data_semester as $semester)
                                        <option value="{{ $semester->id_semester }}"
                                            @if ($semester->id_semester == $id_semester) selected @endif>
                                            {{ $semester->tahun_ajaran }} {{ $semester->nm_semester }} @if ($semester->is_aktif_semester == 1)
                                                (Aktif)
                                            @endif

                                        </option>
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



        </div>
    </div>
    <br>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Point KPI</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelompok KPI</th>
                                    <th>Urutan</th>
                                    <th>Jenis</th>
                                    <th>Deskripsi</th>

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>

@include('scriptjs')
<script>
    var modul_url = 'kpi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-kpi/detail/datatables';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'jenis-mgmp/action-data-kategori/delete';

    var tingkat = "{{ $tingkat }}";
    var id_semester = "{{ $id_semester }}";

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.tingkat = '{{ $tingkat }}';
                d.id_semester = '{{ $id_semester }}';
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },

            {
                data: 'nm_point_kpi',
                name: 'nm_point_kpi'
            },
            {
                data: 'kelompok_kpi.nm_kelompok_kpi',
                name: 'kelompok_kpi.nm_kelompok_kpi'
            },
            {
                data: 'urutan',
                name: 'urutan'
            },
            {
                data: 'jenis',
                name: 'jenis'
            },
            {
                data: 'deskripsi',
                name: 'deskripsi'
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
