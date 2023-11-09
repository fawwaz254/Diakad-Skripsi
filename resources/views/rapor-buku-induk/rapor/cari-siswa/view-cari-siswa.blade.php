<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        CARI DATA RAPOR SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-cari-siswa') }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Masukkan NIS/NISN atau Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nis_nama_siswa" aria-invalid="true"
                                    value="{{ $nis_nama_siswa == null ? '' : $nis_nama_siswa }}" autofocus>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>

                @if ($nis_nama_siswa != null)
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Jalur Masuk</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-rapor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="modal-header">
                    <h4 class="modal-title">PRINT RAPOR</h4>
                </div>
                <form id="print"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cari-siswa/print-rapor') }}"
                    target="_blank" method="POST">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Catatan Wali Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea id="catatan" class="form-control" name="deskripsi_catatan_wali_kelas" placeholder="Tulis catatan"></textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="modal-print"></div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block waves-effect"><span><i
                                            class="material-icons">print</i></span> Cetak</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var nis_nama_siswa = {!! json_encode($nis_nama_siswa) !!};

    var modul_url = 'rapor';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cari-siswa/datatables/' + nis_nama_siswa;
    var preview_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cari-siswa/preview-rapor';
    var print_url = role_url + '#' + modul_url + '/' + 'cari-siswa/print-rapor';
    var dataLog = null;

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
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'nisn_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'nm_status_pengguna',
                name: 'nm_status_pengguna'
            },
            {
                data: 'nm_jalur',
                name: 'nm_jalur'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float btn-print" target="_blank">' +
                        '    <i class="material-icons">print</i>' + '</a>';
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

    var table = $('#primary_table').DataTable();

    $('#primary_table tbody').on('click', 'tr', function() {
        var data = table.row(this).data();
        var id_kelas_opt = null;
        dataLog = data; // set global dataLog

        $(".modal-print").empty();
        $(".modal-print").append('<input type="hidden" name="id_siswa" value="' + data.id_siswa + '">');
        $(".modal-print").append('<h2 class="card-inside-title">Pilih Kelas</h2>');
        $(".modal-print").append(
            '<select class="option-kelas form-control" id="id_kelas" name="id_kelas" required onchange="changeKelas()">'
        );
        $(".option-kelas").append('<option value="" disabled selected>-- Pilih Kelas --</option>');
        data.log_kelas.forEach(function(row) {
            if (row.id_kelas != id_kelas_opt) {
                $(".option-kelas").append('<option value="' + row.id_kelas + '">' + row.nm_kelas +
                    '</option>');
                id_kelas_opt = row.id_kelas;
            }
        })
        $(".modal-print").append('<h2 class="card-inside-title">Pilih Semester</h2>');
        $(".modal-print").append(
            '<select class="option-semester form-control" id="id_semester" name="id_semester" required onchange="changeSemester()">'
        );
        $(".option-semester").append('<option value="" selected disabled>-- Pilih Semester --</option>');

        $(".modal-print").append(
            '<h2 class="card-inside-title keputusan" style="display: none;">Keputusan</h2>');
        $(".modal-print").append(
            '<select name="keputusan" id="keputusan-select" class="form-control keputusan" disabled style="display: none;">'
        );
        $("#keputusan-select").append(
            '<option value="1">Naik kelas</option><option value="2">Lulus</option><option value="0">Tinggal kelas</option>'
        );

        $("#modal-rapor").modal('show');

    });

    function submitForm(kelas, semester) {
        console.log(kelas, semester);
        $('input[name="id_kelas"]').val(kelas);
        $('input[name="id_semester"]').val(semester);

        var form = $('form#print');
        form.submit();
    }

    function changeKelas() {
        // console.log(dataLog); // global var dataLog
        var id_kelas = $('#id_kelas').val();
        // console.log(id_kelas);
        $('#id_semester').empty();

        $('#id_semester').append($("<option>")
            .attr("value", null)
            .attr('selected', 'selected')
            .attr('disabled', 'disabled')
            .text("-- Pilih Semester --")
        );

        dataLog.log_kelas.forEach(function(row) {
            if (row.id_kelas == id_kelas) {
                $("#id_semester").append('<option value="' + row.id_semester + '">' + row.nm_semester +
                    '</option>');
            }
        })
    }

    function changeSemester() {
        var txt_smt = $("select[name='id_semester'] option:selected").text();

        if (txt_smt == 'Genap' || txt_smt == 'genap' || txt_smt == 'GENAP') {
            $('.keputusan').css('display', 'block');
            $('.keputusan').attr('required', 'required');
            $('.keputusan').removeAttr('disabled');
        } else {
            $('.keputusan').css('display', 'none');
            $('.keputusan').removeAttr('required');
            $('.keputusan').attr('disabled', 'disabled');
        }
    }
</script>
