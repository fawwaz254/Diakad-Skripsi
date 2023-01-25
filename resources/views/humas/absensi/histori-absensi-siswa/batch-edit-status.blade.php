<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#absensi/histori-absensi-siswa/detail/' . $id_kelas . '/' . $date . '/0') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    {{-- data-id="{{ $id_jadwal_kelas_mp }}" pertemuan-id="{{ $pertemuan_ke }}" --}}
    {{-- <input type="hidden" id="data-id" value="{{ $id_jadwal_kelas_mp }}">
    <input type="hidden" id="id_kelas" value="{{ $pertemuan_ke }}"> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    @if ($id_kelas == '0')
                        <th>SEMUA KELAS, TANGGAL {{ $date }}</th>
                    @else
                        <h2>KELAS {{ $kelas->nm_kelas }}, TANGGAL {{ $date }}
                        </h2>
                    @endif
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/histori-absensi-siswa/action-batch-edit-status/' . $id_kelas . '/' . $date) }}">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                                id="primary_table" style="overflow-x: scroll;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Pilih</th>
                                        <th>Status</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="date" required=""
                                    aria-required="true" aria-invalid="true" value="{{ $date }}" disabled>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="status">
                                    <option value="izin">izin</option>
                                    <option value="sakit">sakit</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Note
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea class="form-control" name="notes" rows="4" cols="100"></textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                style="margin-bottom: 30px; margin-left: 20">
                                <button class="btn btn-block bg-green waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
</div>
@include('scriptjs')
<script>
    var kelas = {!! json_encode($id_kelas) !!};
    var date = {!! json_encode($date) !!};

    var modul_url = 'absensi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'histori-absensi-siswa/datatables-batch-edit-status/' +
        kelas + '/' + date;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        pageLength: -1,
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
                data: 'kelas',
                name: 'kelas'
            },
            {
                data: 'nis',
                name: 'nis'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'checkbox',
                name: 'checkbox',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<input id="checkbox-' + data.id_pengguna +
                        '" type="checkbox" name="id_pengguna[]" class="filled-in" value="' +
                        data.id_pengguna + '">' +
                        '<label for="checkbox-' + data.id_pengguna + '"></label>';


                }
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'notes',
                name: 'notes'
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


    $(function() {
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
