<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#laporan/wali-kelas') }}"><i
                    class="material-icons">arrow_back</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Laporan Wali Kelas Role {{ $laporan_wali_kelas->role->nm_role }} Semester
                        {{ $laporan_wali_kelas->semester->tahun_ajaran }}
                        {{ $laporan_wali_kelas->semester->nm_semester }} Bulan
                        {{ $laporan_wali_kelas->bulan->nm_bulan }}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama</th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Jabatan</th>
                                    <th colspan="2" style="vertical-align : middle;text-align:center;">Hasil Laporan
                                    </th>
                                    <th rowspan="2" style="vertical-align : middle;text-align:center;">Catatan</th>
                                </tr>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">Tuntas</th>
                                    <th style="vertical-align : middle;text-align:center;">Belum Tuntas</th>
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

<input type="hidden" id="id" value="{{ $laporan_wali_kelas->id_laporan_wali_kelas }}">

@include('scriptjs')

<script type="text/javascript">
    var id = $('#id').val();
    var modul_url = 'laporan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-kelas/detail-datatable/' + id;
    var edit_url = '';

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
                data: 'guru',
                name: 'guru'
            },
            {
                data: 'jabatan',
                name: 'jabatan'
            },
            {
                data: 'action',
                class: 'text-center',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.status == 1) {
                        return '<i class="material-icons" style="color:green">done</i>';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'action',
                class: 'text-center',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (data.status == 0) {
                        return '<i class="material-icons" style="color:red">clear</i>';
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'catatan',
                name: 'catatan'
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
