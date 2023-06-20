<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="block-header">
                <h2><a class="btn bg-blue waves-effect target-link"
                        href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sts') }}"><i
                            class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        Import Input Absensi Ekskul
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Download Template Excel
                            </h2>

                            <div class="form-group">
                                <div class="form-line">
                                    <label>Hari Exskul</label>
                                    <select class="form-control show-tick" name="hari" required="">
                                        <option value="7">Minggu</option>
                                        <option value="1">Senin</option>
                                        <option value="2">Selasa</option>
                                        <option value="3">Rabu</option>
                                        <option value="4">Kamis</option>
                                        <option value="5">Jumat</option>
                                        <option value="6">Sabtu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-line">
                                    <label>Tanggal</label>
                                    <input type="text" class="datetimepicker form-control" name="start_date"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ \Carbon\Carbon::today()->subDays(30)->format('Y-m-d') }}">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="form-line">
                                    <label>Sampai Tanggal</label>

                                    <input type="text" class="datetimepicker form-control" name="end_date"
                                        required="" aria-required="true" aria-invalid="true"
                                        value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="form-line">
                                    <label>Jam Mulai Ekskul</label>
                                    <input type="text" class="datepicker-time form-control" name="jam_mulai"
                                        required="" aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="form-line">
                                    <label>Jam Selesai Ekskul</label>
                                    <input type="text" class="datepicker-time form-control" name="jam_selesai"
                                        required="" aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>


                            <button type="button" class="btn bg-green waves-effect" onclick="filterAction()">

                                <i class="material-icons">cloud_download</i>
                                <span> Download Template Excel</span>
                            </button>

                        </div>
                        <div class="row clearfix">
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                {{-- <a class="btn btn-success" target="_blank"
                                    href="{{ asset('data-rekanan-magang.xlsx') }}">Download Template Excel</a> --}}
                                <h2 class="card-inside-title">
                                    Upload Data Absensi Ekskul
                                </h2>

                                <form id="form-upload"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/upload') }}"
                                    method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    Pilih File Excel
                                    <input type="file" name="file-excel" id="file-excel"
                                        accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
                                    <br>
                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                        <button class="btn btn-block bg-red waves-effect" type="submit">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Upload File Excel</span>
                                        </button>
                                        <br>
                                        <br>

                                        <p>
                                            <b>Peraturan Upload Data Excel</b>
                                            <br>
                                            * Hanya isi di kolom warna kuning.
                                            <br>
                                            * Status siswa hanya boleh diisi kode H,I,S,A
                                        </p>
                                    </div>
                                </form>

                            </div>

                        </div>
                        {{-- <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Petunjuk
                            </h2>
                            <ul>
                                <li>1. Buat Tambah Nilai</li>
                                <li>2. Download Template Exel</li>
                                <li>3. Isi Nilai, (Jangan merubah kolom lain kecuali kolom nilai)</li>
                                <li>4. Upload</li>
                            </ul>
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            vex.dialog.alert(response.message);
                        } else if (response.status == 201) {
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        } else if (response.status == 202) {
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });


    $(function() {
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: true
        });
    });

    function filterAction() {

        if (false) {
            swal({
                title: "Nama Pengguna Belum Dipilih",
                text: "Dimohon pilih pengguna yang akan dicari terlebih dahulu",
                type: "warning",
                confirmButtonColor: "#DD6B55",
            })
            return
        } else {
            var modul_url = 'absensi-ekskul';
            var id_semster = '{{ $id_semester }}';
            var id_ekskul = '{{ $id_ekskul }}';
            var excel_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-absensi-ekskul/excel/download/' +
                id_semster + '/' + id_ekskul + '/' + $('select[name=hari]').val() + '/' + $('input[name=start_date]')
                .val() + '/' + $('input[name=end_date]')
                .val() + '/' + $('input[name=jam_mulai]').val() + '/' + $('input[name=jam_selesai]').val();
            window.open(excel_url, "_blank");
        }
    }
</script>
