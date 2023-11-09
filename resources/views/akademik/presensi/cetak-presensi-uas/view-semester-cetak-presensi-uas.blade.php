<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Cetak Presensi Ujian Akhir Semester (UAS) - {{ $semester->tahun_ajaran }}
                        {{ $semester->nm_semester }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Ujian</th>
                                    <th>Kode - Nama Mata Ajar</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Ujian</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Ruangan</th>
                                    <th>Kapasitas</th>
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
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_semester = {!! json_encode($id) !!};

    var modul_url = 'presensi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-presensi-uas/datatables/' +
        id_semester;
    var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-presensi-uas/print';

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
                data: 'nm_ujian_mp',
                name: 'nm_ujian_mp'
            },
            {
                data: 'mata_pelajaran',
                name: 'mata_pelajaran'
            },
            {
                data: 'nm_kelas_mp',
                name: 'nm_kelas_mp'
            },
            {
                data: 'tgl_ujian_mp',
                name: 'tgl_ujian_mp'
            },
            {
                data: 'jam_mulai',
                name: 'jam_mulai'
            },
            {
                data: 'jam_selesai',
                name: 'jam_selesai'
            },
            {
                data: 'ruangan_ujian',
                name: 'ruangan_ujian'
            },
            {
                data: 'kapasitas_ujian',
                name: 'kapasitas_ujian'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" target="_blank" href="' +
                        print_url + '/' + data.id + '/' + data.pengampu + '">' +
                        '    <i class="material-icons">print</i>';
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
