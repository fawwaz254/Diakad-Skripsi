<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
        {{-- <div>
            <h2><a class="btn btn-warning waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewSetting') }}"><i
                        class="material-icons">settings</i><span>Setting Urutan</span></a>
                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman')
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewDeskripsi') }}"><i
                            class="material-icons">add</i><span>Deskripsi</span></a>
                @endif
            </h2>
        </div> --}}
        <div class="dropdown" style="display: inline; margin-right:50px">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Tahun Ajaran
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach ($data_semester as $data)
                    <li> <a onclick="changeThn(this)" data-id=" {{ $data->id_semester }} ">
                            {{ $data->tahun_ajaran . ' ' . $data->nm_semester }}
                            @if ($semester_aktif->id_semester == $data->id_semester)
                                (Aktif)
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" id="id_semester" value="">
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Cetak Rapor</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>Wali Kelas</th>
                                    <th>Semester</th>
                                    <th>Total Mapel</th>
                                    <th>Mapel terinput</th>
                                    <th>UTS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var modul_url = 'rapor-sisipan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/datatables';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print';
    var uas_url = role_url + '#' + modul_url + '/' + 'cetak-rapor/view-siswa-uas';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.id_semester = $('#id_semester').val();
            }
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas',
                className: 'align-center'
            },
            {
                data: 'jurusan.nm_jurusan',
                name: 'jurusan.nm_jurusan',
            },
            {
                data: 'wali_kelas',
                name: 'wali_kelas'
            },
            {
                data: 'semester',
                name: 'semester',
                className: 'align-center'
            },
            {
                data: 'kelas_sisipan',
                name: 'kelas_sisipan',
                className: 'align-center'
            },
            {
                data: 'rapor_sisipan',
                name: 'rapor_sisipan',
                className: 'align-center'
            },

            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.jumlah != '0') {
                        return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            pdf_url + '/' + data.id_semester + '/' + data.id_kelas +
                            '"  target="_blank">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    } else {
                        return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    }

                }
            }
            // ,
            // {
            //     data: 'action',
            //     name: 'action',
            //     searchable: false,
            //     orderable: false,
            //     className: 'align-center',
            //     render: function(data) {
            //         if (data.jumlah != '0') {
            //             return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
            //                 uas_url + '/' + data.id_semester + '/' + data.id_kelas + '" >' +
            //                 '    <i class="material-icons">group</i>' +
            //                 '</a> ';
            //         } else {
            //             return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
            //                 '    <i class="material-icons">group</i>' +
            //                 '</a> ';
            //         }

            //     }
            // }
            // {
            //     data: 'pengguna.nm_pengguna',
            //     name: 'pengguna.nm_pengguna',
            //     className: 'align-center'
            // },
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

    function changeThn(value) {
        var item = $(value);
        $('#id_semester').val(item.attr('data-id'));
        primary_table.draw();
    }
</script>
