<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="
				{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/manage/0') }}
				"><i
                    class="material-icons">note_add</i><span>Tambah Absensi Magang</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>ABSENSI MAGANG {{ $detail_magang->rekanan->nm_rekanan_magang }} <br>
                        {{ $detail_magang->periode->tgl_magang_mulai }} sampai
                        {{ $detail_magang->periode->tgl_magang_selesai }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hari, Tanggal</th>
                                    <th>Hadir</th>
                                    <th>Izin/Sakit</th>
                                    <th>Absent</th>
                                    <th>Keterangan</th>
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
<script>
    var modul_url = 'presensi-magang';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/input-presensi-magang/datatables';
    let detail_url = role_url + '#' + modul_url + '/input-presensi-magang/detail';

    let primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'tanggal',
                name: 'tanggal'
            },
            {
                data: 'kehadiran.hadir',
                name: 'kehadiran.hadir'
            },
            {
                data: 'kehadiran.sakit_izin',
                name: 'kehadiran.sakit_izin'
            },
            {
                data: 'kehadiran.alfa',
                name: 'kehadiran.alfa'
            },
            {
                data: 'keterangan',
                name: 'keterangan'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-danger btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/' + data.id + '">' +
                        '    <i class="material-icons">remove_red_eye</i>' +
                        '</a> ';
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
