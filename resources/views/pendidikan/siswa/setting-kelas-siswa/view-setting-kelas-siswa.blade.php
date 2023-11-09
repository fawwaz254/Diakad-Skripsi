<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#siswa/setting-kelas-siswa') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Daftar Siswa Kelas {{ $kelas->nm_kelas }}</h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-setting-kelas-siswa/ganti-kelas/' . $kelas->id_kelas) }}">
                        {{ csrf_field() }}

                        <h2 class="card-inside-title">
                            Apakah anda ingin mengubah semua tagihan dari kelas {{ $kelas->nm_kelas }} ke kelas yang
                            anda pilih
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="status_pindah">
                                    <option value="0">Tidak</option>
                                    <option value="1">Iya</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Pindah Ke Kelas :
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
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>
                                            <input id="checkbox_select_all" type="checkbox" name="select_all"
                                                class="filled-in">
                                            <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas = {!! json_encode($kelas->id_kelas) !!};

    var modul_url = 'siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-kelas-siswa/datatables/' +
    id_kelas;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        lengthMenu: [
            [50, 100, -1],
            [50, 100, "All"]
        ],
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
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<input id="checkbox-' + data.id_siswa +
                        '" type="checkbox" name="id_siswa[]" class="filled-in" value="' + data
                        .id_siswa + '">' +
                        '<label for="checkbox-' + data.id_siswa + '"></label>';
                }
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
        /* Select All Checkbox */
        $('#checkbox_select_all').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({
                'search': 'applied'
            }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>
