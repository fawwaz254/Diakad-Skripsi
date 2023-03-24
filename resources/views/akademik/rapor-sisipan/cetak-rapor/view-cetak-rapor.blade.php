<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
        <div>
            <h2><a class="btn btn-warning waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewSetting') }}"><i
                        class="material-icons">settings</i><span>Setting Urutan</span></a>
                @if (
                    $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2' ||
                        $auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman')
                    <a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#rapor-sisipan/cetak-rapor/viewDeskripsi') }}"><i
                            class="material-icons">add</i><span>Deskripsi</span></a>
                @endif
            </h2>
        </div>
        <div class="dropdown" style="display: inline;">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Tahun Ajaran
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                @foreach ($data_semester as $data)
                    <li> <a onclick="changeThn(this)" data-id=" {{ $data->thn_akademik_semester }} ">
                            {{ $data->tahun_ajaran }}
                            @if ($semester_aktif->thn_akademik_semester == $data->thn_akademik_semester)
                                (Aktif)
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <input type="hidden" id="tahun" value="">
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
                                    <th>Nama</th>
                                    <th>Semester</th>
                                    <th>Jumlah Mapel yang sudah terisi</th>
                                    {{-- <th>Semester</th> --}}
                                    <th>UTS</th>
                                    <th>UAS</th>
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
    // var edit_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/edit';
    // var nilai_url = role_url + '#' + modul_url + '/' + 'daftar-nilai-sts/nilai';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/action-daftar-nilai-sts/delete';
    // var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print';
    var uas_url = role_url + '#' + modul_url + '/' + 'cetak-rapor/view-siswa-uas';
    // var pdf_url2 = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print2/' + thn_akademik_semester;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.tahun_ajaran = $('#tahun').val();
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
                name: 'semester'
            },
            {
                data: 'rapor_sisipan',
                name: 'rapor_sisipan',
                className: 'align-center'
            },
            // {
            //     data: 'jumlah',
            //     name: 'jumlah',
            //     className: 'align-center',
            //     searchable: false,
            //     orderable: false,
            //     className: 'align-center',
            //     render: function(data) {
            //         return `<p>` + data.terisi_siswa + ' / ' + data.jumlah_siswa + `</p>`  ;
            //     }},
            //     {
            //     data: 'semester',
            //     name: 'semester',
            //     className: 'align-center'
            // },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.jumlah != '0') {
                        return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            pdf_url + '/' + data.thn_akademik_semester + '/' + data.id_kelas +
                            '"  target="_blank">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    } else {
                        return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
                            '    <i class="material-icons">picture_as_pdf</i>' +
                            '</a> ';
                    }

                }
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
                            uas_url + '/' + data.thn_akademik_semester + '/' + data.id_kelas + '" >' +
                            '    <i class="material-icons">group</i>' +
                            '</a> ';
                    } else {
                        return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
                            '    <i class="material-icons">group</i>' +
                            '</a> ';
                    }

                }
            }
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
        $('#tahun').val(item.attr('data-id'));
        primary_table.draw();
    }
</script>
