<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>ABSENSI MAGANG {{ $detail_magang->rekanan->nm_rekanan_magang }} </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/action') }}/{{ $presensi_magang ? 'edit' : 'add' }}/{{ $presensi_magang ? $presensi_magang->id_presensi_magang : '0' }}">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                                id="primary_table" style="overflow-x: scroll;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Keterangan</label>
                                        <textarea class="form-control" name="keterangan" rows="2" cols="100">{{ $presensi_magang ? $presensi_magang->keterangan : '' }}</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Tanggal Presensi</label>
                                        <input type="text" class="datetimepicker form-control" name="tanggal"
                                            aria-required="true" aria-invalid="true"
                                            value="{{ $presensi_magang ? \Carbon\Carbon::parse($presensi_magang->tanggal)->format('Y-m-d ') : \Carbon\Carbon::today()->format('Y-m-d ') }} ">
                                    </div>
                                </div>
                            </div>
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
</div>
@include('scriptjs')
<script>
    var modul_url = 'presensi-magang';
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/input-presensi-magang/datatables-detail/';
    var id_presensi_magang = "{{ $presensi_magang ? $presensi_magang->id_presensi_magang : '0' }}";

    let primary_table = $('#primary_table').DataTable({
        processing: true,
        pageLength: 100,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: {
                id_presensi_magang: id_presensi_magang
            },
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                render: function(data) {
                    if (data.status_pengguna.status == 1) {
                        return data.nis_siswa + '<br><input type="hidden" name="id_siswa[]" value="' +
                            data.id_siswa + '" >';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'siswa.pengguna.nm_pengguna'
            },
            {
                data: 'keterangan',
                name: 'keterangan',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.status_pengguna.status == 1) {
                        let html = '';
                        $.each(data.options, function(index, item) {
                            if (data.kehadiran == item.id) {
                                html += '<option value="' + item.id + '"selected>' + item.text +
                                    '</option>';
                            } else {
                                html += '<option value="' + item.id + '">' + item.text +
                                    '</option>';
                            }
                        })
                        return '<select class="form-control show-tick" style="width:85px;" name="kehadiran[]">' +
                            html +
                            '</select>';
                    } else {
                        return '<p class="font-underline col-orange font-24">' + data.status_pengguna
                            .nm_status + '</p>';
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


    $(function() {
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: true
        });
    });
</script>
