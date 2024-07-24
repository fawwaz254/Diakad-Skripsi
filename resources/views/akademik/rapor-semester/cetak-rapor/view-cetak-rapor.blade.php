<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
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
                <div class="container">
                    <div style="margin-top: 1.5rem">
                        <label for="tanggal_cetak">Set Tanggal Cetak Rapor</label>
                        <input type="date" class="form-control" id="set_tanggal_cetak" name="set_tanggal_cetak"
                            aria-required="true" aria-invalid="true" value="{{ $tanggal_cetak }}">
                        <button class="btn btn-block btn-primary waves-effect align-items-start" type="submit"
                            onclick="setTanggalCetak()" style="margin-top: 1rem">
                            <i class="material-icons">save</i>Simpan
                        </button>
                    </div>
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
                                    <th>Cetak</th>
                                    <th>Leger</th>
                                    <th>Data Siswa</th>
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
    var modul_url = 'rapor-semester';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/datatables';
    var pdf_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print';
    var leger_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/leger';
    // var pdf_uas_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/print2';
    var data_tambahan_url = role_url + '#' + modul_url + '/' + 'cetak-rapor/view-data-tambahan';
    var saveTanggalCetakSemester_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-rapor/save-tanggal-cetak-semester';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
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
                data: 'kelas',
                name: 'kelas',
                className: 'align-center'
            },
            {
                data: 'rapor',
                name: 'rapor',
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
                            leger_url + '/' + data.id_semester + '/' + data.id_kelas +
                            '"  target="_blank">' +
                            '    <i class="material-icons">print</i>' +
                            '</a> ';
                    } else {
                        return '<a class=" btn bg-grey btn-circle waves-effect waves-circle waves-float" href=""  style=" pointer-events: none;">' +
                            '    <i class="material-icons">print</i>' +
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
                    return '<a class=" btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                        data_tambahan_url + '/' + data.id_semester + '/' + data.id_kelas +
                        '" >' +
                        '    <i class="material-icons">group</i>' +
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

    function changeThn(value) {
        var item = $(value);
        $('#id_semester').val(item.attr('data-id'));
        primary_table.draw();
    }

    function setTanggalCetak() {
        let inputTanggal = $('#set_tanggal_cetak').val();
        console.log(inputTanggal);

        $.ajax({
            url: saveTanggalCetakSemester_url,
            type: 'POST',
            data: {
                tanggal: inputTanggal
            },
            dataType: 'JSON',
            success: function(response) {
                console.log(response)
                if (response.code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.code == 300) {
                    vex.dialog.alert(response.message);
                } else if (response.code == 400) {
                    vex.dialog.alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                vex.dialog.alert('Terjadi kesalahan pada server: ' + error);
            }
        });
    }
</script>
