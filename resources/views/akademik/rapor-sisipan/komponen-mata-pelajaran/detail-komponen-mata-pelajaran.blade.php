<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        Komponen Mata Pelajaran
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
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($k->id_kelas == $id_kelas) selected @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    <br>
                                </h2>
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
            <div class="block-header">
                <h2><a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#rapor-sisipan/komponen-mata-pelajaran/add/' . $id_kelas) }}"><i
                            class="material-icons">add</i><span>Tambah Komponen Mata Pelajaran</span></a></h2>
            </div>
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelompok</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Urutan</th>

                                    <th>Kelas</th>
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
<br>

@include('scriptjs')
<script>
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'komponen-mata-pelajaran/datatables';
    var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'komponen-mata-pelajaran/action-komponen-mata-pelajaran/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_kelas = '{{ $id_kelas }}';
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },

            {
                data: 'nm_kelompok_sisipan',
                name: 'nm_kelompok_sisipan'
            },
            {
                data: 'nm_mata_pelajaran',
                name: 'nm_mata_pelajaran'
            },
            {
                data: 'mata_pelajaran_sisipan.urutan',
                name: 'mata_pelajaran_sisipan.urutan'
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
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
