<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
        <div>
            <h2>
                <a class="btn bg-green waves-effect target-link" style="margin-left: 10px"
                    href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sas/importExcel/') }}"><i
                        class="material-icons">cloud_upload</i><span> Import Excel</span></a>
                <div style="display: inline;margin-right:10px"></div>
                <input type="checkbox" id="data_semua_pengguna" class="checkbox">
                <label for="data_semua_pengguna">Data Semua Pengguna</label>
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
        <input type="hidden" id="status" value="0">
        <input type="hidden" id="tahun" value="">
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Daftar Nilai Akhir Semester</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Nilai Siswa Terisi</th>
                                    {{-- <th>Nilai SAS Terisi Lengkap</th> --}}
                                    <th>Semester</th>

                                    <th>Nilai</th>
                                    <th>Template Excel</th>
                                    <th>Action</th>
                                    <th>Pembuat</th>
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
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sas/datatables';
    // var edit_url = role_url + '#' + modul_url + '/' + 'manajemen-materi-ajar/edit';
    var nilai_url = role_url + '#' + modul_url + '/' + 'daftar-nilai-sas/nilai';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/action-daftar-nilai-sts/delete';
    // var print_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sts/print';
    var excel_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sas/excel';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'daftar-nilai-sas/pdf';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data: function(d) {
                d.status = $('#status').val();
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
                data: 'mata_pelajaran.nm_mata_pelajaran',
                name: 'mata_pelajaran.nm_mata_pelajaran',
                className: 'align-center',
                orderable: false,
            },
            {
                data: 'kelas.nm_kelas',
                name: 'kelas.nm_kelas',
                className: 'align-center',
                orderable: false,
            },
            {
                data: 'jumlah',
                name: 'jumlah',
                className: 'align-center',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.setting == '3') {
                        return `<p> Terisi = ` + data.terisi_siswa + ' / ' + data.jumlah_siswa +
                            '</p>';
                    } else {
                        return `<p> STS = ` + data.terisi_siswa_sts + ' / ' + data.jumlah_siswa +
                            `</p><p> SAS = ` + data.terisi_siswa_sas + ' / ' + data.jumlah_siswa +
                            `</p>`;
                    }
                }
            },
            {
                data: 'semester',
                name: 'semester',
                searchable: false,
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    if (data.status == '0') {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            nilai_url + '/' + data.id + '">' +
                            '    <i class="material-icons">visibility</i>' +
                            '</a> ';
                    } else {
                        return '';
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
                    if (data.status == '0') {
                        return '<a class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                            excel_url + '/' + data.id + '" target="_blank">' +
                            '    <i class="material-icons">backup</i>' +
                            '</a> ';
                    } else {
                        return '';
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
                    // return  '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                    // print_url + '/' + data.id + '"  target="_blank">' +
                    //     '    <i class="material-icons">picture_in_picture</i>' +
                    //     '</a> '+
                    return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                        pdf_url + '/' + data.id + '"  target="_blank">' +
                        '    <i class="material-icons">picture_as_pdf</i>' +
                        '</a> ';
                }
            },
            {
                data: 'pengguna.nm_pengguna',
                name: 'pengguna.nm_pengguna',
                className: 'align-center',
                orderable: false,
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


    function changeThn(value) {
        var item = $(value);
        $('#tahun').val(item.attr('data-id'));
        primary_table.draw();
    }

    $('.checkbox').on('change', function() { // on change of state
        if (this.checked) // if changed state is "CHECKED"
        {
            $('#status').val(1);
            primary_table.draw();

        } else {
            $('#status').val(0);
            primary_table.draw();
        }
    });
</script>
