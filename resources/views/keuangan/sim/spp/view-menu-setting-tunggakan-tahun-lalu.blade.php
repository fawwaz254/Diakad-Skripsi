<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        SETTING TUNGGAKAN TAHUN LALU
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>

            @if ($semester_aktif->nm_semester == 'Ganjil')
                <a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#sim/spp/setting-tunggakan-tahun-lalu/add') }}"><i
                        class="material-icons">add</i><span>Tambah Tunggakan Tahun Lalu</span></a>
            @else
                <a class="btn bg-blue waves-effect" disabled><i class="material-icons">add</i><span>Tambah Tunggakan Tahun
                        Lalu</span></a>
            @endif

            <p></p>
            <div class="card">
                <div class="body">

                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester Mulai</th>
                                    <th>Semester Selesai</th>
                                    <th>Jumlah Tunggakan Biaya</th>
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
    var modul_url = 'sim/spp';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-tunggakan-tahun-lalu/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'setting-tunggakan-tahun-lalu/edit';

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
                data: 'semester_mulai',
                name: 'semester_mulai'
            },
            {
                data: 'semester_selesai',
                name: 'semester_selesai'
            },
            {
                data: 'tunggakan',
                name: 'tunggakan'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
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
