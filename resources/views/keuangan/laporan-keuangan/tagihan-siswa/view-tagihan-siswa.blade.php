<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">
                <div class="header">
                    <h2>
                        TAGIHAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" onchange="change_jenis_tagihan()"
                                id="tahun_akademik_semester" name="tahun_akademik_semester">
                                @foreach ($data_semester as $semester)
                                    <option value="{{ $semester->thn_akademik_semester }}"
                                        @if ($semester->thn_akademik_semester == $tahun_akademik_semester) selected @endif>
                                        {{ $semester->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <select class="form-control show-tick" onchange="change_jenis_tagihan()" id="kelas"
                                name="kelas">
                                <option value="">Pilih kelas</option>
                                <option value="all">Semua Kelas</option>
                                @foreach ($data_kelas as $data)
                                    <option value="{{ $data->id_kelas }}">
                                        {{ $data->nm_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small style="color: red;">Wajib pilih kelas Terlebih Dahulu</small>
                                <label>
                                    Status
                                    <input id="status_opsi_1" class="with-gap radio-col-light-green form-control validate"
                                        type="radio" name="status" value="1" checked="">
                                    <label for="status_opsi_1"> Siswa Aktif </label>
                                    <input id="status_opsi_2" class="with-gap radio-col-light-green form-control validate"
                                        type="radio" name="status" value="2">
                                    <label for="status_opsi_2"> Alumni </label>
                                </label>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Tagihan yang Ditampilkan
                            </h2>
                            <select class="form-control show-tick" multiple="" id="jenis_tagihan"
                                name="jenis_tagihan">
                                <option value="0">Semua Tagihan</option>
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Ajaran/kelas</span></button>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-orange waves-effect" onclick="printAction()"><i
                                    class="material-icons">print</i><span>Print Data Tagihan</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tunggakan</th>
                                    <th>Tagihan</th>
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
    function change_jenis_tagihan() {

        var tahun = $('#tahun_akademik_semester').val();
        var kelas = $('#kelas').val();

        $('#jenis_tagihan').empty();

        if (kelas == "") {
            $('#jenis_tagihan').append(`
             <option value="0">Semua Tagihan</option>
        `);
        } else {

            $('#jenis_tagihan').append(`
             <option value="0">Semua Tagihan</option>
        `);

            $.ajax({
                url: base_url + '/' + role_url + '/laporan-keuangan/tagihan-siswa/show-list-tagihan/' + tahun + '/' + kelas,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $.each(response, function(i, value) {
                        $('#jenis_tagihan').append(`
                        <option value=` + value.id_detail_biaya + `>` + value.judul + `</option>
                    `);
                    })
                },
                error: function() {
                    alert('terjadi kesalahan, silahkan hubungi admin');
                }
            });

        }

    }

    var datatable_url = base_url + '/' + role_url + '/laporan-keuangan/tagihan-siswa/datatables';
    var print_tagihan_url = base_url + '/' + role_url + '/laporan-keuangan/tagihan-siswa/print';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        dom: 'Bfrtip',
        buttons: dtButtonConfig,

        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params) {
                params.kelas = $('select[name=kelas]').val();
                params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
                params.jenis_tagihan = $('#jenis_tagihan').val();
                params.status = $('input[name=status]:checked').val();
            },
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa'
            },
            {
                data: 'pengguna.nm_pengguna'
            },
            {
                data: 'kelas.nm_kelas'
            },
            {
                data: 'total_tagihan_bulan',
                searchable: false,
                orderable: false
            },
            {
                data: 'tagihan_bulan',
                searchable: false,
                orderable: false,
                render: function(data) {
                    var html = '<ul>';
                    $.each(data, function(key, value) {
                        html += '<li>' + value.judul + ' (' + value.biaya + ')</li>';
                    });
                    html += '</ul>';

                    return html;
                }
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

    function filterAction() {
        primary_table.ajax.reload(null, false);
    }

    function printAction() {
        $('button').attr('disabled', 'disabled');
        var kelas = $('select[name=kelas]').val();
        var ta_semester = $('select[name=tahun_akademik_semester]').val();
        var jenis_tagihan = $('#jenis_tagihan').val();

        if (kelas == null || ta_semester == null || kelas == '' || ta_semester == '') {
            vex.dialog.alert("Kelas atau Tahun Ajaran yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            $('button').removeAttr('disabled', 'disabled');
            window.open(print_tagihan_url + '/' + ta_semester + '/' + kelas + '/' + jenis_tagihan + '?status=' + $('input[name=status]:checked').val(), "_blank");
        }
    }
</script>
