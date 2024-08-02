@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>Dashboard Akademik | {{ $today->format('d M Y') }}</h2>
    </div>
    @if (in_array($auth_data->sekolah_data->nm_singkat_sekolah, [
            'smkypm1taman', //SMK YPM 1 TAMAN
            'smkypm2', //SMK YPM 2 TAMAN
            'smkypm3taman', //SMK YPM 3 TAMAN
            'smawh2', //SMA WACHID HASYIM 2
            'smpypm1', //SMP YPM 1 TAMAN
            'smpypm2', //SMP YPM 2 TAMAN
        ]))
        <div class="block-header" style=" display: flex;justify-content: space-between;">
            <h1 style="font-size: 3rem; margin:0; padding:5px">LOG UPLOAD RPP GURU</h1>
        </div>

        <div class="row clearfix" style="margin-bottom:3rem">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>1 Hari Terakhir</h2>

                    </div>
                    <div class="body">
                        <span style="font-size: 5rem;font-weight:bold">
                            {{ $totalUpload1HariTerakhir }}
                        </span> RPP
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>7 Hari Terakhir</h2>

                    </div>
                    <div class="body">
                        <span style="font-size: 5rem;font-weight:bold">
                            {{ $totalUpload1MingguTerakhir }}
                        </span> RPP
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <div class="card">
                    <div class="header bg-light-green">
                        <h2>30 Hari Terakhir</h2>

                    </div>
                    <div class="body">
                        <span style="font-size: 5rem;font-weight:bold">
                            {{ $totalUpload1BulanTerakhir }}
                        </span> RPP
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <h1 style="font-size: 2.5rem; margin-top:0; padding:5px; padding-left:14px;">REKAP UPLOAD RPP GURU</h1>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    {{ csrf_field() }}
                    <div class="header bg-light-green">
                        <h2>Data Jenis Jurnal Harian</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Guru</th>
                                        <th>Jumlah Upload</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @if ($role_dashboard)
                @if ($role_dashboard->isi_dashboard != null)
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    PENGUMUMAN
                                </h2>
                            </div>
                            <div class="body">
                                {!! $role_dashboard->isi_dashboard !!}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
        {{-- end if untuk pilihan sekolah --}}
    @endif
</div>
{{-- MODAL ACTION --}}
<div class="modal" tabindex="-1" role="dialog" id="modal-action">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h1 style="font-size: 3rem" class="modal-title"></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="margin-top: 0px"></div>
        </div>
    </div>
</div>
@include('rilis-note')


@include('scriptjs')
<script>
    var datatable_url = base_url + '/' + role_url + '/' + 'datatable';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
                className: 'text-center'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna',
                searchable: true
            },
            {
                data: 'jumlah_rpp',
                name: 'jumlah_rpp',
                className: 'text-center',
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return '<button class="btn btn-info btn-sm view-detail" data-id="' + row
                        .id_pengguna + '">Detail</button>';
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

    $('#primary_table').on('click', '.view-detail', function() {
        var id_pengguna = $(this).data('id');
        $.ajax({
            url: base_url + '/' + role_url + '/' + 'detail-rpp/' + id_pengguna,
            type: 'GET',
            success: function(response) {
                $('#modal-action .modal-title').text('Detail Mapel RPP');
                $('#modal-action .modal-body').html(response);
                $('#modal-action').modal('show');
            },
            error: function(xhr) {
                alert('Failed to fetch data');
            }
        });
    });
</script>
